<?php
// includes/header.php — Navigation commune à toutes les pages
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RailConnect France — <?= htmlspecialchars($pageTitle ?? 'Accueil') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); color: #1b5e20; }

        nav { background: linear-gradient(to right, #2e7d32, #1b5e20); padding: 15px 0; box-shadow: 0 2px 10px rgba(0,0,0,.1); position: sticky; top: 0; z-index: 1000; }
        .nav-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
        .logo { display: flex; align-items: center; gap: 12px; color: white; font-size: 24px; font-weight: bold; text-decoration: none; }
        .logo-icon { background: white; color: #2e7d32; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; }
        .nav-links { display: flex; gap: 5px; align-items: center; }
        .nav-links a { color: white; text-decoration: none; padding: 10px 18px; border-radius: 8px; transition: all .3s; font-weight: 500; display: flex; align-items: center; gap: 8px; }
        .nav-links a:hover { background: rgba(255,255,255,.2); transform: translateY(-2px); }
        .nav-links a.active { background: #a5d6a7; color: #1b5e20; }

        footer { background: linear-gradient(to right, #1b5e20, #2e7d32); color: white; padding: 50px 0 20px; margin-top: 60px; }
        .footer-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; }
        .footer-column h3 { color: #a5d6a7; margin-bottom: 20px; font-size: 1.2rem; }
        .footer-column p { margin: 12px 0; font-size: .95rem; line-height: 1.6; color: #c8e6c9; }
        .footer-column a { color: #c8e6c9; display: block; margin: 8px 0; text-decoration: none; transition: all .3s; }
        .footer-column a:hover { color: #a5d6a7; padding-left: 5px; }
        .contact-item-footer { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 15px; }
        .contact-icon-footer { background: rgba(255,255,255,.1); width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .social-links { display: flex; gap: 15px; margin-top: 20px; }
        .social-link { background: rgba(255,255,255,.1); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; color: white; text-decoration: none; transition: all .3s; }
        .social-link:hover { background: white; color: #2e7d32; transform: translateY(-5px); }
        .footer-bottom { max-width: 1200px; margin: 30px auto 0; padding: 20px; border-top: 1px solid rgba(255,255,255,.1); text-align: center; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .copyright { color: #a5d6a7; font-size: .9rem; }
        .legal-links { display: flex; gap: 25px; }
        .legal-links a { color: #a5d6a7; text-decoration: none; font-size: .9rem; transition: .3s; }
        .legal-links a:hover { color: white; }

        @media (max-width: 768px) {
            .nav-container { flex-direction: column; gap: 15px; }
            .nav-links { flex-direction: column; width: 100%; }
            .nav-links a { width: 100%; justify-content: center; }
            .footer-bottom { flex-direction: column; gap: 15px; }
        }
    </style>
</head>
<body>
<nav>
    <div class="nav-container">
        <a href="home.php" class="logo">
            <div class="logo-icon"><i class="fas fa-train"></i></div>
            <span>RailConnect France</span>
        </a>
        <div class="nav-links">
            <a href="home.php"    <?= $currentPage === 'home'    ? 'class="active"' : '' ?>><i class="fas fa-home"></i> Accueil</a>
            <a href="hor.php"     <?= $currentPage === 'hor'     ? 'class="active"' : '' ?>><i class="fas fa-clock"></i> Horaires</a>
            <a href="station.php" <?= $currentPage === 'station' ? 'class="active"' : '' ?>><i class="fas fa-map-marker-alt"></i> Stations</a>
            <a href="tarifs.php"  <?= $currentPage === 'tarifs'  ? 'class="active"' : '' ?>><i class="fas fa-ticket-alt"></i> Tarifs</a>
            <a href="contact.php" <?= $currentPage === 'contact' ? 'class="active"' : '' ?>><i class="fas fa-envelope"></i> Contact</a>
        </div>
    </div>
</nav>
