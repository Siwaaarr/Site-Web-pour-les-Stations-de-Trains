<?php
// tarifs.php — SANS API JSON : les stations viennent directement de PHP dans le HTML
require_once 'config/db.php';
$pageTitle = 'Tarifs & Calculateur';

// On recupere les stations directement ici, plus besoin d'une API
$stations = $pdo->query("SELECT id, nom, ville FROM stations ORDER BY nom")->fetchAll();

include 'includes/header.php';
?>

<style>
    .page-header { background:linear-gradient(135deg,#2e7d32,#1b5e20); color:white; padding:60px 20px; text-align:center; margin-bottom:40px; }
    .page-header h1 { font-size:2.5rem; margin-bottom:15px; }
    .page-header p  { font-size:1.1rem; opacity:.95; max-width:800px; margin:0 auto; }
    .container { max-width:1200px; margin:0 auto; padding:0 20px 50px; }

    .calculator-section { background:white; padding:35px; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,.1); margin-bottom:40px; }
    .calculator-section h2 { color:#1b5e20; margin-bottom:30px; font-size:1.8rem; text-align:center; }
    .form-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:25px; margin-bottom:30px; }
    .form-group { display:flex; flex-direction:column; gap:10px; }
    .form-group label { color:#1b5e20; font-weight:600; }
    .form-group select, .form-group input { padding:14px; border:2px solid #a5d6a7; border-radius:10px; font-size:15px; width:100%; }
    .form-group select:focus, .form-group input:focus { outline:none; border-color:#2e7d32; }
    .btn { background:linear-gradient(135deg,#43a047,#2e7d32); color:white; padding:16px 40px; border:none; border-radius:12px; font-size:18px; font-weight:600; cursor:pointer; width:100%; }
    .btn:hover { background:linear-gradient(135deg,#2e7d32,#1b5e20); }
    .result { background:#e8f5e9; padding:30px; border-radius:15px; margin-top:30px; border-left:6px solid #43a047; display:none; }
    .result h3 { color:#1b5e20; margin-bottom:15px; }
    .price-final { font-size:3rem; color:#2e7d32; font-weight:bold; margin:15px 0; }
    .alert-error { background:#ffebee; color:#c62828; border:2px solid #ef9a9a; padding:14px 20px; border-radius:10px; margin-bottom:20px; display:none; }

    .pricing-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:25px; margin-bottom:35px; }
    .pricing-card { background:white; border-radius:20px; padding:30px; box-shadow:0 8px 20px rgba(0,0,0,.1); text-align:center; border-top:5px solid #66bb6a; }
    .pricing-card h3 { color:#1b5e20; font-size:1.4rem; margin-bottom:10px; }
    .pricing-card .price { font-size:2.5rem; color:#2e7d32; font-weight:bold; margin:15px 0; }
    table { width:100%; border-collapse:collapse; }
    thead { background:linear-gradient(135deg,#66bb6a,#43a047); color:white; }
    thead th { padding:14px 16px; text-align:left; }
    tbody td { padding:14px 16px; border-bottom:1px solid #e8f5e9; color:#1b5e20; }
    tbody tr:hover { background:#f1f8f3; }
</style>

<div class="page-header">
    <h1><i class="fas fa-euro-sign"></i> Tarifs</h1>
    <p>Calculez le prix de votre billet en quelques clics</p>
</div>

<div class="container">
    <div class="calculator-section">
        <h2><i class="fas fa-calculator"></i> Calculateur de Prix</h2>

        <div id="alerteErreur" class="alert-error">
            <span id="alerteMsg"></span>
        </div>

        <div class="form-grid">
            <!-- Les stations sont injectees directement par PHP, pas besoin d'API -->
            <div class="form-group">
                <label>Station de Depart</label>
                <select id="depart">
                    <option value="">Choisissez une station</option>
                    <?php foreach ($stations as $s): ?>
                    <option value="<?= htmlspecialchars($s['nom']) ?>">
                        <?= htmlspecialchars($s['nom']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Station d'Arrivee</label>
                <select id="arrivee">
                    <option value="">Choisissez une station</option>
                    <?php foreach ($stations as $s): ?>
                    <option value="<?= htmlspecialchars($s['nom']) ?>">
                        <?= htmlspecialchars($s['nom']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Type de Train</label>
                <select id="typeTrain">
                    <option value="">Choisissez un type</option>
                    <option value="regional">Regional - 5 euros</option>
                    <option value="rapide">Rapide - 10 euros</option>
                    <option value="express">Express - 25 euros</option>
                </select>
            </div>

            <div class="form-group">
                <label>Classe de Voyage</label>
                <select id="classe">
                    <option value="">Choisissez une classe</option>
                    <option value="seconde">2eme Classe (prix normal)</option>
                    <option value="premiere">1ere Classe (+50%)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Statut du Voyageur</label>
                <select id="statut">
                    <option value="normal">Normal (sans reduction)</option>
                    <option value="etudiant">Etudiant (-20%)</option>
                    <option value="senior">Senior 60+ (-25%)</option>
                </select>
            </div>
        </div>

        <button class="btn" onclick="calculerPrix()">Calculer le Prix</button>

        <div class="result" id="resultat">
            <h3>Prix de votre billet</h3>
            <div class="price-final" id="prixFinal"></div>
            <div id="detailReduction"></div>
            <div id="resultDetail"></div>
        </div>
    </div>

    <!-- Cartes tarifs -->
    <div style="margin-bottom:40px;">
        <h2 style="color:#1b5e20;font-size:1.8rem;margin-bottom:25px;">Tarifs par Type de Train</h2>
        <div class="pricing-grid">
            <div class="pricing-card">
                <h3>Regional</h3>
                <div class="price">5 euros</div>
                <p>Tous les arrets, confort standard, WiFi gratuit</p>
            </div>
            <div class="pricing-card">
                <h3>Rapide</h3>
                <div class="price">10 euros</div>
                <p>Arrets principaux, confort ameliore, prises electriques</p>
            </div>
            <div class="pricing-card">
                <h3>Express</h3>
                <div class="price">25 euros</div>
                <p>Service ultra-rapide, sieges premium, restauration a bord</p>
            </div>
        </div>
    </div>

    <!-- Tableau recapitulatif -->
    <div style="background:white;padding:35px;border-radius:20px;box-shadow:0 8px 20px rgba(0,0,0,.1);">
        <h2 style="color:#1b5e20;font-size:1.8rem;margin-bottom:25px;">Recapitulatif des Prix</h2>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Type de train</th>
                        <th>2eme classe</th>
                        <th>1ere classe (+50%)</th>
                        <th>Etudiant (-20%)</th>
                        <th>Senior (-25%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Regional</td><td>5,00 euros</td><td>7,50 euros</td><td>4,00 euros</td><td>3,75 euros</td></tr>
                    <tr><td>Rapide</td><td>10,00 euros</td><td>15,00 euros</td><td>8,00 euros</td><td>7,50 euros</td></tr>
                    <tr><td>Express</td><td>25,00 euros</td><td>37,50 euros</td><td>20,00 euros</td><td>18,75 euros</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function afficherErreur(msg) {
    var el = document.getElementById('alerteErreur');
    document.getElementById('alerteMsg').textContent = msg;
    el.style.display = 'block';
}

function calculerPrix() {
    document.getElementById('alerteErreur').style.display = 'none';
    document.getElementById('resultat').style.display = 'none';

    var depart    = document.getElementById('depart').value;
    var arrivee   = document.getElementById('arrivee').value;
    var typeTrain = document.getElementById('typeTrain').value;
    var classe    = document.getElementById('classe').value;
    var statut    = document.getElementById('statut').value;

    if (!depart)            { afficherErreur('Veuillez choisir une station de depart.'); return; }
    if (!arrivee)           { afficherErreur('Veuillez choisir une station d\'arrivee.'); return; }
    if (depart === arrivee) { afficherErreur('Les stations de depart et d\'arrivee doivent etre differentes.'); return; }
    if (!typeTrain)         { afficherErreur('Veuillez choisir un type de train.'); return; }
    if (!classe)            { afficherErreur('Veuillez choisir une classe de voyage.'); return; }

    var prixBase = { regional: 5, rapide: 10, express: 25 };
    var prix = prixBase[typeTrain];
    if (classe === 'premiere') prix = prix * 1.5;

    var reductions = { normal: 0, etudiant: 0.20, senior: 0.25 };
    var reduc = reductions[statut] || 0;
    var montantReduc = prix * reduc;
    prix = prix - montantReduc;

    document.getElementById('prixFinal').textContent = prix.toFixed(2) + ' euros';

    var msgReduc = '';
    if (statut === 'etudiant') msgReduc = 'Reduction etudiant : -20% (-' + montantReduc.toFixed(2) + ' euros)';
    if (statut === 'senior')   msgReduc = 'Reduction senior : -25% (-' + montantReduc.toFixed(2) + ' euros)';
    if (statut === 'normal')   msgReduc = 'Aucune reduction appliquee';
    document.getElementById('detailReduction').textContent = msgReduc;

    var labelsType = { regional: 'Regional', rapide: 'Rapide', express: 'Express' };
    document.getElementById('resultDetail').textContent =
        'Trajet : ' + depart + ' vers ' + arrivee +
        ' | Train : ' + labelsType[typeTrain] +
        ' | Classe : ' + (classe === 'premiere' ? '1ere' : '2eme');

    document.getElementById('resultat').style.display = 'block';
}
</script>

<?php include 'includes/footer.php'; ?>
