<?php
return [

'paths' => ['api/*', 'sanctum/csrf-cookie', 'resources/js/*', '@vite/*'],

'allowed_methods' => ['*'],  // Laat alle methoden toe (GET, POST, etc.)

'allowed_origins' => ['http://localhost:5173', 'http://localhost:8000'],  // Toestaan vanaf beide poorten

'allowed_origins_patterns' => [],

'allowed_headers' => ['*'],  // Laat alle headers toe

'exposed_headers' => [],

'max_age' => 0,

'supports_credentials' => true,  // Als je met cookies werkt, stel dit in op true

];
