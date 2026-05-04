<?php
session_start();
if (!isset($_SESSION['CONNECTE']) || $_SESSION['CONNECTE'] !== "YES") {
    header("Location: login.php");
    exit();
}

require_once "config/db.php";
$pageTitle = "Horaires";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'supprimer') {
    $id = (int) $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM horaires WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $succes = "Horaire supprimé.";
}

$horaires = $pdo->query("SELECT * FROM horaires ORDER BY heure_depart")->fetchAll();

require_once "includes/header_admin.php";
?>

<?php if (isset($succes)): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $succes ?></div>
<?php endif; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'ajoute'): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> Horaire ajouté.</div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'modifie'): ?>
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> Horaire modifié.</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-clock"></i> Horaires (<?= count($horaires) ?>)</h2>
        <a href="horaire_ajouter.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter</a>
    </div>
    <div style="overflow-x:auto;">
    <table>
        <thead>
            <tr>
                <th>#</th><th>Train</th><th>Départ</th><th>Arrivée</th>
                <th>H. départ</th><th>H. arrivée</th><th>Type</th><th>Retard</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($horaires)): ?>
            <tr><td colspan="9" style="text-align:center;color:#aaa;padding:20px;">Aucun horaire.</td></tr>
        <?php else: ?>
            <?php foreach ($horaires as $h): ?>
            <tr>
                <td><?= $h['id'] ?></td>
                <td><strong><?= htmlspecialchars($h['numero_train']) ?></strong></td>
                <td><?= htmlspecialchars($h['station_depart']) ?></td>
                <td><?= htmlspecialchars($h['station_arrivee']) ?></td>
                <td><?= substr($h['heure_depart'], 0, 5) ?></td>
                <td><?= substr($h['heure_arrivee'], 0, 5) ?></td>
                <td><span class="badge badge-blue"><?= ucfirst($h['type_train']) ?></span></td>
                <td>
                    <?php if ($h['retard'] > 0): ?>
                        <span class="badge badge-red">+<?= $h['retard'] ?>min</span>
                    <?php else: ?>
                        <span class="badge badge-green">À l'heure</span>
                    <?php endif; ?>
                </td>
                <td style="display:flex; gap:8px;">
                    <a href="horaire_modifier.php?id=<?= $h['id'] ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <form method="POST" onsubmit="return confirm('Supprimer cet horaire ?');">
                        <input type="hidden" name="action" value="supprimer">
                        <input type="hidden" name="id" value="<?= $h['id'] ?>">
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
</div>

</div></body></html>
