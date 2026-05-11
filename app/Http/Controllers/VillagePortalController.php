<?php

namespace App\Http\Controllers;

use App\Models\Village;
use Illuminate\Http\Request;

class VillagePortalController extends Controller
{
    public function show(Request $request)
    {
        $village = $request->get('village');

        if (is_string($village)) {
            $village = Village::where('subdomain', $village)->first();
        }

        if (! $village instanceof Village) {
            return redirect(config('app.url'));
        }

        $template = $village->portal_template ?: 'classic';
        $view = 'portals.'.$template;

        if (! view()->exists($view)) {
            $view = 'portals.classic';
        }

        return view($view, [
            'village' => $village->load('district.state'),
            'portalTheme' => $template,
        ]);
    }
}
