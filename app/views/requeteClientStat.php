<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Tickets - CRM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="<?=$base_url?>/assets/css/statrequet.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->

    <div class="sidebar">
        <header>
            <h2>CRM</h2>
        </header>
        <nav class="sidebar-nav">
            <?= $navbar ?>
        </nav>
    </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1><i class="fas fa-chart-bar"></i> Statistiques des Tickets</h1>
                <div class="header-actions">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="date-filter-container">
                <form method="get" action="<?php echo $base_url; ?>/requeteClient_stats" id="dateFilterForm">
                    <div class="date-filter-group">
                        <label for="date_debut"><i class="far fa-calendar"></i> De :</label>
                        <input type="date" id="date_debut" name="date_debut" class="date-filter-input" 
                               value="<?php echo htmlspecialchars($dateDebut); ?>">
                    </div>
                    
                    <div class="date-filter-group">
                        <label for="date_fin">À :</label>
                        <input type="date" id="date_fin" name="date_fin" class="date-filter-input" 
                               value="<?php echo htmlspecialchars($dateFin); ?>">
                    </div>
                    
                    <button type="submit" class="date-filter-btn">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    
                    <span class="date-range-display">
                        Période : <?php echo date('d/m/Y', strtotime($dateDebut)); ?> - <?php echo date('d/m/Y', strtotime($dateFin)); ?>
                    </span>
                </form>
            </div>

            <!-- Stats Summary -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(255, 107, 107, 0.1); color: var(--danger);">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo array_sum(array_column($statsByCategory, 'NombreTickets')); ?></div>
                        <div class="stat-label">Tickets ouverts</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(6, 214, 160, 0.1); color: var(--success);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $globalResolution; ?>%</div>
                        <div class="stat-label">Taux de résolution</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(255, 209, 102, 0.1); color: var(--warning);">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">
                            <?php echo count($satisfaction) > 0 ? round(array_sum(array_column($satisfaction, 'NoteMoyenne')) / count($satisfaction), 1) : 'N/A'; ?>
                        </div>
                        <div class="stat-label">Satisfaction client</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(75, 192, 192, 0.1); color: var(--info);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">
                            <?php echo count($statsByCategory) > 0 ? round(array_sum(array_column($statsByCategory, 'DureeMoyenneResolution')) / count($statsByCategory), 1) : 'N/A'; ?>h
                        </div>
                        <div class="stat-label">Temps moyen</div>
                    </div>
                </div>
            </div>

            <!-- Cards Grid -->
            <div class="card-grid">
                <!-- Vue d'ensemble -->
                <div class="card">
                    <h3><div class="card-icon"><i class="fas fa-list"></i></div> Vue d'ensemble</h3>
                    <ul class="data-list">
                        <?php foreach ($statsByCategory as $category): ?>
                        <li>
                            <span><?php echo htmlspecialchars($category['Categorie']); ?></span>
                            <strong><?php echo $category['NombreTickets']; ?> tickets</strong>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Répartition par catégorie -->
                <div class="card">
                    <h3><div class="card-icon"><i class="fas fa-chart-pie"></i></div> Répartition par catégorie</h3>
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <!-- Satisfaction client -->
                <div class="card">
                    <h3><div class="card-icon"><i class="fas fa-smile"></i></div> Satisfaction client</h3>
                    <div class="chart-container">
                        <canvas id="satisfactionChart"></canvas>
                    </div>
                </div>

                <!-- Taux de résolution -->
                <div class="card">
                    <h3><div class="card-icon"><i class="fas fa-percent"></i></div> Taux de résolution</h3>
                    <ul class="data-list">
                        <?php foreach ($resolutionRate as $rate): ?>
                        <li>
                            <span><?php echo htmlspecialchars($rate['Categorie']); ?></span>
                            <div>
                                <strong><?php echo $rate['TauxResolution']; ?>%</strong>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: <?php echo $rate['TauxResolution']; ?>%; 
                                    background: <?php echo $rate['TauxResolution'] > 80 ? 'var(--success)' : ($rate['TauxResolution'] > 60 ? 'var(--warning)' : 'var(--danger)'); ?>;"></div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Tickets par priorité -->
                <div class="card">
                    <h3><div class="card-icon"><i class="fas fa-exclamation-triangle"></i></div> Tickets par priorité</h3>
                    <div class="chart-container">
                        <canvas id="priorityChart"></canvas>
                    </div>
                </div>

                <!-- Temps de résolution -->
                <div class="card">
                    <h3><div class="card-icon"><i class="fas fa-clock"></i></div> Temps de résolution</h3>
                    <ul class="data-list">
                        <?php foreach ($statsByCategory as $category): ?>
                        <li>
                            <span><?php echo htmlspecialchars($category['Categorie']); ?></span>
                            <strong><?php echo round($category['DureeMoyenneResolution'], 1); ?>h</strong>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Taux de résolution détaillé -->
                <div class="card">
                    <h3><div class="card-icon"><i class="fas fa-check-double"></i></div> Taux de Résolution Détaillé</h3>
                    <div class="stat-value" style="text-align: center; font-size: 1.8rem; margin: 1rem 0;">
                        Global : <?php echo $globalResolution; ?>%
                    </div>
                    <ul class="data-list">
                        <?php foreach ($resolutionDetails as $detail): ?>
                        <li>
                            <span><?php echo htmlspecialchars($detail['Categorie']); ?> (<?php echo htmlspecialchars($detail['Priorite']); ?>)</span>
                            <div>
                                <strong><?php echo $detail['TauxResolution']; ?>%</strong>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: <?php echo $detail['TauxResolution']; ?>%; 
                                        background: <?php 
                                            echo $detail['TauxResolution'] > 80 ? 'var(--success)' : 
                                                 ($detail['TauxResolution'] > 50 ? 'var(--warning)' : 'var(--danger)'); 
                                        ?>;">
                                    </div>
                                </div>
                                <small><?php echo $detail['TotalRequetes']; ?> requêtes</small>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="footer">
                <p>CRM Analytics © <?php echo date('Y'); ?> - Tous droits réservés</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    <script>
        // Toggle mobile menu
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Initialize date picker
        flatpickr("#date_debut, #date_fin", {
            dateFormat: "Y-m-d",
            locale: "fr",
            maxDate: "today"
        });

        // Date filter validation
        document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
            const dateDebut = new Date(document.getElementById('date_debut').value);
            const dateFin = new Date(document.getElementById('date_fin').value);
            
            if (dateDebut > dateFin) {
                alert('La date de début doit être antérieure à la date de fin');
                e.preventDefault();
            }
        });

        // Initialize charts with PHP data
        document.addEventListener('DOMContentLoaded', function() {
            // Category Chart (Doughnut)
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($chartData['categories']); ?>,
                    datasets: [{
                        data: <?php echo json_encode($chartData['ticketsCount']); ?>,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

            // Satisfaction Chart (Bar)
            const satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');
            new Chart(satisfactionCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($chartData['categories']); ?>,
                    datasets: [{
                        label: 'Note moyenne /5',
                        data: <?php echo json_encode($chartData['satisfactionScores']); ?>,
                        backgroundColor: '#4169E1',
                        borderRadius: 6,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Priority Chart (Stacked Bar)
            const priorityCtx = document.getElementById('priorityChart').getContext('2d');
            
            // Prepare priority data
            const categories = <?php echo json_encode(array_unique(array_column($repartitionByPriority, 'Categorie'))); ?>;
            const priorities = ['haute', 'moyenne', 'basse'];
            
            // Create datasets for each priority
            const priorityDatasets = priorities.map(priority => {
                return {
                    label: priority,
                    data: categories.map(cat => {
                        const found = <?php echo json_encode($repartitionByPriority); ?>.find(item => 
                            item.Categorie === cat && item.priorite === priority);
                        return found ? found.NombreTickets : 0;
                    }),
                    backgroundColor: 
                        priority === 'haute' ? '#FF6B6B' : 
                        priority === 'moyenne' ? '#FFD166' : '#06D6A0',
                    borderRadius: 6,
                    borderWidth: 0
                };
            });

            new Chart(priorityCtx, {
                type: 'bar',
                data: {
                    labels: categories,
                    datasets: priorityDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            stacked: true,
                            grid: {
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>