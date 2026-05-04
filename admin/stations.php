<?php
session_start();
if (!isset($_SESSION['CONNECTE']) || $_SESSION['CONNECTE'] !== "YES") {
    header("Location: login.php");
    exit();
}

require_once "config/db.php";
$pageTitle = "Stations";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'supprimer') {
    $id = (int) $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM stations WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $succes = "Station supprimée.";
}

$stations = $pdo->query("SELECT * FROM stations ORDER BY ville, nom")->fetchAll();

require_once "includes/header_admin.php";
?>

<?php if (isset($succes)): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $succes ?></div>
<?php endif; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'ajoute'): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> Station ajoutée.</div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'modifie'): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> Station modifiée.</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-map-marker-alt"></i> Stations (<?= count($stations) ?>)</h2>
        <a href="station_ajouter.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th><th>Nom</th><th>Ville</th><th>Services</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($stations)): ?>
            <tr><td colspan="5" style="text-align:center;color:#aaa;padding:20px;">Aucune station.</td></tr>
        <?php else: ?>
            <?php foreach ($stations as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><strong><?= htmlspecialchars($s['nom']) ?></strong></td>
                <td><?= htmlspecialchars($s['ville']) ?></td>
                <td>
                    <?php if ($s['wifi'])       echo '<span class="badge badge-blue">WiFi</span> '; ?>
                    <?php if ($s['parking'])    echo '<span class="badge badge-green">Parking</span> '; ?>
                    <?php if ($s['restaurant']) echo '<span class="badge badge-orange">Restaurant</span> '; ?>
                </td>
                <td style="display:flex; gap:8px;">
                    <a href="station_modifier.php?id=<?= $s['id'] ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <form method="POST" onsubmit="return confirm('Supprimer cette station ?');">
                        <input type="hidden" name="action" value="supprimer">
                        <input type="hidden" name="id" value="<?= $s['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</div></body></html>
