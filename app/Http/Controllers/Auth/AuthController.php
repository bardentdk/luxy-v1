<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {}

    public function showLogin(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        // ── Votre logique d'auth originale ──────────────
        $success = $this->authService->attemptLogin(
            email:    $request->email,
            password: $request->password,
            remember: $request->boolean('remember'),
        );

        if (! $success) {
            return back()->withErrors([
                'email' => 'Ces identifiants ne correspondent à aucun compte actif.',
            ])->onlyInput('email');
        }

        // ── Redirect selon le rôle ──────────────────────
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Bienvenue, ' . $user->first_name . ' !');
        }

        if ($user->hasRole('commercial')) {
            return redirect()->intended(route('commercial.dashboard'))
                ->with('success', 'Bienvenue, ' . $user->first_name . ' !');
        }

        // Rôle non reconnu → accueil public
        return redirect()->route('home')
            ->with('success', 'Bienvenue, ' . $user->first_name . ' !');
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('home')
            ->with('info', 'Vous avez été déconnecté avec succès.');
    }
}