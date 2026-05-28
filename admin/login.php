<?php

declare(strict_types=1);

require_once __DIR__ . '/_bootstrap.php';

if (cms_is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Veuillez renseigner votre identifiant et votre mot de passe.';
    } elseif (!cms_authenticate($username, $password)) {
        $error = 'Identifiants invalides.';
    } else {
        cms_flash('success', 'Connexion réussie.');
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion backoffice — Elenge Space</title>
    <style>
        :root{
            --bg:#071120;
            --panel:#0d1b31;
            --panel-soft:#122443;
            --line:rgba(148,163,184,.18);
            --text:#f8fbff;
            --muted:#9eb2cf;
            --primary:#26A9FF;
            --primary-dark:#1B75BC;
            --danger:#ff8d8d;
        }
        *{box-sizing:border-box}
        body{
            margin:0;
            min-height:100vh;
            display:grid;
            place-items:center;
            padding:24px;
            font-family:Arial, Helvetica, sans-serif;
            background:radial-gradient(circle at top, #0d2748 0%, #071120 45%, #03070f 100%);
            color:var(--text);
        }
        .login-shell{
            width:min(100%, 980px);
            display:grid;
            grid-template-columns:minmax(0,1.1fr) minmax(360px,420px);
            border:1px solid var(--line);
            border-radius:28px;
            overflow:hidden;
            background:rgba(6,12,23,.88);
            box-shadow:0 30px 80px rgba(0,0,0,.38);
        }
        .login-hero{
            padding:48px;
            background:linear-gradient(135deg, rgba(38,169,255,.22), rgba(7,17,32,.2));
            position:relative;
        }
        .login-hero::after{
            content:"";
            position:absolute;
            inset:0;
            background:radial-gradient(circle at top right, rgba(38,169,255,.18), transparent 35%);
            pointer-events:none;
        }
        .brand{
            display:flex;
            align-items:center;
            gap:14px;
            margin-bottom:28px;
        }
        .brand img{width:58px;height:auto}
        .brand strong{display:block;font-size:20px;letter-spacing:.08em}
        .brand span{color:var(--muted);font-size:13px}
        .hero-badge{
            display:inline-flex;
            padding:8px 14px;
            border-radius:999px;
            background:rgba(38,169,255,.12);
            border:1px solid rgba(38,169,255,.24);
            color:#a7dfff;
            font-size:12px;
            text-transform:uppercase;
            letter-spacing:.14em;
        }
        .login-hero h1{
            font-size:42px;
            line-height:1.05;
            margin:20px 0 16px;
            max-width:460px;
        }
        .login-hero p{
            max-width:470px;
            color:var(--muted);
            font-size:15px;
        }
        .hero-list{
            margin:28px 0 0;
            padding:0;
            list-style:none;
            display:grid;
            gap:12px;
        }
        .hero-list li{
            padding:14px 16px;
            border-radius:16px;
            background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.06);
            color:#dcecff;
        }
        .login-card{
            padding:42px 34px;
            background:rgba(8,16,30,.96);
        }
        .login-card h2{
            margin:0 0 8px;
            font-size:28px;
        }
        .login-card p{
            margin:0 0 22px;
            color:var(--muted);
            font-size:14px;
        }
        .flash-error{
            margin-bottom:18px;
            padding:12px 14px;
            border-radius:14px;
            background:rgba(255,141,141,.12);
            border:1px solid rgba(255,141,141,.22);
            color:#ffd3d3;
            font-size:14px;
        }
        form{display:grid;gap:14px}
        label{display:grid;gap:7px;font-size:14px;color:#dcecff}
        input{
            width:100%;
            border:none;
            outline:none;
            border-radius:14px;
            padding:14px 15px;
            background:var(--panel-soft);
            color:var(--text);
            border:1px solid transparent;
        }
        input:focus{border-color:rgba(38,169,255,.45)}
        button{
            margin-top:8px;
            border:none;
            border-radius:14px;
            padding:14px 16px;
            font-weight:700;
            color:white;
            cursor:pointer;
            background:linear-gradient(135deg, var(--primary), var(--primary-dark));
            box-shadow:0 18px 35px rgba(38,169,255,.24);
        }
        .login-note{
            margin-top:18px;
            padding:14px 16px;
            border-radius:14px;
            background:rgba(255,255,255,.03);
            border:1px solid rgba(255,255,255,.05);
            font-size:13px;
            color:var(--muted);
        }
        .login-note strong{color:#fff}
        @media (max-width: 860px){
            .login-shell{grid-template-columns:1fr}
            .login-hero{padding:32px 28px}
            .login-card{padding:32px 24px}
            .login-hero h1{font-size:34px}
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <section class="login-hero">
            <div class="brand">
                <img src="<?= cms_escape(cms_asset_url('images/logo_es.png')) ?>" alt="Elenge Space">
                <div>
                    <strong>ELENGE SPACE</strong>
                    <span>Backoffice de publication</span>
                </div>
            </div>
            <span class="hero-badge">Administration</span>
            <h1>Pilotez vos contenus depuis une interface simple et élégante.</h1>
            <p>Publiez les émissions vidéo, mettez à jour les podcasts, modifiez les actualités et gardez la page d’accueil toujours fraîche.</p>
            <ul class="hero-list">
                <li>Gestion rapide des émissions vidéo en vedette</li>
                <li>Publication des replays et podcasts</li>
                <li>Mise à jour des actus en quelques clics</li>
            </ul>
        </section>
        <section class="login-card">
            <h2>Connexion</h2>
            <p>Accédez au tableau de bord Elenge Space.</p>
            <?php if ($error !== null): ?>
                <div class="flash-error"><?= cms_escape($error) ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <label>
                    Identifiant
                    <input type="text" name="username" placeholder="admin" value="<?= cms_escape($_POST['username'] ?? '') ?>">
                </label>
                <label>
                    Mot de passe
                    <input type="password" name="password" placeholder="Votre mot de passe">
                </label>
                <button type="submit">Se connecter</button>
            </form>
            <div class="login-note">
                <strong>Compte initial</strong><br>
                Identifiant: admin<br>
                Mot de passe: Admin@123456
            </div>
        </section>
    </div>
</body>
</html>
