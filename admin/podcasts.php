<?php

declare(strict_types=1);

$pageTitle = 'Podcasts & replays';
$pageKey = 'podcasts';
require_once __DIR__ . '/_layout.php';

$content = cms_load_content();
$content['podcasts'] = $content['podcasts'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? 'save');

    if ($action === 'delete') {
        $id = (string) ($_POST['id'] ?? '');
        $index = cms_find_item_index($content['podcasts'], $id);
        if ($index !== null) {
            array_splice($content['podcasts'], $index, 1);
            cms_save_content($content);
            cms_flash('success', 'Contenu supprimé.');
        }
        header('Location: podcasts.php');
        exit;
    }

    $id = trim((string) ($_POST['id'] ?? ''));
    $item = [
        'id' => $id !== '' ? $id : cms_slug_id('pod'),
        'tag' => trim((string) ($_POST['tag'] ?? 'Podcast')),
        'title' => trim((string) ($_POST['title'] ?? '')),
        'meta' => trim((string) ($_POST['meta'] ?? '')),
        'published' => isset($_POST['published']),
        'sort_order' => (int) ($_POST['sort_order'] ?? 0),
    ];

    $index = $id !== '' ? cms_find_item_index($content['podcasts'], $id) : null;
    if ($index !== null) {
        $content['podcasts'][$index] = $item;
        cms_flash('success', 'Contenu mis à jour.');
    } else {
        $content['podcasts'][] = $item;
        cms_flash('success', 'Contenu ajouté.');
    }

    cms_save_content($content);
    header('Location: podcasts.php');
    exit;
}

