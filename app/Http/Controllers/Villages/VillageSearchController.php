<?php

namespace App\Http\Controllers\Villages;

use App\Http\Controllers\Controller;
use App\Models\Village;
use Illuminate\Http\Request;

class VillageSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        abort_unless($request->user()?->isSuperMasterAdmin(), 403);

        $query = trim((string) $request->string('q'));

        if ($query === '') {
            return [];
        }

        return Village::query()
            ->where(function ($q) {
                $q->whereNull('is_active')->orWhere('is_active', true);
            })
            ->where(function ($q) use ($query) {
                $q->where('name_en', 'like', "%{$query}%")
                    ->orWhere('subdomain', 'like', "%{$query}%");
            })
            ->orderBy('name_en')
            ->limit(20)
            ->get(['id', 'name_en', 'subdomain']);
    }
}
