<h2>Ajouter un produit</h2>
<form method="post" action="formProduit">
    Désignation: <input type="text" name="designation"><br>
    Prix: <input type="number" step="0.01" name="prix"><br>
    Unité ID: <input type="number" name="unite_id"><br>
    Quantité en stock: <input type="number" name="quantite_en_stock"><br>
    Seuil alerte: <input type="number" name="seuil_alerte"><br>
    Catégorie ID: <input type="number" name="categorie_id"><br>
    Fournisseur ID: <input type="number" name="fournisseur_id"><br>
    Code barre: <input type="text" name="code_barre"><br>
    Est discontinu: <input type="checkbox" name="est_discontinu"><br>
    <button type="submit">Ajouter</button>
</form>
