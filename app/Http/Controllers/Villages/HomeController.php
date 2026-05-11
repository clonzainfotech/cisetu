<?php

namespace App\Http\Controllers\Villages;

use App\Http\Controllers\Controller;
use App\Models\Home;
use App\Models\Village;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $village = $request->get('village');

        if (! $village instanceof Village) {
            $user = $request->user();
            if ($user->village_id) {
                $village = $user->village;
            } else {
                return redirect()->route('dashboard')->with('toast', [
                    'type' => 'error',
                    'message' => 'Please select a village first to manage records.',
                ]);
            }
        }

        $search = $request->input('search');
        $limit = $request->input('limit', 10);

        $homes = Home::where('village_id', $village->id)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('property_no', 'like', "%{$search}%")
                        ->orWhere('house_no', 'like', "%{$search}%")
                        ->orWhere('owner', 'like', "%{$search}%")
                        ->orWhere('occupant', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->orderBy('property_no')
            ->paginate($limit)
            ->withQueryString();

        return Inertia::render('villages/homes/Index', [
            'homes' => $homes,
            'filters' => [
                'search' => $search,
                'limit' => (int) $limit,
            ],
            'village' => $village->load('plan'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $village = $request->get('village');
        if (! $village instanceof Village) {
            $village = $request->user()->village;
        }

        $validated = $request->validate([
            'property_no' => [
                'required',
                'string',
                'max:64',
                Rule::unique('homes')->where('village_id', $village->id),
            ],
            'house_no' => ['required', 'string', 'max:64'],
            'owner' => ['required', 'string', 'max:512'],
            'occupant' => ['required', 'string', 'max:512'],
            'address' => ['required', 'string'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        Home::create([
            'village_id' => $village->id,
            ...$validated,
        ]);

        return back()->with('toast', ['type' => 'success', 'message' => 'Property added successfully.']);
    }

    public function update(Request $request, Home $home): RedirectResponse
    {
        $village = $home->village;

        $validated = $request->validate([
            'property_no' => [
                'required',
                'string',
                'max:64',
                Rule::unique('homes')->where('village_id', $village->id)->ignore($home->id),
            ],
            'house_no' => ['required', 'string', 'max:64'],
            'owner' => ['required', 'string', 'max:512'],
            'occupant' => ['required', 'string', 'max:512'],
            'address' => ['required', 'string'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        $home->update($validated);

        return back()->with('toast', ['type' => 'success', 'message' => 'Property updated successfully.']);
    }

    public function destroy(Home $home): RedirectResponse
    {
        $home->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Property deleted successfully.']);
    }

    public function export(Request $request)
    {
        $village = $request->get('village') ?: $request->user()->village;
        $homes = Home::where('village_id', $village->id)->orderBy('property_no')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="homes_export.csv"',
        ];

        $callback = function () use ($homes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Property No', 'House No', 'Owner', 'Occupant', 'Address', 'Total Tax']);

            foreach ($homes as $home) {
                fputcsv($file, [
                    $home->property_no,
                    $home->house_no,
                    $home->owner,
                    $home->occupant,
                    $home->address,
                    $home->total,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function template()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="homes_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Property No', 'House No', 'Owner', 'Occupant', 'Address', 'Total Tax']);
            fputcsv($file, ['101', '23', 'Rajesh Patel', 'Rajesh Patel', 'Near Temple, Jinjuda', '500']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $village = $request->get('village') ?: $request->user()->village;
        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        // Skip header
        fgetcsv($handle);

        $count = 0;
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) < 6) {
                continue;
            }

            Home::updateOrCreate(
                ['village_id' => $village->id, 'property_no' => $data[0]],
                [
                    'house_no' => $data[1],
                    'owner' => $data[2],
                    'occupant' => $data[3],
                    'address' => $data[4],
                    'total' => (float) $data[5],
                ]
            );
            $count++;
        }
        fclose($handle);

        return back()->with('toast', ['type' => 'success', 'message' => "Successfully imported {$count} records."]);
    }
}
