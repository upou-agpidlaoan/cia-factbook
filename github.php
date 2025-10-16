<?php
define('GITHUB_SECRET', trim(@file_get_contents('/var/www/github.secret')));

$header = 'HTTP_X_HUB_SIGNATURE';
$secret = GITHUB_SECRET;
$payload = file_get_contents('php://input');
$hash = 'sha1='.hash_hmac('sha1', $payload, $secret);

if (isset($_SERVER[$header]) && $_SERVER[$header] === $hash) {
    $payload_decoded = json_decode($payload, true);
    if ($payload_decoded['ref'] === 'refs/heads/main') {
        shell_exec('git reset --hard HEAD && git fetch origin main && git pull origin main');
    }
}
