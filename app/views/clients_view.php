<!DOCTYPE html>
<html>
<head>
    <title>Clients</title>
</head>
<body>
    <h1>Liste des Clients</h1>
    <ul>
        <?php foreach ($clients as $client): ?>
            <li>
                <?= htmlspecialchars($client['Nom']) ?>
                - <a href="/client/<?= $client['ClientID'] ?>">Voir Détail</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
