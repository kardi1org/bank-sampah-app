<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $userRole = Auth::user()->role;

        // Jika role user tidak sesuai dengan yang diminta rute, tendang ke dashboard masing-masing
        if ($userRole !== $role) {
            switch ($userRole) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'petugas':
                    return redirect()->route('petugas.dashboard');
                default:
                    return redirect()->route('dashboard'); // Nasabah
            }
        }

        return $next($request);
    }
}
