<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class is_allowed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,string $role): RedirectResponse|Response
    {
        $thisRole = Auth::user()->role ;
        if($thisRole != $role)return redirect()->route('dashboard')->with(['status'=>'error','response'=>'Unouthorized action!']);
        return $next($request);
    }
}
