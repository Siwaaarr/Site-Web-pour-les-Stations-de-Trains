<?php
require_once 'config/db.php';

$where  = [];
$params = [];

if (isset($_GET['station']) && $_GET['station'] !== '') {
    $s        = '%' . $_GET['station'] . '%';
    $where[]  = '(station_depart LIKE ? OR station_arrivee LIKE ?)';
    $params[] = $s;
    $params[] = $s;
}

if (isset($_GET['type']) && in_array($_GET['type'], ['rapide', 'express', 'regional'])) {
    $where[]  = 'type_train = ?';
    $params[] = $_GET['type'];
}

if (isset($_GET['classe']) && in_array($_GET['classe'], ['premiere', 'seconde'])) {
    $where[]  = 'classe = ?';
    $params[] = $_GET['classe'];
}

$sql = "SELECT * FROM horaires";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY heure_depart";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$horaires = $stmt->fetchAll();

$pageTitle = 'Horaires des trains';
include 'includes/header.php';
?>

<style>
    .page-header { background:linear-gradient(135deg,#2e7d32,#1b5e20); color:white; padding:60px 20px; text-align:center; margin-bottom:40px; }
    .page-header h1 { font-size:2.5rem; margin-bottom:15px; }
    .page-header p  { font-size:1.1rem; opacity:.95; max-width:800px; margin:0 auto; }
    .container { max-width:1200px; margin:0 auto; padding:0 20px 50px; }

    .filter-section { background:white; padding:35px; border-radius:20px; box-shadow:0 8px 20px rgba(0,0,0,.1); margin-bottom:30px; }
    .filter-section h2 { color:#1b5e20; margin-bottom:25px; font-size:1.8rem; }
    .filter-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:15px; }
    .filter-group label { display:block; color:#2e7d32; font-weight:600; margin-bottom:8px; }
    .filter-group input,
    .filter-group select { width:100%; padding:12px; border:2px solid #a5d6a7; border-radius:10px; font-size:15px; transition:.3s; }
    .filter-group input:focus,
    .filter-group select:focus { outline:none; border-color:#2e7d32; }
    .btn-filter { background:#2e7d32; color:white; padding:12px 30px; border:none; border-radius:10px; cursor:pointer; font-weight:600; font-size:15px; margin-top:15px; }
    .btn-filter:hover { background:#1b5e20; }

    .results-section { background:white; padding:35px; border-radius:20px; box-shadow:0 8px 20px rgba(0,0,0,.1); }
    .results-section h2 { color:#1b5e20; margin-bottom:10px; font-size:1.8rem; }
    .count-info { color:#666; margin-bottom:20px; font-size:.95rem; }
    .table-container { overflow-x:auto; }
    table { width:100%; border-collapse:collapse; }
    thead { background:linear-gradient(135deg,#66bb6a,#43a047); color:white; }
    thead th { padding:15px; text-align:left; font-weight:600; }
    tbody td { padding:15px; border-bottom:1px solid #e8f5e9; }
    tbody tr:hover { background:#f1f8f3; }
    .no-results { text-align:center; color:#888; padding:30px; }
    .badge-type { display:inline-block; padding:4px 10px; border-radius:12px; font-size:.8rem; font-weight:600; }
    .badge-rapide   { background:#e8f5e9; color:#2e7d32; }
    .badge-express  { background:#e3f2fd; color:#1565c0; }
    .badge-regional { background:#fff3e0; color:#e65100; }
    .badge-premiere { background:#f3e5f5; color:#6a1b9a; }
    .badge-seconde  { background:#fce4ec; color:#880e4f; }
    .retard-ok     { color:#2e7d32; font-weight:600; }
    .retard-retard { color:#e53935; font-weight:600; }
</style>

<div class="page-header">
    <h1><i class="fas fa-clock"></i> Horaires des Trains</h1>
    <p>Filtrez par station, type de train ou classe pour trouver votre trajet</p>
</div>

<div class="container">

    <!-- Formulaire GET : la page se recharge avec les filtres dans l'URL -->
    <div class="filter-section">
        <h2><i class="fas fa-filter"></i> Filtrer les horaires</h2>
        <form method="GET" action="hor.php">
            <div class="filter-grid">

                <div class="filter-group">
                    <label for="station">Station</label>
                    <input type="text" id="station" name="station"
                           placeholder="Ex : Paris"
                           value="<?= htmlspecialchars($_GET['station'] ?? '') ?>">
                </div>

                <div class="filter-group">
                    <label for="type">Type de train</label>
                    <select id="type" name="type">
                        <option value="">Tous</option>
                        <option value="rapide"   <?= (($_GET['type'] ?? '') === 'rapide')   ? 'selected' : '' ?>>Rapide</option>
                        <option value="express"  <?= (($_GET['type'] ?? '') === 'express')  ? 'selected' : '' ?>>Express</option>
                        <option value="regional" <?= (($_GET['type'] ?? '') === 'regional') ? 'selected' : '' ?>>Régional</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="classe">Classe</label>
                    <select id="classe" name="classe">
                        <option value="">Toutes</option>
                        <option value="premiere" <?= (($_GET['classe'] ?? '') === 'premiere') ? 'selected' : '' ?>>1ère classe</option>
                        <option value="seconde"  <?= (($_GET['classe'] ?? '') === 'seconde')  ? 'selected' : '' ?>>2ème classe</option>
                    </select>
                </div>

            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Filtrer
            </button>
            <a href="hor.php" style="margin-left:15px; color:#2e7d32; font-weight:600;">
                <i class="fas fa-undo"></i> Réinitialiser
            </a>
        </form>
    </div>

    <!-- Tableau des résultats -->
    <div class="results-section">
        <h2><i class="fas fa-table"></i> Tous les horaires</h2>
        <p class="count-info"><?= count($horaires) ?> train(s) trouvé(s)</p>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Train</th>
                        <th>Heure départ</th>
                        <th>Heure arrivée</th>
                        <th>Quai</th>
                        <th>Type</th>
                        <th>Classe</th>
                        <th>Retard</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($horaires)): ?>
                        <tr>
                            <td colspan="9" class="no-results">
                                <i class="fas fa-search"></i> Aucun train trouvé.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($horaires as $t): ?>
                        <tr>
                            <td><?= htmlspecialchars($t['station_depart']) ?></td>
                            <td><?= htmlspecialchars($t['station_arrivee']) ?></td>
                            <td><strong><?= htmlspecialchars($t['numero_train']) ?></strong></td>
                            <td><strong><?= substr($t['heure_depart'],  0, 5) ?></strong></td>
                            <td><?= substr($t['heure_arrivee'], 0, 5) ?></td>
                            <td><?= htmlspecialchars($t['quai']) ?></td>
                            <td>
                                <span class="badge-type badge-<?= htmlspecialchars($t['type_train']) ?>">
                                    <?= htmlspecialchars($t['type_train']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-type badge-<?= htmlspecialchars($t['classe']) ?>">
                                    <?= $t['classe'] === 'premiere' ? '1ère' : '2ème' ?>
                                </span>
                            </td>
                            <td class="<?= (int)$t['retard'] > 0 ? 'retard-retard' : 'retard-ok' ?>">
                                <?= (int)$t['retard'] > 0 ? $t['retard'] . ' min' : "A l'heure" ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
