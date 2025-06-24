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
        .table-container {
            overflow-x: auto;
            margin: 1rem 0;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .data-table th,
        .data-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .data-table th {
            background-color: var(--primary-color);
            color: var(--text-light);
            font-weight: 600;
            position: sticky;
            top: 60px; /* Correspond à la hauteur du header */
        }

        .data-table tr:hover {
            background-color: #f8f9ff;
        }

        .data-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: #e6f4ea;
            color: #137333;
        }

        .badge-warning {
            background-color: #fef7e0;
            color: #b06000;
        }

        /* Adaptation responsive */
        @media (max-width: 768px) {
            .data-table th,
            .data-table td {
                padding: 0.75rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

    <!-- Header et Sidebar inchangés -->
    <header>
        <h2>CRM</h2>
    </header>

    <div class="sidebar">
        <nav class="sidebar-nav">
            <?= $navbar ?>
        </nav>
    </div>
    <div class="main-content">
        <h1>Vue d'ensemble</h1>
        <div class="card-grid">
            
            <!-- Carte Clients avec Tableau JavaScript -->
            <div class="card">
                <h3>👥 Liste des Clients</h3>
                <div class="table-container">
                    <table class="data-table" id="clientsTable">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="clientsTableBody">
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?= htmlspecialchars($client['Nom']) ?></td>
                                <td><?= htmlspecialchars($client['Email']) ?></td>
                                <td><?= htmlspecialchars($client['Statut']) ?></td>
                                <td><a href="/client/<?= $client['ClientID'] ?>">Voir Détail</a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ... Autres cartes ... -->

        </div>
    </div>

    
</body>
</html>