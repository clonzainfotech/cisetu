<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class InquiryManagementController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'search' => 'nullable|string',
            'status' => 'nullable|string',
            'type' => 'nullable|string',
        ]);

        $search = $validated['search'] ?? null;
        $status = ($validated['status'] ?? 'all') === 'all' ? null : $validated['status'];
        $type = ($validated['type'] ?? 'all') === 'all' ? null : $validated['type'];

        $inquiries = Inquiry::query()
            ->with(['plan'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('village_name', 'like', "%{$search}%")
                        ->orWhere('district_name', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($type, fn ($q) => $q->where('type', $type))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('admin/Inquiries', [
            'inquiries' => $inquiries,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'type' => $type,
            ],
        ]);
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,contacted,completed,rejected'],
        ]);

        $inquiry->update($validated);

        if ($validated['status'] === 'completed') {
            return redirect()->route('villages.index')->with('inquiry_prefill', $inquiry->toArray())->with('toast', ['type' => 'success', 'message' => 'Inquiry completed. Redirected to village creation.']);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Inquiry status updated.']);

        return back();
    }

    public function sendEmail(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $apiKey = env('ZEPTOMAIL_API_KEY');
        $authHeader = str_starts_with($apiKey, 'Zoho-enczapikey') ? $apiKey : 'Zoho-enczapikey ' . $apiKey;

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => $authHeader,
            'content-type' => 'application/json',
        ])->post('https://api.zeptomail.in/v1.1/email/template', [
            'template_key' => '2518b.1f7e2d7d3e304a0f.k1.22a8e481-4aa1-11f1-9e27-d2cf08f4ca8c.19e061eb2c6',
            'from' => [
                'address' => 'noreply@clonzainfotech.com',
                'name' => 'CISETU Operations'
            ],
            'to' => [
                [
                    'email_address' => [
                        'address' => $inquiry->email,
                        'name' => $inquiry->name
                    ]
                ]
            ],
            'merge_info' => [
                'message_body' => nl2br(e($validated['message'])),
                'subject' => $validated['subject']
            ]
        ]);

        if ($response->successful()) {
            $inquiry->update(['status' => 'contacted']);
            Inertia::flash('toast', ['type' => 'success', 'message' => 'Email sent successfully via ZeptoMail.']);
        } else {
            \Illuminate\Support\Facades\Log::error('ZeptoMail Send Failed', [
                'status' => $response->status(),
                'response' => $response->json(),
                'inquiry_id' => $inquiry->id
            ]);
            Inertia::flash('toast', ['type' => 'error', 'message' => 'Failed to send email. Check ZeptoMail configuration.']);
        }

        return back();
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $inquiry->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Inquiry deleted.']);

        return back();
    }

    protected function authorizeSuperAdmin(): void
    {
        abort_unless(auth()->user()->isSuperMasterAdmin(), 403);
    }
}
