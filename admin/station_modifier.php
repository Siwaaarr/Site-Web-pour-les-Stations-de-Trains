<?php
session_start();
if (!isset($_SESSION['CONNECTE']) || $_SESSION['CONNECTE'] !== "YES") {
    header("Location: login.php");
    exit();
}

require_once "config/db.php";
$pageTitle = "Modifier une station";
$erreurs   = [];

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: stations.php"); exit(); }

$stmt = $pdo->prepare("SELECT * FROM stations WHERE id = :id");
$stmt->execute(['id' => $id]);
$station = $stmt->fetch();
if (!$station) { header("Location: stations.php"); exit(); }

$data = $station;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id'          => $id,
        'nom'         => trim($_POST['nom']         ?? ''),
        'ville'       => trim($_POST['ville']        ?? ''),
        'description' => trim($_POST['description']  ?? ''),
        'image'       => trim($_POST['image']        ?? ''),
        'latitude'    => trim($_POST['latitude']     ?? ''),
        'longitude'   => trim($_POST['longitude']    ?? ''),
        'wifi'        => isset($_POST['wifi'])        ? 1 : 0,
        'guichets'    => isset($_POST['guichets'])    ? 1 : 0,
        'parking'     => isset($_POST['parking'])     ? 1 : 0,
        'restaurant'  => isset($_POST['restaurant'])  ? 1 : 0,
        'boutiques'   => isset($_POST['boutiques'])   ? 1 : 0,
        'cafe'        => isset($_POST['cafe'])        ? 1 : 0,
        'bornes_auto' => isset($_POST['bornes_auto']) ? 1 : 0,
        'toilettes'   => isset($_POST['toilettes'])   ? 1 : 0,
    ];

    if (empty($data['nom']))   $erreurs[] = "Le nom est obligatoire.";
    if (empty($data['ville'])) $erreurs[] = "La ville est obligatoire.";

    if (empty($erreurs)) {
        $sql = "UPDATE stations SET nom=:nom, ville=:ville, description=:description, image=:image,
                latitude=:latitude, longitude=:longitude, wifi=:wifi, guichets=:guichets,
                parking=:parking, restaurant=:restaurant, boutiques=:boutiques, cafe=:cafe,
                bornes_auto=:bornes_auto, toilettes=:toilettes WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        header("Location: stations.php?msg=modifie");
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
        <h2><i class="fas fa-edit"></i> Modifier : <?= htmlspecialchars($station['nom']) ?></h2>
        <a href="stations.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>

    <form method="POST" action="station_modifier.php?id=<?= $id ?>">

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($data['nom']) ?>" required>
            </div>
            <div class="form-group">
                <label>Ville *</label>
                <input type="text" name="ville" value="<?= htmlspecialchars($data['ville']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Image</label>
                <input type="text" name="image" value="<?= htmlspecialchars($data['image'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Latitude</label>
                <input type="number" step="0.000001" name="latitude" value="<?= htmlspecialchars($data['latitude'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Longitude</label>
                <input type="number" step="0.000001" name="longitude" value="<?= htmlspecialchars($data['longitude'] ?? '') ?>">
            </div>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;color:#1b5e20;margin-bottom:12px;">Services</label>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;">
                <?php
                $services = [
                    'wifi'        => 'WiFi',
                    'guichets'    => 'Guichets',
                    'parking'     => 'Parking',
                    'restaurant'  => 'Restaurant',
                    'boutiques'   => 'Boutiques',
                    'cafe'        => 'Café',
                    'bornes_auto' => 'Bornes auto',
                    'toilettes'   => 'Toilettes',
                ];
                foreach ($services as $key => $label): ?>
                <label style="display:flex;align-items:center;gap:8px;background:#f0f4f0;padding:10px 14px;border-radius:9px;cursor:pointer;font-size:0.9rem;">
                    <input type="checkbox" name="<?= $key ?>" <?= !empty($data[$key]) ? 'checked' : '' ?>>
                    <?= $label ?>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div style="display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
            <a href="stations.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
        </div>
    </form>
</div>

</div></body></html>
