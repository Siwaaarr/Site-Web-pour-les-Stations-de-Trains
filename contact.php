<?php
// ============================================================
// contact.php — Formulaire de contact avec enregistrement BDD
//
// CHANGEMENTS PAR RAPPORT À LA VERSION PRÉCÉDENTE :
//
// 1. isset($_POST['nom']) au lieu de $_SERVER['REQUEST_METHOD']
//    → Plus simple et direct : on vérifie si le champ 'nom' existe
//      dans $_POST. S'il existe = formulaire soumis. Sinon = première visite.
//
// 2. Suppression de trim()
//    → trim() supprime les espaces en début/fin de chaîne
//    → Dans une vraie application c'est utile, mais pour simplifier
//      le code selon les instructions, on l'a retiré ici
//
// 3. Suppression du filtre filter_var pour l'email
//    → Validation simplifiée : on vérifie juste que les champs ne sont pas vides
// ============================================================

require_once 'config/db.php';

$success = false;
$errors  = [];

// ─────────────────────────────────────────────────────────────
// TRAITEMENT DU FORMULAIRE
//
// isset($_POST['nom']) = vrai si le formulaire a été soumis
//   car le champ name="nom" existe dans le <form>
//   Quand on soumet, PHP remplit $_POST avec tous les champs
//   Quand on visite la page normalement, $_POST est vide
//
// DIFFÉRENCE AVEC $_SERVER['REQUEST_METHOD'] === 'POST' :
//   $_SERVER['REQUEST_METHOD'] vérifie la méthode HTTP de la requête
//   isset($_POST['nom'])       vérifie qu'un champ spécifique a été envoyé
//   Les deux fonctionnent. isset() est plus direct et plus simple.
// ─────────────────────────────────────────────────────────────
if (isset($_POST['nom'])) {

    // Récupération des valeurs depuis $_POST
    // ?? '' = si le champ n'existe pas dans $_POST, utiliser '' (chaîne vide)
    $nom     = $_POST['nom']     ?? '';
    $email   = $_POST['email']   ?? '';
    $sujet   = $_POST['sujet']   ?? '';
    $message = $_POST['message'] ?? '';

    // ── Validation ───────────────────────────────────────────
    // On vérifie que chaque champ n'est pas vide
    // Si vide → on ajoute un message d'erreur dans $errors
    if ($nom === '')     $errors[] = 'Le nom est requis.';
    if ($email === '')   $errors[] = "L'email est requis.";
    if ($sujet === '')   $errors[] = 'Le sujet est requis.';
    if ($message === '') $errors[] = 'Le message est requis.';

    // ── Enregistrement en BDD si aucune erreur ───────────────
    // empty($errors) = vrai si le tableau $errors est vide (aucune erreur)
    if (empty($errors)) {
        // Requête préparée : INSERT dans la table contacts
        // Les ? sont remplacés par les vraies valeurs de façon sécurisée
        $stmt = $pdo->prepare(
            "INSERT INTO contacts (nom, email, sujet, message) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$nom, $email, $sujet, $message]);
        $success = true;   // pour afficher le message de succès
    }
}

$pageTitle = 'Contact';
include 'includes/header.php';
?>

<style>
    .page-header { background:linear-gradient(135deg,#2e7d32,#1b5e20); color:white; padding:60px 20px; text-align:center; margin-bottom:40px; }
    .page-header h1 { font-size:2.5rem; margin-bottom:15px; }
    .page-header p  { font-size:1.1rem; opacity:.95; max-width:800px; margin:0 auto; }
    .container { max-width:1200px; margin:0 auto; padding:0 20px 50px; }
    .contact-grid { display:grid; grid-template-columns:1fr 1fr; gap:40px; margin-bottom:40px; }

    /* Formulaire */
    .form-section { background:white; padding:35px; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,.1); }
    .form-section h2 { color:#1b5e20; margin-bottom:20px; font-size:1.8rem; display:flex; align-items:center; gap:10px; }
    .form-section > p { color:#2e7d32; margin-bottom:30px; line-height:1.6; }
    .form-group { margin-bottom:25px; }
    .form-group label { display:flex; align-items:center; gap:8px; color:#1b5e20; margin-bottom:10px; font-weight:600; }
    .form-control { width:100%; padding:14px; border:2px solid #a5d6a7; border-radius:10px; font-size:15px; transition:all .3s; background:#f8fff8; font-family:inherit; }
    .form-control:focus { outline:none; border-color:#2e7d32; box-shadow:0 0 0 3px rgba(46,125,50,.1); }
    textarea.form-control { min-height:150px; resize:vertical; }
    .form-control.error { border-color:#f44336; }
    .btn-submit { background:linear-gradient(135deg,#43a047,#2e7d32); color:white; padding:16px 40px; border:none; border-radius:12px; font-size:18px; font-weight:600; cursor:pointer; transition:all .3s; width:100%; display:flex; align-items:center; justify-content:center; gap:10px; }
    .btn-submit:hover { background:linear-gradient(135deg,#2e7d32,#1b5e20); transform:translateY(-3px); }

    /* Alertes */
    .alert { padding:18px 24px; border-radius:12px; margin-bottom:25px; display:flex; align-items:flex-start; gap:12px; }
    .alert-success { background:#e8f5e9; border:2px solid #66bb6a; color:#1b5e20; }
    .alert-error   { background:#ffebee; border:2px solid #ef9a9a; color:#b71c1c; }
    .alert ul { margin:8px 0 0 18px; }
    .alert ul li { margin-bottom:4px; }

    /* Infos */
    .info-section { background:white; padding:35px; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,.1); }
    .info-section h2 { color:#1b5e20; margin-bottom:30px; font-size:1.8rem; display:flex; align-items:center; gap:10px; }
    .contact-item { display:flex; align-items:flex-start; gap:20px; margin-bottom:30px; padding:20px; background:linear-gradient(135deg,#f8fff8,#e8f5e9); border-radius:15px; transition:all .3s; border-left:5px solid #66bb6a; }
    .contact-item:hover { transform:translateX(10px); box-shadow:0 5px 20px rgba(46,125,50,.15); }
    .contact-icon-box { background:linear-gradient(135deg,#66bb6a,#43a047); color:white; width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0; }
    .contact-details h3 { color:#1b5e20; margin-bottom:10px; font-size:1.2rem; }
    .contact-details p  { color:#2e7d32; margin:5px 0; line-height:1.6; }
    .contact-details small { color:#66bb6a; font-style:italic; }

    @media(max-width:768px){ .contact-grid{ grid-template-columns:1fr; } }
</style>

<div class="page-header">
    <h1><i class="fas fa-comments"></i> Contactez-nous</h1>
    <p>Une question ? Un problème ? Notre équipe est à votre écoute</p>
</div>

<div class="container">
    <div class="contact-grid">

        <!-- Formulaire -->
        <div class="form-section">
            <h2><i class="fas fa-paper-plane"></i> Formulaire de contact</h2>
            <p>Remplissez ce formulaire et nous vous répondrons dans les plus brefs délais.</p>

            <!-- Message succès : affiché seulement si $success = true -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle fa-lg"></i>
                    <div>
                        <strong>Message envoyé avec succès !</strong><br>
                        Merci de nous avoir contactés. Nous vous répondrons bientôt.
                    </div>
                </div>
            <?php endif; ?>

            <!-- Liste des erreurs : affichée seulement si $errors n'est pas vide -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle fa-lg"></i>
                    <div>
                        <strong>Veuillez corriger les erreurs suivantes :</strong>
                        <ul>
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Le formulaire envoie vers contact.php avec method POST -->
            <form method="POST" action="contact.php">

                <div class="form-group">
                    <label for="nom"><i class="fas fa-user"></i> Nom complet *</label>
                    <input type="text" id="nom" name="nom"
                           class="form-control <?= in_array('Le nom est requis.', $errors) ? 'error' : '' ?>"
                           placeholder="Votre nom et prénom"
                           value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                    <!--
                        value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                        → Après soumission avec erreurs, le champ garde sa valeur
                        → L'utilisateur ne perd pas ce qu'il avait tapé
                        → ?? '' = si pas encore soumis, valeur vide
                    -->
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Adresse email *</label>
                    <input type="email" id="email" name="email"
                           class="form-control <?= in_array("L'email est requis.", $errors) ? 'error' : '' ?>"
                           placeholder="votre@email.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="sujet"><i class="fas fa-tag"></i> Sujet *</label>
                    <input type="text" id="sujet" name="sujet"
                           class="form-control <?= in_array('Le sujet est requis.', $errors) ? 'error' : '' ?>"
                           placeholder="Sujet de votre message"
                           value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="message"><i class="fas fa-comment-dots"></i> Message *</label>
                    <textarea id="message" name="message"
                              class="form-control <?= in_array('Le message est requis.', $errors) ? 'error' : '' ?>"
                              placeholder="Décrivez votre demande en détail..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Envoyer le message
                </button>
            </form>
        </div>

        <!-- Coordonnées -->
        <div class="info-section">
            <h2><i class="fas fa-info-circle"></i> Nos coordonnées</h2>

            <div class="contact-item">
                <div class="contact-icon-box"><i class="fas fa-map-marker-alt"></i></div>
                <div class="contact-details">
                    <h3>Adresse principale</h3>
                    <p><strong>RailConnect France</strong></p>
                    <p>15 Avenue des Chemins de Fer</p>
                    <p>75012 Paris, France</p>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon-box"><i class="fas fa-phone"></i></div>
                <div class="contact-details">
                    <h3>Téléphone</h3>
                    <p><strong>+33 1 23 45 67 89</strong></p>
                    <small>Service client : Lun-Ven 7h-22h</small>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon-box"><i class="fas fa-envelope"></i></div>
                <div class="contact-details">
                    <h3>Email</h3>
                    <p>contact@railconnect-france.fr</p>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon-box"><i class="fas fa-clock"></i></div>
                <div class="contact-details">
                    <h3>Horaires d'ouverture</h3>
                    <p><strong>Lundi - Vendredi :</strong> 7h - 22h</p>
                    <p><strong>Samedi - Dimanche :</strong> 8h - 20h</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
