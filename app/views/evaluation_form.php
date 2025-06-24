<form method="POST" action="/evaluation/submit">
    <input type="hidden" name="id_ticket" value="<?= $id_ticket ?>">
    <input type="hidden" name="id_affectation" value="<?= $id_affectation ?>">

    <label for="note">Note (1 à 5) :</label>
    <select name="note" id="note" required>
        <option value="">--Choisir--</option>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
    </select>

    <label for="commentaire">Commentaire :</label>
    <textarea name="commentaire" id="commentaire" rows="4"></textarea>

    <button type="submit">Envoyer l’évaluation</button>
</form>
