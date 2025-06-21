<?php $base_url = Flight::get('flight.base_url'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Requêtes Clients</title>
    <link rel="stylesheet" href="<?=$base_url?>/assets/css/requeteClients.css">
</head>
<body>
<header>
    <h2>CRM - Gestion des Requêtes</h2>
</header>

<div class="sidebar">
    <nav class="sidebar-nav">
        <a href="<?= $base_url ?>/dashboard">Accueil</a>
        <a href="<?= $base_url ?>/clients">Clients</a>
        <a href="<?= $base_url ?>/produits">Produits</a>
        <a href="<?= $base_url ?>/commandes">Commandes</a>
        <a href="<?= $base_url ?>/stat">Statistique</a>
        <a href="<?= $base_url ?>/simulation">Simulation</a>
        <a href="<?= $base_url ?>/actioncommercial">Actions Commerciales</a>
        <a href="<?= $base_url ?>/requeteClient" class="active">Requêtes Clients</a>
    </nav>
</div>

<div class="main-content">
    <h1>Gestion des Requêtes Clients</h1>
    
    <!-- Formulaire de filtre -->
    <div class="filter-container">
        <form method="get" action="<?= $base_url ?>/requeteClient" class="filter-form">
            <div class="form-group">
                <label for="filter_etat">Filtrer par état :</label>
                <select name="filter_etat" id="filter_etat" class="form-control">
                    <option value="">Tous les états</option>
                    <?php foreach ($etats as $etat): ?>
                    <option value="<?= $etat['id_etat'] ?>" 
                        <?= (isset($_GET['filter_etat']) && $_GET['filter_etat'] == $etat['id_etat'] ? 'selected' : '') ?>>
                        <?= htmlspecialchars($etat['libelle']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="filter_client">Client :</label>
                <input type="text" name="filter_client" id="filter_client" class="form-control" 
                       placeholder="Nom du client" value="<?= htmlspecialchars($_GET['filter_client'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="filter_date">Date :</label>
                <input type="date" name="filter_date" id="filter_date" class="form-control" 
                       value="<?= htmlspecialchars($_GET['filter_date'] ?? '') ?>">
            </div>
            
            <button type="submit" class="filter-btn">Filtrer</button>
            <a href="<?= $base_url ?>/requeteClient" class="reset-btn">Réinitialiser</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="products-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Sujet</th>
                    <th>Date</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($requeteClient)): ?>
                <tr>
                    <td colspan="6" class="no-results">Aucune requête trouvée</td>
                </tr>
                <?php else: ?>
                <?php foreach ($requeteClient as $requete): ?>
                <tr>
                    <td><?= htmlspecialchars($requete['id_requete']) ?></td>
                    <td>
                        <?= htmlspecialchars($requete['client_nom'] ?? 'Client #'.htmlspecialchars($requete['id_client'])) ?>
                        <?= isset($requete['client_prenom']) ? htmlspecialchars($requete['client_prenom']) : '' ?>
                    </td>
                    <td><?= htmlspecialchars($requete['Sujet']) ?></td>
                    <td data-sort="<?= htmlspecialchars($requete['Date_creation']) ?>">
                        <?= date('d/m/Y H:i', strtotime($requete['Date_creation'])) ?>
                    </td>
                    <td>
                        <?php 
                        $statusClass = str_replace(['é', 'è', 'ê'], 'e', strtolower($requete['etat_libelle'] ?? $requete['id_etat']));
                        $statusClass = preg_replace('/[^a-z0-9]/', '-', $statusClass);
                        ?>
                        <span class="status-badge status-<?= $statusClass ?>">
                            <?= htmlspecialchars($requete['etat_libelle'] ?? $requete['id_etat']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?= $base_url ?>/requeteClient/details/<?= $requete['id_requete'] ?>" class="action-btn view-btn">👁️ Voir</a>
                            <a href="<?= $base_url ?>/requeteClient/modifier/<?= $requete['id_requete'] ?>" class="action-btn edit-btn">✏️ Modifier</a>
                            <a href="<?= $base_url ?>/requeteClient/supprimer/<?= $requete['id_requete'] ?>" class="action-btn delete-btn" onclick="return confirm('Confirmer la suppression ?')">🗑️ Supprimer</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
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

        if (column === 3) { // Colonne date
            const dateA = new Date(a.cells[column].getAttribute('data-sort') || aVal);
            const dateB = new Date(b.cells[column].getAttribute('data-sort') || bVal);
            return (dateA - dateB) * (asc ? 1 : -1);
        }

        if (!isNaN(parseFloat(aVal.replace(',', '.')))) {
            return (parseFloat(aVal.replace(',', '.')) - parseFloat(bVal.replace(',', '.'))) * (asc ? 1 : -1);
        }

        return aVal.localeCompare(bVal, 'fr', { numeric: true }) * (asc ? 1 : -1);
    });

    tableBody.innerHTML = '';
    sortedRows.forEach(row => tableBody.appendChild(row));
}
</script>
</body>
</html>