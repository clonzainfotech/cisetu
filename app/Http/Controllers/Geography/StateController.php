<?php

namespace App\Http\Controllers\Geography;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class StateController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var User $actor */
        $actor = $request->user();

        abort_unless($actor->isSuperMasterAdmin(), 403);

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'limit' => ['nullable', 'integer', Rule::in([10, 25, 50, 100])],
        ]);

        $search = $validated['search'] ?? null;
        $limit = (int) ($validated['limit'] ?? 25);

        $states = State::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name_en', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('LOWER(name_en)')
            ->paginate($limit)
            ->withQueryString()
            ->through(fn (State $s) => [
                'id' => $s->id,
                'code' => $s->code,
                'name_en' => $s->name_en,
                'is_active' => $s->is_active,
            ]);

        return Inertia::render('geography/States', [
            'states' => $states,
            'filters' => [
                'search' => $search,
                'limit' => $limit,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        abort_unless($actor->isSuperMasterAdmin(), 403);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:8', 'unique:states,code'],
            'name_en' => ['required', 'string', 'max:128'],
        ]);

        State::query()->create([
            'code' => strtoupper($validated['code']),
            'name_en' => $validated['name_en'],
            'is_active' => true,
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('State added.')]);

        return to_route('states.index');
    }
}
