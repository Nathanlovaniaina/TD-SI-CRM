<h2>Liste des produits</h2>
<a href="/produits/ajouter">Ajouter un produit</a>
<table border="1">
    <tr>
        <th>ID</th><th>Désignation</th><th>Prix</th><th>Stock</th><th>Seuil</th><th>Code-barre</th><th>Discontinu</th><th>Action</th>
    </tr>
    <?php foreach ($produits as $produit): ?>
    <tr>
        <td><?= $produit['ProduitID'] ?></td>
        <td><?= $produit['Designation'] ?></td>
        <td><?= $produit['Prix'] ?></td>
        <td><?= $produit['QuantiteEnStock'] ?></td>
        <td><?= $produit['SeuilAlerte'] ?></td>
        <td><?= $produit['CodeBarre'] ?></td>
        <td><?= $produit['EstDiscontinu'] ? 'Oui' : 'Non' ?></td>
        <td><a href="/produits/supprimer/<?= $produit['ProduitID'] ?>">Supprimer</a></td>
    </tr>
    <?php endforeach; ?>
</table>
