<?php

declare(strict_types=1);

require_once __DIR__ . '/_bootstrap.php';

cms_logout();
cms_flash('success', 'Déconnexion effectuée.');
header('Location: login.php');
exit;
