<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit - CRM</title>
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

        /* Formulaire produit */
        .product-form {
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow);
            max-width: 600px;
            margin-top: 1rem;
            padding: 2rem;
        }

        .product-form h1 {
            margin-top: 0;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 1.25rem;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="email"] {
            padding: 0.75rem 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            background-color: white;
        }

        .form-group input:focus {
            border-color: var(--accent-color);
            outline: none;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            color: var(--primary-color);
        }

        .add-product-btn {
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
        }

        .add-product-btn:hover {
            background-color: var(--secondary-color);
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
        <h1>Ajouter un produit</h1>
        <div class="product-form">
    <form method="post" action="formProduit">
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" required>
        </div>

        <div class="form-group">
            <label for="categorie">Catégorie</label>
            <input type="text" id="categorie" name="categorie" required>
        </div>

        <div class="form-group">
            <label for="prix">Prix (€)</label>
            <input type="number" step="0.01" id="prix" name="prix" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" required>
        </div>

        <button type="submit" class="add-product-btn">💾 Ajouter</button>
    </form>
</div>

    </div>

</body>
</html>
