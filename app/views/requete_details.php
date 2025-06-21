<?php $base_url = Flight::get('flight.base_url'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Requête #<?= htmlspecialchars($requete['id_requete']) ?> | CRM</title>
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/requeteDetails.css">
    <style>
        :root {
            --primary-color: #1b3e6f;
            --secondary-color: #2e5392;
            --accent-color: #4169e1;
            --text-light: #ffffff;
            --background: #f5f5f5;
            --card-bg: #ffffff;
            --border-color: #e0e0e0;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: var(--background);
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .container {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: calc(100vh - 60px);
        }

        .sidebar {
            background-color: var(--secondary-color);
            padding: 1rem 0;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav a {
            display: block;
            color: var(--text-light);
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar-nav a:hover {
            background-color: var(--accent-color);
            padding-left: 2rem;
        }

        .sidebar-nav a.active {
            background-color: var(--accent-color);
            font-weight: 500;
        }

        .main-content {
            padding: 2rem;
        }

        .section {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        h1, h2, h3 {
            color: var(--primary-color);
            margin-top: 0;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-ouvert { background-color: #fff3cd; color: #856404; }
        .status-assigne { background-color: #cce5ff; color: #004085; }
        .status-en_cours { background-color: #e2e3e5; color: #383d41; }
        .status-resolu { background-color: #d4edda; color: #155724; }
        .status-ferme { background-color: #f8d7da; color: #721c24; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: var(--primary-color);
            color: white;
        }

        .chat-container {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 1rem;
        }

        .message {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px dashed var(--border-color);
        }

        .message:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .assign-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        select, button {
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid var(--border-color);
        }

        button {
            background-color: var(--accent-color);
            color: white;
            border: none;
            cursor: pointer;
            padding: 0.5rem 1rem;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 1rem;
            color: var(--accent-color);
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
    <h2>CRM - Détails de la Requête</h2>
</header>

<div class="container">
    <div class="sidebar">
        <nav class="sidebar-nav">
            <a href="<?= $base_url ?>/dashboard">Accueil</a>
            <a href="<?= $base_url ?>/clients">Clients</a>
            <a href="<?= $base_url ?>/produits">Produits</a>
            <a href="<?= $base_url ?>/commandes">Commandes</a>
            <a href="<?= $base_url ?>/stat">Statistique</a>
            <a href="<?= $base_url ?>/simulation">Simulation</a>
            <a href="<?= $base_url ?>/actioncommercial">Actions Commerciales</a>
            <a href="<?= $base_url ?>/requeteClient" class="active">Requêtes Clients</a>
        </nav>
    </div>

    <div class="main-content">
        <a href="<?= $base_url ?>/requeteClient" class="back-link">← Retour à la liste</a>
        
        <div class="section">
            <h1>Requête #<?= htmlspecialchars($requete['id_requete']) ?></h1>
            
            <div class="requete-info">
                <p><strong>Client :</strong> 
                    <?= htmlspecialchars($requete['client_nom']) ?> 
                    <?= htmlspecialchars($requete['client_prenom']) ?>
                    (<?= htmlspecialchars($requete['client_email']) ?> | 
                    <?= htmlspecialchars($requete['client_telephone']) ?>)
                </p>
                
                <p><strong>Sujet :</strong> <?= htmlspecialchars($requete['Sujet']) ?></p>
                
                <p><strong>Description :</strong><br>
                <?= nl2br(htmlspecialchars($requete['Description'])) ?></p>
                
                <p><strong>Date création :</strong> 
                <?= date('d/m/Y H:i', strtotime($requete['Date_creation'])) ?></p>
                
                <p><strong>État :</strong> 
                <span class="status-badge status-<?= strtolower(str_replace(['é', 'è', 'ê'], 'e', $requete['etat_libelle'])) ?>">
                    <?= htmlspecialchars($requete['etat_libelle']) ?>
                </span></p>
                
                <?php if (!empty($requete['FichierJoint'])): ?>
                <p><strong>Fichier joint :</strong> 
                <a href="<?= $base_url ?>/uploads/<?= htmlspecialchars($requete['FichierJoint']) ?>" target="_blank">
                    Télécharger
                </a></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <h2>Assignation</h2>
            
            <h3>Agent actuel</h3>
            <?php if (!empty($agentsAssignes) && $agentsAssignes[0]['is_valide']): ?>
                <p><?= htmlspecialchars($agentsAssignes[0]['agent_nom']) ?> 
                <?= htmlspecialchars($agentsAssignes[0]['agent_prenom']) ?> 
                (depuis le <?= date('d/m/Y', strtotime($agentsAssignes[0]['date_affectation'])) ?>)</p>
            <?php else: ?>
                <p>Aucun agent actuellement assigné</p>
            <?php endif; ?>
            
            <h3>Réassigner</h3>
            <form id="assignForm" class="assign-form">
                <div class="form-group">
                    <label for="agentSelect">Nouvel agent :</label>
                    <select id="agentSelect" name="id_agent" required>
                        <option value="">Sélectionnez un agent</option>
                        <?php foreach ($agentsDisponibles as $agent): ?>
                        <option value="<?= $agent['id_agent'] ?>">
                            <?= htmlspecialchars($agent['Nom']) ?> 
                            <?= htmlspecialchars($agent['Prenom']) ?>
                            (<?= $agent['nb_requetes_actives'] ?> requêtes)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Assigner</button>
            </form>
            
            <h3>Historique des assignations</h3>
            <table>
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agentsAssignes as $agent): ?>
                    <tr>
                        <td><?= htmlspecialchars($agent['agent_nom']) ?> <?= htmlspecialchars($agent['agent_prenom']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($agent['date_affectation'])) ?></td>
                        <td><?= $agent['is_valide'] ? 'Actif' : 'Inactif' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>Conversation</h2>
            <div class="chat-container">
                <?php if (empty($messagesChat)): ?>
                    <p>Aucun message dans cette conversation</p>
                <?php else: ?>
                    <?php foreach ($messagesChat as $message): ?>
                    <div class="message">
                        <div class="message-header">
                            <strong>
                                <?= htmlspecialchars($message['expediteur_nom']) ?> 
                                <?= htmlspecialchars($message['expediteur_prenom']) ?>
                            </strong>
                            <small><?= date('d/m/Y H:i', strtotime($message['date_envoi'])) ?></small>
                        </div>
                        <p><?= nl2br(htmlspecialchars($message['contenu'])) ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <form id="messageForm" style="margin-top: 1rem;">
                <div class="form-group">
                    <textarea name="message" rows="3" style="width: 100%;" required></textarea>
                </div>
                <button type="submit">Envoyer</button>
            </form>
        </div>

        <div class="section">
            <h2>Évaluations</h2>
            <?php if (empty($evaluations)): ?>
                <p>Aucune évaluation pour cette requête</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Note</th>
                            <th>Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $eval): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($eval['date_evaluation'])) ?></td>
                            <td>
                                <?= str_repeat('★', $eval['note']) ?><?= str_repeat('☆', 5 - $eval['note']) ?>
                            </td>
                            <td><?= htmlspecialchars($eval['commentaire']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Gestion de l'assignation d'agent
document.getElementById('assignForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('id_requete', <?= $requete['id_requete'] ?>);
    
    fetch('<?= $base_url ?>/requeteClient/assigner/<?= $requete['id_requete'] ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Agent assigné avec succès');
            location.reload();
        } else {
            alert('Erreur: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue');
    });
});

// Gestion de l'envoi de message (à implémenter selon votre backend)
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Fonctionnalité d\'envoi de message à implémenter');
    // Implémentez l'envoi AJAX vers votre endpoint d'ajout de message
});
</script>
</body>
</html>