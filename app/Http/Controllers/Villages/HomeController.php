<?php

namespace App\Http\Controllers\Villages;

use App\Http\Controllers\Controller;
use App\Models\Home;
use App\Models\Village;
use App\Services\Imports\HomeImportService;
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
        $limit = $this->resolvePerPageLimit($request);

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
            ->withQueryString()
            ->appends(array_filter([
                'search' => $search,
                'limit' => $limit,
            ], fn ($value) => $value !== null && $value !== ''));

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

    public function import(Request $request, HomeImportService $homeImportService): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:10240'],
            'use_ai' => ['sometimes', 'boolean'],
        ]);

        $village = $request->get('village') ?: $request->user()->village;

        $result = $homeImportService->import(
            $village,
            $request->file('file'),
            $request->boolean('use_ai', true),
        );

        if ($result->imported === 0) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'No valid property rows found. Check the file format or enable AI mapping with OPENAI_API_KEY.',
            ]);
        }

        $methodLabel = match ($result->method) {
            'ai-full' => 'AI',
            'ai-columns' => 'AI column mapping',
            default => 'auto-detect',
        };

        return back()->with('toast', [
            'type' => 'success',
            'message' => "Successfully imported {$result->imported} properties ({$methodLabel}).",
        ]);
    }
}
