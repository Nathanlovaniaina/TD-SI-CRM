<?php $base_url = Flight::get('flight.base_url'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Requête #<?= htmlspecialchars($requete['id_requete']) ?> | CRM</title>
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/requeteDetails.css">

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
        
        <!-- Affichage des messages flash -->
        <?php if (Flight::has('success')): ?>
        <div class="notification success">
            <?= Flight::flash('success') ?>
        </div>
        <?php endif; ?>

        <?php if (Flight::has('error')): ?>
        <div class="notification error">
            <?= Flight::flash('error') ?>
        </div>
        <?php endif; ?>

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
            <h2>Gestion de l'affectation</h2>
            
            <form id="assignForm" method="POST" action="<?= $base_url ?>/requeteClient/affecter" class="assign-form">
                <input type="hidden" name="id_requete" value="<?= $requete['id_requete'] ?>">
                <?php if (isset($affectationActive['id_ticket'])): ?>
                <input type="hidden" name="id_ticket" value="<?= $affectationActive['id_ticket'] ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="ticketCategory">Catégorie :</label>
                        <select id="ticketCategory" name="id_categorie" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach ($categories as $categorie): ?>
                            <option value="<?= $categorie['id_categorie'] ?>" >
                                <?= htmlspecialchars($categorie['Nom']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="ticketPriority">Priorité :</label>
                        <select id="ticketPriority" name="priorite" required>
                            <option value="faible" <?= isset($affectationActive) && $affectationActive['priorite'] == 'faible' ? 'selected' : '' ?>>Faible</option>
                            <option value="moyenne" <?= isset($affectationActive) && $affectationActive['priorite'] == 'moyenne' ? 'selected' : '' ?>>Moyenne</option>
                            <option value="haute" <?= isset($affectationActive) && $affectationActive['priorite'] == 'haute' ? 'selected' : '' ?>>Haute</option>
                            <option value="urgence" <?= isset($affectationActive) && $affectationActive['priorite'] == 'urgence' ? 'selected' : '' ?>>Urgence</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="ticketPrice">Prix prestation :</label>
                        <input type="number" id="ticketPrice" name="prixPrestation" step="0.01" min="0" 
                            value="<?= isset($affectationActive) ? htmlspecialchars($affectationActive['prixPrestation']) : '' ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ticketDuration">Durée (minutes) :</label>
                        <input type="number" id="ticketDuration" name="duree" min="1" 
                            value="<?= isset($affectationActive) ? htmlspecialchars($affectationActive['duree']) : '30' ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="agentSelect">Agent :</label>
                    <select id="agentSelect" name="id_agent" required>
                        <option value="">Sélectionnez un agent</option>
                        <?php foreach ($agentsDisponibles as $agent): ?>
                        <option value="<?= $agent['id_agent'] ?>"
                            <?= isset($affectationActive) && $affectationActive['id_agent'] == $agent['id_agent'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($agent['Nom']) ?> 
                            (<?= $agent['nb_requetes_actives'] ?> requêtes)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <?= isset($affectationActive) ? 'Mettre à jour l\'affectation' : 'Créer l\'affectation' ?>
                    </button>
                    
                    <?php if (isset($affectationActive)): ?>
                    <a href="<?= $base_url ?>/requete/cloturer/<?= $requete['id_requete'] ?>" 
                       class="btn-secondary" 
                       onclick="return confirm('Voulez-vous vraiment clôturer ce ticket ?')">
                        Clôturer le ticket
                    </a>
                    <a href="<?= $base_url ?>/requete/resolue/<?= $requete['id_requete'] ?>" 
                       class="btn-secondary" 
                       onclick="return confirm('Voulez-vous vraiment resoudre ce ticket ?')">
                        Resoudre le ticket
                    </a>
                    <?php endif; ?>
                </div>
            </form>
            
            <h3 style="margin-top: 30px;">Historique des assignations</h3>
            <table>
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Catégorie</th>
                        <th>Priorité</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agentsAssignes as $agent): ?>
                    <tr>
                        <td><?= htmlspecialchars($agent['agent_nom']) ?></td>
                        <td><?= htmlspecialchars($agent['categorie_nom'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($agent['priorite'] ?? 'N/A') ?></td>
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
            
            <form id="messageForm" method="POST" action="<?= $base_url ?>/requeteClient/envoyer-message" style="margin-top: 1rem;">
                <input type="hidden" name="id_requete" value="<?= $requete['id_requete'] ?>">
                <div class="form-group">
                    <textarea name="message" rows="3" style="width: 100%;" placeholder="Écrivez votre message..." required></textarea>
                </div>
                <button type="submit" class="btn-primary">Envoyer</button>
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
                            <th>Évaluateur</th>
                            <th>Note</th>
                            <th>Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $eval): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($eval['date_evaluation'])) ?></td>
                            <td><?= htmlspecialchars($eval['evaluateur_nom']) ?> </td>
                            <td>
                                <span style="color: gold; font-size: 1.2em;">
                                    <?= str_repeat('★', $eval['note']) ?><?= str_repeat('☆', 5 - $eval['note']) ?>
                                </span>
                                (<?= $eval['note'] ?>/5)
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
// Simple confirmation pour la clôture du ticket
document.querySelectorAll('[onclick="return confirm(\'Voulez-vous vraiment clôturer ce ticket ?\')"]')
    .forEach(link => {
        link.onclick = function() {
            return confirm('Voulez-vous vraiment clôturer ce ticket ?');
        };
    });

// Faire défiler le chat vers le bas
window.addEventListener('load', () => {
    const chatContainer = document.querySelector('.chat-container');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
});
</script>
</body>
</html>