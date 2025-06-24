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
            <?= $navbar ?>
        </nav>
    </div>

    <div class="main-content">
        <h1>Vue d'ensemble</h1>
        <div class="card-grid">
            <div class="card">
                <h3>📊 Information divers</h3>
                <ul>
                    <li><strong>Clients Totals : </strong> <?= $nb_clients ?></li>
                    <li><strong>Commandes : </strong> <?= $nb_commandes ?></li>
                </ul>
            </div>

            <div class="card">
                <h3>📦 Commandes Récentes</h3>
                <ul>
                    <?php if (!empty($commandes)): ?>
                        <?php foreach ($commandes as $commande): ?>
                            <li>
                                Cmd #<?= htmlspecialchars($commande['CommandeID']) ?> -
                                <?= htmlspecialchars($commande['DateCommande']) ?> -
                                €<?= number_format($commande['MontantTotal'], 2, ',', ' ') ?> -
                                <?= htmlspecialchars($commande['Statut']) ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Aucune commande récente.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- <div class="card">
                <h3>💼 Actions Commerciales</h3>
                <ul>
                    <li>Promo printemps - 01/04 → 30/04 - Coût €1,200</li>
                    <li>Offre Été - 10/06 → 20/06 - Coût €900</li>
                </ul>
            </div> -->

            <div class="card">
                <h3>📦 Nombre de produits commandés par catégorie</h3>
                <div class="chart-container" style="position: relative; height: 300px;">
                    <canvas id="prodCatChart"></canvas>
                </div>
            </div>

            <div class="card">
                <h3>👥 Répartition clients par sexe</h3>
                <div class="chart-container" style="position: relative; height: 300px;">
                    <canvas id="genreChart"></canvas>
                </div>
            </div>

            <div class="card">
                <h3>👥 Clients par classification</h3>
                <div class="chart-container" style="position: relative; height: 300px;">
                    <canvas id="trancheAgeChart"></canvas>
                </div>
            </div>

        </div>
    </div>
                        <!-- EN TÊTE ou juste avant ton <script> de création de chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    // Sérialisation PHP → JS
    <?php
        // $stats contient ['Le Jeune'=>12, 'Parent'=>8, …]
        $stats = $nombre_clients_par_tranche_age;
    ?>
    const trancheLabels = <?= json_encode(array_keys($stats), JSON_UNESCAPED_UNICODE) ?>;
    const trancheValues = <?= json_encode(array_values($stats)) ?>;

    // Création du camembert (doughnut)
    const ctxTranche = document.getElementById('trancheAgeChart').getContext('2d');
    new Chart(ctxTranche, {
        type: 'doughnut',
        data: {
            labels: trancheLabels,
            datasets: [{
                data: trancheValues,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = trancheValues.reduce((sum, v) => sum + v, 0);
                            const percent = total ? (value / total * 100).toFixed(2) : 0;
                            return `${label}: ${percent}% (${value})`;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Répartition des clients par classification',
                    font: { size: 16 },
                    padding: { bottom: 10 }
                }
            }
        }
    });
</script>

    <!-- Chart.js (ajoute le CDN une seule fois) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sérialisation PHP→JS
        <?php if (!empty($nd_clients_par_genre)): ?>
            const genreData = <?= json_encode($nd_clients_par_genre) ?>;
        <?php else: ?>
            const genreData = {};
        <?php endif; ?>

        // Préparation des labels et valeurs
        const labels = Object.keys(genreData);
        const values = Object.values(genreData);

        // Création du camembert (doughnut)
        const ctx = document.getElementById('genreChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    /* Tu peux personnaliser ces couleurs ou en générer dynamiquement */
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',   // ex. Hommes
                        'rgba(54, 162, 235, 0.6)',   // ex. Femmes
                        'rgba(255, 206, 86, 0.6)'    // ex. Autres
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = values.reduce((sum, v) => sum + v, 0);
                                const percent = total ? (value / total * 100).toFixed(2) : 0;
                                return `${label}: ${percent}% (${value})`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Clients par genre',
                        font: { size: 16 },
                        padding: { bottom: 10 }
                    }
                }
            }
        });
    </script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Sérialisation PHP → JS
    const dataCat = <?= json_encode($nombre_produits_commandes_par_categorie ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const labels = Object.keys(dataCat);
    const values = Object.values(dataCat);

    // 2. Vérifie la console
    console.log('Catégories:', labels, 'Valeurs:', values);

    // 3. Création du camembert
    const ctx = document.getElementById('prodCatChart');
    if (!ctx) {
        console.error('Canvas #prodCatChart introuvable');
        return;
    }
    new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(199, 199, 199, 0.6)',
                    'rgba(83, 102, 255, 0.6)'
                ].slice(0, labels.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: context => {
                            const lbl = context.label;
                            const val = context.parsed;
                            const total = values.reduce((a, b) => a + b, 0);
                            const pct = total ? (val / total * 100).toFixed(2) : 0;
                            return `${lbl}: ${pct}% (${val})`;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Produits commandés par catégorie',
                    padding: { bottom: 10 }
                }
            }
        }
    });
});
</script>

</body>
</html>
