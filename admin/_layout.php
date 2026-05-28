<?php

declare(strict_types=1);

require_once __DIR__ . '/_bootstrap.php';
cms_require_auth();

if (!isset($pageTitle)) {
    $pageTitle = 'Backoffice';
}

if (!isset($pageKey)) {
    $pageKey = 'dashboard';
}

$user = cms_user();
$flash = cms_pull_flash();

function admin_nav_class(string $key, string $pageKey): string
{
    return $key === $pageKey ? 'active' : '';
}
