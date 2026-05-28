<?php

require_once __DIR__ . '/includes/cms.php';

// include('../pages/bdd.php');
 

//------------PHPMailer---------
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
//-----------------------------


$page = isset($_GET['page']) ? htmlentities((string) $_GET['page']) : '';
$pages = scandir('pages');

$cmsContent = cms_load_content();
$siteContent = $cmsContent['site'] ?? [];
$featuredVideos = cms_filter_published($cmsContent['featured_videos'] ?? []);
$podcastItems = cms_filter_published($cmsContent['podcasts'] ?? []);
$newsContent = $cmsContent['news'] ?? ['headline_title' => '', 'headline_body' => '', 'items' => []];
$newsItems = cms_filter_published($newsContent['items'] ?? []);

if(!empty($page) && in_array($_GET['page'].".php",$pages)) {
  
    $content = 'pages/'.$_GET['page'].".php";
} 
   
   
 else{
  header("Location:index.php?page=accueil");
    exit;
}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Elenge Space – Webradio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Police Google (optionnel) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Ajouter dans la section <head> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/plyr@3.7.2/dist/plyr.css" />
    <script src="https://cdn.jsdelivr.net/npm/plyr@3.7.2/dist/plyr.polyfilled.js"></script>

    <style>
        :root{
            --blue-dark:#165C9D;   /* bleu inspiré du logo */
            --blue:#1B75BC;
            --blue-light:#26A9FF;
            --bg:#05070d;
            --text:#f5f7fb;
            --muted:#9da9c8;
        }

        *{box-sizing:border-box;margin:0;padding:0;}

        body{
            font-family:'Montserrat',system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
            background: radial-gradient(circle at top left,#041326,#05070d 55%,#000 100%);
            color:var(--text);
            line-height:1.6;
        }

        a{text-decoration:none;color:inherit;}
        img{max-width:100%;display:block;}

        /* HEADER */
        header{
            position:sticky;
            top:0;
            z-index:50;
            backdrop-filter:blur(14px);
            background:rgba(2,8,23,0.85);
            border-bottom:1px solid rgba(255,255,255,0.04);
        }
        .nav{
            max-width:1180px;
            margin:0 auto;
            padding:0.7rem 1.5rem;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:1rem;
            position:relative;
        }
        .logo-wrap{
            display:flex;
            align-items:center;
            gap:.8rem;
            min-width:0;
            flex:0 0 auto;
        }
        .logo-wrap img{
            height:46px;
        }
        .logo-text{
            display:flex;
            flex-direction:column;
        }
        .logo-text span:first-child{
            font-weight:800;
            letter-spacing:.08em;
            font-size:.9rem;
            white-space:nowrap;
        }
        .logo-text span:last-child{
            font-size:.7rem;
            color:var(--muted);
        }
        .nav-menu{
            display:flex;
            align-items:center;
            justify-content:flex-end;
            gap:1.15rem;
        }
        nav ul{
            display:flex;
            align-items:center;
            gap:1.4rem;
            list-style:none;
            font-size:.9rem;
        }
        nav a{
            position:relative;
            padding:.2rem 0;
        }
        nav a::after{
            content:"";
            position:absolute;
            left:0;bottom:-4px;
            width:0;
            height:2px;
            border-radius:999px;
            background:linear-gradient(90deg,var(--blue-light),var(--blue));
            transition:.25s;
        }
        nav a:hover::after{width:100%;}
        .nav-cta{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            white-space:nowrap;
        }
        .nav-cta::after{display:none;}

        .nav-toggle{
            display:none;
            width:46px;
            height:46px;
            border:1px solid rgba(148,163,184,0.24);
            border-radius:16px;
            background:linear-gradient(145deg,rgba(15,23,42,0.98),rgba(27,117,188,0.22));
            box-shadow:0 14px 32px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.08);
            cursor:pointer;
            align-items:center;
            justify-content:center;
            flex-direction:column;
            gap:5px;
            color:var(--text);
            -webkit-tap-highlight-color:transparent;
        }
        .nav-toggle span{
            width:20px;
            height:2px;
            border-radius:999px;
            background:linear-gradient(90deg,#fff,var(--blue-light));
            box-shadow:0 0 12px rgba(38,169,255,0.35);
            transition:transform .25s ease,opacity .2s ease,width .25s ease;
        }
        .nav-toggle span:nth-child(2){width:16px;}
        .nav-toggle.is-open span:nth-child(1){transform:translateY(7px) rotate(45deg);}
        .nav-toggle.is-open span:nth-child(2){opacity:0;transform:scaleX(0);}
        .nav-toggle.is-open span:nth-child(3){transform:translateY(-7px) rotate(-45deg);}

        .btn-primary{
            padding:.5rem 1.1rem;
            border-radius:999px;
            background:linear-gradient(135deg,var(--blue-light),var(--blue));
            border:none;
            color:#fff;
            font-size:.85rem;
            font-weight:600;
            box-shadow:0 8px 25px rgba(0,163,255,0.35);
            cursor:pointer;
            transition:transform .15s,box-shadow .15s;
        }
        .btn-primary:hover{
            transform:translateY(-1px);
            box-shadow:0 12px 32px rgba(0,163,255,0.45);
        }

        /* HERO */
        .hero{
            max-width:1180px;
            margin:1.6rem auto 2.5rem;
            padding:0 1.5rem;
            display:grid;
            grid-template-columns: minmax(0,1.2fr) minmax(0,1fr);
            gap:2rem;
            align-items:center;
        }
        .hero-left h1{
            font-size:2.4rem;
            line-height:1.1;
            margin-bottom:1rem;
        }
        .hero-left h1 span{
            background:linear-gradient(135deg,var(--blue-light),var(--blue));
            -webkit-background-clip:text;
            color:transparent;
        }
        .hero-left p{
            max-width:34rem;
            color:var(--muted);
            margin-bottom:1.3rem;
            font-size:.95rem;
        }
        .hero-tags{
            display:flex;
            flex-wrap:wrap;
            gap:.6rem;
            margin-bottom:1.7rem;
        }
        .pill{
            border-radius:999px;
            padding:.25rem .7rem;
            font-size:.7rem;
            text-transform:uppercase;
            letter-spacing:.08em;
            background:rgba(37,99,235,0.1);
            border:1px solid rgba(37,99,235,0.35);
            color:var(--blue-light);
        }
        .pill.outline{
            background:transparent;
            color:var(--muted);
            border:1px solid rgba(148,163,184,0.35);
        }

        /* Player bloc */
        .live-card{
            border-radius:1.3rem;
            padding:1rem 1.1rem;
            background:radial-gradient(circle at top left,rgba(37,99,235,0.28),rgba(15,23,42,0.98));
            border:1px solid rgba(148,163,184,0.35);
            box-shadow:0 18px 40px rgba(15,23,42,0.85);
            display:flex;
            flex-direction:column;
            gap:.7rem;
            max-width:23rem;
        }
        .live-label{
            font-size:.7rem;
            text-transform:uppercase;
            letter-spacing:.18em;
            color:var(--muted);
        }
        .live-badge{
            display:inline-flex;
            align-items:center;
            gap:.4rem;
            padding:.2rem .65rem;
            border-radius:999px;
            background:rgba(239,68,68,0.08);
            color:#fecaca;
            font-size:.7rem;
        }
        .dot{
            width:7px;height:7px;
            border-radius:50%;
            background:#f97373;
            box-shadow:0 0 0 6px rgba(248,113,113,0.35);
        }
        .now-title{
            font-weight:600;
            font-size:1rem;
        }
        .now-meta{
            font-size:.8rem;
            color:var(--muted);
        }
        .player-bar{
            height:4px;
            background:rgba(148,163,184,0.25);
            border-radius:999px;
            overflow:hidden;
            margin:.4rem 0 .1rem;
        }
        .player-bar span{
            display:block;
            width:35%;
            height:100%;
            background:linear-gradient(90deg,var(--blue-light),var(--blue));
        }
        .player-time{
            display:flex;
            justify-content:space-between;
            font-size:.7rem;
            color:var(--muted);
        }
        .player-controls{
            margin-top:.6rem;
            display:flex;
            align-items:center;
            gap:.7rem;
        }
        .player-btn{
            width:42px;height:42px;
            border-radius:50%;
            border:none;
            background:#fff;
            color:var(--blue-dark);
            font-weight:700;
            cursor:pointer;
            box-shadow:0 12px 30px rgba(255,255,255,0.18);
        }
        .player-controls small{
            font-size:.75rem;
            color:var(--muted);
        }

        .hero-right{
            justify-self:end;
        }
        .hero-stats{
            display:grid;
            grid-template-columns:repeat(2,minmax(0,1fr));
            gap:.7rem;
            margin-top:1rem;
            font-size:.75rem;
        }
        .stat{
            padding:.6rem .7rem;
            border-radius:.9rem;
            background:rgba(15,23,42,0.8);
            border:1px solid rgba(148,163,184,0.25);
        }
        .stat strong{display:block;font-size:.95rem;}
        .stat span{color:var(--muted);}

        /* SECTIONS GENERIQUES */
        section{
            max-width:1180px;
            margin:0 auto 2.8rem;
            padding:0 1.5rem;
        }
        .section-head{
            display:flex;
            justify-content:space-between;
            align-items:flex-end;
            gap:1rem;
            margin-bottom:1.4rem;
        }
        .section-title{
            font-size:1.3rem;
            font-weight:600;
        }
        .section-sub{
            font-size:.86rem;
            color:var(--muted);
        }
        .section-head a{
            font-size:.78rem;
            color:var(--blue-light);
            text-transform:uppercase;
            letter-spacing:.16em;
        }

        /* VIDEOS */
        .video-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
            gap:1.3rem;
        }
        .video-card{
            background:rgba(15,23,42,0.94);
            border-radius:1rem;
            overflow:hidden;
            border:1px solid rgba(148,163,184,0.25);
            display:flex;
            flex-direction:column;
        }
        .video-thumb{
            position:relative;
            padding-top:56%;
            overflow:hidden;
        }
        .video-thumb iframe,
        .video-thumb img{
            position:absolute;
            inset:0;
            width:100%;
            height:100%;
            border:0;
        }
        .video-body{
            padding:.8rem .9rem 1rem;
        }
        .video-title{
            font-size:.95rem;
            font-weight:600;
            margin-bottom:.15rem;
        }
        .video-meta{
            font-size:.78rem;
            color:var(--muted);
        }

        /* PODCASTS / REPLAYS (audio ou vidéos sans vignette) */
        .list{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
            gap:1rem;
        }
        .list-item{
            padding:.9rem 1rem;
            border-radius:1rem;
            background:rgba(15,23,42,0.9);
            border:1px solid rgba(148,163,184,0.25);
        }
        .list-tag{
            font-size:.7rem;
            text-transform:uppercase;
            letter-spacing:.12em;
            color:var(--blue-light);
            margin-bottom:.25rem;
        }
        .list-title{
            font-size:.95rem;
            font-weight:600;
        }
        .list-meta{
            font-size:.78rem;
            color:var(--muted);
            margin-top:.25rem;
        }

        /* ACTUALITES */
        .news-grid{
            display:grid;
            grid-template-columns:2fr 2fr;
            gap:1.2rem;
        }
        .news-main{
            padding:1rem 1.1rem;
            border-radius:1.1rem;
            background:linear-gradient(135deg,rgba(37,99,235,0.22),rgba(15,23,42,0.98));
            border:1px solid rgba(148,163,184,0.35);
        }
        .news-main h3{margin-bottom:.35rem;}
        .news-main p{font-size:.88rem;color:var(--muted);}
        .news-list{
            display:flex;
            flex-direction:column;
            gap:.7rem;
        }
        .news-item{
            padding:.6rem .7rem;
            border-radius:.9rem;
            background:rgba(15,23,42,0.85);
            border:1px solid rgba(148,163,184,0.2);
            font-size:.84rem;
        }
        .news-item span{
            display:block;
            font-size:.74rem;
            color:var(--muted);
        }

        /* CONTACT / FOOTER */
        .contact{
            display:grid;
            grid-template-columns:minmax(0,1.3fr) minmax(0,1fr);
            gap:2rem;
        }
        .contact-info p{
            font-size:.9rem;
            color:var(--muted);
            margin-bottom:.7rem;
        }
        .contact-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
            gap:.9rem;
            margin-top:.7rem;
            font-size:.83rem;
        }
        .contact-card{
            padding:.7rem .85rem;
            border-radius:.9rem;
            background:rgba(15,23,42,0.9);
            border:1px solid rgba(148,163,184,0.25);
        }
        .contact-card strong{
            display:block;
            margin-bottom:.2rem;
        }
        form{
            display:grid;
            gap:.7rem;
        }
        input,textarea{
            width:100%;
            border-radius:.7rem;
            border:1px solid rgba(148,163,184,0.3);
            background:rgba(15,23,42,0.9);
            color:var(--text);
            padding:.55rem .8rem;
            font-size:.85rem;
        }
        textarea{min-height:120px;resize:vertical;}
        input::placeholder,textarea::placeholder{color:#6b7280;}
        footer{
            border-top:1px solid rgba(148,163,184,0.25);
            margin-top:2.5rem;
            padding:1.1rem 1.5rem 1.4rem;
            font-size:.78rem;
            color:var(--muted);
            text-align:center;
        }

        /* RESPONSIVE */
        @media (max-width:900px){
            .hero{
                grid-template-columns:1fr;
            }
            .hero-right{
                justify-self:start;
            }
            .news-grid{
                grid-template-columns:1fr;
            }
            .contact{
                grid-template-columns:1fr;
            }
        }
        @media (max-width:700px){
            body.nav-open{
                overflow:hidden;
            }
            .nav{
                padding:.65rem 1rem;
                gap:.75rem;
            }
            .logo-wrap img{
                height:42px;
            }
            .logo-text span:first-child{
                font-size:.78rem;
            }
            .logo-text span:last-child{
                max-width:180px;
                overflow:hidden;
                text-overflow:ellipsis;
                white-space:nowrap;
            }
            .nav-toggle{
                display:flex;
            }
            .nav-menu{
                position:absolute;
                top:calc(100% + 12px);
                left:1rem;
                right:1rem;
                display:grid;
                grid-template-columns:1fr;
                justify-content:stretch;
                align-items:stretch;
                gap:.85rem;
                padding:1rem;
                border-radius:24px;
                background:
                    radial-gradient(circle at top left,rgba(38,169,255,0.22),transparent 42%),
                    linear-gradient(160deg,rgba(3,10,24,0.98),rgba(8,22,44,0.97));
                border:1px solid rgba(148,163,184,0.22);
                box-shadow:0 24px 70px rgba(0,0,0,0.55), inset 0 1px 0 rgba(255,255,255,0.08);
                backdrop-filter:blur(18px);
                opacity:0;
                visibility:hidden;
                transform:translateY(-12px) scale(.98);
                pointer-events:none;
                transition:opacity .24s ease,transform .24s ease,visibility .24s ease;
            }
            .nav-menu.is-open{
                opacity:1;
                visibility:visible;
                transform:translateY(0) scale(1);
                pointer-events:auto;
            }
            nav ul{
                width:100%;
                display:grid;
                grid-template-columns:1fr;
                gap:.55rem;
            }
            nav li{width:100%;}
            nav a{
                display:flex;
                align-items:center;
                justify-content:flex-start;
                gap:.65rem;
                width:100%;
                padding:.85rem .95rem;
                border-radius:16px;
                color:#eaf6ff;
                background:rgba(255,255,255,0.045);
                border:1px solid rgba(255,255,255,0.06);
            }
            nav ul a::before{
                content:"";
                width:8px;
                height:8px;
                margin-right:.7rem;
                border-radius:999px;
                background:var(--blue-light);
                box-shadow:0 0 18px rgba(38,169,255,0.65);
                flex:0 0 auto;
            }
            nav a::after{
                display:none;
            }
            nav a:hover,
            nav a:focus-visible{
                background:rgba(38,169,255,0.14);
                border-color:rgba(38,169,255,0.32);
                outline:none;
            }
            .nav-menu .nav-cta{
                min-height:48px;
                padding:.9rem 1rem;
                font-weight:800;
                letter-spacing:.01em;
                box-shadow:0 14px 34px rgba(0,163,255,0.32);
                justify-content:center;
            }
            .hero-left h1{
                font-size:2rem;
            }
        }
        @media (max-width:420px){
            .logo-text span:last-child{display:none;}
            .nav-menu{left:.75rem;right:.75rem;}
        }
    </style>
</head>
<body>






      <?php
        include($content);
      ?>
         

      

</body>
</html>
