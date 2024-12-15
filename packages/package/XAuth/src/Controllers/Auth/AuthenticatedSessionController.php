<?php

namespace Package\XAuth\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\{Request, Response as LaravelResponse };
use Illuminate\Support\Facades\{Auth, Log};
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Package\XAuth\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('XAuth/Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): LaravelResponse
    {

        $request->authenticate();

        $request->session()->regenerate();

        $intendedUrl = redirect()->intended()->getTargetUrl();

        return Inertia::location($intendedUrl == url('/') ? RouteServiceProvider::getHomeRoute() : $intendedUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
