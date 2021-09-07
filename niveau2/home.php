<?php
    session_start() ; // il faut démarrer la session
    include_once "../connexion/connect.php" ;
    include_once "../connexion/tools.php" ;

    $error_message = null ; // cette variable servira à savoir à la fois si une erreur a été détectée et si oui, son message
    if ((isset($_GET["action"]) && $_GET["action"] === "logout") // bouton logout
        || ! isset($_SESSION["email"])
        || empty($_SESSION["email"]))
    {
        // on vérifie si on a bien un email en session, sinon ça veut dire que l'utilisateur n'est pas connecté
        session_destroy();
        header('Location: login.php'); // on le renvoie au login
    }

    // on sait que l'utilisateur est bien connecté, du coup on commence le traitement backend

    // cas simple : l'utilisateur a cliquer sur un lien delete et a confirmé la suppression, on supprime donc l'utilisateur
    if (isset($_GET["delete"]) && ! empty($_GET["delete"]))
    {
        $user = $database->delete(DATABASE_TABLE_UTILISATEURS, [DATABASE_TABLE_UTILISATEURS_ID => $_GET["delete"]]) ;
    }

    // Traitement des formulaires
    // pour rappel il peut y avoir soit la création d'un nouvel utilisateur soit l'edition d'un utilisateur existants
    // les 2 passent par un formulaire en modal
    if (isset($_POST["submit"]))
    {
        // on intialise des variables qui serviron plus tard pour le traitement
        $id = null ;
        $pwd = null ;
        $nom = null ;
        $prenom = null ;
        $email = null ;
        $is_pro = false ;

        // il n'y aura un id que pour une édition, ça nous permettra donc de savoir si on est en edition u création
        if (isset($_POST["id"]))
        {
            $id = htmlspecialchars($_POST["id"]) ;
        }

        // si on a le password qui est dans le formulaire
        if (isset($_POST["pwd"]) && !empty($_POST["pwd"]))
        {
            $pwd = htmlspecialchars($_POST["pwd"]) ;
            if (! validate_password($_POST["pwd"])) // on vérifie s'il correspond aux attentes en terme de complexité
            {
                $error_message = "Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre, et faire 8 caractères ou plus" ;
            }
        }

        // cas particulier, le password peut etre null dans le cas d'une edition, mais uniquement dans ce cas là
        // si le password est nul dans le cas d'une création c'est une erreur
        if ($id == null && $pwd == null)
        {
            $error_message = "Vous devez saisir un mot de passe lors de la création d'un compte" ;
        }

        // dans le cas création aussi, l'email ne doit pas déjà exister en base (e édition il ne faut pas faire ce test)
        if ($id == null && $database->count(DATABASE_TABLE_UTILISATEURS, DATABASE_TABLE_UTILISATEURS_EMAIL, [DATABASE_TABLE_UTILISATEURS_EMAIL => $_POST["email"] ]))
        {
            $error_message = "Un utilisateur est déjà inscrit avec cet email" ;
        }

        // en édition ou création, on doit avoir un email
        if (isset($_POST["email"]))
        {
            $email = htmlspecialchars($_POST["email"]) ;
        }
        else{
            $error_message = "Vous devez saisir un email" ;
        }

        // en édition ou création, on doit avoir un nom
        if (isset($_POST["nom"]))
        {
            $nom = htmlspecialchars($_POST["nom"]) ;
        }
        else{
            $error_message = "Vous devez saisir un nom" ;
        }

        // en édition ou création, on doit avoir un prénom
        if (isset($_POST["prenom"]))
        {
            $prenom = htmlspecialchars($_POST["prenom"]) ;
        }
        else{
            $error_message = "Vous devez saisir un prénom" ;
        }

        // en édition ou création, on doit avoir un type de compte
        if (isset($_POST["type"]) && strlen($_POST["type"]) > 0)
        {
            $is_pro = htmlspecialchars($_POST["type"]) ;
        }
        else{
            $error_message = "Vous devez choisir un type de compte" ;
        }

        // on a fini toutes les vérifications, maintenant on peut effectuer le traitement
        if ($error_message == null) // si on a décelé aucune erreur, on fera une requete en DB
        {
            if ($id == null) // insertion d'un nouvel utilisateur
            {
                // $id == null signifie qu'on est en mode création donc on fait un insert
                $password = password_hash($pwd, PASSWORD_BCRYPT) ;

                $database->insert(DATABASE_TABLE_UTILISATEURS,
                    [
                        DATABASE_TABLE_UTILISATEURS_EMAIL => $email,
                        DATABASE_TABLE_UTILISATEURS_IS_PRO => $is_pro,
                        DATABASE_TABLE_UTILISATEURS_LAST_LOGIN => null,
                        DATABASE_TABLE_UTILISATEURS_NOM => $nom,
                        DATABASE_TABLE_UTILISATEURS_PRENOM => $prenom,
                        DATABASE_TABLE_UTILISATEURS_PASSWORD => $password,
                    ]);
            }
            else{ // update de l'utilisateur
                $datas =  [
                    DATABASE_TABLE_UTILISATEURS_EMAIL => $email,
                    DATABASE_TABLE_UTILISATEURS_IS_PRO => $is_pro,
                    DATABASE_TABLE_UTILISATEURS_NOM => $nom,
                    DATABASE_TABLE_UTILISATEURS_PRENOM => $prenom,
                ] ;
                if ($pwd != null) // si le mot de passe est présent dans le formulaire alors on l'ajoute dans les champs à updater
                {
                    $datas[DATABASE_TABLE_UTILISATEURS_PASSWORD] = password_hash($pwd, PASSWORD_BCRYPT) ;
                }
                $database->update(DATABASE_TABLE_UTILISATEURS, $datas, [DATABASE_TABLE_UTILISATEURS_ID => $id]) ;
            }

            // on réinitialise les variables pour vider les champs du formulaire
            $id = null ;
            $pwd = null ;
            $nom = null ;
            $prenom = null ;
            $email = null ;
            $is_pro = false ;
        }

    }

    // Traitement terminé, on charge la liste de tous les users pour les afficher
    $users = $database->select(DATABASE_TABLE_UTILISATEURS, "*") ;

