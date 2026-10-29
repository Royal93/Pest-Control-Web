<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Live (Axxess): this file sits in public_html and the app lives in the sibling
// folder sppestapp/. Locally: the normal Laravel layout, app one level up.
$live = is_dir(__DIR__.'/../sppestapp');
$base = $live ? __DIR__.'/../sppestapp' : dirname(__DIR__);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $base.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $base.'/vendor/autoload.php';

// Bootstrap Laravel...
$app = require_once $base.'/bootstrap/app.php';

// Live: the real web root is public_html (this folder), not sppestapp/public.
// Each deploy puts the fresh CSS/JS build and its manifest here, so Laravel
// must read build/manifest.json from here or pages load the old stylesheet.
if ($live) {
    $app->usePublicPath(__DIR__);
}

// ...and handle the request.
$app->handleRequest(Request::capture());
