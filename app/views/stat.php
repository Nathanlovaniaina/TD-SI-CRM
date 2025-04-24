<?php $base_url = Flight::get('flight.base_url'); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
    <title>Statistiques commerciales</title>
    <link href="<?= $base_url ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
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
            font-size: 15px;
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


/* Responsive adjustments */
@media (max-width: 768px) {
    #myTable thead th {
        padding: 0.75rem;
        font-size: 0.9rem;
    }
    
    #myTable tbody td {
        padding: 0.6rem;
        font-size: 0.9rem;
    }
    
    .table-container {
        margin: 0.5rem -1rem;
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

    <!-- Conserver la structure header/sidebar existante -->

    <div class="main-content">
        <h1>Statistiques </h1>

        <!-- Section Filtres -->
        
        <div class="card">
            <form action="stat" method="post" id="filterForm" class="filters">

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
                        Appliquer le filtre
                    </button>
                </div>
            </form>
        </div>

        <!-- Section Nombre par mois -->
        <div class="cards-container">
            <div class="card">
                <h3>Tendance par categorie</h3>
                <div class="table-container">
                    <table id="myTable" class="table table-striped table-bordered nowrap" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Categorie</th>
                                <th>Quantite</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($tendance as $t): ?>
                                <tr>
                                    <td><?= $t['Categorie'] ?></td>
                                    <td><?= $t['total_quantite'] ?></td>
                                    <td><?= $t['montant_total'] ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
                        
            <div class="card">
                <h3>Nombre de commande par mois</h3>
                <canvas id="commandesChart"></canvas>
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
  <script src="<?= $base_url ?>/assets/js/core/jquery-3.7.1.min.js"></script>
  <script src="<?= $base_url ?>/assets/js/core/popper.min.js"></script>
  <script src="<?= $base_url ?>/assets/js/core/bootstrap.min.js"></script>

  <!-- DataTables -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    


  <script>
      $(document).ready(function() {
          $('#myTable').DataTable({
              "paging": true,
              "searching": true,
              "ordering": true,
              "info": true,
              "lengthMenu": [5, 10, 25, 50, 100],
              "language": {
                  "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
              }
          });
      });
  </script>

</body>
</html>