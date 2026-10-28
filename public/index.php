<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Live (Axxess): this file sits in public_html and the app lives in the sibling
// folder sppestapp/. Locally: the normal Laravel layout, app one level up.
$base = is_dir(__DIR__.'/../sppestapp') ? __DIR__.'/../sppestapp' : dirname(__DIR__);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $base.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $base.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once $base.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
