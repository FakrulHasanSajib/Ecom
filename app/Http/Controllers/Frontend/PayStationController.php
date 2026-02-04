<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PayStationController extends Controller
{
    private $merchantId;
    private $password;
    private $baseUrl;

    public function __construct()
    {
        $this->merchantId = config('paystation.merchant_id');
        $this->password = config('paystation.merchant_password');
        $this->baseUrl = config('paystation.base_url', 'https://api.paystation.com.bd');
        $this->baseUrl = config('paystation.callback_url');
    }



    /**
     * Get authentication token from PayStation
     */
    private function getToken()
    {
        try {
            $response = Http::withHeaders([
                'merchantId' => $this->merchantId,
                'password' => $this->password,
            ])->post($this->baseUrl . '/grant-token');

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status_code'] == 200 && $data['status'] == 'success') {
                    return $data['token'];
                }
            }

            Log::error('PayStation token error', ['response' => $response->body()]);
            return null;
        } catch (Exception $e) {
            Log::error('PayStation token exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Create payment
     */
    public function createPayment($request, $order)
    {
        $token = $this->getToken();

        if (!$token) {
            throw new Exception('Unable to get PayStation token');
        }

        try {
            $payload = [
                'token' => $token,
                'invoice_number' => $order->invoice_id,
                'currency' => 'BDT',
                'payment_amount' => $order->amount,
                'reference' => 'Order Payment',
                'cust_name' => $request->name,
                'cust_phone' => $request->phone,
                'cust_email' => 'customer@gmail.com',
                'cust_address' => $request->address ?? '',
                'callback_url' => $this->callbackUrl,
                'opt_a' => $order->id
            ];

            $response = Http::post($this->baseUrl . '/create-payment', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status_code'] == 200 && $data['status'] == 'success') {
                    return [
                        'success' => true,
                        'payment_url' => $data['payment_url'],
                        'message' => $data['message'],
                    ];
                }
            }

            Log::error('PayStation payment creation failed', ['response' => $response->body()]);
            return [
                'success' => false,
                'message' => 'Payment creation failed',
            ];
        } catch (Exception $e) {
            Log::error('PayStation payment exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Verify payment status
     */
    public function verifyPayment($invoiceNumber)
    {
        $token = $this->getToken();

        if (!$token) {
            return null;
        }

        try {
            $response = Http::post($this->baseUrl . '/check-payment', [
                'token' => $token,
                'invoice_number' => $invoiceNumber,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status_code'] == 200 && $data['status'] == 'success') {
                    return $data['data'];
                }
            }

            Log::error('PayStation verification failed', ['response' => $response->body()]);
            return null;
        } catch (Exception $e) {
            Log::error('PayStation verification exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
}