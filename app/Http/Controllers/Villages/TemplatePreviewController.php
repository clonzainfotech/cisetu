<?php

namespace App\Http\Controllers\Villages;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\State;
use App\Models\Village;
use Illuminate\Http\Request;

class TemplatePreviewController extends Controller
{
    public function show(Request $request, string $template)
    {
        // Mock a state
        $state = new State;
        $state->name_en = 'Maharashtra';

        // Mock a district
        $district = new District;
        $district->name_en = 'Mumbai City';
        $district->state = $state;

        // Mock a village
        $village = new Village;
        $village->name_en = 'Sample Village';
        $village->name_local = 'નમૂનારૂપ ગામ';
        $village->subdomain = 'sample';
        $village->portal_template = $template;
        $village->upi_id = 'sample@upi';
        $village->upi_name = 'Sample Merchant';
        $village->payment_note = 'Please pay your taxes online.';
        $village->whatsapp_number = '9876543210';
        $village->is_active = true;
        $village->district = $district;

        $view = 'portals.'.$template;

        if (! view()->exists($view)) {
            $view = 'portals.classic';
        }

        return view($view, [
            'village' => $village,
            'portalTheme' => $template,
            'isPreview' => true,
        ]);
    }
}
