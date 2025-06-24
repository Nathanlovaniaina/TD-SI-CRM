<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($action) ? 'Modifier' : 'Ajouter' ?> Action Commerciale - CRM</title>
    <style>
        :root {
            --primary-color: #1b3e6f;
            --secondary-color: #2e5392;
            --accent-color: #4169e1;
            --text-light: #ffffff;
            --background: #f5f5f5;
            --shadow: 0 2px 8px rgba(0,0,0,0.1);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
            background-color: var(--background);
            line-height: 1.6;
            color: #333;
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
            transition: var(--transition);
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
            transition: var(--transition);
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

        /* Styles spécifiques au formulaire */
        .form-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background-color: #f9f9f9;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            outline: none;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(65, 105, 225, 0.2);
        }

        .rate-input {
            width: 100px;
            text-align: right;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1.5rem 0;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--accent-color);
        }

        .checkbox-group label {
            margin: 0;
            font-weight: 500;
            color: var(--primary-color);
            cursor: pointer;
        }

        .submit-btn {
            background-color: var(--accent-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .submit-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .full-width {
            grid-column: span 2;
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
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .full-width {
                grid-column: span 1;
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
        <h1><?= isset($action) ? 'Modifier' : 'Ajouter' ?> une Action Commerciale</h1>
        
        <div class="form-container">
            <form method="post" action="<?= isset($action) ? '/action-commerciale/update' : '/action-commerciale/add' ?>">
                <?php if (isset($action)): ?>
                <input type="hidden" name="ActionCommercialeID" value="<?= $action['ActionCommercialeID'] ?>">
                <?php endif; ?>
                
                <div class="form-grid">

                    <div class="form-group">
                        <label for="Campagne">Campagne</label>
                        <input type="text" id="Campagne" name="Campagne" class="form-control" required
                               value="<?= htmlspecialchars($action['Campagne'] ?? '') ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="Objectif">Objectif</label>
                        <input type="text" id="Objectif" name="Objectif" class="form-control" required
                               value="<?= htmlspecialchars($action['Objectif'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="Cout">Coût (€)</label>
                        <input type="number" step="0.01" id="Cout" name="Cout" class="form-control" required
                               value="<?= $action['Cout'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="ClientRate">Taux Client (%)</label>
                        <input type="number" step="0.01" id="ClientRate" name="ClientRate" 
                               class="form-control rate-input" required
                               value="<?= $action['ClientRate'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="CommandeRate">Taux Commande (%)</label>
                        <input type="number" step="0.01" id="CommandeRate" name="CommandeRate" 
                               class="form-control rate-input" required
                               value="<?= $action['CommandeRate'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="PrixRate">Taux Prix (%)</label>
                        <input type="number" step="0.01" id="PrixRate" name="PrixRate" 
                               class="form-control rate-input" required
                               value="<?= $action['PrixRate'] ?? '' ?>">
                    </div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="EstConvertie" name="EstConvertie" 
                           <?= isset($action['EstConvertie']) && $action['EstConvertie'] ? 'checked' : '' ?>>
                    <label for="EstConvertie">Action convertie</label>
                </div>

                <button type="submit" class="submit-btn">
                    💾 <?= isset($action) ? 'Mettre à jour' : 'Ajouter' ?>
                </button>
            </form>
        </div>
    </div>
</body>
</html>