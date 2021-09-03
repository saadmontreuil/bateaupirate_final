
<!-- ce template permet d'insérer à plusieurs endroit dans le code le formulaire qui permettra de créer un nouvel
utilisateur ou éditer un utilisateur existant dans la page home.php
Notez que les variables utilisées dans ce templates DOIVENT être définies dans le code PHP qui précède l'include
-->
<form action="home.php" method="post">
    <div class="imgcontainer">
        <img src="img/img_avatar2.png" alt="Avatar" class="avatar">
    </div>
    <div class="container">
        <label for="nom"><b>Nom *</b></label>
        <input type="text" placeholder="Entrez votre nom" name="nom" required value="<?= $nom_user ?>">

        <label for="prenom"><b>Prénom *</b></label>
        <input type="text" placeholder="Entrez votre prénom" name="prenom" required value="<?= $prenom_user ?>">

        <label for="email"><b>Email *</b></label>
        <input type="email" placeholder="Entrez votre email" name="email" required value="<?= $email_user ?>">

        <!-- le mot de passe sera "required" pour la création uniquement, en édition on peut le laisser vide -->
        <label for="pwd"><b>Mot de passe <?= $id_user == null ? "*" : "" ?></b></label>
        <input type="text" placeholder="<?= $id_user == null ? "Entrez votre mot de passe" : "Laissez vide pour ne pas modifier le mot de passe" ?>" name="pwd" <?= $id_user == null ? "required" : "" ?> value="">

        <?php if (isset($id_user) && $id_user != null ) { ?>
        <!-- si on est en édition, alors on ajoute l'id de l'utilisateur en hidden histoire de retrouver l'id dans le POST -->
        <input type="hidden" name="id" value="<?= $id_user ?>">
        <?php } ?>

        <label class="col-12" for="type"><b>Type de compte *</b></label>
        <span class="col-6"><input type="radio" id="particulier" name="type" value="0" <?= $is_pro_user ? "" : "checked" ?>>Particulier</span>
        <span class="col-6"><input type="radio" id="pro" name="type" value="1" <?= $is_pro_user ? "checked" : "" ?>>Professionnel</span>
        <?php if ($error_message != null && $id_user == $id) { // s'il y a eu une erreur on l'affiche ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php } ?>
    </div>
    <button name="submit" type="submit" value="on" class="btn btn-primary">Enregistrer</button>
</form>