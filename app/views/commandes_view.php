<!DOCTYPE html>
<html>
<head>
    <title>Commandes</title>
</head>
<body>
    <h1>Liste des Commandes</h1>

    <form action="/commande/add" method="POST">
        <h3>Ajouter une Commande :</h3>
        Client ID: <input type="text" name="ClientID" required><br>
        Montant Total: <input type="text" name="MontantTotal" required><br>
        Statut: <input type="text" name="Statut" required><br>
        <button type="submit">Ajouter</button>
    </form>

    <h3>Commandes existantes :</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Date</th>
            <th>Montant</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        <?php foreach ($commandes as $commande): ?>
        <tr>
            <td><?= htmlspecialchars($commande['CommandeID']) ?></td>
            <td><?= htmlspecialchars($commande['ClientID']) ?></td>
            <td><?= htmlspecialchars($commande['DateCommande']) ?></td>
            <td><?= htmlspecialchars($commande['MontantTotal']) ?></td>
            <td><?= htmlspecialchars($commande['Statut']) ?></td>
            <td>
                <form action="/commande/delete" method="POST" style="display:inline;">
                    <input type="hidden" name="CommandeID" value="<?= $commande['CommandeID'] ?>">
                    <button type="submit">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
