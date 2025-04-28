<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemple Dolibarr Style</title>
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

        .sidebar a:hover {
            background-color: var(--accent-color);
            padding-left: 2rem;
        }

        .sidebar a.active {
            background-color: var(--accent-color);
            border-left: 4px solid var(--text-light);
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
        <h2>Dolibarr Style - Démo</h2>
    </header>

    <div class="sidebar">
        <nav class="sidebar-nav">
            <a href="#" class="active">🏠 Accueil</a>
            <a href="/clients">👥 Clients</a>
            <a href="/commandes">📋 Commandes</a>            
            <a href="#">📦 Produits</a>
            <a href="#">🧾 Factures</a>
            <a href="#">👤 Utilisateurs</a>
        </nav>
    </div>

    <div class="main-content">
        <h1>Bienvenue dans votre ERP</h1>
        <div class="card-grid">
            <div class="card">
                <h3>📈 Clients récents</h3>
                <ul>
                    <li>Alice Durand - Client actif</li>
                    <li>Jean Martin - Client inactif</li>
                    <li>Sophie Lambert - Nouveau client</li>
                </ul>
            </div>

            <div class="card">
                <h3>📦 Produits en stock</h3>
                <ul>
                    <li>Ordinateur Portable - 50 unités</li>
                    <li>Smartphone - 120 unités</li>
                    <li>Écran 24" - 35 unités</li>
                </ul>
            </div>

            <div class="card">
                <h3>📅 Prochaines échéances</h3>
                <ul>
                    <li>Facture #1234 - 15/06/2024</li>
                    <li>Commande #5678 - 18/06/2024</li>
                </ul>
            </div>
        </div>
    </div>

</body>
</html>