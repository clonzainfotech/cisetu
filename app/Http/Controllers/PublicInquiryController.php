<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\SubscriptionPlan;
use App\Services\ZeptoMailService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicInquiryController extends Controller
{
    /**
     * Show the contact page.
     */
    public function index()
    {
        return Inertia::render('Contact', [
            'plans' => SubscriptionPlan::where('is_active', true)->get(),
        ]);
    }

    /**
     * Store a new inquiry (Demo or Subscription).
     */
    public function store(Request $request, ZeptoMailService $mailService)
    {
        $request->validate([
            'type' => ['required', 'in:demo,subscription'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'village_name' => ['required', 'string', 'max:255'],
            'district_name' => ['required', 'string', 'max:255'],
            'plan_id' => ['required_if:type,subscription', 'nullable', 'exists:subscription_plans,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $inquiry = Inquiry::create($request->all());

        $planName = 'N/A';
        if ($inquiry->plan_id) {
            $planName = SubscriptionPlan::find($inquiry->plan_id)?->name ?? 'N/A';
        }

        // Send Notification to Master Admin
        $mailService->sendTemplatedEmail([
            'user_email' => $inquiry->email,
            'user_name' => $inquiry->name,
            'user_phone' => $inquiry->phone ?? 'N/A',
            'village_name' => $inquiry->village_name,
            'district_name' => $inquiry->district_name,
            'plan_name' => $planName,
            'request_type' => ucfirst($inquiry->type).' Request',
            'message' => $inquiry->message ?? 'No message provided.',
            'submitted_at' => now()->format('M d, Y H:i'),
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Your '.$inquiry->type.' request has been submitted successfully! We will contact you soon.',
        ]);
    }
}
