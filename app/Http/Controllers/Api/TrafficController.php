<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TrafficController extends Controller
{
    public function monitorTraffic(Request $request, $interface)
    {
        // Retrieve router information from session
        $router = $request->session()->get('active_router');

        // Ensure router information is available
        if (!$router) {
            return response()->json(['error' => 'Router information not found in session.'], 404);
        }

        // Extract router details from session
        $username = $router['username'];
        $password = $router['password'];
        $url = $router['ip_mikrotik'];

        // Call API to monitor traffic
        $response = $this->callApi($url, $username, $password, $interface);

        // Initialize data and labels
        $data = [];
        $labels = [];

        // Check if API response contains required data
        if ($response && isset($response[0]['rx-bits-per-second']) && isset($response[0]['tx-bits-per-second'])) {
            $timestamp = time() * 1000; // Using milliseconds
            $data['tx'] = [
                'value' => $response[0]['tx-bits-per-second'],
                'formatted' => $this->formatBits($response[0]['tx-bits-per-second'])
            ];
            $data['rx'] = [
                'value' => $response[0]['rx-bits-per-second'],
                'formatted' => $this->formatBits($response[0]['rx-bits-per-second'])
            ];
            $labels = ['TX', 'RX'];
        } else {
            // If response is invalid or missing required data, return error message
            $data = ['error' => 'No data available'];
        }

        // Return JSON response with data and labels
        return response()->json(['data' => $data, 'labels' => $labels]);
    }

    private function callApi($url, $username, $password, $interface)
    {
        // API URL to monitor traffic
        $apiUrl = $url . 'interface/monitor-traffic';

        // Call API with HTTP Client
        $response = Http::withBasicAuth($username, $password)->post($apiUrl, [
            'interface' => $interface,
            'duration' => '1s',
            '.proplist' => ['rx-bits-per-second', 'tx-bits-per-second']
        ]);

        // Return response as array
        return $response->json();
    }

    private function formatBits($bits)
    {
        // Function to format bits into a human-readable format
        // Implement your formatting logic here as needed
        return number_format($bits / 1000000, 2) . ' Mbps'; // Example: Convert to Mbps
    }
}
