<?php
$site = $siteContent ?? [];
$videos = $featuredVideos ?? [];
$podcasts = $podcastItems ?? [];
$headlineTitle = $newsContent['headline_title'] ?? '🎙️ Lancement officiel du studio vidéo Elenge Space';
$headlineBody = $newsContent['headline_body'] ?? 'Un nouvel espace équipé pour produire des émissions en live streaming multi-caméras, ouvert aux jeunes créateurs, artistes, entrepreneurs et associations.';
$newsList = $newsItems ?? [];

$heroTitle = $site['hero_title'] ?? 'Elenge Space — la webradio qui amplifie la voix des jeunes.';
$heroIntro = $site['hero_intro'] ?? 'Culture, débats, musique urbaine, entrepreneuriat, tech, inspiration… Branche-toi sur Elenge Space et retrouve les voix qui construisent l’Afrique d’aujourd’hui.';
$heroButtonLabel = $site['hero_button_label'] ?? '▶ Lancer la radio en ligne';
$radioStreamUrl = $site['radio_stream_url'] ?? 'https://elengespace-lbfdbroadcast.radioca.st/stream';
$contactEmail = $site['contact_email'] ?? 'elenge.space@gmail.com';
$contactPhone = $site['contact_phone'] ?? '+243 853 658 131';
$contactAddress = $site['contact_address'] ?? 'Kinshasa, République Démocratique du Congo';
$contactHandle = $site['contact_handle'] ?? '@ElengeSpace';
?>

<header>
  <div class="nav">
    <div class="logo-wrap">
      <img src="images/logo_es.png" alt="Elenge Space">
      <div class="logo-text">
        <span>ELENGE SPACE</span>
        <span>La voix de la jeunesse, l’écho du présent</span>
      </div>
    </div>

    <!-- Bouton hamburger -->
    <button class="nav-toggle" type="button" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="navMenu">
      <span></span><span></span><span></span>
    </button>

    <!-- Menu -->
    <nav class="nav-menu" id="navMenu" aria-label="Menu principal">
      <ul>
        <li><a href="#direct">En direct</a></li>
        <li><a href="#videos">Émissions vidéo</a></li>
        <li><a href="#podcasts">Replays & Podcasts</a></li>
        <li><a href="#actus">Actus</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>

      <a class="btn-primary nav-cta" href="#direct">Écouter en direct</a>
    </nav>
  </div>
</header>

<script>
  (() => {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.getElementById('navMenu');

    if (!navToggle || !navMenu) {
      return;
    }

    const closeMenu = () => {
      navToggle.classList.remove('is-open');
      navMenu.classList.remove('is-open');
      navToggle.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('nav-open');
    };

    const toggleMenu = () => {
      const isOpen = navMenu.classList.toggle('is-open');
      navToggle.classList.toggle('is-open', isOpen);
      navToggle.setAttribute('aria-expanded', String(isOpen));
      document.body.classList.toggle('nav-open', isOpen);
    };

    navToggle.addEventListener('click', toggleMenu);
    navMenu.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        closeMenu();
      }
    });

    document.addEventListener('click', (event) => {
      if (!navMenu.contains(event.target) && !navToggle.contains(event.target)) {
        closeMenu();
      }
    });
  })();
</script>















<style>

    /* Ajout de styles CSS pour adapter le lecteur à toute la largeur de la section */
 




/* Section DIRECT avec image de fond */
.hero-bg {
    background: url("images/plateau_elenge.png") center/cover no-repeat;
    padding-top: 60px;
    padding-bottom: 60px;
    border-radius: 0 0 30px 30px;
    position: relative;
}

.hero-bg::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.55);     /* assombrir l’image */
    backdrop-filter: blur(2px);
    border-radius: 0 0 30px 30px;
}

.hero-bg * {
    position: relative;
    z-index: 2;
}

 









