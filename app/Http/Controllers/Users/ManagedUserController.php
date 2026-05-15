<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateManagedUserRequest;
use App\Http\Requests\Users\UpdateManagedUserRequest;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ManagedUserController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var User $actor */
        $actor = $request->user();

        abort_unless($actor->isSuperMasterAdmin() || $actor->isVillageAdmin(), 403);

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'limit' => ['nullable', 'integer', Rule::in([10, 25, 50, 100])],
        ]);

        $search = $validated['search'] ?? null;
        $limit = $this->resolvePerPageLimit($request);

        $villages = $actor->isSuperMasterAdmin()
            ? Village::query()->select(['id', 'name_en'])->orderBy('name_en')->get()
            : collect();

        $villageId = $actor->isSuperMasterAdmin() ? null : $actor->village_id;

        $users = User::query()
            ->when($villageId, fn ($q) => $q->where('village_id', $villageId))
            ->where('is_super_master_admin', false)
            ->where('role', '!=', 'super_master_admin')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->select(['id', 'name', 'email', 'role', 'village_id', 'permissions'])
            ->orderByRaw('LOWER(name)')
            ->paginate($limit)
            ->withQueryString()
            ->appends(array_filter([
                'search' => $search,
                'limit' => $limit,
            ], fn ($value) => $value !== null && $value !== ''));

        $village = $actor->isVillageAdmin() ? $actor->village()->select(['id', 'name_en', 'subscription_plan_id'])->with('plan')->first() : null;

        $maxUserAccounts = $village?->maxUserAccounts();
        $currentUserAccounts = $village ? User::where('village_id', $village->id)->count() : null;

        return Inertia::render('users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $search,
                'limit' => $limit,
            ],
            'villages' => $villages,
            'currentVillage' => $village,
            'limits' => [
                'maxUserAccounts' => $maxUserAccounts,
                'currentUserAccounts' => $currentUserAccounts,
            ],
            'actor' => [
                'id' => $actor->id,
                'isSuperMasterAdmin' => $actor->isSuperMasterAdmin(),
                'isVillageAdmin' => $actor->isVillageAdmin(),
            ],
        ]);
    }

    public function store(CreateManagedUserRequest $request): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        abort_unless($actor->isSuperMasterAdmin() || $actor->isVillageAdmin(), 403);

        $role = $request->validated('role');

        $villageId = $actor->isSuperMasterAdmin()
            ? (int) $request->validated('village_id')
            : (int) $actor->village_id;

        if ($actor->isVillageAdmin()) {
            abort_if($role !== 'user', 403);
        }

        $village = Village::query()->with(['subscription.plan'])->findOrFail($villageId);

        $maxUserAccounts = $village->maxUserAccounts();
        $currentUserAccounts = User::query()->where('village_id', $village->id)->count();

        abort_if($maxUserAccounts !== null && $currentUserAccounts >= $maxUserAccounts, 403);

        User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'email_verified_at' => now(),
            'password' => Hash::make($request->validated('password')),
            'role' => $role,
            'village_id' => $village->id,
            'is_super_master_admin' => false,
            'permissions' => $request->validated('permissions'),
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('User created.')]);

        return to_route('managed-users.index');
    }

    public function update(UpdateManagedUserRequest $request, User $user): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        $this->authorizeManage($actor, $user);

        $villageId = $actor->isSuperMasterAdmin()
            ? (int) $request->validated('village_id')
            : (int) $user->village_id;

        $role = $request->validated('role');

        if ($actor->isVillageAdmin()) {
            abort_if($role !== 'user', 403);
        }

        $attributes = [
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'role' => $role,
            'village_id' => $villageId,
            'permissions' => $request->validated('permissions'),
        ];

        if ($password = $request->validated('password')) {
            $attributes['password'] = Hash::make($password);
        }

        $user->update($attributes);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('User updated.')]);

        return to_route('managed-users.index');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        $this->authorizeManage($actor, $user);

        $user->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('User deleted.')]);

        return to_route('managed-users.index');
    }

    protected function authorizeManage(User $actor, User $target): void
    {
        abort_unless($actor->isSuperMasterAdmin() || $actor->isVillageAdmin(), 403);

        abort_if($target->isSuperMasterAdmin() || $target->role === 'super_master_admin', 403);

        abort_if($actor->id === $target->id, 403);

        if ($actor->isVillageAdmin()) {
            abort_unless($target->village_id === $actor->village_id, 403);
            abort_unless($target->role === 'user', 403);
        }
    }
}
