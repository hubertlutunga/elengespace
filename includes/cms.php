<?php

declare(strict_types=1);

const ELENGE_STORAGE_PATH = __DIR__ . '/../storage';
const ELENGE_CONTENT_FILE = ELENGE_STORAGE_PATH . '/content.json';
const ELENGE_USERS_FILE = ELENGE_STORAGE_PATH . '/users.json';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function cms_load_content(): array
{
    if (!file_exists(ELENGE_CONTENT_FILE)) {
        return [];
    }

    $json = file_get_contents(ELENGE_CONTENT_FILE);
    if ($json === false || trim($json) === '') {
        return [];
    }

    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function cms_save_content(array $data): bool
{
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($json === false) {
        return false;
    }

    return file_put_contents(ELENGE_CONTENT_FILE, $json . PHP_EOL, LOCK_EX) !== false;
}

function cms_load_users(): array
{
    if (!file_exists(ELENGE_USERS_FILE)) {
        return [];
    }

    $json = file_get_contents(ELENGE_USERS_FILE);
    if ($json === false || trim($json) === '') {
        return [];
    }

    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

function cms_authenticate(string $username, string $password): bool
{
    $users = cms_load_users();

    foreach ($users as $user) {
        if (($user['username'] ?? '') !== $username) {
            continue;
        }

        if (!password_verify($password, (string) ($user['password_hash'] ?? ''))) {
            return false;
        }

        $_SESSION['cms_user'] = [
            'username' => $user['username'],
            'name' => $user['name'] ?? $user['username'],
        ];

        return true;
    }

    return false;
}

function cms_logout(): void
{
    unset($_SESSION['cms_user']);
}

function cms_user(): ?array
{
    $user = $_SESSION['cms_user'] ?? null;
    return is_array($user) ? $user : null;
}

function cms_is_logged_in(): bool
{
    return cms_user() !== null;
}

function cms_require_auth(): void
{
    if (!cms_is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function cms_flash(string $type, string $message): void
{
    $_SESSION['cms_flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function cms_pull_flash(): ?array
{
    $flash = $_SESSION['cms_flash'] ?? null;
    unset($_SESSION['cms_flash']);
    return is_array($flash) ? $flash : null;
}

function cms_sort_items(array $items): array
{
    usort($items, static function (array $left, array $right): int {
        return ((int) ($left['sort_order'] ?? 0)) <=> ((int) ($right['sort_order'] ?? 0));
    });

    return $items;
}

function cms_filter_published(array $items): array
{
    $published = array_filter($items, static function ($item): bool {
        return is_array($item) && !empty($item['published']);
    });

    return cms_sort_items(array_values($published));
}

function cms_find_item_index(array $items, string $id): ?int
{
    foreach ($items as $index => $item) {
        if (($item['id'] ?? '') === $id) {
            return $index;
        }
    }

    return null;
}

function cms_slug_id(string $prefix): string
{
    return $prefix . '_' . bin2hex(random_bytes(4));
}

function cms_escape(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function cms_asset_url(string $path): string
{
    $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

    if ($scriptDir === '' || $scriptDir === '.') {
        $basePath = '';
    } elseif (preg_match('#/admin$#', $scriptDir) === 1) {
        $basePath = (string) preg_replace('#/admin$#', '', $scriptDir);
    } else {
        $basePath = $scriptDir;
    }

    return ($basePath !== '' ? $basePath : '') . '/' . ltrim($path, '/');
}
