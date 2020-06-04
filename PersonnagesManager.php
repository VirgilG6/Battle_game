<?php
use Personnage;

    class PersonnagesManager
    {
        private $_db; //Instance de PDO

        public function __construct($db)
        {
            $this->setDb($db);
        }
        public function add(Personnage $perso)
        {
            // Préparation de la requête d'insertion.
            $request = $this->_db->prepare('INSERT INTO personnages SET nom = :nom, `force` = :force, degats = :degats, niveau = :niveau, experience = :experience;');
            
            // Assignation des valeurs pour le nom, la force, les dégâts, l'expérience et le niveau du personnage.
            $resquest->bidvalue(':nom', $perso->getNom(), PDO::PARAM_STR);
            $resquest->bidvalue(':force', $perso->getForce(), PDO::PARAM_INT);
            $resquest->bidvalue(':degats', $perso->getDegats(), PDO::PARAM_INT);
            $resquest->bidvalue(':niveau', $perso->getNiveau(), PDO::PARAM_INT);
            $resquest->bidvalue(':experience', $perso->getExperience(), PDO::PARAM_INT);

            // Exécution de la requête.
            $request->execute();
        }
    
        public function delete(Personnage $perso)
        {
            // Exécute une requête de type DELETE.
            $this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->id().';');
        }

        public function count()
        {
            return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
        }

        public function exists($info)
        {
            if(is_int($info)) //On veut voir si tel personnage ayant pour id $info existe
            {
                $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE id = :id');
                $q->execute(array(':id' => $info));

                return (bool) $q->fetchCOlumn();
            }

            //Sinon, c'est qu'on veut vérifier que le nom existe ou pas
            $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
            $q->execute(array(':nom' => $info));

            return (bool) $q->fetchCOlumn();
        }

        // public function getOne($id)
        // {
        //     // Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personnage.
        //     $id = (int) $id;

        //     $request = $this->_db-> query('SELECT id, nom, `force`, degats, niveau, experience FROM personnages WHERE id = '.$id.';');
        //     $ligne = $request->fetch(PDO::FETCH_ASSOC);

        //     return new Personnage($ligne);
        // }

        public function getOne($info)
        {
            if(is_int($info))
            {
                $q = $this->_db->prepare('SELECT id, nom, deagts FROM personnages WHERE id = '.$info);
                $donnees = $q->fecth(PDO::FECTH_ASSOC);

                return new Personnage($donnees);
            }
            else
            {
                $q = $this->_db->prepare('SELECT id, nom, deagts FROM personnages WHERE nom = :nom');
                $q->execute(array(':nom' => $info));

                return new Personnage($q->fetch(PDO::FETCH_ASSOC));
            }
        }

        public function getList()
        {
            // Retourne la liste de tous les personnages.
            $persos = array();

            $request = $this->_db->query('SELECT id, nom, `force`, degats, niveau, experience FROM personnages ORDER BY nom;');

            if ($request->errorCode() > 0) {
                echo "<br/>Une erreur SQL est intervenue : ";
                print_r($request->errorInfo()[2]);
            }
            
            while($ligne = $request->fetch(PDO::FETCH_ASSOC))
            {
                $persos[] = new Personnage($ligne);
            }

            return $persos;
        }

        public function update(Personnage $perso)
        {
            // Prépare une requête de type UPDATE.
            $request = $this->_db->prepare('UPDATE personnages SET nom = :nom, `force` = :force, degats = :degats, niveau = :niveau, experience = :experience WHERE id = :id;'); 
            
            // Assignation des valeurs à la requête.
            $resquest->bidvalue(':force', $perso->getForce(), PDO::PARAM_INT);
            $resquest->bidvalue(':degats', $perso->getDegats(), PDO::PARAM_INT);
            $resquest->bidvalue(':niveau', $perso->getNiveau(), PDO::PARAM_INT);
            $resquest->bidvalue(':experience', $perso->getExperience(), PDO::PARAM_INT);
            $resquest->bidvalue(':id', $perso->getId(), PDO::PARAM_INT);

            // Exécution de la requête.
            $request->execute();
        }

        public function setDb(PDO $db)
        {
            $this->_db = $db;
        }
    }
?>