.radio-player-pro {
    width: 100%;
    margin-top: 30px;
    padding: 25px;
    border-radius: 20px;
    background: linear-gradient(135deg, rgba(10,20,40,0.95), rgba(5,10,25,0.95));
    border: 1px solid rgba(0,160,255,0.4);
    box-shadow: 0 20px 50px rgba(0,0,0,0.6);
}

/* FORCER le lecteur à être GRAND */
.radio-player-pro .plyr {
    --plyr-color-main: #26A9FF;
    --plyr-audio-controls-background: transparent;
    --plyr-control-icon-size: 22px;
    --plyr-control-spacing: 18px;
    --plyr-range-thumb-height: 14px;
    --plyr-range-track-height: 6px;
}

.radio-player-pro audio {
    width: 100%;
}












.radio-card{
  width:100%;
  padding:22px 22px 18px;
  border-radius:24px;
  background: linear-gradient(135deg, rgba(10,18,40,0.86), rgba(5,10,25,0.88));
  border:1px solid rgba(38,169,255,0.35);
  box-shadow:
    0 22px 60px rgba(0,0,0,0.55),
    inset 0 1px 0 rgba(255,255,255,0.06);
  position:relative;
  overflow:hidden;
}

.radio-card::before{
  content:"";
  position:absolute;
  inset:-60px;
  background: radial-gradient(circle at 20% 20%, rgba(38,169,255,0.25), transparent 55%),
              radial-gradient(circle at 90% 60%, rgba(27,117,188,0.18), transparent 55%);
  filter: blur(18px);
  opacity:.9;
  pointer-events:none;
}

.radio-card__top{
  position:relative;
  z-index:2;
  display:flex;
  align-items:center;
  gap:14px;
  margin-bottom:14px;
}

.radio-live{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:7px 12px;
  border-radius:999px;
  background: rgba(239,68,68,0.10);
  border:1px solid rgba(239,68,68,0.28);
  color:#ffd3d3;
  font-size:12px;
  letter-spacing:.12em;
  text-transform:uppercase;
}

.live-dot{
  width:8px;height:8px;border-radius:50%;
  background:#ff4d4d;
  box-shadow:0 0 0 6px rgba(255,77,77,0.18);
  animation:pulse 1.2s infinite;
}

@keyframes pulse{
  0%{transform:scale(1);opacity:1}
  60%{transform:scale(1.25);opacity:.65}
  100%{transform:scale(1);opacity:1}
}

.radio-meta{display:flex;flex-direction:column;line-height:1.1;}
.radio-title{
  font-weight:800;
  letter-spacing:.08em;
  font-size:14px;
}
.radio-subtitle{
  margin-top:5px;
  font-size:12px;
  color:rgba(255,255,255,0.65);
}

.radio-card__player{
  position:relative;
  z-index:2;
}

/* --- PLYR SKIN --- */
.radio-card .plyr{
  border-radius:18px;
  background: rgba(255,255,255,0.04);
  border:1px solid rgba(148,163,184,0.22);
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
  padding:10px;
}

.radio-card .plyr--audio .plyr__controls{
  background: transparent !important;
}

.radio-card .plyr{
  --plyr-color-main:#26A9FF;
  --plyr-control-icon-size:20px;
  --plyr-control-spacing:14px;
  --plyr-font-size-base:14px;
  --plyr-audio-controls-background: transparent;
}

/* Bouton play plus “premium” */
.radio-card .plyr__control.plyr__control--overlaid,
.radio-card .plyr__controls .plyr__control{
  border-radius:14px;
}

.radio-card .plyr__controls .plyr__control{
  background: rgba(255,255,255,0.06);
  border:1px solid rgba(148,163,184,0.18);
}
.radio-card .plyr__controls .plyr__control:hover{
  background: rgba(38,169,255,0.14);
  border-color: rgba(38,169,255,0.35);
}

/* Progress plus doux */
.radio-card .plyr__progress__container{
  margin: 0 8px;
}
.radio-card .plyr--full-ui input[type=range]{
  height:6px;
}

