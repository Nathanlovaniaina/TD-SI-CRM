<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation commerciales</title>
    <style>
        /* Conserver les styles de base de Dolibarr */
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
        }

        .main-content {
            margin-left: 240px;
            padding: 2rem;
            margin-top: 60px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .filter-group h4 {
            color: var(--primary-color);
            margin: 0 0 0.5rem 0;
        }

        .month-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .month-item {
            padding: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin: 1rem 0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: bold;
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 2rem;
        }

        h3 {
            color: var(--secondary-color);
            margin-top: 0;
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
    </style>

<style>
    /* Styles complémentaires */
    .form-fields {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .dolibarr-select, .dolibarr-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
        font-family: inherit;
    }

    .dolibarr-button {
        background-color: var(--accent-color);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .dolibarr-button:hover {
        background-color: #304f8a;
    }

    .form-actions {
        margin-top: 1.5rem;
        text-align: right;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filters {
            grid-template-columns: 1fr;
        }
    }
    .cards-container {
    display: flex;
    gap: 20px; /* espace entre les cartes */
    flex-wrap: wrap;
}

.card {
    flex: 1;
    min-width: 300px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    background: #fff;
}

canvas {
    max-width: 100%;
    height: 300px;
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

    <!-- Conserver la structure header/sidebar existante -->

    <div class="main-content">
        <h1>Simulation d'action</h1>

        <!-- Section Filtres -->
        
        <div class="card">
            <form action="simulation" method="post" id="filterForm" class="filters">
                <!-- Groupe Tiers -->
                <div class="filter-group">
                    <h4><label for="tierType">Action</label></h4>
                    <div class="form-fields">
                        <select id="tierType" name="idAction" class="dolibarr-select">
                            <option value="">Selectionner une action</option>
                            <?php foreach($actions as $a): ?>
                                <option value="<?php echo $a['ActionCommercialeID']?>"><?php echo $a['Campagne'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <!-- Groupe Créé par -->
                <div class="filter-group">
                    <h4><label for="state">Date debut</label></h4>
                    <div class="form-fields">
                        <input type="date" name="date_debut" class="dolibarr-input" id="">
                    </div>
                </div>

                <!-- Groupe Année -->
                <div class="filter-group">
                    <h4><label for="year">Date fin</label></h4>
                    <div class="form-fields">
                        <input type="date" name="date_fin" class="dolibarr-input" id="">
                    </div>
                </div>

                <!-- Bouton de validation -->
                <div class="form-actions">
                    <button type="submit" class="dolibarr-button">
                        Valider
                    </button>
                </div>

                <div class="filter-group">
                    <h4><label for="tierType">Etat</label></h4>
                    <div class="form-fields">
                        <select id="tierType" name="tierType" class="dolibarr-select">
                            <option value="">Selectionner un etat</option>
                            <option value="Simulation">Simuler</option>
                            <option value="Valider">Valider</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Section Nombre par mois -->
        <div class="cards-container">
            <div class="card">
                <h3>Nombre de commande par mois</h3>
                <div class="month-grid">
                    <?php if(isset($stat)) { ?>
                        <?php foreach($stat['commandes'] as $s): ?>
                            <div class="month-item"><strong><?= $s['date']?>:</strong> <?= number_format($s['value'],2) ?></div>
                        <?php endforeach ?>
                    <?php }?>
                </div>
            </div>
                        
            <div class="card">
                <h3>Nombre de commande par mois</h3>
                <canvas id="commandesChart"></canvas>
            </div>
        </div>



        <!-- Section RAFRAICHE -->
        <div class="card">
            <h3>INFORMATION</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-label">Objectif</div>
                    <div class="stat-value" style="font-size:15px;"><?php if(isset($action)) echo $action['Objectif'] ?></div>
                </div>

                <div class="stat-item">
                    <div class="stat-label">Durer</div>
                    <div class="stat-value" style="font-size:15px;"><?php if(isset($durer)) echo $durer?> mois</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Cout total</div>
                    <div class="stat-value"><?php if(isset($action)) echo $action['Cout'] * $durer ?> AR</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Cout/mois</div>
                    <div class="stat-value"><?php if(isset($action)) echo $action['Cout'] ?> AR</div>
                </div>
            </div>
        </div>

        <!-- Section Montant par mois -->
        <div class="cards-container">
            <div class="card">
                <h3>Montant par mois</h3>
                <div class="month-grid">
                    <?php if(isset($stat)) { ?>
                        <?php foreach($stat['montant_par_moi'] as $s): ?>
                            <div class="month-item"><strong><?= $s['date']?>:</strong> <?= number_format($s['value'],2) ?> AR</div>
                        <?php endforeach ?>
                    <?php }?>
                </div>
            </div>
                        
            <div class="card">
                <h3>Montant par mois</h3>
                <canvas id="montantChart"></canvas>
            </div>
        </div>


        <div class="cards-container">
            <div class="card">
                <h3>Nombre de client par mois</h3>
                <div class="month-grid">
                    <?php if(isset($stat)) { ?>
                        <?php foreach($stat['clients'] as $s): ?>
                            <div class="month-item"><strong><?= $s['date']?>:</strong> <?= number_format($s['value'],2) ?> </div>
                        <?php endforeach ?>
                    <?php }?>
                </div>
            </div>
                        
            <div class="card">
                <h3>Nombre de client par mois</h3>
                <canvas id="clientsChart"></canvas>
            </div>
        </div>


        <div class="card">
            <h3>BILAN</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-label">Nombre de client obtenue</div>
                    <div class="stat-value" style="font-size:15px;"><?php if(isset($stat)) echo $stat['client_obtenue'] ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Commande</div>
                    <div class="stat-value"><?php if(isset($stat)) echo $stat['commande_obtenue'] ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Montant total</div>
                    <div class="stat-value"><?php if(isset($action)) echo $action['Cout'] ?> AR</div>
                    <div>-3%</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        <?php if (isset($stat['commandes'])): ?>
            const labels = <?= json_encode(array_column($stat['commandes'], 'date')) ?>;
            const dataValues = <?= json_encode(array_column($stat['commandes'], 'value')) ?>;
        <?php else: ?>
            const labels = [];
            const dataValues = [];
        <?php endif; ?>
    </script>

<script>
    const ctx = document.getElementById('commandesChart').getContext('2d');
    const commandesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Commandes',
                data: dataValues,
                backgroundColor: 'rgba(67, 59, 206, 0.7)', // violet clair
                borderColor: 'rgba(142, 68, 173, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', // position de la légende
                    labels: {
                        boxWidth: 20,
                        padding: 15
                    }
                },
                title: {
                    display: true,
                    text: 'Nombre par mois',
                    font: {
                        size: 18
                    },
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 0,
                        minRotation: 0
                    }
                }
            }
        }
    });
