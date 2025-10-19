<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    private function createOrder()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'customer_id' => $user->id,
        ]);
        Payment::factory()->create([
            'order_id' => $order->id,
        ]);

        return $order;
    }

    private function createPayload($order, $transactionStatus)
    {
        $payload = [
            'order_id' => $order->order_number,
            'status_code' => '200',
            'gross_amount' => $order->total_amount . '.00',
            'transaction_status' => $transactionStatus,
            'fraud_status' => 'accept',
        ];
        $payload['signature_key'] = hash('sha512', $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . config('midtrans.server_key'));

        return $payload;
    }

    public function test_webhook_handles_settlement()
    {
        $order = $this->createOrder();
        $payload = $this->createPayload($order, 'settlement');

        $response = $this->postJson('/api/v1/webhook/midtrans', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'processing',
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'paid',
        ]);
    }

    public function test_webhook_handles_pending()
    {
        $order = $this->createOrder();
        $payload = $this->createPayload($order, 'pending');

        $response = $this->postJson('/api/v1/webhook/midtrans', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'pending',
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'pending',
        ]);
    }

    public function test_webhook_handles_deny()
    {
        $order = $this->createOrder();
        $payload = $this->createPayload($order, 'deny');

        $response = $this->postJson('/api/v1/webhook/midtrans', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'failed',
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'failed',
        ]);
    }

    public function test_webhook_handles_cancel()
    {
        $order = $this->createOrder();
        $payload = $this->createPayload($order, 'cancel');

        $response = $this->postJson('/api/v1/webhook/midtrans', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'cancelled',
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_webhook_handles_expire()
    {
        $order = $this->createOrder();
        $payload = $this->createPayload($order, 'expire');

        $response = $this->postJson('/api/v1/webhook/midtrans', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'cancelled',
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_webhook_handles_refund()
    {
        $order = $this->createOrder();
        $payload = $this->createPayload($order, 'refund');

        $response = $this->postJson('/api/v1/webhook/midtrans', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'refunded',
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'refunded',
        ]);
    }

    public function test_webhook_handles_invalid_signature()
    {
        $order = $this->createOrder();
        $payload = $this->createPayload($order, 'settlement');
        $payload['signature_key'] = 'invalid-signature';

        $response = $this->postJson('/api/v1/webhook/midtrans', $payload);

        $response->assertStatus(400);
        $response->assertJson(['status' => 'error', 'message' => 'Invalid signature']);
    }
}
