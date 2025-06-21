<?php $base_url = Flight::get('flight.base_url'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Requête #<?= htmlspecialchars($requete['id_requete']) ?> | CRM</title>
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/requeteDetails.css">
    <style>
    </style>
</head>
<body>
<header>
    <h2>CRM - Gestion des Requêtes</h2>
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
                            <?= htmlspecialchars($agent['Email']) ?>
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
                        <td><?= htmlspecialchars($agent['agent_nom']) ?></td>
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