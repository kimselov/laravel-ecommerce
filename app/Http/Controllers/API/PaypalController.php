<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    private $clientId;
    private $secret;
    private $baseUrl;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->secret = config('services.paypal.secret');
        // $this->baseUrl = config('services.paypal.base_url');
    }

    private function getAccessToken()
    {
        $response = Http::withBasicAuth($this->clientId, $this->secret)
            ->asForm()
            ->post($this->baseUrl . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        return $response->json()['access_token'] ?? null;
    }

    public function createOrder(Request $request)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)->post($this->baseUrl . '/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => $request->total,
                ]
            ]],
            'application_context' => [
                'return_url' => 'https://your-site.com/success',
                'cancel_url' => 'https://your-site.com/cancel',
            ]
        ]);

        return response()->json($response->json());
    }

    public function captureOrder(Request $request)
    {
        $accessToken = $this->getAccessToken();
        $orderId = $request->order_id;

        $response = Http::withToken($accessToken)
            ->post($this->baseUrl . "/v2/checkout/orders/{$orderId}/capture");

        $data = $response->json();

        // Handle your order saving logic here

        return response()->json($data);
    }
}
