<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\LogCollectorControllerInterfaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LogCollectorController extends Controller implements LogCollectorControllerInterfaces
{
    //
    public function index(Request $request)
    {
        // $response = Http::get('http://152.42.184.255:5001/logs');
        $response = Http::get('http://152.42.184.255:5001/logs');
        $logs = $response->json();
                
        // Convert to a collection for Blade to handle
        $logs = collect($logs);
        return view('log-collector.index', ['logs' => $logs]);
    }
}
