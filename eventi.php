<?php
session_start();
include "Connessione.php";

$accesso     = $_SESSION['accesso'];
$NomeUtente  = $_SESSION['user'];

if ($accesso != 1) {
    header("location: Index.php");
} else {
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asteria - Eventi</title>
    <link rel="icon" type="image/x-icon" href="LogoIcona.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="StileAsteria.css">
    <link rel="stylesheet" href="StileEventi.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-0">

            <div class="nav-section-left d-flex align-items-center justify-content-center">
                <a class="navbar-brand d-flex align-items-center m-0" href="#">
                    <img src="LogoTrasparente.png" alt="Asteria Logo" class="logo-img">
                    <small id="current-date" class="text-secondary ms-2"
                           style="font-size:0.7rem; letter-spacing:1px; white-space:nowrap;"></small>
                </a>
            </div>

            <div class="flex-grow-1"></div>

            <div class="nav-section-right d-flex align-items-center justify-content-center">
                <div class="theme-switch-wrapper me-3">
                    <button id="theme-toggle">🌙</button>
                </div>
                <button class="btn btn-custom rounded-pill fw-bold"
                        onclick="location.href='Profilo.php?user=<?= $NomeUtente ?>'">
                    Profilo Utente
                </button>
            </div>

        </div>
    </nav>

    <div class="contenitore">
        <div class="sezione sinistra">
            <div class="d-grid gap-2 p-3">
                <button class="btn btn-custom rounded-pill fw-bold"
                        onclick="location.href='CreaPost.php'">
                    <i class="fa fa-plus-circle me-2"></i>Crea
                </button>
            </div>
            <div class="mt-3">
                <li class="nav-item"><a class="nav-link px-3" href="Asteria.php">Home</a></li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="eventi.php"
                       style="color: var(--primary-color) !important;">
                        <i class="fa fa-star me-1"></i>Eventi
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link px-3" href="Asteria.php">Notifiche</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="Asteria.php">Ricerca</a></li>
            </div>
        </div>
        <div class="sezione centro">

            <!-- Tab bar -->
            <div class="eventi-tabs">
                <button class="active" onclick="mostraTab('apod', this)">
                    <i class="fa fa-picture-o me-1"></i> Foto del Giorno
                </button>
                <button onclick="mostraTab('asteroidi', this)">
                    <i class="fa fa-circle-o me-1"></i> Asteroidi
                </button>
                <button onclick="mostraTab('eonet', this)">
                    <i class="fa fa-globe me-1"></i> Eventi Terrestri
                </button>
            </div>

            <!-- ─── Pannello APOD ─── -->
            <div id="tab-apod" class="pannello attivo">
                <div class="pannello-header">
                    <h2><i class="fa fa-picture-o me-2" style="color:var(--primary-color)"></i>Astronomy Picture of the Day</h2>
                </div>
                <div id="apod-content">
                    <div class="loading-box">
                        <div class="spinner"></div>
                        <span>Caricamento in corso…</span>
                    </div>
                </div>
            </div>

            <!-- ─── Pannello Asteroidi ─── -->
            <div id="tab-asteroidi" class="pannello">
                <div class="pannello-header">
                    <h2><i class="fa fa-circle-o me-2" style="color:var(--primary-color)"></i>Asteroidi in avvicinamento</h2>
                    <span id="asteroid-count" class="count-badge"></span>
                </div>

                <div class="date-row">
                    <label for="ast-start">Dal</label>
                    <input type="date" id="ast-start">
                    <label for="ast-end">Al</label>
                    <input type="date" id="ast-end">
                    <button class="btn-cerca" onclick="caricaAsteroidi()">
                        <i class="fa fa-search me-1"></i>Cerca
                    </button>
                </div>

                <div id="asteroidi-content">
                    <div class="loading-box">
                        <div class="spinner"></div>
                        <span>Caricamento in corso…</span>
                    </div>
                </div>
            </div>

            <!-- ─── Pannello EONET ─── -->
            <div id="tab-eonet" class="pannello">
                <div class="pannello-header">
                    <h2><i class="fa fa-globe me-2" style="color:var(--primary-color)"></i>Eventi Naturali (EONET)</h2>
                    <span id="eonet-count" class="count-badge"></span>
                </div>
                <div id="eonet-content">
                    <div class="loading-box">
                        <div class="spinner"></div>
                        <span>Caricamento in corso…</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- ── Sidebar destra ── -->
        <div class="sezione destra">
            <li class="nav-item"><a class="nav-link px-3">Seguiti</a></li>
        </div>

    </div><!-- fine .contenitore -->


    <!-- ═══════════════ SCRIPT ═══════════════ -->
    <script src="config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>

    /* ── Chiave API NASA ──────────────────────────────────────────
       Registrati gratuitamente su https://api.nasa.gov per ottenere
       la tua chiave personale e sostituisci DEMO_KEY qui sotto.
    ─────────────────────────────────────────────────────────────── */
    const NASA_KEY = 'DEMO_KEY';

    /* ─────────────────────────────────────────────────────────────
       UTILITÀ
    ─────────────────────────────────────────────────────────────── */
    function oggi() {
        return new Date().toISOString().split('T')[0];
    }

    function aggiungiGiorni(dateStr, n) {
        const d = new Date(dateStr);
        d.setDate(d.getDate() + n);
        return d.toISOString().split('T')[0];
    }

    function formatData(dateStr) {
        if (!dateStr) return '—';
        return new Date(dateStr).toLocaleDateString('it-IT', {
            day: '2-digit', month: 'long', year: 'numeric'
        });
    }

    function mostraLoading(id, testo = 'Caricamento in corso…') {
        document.getElementById(id).innerHTML =
            `<div class="loading-box"><div class="spinner"></div><span>${testo}</span></div>`;
    }

    function mostraErrore(id, msg) {
        document.getElementById(id).innerHTML =
            `<div class="error-box"><i class="fa fa-exclamation-triangle me-2"></i>${msg}</div>`;
    }

    /* ─────────────────────────────────────────────────────────────
       TAB SWITCH
    ─────────────────────────────────────────────────────────────── */
    function mostraTab(nome, btn) {
        document.querySelectorAll('.pannello').forEach(p => p.classList.remove('attivo'));
        document.querySelectorAll('.eventi-tabs button').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + nome).classList.add('attivo');
        btn.classList.add('active');
    }

    /* ─────────────────────────────────────────────────────────────
       1)  APOD
    ─────────────────────────────────────────────────────────────── */
    async function caricaAPOD() {
        try {
            const res = await fetch('api/apod.php');
            if (!res.ok) throw new Error();
            const d    = await res.json();

            const media = d.media_type === 'video'
                ? `<iframe src="${d.url}" allowfullscreen></iframe>`
                : `<img src="${d.hdurl || d.url}" alt="${d.title}">`;

            document.getElementById('apod-content').innerHTML = `
                <div class="apod-card">
                    ${media}
                    <div class="apod-body">
                        <div class="apod-title">${d.title}</div>
                        <div class="apod-meta">
                            <i class="fa fa-calendar me-1"></i>${formatData(d.date)}
                            ${d.copyright
                                ? `&nbsp;·&nbsp;<i class="fa fa-copyright me-1"></i>${d.copyright.trim()}`
                                : ''}
                        </div>
                        <p class="apod-testo">${d.explanation}</p>
                    </div>
                </div>`;
        } catch {
            mostraErrore('apod-content', 'Impossibile caricare la foto del giorno. Riprova più tardi.');
        }
    }

    /* ─────────────────────────────────────────────────────────────
       2)  ASTEROIDI (NeoWs)
    ─────────────────────────────────────────────────────────────── */
    function initDatePicker() {
        const start = oggi();
        document.getElementById('ast-start').value = start;
        document.getElementById('ast-end').value   = aggiungiGiorni(start, 6);
    }

    async function caricaAsteroidi() {
        const start = document.getElementById('ast-start').value || oggi();
        const end   = document.getElementById('ast-end').value   || aggiungiGiorni(oggi(), 6);
        const diff  = (new Date(end) - new Date(start)) / 86_400_000;

        if (diff < 0 || diff > 7) {
            mostraErrore('asteroidi-content',
                'L\'intervallo di date deve essere compreso tra 1 e 7 giorni.');
            return;
        }

        mostraLoading('asteroidi-content', 'Ricerca asteroidi in corso…');
        document.getElementById('asteroid-count').style.display = 'none';

        try {
            const res  = await fetch(
                `https://api.nasa.gov/neo/rest/v1/feed?start_date=${start}&end_date=${end}&api_key=${NASA_KEY}`
            );
            if (!res.ok) throw new Error();
            const data = await res.json();

            const tutti = Object.values(data.near_earth_objects).flat();
            tutti.sort((a, b) => {
                const dA = parseFloat(a.close_approach_data[0]?.miss_distance?.kilometers || 0);
                const dB = parseFloat(b.close_approach_data[0]?.miss_distance?.kilometers || 0);
                return dA - dB;
            });

            const badge = document.getElementById('asteroid-count');
            badge.textContent     = tutti.length + ' trovati';
            badge.style.display   = 'inline-block';

            if (tutti.length === 0) {
                document.getElementById('asteroidi-content').innerHTML =
                    `<div class="loading-box">
                        <i class="fa fa-check-circle" style="font-size:2rem;color:var(--primary-color)"></i>
                        <span>Nessun asteroide rilevato nell'intervallo selezionato.</span>
                     </div>`;
                return;
            }

            const cards = tutti.map(ast => {
                const pericoloso = ast.is_potentially_hazardous_asteroid;
                const avv  = ast.close_approach_data[0] || {};
                const dMin = parseFloat(ast.estimated_diameter.meters.estimated_diameter_min).toFixed(0);
                const dMax = parseFloat(ast.estimated_diameter.meters.estimated_diameter_max).toFixed(0);
                const dist = parseFloat(avv.miss_distance?.kilometers || 0)
                               .toLocaleString('it-IT', { maximumFractionDigits: 0 });
                const vel  = parseFloat(avv.relative_velocity?.kilometers_per_hour || 0)
                               .toLocaleString('it-IT', { maximumFractionDigits: 0 });
                const data_av = formatData(avv.close_approach_date);

                return `
                <div class="asteroid-card ${pericoloso ? 'pericoloso' : ''}">
                    <div class="asteroid-nome">${ast.name}</div>
                    <span class="asteroid-badge ${pericoloso ? 'badge-danger' : 'badge-safe'}">
                        ${pericoloso ? '⚠️ Potenzialmente pericoloso' : '✅ Non pericoloso'}
                    </span>
                    <div class="asteroid-info">
                        <span><i class="fa fa-calendar"></i>${data_av}</span>
                        <span><i class="fa fa-arrows-h"></i>${dMin}–${dMax} m</span>
                        <span><i class="fa fa-road"></i>${dist} km</span>
                        <span><i class="fa fa-tachometer"></i>${vel} km/h</span>
                    </div>
                </div>`;
            }).join('');

            document.getElementById('asteroidi-content').innerHTML =
                `<div class="asteroid-grid">${cards}</div>`;

        } catch {
            mostraErrore('asteroidi-content',
                'Impossibile caricare i dati sugli asteroidi. Riprova più tardi.');
        }
    }

    /* ─────────────────────────────────────────────────────────────
       3)  EONET
    ─────────────────────────────────────────────────────────────── */
    const eonetEmoji = {
        'Wildfires':           '🔥',
        'Severe Storms':       '⛈️',
        'Volcanoes':           '🌋',
        'Floods':              '🌊',
        'Earthquakes':         '🫨',
        'Drought':             '🏜️',
        'Dust and Haze':       '🌫️',
        'Landslides':          '⛰️',
        'Sea and Lake Ice':    '🧊',
        'Snow':                '❄️',
        'Temperature Extremes':'🌡️',
        'Manmade':             '🏭',
        'Water Color':         '💧',
    };

    function getEmoji(categories) {
        if (!categories?.length) return '🌍';
        return eonetEmoji[categories[0].title] || '🌍';
    }

    async function caricaEONET() {
        try {
            const res  = await fetch(
                `https://eonet.gsfc.nasa.gov/api/v3/events?status=open&limit=50&api_key=${NASA_KEY}`
            );
            if (!res.ok) throw new Error();
            const data = await res.json();
            const eventi = data.events || [];

            const badge = document.getElementById('eonet-count');
            badge.textContent   = eventi.length + ' attivi';
            badge.style.display = 'inline-block';

            if (eventi.length === 0) {
                document.getElementById('eonet-content').innerHTML =
                    `<div class="loading-box">
                        <i class="fa fa-check-circle" style="font-size:2rem;color:var(--primary-color)"></i>
                        <span>Nessun evento naturale attivo al momento.</span>
                     </div>`;
                return;
            }

            const items = eventi.map(ev => {
                const ultima   = ev.geometry?.[ev.geometry.length - 1];
                const dataEv   = ultima?.date ? formatData(ultima.date.split('T')[0]) : '—';
                const emoji    = getEmoji(ev.categories);
                const cat      = ev.categories?.map(c => c.title).join(', ') || 'Evento';
                const source   = ev.sources?.[0]?.url || '#';

                return `
                <div class="eonet-card">
                    <div class="eonet-icona">${emoji}</div>
                    <div class="eonet-corpo">
                        <div class="eonet-titolo">
                            <a href="${source}" target="_blank" rel="noopener noreferrer">
                                ${ev.title}
                                <i class="fa fa-external-link ms-1" style="font-size:0.65rem;opacity:0.45;"></i>
                            </a>
                        </div>
                        <div class="eonet-meta">
                            <i class="fa fa-clock-o me-1"></i>Ultimo aggiornamento: ${dataEv}
                        </div>
                        <span class="eonet-categoria">${cat}</span>
                    </div>
                </div>`;
            }).join('');

            document.getElementById('eonet-content').innerHTML =
                `<div class="eonet-lista">${items}</div>`;

        } catch {
            mostraErrore('eonet-content',
                'Impossibile caricare gli eventi naturali. Riprova più tardi.');
        }
    }

    /* ─────────────────────────────────────────────────────────────
       INIT
    ─────────────────────────────────────────────────────────────── */
    initDatePicker();
    caricaAPOD();
    caricaAsteroidi();
    caricaEONET();

    </script>

</body>
</html>
<?php } ?>
