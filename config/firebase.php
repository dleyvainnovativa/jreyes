<?php

/*
|--------------------------------------------------------------------------
| Configuración de Firebase (kreait/laravel-firebase)
|--------------------------------------------------------------------------
|
| Firebase está integrado pero INACTIVO por ahora. El sitio es público y no
| requiere inicio de sesión. Cuando se habilite el panel de administración
| bastará con colocar el archivo de credenciales y definir las variables en
| el archivo .env; el middleware 'firebase.auth' ya está registrado.
|
*/

return [
    'default' => env('FIREBASE_PROJECT', 'app'),
    'projects' => [
        'app' => [
            'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase/credentials.json')),
            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],
        ],
    ],
];
