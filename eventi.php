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
                       style="color: var(--primary-color) !important;">Eventi
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link px-3" href="Asteria.php">Notifiche</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="Ricerca.php">Ricerca</a></li>
            </div>
        </div>

        <div class="sezione centro">

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

            <div id="tab-apod" class="pannello attivo">
                <div class="pannello-header">
                    <h2><i class="fa fa-picture-o me-2" style="color:var(--primary-color)"></i>Astronomy Picture of the Day</h2>
                </div>
                <div id="apod-countdown" style="font-size: 0.82rem; color: rgb(83,100,113); margin-bottom: 16px; letter-spacing: 0.4px;">
                    <i class="fa fa-clock-o me-1"></i>Prossimo aggiornamento tra: 
                    <span id="countdown-timer" style="color: var(--primary-color); font-weight: 700;"></span>
                </div>
                <div id="apod-content">
                    <div class="loading-box">
                        <div class="spinner"></div>
                        <span>Caricamento in corso…</span>
                    </div>
                </div>
            </div>

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

        <div class="sezione destra">
           <div style="padding: 16px; width:100%;">
            <div style="font-size:0.78rem; font-weight:800; color:var(--primary-color);
                        letter-spacing:0.8px; text-transform:uppercase; margin-bottom:12px;">
                <i class="fa fa-users me-1"></i>Utenti Consigliati
            </div>
            <div class="profiliScroll">
                <?php 
                try{
                    $connessione= new PDO("mysql:host=$host;dbname=$db", $user, $password);
                    $sql= "SELECT NomeUtente, Foto
                            FROM utenti
                            WHERE NomeUtente IN(
                                SELECT Utente
                                FROM post 
                                WHERE Id_Post IN (
                                    SELECT Id_Post 
                                    FROM tagpost 
                                    WHERE Id_Tag IN (
                                        SELECT Id_Tag 
                                        FROM tagpost 
                                        WHERE Id_Post IN (
                                            SELECT Id_Post 
                                            FROM likepost 
                                            WHERE Utente = ?
                                        ) 
                                        GROUP BY Id_Tag
                                    )
                                )
                                GROUP BY Utente
                            ) AND NomeUtente <> ?;";
                    $preparata= $connessione->prepare($sql);
                    $preparata->execute([$NomeUtente, $NomeUtente]);
                    if($preparata->rowCount() > 0){
                        $ris = $preparata->fetchAll(PDO::FETCH_ASSOC);
                        foreach($ris as $riga){
                            ?>
                            <div class="follower-card">
                                <div class="user-info">
                                    <div class="avatar-container">
                                        <img src="<?="UploadProfili/".$riga['Foto']?>" alt="Profilo" class="follower-img">
                                    </div>
                                    <span class="username">@<?=htmlspecialchars($riga['NomeUtente']) ?></span>
                                </div>
                                <button class="btn-profilo" onclick="location.href='Profilo.php?user=<?php echo urlencode($riga['NomeUtente']); ?>'">
                                    Vedi Profilo
                                </button>
                            </div>
                            <br>
                            <?php
                        }
                    }
                    $connessione = null;
                }catch(PDOException $e){
                    die("Errore nella gestione del database $db: " . $e->getMessage());
                }
                ?>
            </div> 
        </div>

    </div>


    <script src="config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    
    function avviaCountdown() {
    function aggiorna() {
        const ora      = new Date();
        const domani   = new Date(Date.UTC(
            ora.getUTCFullYear(),
            ora.getUTCMonth(),
            ora.getUTCDate() + 1
        ));
        const diff     = domani - ora;

        const ore      = Math.floor(diff / 3600000);
        const minuti   = Math.floor((diff % 3600000) / 60000);
        const secondi  = Math.floor((diff % 60000) / 1000);

        document.getElementById('countdown-timer').textContent =
            `${String(ore).padStart(2,'0')}:${String(minuti).padStart(2,'0')}:${String(secondi).padStart(2,'0')}`;
    }

    aggiorna();
    setInterval(aggiorna, 1000);
    }

    function oggiData() {
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

    function mostraTab(nome, btn) {
    document.querySelectorAll('.pannello').forEach(p => p.classList.remove('attivo'));
    document.querySelectorAll('.eventi-tabs button').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + nome).classList.add('attivo');
    btn.classList.add('active');
    localStorage.setItem('ultimoTab', nome);
    }

    async function caricaAPOD() {
        try {
            const res  = await fetch('api/apod.php');
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

    function initDatePicker() {
        const start = oggiData();
        document.getElementById('ast-start').value = start;
        document.getElementById('ast-end').value   = aggiungiGiorni(start, 6);
    }

    async function caricaAsteroidi() {
        const start = document.getElementById('ast-start').value || oggiData();
        const end   = document.getElementById('ast-end').value   || aggiungiGiorni(oggiData(), 6);
        const diff  = (new Date(end) - new Date(start)) / 86_400_000;

        if (diff < 0 || diff > 7) {
            mostraErrore('asteroidi-content',
                'L\'intervallo di date deve essere compreso tra 1 e 7 giorni.');
            return;
        }

        mostraLoading('asteroidi-content', 'Ricerca asteroidi in corso…');
        document.getElementById('asteroid-count').style.display = 'none';

        try {
            const res  = await fetch(`api/asteroidi.php?start=${start}&end=${end}`);
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

    const eonetTraduzione = {
    'Wildfires':            'Incendi',
    'Severe Storms':        'Forti Temporali',
    'Volcanoes':            'Vulcani',
    'Floods':               'Alluvioni',
    'Earthquakes':          'Terremoti',
    'Drought':              'Siccità',
    'Dust and Haze':        'Polvere e Foschia',
    'Landslides':           'Frane',
    'Sea and Lake Ice':     'Ghiaccio marino e lacustre',
    'Snow':                 'Neve',
    'Temperature Extremes': 'Temperature Estreme',
    'Manmade':              'Disastri artificiali',
    'Water Color':          'Colorazione dell\'acqua',
    };

    function getEmoji(categories) {
    if (!categories?.length) return '🌍';
    return eonetEmoji[categories[0].title] || '🌍';
    }

    async function caricaEONET() {
        try {
            const res  = await fetch('api/eonet.php');
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
                const cat = ev.categories?.map(c => eonetTraduzione[c.title] || c.title).join(', ') || 'Evento';
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

    initDatePicker();
    const tabSalvato = localStorage.getItem('ultimoTab') || 'apod';
    const btnSalvato = document.querySelector(`.eventi-tabs button:nth-child(${
        tabSalvato === 'apod' ? 1 : tabSalvato === 'asteroidi' ? 2 : 3
    })`);
    mostraTab(tabSalvato, btnSalvato);

    avviaCountdown();
    caricaAPOD();
    caricaAsteroidi();
    caricaEONET();

    </script>

</body>
</html>
<?php } ?>