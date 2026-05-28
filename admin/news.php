<?php

declare(strict_types=1);

$pageTitle = 'Actualités';
$pageKey = 'news';
require_once __DIR__ . '/_layout.php';

$content = cms_load_content();
$content['news'] = $content['news'] ?? ['headline_title' => '', 'headline_body' => '', 'items' => []];
$content['news']['items'] = $content['news']['items'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? 'save');

    if ($action === 'save_headline') {
        $content['news']['headline_title'] = trim((string) ($_POST['headline_title'] ?? ''));
        $content['news']['headline_body'] = trim((string) ($_POST['headline_body'] ?? ''));
        cms_save_content($content);
        cms_flash('success', 'Actualité principale mise à jour.');
        header('Location: news.php');
        exit;
    }

    if ($action === 'delete') {
        $id = (string) ($_POST['id'] ?? '');
        $index = cms_find_item_index($content['news']['items'], $id);
        if ($index !== null) {
            array_splice($content['news']['items'], $index, 1);
            cms_save_content($content);
            cms_flash('success', 'Brève supprimée.');
        }
        header('Location: news.php');
        exit;
    }

    $id = trim((string) ($_POST['id'] ?? ''));
    $item = [
        'id' => $id !== '' ? $id : cms_slug_id('news'),
        'title' => trim((string) ($_POST['title'] ?? '')),
        'description' => trim((string) ($_POST['description'] ?? '')),
        'published' => isset($_POST['published']),
        'sort_order' => (int) ($_POST['sort_order'] ?? 0),
    ];

    $index = $id !== '' ? cms_find_item_index($content['news']['items'], $id) : null;
    if ($index !== null) {
        $content['news']['items'][$index] = $item;
        cms_flash('success', 'Brève mise à jour.');
    } else {
        $content['news']['items'][] = $item;
        cms_flash('success', 'Brève ajoutée.');
    }

    cms_save_content($content);
    header('Location: news.php');
    exit;
}

