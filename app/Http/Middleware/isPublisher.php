<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isPublisher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        if(auth()->check() && auth()->user()->user_type == 'Publisher')
        { 
            return $next($request); 
        } 
        else
        { 
            $response = [
                'success' => false,
                'message' => 'You must be in Publisher to view this page.',
            ];
            return response()->json($response, 422);
            // return redirect()->route('dashboard')->with('message','you must be in Publisher to view this page.');
        }
    }
}
