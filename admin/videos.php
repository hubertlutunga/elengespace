<?php

declare(strict_types=1);

$pageTitle = 'Émissions vidéo';
$pageKey = 'videos';
require_once __DIR__ . '/_layout.php';

$content = cms_load_content();
$content['featured_videos'] = $content['featured_videos'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? 'save');

    if ($action === 'delete') {
        $id = (string) ($_POST['id'] ?? '');
        $index = cms_find_item_index($content['featured_videos'], $id);
        if ($index !== null) {
            array_splice($content['featured_videos'], $index, 1);
            cms_save_content($content);
            cms_flash('success', 'Vidéo supprimée.');
        }
        header('Location: videos.php');
        exit;
    }

    $id = trim((string) ($_POST['id'] ?? ''));
    $item = [
        'id' => $id !== '' ? $id : cms_slug_id('vid'),
        'title' => trim((string) ($_POST['title'] ?? '')),
        'meta' => trim((string) ($_POST['meta'] ?? '')),
        'type' => (string) ($_POST['type'] ?? 'image'),
        'embed_url' => trim((string) ($_POST['embed_url'] ?? '')),
        'image' => trim((string) ($_POST['image'] ?? '')),
        'published' => isset($_POST['published']),
        'sort_order' => (int) ($_POST['sort_order'] ?? 0),
    ];

    $index = $id !== '' ? cms_find_item_index($content['featured_videos'], $id) : null;
    if ($index !== null) {
        $content['featured_videos'][$index] = $item;
        cms_flash('success', 'Vidéo mise à jour.');
    } else {
        $content['featured_videos'][] = $item;
        cms_flash('success', 'Vidéo ajoutée.');
    }

    cms_save_content($content);
    header('Location: videos.php');
    exit;
}

$editId = (string) ($_GET['edit'] ?? '');
$videos = cms_sort_items($content['featured_videos']);
$editing = [
    'id' => '',
    'title' => '',
    'meta' => '',
    'type' => 'image',
    'embed_url' => '',
    'image' => '',
    'published' => true,
    'sort_order' => count($videos) + 1,
];

