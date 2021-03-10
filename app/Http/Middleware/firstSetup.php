<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class firstSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $setting = Auth::user()->setting;
        if (empty($setting)) {
            Setting::create([
                'user_id'    => Auth::user()->id,
                'confidence' => 50 / 100,
                'support'    => 50 / 100,
            ]);
        }
        return $next($request);
    }
}
