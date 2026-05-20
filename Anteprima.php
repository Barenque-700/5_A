<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asteria - Social Astronomy</title>
    <link rel="icon" type="image/x-icon" href="LogoIcona.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="StileAsteria.css">
    <link rel="stylesheet" href="StileAnteprima.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-0">
            <div class="nav-section-left d-flex align-items-center justify-content-center">
                <a class="navbar-brand d-flex align-items-center m-0" href="#">
                    <img src="LogoTrasparente.png" alt="Asteria Logo" class="logo-img">
                    <small id="current-date" class="text-secondary ms-2" style="font-size: 0.7rem; letter-spacing: 1px; white-space: nowrap;"></small>
                </a>
            </div>

            <div class="flex-grow-1"></div>
 
            <div class="nav-section-right d-flex align-items-center justify-content-center">
                <div class="theme-switch-wrapper me-3">
                    <button id="theme-toggle">🌙</button>
                </div>
                <button class="btn btn-custom rounded-pill fw-bold me-2" onclick="location.href='Index.php'">Accedi</button>
                <button class="btn btn-custom rounded-pill fw-bold" onclick="location.href='Iscrizione.php'">Iscriviti</button>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <h1>Il social per chi<br>guarda le <span>stelle</span></h1>
        <p>Condividi le tue fotografie astronomiche, scopri gli scatti della community e connettiti con appassionati di tutto il mondo.</p>
        <div class="hero-buttons">
            <button class="btn btn-custom rounded-pill fw-bold px-4" onclick="location.href='Iscrizione.php'">
                <i class="fa-solid fa-user-plus me-2"></i>Registrati Gratis
            </button>
            <button class="btn rounded-pill fw-bold px-4" 
                style="border: 2px solid var(--primary-color); color: var(--primary-color); background: transparent;"
                onclick="location.href='Index.php'">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Accedi
            </button>
        </div>
    </section>

    <section class="features-section">
        <h2><i class="fa-solid fa-star me-2"></i>Cosa puoi fare su Asteria</h2>
        <div class="row g-3 justify-content-center" style="max-width: 960px; margin: 0 auto;">
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fa-solid fa-camera" style="color: var(--primary-color);"></i>
                    <h5>Condividi Foto</h5>
                    <p>Carica i tuoi scatti di nebulose, pianeti, galassie e via lattea direttamente dal tuo dispositivo.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fa-solid fa-heart" style="color: var(--primary-color);"></i>
                    <h5>Like & Commenti</h5>
                    <p>Interagisci con i post degli altri utenti, lascia commenti e mostra apprezzamento con un like.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fa-solid fa-users" style="color: var(--primary-color);"></i>
                    <h5>Segui & Follower</h5>
                    <p>Segui gli astrofili che ti ispirano e costruisci la tua rete di appassionati del cielo notturno.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fa-solid fa-rocket" style="color: var(--primary-color);"></i>
                    <h5>NASA & Asteroidi</h5>
                    <p>Scopri ogni giorno l'immagine astronomica della NASA e gli ultimi aggiornamenti sugli asteroidi.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fa-solid fa-magnifying-glass" style="color: var(--primary-color);"></i>
                    <h5>Ricerca & Tag</h5>
                    <p>Trova foto per hashtag, scopri nuovi utenti e filtra i contenuti che ti interessano di più.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fa-solid fa-moon" style="color: var(--primary-color);"></i>
                    <h5>Dark Mode</h5>
                    <p>Interfaccia ottimizzata per la notte: preserva la tua visione durante le sessioni di osservazione.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <h2>Pronto a condividere il tuo <span>universo</span>?</h2>
        <p>Unisciti ad Asteria. La registrazione è gratuita.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <button class="btn btn-custom rounded-pill fw-bold px-4" onclick="location.href='Iscrizione.php'">
                <i class="fa-solid fa-user-plus me-2"></i>Crea il tuo account
            </button>
            <button class="btn rounded-pill fw-bold px-4"
                style="border: 2px solid var(--primary-color); color: var(--primary-color); background: transparent;"
                onclick="location.href='Index.php'">
                Ho già un account
            </button>
        </div>
    </section>
    <footer>
        <span><img src="LogoTrasparente.png" alt="Asteria" style="height:28px; opacity:0.6;"></span>
        <span>© 2025 Asteria — Social Media Astronomico</span>
        <span style="opacity:0.5; font-size:0.75rem;">Barbieri & Adday</span>
    </footer>

    <script src="config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>