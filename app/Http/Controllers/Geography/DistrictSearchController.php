<?php

namespace App\Http\Controllers\Geography;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DistrictSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'id' => ['nullable', 'integer', 'exists:districts,id'],
        ]);

        if (isset($validated['id'])) {
            $district = District::query()
                ->with('state:id,name_en')
                ->whereKey($validated['id'])
                ->first();

            return response()->json([
                'data' => $district ? [
                    $this->formatDistrict($district),
                ] : [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 1,
                    'total' => $district ? 1 : 0,
                ],
            ]);
        }

        $search = $validated['search'] ?? null;
        $page = (int) ($validated['page'] ?? 1);

        $districts = District::query()
            ->with('state:id,name_en')
            ->where('is_active', true)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('districts.name_en', 'like', "%{$search}%")
                        ->orWhere('districts.name_local', 'like', "%{$search}%")
                        ->orWhereHas('state', fn ($state) => $state->where('states.name_en', 'like', "%{$search}%"));
                });
            })
            ->orderByRaw('LOWER(districts.name_en)')
            ->paginate(perPage: 20, page: $page);

        return response()->json([
            'data' => $districts->map(fn (District $district) => $this->formatDistrict($district))->values(),
            'meta' => [
                'current_page' => $districts->currentPage(),
                'last_page' => $districts->lastPage(),
                'per_page' => $districts->perPage(),
                'total' => $districts->total(),
            ],
        ]);
    }

    /**
     * @return array{id: int, name_en: string, state_name_en: string}
     */
    private function formatDistrict(District $district): array
    {
        return [
            'id' => $district->id,
            'name_en' => $district->name_en,
            'state_name_en' => $district->state->name_en,
        ];
    }
}
