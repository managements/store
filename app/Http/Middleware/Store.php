<?php

namespace App\Http\Middleware;

use Closure;

class Store
{
    public function handle($request, Closure $next)
    {
        if (auth()->user()->staff->category->category != 'admin'
        && auth()->user()->staff->category->category != 'store'){
            return redirect()->route('home');
        }
        return $next($request);
    }
}
