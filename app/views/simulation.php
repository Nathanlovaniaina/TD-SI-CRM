<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques commerciales</title>
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
        <h1>Par mois/année</h1>

        <!-- Section Filtres -->
        
        <div class="card">
            <form id="filterForm" class="filters">
                <!-- Groupe Tiers -->
                <div class="filter-group">
                    <h4><label for="tierType">Tiers</label></h4>
                    <div class="form-fields">
                        <select id="tierType" name="tierType" class="dolibarr-select">
                            <option value="">Type du tiers</option>
                            <option value="client">Client</option>
                            <option value="fournisseur">Fournisseur</option>
                            <option value="partenaire">Partenaire</option>
                        </select>

                        <input type="text" 
                               id="clientCategory" 
                               name="clientCategory" 
                               class="dolibarr-input"
                               placeholder="Tag/catégorie client">
                    </div>
                </div>

                <!-- Groupe Créé par -->
                <div class="filter-group">
                    <h4><label for="state">Créé par</label></h4>
                    <div class="form-fields">
                        <select id="state" name="state" class="dolibarr-select">
                            <option value="">État</option>
                            <option value="draft">Brouillon</option>
                            <option value="validated">Validé</option>
                            <option value="canceled">Annulé</option>
                        </select>
                    </div>
                </div>

                <!-- Groupe Année -->
                <div class="filter-group">
                    <h4><label for="year">Année</label></h4>
                    <div class="form-fields">
                        <input type="number" 
                               id="year" 
                               name="year" 
                               min="2000" 
                               max="2030" 
                               step="1" 
                               value="2025"
                               class="dolibarr-input">
                    </div>
                </div>

                <!-- Bouton de validation -->
                <div class="form-actions">
                    <button type="submit" class="dolibarr-button">
                        Appliquer les filtres
                    </button>
                </div>
            </form>
        </div>

        <!-- Section Nombre par mois -->
        <div class="card">
            <h3>Nombre par mois</h3>
            <div class="month-grid">
                <div class="month-item">Jan.</div>
                <div class="month-item">Fév.</div>
                <div class="month-item">Mars</div>
                <div class="month-item">Avr.</div>
                <div class="month-item">Mai</div>
                <div class="month-item">Juin</div>
                <div class="month-item">Juil.</div>
                <div class="month-item">Août</div>
                <div class="month-item">Sep.</div>
                <div class="month-item">Oct.</div>
                <div class="month-item">Nov.</div>
                <div class="month-item">Déc.</div>
            </div>
        </div>

        <!-- Section RAFRAICHE -->
        <div class="card">
            <h3>RAFRAICHE</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-label">Année</div>
                    <div class="stat-value">2025</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Nombre de propositions</div>
                    <div class="stat-value">120</div>
                    <div>+15%</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Montant total</div>
                    <div class="stat-value">€245k</div>
                    <div>+12%</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Montant moyen</div>
                    <div class="stat-value">€2,042</div>
                    <div>-3%</div>
                </div>
            </div>
        </div>

        <!-- Section Montant par mois -->
        <div class="card">
            <h3>Montant par mois (HT)</h3>
            <div class="month-grid">
                <div class="month-item">Jan.</div>
                <div class="month-item">Fév.</div>
                <div class="month-item">Mars</div>
                <div class="month-item">Avr.</div>
                <div class="month-item">Mai</div>
                <div class="month-item">Juin</div>
                <div class="month-item">Juil.</div>
                <div class="month-item">Août</div>
                <div class="month-item">Sep.</div>
                <div class="month-item">Oct.</div>
                <div class="month-item">Nov.</div>
                <div class="month-item">Déc.</div>
            </div>
        </div>
    </div>

</body>
</html>