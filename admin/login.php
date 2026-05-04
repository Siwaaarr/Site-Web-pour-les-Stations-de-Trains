<?php
session_start();

if (isset($_SESSION['CONNECTE']) && $_SESSION['CONNECTE'] === "YES") {
    header("Location: stations.php");
    exit();
}

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant  = trim($_POST['identifiant']);
    $mot_de_passe = trim($_POST['mot_de_passe']);

    if (empty($identifiant) || empty($mot_de_passe)) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        require_once "config/db.php";

        $stmt = $pdo->prepare("SELECT * FROM admins WHERE identifiant = :id AND mot_de_passe = :mdp");
        $stmt->execute(['id' => $identifiant, 'mdp' => $mot_de_passe]);
        $admin = $stmt->fetch();

        if ($admin) {
            $_SESSION['CONNECTE']    = "YES";
            $_SESSION['identifiant'] = $admin['identifiant'];
            header("Location: stations.php");
            exit();
        } else {
            $erreur = "Identifiant ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #1b5e20, #2e7d32); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: white; border-radius: 16px; padding: 45px 40px; width: 380px; box-shadow: 0 20px 50px rgba(0,0,0,0.25); }
        .logo { text-align: center; margin-bottom: 30px; }
        .logo .icon { width: 65px; height: 65px; background: linear-gradient(135deg, #2e7d32, #1b5e20); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 26px; color: white; }
        .logo h1 { color: #1b5e20; font-size: 1.5rem; }
        .logo p { color: #888; font-size: 0.85rem; margin-top: 4px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; color: #1b5e20; font-weight: 600; font-size: 0.9rem; }
        .form-group input { width: 100%; padding: 11px 14px; border: 2px solid #e0e0e0; border-radius: 9px; font-size: 0.95rem; outline: none; }
        .form-group input:focus { border-color: #2e7d32; }
        .btn-login { width: 100%; padding: 13px; background: linear-gradient(to right, #2e7d32, #1b5e20); color: white; border: none; border-radius: 9px; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 5px; }
        .btn-login:hover { opacity: 0.9; }
        .erreur { background: #fdecea; border-left: 4px solid #e53935; color: #c62828; padding: 12px 15px; border-radius: 8px; margin-bottom: 18px; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <div class="icon"><i class="fas fa-train"></i></div>
        <h1>RailConnect Admin</h1>
        <p>Connectez-vous pour accéder à l'espace admin</p>
    </div>

    <?php if ($erreur): ?>
    <div class="erreur">
        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($erreur) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label>Identifiant</label>
            <input type="text" name="identifiant" value="<?= htmlspecialchars($_POST['identifiant'] ?? '') ?>" placeholder="Votre identifiant" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" placeholder="Votre mot de passe" required>
        </div>
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Se connecter
        </button>
    </form>
</div>
</body>
</html>
