<?php

namespace App\Http\Controllers\Villages;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Village;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
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

        $shops = Shop::where('village_id', $village->id)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reg_no', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('reg_no')
            ->paginate($limit)
            ->withQueryString();

        return Inertia::render('villages/shops/Index', [
            'shops' => $shops,
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
            'reg_no' => [
                'required',
                'string',
                'max:64',
                Rule::unique('shops')->where('village_id', $village->id),
            ],
            'name' => ['required', 'string', 'max:512'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        Shop::create([
            'village_id' => $village->id,
            ...$validated,
        ]);

        return back()->with('toast', ['type' => 'success', 'message' => 'Shop added successfully.']);
    }

    public function update(Request $request, Shop $shop): RedirectResponse
    {
        $village = $shop->village;

        $validated = $request->validate([
            'reg_no' => [
                'required',
                'string',
                'max:64',
                Rule::unique('shops')->where('village_id', $village->id)->ignore($shop->id),
            ],
            'name' => ['required', 'string', 'max:512'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        $shop->update($validated);

        return back()->with('toast', ['type' => 'success', 'message' => 'Shop updated successfully.']);
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        $shop->delete();

        return back()->with('toast', ['type' => 'success', 'message' => 'Shop deleted successfully.']);
    }

    public function export(Request $request)
    {
        $village = $request->get('village') ?: $request->user()->village;
        $shops = Shop::where('village_id', $village->id)->orderBy('reg_no')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="shops_export.csv"',
        ];

        $callback = function () use ($shops) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Reg No', 'Shop Name', 'Total Tax']);

            foreach ($shops as $shop) {
                fputcsv($file, [
                    $shop->reg_no,
                    $shop->name,
                    $shop->total,
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
            'Content-Disposition' => 'attachment; filename="shops_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Reg No', 'Shop Name', 'Total Tax']);
            fputcsv($file, ['S123', 'Shreeji General Store', '1200']);
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
            if (count($data) < 3) {
                continue;
            }

            Shop::updateOrCreate(
                ['village_id' => $village->id, 'reg_no' => $data[0]],
                [
                    'name' => $data[1],
                    'total' => (float) $data[2],
                ]
            );
            $count++;
        }
        fclose($handle);

        return back()->with('toast', ['type' => 'success', 'message' => "Successfully imported {$count} records."]);
    }
}
