<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\OrderStatus;
use App\Models\Courierapi;
use Image;
use File;
use Toastr;
use Exception;
use Illuminate\Support\Facades\Log;
class OrderStatusController extends Controller
{
    
    public function index(Request $request)
    {
        $data = OrderStatus::orderBy('id','DESC')->get();
        return view('backEnd.orderstatus.index',compact('data'));
    }
    public function create()
    {
        return view('backEnd.orderstatus.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);
        $input = $request->all();
        $input['slug'] = strtolower(preg_replace('/\s+/u', '-', trim($request->name)));
        OrderStatus::create($input);
        Toastr::success('Success','Data insert successfully');
        return redirect()->route('orderstatus.index');
    }
    
    public function edit($id)
    {
        $edit_data = OrderStatus::find($id);
        return view('backEnd.orderstatus.edit',compact('edit_data'));
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $update_data = OrderStatus::find($request->id);
        $input = $request->all();
        $input['status'] = $request->status?1:0;
        $update_data->update($input);

        Toastr::success('Success','Data update successfully');
        return redirect()->route('orderstatus.index');
    }
 
    public function inactive(Request $request)
    {
        $inactive = OrderStatus::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = OrderStatus::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = OrderStatus::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    
    public function fraudorder(){
        return view('backEnd.fraud.newcourier');
    }
    public function checkDeliveryStatus(Request $request)
    {

        // Pathao API credentials
        $client_id = "open5A7d7A";
        $client_secret = "oItLl3iQSUOzTPnbK4RDsVYjCVKluaGU7WRmRL93";


        $this->validate($request, [
            'mobile_number' => 'required|digits:11',
        ]);

        $mobile_number = $request->input('mobile_number');

        // Step 2: Get Access Token from Pathao API
        $token_url = 'https://api-hermes.pathao.com/oauth/token';
        $data = [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => 'client_credentials',
        ];


        // Make a POST request to get the access token
        $response = Http::asForm()->post($token_url, $data);

        if (!$response) {
            return response()->json(['error' => 'Failed to authenticate with Pathao API.'], 500);
        }

        $access_token = $response->json()['access_token'];

        // Step 3: Call Pathao API to get delivery status
        $status_url = 'https://api-hermes.pathao.com/v1/delivery/get_user_success_rate';

        // Make a POST request to get delivery status
        $status_response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $access_token,
        ])->post($status_url, [
            'mobile_number' => $mobile_number,
        ]);

        if ($status_response->failed()) {
            return response()->json(['error' => 'Failed to retrieve delivery status.'], 500);
        }

        // Return the response to the frontend
        return response()->json($status_response->json());
    }
    
