<?php
$id_agent = $_SESSION['agent_id'] ?? null;
$base_url = Flight::get('flight.base_url');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Agent</title>
    <style>
        :root {
            --primary-color: #1b3e6f;
            --secondary-color: #2e5392;
            --accent-color: #4169e1;
            --text-light: #ffffff;
            --background: #f5f5f5;
            --shadow: 0 2px 8px rgba(0,0,0,0.1);
            --success-color: #28a745;
            --info-color: #17a2b8;
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

        .main-content {
            margin-left: 240px;
            padding: 2rem;
            margin-top: 60px;
            max-width: 1200px;
        }

        /* Styles spécifiques au chat */
        .chat-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 180px);
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow);
            padding: 1.5rem;
        }

        .chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #eee;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
        }

        .message {
            padding: 0.75rem 1rem;
            margin: 0.5rem 0;
            border-radius: 8px;
            width: fit-content;
            max-width: 70%;
            box-shadow: var(--shadow);
            font-size: 0.9rem;
        }

        .client {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            align-self: flex-start;
        }

        .agent {
            background-color: var(--accent-color);
            color: var(--text-light);
            align-self: flex-end;
        }

        .chat-controls {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        #msg {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        button {
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: var(--secondary-color);
        }

        .btn-success {
            background-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-info {
            background-color: var(--info-color);
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            justify-content: flex-end;
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
                margin-top: 60px;
            }

            header h2 {
                font-size: 1.2rem;
            }

            .message {
                max-width: 85%;
            }
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            color: var(--text-light);
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
            gap: 0.75rem;
            margin: 0.25rem 0;
            border-radius: 4px;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: var(--accent-color);
            padding-left: 2rem;
        }

        .sidebar-nav a::before {
            content: "";
            display: inline-block;
            width: 20px;
            height: 20px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            filter: invert(1);
        }

        .sidebar-nav a[href="requeteClient"]::before {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/><path d="M7 12h2v5H7zm4-7h2v12h-2zm4 5h2v7h-2z"/></svg>');
        }

        .sidebar-nav a[href="/logout"]::before {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><path d="M0 0h24v24H0z" fill="none"/><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>');
        }

        .sidebar-nav a.active {
            background-color: var(--accent-color);
            font-weight: 600;
        }

        .sidebar-nav a:hover {
            transform: translateX(5px);
        }
    </style>
</head>
<body>
    <header>
        <h2>CRM - Support Agent</h2>
    </header>

    <div class="sidebar">
        <?= $navbar ?>
    </div>

    <div class="main-content">
        <h1>Conversation avec le client</h1>
        
        <div class="chat-container">
            <div id="chat-box" class="chat-box"></div>
            
            <div class="chat-controls">
                <input type="text" id="msg" placeholder="Tapez votre réponse...">
                <button onclick="envoyerMessage(<?= $id_ticket ?>, <?= $id_affectation ?>)">Envoyer</button>
            </div>
            
            <!-- <div class="action-buttons">
                <a href="/requete/cloturer/<?= $id_requete ?>" class="btn-success-link">
                    <button class="btn-success">Clôturer le ticket</button>
                </a>
            </div> -->
        </div>
    </div>

    <script>
        function chargerMessages(id_affectation) {
            fetch(`/api/chat/${id_affectation}`)
                .then(res => res.json())
                .then(data => {
                    const box = document.getElementById("chat-box");
                    box.innerHTML = "";

                    data.forEach(msg => {
                        const div = document.createElement("div");

                        if (msg.id_client !== null && msg.id_agent === null) {
                            div.classList.add('message', 'client');
                            div.textContent = `Client (${msg.date_envoi}): ${msg.contenu}`;
                        } 
                        else if (msg.id_agent !== null) {
                            div.classList.add('message', 'agent');
                            div.textContent = `Vous (${msg.date_envoi}): ${msg.contenu}`;
                        }

                        box.appendChild(div);
                    });

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
        }

        const id_affectation = <?= $id_affectation ?>;
        chargerMessages(id_affectation);
        setInterval(() => chargerMessages(id_affectation), 3000);
    </script>
</body>
</html>