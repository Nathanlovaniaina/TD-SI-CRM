<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actions Commerciales - CRM</title>
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
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin: 1rem 0;
            box-shadow: var(--shadow);
        }
        .products-table th,
        .products-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .products-table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 60px;
            cursor: pointer;
        }
        .products-table th.asc::after {
            content: " ▲";
            font-size: 0.8rem;
        }
        .products-table th:not(.asc)::after {
            content: " ▼";
            font-size: 0.8rem;
        }
        .action-link {
            color: #dc3545;
            text-decoration: none;
            font-weight: 500;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font: inherit;
        }
        .action-link:hover {
            text-decoration: underline;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 50rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-convertie {
            background-color: #28a745;
            color: white;
        }
        .badge-non-convertie {
            background-color: #6c757d;
            color: white;
        }
        .add-btn {
            background-color: var(--accent-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            text-decoration: none;
        }
        .add-btn:hover {
            background-color: var(--secondary-color);
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .edit-btn {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font: inherit;
        }
        .edit-btn:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 280px; }
            .main-content { margin-left: 0; padding: 1rem; }
            header h2 { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <header>
        <h2>CRM</h2>
    </header>

    <div class="sidebar">
        <nav class="sidebar-nav">
            <a href="dashboard">Accueil</a>
            <a href="clients">Clients</a>
            <a href="produits">Produits</a>
            <a href="commandes">Commandes</a>
            <a href="stat">Statistique</a>
            <a href="simulation">Simulation</a>
            <a href="actions-commerciales" class="active">Actions Commerciales</a>
        </nav>
    </div>

    <div class="main-content">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Liste des Actions Commerciales</h1>
            <a href="actioncommercialForm" class="add-btn">➕ Ajouter une action</a>
        </div>
        
        <div class="table-responsive">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Action ID</th>
                        <th>Campagne</th>
                        <th>Objectif</th>
                        <th>Statut</th>
                        <th>Coût</th>
                        <th>Client Rate</th>
                        <th>Commande Rate</th>
                        <th>Prix Rate</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actions as $action): ?>
                    <tr>
                        <td><?= htmlspecialchars($action['ActionCommercialeID']) ?></td>
                        <td><?= htmlspecialchars($action['ActionID']) ?></td>
                        <td><?= htmlspecialchars($action['Campagne']) ?></td>
                        <td><?= htmlspecialchars($action['Objectif']) ?></td>
                        <td>
                            <span class="badge <?= $action['EstConvertie'] ? 'badge-convertie' : 'badge-non-convertie' ?>">
                                <?= $action['EstConvertie'] ? 'Convertie' : 'Non convertie' ?>
                            </span>
                        </td>
                        <td class="rate-cell"><?= number_format($action['Cout'], 2, ',', ' ') ?> €</td>
                        <td class="rate-cell"><?= number_format($action['ClientRate'], 2) ?></td>
                        <td class="rate-cell"><?= number_format($action['CommandeRate'], 2) ?></td>
                        <td class="rate-cell"><?= number_format($action['PrixRate'], 2) ?></td>
                        <td class="action-buttons">
                            <!-- <a href="/action-commerciale/modifier/<?= $action['ActionCommercialeID'] ?>" class="edit-btn">✏️</a> -->
                            <form action="/actioncommercial/delete" method="POST" style="display:inline;">
                                <input type="hidden" name="ActionCommercialeID" value="<?= $action['ActionCommercialeID'] ?>">
                                <button type="submit" class="action-link" onclick="return confirm('Confirmer la suppression ?')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    // [Conserver le script de tri précédent]
    </script>
</body>
</html>