.social-showcase{
  margin-top:28px;
  padding:24px;
  border-radius:24px;
  background: linear-gradient(135deg, rgba(9,18,38,0.96), rgba(14,35,72,0.95));
  border:1px solid rgba(38,169,255,0.20);
  box-shadow: 0 18px 40px rgba(0,0,0,0.22);
  color:#fff;
}

.social-showcase__head{
  display:flex;
  justify-content:space-between;
  align-items:flex-end;
  gap:16px;
  margin-bottom:18px;
}

.social-showcase__eyebrow{
  display:inline-block;
  margin-bottom:8px;
  color:#7fd0ff;
  font-size:12px;
  font-weight:700;
  letter-spacing:.14em;
  text-transform:uppercase;
}

.social-showcase h3{
  margin:0;
  font-size:28px;
  line-height:1.1;
}

.social-showcase p{
  margin:8px 0 0;
  color:rgba(255,255,255,0.72);
}

.social-handle{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:9px 14px;
  border-radius:999px;
  background:rgba(255,255,255,0.08);
  color:#dff3ff;
  font-size:13px;
  font-weight:600;
  white-space:nowrap;
}

.social-links{
  display:grid;
  grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
  gap:14px;
}

.social-link{
  display:flex;
  align-items:center;
  gap:14px;
  padding:15px 16px;
  border-radius:18px;
  text-decoration:none;
  color:#fff;
  background:rgba(255,255,255,0.06);
  border:1px solid rgba(255,255,255,0.08);
  transition:transform .2s ease, border-color .2s ease, background .2s ease, box-shadow .2s ease;
}

.social-link:hover{
  transform:translateY(-3px);
  border-color:rgba(38,169,255,0.35);
  background:rgba(255,255,255,0.10);
  box-shadow:0 14px 28px rgba(0,0,0,0.18);
}

.social-link__icon{
  width:48px;
  height:48px;
  flex:0 0 48px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  border-radius:16px;
  background:rgba(255,255,255,0.1);
}

.social-link__icon svg{
  width:22px;
  height:22px;
  fill:currentColor;
}

.social-link__content{
  display:flex;
  flex-direction:column;
  min-width:0;
}

.social-link__name{
  font-size:16px;
  font-weight:700;
}

.social-link__meta{
  margin-top:3px;
  color:rgba(255,255,255,0.68);
  font-size:13px;
}

