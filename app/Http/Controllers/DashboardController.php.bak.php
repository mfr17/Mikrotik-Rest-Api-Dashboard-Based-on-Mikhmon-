<?php

namespace App\Http\Controllers;

use NumberFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $router = $request->session()->get('active_router', []);

        // Ensure router is retrieved successfully
        if (!$router) {
            return redirect()->back()->with('error', 'Router not found.');
        }

        $username = $router['username'];
        $password = $router['password'];
        $url = $router['ip_mikrotik'];

        $data = $this->fetchRouterData($username, $password, $url);

        // Fetch log messages
        $logData = $this->fetchLogMessages($username, $password, $url);
        $data['log_messages'] = $logData['messages'];
        $data['interface'] = collect($data['interface'])
            ->pluck('name', 'name')
            ->toArray();

        $thisD = date("d");
        $thisM = strtolower(date("M"));
        $thisY = date("Y");
        $idhr = $thisM . "/" . $thisD . "/" . $thisY;
        $idbl = $thisM . $thisY;
        $router['idhr'] = $idhr;
        Session::put('active_router', $router);
        $script =  "/system/script";

        $income = $this->makeRequest(
            'GET',
            $url . $script,
            $username,
            $password
        )->json();
        $income = collect($income)
            ->filter(function ($item) use ($idbl) {
                return $item['owner'] == $idbl;
            })
            ->toArray();

        $formatter = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
        $tHr = 0;
        $TotalRHr = 0;
        $tBl = 0;
        $TotalRBl = count($income);
        $data['vcr_monthly'] = $TotalRBl;
        foreach ($income as $row) {
            if ((explode("-|-", $row['name'])[0]) == $idhr) {
                $tHr += explode("-|-", $row['name'])[3];
                $TotalRHr += count((array)$row['source']); /*Modif line add (array) by github https://github.com/MasKawer*/
            }
            $tBl += explode("-|-", $row['name'])[3];
        }
        if ($TotalRHr == 0) {
            $data['vcr_today'] = '0';
        } else {
            $data['vcr_today'] = $TotalRHr;
        }
        $data['today'] = $formatter->formatCurrency($tHr, 'IDR');
        $data['monthly'] = $formatter->formatCurrency($tBl, 'IDR');

        dd($data); // Uncomment for debugging

        return view('pages.dashboard.index', $data);
    }

    private function fetchRouterData($username, $password, $url)
    {
        $paths = [
            "/interface",
            "/ip/hotspot/host",
            "/ip/hotspot/active",
            "/ip/hotspot/user",
            "/ip/hotspot/profile",
            "/ppp/active",
            "/ppp/secret",
            "/ppp/profile",
            "/interface/pppoe-server/server",
            "/system/resource",
            "/system/routerboard",
            "/system/identity",
            "/system/clock",
        ];
        $data = [];
        $hsActive = 0;
        $hsHost = 0;
        $hsUser = 0;
        $hsProfile = 0;
        $pppActive = 0;
        $pppSecret = 0;
        $pppProfile = 0;
        $pppServer = 0;

        foreach ($paths as $path) {
            $response = $this->makeRequest('GET', $url . $path, $username, $password);
            $key = $this->generateKey($path);
            $responseData = $response->successful() ? $response->json() : ['error' => $response->status()];
            $data[$key] = $responseData;
            if ($response->successful()) {
                $hsActive += strpos($key, 'hotspot_active') !== false ? count($responseData) : 0;
                $hsHost += strpos($key, 'hotspot_host') !== false ? count($responseData) : 0;
                $hsUser += strpos($key, 'hotspot_user') !== false ? count($responseData) : 0;
                $hsProfile += strpos($key, 'hotspot_profile') !== false ? count($responseData) : 0;
                $pppActive += strpos($key, 'ppp_active') !== false ? count($responseData) : 0;
                $pppSecret += strpos($key, 'ppp_secret') !== false ? count($responseData) : 0;
                $pppProfile += strpos($key, 'ppp_profile') !== false ? count($responseData) : 0;
                $pppServer += strpos($key, 'ppp_server') !== false ? count($responseData) : 0;
            }
        }
        if (isset($data['resource'])) {
            $resource = $data['resource'];

            // Format free hdd space
            if (isset($resource['free-hdd-space'])) {
                $data['free_hdd_formatted'] = formatBytes2($resource['free-hdd-space']);
            }

            // Format free memory
            if (isset($resource['free-memory'])) {
                $data['free_memory_formatted'] = formatBytes2($resource['free-memory']);
            }
        }






        $data['hs_active'] = $hsActive;
        $data['hs_host'] = $hsHost;
        $data['hs_user'] = $hsUser;
        $data['hs_profile'] = $hsProfile;
        $data['ppp_active'] = $pppActive;
        $data['ppp_secret'] = $pppSecret;
        $data['ppp_profile'] = $pppProfile;
        $data['ppp_server'] = $pppServer;
        return $data;
    }


    private function fetchLogMessages($username, $password,  $url)
    {
        $path = '/log';
        $response = $this->makeRequest('GET', $url . $path, $username, $password);
        $collections = collect($response->json());

        $filteredData = $collections->filter(function ($item) {
            return strpos($item['topics'], 'hotspot,info,debug') !== false;
        })->toArray();

        $filteredResult = [];

        $filteredResult = collect($filteredData)->map(function ($item) {
            $message = str_replace('->: ', '', $item['message']);
            $message = explode(': ', $message, 2);
            return [
                'time' => $item['time'],
                'user' => trim($message[0]),
                'message' => trim($message[1])
            ];
        })->values()->toArray();
        $reverse = array_reverse($filteredResult);
        return ['messages' => $reverse];
    }

    private function makeRequest($method, $url, $username, $password)
    {
        return Http::withBasicAuth($username, $password)->$method($url);
    }

    private function generateKey($path)
    {
        $key = explode('/', $path);
        $key = end($key);
        if (strpos($path, 'hotspot') !== false) {
            $key = 'hotspot_' . $key;
        } elseif (strpos($path, 'ppp') !== false) {
            $key = 'ppp_' . $key;
        }
        return $key;
    }
}
