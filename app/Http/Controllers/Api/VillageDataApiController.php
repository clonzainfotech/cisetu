<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Home;
use App\Models\Shop;
use App\Models\Village;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VillageDataApiController extends Controller
{
    /**
     * Authenticate the request via token and return the village.
     */
    private function getVillageByToken(Request $request): ?Village
    {
        $token = $request->header('X-Village-Token') ?: $request->query('token');

        if (!$token) {
            return null;
        }

        return Village::where('api_token', $token)->where('is_active', true)->first();
    }

    /**
     * Get details for a specific receipt (Shop or Home).
     */
    public function getDetails(Request $request): JsonResponse
    {
        $village = $this->getVillageByToken($request);

        if (!$village) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized or invalid village token',
            ], 401);
        }

        $receipt_no = $request->query('receipt');

        if (!$receipt_no) {
            return response()->json([
                'status' => false,
                'message' => 'Receipt number required',
            ], 422);
        }

        // Search in Shops first (starts with PE)
        if (str_starts_with($receipt_no, 'PE')) {
            $record = Shop::where('village_id', $village->id)
                ->where('reg_no', $receipt_no)
                ->first();
        } else {
            // Otherwise search in Homes
            $record = Home::where('village_id', $village->id)
                ->where('property_no', $receipt_no)
                ->first();
        }

        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Registration Number for this village',
            ], 404);
        }

        $amount = (float) $record->total;
        $upiId = $village->upi_id ?? "gramp96620757@barodampay";
        $name = $village->upi_name ?? "Gram Panchayat";
        $note = $village->payment_note ?? "Property Tax - {$receipt_no}";

        // UPI Payment Link
        $upiUrl = "upi://pay?pa={$upiId}&pn=" . urlencode($name) . "&am={$amount}&cu=INR&tn=" . urlencode($note);

        // QR Code URL
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($upiUrl);

        return response()->json([
            'status' => true,
            'message' => 'Get details successfully',
            'data' => [
                'village' => $village->name_en,
                'receipt' => $receipt_no,
                'amount' => $amount,
                'payment_url' => $upiUrl,
                'qr_code' => $qrUrl
            ]
        ]);
    }

    /**
     * Get all shops for the village.
     */
    public function shops(Request $request): JsonResponse
    {
        $village = $this->getVillageByToken($request);

        if (!$village) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $shops = Shop::where('village_id', $village->id)->paginate(50);

        return response()->json([
            'status' => true,
            'data' => $shops
        ]);
    }

    /**
     * Get all homes for the village.
     */
    public function homes(Request $request): JsonResponse
    {
        $village = $this->getVillageByToken($request);

        if (!$village) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $homes = Home::where('village_id', $village->id)->paginate(50);

        return response()->json([
            'status' => true,
            'data' => $homes
        ]);
    }
}