.social-link--facebook{color:#9fd2ff;}
.social-link--twitter{color:#c8d7ff;}
.social-link--tiktok{color:#d6fefe;}
.social-link--youtube{color:#ffc0c0;}
.social-link--whatsapp{color:#cbffd9;}

@media (max-width: 767px){
  .social-showcase{
    padding:20px 16px;
    border-radius:20px;
  }

  .social-showcase__head{
    flex-direction:column;
    align-items:flex-start;
  }

  .social-showcase h3{
    font-size:24px;
  }

  .social-links{
    grid-template-columns:1fr;
  }
}

</style>






<main>
    <!-- HERO + PLAYER -->
<!-- Section HERO avec lecteur prenant toute la largeur -->


<section class="hero hero-bg" id="direct">
    <div class="hero-left">
        <h1><span>Elenge Space</span> — <?= htmlspecialchars($heroTitle, ENT_QUOTES, 'UTF-8') ?></h1>
        <p><?= nl2br(htmlspecialchars($heroIntro, ENT_QUOTES, 'UTF-8')) ?></p>

        <div class="hero-tags">
            <span class="pill">Webradio 100% Jeunes</span>
            <span class="pill outline">Kinshasa – RDC</span>
            <span class="pill outline">Live &amp; Replays vidéo</span>
        </div>

        <button class="btn-primary"><?= htmlspecialchars($heroButtonLabel, ENT_QUOTES, 'UTF-8') ?></button>
    </div>

     
        
 <div class="radio-card">
  <div class="radio-card__top">
    <div class="radio-live">
      <span class="live-dot"></span>
      <span>EN DIRECT</span>
    </div>

    <div class="radio-meta">
      <div class="radio-title">ELENGE SPACE</div>
      <div class="radio-subtitle">Live stream • MP3</div>
    </div>
  </div>

  <div class="radio-card__player">
    <audio id="radio-player" controls crossorigin>
      <source src="<?= htmlspecialchars($radioStreamUrl, ENT_QUOTES, 'UTF-8') ?>" type="audio/mpeg">
    </audio>
  </div>
</div>



 
</section>

 

<script>
  const player = new Plyr('#radio-player', {
    controls: ['play', 'progress', 'mute', 'volume'],
  });
</script>



    <!-- EMISSIONS VIDEO -->
    <section id="videos">
        <div class="section-head">
            <div>
                <h2 class="section-title">Émissions vidéo en vedette</h2>
                <p class="section-sub">Retrouve les replays vidéo de tes programmes préférés.</p>
            </div>
            <a href="#">Voir toutes les vidéos</a>
        </div>

        <div class="video-grid">
          <?php foreach ($videos as $video): ?>
          <article class="video-card">
            <div class="video-thumb">
              <?php if (($video['type'] ?? 'image') === 'embed' && !empty($video['embed_url'])): ?>
                <iframe src="<?= htmlspecialchars((string) $video['embed_url'], ENT_QUOTES, 'UTF-8') ?>"
                    title="<?= htmlspecialchars((string) ($video['title'] ?? 'Vidéo Elenge Space'), ENT_QUOTES, 'UTF-8') ?>"
                    allowfullscreen></iframe>
              <?php elseif (!empty($video['image'])): ?>
                <img src="<?= htmlspecialchars((string) $video['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) ($video['title'] ?? 'Vidéo Elenge Space'), ENT_QUOTES, 'UTF-8') ?>">
              <?php else: ?>
                <img src="images/plateau_elenge.png" alt="<?= htmlspecialchars((string) ($video['title'] ?? 'Vidéo Elenge Space'), ENT_QUOTES, 'UTF-8') ?>">
              <?php endif; ?>
            </div>
            <div class="video-body">
              <div class="video-title"><?= htmlspecialchars((string) ($video['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
              <div class="video-meta"><?= htmlspecialchars((string) ($video['meta'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
    </section>

    <!-- PODCASTS / REPLAYS AUDIO/VIDEO -->
    <section id="podcasts">
        <div class="section-head">
            <div>
                <h2 class="section-title">Replays &amp; Podcasts</h2>
                <p class="section-sub">Écoute les émissions quand tu veux, où tu veux.</p>
            </div>
            <a href="#">Voir tous les replays</a>
        </div>

        <div class="list">
          <?php foreach ($podcasts as $podcast): ?>
          <article class="list-item">
            <div class="list-tag"><?= htmlspecialchars((string) ($podcast['tag'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
            <div class="list-title"><?= htmlspecialchars((string) ($podcast['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
            <div class="list-meta"><?= htmlspecialchars((string) ($podcast['meta'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
          </article>
          <?php endforeach; ?>
        </div>
    </section>

    <!-- ACTUALITES -->
    <section id="actus">
        <div class="section-head">
            <div>
                <h2 class="section-title">Actus de la radio</h2>
                <p class="section-sub">Ce qui se passe en coulisses et dans la communauté Elenge Space.</p>
            </div>
            <a href="#">Toutes les news</a>
        </div>

        <div class="news-grid">
          <article class="news-main">
            <h3><?= htmlspecialchars($headlineTitle, ENT_QUOTES, 'UTF-8') ?></h3>
            <p><?= htmlspecialchars($headlineBody, ENT_QUOTES, 'UTF-8') ?></p>
          </article>

          <div class="news-list">
            <?php foreach ($newsList as $item): ?>
            <div class="news-item">
              <?= htmlspecialchars((string) ($item['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
              <span><?= htmlspecialchars((string) ($item['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact">
        <div class="section-head">
            <div>
                <h2 class="section-title">Contact &amp; dédicaces</h2>
                <p class="section-sub">Envie de proposer une émission, un partenariat ou de passer un message à l’antenne ?</p>
            </div>
        </div>

        <div class="contact">
            <div class="contact-info">
                <p>
                    Elenge Space est une webradio et un espace média dédié à la voix de la jeunesse.
                    Contacte-nous pour les demandes d’émissions, les collaborations ou la publicité.
                </p>

                <div class="contact-grid">
                    <div class="contact-card">
                        <strong>Adresse</strong>
                        <?= htmlspecialchars($contactAddress, ENT_QUOTES, 'UTF-8') ?><br>
                        (Studio Elenge Space)
                    </div>
                    <div class="contact-card">
                        <strong>Email</strong>
                        <?= htmlspecialchars($contactEmail, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <div class="contact-card">
                        <strong>Téléphone / WhatsApp</strong>
                        <?= htmlspecialchars($contactPhone, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <div class="contact-card">
                        <strong>Réseaux</strong>
                      <?= htmlspecialchars($contactHandle, ENT_QUOTES, 'UTF-8') ?> (Facebook, Twitter, TikTok, YouTube)
                    </div>
                </div>

                  <div class="social-showcase">
                    <div class="social-showcase__head">
                      <div>
                        <span class="social-showcase__eyebrow">Communauté Elenge Space</span>
                        <h3>Suivez-nous partout</h3>
                        <p>Retrouvez nos directs, extraits, coulisses et annonces sur tous nos réseaux.</p>
                      </div>
                      <span class="social-handle"><?= htmlspecialchars($contactHandle, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>

                    <div class="social-links">
                      <a class="social-link social-link--facebook" href="https://www.facebook.com/elengespace" target="_blank" rel="noopener noreferrer" aria-label="Facebook Elenge Space">
                        <span class="social-link__icon" aria-hidden="true">
                          <svg viewBox="0 0 24 24"><path d="M13.5 21v-7h2.4l.4-3h-2.8V9.3c0-.9.3-1.5 1.6-1.5H16V5.1c-.6-.1-1.4-.1-2.2-.1-2.2 0-3.8 1.3-3.8 3.9V11H7.5v3H10v7h3.5Z"/></svg>
                        </span>
                        <span class="social-link__content">
                          <span class="social-link__name">Facebook</span>
                          <span class="social-link__meta">Lives, annonces et communauté</span>
                        </span>
                      </a>

                      <a class="social-link social-link--twitter" href="https://x.com/ElengeSpace" target="_blank" rel="noopener noreferrer" aria-label="Twitter Elenge Space">
                        <span class="social-link__icon" aria-hidden="true">
                          <svg viewBox="0 0 24 24"><path d="M18.9 2H22l-6.8 7.8L23 22h-6.1l-4.8-6.3L6.6 22H3.5l7.3-8.4L1 2h6.3l4.3 5.7L18.9 2Zm-1.1 18h1.7L6.1 3.9H4.3L17.8 20Z"/></svg>
                        </span>
                        <span class="social-link__content">
                          <span class="social-link__name">X</span>
                          <span class="social-link__meta">Actualités, annonces et live tweets</span>
                        </span>
                      </a>

                      <a class="social-link social-link--tiktok" href="https://www.tiktok.com/@elenge_space" target="_blank" rel="noopener noreferrer" aria-label="TikTok Elenge Space">
                        <span class="social-link__icon" aria-hidden="true">
                          <svg viewBox="0 0 24 24"><path d="M14.6 3c.2 1.7 1.2 3.1 2.8 3.9 1 .5 2.1.8 3.3.8v3.1a8.2 8.2 0 0 1-3-.6v5.1a6.3 6.3 0 1 1-6.3-6.3c.3 0 .6 0 .9.1v3.2a3.1 3.1 0 1 0 2.3 3V3h3Z"/></svg>
                        </span>
                        <span class="social-link__content">
                          <span class="social-link__name">TikTok</span>
                          <span class="social-link__meta">Capsules courtes et tendances</span>
                        </span>
                      </a>

                      <a class="social-link social-link--youtube" href="https://youtube.com/@elengespace" target="_blank" rel="noopener noreferrer" aria-label="YouTube Elenge Space">
                        <span class="social-link__icon" aria-hidden="true">
                          <svg viewBox="0 0 24 24"><path d="M21.6 7.2a2.9 2.9 0 0 0-2-2C17.8 4.7 12 4.7 12 4.7s-5.8 0-7.6.5a2.9 2.9 0 0 0-2 2A30.5 30.5 0 0 0 1.9 12a30.5 30.5 0 0 0 .5 4.8 2.9 2.9 0 0 0 2 2c1.8.5 7.6.5 7.6.5s5.8 0 7.6-.5a2.9 2.9 0 0 0 2-2 30.5 30.5 0 0 0 .5-4.8 30.5 30.5 0 0 0-.5-4.8ZM10 15.6V8.4l6 3.6-6 3.6Z"/></svg>
                        </span>
                        <span class="social-link__content">
                          <span class="social-link__name">YouTube</span>
                          <span class="social-link__meta">Émissions vidéo et replays</span>
                        </span>
                      </a>

                      <a class="social-link social-link--whatsapp" href="https://wa.me/243853658131" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp Elenge Space">
                        <span class="social-link__icon" aria-hidden="true">
                          <svg viewBox="0 0 24 24"><path d="M12 2.2a9.7 9.7 0 0 0-8.4 14.6L2.2 22l5.4-1.4A9.8 9.8 0 1 0 12 2.2Zm0 17.6a7.9 7.9 0 0 1-4-1.1l-.3-.2-3.2.8.9-3.1-.2-.3A8 8 0 1 1 12 19.8Zm4.4-5.9c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.5.7c-.1.1-.2.2-.4.1a6.6 6.6 0 0 1-1.9-1.2 7.3 7.3 0 0 1-1.3-1.7c-.1-.2 0-.3.1-.4l.3-.3.2-.3a.4.4 0 0 0 0-.4l-.7-1.8c-.2-.4-.4-.3-.5-.3h-.5a.9.9 0 0 0-.6.3 2.6 2.6 0 0 0-.8 1.9c0 1.1.8 2.2 1 2.5.1.1 1.7 2.6 4.2 3.6 2.4 1 2.4.7 2.8.7.4 0 1.4-.6 1.5-1.1.2-.5.2-1 .2-1.1-.1-.1-.3-.2-.5-.3Z"/></svg>
                        </span>
                        <span class="social-link__content">
                          <span class="social-link__name">WhatsApp</span>
                          <span class="social-link__meta">Dédicaces et messages directs</span>
                        </span>
                      </a>
                    </div>
                  </div>
            </div>

            <div>
                <form action="#" method="post">
                    <input type="text" name="nom" placeholder="Nom complet">
                    <input type="email" name="email" placeholder="Adresse e-mail">
                    <input type="text" name="sujet" placeholder="Sujet (dédicace, collaboration...)">
                    <textarea name="message" placeholder="Ton message pour l’équipe Elenge Space"></textarea>
                    <button type="submit" class="btn-primary">Envoyer le message</button>
                </form>
            </div>
        </div>
    </section>
</main>

<footer>
    © <span id="year"></span> Elenge Space – La voix de la jeunesse, l’écho du présent.
</footer>

<script>
    // Met automatiquement l’année dans le footer
    document.getElementById('year').textContent = new Date().getFullYear();
</script>



<script>
    const player = new Plyr('#player', {
        controls: [
            'play', 'progress', 'current-time', 'mute', 'volume',
            'pip', 'airplay', 'fullscreen'
        ]
    });
</script>
