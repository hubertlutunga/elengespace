<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/cms.php';

$cmsContent = cms_load_content();
$cmsFlash = cms_pull_flash();
$cmsUser = cms_user();