</script>

<script>
    <?php if (isset($stat['montant_par_moi'])): ?>
        const montantLabels = <?= json_encode(array_column($stat['montant_par_moi'], 'date')) ?>;
        const montantValues = <?= json_encode(array_column($stat['montant_par_moi'], 'value')) ?>;
    <?php else: ?>
        const montantLabels = [];
        const montantValues = [];
    <?php endif; ?>
</script>

<script>
    const ctxMontant = document.getElementById('montantChart').getContext('2d');
    const montantChart = new Chart(ctxMontant, {
        type: 'bar',
        data: {
            labels: montantLabels,
            datasets: [{
                label: 'Montant (AR)',
                data: montantValues,
                backgroundColor: 'rgba(52, 152, 219, 0.7)',
                borderColor: 'rgba(41, 128, 185, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: {
                    display: true,
                    text: 'Montant par mois',
                    font: { size: 18 },
                    padding: { top: 10, bottom: 30 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    ticks: {
                        maxRotation: 0,
                        minRotation: 0
                    }
                }
            }
        }
    });
</script>

<script>
    <?php if (isset($stat['clients'])): ?>
        const clientsLabels = <?= json_encode(array_column($stat['clients'], 'date')) ?>;
        const clientsValues = <?= json_encode(array_column($stat['clients'], 'value')) ?>;
    <?php else: ?>
        const clientsLabels = [];
        const clientsValues = [];
    <?php endif; ?>
</script>

<script>
    const ctxClients = document.getElementById('clientsChart').getContext('2d');
    const clientsChart = new Chart(ctxClients, {
        type: 'bar',
        data: {
            labels: clientsLabels,
            datasets: [{
                label: 'Clients',
                data: clientsValues,
                backgroundColor: 'rgba(241, 196, 15, 0.7)',
                borderColor: 'rgba(243, 156, 18, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: {
                    display: true,
                    text: 'Nombre de client par mois',
                    font: { size: 18 },
                    padding: { top: 10, bottom: 30 }
                }
            },
            scales: {
                y: { beginAtZero: true },
                x: {
                    ticks: {
                        maxRotation: 0,
                        minRotation: 0
                    }
                }
            }
        }
    });
</script>


</body>
</html>