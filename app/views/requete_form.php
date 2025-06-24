<?php 
    $base_url = Flight::get('flight.base_url'); 
    $userRole = $_SESSION["role"] ?? 'client';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Requête Client</title>
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
            max-width: 1000px;
        }

        form {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        label {
            display: block;
            margin-top: 1rem;
            font-weight: 600;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        input[type="file"] {
            margin-top: 0.5rem;
        }

        button {
            margin-top: 1.5rem;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }

        button:hover {
            background-color: var(--secondary-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: var(--shadow);
        }

        th, td {
            padding: 0.75rem;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: var(--primary-color);
            color: white;
        }

        a {
            color: var(--accent-color);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
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
        <h2><?= isset($requete) ? 'Modifier une Requête' : 'Nouvelle Requête Client' ?></h2>

        <form method="post" action="<?= isset($requete) ? '/requete-client/update/' . $requete['id_requete'] : '/requete-client' ?>" enctype="multipart/form-data">
            <label>Client :</label>
            <select name="id_client" required>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['ClientID'] ?>" <?= (isset($requete) && $requete['id_client'] == $client['ClientID']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($client['Nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Sujet :</label>
            <input type="text" name="sujet" value="<?= $requete['Sujet'] ?? '' ?>" required>

            <label>Description :</label>
            <textarea name="description" rows="4" required><?= $requete['Description'] ?? '' ?></textarea>

            <label>Fichier joint :</label>
            <input type="file" name="fichier_joint">

            <button type="submit"><?= isset($requete) ? 'Mettre à jour' : 'Enregistrer' ?></button>
        </form>

        <h3>Dernières requêtes</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Sujet</th>
                <th>Description</th>
                <th>Date</th>
                <th>Fichier</th>
                <th>Action</th>
            </tr>
            <?php foreach ($allRequetes as $r): ?>
            <tr>
                <td><?= $r['id_requete'] ?></td>
                <td><?= $r['id_client'] ?></td>
                <td><?= htmlspecialchars($r['Sujet']) ?></td>
                <td><?= htmlspecialchars($r['Description']) ?></td>
                <td><?= $r['Date_creation'] ?></td>
                <td>
                    <?php if (!empty($r['FichierJoint'])): ?>
                        <a href="/uploads/<?= htmlspecialchars($r['FichierJoint']) ?>" target="_blank">Voir fichier</a>
                    <?php else: ?>
                        Aucun
                    <?php endif; ?>
                </td>
                <td><a href="/requete-client/edit/<?= $r['id_requete'] ?>">Modifier</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>