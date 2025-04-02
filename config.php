<?php
require_once __DIR__ . '/vendor/autoload.php'; // Include Composer's autoloader
// Load environment variables from the .env file
Dotenv\Dotenv::createImmutable(__DIR__)->load();
$clientId = $_ENV['GITHUB_CLIENT_ID'];
$clientSecret = $_ENV['GITHUB_CLIENT_SECRET'];
define('GITHUB_REDIRECT_URL', 'http://localhost/PersonalProject/NativePhp/oauth_app/callback.php'); // Change to live URL later
?>