if ($editId !== '') {
    $index = cms_find_item_index($videos, $editId);
    if ($index !== null) {
        $editing = array_merge($editing, $videos[$index]);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= cms_escape($pageTitle) ?> — Elenge Space</title>
    <style>
        :root{--bg:#071120;--panel:#0d1b31;--panel-2:#132644;--line:rgba(148,163,184,.16);--text:#f8fbff;--muted:#9cb0cd;--primary:#26A9FF;--primary-dark:#1B75BC;--success:#7df0bb;--danger:#ff9d9d}
        *{box-sizing:border-box} body{margin:0;font-family:Arial, Helvetica, sans-serif;background:#04101f;color:var(--text)} a{text-decoration:none;color:inherit}
        .layout{display:grid;grid-template-columns:280px 1fr;min-height:100vh}.sidebar{padding:28px 22px;background:linear-gradient(180deg,#091526,#08111f);border-right:1px solid var(--line)}
        .brand{display:flex;align-items:center;gap:12px;margin-bottom:26px}.brand img{width:48px}.brand strong{display:block;letter-spacing:.08em}.brand span{font-size:12px;color:var(--muted)}
        .nav{display:grid;gap:10px}.nav a{padding:12px 14px;border-radius:14px;background:rgba(255,255,255,.03);border:1px solid transparent;color:#dcecff}.nav a.active,.nav a:hover{background:rgba(38,169,255,.12);border-color:rgba(38,169,255,.18)}
        .main{padding:28px}.topbar{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px}.topbar h1{margin:0}.topbar p{margin:6px 0 0;color:var(--muted)}
        .flash{margin-bottom:16px;padding:14px 16px;border-radius:14px;background:rgba(125,240,187,.12);border:1px solid rgba(125,240,187,.22);color:#d7ffeb}.content{display:grid;grid-template-columns:minmax(360px,.9fr) minmax(0,1.1fr);gap:18px}
        .card{background:rgba(10,20,36,.92);border:1px solid var(--line);border-radius:20px;padding:20px}.fields{display:grid;gap:14px}.field{display:grid;gap:7px}.field-inline{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.field-check{display:flex;align-items:center;gap:10px;margin-top:6px}
        input,textarea,select{width:100%;border:none;outline:none;border-radius:14px;padding:14px 15px;background:#132644;color:#fff;border:1px solid transparent}textarea{min-height:110px;resize:vertical}input:focus,textarea:focus,select:focus{border-color:rgba(38,169,255,.45)}
        .btn-row{display:flex;gap:10px;flex-wrap:wrap}.btn{border:none;cursor:pointer;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;padding:12px 15px;border-radius:12px;font-weight:700}.btn-soft{background:rgba(255,255,255,.06);border:1px solid var(--line);color:#fff}.btn-danger{background:rgba(255,157,157,.12);border:1px solid rgba(255,157,157,.24);color:#ffd7d7}
        table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:12px 10px;border-bottom:1px solid rgba(148,163,184,.12);font-size:14px;vertical-align:top}th{color:#9fc6eb;font-size:12px;text-transform:uppercase;letter-spacing:.12em}.badge{display:inline-flex;padding:5px 10px;border-radius:999px;font-size:12px;font-weight:700;background:rgba(125,240,187,.12);color:#cffff0}.badge.off{background:rgba(255,157,157,.12);color:#ffd7d7}
        .actions{display:flex;gap:8px;flex-wrap:wrap}.actions a,.actions button{font-size:12px;padding:8px 10px;border-radius:10px}.actions form{display:inline}
        .helper{font-size:12px;color:var(--muted)}
        @media (max-width: 1100px){.content{grid-template-columns:1fr}} @media (max-width: 980px){.layout{grid-template-columns:1fr}.sidebar{border-right:none;border-bottom:1px solid var(--line)}.field-inline{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="brand"><img src="<?= cms_escape(cms_asset_url('images/logo_es.png')) ?>" alt="Elenge Space"><div><strong>ELENGE SPACE</strong><span>Backoffice</span></div></div>
        <nav class="nav">
            <a class="<?= admin_nav_class('dashboard', $pageKey) ?>" href="index.php">Tableau de bord</a>
            <a class="<?= admin_nav_class('site', $pageKey) ?>" href="site.php">Paramètres du site</a>
            <a class="<?= admin_nav_class('videos', $pageKey) ?> active" href="videos.php">Émissions vidéo</a>
            <a class="<?= admin_nav_class('podcasts', $pageKey) ?>" href="podcasts.php">Podcasts & replays</a>
            <a class="<?= admin_nav_class('news', $pageKey) ?>" href="news.php">Actualités</a>
            <a href="../index.php?page=accueil" target="_blank">Voir le site</a>
            <a href="logout.php">Se déconnecter</a>
        </nav>
    </aside>
    <main class="main">
        <div class="topbar"><div><h1>Émissions vidéo en vedette</h1><p>Ajoutez, modifiez ou masquez les vidéos visibles sur l’accueil.</p></div></div>
        <?php if (is_array($flash)): ?><div class="flash"><?= cms_escape($flash['message'] ?? '') ?></div><?php endif; ?>
        <div class="content">
            <section class="card">
                <h2 style="margin-top:0"><?= $editing['id'] !== '' ? 'Modifier la vidéo' : 'Nouvelle vidéo' ?></h2>
                <form class="fields" method="post" action="">
                    <input type="hidden" name="id" value="<?= cms_escape($editing['id']) ?>">
                    <label class="field">Titre
                        <input type="text" name="title" value="<?= cms_escape($editing['title']) ?>" required>
                    </label>
                    <label class="field">Meta
                        <input type="text" name="meta" value="<?= cms_escape($editing['meta']) ?>" placeholder="Ex: Épisode 12 • 1h05 • Publié le 10 déc. 2025">
                    </label>
                    <div class="field-inline">
                        <label class="field">Type de média
                            <select name="type">
                                <option value="embed" <?= $editing['type'] === 'embed' ? 'selected' : '' ?>>Vidéo embarquée</option>
                                <option value="image" <?= $editing['type'] === 'image' ? 'selected' : '' ?>>Image de couverture</option>
                            </select>
                        </label>
                        <label class="field">Ordre d’affichage
                            <input type="number" name="sort_order" value="<?= (int) $editing['sort_order'] ?>" min="1">
                        </label>
                    </div>
                    <label class="field">URL embed (YouTube embed)
                        <input type="text" name="embed_url" value="<?= cms_escape($editing['embed_url']) ?>" placeholder="https://www.youtube.com/embed/...">
                    </label>
                    <label class="field">Image / vignette
                        <input type="text" name="image" value="<?= cms_escape($editing['image']) ?>" placeholder="images/thumb-urban-session.jpg">
                        <span class="helper">Utilisé si le type est « image ».</span>
                    </label>
                    <label class="field-check"><input type="checkbox" name="published" value="1" <?= !empty($editing['published']) ? 'checked' : '' ?>> Publier sur le site</label>
                    <div class="btn-row">
                        <button class="btn" type="submit">Enregistrer</button>
                        <a class="btn btn-soft" href="videos.php">Réinitialiser</a>
                    </div>
                </form>
            </section>
            <section class="card">
                <h2 style="margin-top:0">Liste des vidéos</h2>
                <table>
                    <thead><tr><th>Titre</th><th>Type</th><th>Statut</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php foreach ($videos as $video): ?>
                        <tr>
                            <td><strong><?= cms_escape($video['title'] ?? '') ?></strong><br><span class="helper"><?= cms_escape($video['meta'] ?? '') ?></span></td>
                            <td><?= cms_escape($video['type'] ?? '') ?></td>
                            <td><span class="badge <?= !empty($video['published']) ? '' : 'off' ?>"><?= !empty($video['published']) ? 'Publié' : 'Brouillon' ?></span></td>
                            <td>
                                <div class="actions">
                                    <a class="btn btn-soft" href="videos.php?edit=<?= urlencode((string) $video['id']) ?>">Modifier</a>
                                    <form method="post" action="" onsubmit="return confirm('Supprimer cette vidéo ?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= cms_escape($video['id'] ?? '') ?>">
                                        <button class="btn-danger" type="submit">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </main>
</div>
</body>
</html>
