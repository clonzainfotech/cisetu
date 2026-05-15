<?php

namespace App\Actions\Villages;

use App\Models\Home;
use App\Models\Shop;
use App\Models\Team;
use App\Models\User;
use App\Models\Village;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteVillage
{
    public function handle(Village $village): void
    {
        DB::transaction(function () use ($village): void {
            Home::query()->where('village_id', $village->id)->delete();
            Shop::query()->where('village_id', $village->id)->delete();

            User::query()
                ->where('village_id', $village->id)
                ->each(fn (User $user) => $this->deleteUser($user));

            if ($village->logo) {
                Storage::disk('public')->delete($village->logo);
            }

            $village->delete();
        });
    }

    private function deleteUser(User $user): void
    {
        $user->ownedTeams()->each(function (Team $team): void {
            User::query()
                ->where('current_team_id', $team->id)
                ->update(['current_team_id' => null]);

            $team->invitations()->delete();
            $team->memberships()->delete();
            $team->forceDelete();
        });

        $user->teamMemberships()->delete();

        $user->delete();
    }
}
