<?php 
    $base_url = Flight::get('flight.base_url'); 
    $userRole = $_SESSION["role"] ?? 'client';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluation du Ticket</title>
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
        <h2>Évaluation du Ticket</h2>

        <form method="POST" action="/evaluation/submit">
            <input type="hidden" name="id_ticket" value="<?= $id_ticket ?>">
            <input type="hidden" name="id_affectation" value="<?= $id_affectation ?>">

            <label for="note">Note (1 à 5) :</label>
            <select name="note" id="note" required>
                <option value="">--Choisir--</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>

            <label for="commentaire">Commentaire :</label>
            <textarea name="commentaire" id="commentaire" rows="4"></textarea>

            <button type="submit">Envoyer l'évaluation</button>
        </form>

        <h3>Historique des évaluations</h3>
        <?php if (!empty($evaluations)): ?>
            <table>
                <tr>
                    <th>ID Ticket</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                </tr>
                <?php foreach ($evaluations as $eval): ?>
                <tr>
                    <td><?= $eval['id_ticket'] ?></td>
                    <td><?= $eval['note'] ?></td>
                    <td><?= htmlspecialchars($eval['commentaire']) ?></td>
                    <td><?= $eval['date_evaluation'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Aucune évaluation enregistrée pour ce ticket.</p>
        <?php endif; ?>
    </div>
</body>
</html>