?>


<?php include_once ("templates/header.php") ; ?>
        <div class="container">
            <h1 class="text-center">Bienvenue <?= $_SESSION["email"] ?> <a href="home.php?action=logout"><i class="fas fa-sign-out-alt"></i></a></h1>
            <div class="row">
                <div class="col-12">
                    <a href="" class="btn btn-primary col-3 float-right" data-toggle="modal" data-target="#globalModalCenter">Ajouter un utilisateur</a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr class="bg-primary text-white">
                            <th>#Id</th>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Professionnel ?</th>
                            <th>Dernière connexion</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(count($users)>0){
                            foreach($users as $user)
                            {
                                // pn boucle sur tous les utilisateurs pour les afficher dans le tableau
                                ?>
                                <tr>
                                    <td><?= $user[DATABASE_TABLE_UTILISATEURS_ID] ?></td>
                                    <td><?= $user[DATABASE_TABLE_UTILISATEURS_NOM]." ". $user[DATABASE_TABLE_UTILISATEURS_PRENOM]?></td>
                                    <td><?= $user[DATABASE_TABLE_UTILISATEURS_EMAIL] ?></td>
                                    <td><?= $user[DATABASE_TABLE_UTILISATEURS_IS_PRO] ? "Oui" : "Non" ?></td>
                                    <td><?= date($user[DATABASE_TABLE_UTILISATEURS_LAST_LOGIN]) ?></td>
                                    <td align="center">
                                        <a data-toggle="modal" data-target="#ModalCenter<?= $user[DATABASE_TABLE_UTILISATEURS_ID] ?>" class="text-primary"><i class="fa fa-fw fa-edit"></i></a> |
                                        <a userid="<?= $user[DATABASE_TABLE_UTILISATEURS_ID] ?>" class="text-danger delete-link" ><i class="fa fa-fw fa-trash"></i></a>
                                    </td>
                                </tr>
                                </div>
                                <?php
                            }
                        }else{
                            ?>
                            <tr><td class="text-center">Aucun utilisateur trouvé</td></tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Fenêtre modale de création de nouveaux utilisateurs -->
        <div class="modal fade" id="globalModalCenter" tabindex="-1" role="dialog" aria-labelledby="globalModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="globalModalLongTitle">Création d'un nouveau compte utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php
                            // comme il s'agit du formulaire de création, soit on met les champs à vide, soit on est dans le cas
                            // d'une mauvaise saisie précédent (ajout qui a échoué avec un message d'erreur) et dans ce cas on préremplit avec les données en POST
                            $nom_user = isset($nom) ? $nom : "" ;
                            $prenom_user = isset($prenom) ? $prenom : "" ;
                            $email_user = isset($email) ? $email : "" ;
                            $is_pro_user = isset($is_pro) ? $is_pro : false ;
                            $id_user = null ;
                        include "templates/userform.php" ; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($error_message != null && $id == null) { ?> <script>$('#globalModalCenter').modal()</script> <?php } // Si on a eu une erreur lors de la saisie on réaffiche le formulaire en modal ?>

        <?php foreach($users as $user) // on créé des fenêtres modales qui permettront d'éditer dans la même page
            // tous les utilisateurs très simplement, en utilisant quelques variables et le template du formulaire
        {
        ?>
                <!-- Modal -->
                <div class="modal fade" id="ModalCenter<?= $user[DATABASE_TABLE_UTILISATEURS_ID] ?>" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle<?= $user[DATABASE_TABLE_UTILISATEURS_ID] ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLongTitle<?= $user[DATABASE_TABLE_UTILISATEURS_ID] ?>">Edition d'un compte utilisateur</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?php
                                // on définit les variables que le template va utiliser pour les différents champs
                                // soit on prend ceux de l'utilisateur en base de données, soit en cas d'erreur de saisie, on reprend les données
                                // déjà saisies par l'utilisateur
                                $id_user = $user[DATABASE_TABLE_UTILISATEURS_ID] ;
                                $nom_user = isset($id) && $id == $id_user && isset($nom) ? $nom : $user[DATABASE_TABLE_UTILISATEURS_NOM]  ;
                                $prenom_user = isset($id) && $id == $id_user && isset($prenom) ? $prenom : $user[DATABASE_TABLE_UTILISATEURS_PRENOM] ;
                                $email_user = isset($id) && $id == $id_user && isset($email) ? $email : $user[DATABASE_TABLE_UTILISATEURS_EMAIL] ;
                                $is_pro_user = isset($id) && $id == $id_user && isset($is_pro) ? $is_pro : $user[DATABASE_TABLE_UTILISATEURS_IS_PRO] ;

                                include "templates/userform.php" ; ?>
                                <?php if (isset($id) && $id == $id_user && $error_message != null) { ?> <script>$('#ModalCenter<?= $user[DATABASE_TABLE_UTILISATEURS_ID] ?>').modal()</script> <?php }
                                // Si on a eu une erreur lors de l'édition de cet utilisateur on réaffiche le formulaire en modal
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

        <?php } ?>


    <!-- Demande de confirmation avant de supprimer un utilisateur -->
    <script>
        $( document ).ready(function() {
            $(".delete-link").click(function () {
                if ( window.confirm('Voulez-vous vraiment supprimer cet utilisateur ?'))
                {
                    window.location.href = "home.php?delete="+$(this).attr("userid") ;
                }
            })
        });
    </script>
<?php include_once ("templates/footer.php") ; ?>
