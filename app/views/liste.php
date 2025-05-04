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
        }

        header h2 {
            font-size: 1.2rem;
        }
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

    .stock-low {
        color: #dc3545;
        font-weight: 500;
        background-color: #fff0f0;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
    }

    .category-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        background-color: #e8f4f8;
        color: #1b3e6f;
        font-size: 0.85rem;
    }

    .price-cell {
        font-weight: 600;
        color: var(--primary-color);
    }

    .add-product-btn {
        background-color: var(--accent-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .action-link {
        color: #dc3545;
        text-decoration: none;
        font-weight: 500;
    }

    .action-link:hover {
        text-decoration: underline;
    }
</style>

<header>
    <h2>CRM</h2>
</header>

<div class="sidebar">
    <div class="sidebar">
        <nav class="sidebar-nav">
            <a href="dashboard" >Accueil</a>
            <a href="clients" >Clients</a>
            <a href="produits" class="active">Produits</a>
            <a href="commandes">Commandes</a>
            <a href="stat">Statistique</a>
            <a href="simulation">Simulation</a>
        </nav>
    </div>
</div>

<div class="main-content">
    <h1>Gestion des Produits</h1>
    <a href="/formProduit" class="add-product-btn">➕ Ajouter un produit</a>

    <div class="table-responsive">
        <table class="products-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produits as $produit): ?>
                <tr>
                    <td><?= htmlspecialchars($produit['ProduitID']) ?></td>
                    <td><?= htmlspecialchars($produit['Nom']) ?></td>
                    <td><span class="category-badge"><?= htmlspecialchars($produit['Categorie']) ?></span></td>
                    <td class="price-cell"><?= number_format($produit['Prix'], 2, ',', ' ') ?> €</td>
                    <td>
                        <?php if($produit['Stock'] < 10): ?>
                            <span class="stock-low"><?= htmlspecialchars($produit['Stock']) ?> (Faible)</span>
                        <?php else: ?>
                            <?= htmlspecialchars($produit['Stock']) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/produits/supprimer/<?= $produit['ProduitID'] ?>" class="action-link" onclick="return confirm('Confirmer la suppression ?')">
                            🗑️ Supprimer
                        </a>
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
        const columnIndex = header.cellIndex;
        const isAsc = header.classList.toggle('asc');
        document.querySelectorAll('.products-table th').forEach(h => {
            if (h !== header) h.classList.remove('asc');
        });
        sortTableByColumn(columnIndex, isAsc);
    });
});

function sortTableByColumn(column, asc = true) {
    const tableBody = document.querySelector('.products-table tbody');
    const rows = Array.from(tableBody.querySelectorAll('tr'));

    const sortedRows = rows.sort((a, b) => {
        const aVal = a.cells[column].textContent.trim();
        const bVal = b.cells[column].textContent.trim();

        if (!isNaN(parseFloat(aVal.replace(',', '.')))) {
            return (parseFloat(aVal.replace(',', '.')) - parseFloat(bVal.replace(',', '.'))) * (asc ? 1 : -1);
        }

        return aVal.localeCompare(bVal, 'fr', { numeric: true }) * (asc ? 1 : -1);
    });

    tableBody.innerHTML = '';
    sortedRows.forEach(row => tableBody.appendChild(row));
}
</script>
