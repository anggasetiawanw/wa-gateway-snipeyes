<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\GrafikCollectorControllerInterfaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class GrafikCollectorController extends Controller implements GrafikCollectorControllerInterfaces
{
    public function index(Request $request)
    {
        // Fetch data from API
        $response = Http::get('http://152.42.184.255:5001/logs');
        $logs = $response->json();

        // Convert to a collection for Blade to handle
        $logsCollection = collect($logs);

        // Prepare data for chart
        $chartData = $logsCollection->groupBy(function ($item) {
            return $item['data'] ?? 'Golput'; // Use 'Golput' if 'data' is null
        })->map(function ($group) {
            return $group->count();
        });
        return view('grafik-collector.index', [
            'chartData' => $chartData
        ]);
    }
}
