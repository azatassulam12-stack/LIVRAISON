<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\DeliveryStatusHistory;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeliveryController extends Controller
{
    private const STATUSES = ['pending','collected','waiting','on_tour','en_route','arrived_in_area','delivered','customer_absent','customer_unreachable','address_not_found','refused','rescheduled','cancelled'];

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Delivery::query()->with('driver:id,name,phone')->latest('scheduled_for');
        if ($user->role !== User::PLATFORM_ADMIN) $query->where('store_id', $user->store_id);
        if ($user->isDriver()) $query->where('driver_id', $user->id);
        return response()->json($query->paginate(30));
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless($request->user()->isStoreAdmin(), 403);
        $delivery = Delivery::create(array_merge($this->validatedDelivery($request), ['store_id' => $request->user()->store_id, 'tracking_token' => (string) Str::uuid(), 'tracking_expires_at' => now()->addDays(14)]));
        $this->history($delivery, $request->user()->id, null, 'pending');
        return response()->json($delivery, 201);
    }

    public function update(Request $request, Delivery $delivery): JsonResponse
    {
        $this->assertStoreAdminOwns($request, $delivery);
        abort_if(in_array($delivery->status, ['delivered', 'cancelled']), 422, 'Une livraison terminée ou annulée ne peut pas être modifiée.');
        $delivery->update($this->validatedDelivery($request, false));
        return response()->json($delivery->fresh());
    }

    public function assign(Request $request, Delivery $delivery): JsonResponse
    {
        $this->assertStoreAdminOwns($request, $delivery);
        $data = $request->validate(['driver_id' => ['required','integer']]);
        $driver = User::where('id', $data['driver_id'])->where('store_id', $delivery->store_id)->where('role', User::DRIVER)->where('is_active', true)->firstOrFail();
        $delivery->update(['driver_id' => $driver->id]);
        return response()->json($delivery->fresh('driver'));
    }

    public function updateStatus(Request $request, Delivery $delivery): JsonResponse
    {
        $user = $request->user();
        abort_unless(($user->isDriver() && $delivery->driver_id === $user->id) || ($user->isStoreAdmin() && $delivery->store_id === $user->store_id), 403);
        $data = $request->validate(['status' => ['required','in:'.implode(',', self::STATUSES)], 'reason' => ['nullable','string','max:1000'], 'collected_amount' => ['nullable','numeric','min:0'], 'payment_method' => ['nullable','string','max:50']]);
        $from = $delivery->status;
        $delivery->update(['status' => $data['status'], 'status_reason' => $data['reason'] ?? null, 'collected_amount' => $data['collected_amount'] ?? $delivery->collected_amount, 'payment_method' => $data['payment_method'] ?? $delivery->payment_method, 'delivered_at' => $data['status'] === 'delivered' ? now() : $delivery->delivered_at]);
        $this->history($delivery, $user->id, $from, $data['status'], $data['reason'] ?? null);
        return response()->json($delivery->fresh());
    }

    private function validatedDelivery(Request $request, bool $creating = true): array
    {
        $required = $creating ? 'required' : 'sometimes';
        return $request->validate(['customer_name' => [$required,'string','max:120'], 'customer_phone' => [$required,'string','max:30'], 'item_description' => [$required,'string','max:2000'], 'quantity' => ['nullable','integer','min:1'], 'amount_due' => [$required,'numeric','min:0'], 'delivery_address' => [$required,'string','max:2000'], 'district' => ['nullable','string','max:120'], 'landmark' => ['nullable','string','max:500'], 'instructions' => ['nullable','string','max:2000'], 'latitude' => ['nullable','numeric','between:-90,90'], 'longitude' => ['nullable','numeric','between:-180,180'], 'scheduled_for' => [$required,'date']]);
    }
    private function assertStoreAdminOwns(Request $request, Delivery $delivery): void { abort_unless($request->user()->isStoreAdmin() && $request->user()->store_id === $delivery->store_id, 403); }
    private function history(Delivery $delivery, ?int $by, ?string $from, string $to, ?string $reason = null): void { DeliveryStatusHistory::create(['delivery_id' => $delivery->id, 'changed_by' => $by, 'from_status' => $from, 'to_status' => $to, 'reason' => $reason]); }
}
