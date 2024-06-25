<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RouterController extends Controller
{
    public function index(Request $request)
    {
        $routers = $request->session()->get('routers', []);

        return view('pages.router.index', ['data' => $routers]);
    }
    public function useRouter(Request $request, $index)
    {
        $routers = $request->session()->get('routers', []);
        if (isset($routers[$index])) {
            $request->session()->put('active_router', $routers[$index]);
            return redirect()->route('dashboard')->with('success', 'Router selected successfully.');
        }

        return redirect()->back()->with('error', 'Router not found.');
    }

    public function create()
    {
        return view('pages.router.create');
    }

    public function store(Request $request)
    {
        // dd($request);
        $routerConfig = [
            'session_name' => $request->input('session_name'),
            'ip_mikrotik' => $request->input('ip_address'),
            'username' => $request->input('username_mikrotik'),
            'password' => $request->input('password'),
            'hotspot_name' => $request->input('hotspot_name'),
            'dns_name' => $request->input('dns_name'),
            'live_report' => $request->input('live_report'),
        ];

        // Retrieve existing routers from session or initialize as an empty array
        $routers = $request->session()->get('routers', []);

        // Add the new router configuration to the array
        $routers[] = $routerConfig;

        // Store the updated array back into the session
        $request->session()->put('routers', $routers);

        // dd($routers);
        // Redirect back or to another page after storing
        return redirect()->route('router')->with('success', 'Router configuration added successfully!');
    }

    public function edit($index)
    {
        $router = session('routers')[$index];
        return view('pages.router.edit', compact('router', 'index'));
    }
    public function update(Request $request, $index)
    {
        $routers = session('routers', []);
        if (isset($routers[$index])) {
            $routers[$index] = [
                'session_name' => $request->input('session_name'),
                'ip_mikrotik' => $request->input('ip_address'),
                'username' => $request->input('username_mikrotik'),
                'password' => $request->input('password'),
                'hotspot_name' => $request->input('hotspot_name'),
                'dns_name' => $request->input('dns_name'),
                'live_report' => $request->input('live_report'),
            ];

            $request->session()->put('routers', $routers);

            return redirect()->route('router')->with('success', 'Router updated successfully.');
        }

        return redirect()->route('router')->with('error', 'Router not found.');
    }
    public function delete(Request $request, $index)
    {
        $routers = session('routers', []);
        if (isset($routers[$index])) {
            unset($routers[$index]);
            $request->session()->put('routers', array_values($routers)); // Reindex the array

            return redirect()->route('router')->with('success', 'Router deleted successfully.');
        }

        return redirect()->route('router')->with('error', 'Router not found.');
    }
}
