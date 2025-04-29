<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes - CRM</title>
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
            <a href="produits" >Produits</a>
            <a href="commandes" class="active">Commandes</a>
            <a href="stat">Statistique</a>
            <a href="simulation">Simulation</a>
        </nav>
    </div>

    <div class="main-content">
        <h1>Liste des Commandes</h1>
        <div class="table-responsive">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?= htmlspecialchars($commande['CommandeID']) ?></td>
                        <td><?= htmlspecialchars($commande['DateCommande']) ?></td>
                        <td><?= number_format($commande['MontantTotal'], 2, ',', ' ') ?> €</td>
                        <td><?= htmlspecialchars($commande['Statut']) ?></td>
                        <td>
                            <form action="/commande/delete" method="POST" style="display:inline;">
                                <input type="hidden" name="CommandeID" value="<?= $commande['CommandeID'] ?>">
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
    document.querySelectorAll('.products-table th').forEach(header => {
        header.addEventListener('click', () => {
            const idx = header.cellIndex;
            const asc = header.classList.toggle('asc');
            document.querySelectorAll('.products-table th').forEach(h => { if(h!==header) h.classList.remove('asc'); });
            sortTable(idx, asc);
        });
    });
    function sortTable(col, asc) {
        const tbody = document.querySelector('.products-table tbody');
        Array.from(tbody.rows)
            .sort((a,b) => {
                let x = a.cells[col].textContent.trim();
                let y = b.cells[col].textContent.trim();
                if(!isNaN(parseFloat(x))) x = parseFloat(x.replace(',', '.'));
                if(!isNaN(parseFloat(y))) y = parseFloat(y.replace(',', '.'));
                return (x > y ? 1 : x < y ? -1 : 0) * (asc ? 1 : -1);
            })
            .forEach(row => tbody.appendChild(row));
    }
    </script>
</body>
</html>
