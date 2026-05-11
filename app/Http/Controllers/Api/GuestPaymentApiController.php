<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestPaymentApiController extends Controller
{
    public function payment(Request $request)
    {
        $village = $request->get('village');

        if (! $village) {
            return response()->json([
                'status' => false,
                'message' => 'Village context not found',
                'data' => [],
            ], 400);
        }

        $receipt_no = $request->receipt;

        if (! $receipt_no) {
            return response()->json([
                'status' => false,
                'message' => 'Receipt number required',
                'data' => [],
            ], 422);
        }

        // Search in the context of the current village
        if (str_starts_with($receipt_no, 'PE')) {
            $record = DB::table('shops')
                ->where('village_id', $village->id)
                ->where('reg_no', $receipt_no)
                ->first();
        } else {
            $record = DB::table('homes')
                ->where('village_id', $village->id)
                ->where('property_no', $receipt_no)
                ->first();
        }

        if (! $record) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Registration Number',
                'data' => [],
            ], 404);
        }

        $amount = (float) $record->total;

        $upiId = $village->upi_id ?? 'gramp96620757@barodampay';
        $name = $village->upi_name ?? $village->name_local;
        $note = "{$village->payment_note} - {$receipt_no}";

        // UPI Payment Link
        $upiUrl = "upi://pay?pa={$upiId}&pn=".urlencode($name)."&am={$amount}&cu=INR&tn=".urlencode($note);

        // QR Code URL
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.urlencode($upiUrl);

        return response()->json([
            'status' => true,
            'message' => 'Get details successfully',
            'data' => [
                'receipt' => $receipt_no,
                'amount' => $amount,
                'payment' => urlencode($upiUrl),
                'qr_code' => $qrUrl,
            ],
        ]);
    }
}
