<?php

declare(strict_types=1);

$pageTitle = 'Paramètres du site';
$pageKey = 'site';
require_once __DIR__ . '/_layout.php';

$content = cms_load_content();
$site = $content['site'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content['site'] = [
        'hero_title' => trim((string) ($_POST['hero_title'] ?? '')),
        'hero_intro' => trim((string) ($_POST['hero_intro'] ?? '')),
        'hero_button_label' => trim((string) ($_POST['hero_button_label'] ?? '')),
        'radio_stream_url' => trim((string) ($_POST['radio_stream_url'] ?? '')),
        'contact_email' => trim((string) ($_POST['contact_email'] ?? '')),
        'contact_phone' => trim((string) ($_POST['contact_phone'] ?? '')),
        'contact_address' => trim((string) ($_POST['contact_address'] ?? '')),
        'contact_handle' => trim((string) ($_POST['contact_handle'] ?? '')),
    ];

    cms_save_content($content);
    cms_flash('success', 'Paramètres du site mis à jour.');
    header('Location: site.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= cms_escape($pageTitle) ?> — Elenge Space</title>
    <style>
        :root{--bg:#071120;--panel:#0d1b31;--panel-2:#132644;--line:rgba(148,163,184,.16);--text:#f8fbff;--muted:#9cb0cd;--primary:#26A9FF;--primary-dark:#1B75BC;--success:#7df0bb}
        *{box-sizing:border-box} body{margin:0;font-family:Arial, Helvetica, sans-serif;background:#04101f;color:var(--text)} a{text-decoration:none;color:inherit}
        .layout{display:grid;grid-template-columns:280px 1fr;min-height:100vh}.sidebar{padding:28px 22px;background:linear-gradient(180deg,#091526,#08111f);border-right:1px solid var(--line)}
        .brand{display:flex;align-items:center;gap:12px;margin-bottom:26px}.brand img{width:48px}.brand strong{display:block;letter-spacing:.08em}.brand span{font-size:12px;color:var(--muted)}
        .nav{display:grid;gap:10px}.nav a{padding:12px 14px;border-radius:14px;background:rgba(255,255,255,.03);border:1px solid transparent;color:#dcecff}.nav a.active,.nav a:hover{background:rgba(38,169,255,.12);border-color:rgba(38,169,255,.18)}
        .main{padding:28px}.topbar{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px}.topbar h1{margin:0}.topbar p{margin:6px 0 0;color:var(--muted)}
        .btn,.btn-link{display:inline-flex;align-items:center;justify-content:center;padding:11px 15px;border-radius:12px;font-weight:700}.btn{border:none;cursor:pointer;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff}.btn-link{background:rgba(255,255,255,.05);border:1px solid var(--line)}
        .card{background:rgba(10,20,36,.92);border:1px solid var(--line);border-radius:20px;padding:22px;max-width:920px}.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.field{display:grid;gap:7px}.field-full{grid-column:1 / -1}label{font-size:14px;color:#deebfb}input,textarea{width:100%;border:none;outline:none;border-radius:14px;padding:14px 15px;background:#132644;color:#fff;border:1px solid transparent}textarea{min-height:120px;resize:vertical}input:focus,textarea:focus{border-color:rgba(38,169,255,.45)}
        .flash{margin-bottom:16px;padding:14px 16px;border-radius:14px;background:rgba(125,240,187,.12);border:1px solid rgba(125,240,187,.22);color:#d7ffeb;max-width:920px}
        @media (max-width: 980px){.layout{grid-template-columns:1fr}.sidebar{border-right:none;border-bottom:1px solid var(--line)}.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="brand">
            <img src="<?= cms_escape(cms_asset_url('images/logo_es.png')) ?>" alt="Elenge Space">
            <div><strong>ELENGE SPACE</strong><span>Backoffice</span></div>
        </div>
        <nav class="nav">
            <a class="<?= admin_nav_class('dashboard', $pageKey) ?>" href="index.php">Tableau de bord</a>
            <a class="<?= admin_nav_class('site', $pageKey) ?>" href="site.php">Paramètres du site</a>
            <a class="<?= admin_nav_class('videos', $pageKey) ?>" href="videos.php">Émissions vidéo</a>
            <a class="<?= admin_nav_class('podcasts', $pageKey) ?>" href="podcasts.php">Podcasts & replays</a>
            <a class="<?= admin_nav_class('news', $pageKey) ?>" href="news.php">Actualités</a>
            <a href="../index.php?page=accueil" target="_blank">Voir le site</a>
            <a href="logout.php">Se déconnecter</a>
        </nav>
    </aside>
    <main class="main">
        <div class="topbar">
            <div>
                <h1><?= cms_escape($pageTitle) ?></h1>
                <p>Modifiez les contenus généraux affichés sur l’accueil public.</p>
            </div>
            <a class="btn-link" href="index.php">Retour au tableau de bord</a>
        </div>
        <?php if (is_array($flash)): ?><div class="flash"><?= cms_escape($flash['message'] ?? '') ?></div><?php endif; ?>
        <form class="card" method="post" action="">
            <div class="grid">
                <label class="field field-full">Titre hero
                    <input type="text" name="hero_title" value="<?= cms_escape($site['hero_title'] ?? '') ?>">
                </label>
                <label class="field field-full">Introduction hero
                    <textarea name="hero_intro"><?= cms_escape($site['hero_intro'] ?? '') ?></textarea>
                </label>
                <label class="field">Libellé bouton hero
                    <input type="text" name="hero_button_label" value="<?= cms_escape($site['hero_button_label'] ?? '') ?>">
                </label>
                <label class="field">URL du flux radio
                    <input type="text" name="radio_stream_url" value="<?= cms_escape($site['radio_stream_url'] ?? '') ?>">
                </label>
                <label class="field">Email de contact
                    <input type="email" name="contact_email" value="<?= cms_escape($site['contact_email'] ?? '') ?>">
                </label>
                <label class="field">Téléphone / WhatsApp
                    <input type="text" name="contact_phone" value="<?= cms_escape($site['contact_phone'] ?? '') ?>">
                </label>
                <label class="field field-full">Adresse
                    <input type="text" name="contact_address" value="<?= cms_escape($site['contact_address'] ?? '') ?>">
                </label>
                <label class="field field-full">Handle réseaux
                    <input type="text" name="contact_handle" value="<?= cms_escape($site['contact_handle'] ?? '') ?>">
                </label>
            </div>
            <div style="margin-top:18px">
                <button class="btn" type="submit">Enregistrer les modifications</button>
            </div>
        </form>
    </main>
</div>
</body>
</html>