$items = cms_sort_items($content['podcasts']);
$editId = (string) ($_GET['edit'] ?? '');
$editing = [
    'id' => '',
    'tag' => 'Podcast',
    'title' => '',
    'meta' => '',
    'published' => true,
    'sort_order' => count($items) + 1,
];
if ($editId !== '') {
    $index = cms_find_item_index($items, $editId);
    if ($index !== null) {
        $editing = array_merge($editing, $items[$index]);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?= cms_escape($pageTitle) ?> — Elenge Space</title><style>
:root{--bg:#071120;--panel:#0d1b31;--panel-2:#132644;--line:rgba(148,163,184,.16);--text:#f8fbff;--muted:#9cb0cd;--primary:#26A9FF;--primary-dark:#1B75BC;--success:#7df0bb;--danger:#ff9d9d}*{box-sizing:border-box}body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#04101f;color:var(--text)}a{text-decoration:none;color:inherit}.layout{display:grid;grid-template-columns:280px 1fr;min-height:100vh}.sidebar{padding:28px 22px;background:linear-gradient(180deg,#091526,#08111f);border-right:1px solid var(--line)}.brand{display:flex;align-items:center;gap:12px;margin-bottom:26px}.brand img{width:48px}.brand strong{display:block;letter-spacing:.08em}.brand span{font-size:12px;color:var(--muted)}.nav{display:grid;gap:10px}.nav a{padding:12px 14px;border-radius:14px;background:rgba(255,255,255,.03);border:1px solid transparent;color:#dcecff}.nav a.active,.nav a:hover{background:rgba(38,169,255,.12);border-color:rgba(38,169,255,.18)}.main{padding:28px}.topbar{margin-bottom:24px}.topbar h1{margin:0}.topbar p{margin:6px 0 0;color:var(--muted)}.flash{margin-bottom:16px;padding:14px 16px;border-radius:14px;background:rgba(125,240,187,.12);border:1px solid rgba(125,240,187,.22);color:#d7ffeb}.content{display:grid;grid-template-columns:minmax(340px,.85fr) minmax(0,1.15fr);gap:18px}.card{background:rgba(10,20,36,.92);border:1px solid var(--line);border-radius:20px;padding:20px}.fields{display:grid;gap:14px}.field{display:grid;gap:7px}.field-inline{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.field-check{display:flex;align-items:center;gap:10px}input,textarea,select{width:100%;border:none;outline:none;border-radius:14px;padding:14px 15px;background:#132644;color:#fff;border:1px solid transparent}input:focus,textarea:focus,select:focus{border-color:rgba(38,169,255,.45)}.btn-row{display:flex;gap:10px;flex-wrap:wrap}.btn{border:none;cursor:pointer;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;padding:12px 15px;border-radius:12px;font-weight:700}.btn-soft{background:rgba(255,255,255,.06);border:1px solid var(--line);color:#fff}.btn-danger{background:rgba(255,157,157,.12);border:1px solid rgba(255,157,157,.24);color:#ffd7d7}table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:12px 10px;border-bottom:1px solid rgba(148,163,184,.12);font-size:14px;vertical-align:top}th{color:#9fc6eb;font-size:12px;text-transform:uppercase;letter-spacing:.12em}.badge{display:inline-flex;padding:5px 10px;border-radius:999px;font-size:12px;font-weight:700;background:rgba(125,240,187,.12);color:#cffff0}.badge.off{background:rgba(255,157,157,.12);color:#ffd7d7}.helper{font-size:12px;color:var(--muted)}.actions{display:flex;gap:8px;flex-wrap:wrap}.actions form{display:inline}@media (max-width:1100px){.content{grid-template-columns:1fr}}@media (max-width:980px){.layout{grid-template-columns:1fr}.sidebar{border-right:none;border-bottom:1px solid var(--line)}.field-inline{grid-template-columns:1fr}}
</style></head>
<body><div class="layout"><aside class="sidebar"><div class="brand"><img src="<?= cms_escape(cms_asset_url('images/logo_es.png')) ?>" alt="Elenge Space"><div><strong>ELENGE SPACE</strong><span>Backoffice</span></div></div><nav class="nav"><a href="index.php">Tableau de bord</a><a href="site.php">Paramètres du site</a><a href="videos.php">Émissions vidéo</a><a class="active" href="podcasts.php">Podcasts & replays</a><a href="news.php">Actualités</a><a href="../index.php?page=accueil" target="_blank">Voir le site</a><a href="logout.php">Se déconnecter</a></nav></aside><main class="main"><div class="topbar"><h1>Podcasts & replays</h1><p>Gérez les cartes de la section Replays & Podcasts.</p></div><?php if (is_array($flash)): ?><div class="flash"><?= cms_escape($flash['message'] ?? '') ?></div><?php endif; ?><div class="content"><section class="card"><h2 style="margin-top:0"><?= $editing['id'] !== '' ? 'Modifier le contenu' : 'Nouveau contenu' ?></h2><form class="fields" method="post" action=""><input type="hidden" name="id" value="<?= cms_escape($editing['id']) ?>"><div class="field-inline"><label class="field">Tag<input type="text" name="tag" value="<?= cms_escape($editing['tag']) ?>" required></label><label class="field">Ordre<input type="number" name="sort_order" min="1" value="<?= (int) $editing['sort_order'] ?>"></label></div><label class="field">Titre<input type="text" name="title" value="<?= cms_escape($editing['title']) ?>" required></label><label class="field">Meta<input type="text" name="meta" value="<?= cms_escape($editing['meta']) ?>"></label><label class="field-check"><input type="checkbox" name="published" value="1" <?= !empty($editing['published']) ? 'checked' : '' ?>> Publier sur le site</label><div class="btn-row"><button class="btn" type="submit">Enregistrer</button><a class="btn btn-soft" href="podcasts.php">Réinitialiser</a></div></form></section><section class="card"><h2 style="margin-top:0">Liste des contenus</h2><table><thead><tr><th>Type</th><th>Titre</th><th>Statut</th><th>Actions</th></tr></thead><tbody><?php foreach ($items as $item): ?><tr><td><?= cms_escape($item['tag'] ?? '') ?></td><td><strong><?= cms_escape($item['title'] ?? '') ?></strong><br><span class="helper"><?= cms_escape($item['meta'] ?? '') ?></span></td><td><span class="badge <?= !empty($item['published']) ? '' : 'off' ?>"><?= !empty($item['published']) ? 'Publié' : 'Brouillon' ?></span></td><td><div class="actions"><a class="btn btn-soft" href="podcasts.php?edit=<?= urlencode((string) $item['id']) ?>">Modifier</a><form method="post" action="" onsubmit="return confirm('Supprimer ce contenu ?');"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= cms_escape($item['id'] ?? '') ?>"><button class="btn-danger" type="submit">Supprimer</button></form></div></td></tr><?php endforeach; ?></tbody></table></section></div></main></div></body></html>
