<?php

declare(strict_types=1);

require_once __DIR__ . '/_bootstrap.php';
cms_require_auth();

$content = cms_load_content();
$videos = cms_sort_items($content['featured_videos'] ?? []);
$podcasts = cms_sort_items($content['podcasts'] ?? []);
$newsItems = cms_sort_items(($content['news']['items'] ?? []));
$site = $content['site'] ?? [];
$flash = cms_pull_flash();
$user = cms_user();

$stats = [
    'videos' => count(array_filter($videos, static fn(array $item): bool => !empty($item['published']))),
    'podcasts' => count(array_filter($podcasts, static fn(array $item): bool => !empty($item['published']))),
    'news' => count(array_filter($newsItems, static fn(array $item): bool => !empty($item['published']))),
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice — Elenge Space</title>
    <style>
        :root{
            --bg:#071120;
            --panel:#0d1b31;
            --panel-2:#132644;
            --line:rgba(148,163,184,.16);
            --text:#f8fbff;
            --muted:#9cb0cd;
            --primary:#26A9FF;
            --primary-dark:#1B75BC;
            --success:#7df0bb;
            --warning:#ffdf8c;
        }
        *{box-sizing:border-box}
        body{margin:0;font-family:Arial, Helvetica, sans-serif;background:#04101f;color:var(--text)}
        a{text-decoration:none;color:inherit}
        .layout{display:grid;grid-template-columns:280px 1fr;min-height:100vh}
        .sidebar{padding:28px 22px;background:linear-gradient(180deg,#091526,#08111f);border-right:1px solid var(--line)}
        .brand{display:flex;align-items:center;gap:12px;margin-bottom:26px}
        .brand img{width:48px}
        .brand strong{display:block;letter-spacing:.08em}
        .brand span{font-size:12px;color:var(--muted)}
        .nav{display:grid;gap:10px}
        .nav a{padding:12px 14px;border-radius:14px;background:rgba(255,255,255,.03);border:1px solid transparent;color:#dcecff}
        .nav a:hover,.nav a.active{background:rgba(38,169,255,.12);border-color:rgba(38,169,255,.18)}
        .sidebar-footer{margin-top:22px;padding-top:22px;border-top:1px solid var(--line);font-size:13px;color:var(--muted)}
        .main{padding:28px}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px}
        .topbar h1{margin:0;font-size:30px}
        .topbar p{margin:6px 0 0;color:var(--muted)}
        .topbar-actions{display:flex;gap:10px;align-items:center}
        .btn,.btn-link{display:inline-flex;align-items:center;justify-content:center;padding:11px 15px;border-radius:12px;font-weight:700}
        .btn{background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;box-shadow:0 16px 32px rgba(38,169,255,.22)}
        .btn-link{background:rgba(255,255,255,.05);border:1px solid var(--line);color:#eaf5ff}
        .flash{margin-bottom:16px;padding:14px 16px;border-radius:14px;border:1px solid transparent}
        .flash.success{background:rgba(125,240,187,.12);border-color:rgba(125,240,187,.22);color:#d7ffeb}
        .grid{display:grid;gap:18px}
        .stats{grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:22px}
        .card{background:rgba(10,20,36,.92);border:1px solid var(--line);border-radius:20px;padding:18px;box-shadow:0 18px 45px rgba(0,0,0,.18)}
        .stat-card small{display:block;color:var(--muted);margin-bottom:12px;text-transform:uppercase;letter-spacing:.14em;font-size:11px}
        .stat-card strong{font-size:34px}
        .stat-card span{display:block;margin-top:8px;color:#d8e7f7;font-size:14px}
        .content-grid{grid-template-columns:1.2fr .8fr}
        .section-title{display:flex;justify-content:space-between;align-items:center;gap:14px;margin-bottom:14px}
        .section-title h2{margin:0;font-size:20px}
        .section-title p{margin:4px 0 0;color:var(--muted);font-size:13px}
        table{width:100%;border-collapse:collapse}
        th,td{text-align:left;padding:12px 10px;border-bottom:1px solid rgba(148,163,184,.12);font-size:14px;vertical-align:top}
        th{color:#9fc6eb;font-size:12px;text-transform:uppercase;letter-spacing:.12em}
        .badge{display:inline-flex;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:700}
        .badge.live{background:rgba(125,240,187,.12);color:#bdf8dc}
        .badge.off{background:rgba(255,223,140,.12);color:#ffe8a9}
        .stack{display:grid;gap:12px}
        .mini-card{padding:14px 15px;border-radius:16px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05)}
        .mini-card strong{display:block;margin-bottom:6px}
        .mini-card span{color:var(--muted);font-size:13px}
        .hero-preview h3{margin:0 0 8px;font-size:22px}
        .hero-preview p{margin:0;color:var(--muted);line-height:1.55}
        @media (max-width: 980px){
            .layout{grid-template-columns:1fr}
            .sidebar{border-right:none;border-bottom:1px solid var(--line)}
            .stats,.content-grid{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="brand">
                <img src="<?= cms_escape(cms_asset_url('images/logo_es.png')) ?>" alt="Elenge Space">
                <div>
                    <strong>ELENGE SPACE</strong>
                    <span>Backoffice</span>
                </div>
            </div>
            <nav class="nav">
                <a class="active" href="index.php">Tableau de bord</a>
                <a href="site.php">Paramètres du site</a>
                <a href="videos.php">Émissions vidéo</a>
                <a href="podcasts.php">Podcasts & replays</a>
                <a href="news.php">Actualités</a>
                <a href="../index.php?page=accueil" target="_blank">Voir le site</a>
            </nav>
            <div class="sidebar-footer">
                Connecté en tant que <strong><?= cms_escape($user['name'] ?? 'Admin') ?></strong>
            </div>
        </aside>
        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Tableau de bord</h1>
                    <p>Gérez les contenus visibles sur la page d’accueil Elenge Space.</p>
                </div>
                <div class="topbar-actions">
                    <a class="btn" href="videos.php">Publier une vidéo</a>
                    <a class="btn-link" href="logout.php">Se déconnecter</a>
                </div>
            </div>
            <?php if (is_array($flash)): ?>
                <div class="flash <?= cms_escape($flash['type'] ?? 'success') ?>"><?= cms_escape($flash['message'] ?? '') ?></div>
            <?php endif; ?>
            <div class="grid stats">
                <div class="card stat-card">
                    <small>Émissions vidéo publiées</small>
                    <strong><?= (int) $stats['videos'] ?></strong>
                    <span>En vedette sur la page d’accueil</span>
                </div>
                <div class="card stat-card">
                    <small>Podcasts / replays</small>
                    <strong><?= (int) $stats['podcasts'] ?></strong>
                    <span>Contenus audio et vidéo disponibles</span>
                </div>
                <div class="card stat-card">
                    <small>Brèves d’actualité</small>
                    <strong><?= (int) $stats['news'] ?></strong>
                    <span>Cartes actives dans la rubrique Actus</span>
                </div>
                <div class="card stat-card">
                    <small>Flux radio</small>
                    <strong>Live</strong>
                    <span><?= cms_escape($site['radio_stream_url'] ?? '') ?></span>
                </div>
            </div>
            <div class="grid content-grid">
                <section class="card">
                    <div class="section-title">
                        <div>
                            <h2>Émissions vidéo en vedette</h2>
                            <p>Les contenus affichés publiquement sur l’accueil.</p>
                        </div>
                        <a class="btn-link" href="videos.php">Gérer</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach (array_slice($videos, 0, 4) as $video): ?>
                            <tr>
                                <td>
                                    <strong><?= cms_escape($video['title'] ?? '') ?></strong><br>
                                    <span style="color:var(--muted)"><?= cms_escape($video['meta'] ?? '') ?></span>
                                </td>
                                <td><?= cms_escape($video['type'] ?? '') ?></td>
                                <td><span class="badge <?= !empty($video['published']) ? 'live' : 'off' ?>"><?= !empty($video['published']) ? 'Publié' : 'Brouillon' ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
                <section class="grid">
                    <div class="card hero-preview">
                        <div class="section-title">
                            <div>
                                <h2>Hero public</h2>
                                <p>Titre et message d’accueil</p>
                            </div>
                            <a class="btn-link" href="site.php">Modifier</a>
                        </div>
                        <h3><?= cms_escape($site['hero_title'] ?? '') ?></h3>
                        <p><?= cms_escape($site['hero_intro'] ?? '') ?></p>
                    </div>
                    <div class="card">
                        <div class="section-title">
                            <div>
                                <h2>Actus récentes</h2>
                                <p>Aperçu des brèves publiées</p>
                            </div>
                            <a class="btn-link" href="news.php">Modifier</a>
                        </div>
                        <div class="stack">
                            <?php foreach (array_slice($newsItems, 0, 3) as $item): ?>
                                <div class="mini-card">
                                    <strong><?= cms_escape($item['title'] ?? '') ?></strong>
                                    <span><?= cms_escape($item['description'] ?? '') ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
