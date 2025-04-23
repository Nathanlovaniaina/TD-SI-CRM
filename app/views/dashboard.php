<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gestion Budget</title>
    <style>
        :root {
            --primary-color: #1b3e6f;
            --secondary-color: #2e5392;
            --accent-color: #4169e1;
            --text-light: #ffffff;
            --background: #f5f5f5;
            --shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        body {
            margin: 0;
            font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
            background-color: var(--background);
            line-height: 1.6;
        }

        header {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
        }

        header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .sidebar {
            width: 240px;
            background-color: var(--secondary-color);
            height: calc(100vh - 60px);
            position: fixed;
            top: 60px;
            left: 0;
            overflow-y: auto;
            padding: 1rem 0;
            transition: transform 0.3s ease;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: var(--text-light);
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
            gap: 0.75rem;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: var(--accent-color);
            padding-left: 2rem;
        }

        .main-content {
            margin-left: 240px;
            padding: 2rem;
            margin-top: 60px;
            max-width: 1200px;
        }

        h1 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .card h3 {
            margin-top: 0;
            color: var(--primary-color);
            font-size: 1.25rem;
            border-bottom: 2px solid var(--background);
            padding-bottom: 0.5rem;
        }

        .card ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .card li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }

        .card li:last-child {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 100;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
                margin-top: 60px;
            }

            header h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>

    <header>
        <h2>CRM</h2>
    </header>

    <div class="sidebar">
        <nav class="sidebar-nav">
            <a href="#" class="active">🏠 Accueil</a>
            <a href="#">👥 Clients</a>
            <a href="#">📦 Produits</a>
            <a href="#">📋 Commandes</a>
            <a href="#">📞 Actions Client</a>
            <a href="#">📣 Actions Com.</a>
            <a href="#">💰 Budget</a>
        </nav>
    </div>

    <div class="main-content">
        <h1>Vue d'ensemble</h1>
        <div class="card-grid">
            <div class="card">
                <h3>📊 KPI</h3>
                <ul>
                    <li><strong>Clients Totals : </strong> 120</li>
                    <li><strong>Commandes : </strong> 85</li>
                    <li><strong>Chiffre d'affaires : </strong> €15,340</li>
                    <li><strong>Taux conversion : </strong> 45%</li>
                </ul>
            </div>

            <div class="card">
                <h3>📦 Commandes Récentes</h3>
                <ul>
                    <li>Cmd #1023 - Dupont J. - €220.00 - Livrée</li>
                    <li>Cmd #1024 - Martin M. - €85.50 - En cours</li>
                    <li>Cmd #1025 - Durand L. - €47.20 - Annulée</li>
                </ul>
            </div>

            <div class="card">
                <h3>💼 Actions Commerciales</h3>
                <ul>
                    <li>Promo printemps - 01/04 → 30/04 - Coût €1,200</li>
                    <li>Offre Été - 10/06 → 20/06 - Coût €900</li>
                </ul>
            </div>

            <div class="card">
                <h3>🔔 Requêtes Budgétaires</h3>
                <ul>
                    <li>#15 - €5,000 - À valider</li>
                    <li>#16 - €12,000 - Validée</li>
                </ul>
            </div>

            <div class="card">
                <h3>⚠️ Produits en Rupture</h3>
                <ul>
                    <li>Chocolat - Stock: 2</li>
                    <li>Lait - Stock: 5</li>
                </ul>
            </div>
        </div>
    </div>

</body>
</html>
