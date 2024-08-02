<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\LogCollectorControllerInterfaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class LogCollectorController extends Controller implements LogCollectorControllerInterfaces
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

        // Paginate the collection
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $logsCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $logsPaginated = new LengthAwarePaginator($currentItems, $logsCollection->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        return view('log-collector.index', [
            'logs' => $logsPaginated,
            'chartData' => $chartData
        ]);
    }
}
