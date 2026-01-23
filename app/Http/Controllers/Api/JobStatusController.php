<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class JobStatusController extends Controller
{
    /**
     * Get the status of an import job
     */
    public function show(string $jobId): JsonResponse
    {
        $status = Cache::get("import_job_{$jobId}");
        
        if (!$status) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or expired',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $status,
        ]);
    }
}
