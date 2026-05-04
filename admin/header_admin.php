<?php
$currentAdmin = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — <?= $pageTitle ?? '' ?> | RailConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f0; color: #1b5e20; }

       
        .admin-header {
            background: linear-gradient(to right, #1b5e20, #2e7d32);
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        .header-logo {
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-logo .icon {
            background: white;
            color: #2e7d32;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

      
        .header-nav { display: flex; gap: 6px; }
        .header-nav a {
            color: #c8e6c9;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background 0.2s;
        }
        .header-nav a:hover  { background: rgba(255,255,255,0.15); color: white; }
        .header-nav a.active { background: rgba(255,255,255,0.2);  color: white; font-weight: 600; }

     
        .btn-logout {
            background: rgba(239,83,80,0.25);
            color: #ef9a9a;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background 0.2s;
        }
        .btn-logout:hover { background: rgba(239,83,80,0.45); color: white; }

      
        .page-body { padding: 30px; max-width: 1200px; margin: 0 auto; }

        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            margin-bottom: 25px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e8f5e9;
        }
        .card-header h2 { font-size: 1.1rem; color: #1b5e20; display: flex; align-items: center; gap: 8px; }

       
        table { width: 100%; border-collapse: collapse; font-size: 0.93rem; }
        thead th { background: #e8f5e9; color: #1b5e20; padding: 12px 15px; text-align: left; font-weight: 600; }
        tbody tr { border-bottom: 1px solid #f0f4f0; }
        tbody tr:hover { background: #f9fdf9; }
        tbody td { padding: 12px 15px; }

        
        .form-row { display: grid; gap: 18px; margin-bottom: 18px; }
        .form-row.cols-2 { grid-template-columns: 1fr 1fr; }
        .form-row.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #1b5e20; font-size: 0.9rem; }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%; padding: 10px 14px;
            border: 2px solid #e0e0e0; border-radius: 9px;
            font-size: 0.95rem; outline: none; font-family: inherit;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus { border-color: #2e7d32; }
        .form-group textarea { resize: vertical; min-height: 100px; }

        
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px; border-radius: 8px;
            font-weight: 600; font-size: 0.9rem;
            cursor: pointer; border: none; text-decoration: none;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.85; }
        .btn-primary   { background: #2e7d32; color: white; }
        .btn-danger    { background: #e53935; color: white; }
        .btn-warning   { background: #f57c00; color: white; }
        .btn-info      { background: #0288d1; color: white; }
        .btn-secondary { background: #78909c; color: white; }
        .btn-sm { padding: 6px 12px; font-size: 0.82rem; }

        
        .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #e8f5e9; border-left: 4px solid #2e7d32; color: #1b5e20; }
        .alert-danger  { background: #fdecea; border-left: 4px solid #e53935; color: #c62828; }
        .alert-warning { background: #fff8e1; border-left: 4px solid #f57c00; color: #e65100; }

       
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
        .badge-green  { background: #e8f5e9; color: #2e7d32; }
        .badge-red    { background: #fdecea; color: #c62828; }
        .badge-blue   { background: #e3f2fd; color: #01579b; }
        .badge-orange { background: #fff8e1; color: #e65100; }
        .badge-gray   { background: #eceff1; color: #546e7a; }

        @media (max-width: 768px) {
            .admin-header { padding: 0 15px; }
            .form-row.cols-2, .form-row.cols-3 { grid-template-columns: 1fr; }
            .page-body { padding: 15px; }
        }
    </style>
</head>
<body>

<header class="admin-header">

  
    <div class="header-logo">
        <div class="icon"><i class="fas fa-train"></i></div>
        RailConnect Admin
    </div>

    
    <nav class="header-nav">
        <a href="stations.php" <?= in_array($currentAdmin, ['stations','station_ajouter','station_modifier']) ? 'class="active"' : '' ?>>
            <i class="fas fa-map-marker-alt"></i> Stations
        </a>
        <a href="horaires.php" <?= in_array($currentAdmin, ['horaires','horaire_ajouter','horaire_modifier']) ? 'class="active"' : '' ?>>
            <i class="fas fa-clock"></i> Horaires
        </a>
        <a href="messages.php" <?= $currentAdmin === 'messages' ? 'class="active"' : '' ?>>
            <i class="fas fa-envelope"></i> Messages
        </a>
    </nav>

   
    <a href="deconnexion.php" class="btn-logout">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
    </a>

</header>

<div class="page-body">
