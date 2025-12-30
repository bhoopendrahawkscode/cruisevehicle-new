<?php

/**
 * @Author: Pankaj Gupta
 * @Date:   2023-12-19 11:20:32
 * @Last Modified by:   Pankaj Gupta
 * @Last Modified time: 2023-12-19 11:20:32
 */


namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class ApiDataLogger
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       
        return $next($request);
    }

    public function terminate($request, $response)
    {

        if (isset($_ENV['API_DATALOGGER']) && $_ENV['API_DATALOGGER'] == 'false'){
             return;
        }
        $end_time = microtime(true);
        $filename = 'api_data_logger_' . date('Y_m_d') . '.log';
        $data = 'Time: ' . gmdate("F j, Y, g:i a") . "\n";
        $data .= 'Duration: ' . number_format($end_time - LARAVEL_START, 3) . "\n";
        $data .= 'IP Address: ' . $request->ip() . "\n";
        $data .= 'URL: ' . $request->fullUrl() . "\n";
        $data .= 'Method: ' . $request->method() . "\n";
        $data .= 'Bearer Token: ' . $request->bearerToken() . "\n";
        $data .= 'Input: ' . json_encode($request->all()) . "\n";
		$data .= 'Attachment: ' . json_encode($_FILES) . "\n";
        if (str_contains($request->route()->getPrefix(), "api/v")) {
            // only in case of api log response body;
            $data .= 'Output: ' . $response->getContent() . "\n";
        }
        \File::append(storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $data . "\n" . str_repeat("=", 20) . "\n\n");
    }
}
