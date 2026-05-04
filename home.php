<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RailConnect France - Accueil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            color: #1b5e20;
        }

        /* Navigation */
        nav {
            background: linear-gradient(to right, #2e7d32, #1b5e20);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .logo-icon {
            background: white;
            color: #2e7d32;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .nav-links {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .nav-links a.active {
            background-color: #a5d6a7;
            color: #1b5e20;
        }

        /* Hero Section */
        .hero {
            background: white;
            max-width: 1200px;
            margin: 40px auto;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(46, 125, 50, 0.15);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 40px;
        }

        .hero-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-content h1 {
            color: #1b5e20;
            font-size: 2.5rem;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-content p {
            color: #2e7d32;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .hero-highlight {
            color: #1b5e20;
            font-weight: bold;
            font-size: 1.2rem;
            margin: 20px 0;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            background: #2e7d32;
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
        }

        .btn:hover {
            background: #1b5e20;
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(46, 125, 50, 0.4);
        }

        .hero-image {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-image img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .hero-image img:hover {
            transform: scale(1.05);
        }

        /* Search Section */
        .search-section {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .search-section h2 {
            color: #1b5e20;
            margin-bottom: 25px;
            font-size: 1.8rem;
            border-bottom: 3px solid #4caf50;
            padding-bottom: 10px;
        }

        .search-bar {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 15px;
            margin-bottom: 30px;
        }

        .search-bar input {
            padding: 14px;
            border: 2px solid #a5d6a7;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #2e7d32;
        }

        .search-bar button {
            background: #2e7d32;
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .search-bar button:hover {
            background: #1b5e20;
            transform: translateY(-2px);
        }

        /* Train List */
        .train-list {
            margin-top: 20px;
        }

        .train-item {
            padding: 20px;
            border: 2px solid #e8f5e9;
            margin: 15px 0;
            border-radius: 12px;
            background: #f9f9f9;
            transition: all 0.3s ease;
        }

        .train-item:hover {
            background: #e8f5e9;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.1);
        }

        .train-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .train-number {
            font-weight: bold;
            color: #1b5e20;
            font-size: 1.1rem;
        }

        .train-time {
            color: #2e7d32;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .train-route {
            color: #666;
            font-size: 0.95rem;
        }

        .train-status {
            font-size: 0.9rem;
            margin-top: 8px;
            font-weight: 600;
        }

        .status-ok {
            color: #4caf50;
        }

        .status-delay {
            color: #ff9800;
        }

        /* Footer */
        footer {
            background: linear-gradient(to right, #1b5e20, #2e7d32);
            color: white;
            padding: 50px 0 20px;
            margin-top: 60px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .footer-column h3 {
            color: #a5d6a7;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .footer-column p {
            margin: 12px 0;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #c8e6c9;
        }

        .footer-column a {
            color: #c8e6c9;
            display: block;
            margin: 8px 0;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-column a:hover {
            color: #a5d6a7;
            transform: translateX(5px);
            padding-left: 5px;
        }

        .contact-item-footer {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 15px;
        }

        .contact-icon-footer {
            background: rgba(255, 255, 255, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-link {
            background: rgba(255, 255, 255, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: white;
            color: #2e7d32;
            transform: translateY(-5px);
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 30px auto 0;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .copyright {
            color: #a5d6a7;
            font-size: 0.9rem;
        }

        .legal-links {
            display: flex;
            gap: 25px;
        }

        .legal-links a {
            color: #a5d6a7;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .legal-links a:hover {
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                flex-direction: column;
                width: 100%;
            }

            .nav-links a {
                width: 100%;
                justify-content: center;
            }

            .hero {
                grid-template-columns: 1fr;
                padding: 30px 20px;
            }

            .search-bar {
                grid-template-columns: 1fr;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="home.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-train"></i>
                </div>
                <span>RailConnect France</span>
            </a>
            <div class="nav-links">
                <a href="home.php" class="active">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="hor.php">
                    <i class="fas fa-clock"></i> Horaires
                </a>
                <a href="station.php">
                    <i class="fas fa-map-marker-alt"></i> Stations
                </a>
                <a href="tarifs.php">
                    <i class="fas fa-ticket-alt"></i> Tarifs
                </a>
                <a href="contact.php">
                    <i class="fas fa-envelope"></i> Contact
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h1>Votre prochaine aventure commence ici</h1>
            <p>Découvrez notre réseau de stations de trains moderne et efficace. Consultez les horaires, trouvez votre station et achetez vos billets en ligne.</p>
            <p class="hero-highlight">✨ Votre voyage en toute sérénité ✨</p>
            <div class="hero-buttons">
                <button class="btn" onclick="window.location.href='tarifs.html'">
                    <i class="fas fa-shopping-cart"></i> Explorer la boutique
                </button>
                <button class="btn" onclick="alert('Promotions spéciales disponibles !')">
                    <i class="fas fa-tag"></i> Voir les promotions
                </button>
            </div>
        </div>
        <div class="hero-image">
            <img src="image train 1.1.jpg" alt="Train moderne">
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <h2><i class="fas fa-search"></i> Rechercher un train</h2>
        <div class="search-bar">
            <input type="text" id="departure" placeholder="Station de départ">
            <input type="text" id="arrival" placeholder="Station d'arrivée">
             <input type="time" >
              <input type="date">
            <button onclick="searchTrains()">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </div>
        <div class="train-list" id="trainResults"></div>
    </div>

    <!-- Departures Section -->
    <div class="search-section">
        <h2><i class="fas fa-train"></i> Prochains départs</h2>
        <div class="train-list" id="departuresList"></div>
        <button class="btn" onclick="refreshDepartures()" style="width: 100%; margin-top: 20px;">
            <i class="fas fa-sync-alt"></i> Actualiser les horaires
        </button>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h3><i class="fas fa-info-circle"></i> Coordonnées</h3>
                <div class="contact-item-footer">
                    <div class="contact-icon-footer"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <p><strong>RailConnect France</strong></p>
                        <p>15 Avenue des Chemins de Fer</p>
                        <p>75012 Paris, France</p>
                    </div>
                </div>
                <div class="contact-item-footer">
                    <div class="contact-icon-footer"><i class="fas fa-phone"></i></div>
                    <div>
                        <p><strong>Téléphone</strong></p>
                        <p>+33 1 23 45 67 89</p>
                    </div>
                </div>
                <div class="contact-item-footer">
                    <div class="contact-icon-footer"><i class="fas fa-envelope"></i></div>
                    <div>
                        <p><strong>Email</strong></p>
                        <p>contact@railconnect-france.fr</p>
                    </div>
                </div>
            </div>

            <div class="footer-column">
                <h3><i class="fas fa-link"></i> Liens rapides</h3>
                <a href="hor.php"><i class="fas fa-clock"></i> Horaires des trains</a>
                <a href="station.php"><i class="fas fa-map-marker-alt"></i> Stations et gares</a>
                <a href="tarifs.php"><i class="fas fa-ticket-alt"></i> Tarifs et abonnements</a>
                <a href="contact.php"><i class="fas fa-envelope"></i> Contact et assistance</a>
            </div>

            <div class="footer-column">
                <h3><i class="fas fa-file-alt"></i> Mentions légales</h3>
                <a href="#mentions-legales">Mentions légales</a>
                <a href="#confidentialite">Politique de confidentialité</a>
                <a href="#cgv">Conditions générales de vente</a>
            </div>

            <div class="footer-column">
                <h3><i class="fas fa-share-alt"></i> Réseaux sociaux</h3>
                <p>Suivez-nous sur les réseaux sociaux</p>
                <div class="social-links">
                    <a href="https://facebook.com" class="social-link" target="_blank" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://instagram.com" class="social-link" target="_blank" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://twitter.com" class="social-link" target="_blank" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://youtube.com" class="social-link" target="_blank" title="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="copyright">
                © <?= date("Y") ?> RailConnect France. Tous droits réservés.
            </div>
            <div class="legal-links">
                <a href="#mentions">Mentions légales</a>
                <a href="#confidentialite">Confidentialité</a>
                <a href="#cookies">Cookies</a>
            </div>
        </div>
    </footer>

    <script>
        const trains = [
            { number: "TGV 6451", from: "Paris Gare de Lyon", to: "Lyon Part-Dieu", 
              departure: "08:30", arrival: "10:15", status: "À l'heure" },
            { number: "TGV 7322", from: "Paris Gare de Lyon", to: "Marseille", 
              departure: "09:15", arrival: "12:30", status: "Retard 5 min" },
            { number: "TGV 8814", from: "Lyon Part-Dieu", to: "Marseille", 
              departure: "11:00", arrival: "13:05", status: "À l'heure" },
            { number: "TER 4512", from: "Paris Nord", to: "Lille", 
              departure: "10:45", arrival: "12:20", status: "À quai" },
            { number: "TGV 5623", from: "Paris Montparnasse", to: "Bordeaux", 
              departure: "14:20", arrival: "16:30", status: "À l'heure" }
        ];

        function searchTrains() {
            const departure = document.getElementById('departure').value.toLowerCase();
            const arrival = document.getElementById('arrival').value.toLowerCase();
            const resultsDiv = document.getElementById('trainResults');

            let filteredTrains = trains;

            if (departure) {
                filteredTrains = filteredTrains.filter(train => 
                    train.from.toLowerCase().includes(departure)
                );
            }

            if (arrival) {
                filteredTrains = filteredTrains.filter(train => 
                    train.to.toLowerCase().includes(arrival)
                );
            }

            if (filteredTrains.length === 0) {
                resultsDiv.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;"><i class="fas fa-info-circle"></i> Aucun train trouvé</p>';
            } else {
                resultsDiv.innerHTML = filteredTrains.map(train => `
                    <div class="train-item">
                        <div class="train-info">
                            <span class="train-number"><i class="fas fa-train"></i> ${train.number}</span>
                            <span class="train-time">${train.departure} → ${train.arrival}</span>
                        </div>
                        <div class="train-route"><i class="fas fa-route"></i> ${train.from} → ${train.to}</div>
                        <div class="train-status ${train.status.includes('Retard') ? 'status-delay' : 'status-ok'}">
                            <i class="fas ${train.status.includes('Retard') ? 'fa-exclamation-triangle' : 'fa-check-circle'}"></i> ${train.status}
                        </div>
                    </div>
                `).join('');
            }
        }

        function displayDepartures() {
            const list = document.getElementById('departuresList');
            list.innerHTML = trains.map(train => `
                <div class="train-item">
                    <div class="train-info">
                        <span class="train-number"><i class="fas fa-train"></i> ${train.number}</span>
                        <span class="train-time">${train.departure}</span>
                    </div>
                    <div class="train-route"><i class="fas fa-route"></i> ${train.from} → ${train.to}</div>
                    <div class="train-status ${train.status.includes('Retard') ? 'status-delay' : 'status-ok'}">
                        <i class="fas ${train.status.includes('Retard') ? 'fa-exclamation-triangle' : 'fa-check-circle'}"></i> ${train.status}
                    </div>
                </div>
            `).join('');
        }

        function refreshDepartures() {
            const btn = event.target;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualisation...';
            btn.disabled = true;

            setTimeout(() => {
                displayDepartures();
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                alert('Horaires actualisés avec succès !');
            }, 1000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            displayDepartures();

            document.getElementById('departure').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') searchTrains();
            });

            document.getElementById('arrival').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') searchTrains();
            });
        });
    </script>
</body>
</html>