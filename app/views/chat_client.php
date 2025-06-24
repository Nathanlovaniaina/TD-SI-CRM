<?php
$id_client = $_SESSION['client_id'] ?? null;
$id_affectation = 1; // À adapter dynamiquement
$id_ticket = 1;      // À adapter aussi
$base_url = Flight::get('flight.base_url'); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chat Client</title>
    <link rel="stylesheet" href="<?$base_url?>/style.css">
    <script>
        function chargerMessages(id_affectation) {
    fetch(`/api/chat/${id_affectation}`)
        .then(res => res.json())
        .then(data => {
            const box = document.getElementById("chat-box");
            box.innerHTML = ""; // on vide le chat avant de re-remplir

            data.forEach(msg => {
                const div = document.createElement("div");

                // Vérifie si le message vient du client
                if (msg.id_client !== null && msg.id_agent === null) {
                    div.classList.add('message', 'client');
                    div.textContent = `[CLIENT ${msg.date_envoi}] ${msg.contenu}`;
                } 
                // Sinon c’est un message d’agent
                else if (msg.id_agent !== null) {
                    div.classList.add('message', 'agent');
                    div.textContent = `[AGENT ${msg.date_envoi}] ${msg.contenu}`;
                }

                box.appendChild(div);
            });

            // scroll automatique en bas
            box.scrollTop = box.scrollHeight;
        })
        .catch(error => console.error("Erreur lors du chargement des messages :", error));
}

function envoyerMessage(id_ticket, id_affectation) {
    const contenu = document.getElementById("msg").value;
    if (!contenu.trim()) return;

    fetch("/api/chat/send", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_ticket, id_affectation, contenu })
    }).then(() => {
        document.getElementById("msg").value = "";
        chargerMessages(id_affectation);
    });
    console.log({ id_ticket, id_affectation, contenu });
}
    </script>
    <style>
        .message {
    padding: 5px 10px;
    margin: 5px;
    border-radius: 8px;
    width: fit-content;
    max-width: 70%;
}

.client {
    background-color: #d4edda;
    align-self: flex-start;
}

.agent {
    background-color: #d1ecf1;
    align-self: flex-end;
    margin-left: auto;
}

    </style>
</head>
<body>
    <div id="chat-box" class="chat-box"></div>
    <input type="text" id="msg" placeholder="Votre message...">
    <button onclick="envoyerMessage(<?= $id_ticket ?>, <?= $id_affectation ?>)">Envoyer</button>
    <script>
        const id_affectation = <?= $id_affectation ?>;
        chargerMessages(id_affectation);
        setInterval(() => chargerMessages(id_affectation), 3000);
    </script>
    <button>Probleme resolu</button>
    <a href="/evaluation/form">Evaluer</a>
</body>
</html>
