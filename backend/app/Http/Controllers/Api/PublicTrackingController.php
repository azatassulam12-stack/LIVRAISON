<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicTrackingController extends Controller
{
    public function show(string $token): JsonResponse
    {
        $delivery = $this->trackable($token);
        return response()->json(['store' => ['name' => $delivery->store->name, 'phone' => $delivery->store->phone], 'delivery' => ['status' => $delivery->status, 'scheduled_for' => $delivery->scheduled_for, 'landmark' => $delivery->landmark, 'instructions' => $delivery->instructions, 'driver_name' => $delivery->status === 'en_route' ? $delivery->driver?->name : null]]);
    }

    public function confirmLocation(Request $request, string $token): JsonResponse
    {
        $delivery = $this->trackable($token);
        $data = $request->validate(['latitude' => ['nullable','numeric','between:-90,90','required_without:landmark'], 'longitude' => ['nullable','numeric','between:-180,180','required_with:latitude'], 'landmark' => ['nullable','string','max:500','required_without:latitude']]);
        $delivery->update(array_filter(['latitude' => $data['latitude'] ?? null, 'longitude' => $data['longitude'] ?? null, 'landmark' => $data['landmark'] ?? null], fn ($value) => $value !== null));
        return response()->json(['message' => 'Position enregistrée.']);
    }

    private function trackable(string $token): Delivery
    {
        $delivery = Delivery::with(['store', 'driver:id,name'])->where('tracking_token', $token)->firstOrFail();
        abort_unless($delivery->isTrackable(), 410, 'Ce lien de suivi a expiré ou a été désactivé.');
        return $delivery;
    }
}
