<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Village;
use App\Services\ZeptoMailService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    /**
     * Show the contact page for subscription renewal/inquiry.
     */
    public function index(Request $request): Response
    {
        /** @var Village|null $village */
        $village = $request->get('village');

        return Inertia::render('subscriptions/Contact', [
            'village' => $village ? [
                'id' => $village->id,
                'name_en' => $village->name_en,
                'subdomain' => $village->subdomain,
            ] : null,
            'plans' => [
                ['id' => 'vikas', 'name' => 'CI Vikas'],
                ['id' => 'pragati', 'name' => 'CI Pragati'],
            ],
            'error' => session('error'),
        ]);
    }

    /**
     * Submit the subscription request.
     */
    public function store(Request $request, ZeptoMailService $mailService)
    {
        $request->validate([
            'plan' => ['required', 'string'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        /** @var Village $village */
        $village = $request->get('village');

        /** @var User $user */
        $user = $request->user();

        $planName = $request->plan === 'vikas' ? 'CI Vikas' : 'CI Pragati';

        // Send Zeptomail notification to Master Admin
        $mailService->sendRenewalRequest([
            'user_email' => $user->email,
            'user_name' => $user->name,
            'subdomain' => $village?->subdomain ?? 'N/A',
            'village_name' => $village?->name_en ?? 'N/A',
            'message' => $request->message,
            'plan_name' => $planName,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Renewal request sent! The Master Admin will contact you at '.$user->email.' with payment details.',
        ]);

        return back();
    }
}
