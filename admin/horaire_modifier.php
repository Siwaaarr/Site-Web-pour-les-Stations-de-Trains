<?php
session_start();
if (!isset($_SESSION['CONNECTE']) || $_SESSION['CONNECTE'] !== "YES") {
    header("Location: login.php");
    exit();
}

require_once "config/db.php";
$pageTitle = "Modifier un horaire";
$erreurs   = [];

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: horaires.php"); exit(); }

$stmt = $pdo->prepare("SELECT * FROM horaires WHERE id = :id");
$stmt->execute(['id' => $id]);
$horaire = $stmt->fetch();
if (!$horaire) { header("Location: horaires.php"); exit(); }

$data   = $horaire;
$villes = $pdo->query("SELECT DISTINCT ville FROM stations ORDER BY ville")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id'              => $id,
        'station_depart'  => trim($_POST['station_depart']  ?? ''),
        'station_arrivee' => trim($_POST['station_arrivee'] ?? ''),
        'heure_depart'    => trim($_POST['heure_depart']    ?? ''),
        'heure_arrivee'   => trim($_POST['heure_arrivee']   ?? ''),
        'numero_train'    => trim($_POST['numero_train']    ?? ''),
        'quai'            => (int)($_POST['quai']           ?? 0),
        'type_train'      => trim($_POST['type_train']      ?? 'express'),
        'classe'          => trim($_POST['classe']          ?? 'seconde'),
        'retard'          => (int)($_POST['retard']         ?? 0),
    ];

    if (empty($data['station_depart']))  $erreurs[] = "Station de départ obligatoire.";
    if (empty($data['station_arrivee'])) $erreurs[] = "Station d'arrivée obligatoire.";
    if (empty($data['heure_depart']))    $erreurs[] = "Heure de départ obligatoire.";
    if (empty($data['heure_arrivee']))   $erreurs[] = "Heure d'arrivée obligatoire.";
    if (empty($data['numero_train']))    $erreurs[] = "Numéro de train obligatoire.";

    if (empty($erreurs)) {
        $sql = "UPDATE horaires SET
                station_depart=:station_depart, station_arrivee=:station_arrivee,
                heure_depart=:heure_depart, heure_arrivee=:heure_arrivee,
                numero_train=:numero_train, quai=:quai,
                type_train=:type_train, classe=:classe, retard=:retard
                WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        header("Location: horaires.php?msg=modifie");
        exit();
    }
}

require_once "includes/header_admin.php";
?>

<?php if (!empty($erreurs)): ?>
    <div class="alert alert-danger">
        <?php foreach ($erreurs as $e): ?><div>— <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-edit"></i> Modifier : <?= htmlspecialchars($horaire['numero_train']) ?></h2>
        <a href="horaires.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>

    <form method="POST" action="horaire_modifier.php?id=<?= $id ?>">

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Station de départ *</label>
                <select name="station_depart" required>
                    <option value="">— Sélectionnez —</option>
                    <?php foreach ($villes as $v): ?>
                    <option value="<?= htmlspecialchars($v['ville']) ?>" <?= $data['station_depart'] === $v['ville'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['ville']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Station d'arrivée *</label>
                <select name="station_arrivee" required>
                    <option value="">— Sélectionnez —</option>
                    <?php foreach ($villes as $v): ?>
                    <option value="<?= htmlspecialchars($v['ville']) ?>" <?= $data['station_arrivee'] === $v['ville'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['ville']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Heure de départ *</label>
                <input type="time" name="heure_depart" value="<?= substr($data['heure_depart'], 0, 5) ?>" required>
            </div>
            <div class="form-group">
                <label>Heure d'arrivée *</label>
                <input type="time" name="heure_arrivee" value="<?= substr($data['heure_arrivee'], 0, 5) ?>" required>
            </div>
        </div>

        <div class="form-row cols-3">
            <div class="form-group">
                <label>Numéro du train *</label>
                <input type="text" name="numero_train" value="<?= htmlspecialchars($data['numero_train']) ?>" required>
            </div>
            <div class="form-group">
                <label>Quai</label>
                <input type="number" name="quai" value="<?= (int)$data['quai'] ?>" min="0" max="30">
            </div>
            <div class="form-group">
                <label>Retard (minutes)</label>
                <input type="number" name="retard" value="<?= (int)$data['retard'] ?>" min="0">
            </div>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Type de train</label>
                <select name="type_train">
                    <?php foreach (['rapide', 'express', 'regional'] as $t): ?>
                    <option value="<?= $t ?>" <?= $data['type_train'] === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Classe</label>
                <select name="classe">
                    <option value="seconde"  <?= $data['classe'] === 'seconde'  ? 'selected' : '' ?>>Seconde</option>
                    <option value="premiere" <?= $data['classe'] === 'premiere' ? 'selected' : '' ?>>Première</option>
                </select>
            </div>
        </div>

        <div style="display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
            <a href="horaires.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
        </div>
    </form>
</div>

</div></body></html>
