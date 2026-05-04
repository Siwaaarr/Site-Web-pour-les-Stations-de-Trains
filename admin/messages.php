<?php
session_start();
if (!isset($_SESSION['CONNECTE']) || $_SESSION['CONNECTE'] !== "YES") {
    header("Location: login.php");
    exit();
}

require_once "config/db.php";
$pageTitle = "Messages";

$messages = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();

require_once "includes/header_admin.php";
?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-envelope"></i> Messages reçus (<?= count($messages) ?>)</h2>
    </div>

    <?php if (empty($messages)): ?>
        <p style="text-align:center; color:#aaa; padding:30px;">Aucun message reçu.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                <tr>
                    <td><?= htmlspecialchars($msg['nom']) ?></td>
                    <td><?= htmlspecialchars($msg['email']) ?></td>
                    <td><?= htmlspecialchars($msg['sujet']) ?></td>
                    <td><?= htmlspecialchars($msg['message']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</div></body></html>