$items = cms_sort_items($content['news']['items']);
$editId = (string) ($_GET['edit'] ?? '');
$editing = [
    'id' => '',
    'title' => '',
    'description' => '',
    'published' => true,
    'sort_order' => count($items) + 1,
];
if ($editId !== '') {
    $index = cms_find_item_index($items, $editId);
    if ($index !== null) {
        $editing = array_merge($editing, $items[$index]);
    }
}
$headlineTitle = $content['news']['headline_title'] ?? '';
$headlineBody = $content['news']['headline_body'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?= cms_escape($pageTitle) ?> — Elenge Space</title><style>
:root{--bg:#071120;--panel:#0d1b31;--panel-2:#132644;--line:rgba(148,163,184,.16);--text:#f8fbff;--muted:#9cb0cd;--primary:#26A9FF;--primary-dark:#1B75BC;--success:#7df0bb;--danger:#ff9d9d}*{box-sizing:border-box}body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#04101f;color:var(--text)}a{text-decoration:none;color:inherit}.layout{display:grid;grid-template-columns:280px 1fr;min-height:100vh}.sidebar{padding:28px 22px;background:linear-gradient(180deg,#091526,#08111f);border-right:1px solid var(--line)}.brand{display:flex;align-items:center;gap:12px;margin-bottom:26px}.brand img{width:48px}.brand strong{display:block;letter-spacing:.08em}.brand span{font-size:12px;color:var(--muted)}.nav{display:grid;gap:10px}.nav a{padding:12px 14px;border-radius:14px;background:rgba(255,255,255,.03);border:1px solid transparent;color:#dcecff}.nav a.active,.nav a:hover{background:rgba(38,169,255,.12);border-color:rgba(38,169,255,.18)}.main{padding:28px}.topbar{margin-bottom:24px}.topbar h1{margin:0}.topbar p{margin:6px 0 0;color:var(--muted)}.flash{margin-bottom:16px;padding:14px 16px;border-radius:14px;background:rgba(125,240,187,.12);border:1px solid rgba(125,240,187,.22);color:#d7ffeb}.content{display:grid;grid-template-columns:minmax(360px,.9fr) minmax(0,1.1fr);gap:18px}.stack{display:grid;gap:18px}.card{background:rgba(10,20,36,.92);border:1px solid var(--line);border-radius:20px;padding:20px}.fields{display:grid;gap:14px}.field{display:grid;gap:7px}.field-inline{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.field-check{display:flex;align-items:center;gap:10px}input,textarea{width:100%;border:none;outline:none;border-radius:14px;padding:14px 15px;background:#132644;color:#fff;border:1px solid transparent}textarea{min-height:110px;resize:vertical}input:focus,textarea:focus{border-color:rgba(38,169,255,.45)}.btn-row{display:flex;gap:10px;flex-wrap:wrap}.btn{border:none;cursor:pointer;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;padding:12px 15px;border-radius:12px;font-weight:700}.btn-soft{background:rgba(255,255,255,.06);border:1px solid var(--line);color:#fff}.btn-danger{background:rgba(255,157,157,.12);border:1px solid rgba(255,157,157,.24);color:#ffd7d7}table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:12px 10px;border-bottom:1px solid rgba(148,163,184,.12);font-size:14px;vertical-align:top}th{color:#9fc6eb;font-size:12px;text-transform:uppercase;letter-spacing:.12em}.badge{display:inline-flex;padding:5px 10px;border-radius:999px;font-size:12px;font-weight:700;background:rgba(125,240,187,.12);color:#cffff0}.badge.off{background:rgba(255,157,157,.12);color:#ffd7d7}.helper{font-size:12px;color:var(--muted)}.actions{display:flex;gap:8px;flex-wrap:wrap}.actions form{display:inline}@media (max-width:1100px){.content{grid-template-columns:1fr}}@media (max-width:980px){.layout{grid-template-columns:1fr}.sidebar{border-right:none;border-bottom:1px solid var(--line)}.field-inline{grid-template-columns:1fr}}
</style></head>
<body><div class="layout"><aside class="sidebar"><div class="brand"><img src="<?= cms_escape(cms_asset_url('images/logo_es.png')) ?>" alt="Elenge Space"><div><strong>ELENGE SPACE</strong><span>Backoffice</span></div></div><nav class="nav"><a href="index.php">Tableau de bord</a><a href="site.php">Paramètres du site</a><a href="videos.php">Émissions vidéo</a><a href="podcasts.php">Podcasts & replays</a><a class="active" href="news.php">Actualités</a><a href="../index.php?page=accueil" target="_blank">Voir le site</a><a href="logout.php">Se déconnecter</a></nav></aside><main class="main"><div class="topbar"><h1>Actualités</h1><p>Mettez à jour l’actualité principale et les brèves secondaires.</p></div><?php if (is_array($flash)): ?><div class="flash"><?= cms_escape($flash['message'] ?? '') ?></div><?php endif; ?><div class="content"><section class="stack"><form class="card fields" method="post" action=""><h2 style="margin:0">Actualité principale</h2><input type="hidden" name="action" value="save_headline"><label class="field">Titre<input type="text" name="headline_title" value="<?= cms_escape($headlineTitle) ?>"></label><label class="field">Description<textarea name="headline_body"><?= cms_escape($headlineBody) ?></textarea></label><div class="btn-row"><button class="btn" type="submit">Mettre à jour</button></div></form><form class="card fields" method="post" action=""><h2 style="margin:0"><?= $editing['id'] !== '' ? 'Modifier une brève' : 'Ajouter une brève' ?></h2><input type="hidden" name="id" value="<?= cms_escape($editing['id']) ?>"><div class="field-inline"><label class="field">Ordre<input type="number" name="sort_order" min="1" value="<?= (int) $editing['sort_order'] ?>"></label><label class="field field-check" style="align-self:end"><input type="checkbox" name="published" value="1" <?= !empty($editing['published']) ? 'checked' : '' ?>> Publier</label></div><label class="field">Titre<input type="text" name="title" value="<?= cms_escape($editing['title']) ?>" required></label><label class="field">Description<textarea name="description"><?= cms_escape($editing['description']) ?></textarea></label><div class="btn-row"><button class="btn" type="submit">Enregistrer</button><a class="btn btn-soft" href="news.php">Réinitialiser</a></div></form></section><section class="card"><h2 style="margin-top:0">Liste des brèves</h2><table><thead><tr><th>Titre</th><th>Statut</th><th>Actions</th></tr></thead><tbody><?php foreach ($items as $item): ?><tr><td><strong><?= cms_escape($item['title'] ?? '') ?></strong><br><span class="helper"><?= cms_escape($item['description'] ?? '') ?></span></td><td><span class="badge <?= !empty($item['published']) ? '' : 'off' ?>"><?= !empty($item['published']) ? 'Publié' : 'Brouillon' ?></span></td><td><div class="actions"><a class="btn btn-soft" href="news.php?edit=<?= urlencode((string) $item['id']) ?>">Modifier</a><form method="post" action="" onsubmit="return confirm('Supprimer cette brève ?');"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= cms_escape($item['id'] ?? '') ?>"><button class="btn-danger" type="submit">Supprimer</button></form></div></td></tr><?php endforeach; ?></tbody></table></section></div></main></div></body></html>
