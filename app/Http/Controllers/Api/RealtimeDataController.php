<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RealtimeDataController extends Controller
{
    public function getRealtimeData(Request $request)
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

        // API URLs to fetch real-time data
        $apiUrls = [
            'resource' => $url . '/system/resource',
            'clock' => $url . '/system/clock', // Example URL for clock data
        ];

        // Array to store fetched data
        $responseData = [];

        // Fetch data from each API endpoint
        foreach ($apiUrls as $key => $apiUrl) {
            $response = Http::withBasicAuth($username, $password)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                // Perform specific processing based on API endpoint
                switch ($key) {
                    case 'resource':
                        // Format uptime for 'resource' endpoint
                        $uptimeFormatted = $this->formatUptime($data['uptime']);
                        $data['uptime_formatted'] = $uptimeFormatted;
                        break;
                    case 'clock':
                        // Perform processing for 'clock' endpoint if needed
                        break;
                        // Add cases for additional endpoints if necessary
                }

                // Add fetched data to responseData array
                $responseData[$key] = $data;
            } else {
                // If request fails, return error response
                return response()->json(['error' => "Failed to fetch data from $key API."], $response->status());
            }
        }

        // Return combined JSON response with formatted uptime and any other data
        return response()->json($responseData);
    }

    // Method to format uptime into a more dynamic format
    private function formatUptime($uptime)
    {
        // Match and parse format like "19h2m3s"
        preg_match('/(\d+)h(\d+)m(\d+)s/', $uptime, $matches);

        // Initialize variables for each time unit
        $hours = isset($matches[1]) ? $matches[1] : 0;
        $minutes = isset($matches[2]) ? $matches[2] : 0;
        $seconds = isset($matches[3]) ? $matches[3] : 0;

        // Array to store text for each time unit
        $timeUnits = [];

        // Add text for each time unit that has a value greater than 0
        if ($hours > 0) {
            $timeUnits[] = $hours . ' jam';
        }
        if ($minutes > 0) {
            $timeUnits[] = $minutes . ' menit';
        }
        if ($seconds > 0) {
            $timeUnits[] = $seconds . ' detik';
        }

        // Combine all time unit texts with commas and "and" between the last two
        $uptimeFormatted = implode(', ', $timeUnits);

        return $uptimeFormatted;
    }
}