     public function combinedFraudCheck(Request $request)
    {
        
        // Validate request
        $request->validate([
            'number' => 'required|string|min:10|max:15'
        ]);

        $phoneNumber = $request->number;
        
        try {
            // Get both API configurations
            $pathaoInfo = $this->getPathaoConfig();
            $steadfastInfo = $this->getSteadfastConfig();
            
            // Perform both fraud checks in parallel
            $pathaoResult = $this->checkPathaoFraud($pathaoInfo, $phoneNumber);
            $steadfastResult = $this->checkSteadfastFraud($steadfastInfo, $phoneNumber);
            
            // Combine and analyze results
            $combinedResult = $this->analyzeCombinedResults($pathaoResult, $steadfastResult, $phoneNumber);
            
            return response()->json($combinedResult);
            
        } catch (Exception $e) {
            Log::error('Combined fraud check failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Fraud check service unavailable',
                'message' => 'Please try again later',
                'phone_number' => $phoneNumber,
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Get Pathao API configuration
     */
    private function getPathaoConfig()
    {
        return Courierapi::where(['status' => 1, 'type' => 'pathao'])
            ->select('id', 'type', 'url', 'token', 'status')
            ->first();
    }

    /**
     * Get Steadfast API configuration
     */
private function getSteadfastConfig()
{
    
    return Courierapi::where(['status' => 1, 'type' => 'steadfast'])
        ->select('id', 'type', 'url', 'token', 'api_key', 'secret_key', 'status')
        ->first();
}

    /**
     * Check fraud using Pathao API
     */
    private function checkPathaoFraud($pathaoInfo, $phoneNumber)
    {
        if (!$pathaoInfo) {
            return [
                'provider' => 'pathao',
                'success' => false,
                'error' => 'Pathao API configuration not found',
                'data' => null
            ];
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $pathaoInfo->token,
                    'Content-Type' => 'application/json'
                ])
                ->post($pathaoInfo->url . '/v1/delivery/get_user_success_rate', [
                        'mobile_number' => $phoneNumber
                    ]);

            if ($response->successful()) {
                return [
                    'provider' => 'pathao',
                    'success' => true,
                    'data' => $response->json(),
                    'error' => null,
                    'status_code' => $response->status()
                ];
            } else {
                return [
                    'provider' => 'pathao',
                    'success' => false,
                    'error' => 'API request failed',
                    'data' => null,
                    'status_code' => $response->status()
                ];
            }
        } catch (Exception $e) {
            return [
                'provider' => 'pathao',
                'success' => false,
                'error' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Check fraud using Steadfast API
     */
private function checkSteadfastFraud($steadfastInfo, $phoneNumber)
{
    if (!$steadfastInfo) {
        return [
            'provider' => 'steadfast',
            'success' => false,
            'error' => 'Steadfast API configuration not found',
            'data' => null
        ];
    }

    try {
        // URL এর শেষে বাড়তি স্লাশ থাকলে তা বাদ দেওয়া
        $apiUrl = rtrim($steadfastInfo->url, '/'); 
        
        $response = Http::timeout(30)
            ->withHeaders([
                'Api-Key' => $steadfastInfo->api_key,
                'Secret-Key' => $steadfastInfo->secret_key,
                'Accept' => 'application/json',
            ])
            ->get($apiUrl . '/fraud_check/' . $phoneNumber);

        if ($response->successful()) {
            return [
                'provider' => 'steadfast',
                'success' => true,
                'data' => $response->json(),
                'error' => null,
                'status_code' => $response->status()
            ];
        } else {
            return [
                'provider' => 'steadfast',
                'success' => false,
                'error' => 'API request failed',
                'data' => null,
                'status_code' => $response->status()
            ];
        }
    } catch (Exception $e) {
        return [
            'provider' => 'steadfast',
            'success' => false,
            'error' => $e->getMessage(),
            'data' => null
        ];
    }
}

    /**
     * Analyze and combine results from both providers
     */
    private function analyzeCombinedResults($pathaoResult, $steadfastResult, $phoneNumber)
    {
        $combinedResult = [
            'phone_number' => $phoneNumber,
            'timestamp' => now()->toISOString(),
            'providers' => [
                'pathao' => $pathaoResult,
                'steadfast' => $steadfastResult
            ],
            'combined_analysis' => [
                'risk_level' => 'unknown',
                'is_fraud' => false,
                'confidence' => 0,
                'reasons' => [],
                'recommendation' => 'manual_review'
            ]
        ];

        $fraudCount = 0;
        $successfulChecks = 0;
        $totalRiskScore = 0;

        // Analyze Pathao results
        if ($pathaoResult['success'] && isset($pathaoResult['data'])) {
            $successfulChecks++;
            $pathaoData = $pathaoResult['data'];
            
            // Check if Pathao indicates fraud (adjust based on actual response structure)
            if (isset($pathaoData['is_fraud']) && $pathaoData['is_fraud']) {
                $fraudCount++;
                $totalRiskScore += $pathaoData['risk_score'] ?? 75;
                $combinedResult['combined_analysis']['reasons'][] = 'Pathao detected fraud risk';
            } elseif (isset($pathaoData['risk_score']) && $pathaoData['risk_score'] > 70) {
                $fraudCount++;
                $totalRiskScore += $pathaoData['risk_score'];
                $combinedResult['combined_analysis']['reasons'][] = 'Pathao high risk score';
            }
        }

        // Analyze Steadfast results
        if ($steadfastResult['success'] && isset($steadfastResult['data'])) {
            $successfulChecks++;
            $steadfastData = $steadfastResult['data'];
            
            // Check if Steadfast indicates fraud (adjust based on actual response structure)
            if (isset($steadfastData['is_fraud']) && $steadfastData['is_fraud']) {
                $fraudCount++;
                $totalRiskScore += $steadfastData['risk_score'] ?? 75;
                $combinedResult['combined_analysis']['reasons'][] = 'Steadfast detected fraud risk';
            } elseif (isset($steadfastData['risk_score']) && $steadfastData['risk_score'] > 70) {
                $fraudCount++;
                $totalRiskScore += $steadfastData['risk_score'];
                $combinedResult['combined_analysis']['reasons'][] = 'Steadfast high risk score';
            }
        }

        // Determine combined risk assessment
        if ($successfulChecks === 0) {
            $combinedResult['combined_analysis']['risk_level'] = 'unknown';
            $combinedResult['combined_analysis']['confidence'] = 0;
            $combinedResult['combined_analysis']['recommendation'] = 'api_unavailable';
        } else {
            $fraudPercentage = ($fraudCount / $successfulChecks) * 100;
            $avgRiskScore = $fraudCount > 0 ? $totalRiskScore / $fraudCount : 0;

            if ($fraudCount >= 2) {
                // Both providers indicate fraud
                $combinedResult['combined_analysis']['risk_level'] = 'high';
                $combinedResult['combined_analysis']['is_fraud'] = true;
                $combinedResult['combined_analysis']['confidence'] = 95;
                $combinedResult['combined_analysis']['recommendation'] = 'reject';
            } elseif ($fraudCount === 1) {
                // One provider indicates fraud
                $combinedResult['combined_analysis']['risk_level'] = 'medium';
                $combinedResult['combined_analysis']['is_fraud'] = false;
                $combinedResult['combined_analysis']['confidence'] = 65;
                $combinedResult['combined_analysis']['recommendation'] = 'manual_review';
            } else {
                // No fraud detected
                $combinedResult['combined_analysis']['risk_level'] = 'low';
                $combinedResult['combined_analysis']['is_fraud'] = false;
                $combinedResult['combined_analysis']['confidence'] = 90;
                $combinedResult['combined_analysis']['recommendation'] = 'approve';
            }

            $combinedResult['combined_analysis']['fraud_percentage'] = $fraudPercentage;
            $combinedResult['combined_analysis']['average_risk_score'] = $avgRiskScore;
        }

        // Add summary
        $combinedResult['summary'] = [
            'total_providers_checked' => 2,
            'successful_checks' => $successfulChecks,
            'failed_checks' => 2 - $successfulChecks,
            'fraud_indicators' => $fraudCount,
            'final_recommendation' => $combinedResult['combined_analysis']['recommendation']
        ];

        return $combinedResult;
    }

    /**
     * Alternative method: Check individual provider
     */
    public function checkSingleProvider(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'provider' => 'required|in:pathao,steadfast'
        ]);

        $phoneNumber = $request->number;
        $provider = $request->provider;

        try {
            if ($provider === 'pathao') {
                $config = $this->getPathaoConfig();
                $result = $this->checkPathaoFraud($config, $phoneNumber);
            } else {
                $config = $this->getSteadfastConfig();
                $result = $this->checkSteadfastFraud($config, $phoneNumber);
            }

            return response()->json($result);
            
        } catch (Exception $e) {
            Log::error("Single provider fraud check failed: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Service unavailable',
                'provider' => $provider,
                'phone_number' => $phoneNumber
            ], 500);
        }
    }
}