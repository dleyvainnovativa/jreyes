<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de verificación de token de Firebase.
 *
 * INACTIVO por ahora: el sitio es público y no requiere inicio de sesión.
 * Está registrado con el alias 'firebase.auth' (ver bootstrap/app.php) para
 * poder proteger las rutas del panel de administración en el futuro.
 *
 * Cuando se habilite:
 *   1. Coloca el service account en storage/app/firebase/credentials.json
 *   2. Define las variables FIREBASE_* en el archivo .env
 *   3. Aplica el middleware a las rutas: Route::middleware('firebase.auth')
 *   4. Descomenta el bloque de verificación de abajo.
 */
class VerifyFirebaseToken
{
    public function handle(Request $request, Closure $next): Response
    {
        // --- Bloque de verificación (inactivo) -------------------------------
        //
        // $token = $request->bearerToken() ?? $request->session()->get('firebase_token');
        //
        // if (! $token) {
        //     return $request->expectsJson()
        //         ? response()->json(['message' => 'No autenticado.'], 401)
        //         : redirect()->route('login');
        // }
        //
        // try {
        //     /** @var \Kreait\Firebase\Contract\Auth $auth */
        //     $auth = app('firebase.auth');
        //     $verified = $auth->verifyIdToken($token);
        //     $request->attributes->set('firebase_uid', $verified->claims()->get('sub'));
        // } catch (\Throwable $e) {
        //     return $request->expectsJson()
        //         ? response()->json(['message' => 'Sesión inválida.'], 401)
        //         : redirect()->route('login');
        // }
        //
        // ---------------------------------------------------------------------

        return $next($request);
    }
}
