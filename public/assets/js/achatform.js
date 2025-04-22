function add_ligne() {
    var produitId = document.getElementById("produit").value;
    var quantite = document.getElementById("quantiter").value;
    var tbody = document.querySelector("#myTable tbody");

    if (produitId == "0" || quantite <= 0) {
        alert("Veuillez sélectionner un produit et entrer une quantité valide.");
        return;
    }

    var xhr = new XMLHttpRequest();
    var url = "/add_ligne?produit=" + encodeURIComponent(produitId) + "&quantite=" + encodeURIComponent(quantite);

    xhr.open("GET", url, true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

    // Indicateur de chargement temporaire
    tbody.innerHTML += "<tr id='loading-row'><td class='skeleton-row' colspan='5'>Chargement...</td></tr>";

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            setTimeout(function () {
                document.getElementById('loading-row').remove(); // Supprimer l'indicateur
                tbody.innerHTML += xhr.responseText; // Ajouter la ligne récupérée depuis le serveur
                updateTotal();
            }, 1000);
        }
    };

    xhr.send();
}

// Fonction pour supprimer une ligne
function removeRow(rowId) {
    document.getElementById(rowId).remove();
    updateTotal();
}

// Fonction pour recalculer le total
function updateTotal() {
    let total = 0;
    const rows = document.querySelectorAll("#myTable tbody tr");

    rows.forEach(row => {
        const priceCell = row.cells[2]; // Prix unitaire
        const quantityCell = row.cells[3]; // Quantité
        const amountCell = row.cells[4]; // Montant

        if (priceCell && quantityCell) {
            const price = parseFloat(priceCell.textContent.replace(/\D/g, ''));
            const quantity = parseInt(quantityCell.textContent);
            const amount = price * quantity;

            amountCell.textContent = amount.toLocaleString();
            total += amount;
        }
    });

    // Mettre à jour le total dans le footer
    document.querySelector("#myTable tfoot th:nth-child(2)").textContent = total.toLocaleString();
}
