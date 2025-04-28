<!DOCTYPE html>
<html>
<head>
    <title>Détail du Client</title>
</head>
<body>
    <h1>Réactions du Client #<?= htmlspecialchars($client_id) ?></h1>

    <table border="1">
        <tr>
            <th>Source</th>
            <th>Type de Réaction</th>
            <th>Date</th>
            <th>Contenu</th>
        </tr>
        <?php foreach ($reactions as $reaction): ?>
        <tr>
            <td><?= htmlspecialchars($reaction['source']) ?></td>
            <td><?= htmlspecialchars($reaction['TypeReaction']) ?></td>
            <td><?= htmlspecialchars($reaction['DateReaction']) ?></td>
            <td><?= htmlspecialchars($reaction['Contenu']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="/clients">Retour à la liste</a></p>
</body>
</html>
