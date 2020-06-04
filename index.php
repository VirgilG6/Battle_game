<?php
    //On enregistre notre autoload
    function chargerClasse($classname)
    {
        require $classname . '.php';
    }

    spl_autoload_register('chargerClasse');

    session_start(); //On appelle session_start() APRÈS avoir enregistré l'autoload

    if (isset($_GET['deconnexion']))
    {
        session_destroy();
        header('Location: .');
        exit();
    }

    $db = new PDO('mysql:host=localhost;dbname=battlegame', 'root', '');
    //Emettre une alerte à chaque fois qu'une requête a échoué
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    //Désactive la simulation des requêtes préparées et utiliser l'interface native afin de récupérer les données avec leurs types
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Si toutes les colonnes sont converties en string
        
    $manager = new PersonnagesManager($db);
    
    if (isset($_POST['creer']) && isset($_POST['nom'])) //Si on a voulu créer un personnage
    {
        $perso = new Personnage(array('nom' => $_POST['nom'])); //On crée un nouveau personnage

        if (!$perso->nomValide())
        {
            $message = 'Le nom choisi est invalide.';
            unset($perso);
        }
        elseif ($manager->exists($perso->getNom())) 
        {
            $message = 'Le nom du personnage est déjà pris.';
            unset($perso);
        } 
        else 
        {
            $manager->add($perso);
        }
    }
    elseif (isset($_POST['utiliser']) && isset($_POST['nom'])) //Si on a voulu utiliser un personnage
    {
        if ($manager->exists($_POST['nom'])) //Si celui-ci existe
        {
            $perso = $manager->getOne($_POST['nom']);
        } 
        else 
        {
            $message = 'Ce personnage n\'existe pas !'; //S'il n'existe pas, on affichera ce message
        }
    }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <title>TP : Mini jeu de combat</title>

        <meta http-equiv="Content-type" content="text/html; charset=iso-8859-1" />
    </head>
    <body>
        <p> Nombre de personnages créés : <?php echo $manager->count(); ?></p>
        <?php
            if (isset($message)) //On a un message à afficher ?
            {
                echo '<p>', $message, '</p>'; //Si oui, on l'affiche
            }
        ?>
        
        <p> <a href="?deconnexion=1">Déconnexion</a></p>

        <fieldset>
            <legend>Mes informations</legend>
            <p>
                Nom : <?php echo htmlspecialchars($perso->getNom()); ?><br />
                Dégâts : <?php echo $perso->getDegats(); ?>
            </p>
        </fieldset>

        <fieldset>
            <legend>Qui frapper ?</legend>
            <p>
                <?php
                    $joueurs = $manager->getList($perso->getNom());

                    if(empty($joueurs))
                    {
                        echo 'Personne à frapper !';
                    }
                    else
                    {
                        foreach($joueurs as $joueur)
                        {
                            echo '<a href="?frapper=', $joueur->getId(), '">', htmlspecialchars($joueur->getNom()), '</a> (dégâts : ', $joueur-getDegats(), ')<br />';
                        }
                ?>
            </p>
        </fieldset>
        <form action="" method="post">
            <p>
                Nom : <input type="text" name="nom" maxlength="50" />
                <input type="submit" value="Créer ce personnage" name="creer" />
                <input type="submit" value="Utiliser ce personnage" name="utiliser" />
            </p>
        </form>
        <?php 
            }
        ?>
    </body>
</html>

<?php
    if (isset($perso)) //Si on créé un personnage, on le stocke dans une véritable session afin d'économiser une requête SQL
    {
        $_SESSION['perso'] = $perso;
    }
?>