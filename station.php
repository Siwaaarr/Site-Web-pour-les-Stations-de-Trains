<?php
// station.php — Page des stations (données depuis MySQL)
require_once 'config/db.php';

$pageTitle = 'Nos Stations';

// Récupérer toutes les stations
$stations = $pdo->query("SELECT * FROM stations ORDER BY nom")->fetchAll();

// Construire un tableau slug → données pour le JS
$stationsJS = [];
foreach ($stations as $s) {
    $stationsJS[$s['slug']] = [
        'lat'  => (float)$s['latitude'],
        'lon'  => (float)$s['longitude'],
        'nom'  => $s['nom'],
    ];
}

// Récupérer les prochains départs (horaires) depuis la BDD
$trains = $pdo->query(
    "SELECT * FROM horaires ORDER BY heure_depart"
)->fetchAll();

$trainsJS = array_map(fn($t) => [
    'depart'      => substr($t['heure_depart'], 0, 5),
    'destination' => $t['station_arrivee'],
    'train'       => $t['numero_train'],
    'quai'        => $t['quai'],
], $trains);

include 'includes/header.php';
?>

<style>
    .page-header { background: linear-gradient(135deg,#2e7d32,#1b5e20); color:white; padding:60px 20px; text-align:center; margin-bottom:40px; }
    .page-header h1 { font-size:2.5rem; margin-bottom:15px; }
    .page-header p  { font-size:1.1rem; opacity:.95; max-width:800px; margin:0 auto; }
    .container { max-width:1200px; margin:0 auto; padding:0 20px 50px; }

    .station-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:30px; margin-bottom:50px; }
    .station-card { background:white; border-radius:20px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,.1); transition:all .3s; cursor:pointer; }
    .station-card:hover { transform:translateY(-10px); box-shadow:0 15px 35px rgba(0,0,0,.2); }
    .station-image { width:100%; height:200px; object-fit:cover; transition:transform .3s; }
    .station-card:hover .station-image { transform:scale(1.1); }
    .station-content { padding:25px; border-top:4px solid #66bb6a; }
    .station-content h3 { color:#1b5e20; font-size:1.5rem; margin-bottom:12px; display:flex; align-items:center; gap:10px; }
    .station-content p { color:#2e7d32; font-size:1rem; line-height:1.6; }
    .services-badges { display:flex; flex-wrap:wrap; gap:8px; margin-top:15px; }
    .badge { background:#e8f5e9; color:#2e7d32; padding:6px 12px; border-radius:20px; font-size:.85rem; font-weight:600; display:flex; align-items:center; gap:5px; }

    .search-section { background:white; padding:35px; border-radius:20px; box-shadow:0 8px 20px rgba(0,0,0,.1); margin-bottom:30px; }
    .search-section h2 { color:#1b5e20; margin-bottom:25px; font-size:1.8rem; display:flex; align-items:center; gap:10px; }
    .search-bar { display:flex; gap:15px; margin-bottom:20px; }
    .search-bar input  { flex:1; padding:15px; border:2px solid #a5d6a7; border-radius:12px; font-size:16px; transition:all .3s; }
    .search-bar input:focus { outline:none; border-color:#2e7d32; box-shadow:0 0 0 3px rgba(46,125,50,.1); }
    .search-bar button { background:#2e7d32; color:white; padding:15px 35px; border:none; border-radius:12px; cursor:pointer; font-weight:600; font-size:16px; transition:all .3s; display:flex; align-items:center; gap:10px; }
    .search-bar button:hover { background:#1b5e20; transform:translateY(-2px); box-shadow:0 5px 15px rgba(46,125,50,.3); }

    #search-results { display:none; margin-top:35px; padding-top:30px; border-top:3px dashed #a5d6a7; }
    #search-results h3 { color:#1b5e20; font-size:1.6rem; margin-bottom:25px; display:flex; align-items:center; gap:10px; }
    .trains-table-container { overflow-x:auto; margin:25px 0; }
    table { width:100%; border-collapse:collapse; background:white; }
    thead { background:linear-gradient(135deg,#66bb6a,#43a047); color:white; }
    thead th { padding:15px; text-align:left; font-weight:600; }
    tbody td { padding:15px; border-bottom:1px solid #e8f5e9; }
    tbody tr:hover { background:#f1f8f3; }
    .map-container { margin-top:30px; border-radius:15px; overflow:hidden; box-shadow:0 5px 20px rgba(0,0,0,.1); }
    iframe { width:100%; height:450px; border:none; }

    @media(max-width:768px){ .station-grid{grid-template-columns:1fr;} .search-bar{flex-direction:column;} }
</style>

<div class="page-header">
    <h1><i class="fas fa-building"></i> Nos Stations Françaises</h1>
    <p>Explorez notre réseau de gares principales, leurs services disponibles et localisez-les facilement sur la carte interactive</p>
</div>

<div class="container">
    <!-- Grille des stations depuis BDD -->
    <div class="station-grid">
        <?php foreach ($stations as $s): ?>
        <div class="station-card" onclick="selectStation('<?= htmlspecialchars($s['slug']) ?>')">
            <img src="<?= htmlspecialchars($s['image']) ?>" class="station-image" alt="<?= htmlspecialchars($s['nom']) ?>"
                 onerror="this.src='https://via.placeholder.com/400x200/2e7d32/ffffff?text=<?= urlencode($s['nom']) ?>'">
            <div class="station-content">
                <h3><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($s['nom']) ?></h3>
                <p><?= htmlspecialchars($s['description']) ?></p>
                <div class="services-badges">
                    <?php if ($s['wifi']):       ?><span class="badge"><i class="fas fa-wifi"></i> WiFi</span><?php endif; ?>
                    <?php if ($s['guichets']):   ?><span class="badge"><i class="fas fa-ticket-alt"></i> Guichets</span><?php endif; ?>
                    <?php if ($s['parking']):    ?><span class="badge"><i class="fas fa-parking"></i> Parking</span><?php endif; ?>
                    <?php if ($s['restaurant']): ?><span class="badge"><i class="fas fa-utensils"></i> Restaurant</span><?php endif; ?>
                    <?php if ($s['boutiques']):  ?><span class="badge"><i class="fas fa-shopping-bag"></i> Boutiques</span><?php endif; ?>
                    <?php if ($s['cafe']):       ?><span class="badge"><i class="fas fa-coffee"></i> Café</span><?php endif; ?>
                    <?php if ($s['bornes_auto']): ?><span class="badge"><i class="fas fa-robot"></i> Bornes auto</span><?php endif; ?>
                    <?php if ($s['toilettes']): ?><span class="badge">Toilettes</span><?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Recherche -->
    <div class="search-section">
        <h2><i class="fas fa-search"></i> Recherche &amp; Localisation</h2>
        <div class="search-bar">
            <input type="text" id="station-input" placeholder="Ex : Paris Gare de Lyon">
            <button onclick="searchStation()">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </div>

        <div id="search-results">
            <h3 id="result-title"></h3>
            <h4 style="color:#2e7d32;margin-top:25px;"><i class="fas fa-train"></i> Prochains départs</h4>
            <div class="trains-table-container">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-clock"></i> Heure</th>
                            <th><i class="fas fa-location-arrow"></i> Destination</th>
                            <th><i class="fas fa-train"></i> Train</th>
                            <th><i class="fas fa-door-open"></i> Quai</th>
                        </tr>
                    </thead>
                    <tbody id="trains-table"></tbody>
                </table>
            </div>
            <h4 style="color:#2e7d32;margin-top:35px;"><i class="fas fa-map-marked-alt"></i> Localisation sur la carte</h4>
            <div class="map-container">
                <iframe id="map-frame" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
// Données injectées depuis PHP/MySQL
const stations = <?= json_encode($stationsJS, JSON_UNESCAPED_UNICODE) ?>;
const trainsStation = <?= json_encode($trainsJS, JSON_UNESCAPED_UNICODE) ?>;

function afficherTrains() {
    const tbody = document.getElementById('trains-table');
    tbody.innerHTML = '';
    trainsStation.forEach(t => {
        tbody.innerHTML += `<tr>
            <td><strong>${t.depart}</strong></td>
            <td>${t.destination}</td>
            <td><strong>${t.train}</strong></td>
            <td>${t.quai}</td>
        </tr>`;
    });
}

function searchStation() {
    const input = document.getElementById('station-input').value.toLowerCase().trim();
    if (!input) { alert('⚠️ Veuillez saisir le nom d\'une station'); return; }
    const key = Object.keys(stations).find(k =>
        stations[k].nom.toLowerCase().includes(input) || k.includes(input)
    );
    if (!key) {
        alert('❌ Station inconnue. Essayez : Paris Gare de Lyon, Lyon Part-Dieu, Marseille Saint-Charles, Bordeaux Saint-Jean…');
        return;
    }
    afficherResultats(key);
}

function selectStation(slug) {
    document.getElementById('station-input').value = stations[slug]?.nom || slug;
    afficherResultats(slug);
}

function afficherResultats(slug) {
    const coords = stations[slug];
    if (!coords) return;
    document.getElementById('result-title').innerHTML =
        '<i class="fas fa-info-circle"></i> Informations pour : <strong>' + coords.nom + '</strong>';
    afficherTrains();
    document.getElementById('map-frame').src =
        `https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d10000!2d${coords.lon}!3d${coords.lat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sfr!2sfr!4v0`;
    const res = document.getElementById('search-results');
    res.style.display = 'block';
    res.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

setInterval(afficherTrains, 10000);
</script>

<?php include 'includes/footer.php'; ?>
