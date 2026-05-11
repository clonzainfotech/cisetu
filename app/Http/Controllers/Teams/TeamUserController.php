<?php

namespace App\Http\Controllers\Teams;

use App\Enums\TeamRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\CreateTeamUserRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class TeamUserController extends Controller
{
    public function index(Team $current_team): Response
    {
        Gate::authorize('view', $current_team);

        $members = $current_team->members()
            ->select(['users.id', 'users.name', 'users.email'])
            ->withPivot(['role'])
            ->orderByRaw('LOWER(users.name)')
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->pivot->role,
            ]);

        $maxUserAccounts = $current_team->maxUserAccounts();
        $currentUserAccounts = $members->count();

        $canCreate = Gate::check('addMember', $current_team)
            && ($maxUserAccounts === null || $currentUserAccounts < $maxUserAccounts);

        return Inertia::render('teams/users/Index', [
            'team' => $current_team,
            'members' => $members,
            'availableRoles' => TeamRole::assignable(),
            'limits' => [
                'maxUserAccounts' => $maxUserAccounts,
                'currentUserAccounts' => $currentUserAccounts,
            ],
            'canCreate' => $canCreate,
        ]);
    }

    public function store(CreateTeamUserRequest $request, Team $current_team): RedirectResponse
    {
        Gate::authorize('addMember', $current_team);

        $maxUserAccounts = $current_team->maxUserAccounts();
        $currentUserAccounts = $current_team->memberships()->count();

        abort_if($maxUserAccounts !== null && $currentUserAccounts >= $maxUserAccounts, 403);

        $user = User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        $current_team->memberships()->create([
            'user_id' => $user->id,
            'role' => TeamRole::from($request->validated('role')),
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('User created.')]);

        return to_route('team-users.index', ['current_team' => $current_team->slug]);
    }
}
