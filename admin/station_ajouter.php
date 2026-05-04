<?php
session_start();
if (!isset($_SESSION['CONNECTE']) || $_SESSION['CONNECTE'] !== "YES") {
    header("Location: login.php");
    exit();
}

require_once "config/db.php";
$pageTitle = "Ajouter une station";
$erreurs = [];
$data    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom'         => trim($_POST['nom']         ?? ''),
        'slug'        => trim($_POST['slug']         ?? ''),
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
    if (empty($data['slug']))  $erreurs[] = "Le slug est obligatoire.";
    if (empty($data['ville'])) $erreurs[] = "La ville est obligatoire.";

    if (empty($erreurs)) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM stations WHERE slug = :slug");
        $check->execute(['slug' => $data['slug']]);
        if ($check->fetchColumn() > 0) {
            $erreurs[] = "Ce slug existe déjà.";
        }
    }

    if (empty($erreurs)) {
        $sql = "INSERT INTO stations (nom, slug, ville, description, image, latitude, longitude, wifi, guichets, parking, restaurant, boutiques, cafe, bornes_auto, toilettes)
                VALUES (:nom, :slug, :ville, :description, :image, :latitude, :longitude, :wifi, :guichets, :parking, :restaurant, :boutiques, :cafe, :bornes_auto, :toilettes)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        if ($stmt->rowCount() > 0) {
            header("Location: stations.php?msg=ajoute");
            exit();
        } else {
            $erreurs[] = "Erreur lors de l'insertion.";
        }
    }
}

require_once "includes/header_admin.php";
?>

<?php if (!empty($erreurs)): ?>
    <div class="alert alert-danger">
        <div>
            <?php foreach ($erreurs as $e): ?>
                <div>— <?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-plus-circle"></i> Nouvelle station</h2>
        <a href="stations.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Retour</a>
    </div>

    <form method="POST" action="station_ajouter.php">

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($data['nom'] ?? '') ?>" placeholder="Ex: Paris Gare de Lyon" required>
            </div>
            <div class="form-group">
                <label>Ville *</label>
                <input type="text" name="ville" value="<?= htmlspecialchars($data['ville'] ?? '') ?>" placeholder="Ex: Paris" required>
            </div>
        </div>

       
        <input type="hidden" id="slug" name="slug" value="<?= htmlspecialchars($data['slug'] ?? '') ?>">

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Image</label>
                <input type="text" name="image" value="<?= htmlspecialchars($data['image'] ?? '') ?>" placeholder="Ex: paris.jpg">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Description..."><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
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

</div></body>

<script>
document.getElementById('nom').addEventListener('input', function () {
    document.getElementById('slug').value = this.value
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')  
        .replace(/[^a-z0-9\s]/g, '')      
        .trim()
        .replace(/\s+/g, '-');           
});
</script>

</html>