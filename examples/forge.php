<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Demo\Forge;

$key = "api-key-here";

$forge = Forge::illuminate($key);

// Raw implementation
$databases = $forge->servers->with(['databases'])->load('418503')->find("407126")->getBody()->getContents();

// Shortcut implementation
$databases = $forge->servers->databases('418503')->find("407126")->getBody()->getContents();
