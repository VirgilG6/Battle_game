<?php

class Personnage
{
    private $_id;
    private $_nom;
    private $_degats = 0; //Dégâts du personnage
    private $_experience = 0; //Expérience du personnage
    private $_force = 20; //Force d'attaque du personnage
    private $_niveau;
    
    //Déclarations des constantes en rapport avec la force
    const FORCE_PETITE = 20;
    const FORCE_MOYENNE = 50;
    const FORCE_GRANDE = 80;

    const CEST_MOI = 2;
    const PERSONNAGE_TUE = 2;
    const PERSONNAGE_FRAPPE = 2;


    public function __construct(array $ligne) //Contructeur demandant 3 paramètres
    {
        $this->hydrate($ligne);
        // self::$_compteur++;
        // print('<br/> Le personnage n°'.self::$_compteur.' "' . $this->getNom() . '" est créé !'); //Message s'affichant une fois que tout objet est créé
    }

    //Un tableau de données doit être passé à la fonction (d'où le préfixe "array")
    public function hydrate(array $ligne)
    {
        foreach($ligne as $key => $value)
        {
            //On récupère le nom du setter correspondant à l'attribut
            $method = 'set'.ucfirst($key);

            //Si le setter correspondant existe
            if (method_exists($this, $method))
            {
                //On appelle le setter
                $this->$method($value);
            }
        }
    }

    //Ceci est la méthode getNom() : elle se charge de renvoyer le contenu de l'attribut $_nom
    public function getNom()
    {
        return $this->_nom;
    }

    public function setNom($nom)
    {
      // On vérifie qu'il s'agit bien d'une chaîne de caractères.
      if (is_string($nom))
      {
        $this->_nom = $nom;
      }
    }

    //Ceci est la méthode getDegats() : elle se charge de renvoyer le contenu de l'attribut $_degats
    public function getDegats()
    {
        return $this->_degats;
    }

    public function setDegats($degats)
    {
        if (!is_string($degats)) //S'il ne s'agit pas d'un nombre entier
        {
            trigger_error('Les degats d\'un personnage doit être un nombre entier', E_USER_WARNING);
            return;
        }
        $this->_degats = $degats;
    }

    //Ceci est la méthode getForce() : elle se charge de renvoyer le contenu de l'attribut $_force
    public function getForce()
    {
        return $this->_force;
    }

    public function setForce($force)
    {
        //On vérifie qu'on nous donne bien soit une "FORCE_PETITE", soit une "FORCE_MOYENNE", soit une "FORCE_GRANDE"
        if (in_array($force, array(self::FORCE_PETITE, self::FORCE_MOYENNE, self::FORCE_GRANDE)))
        {
            $this->_force = $force;
        }
    }

    public function getNiveau()
    {
        return $this->_niveau;
    }

    public function setNiveau($niveau)
    {
        $niveau = (int) $niveau;
      
        if ($niveau >= 1 && $niveau <= 100)
        {
            $this->_niveau = $niveau;
        }
    }

    public function gagnerExperience()
    {
        //On ajoute 1 à notre attribut $_experience
        $this->_experience = $this->_experience + 1;
        print('<br/> Expérience de ' . $this->getNom() . ' = ' . $this->_experience);
    }

    //Ceci est la méthode getExperience() : elle se charge de renvoyer le contenu de l'attribut $_experience
    public function getExperience()
    {
        return $this->_experience;
    }

    public function setExperience($experience)
    {
        if (!is_string($experience)) //S'il ne s'agit pas d'un nombre entier
        {
            trigger_error('L\' experience d\'un personnage doit être un nombre entier', E_USER_WARNING);
            return;
        }

        if ($experience > 100) //On vérifie bien qu'on ne souhaite pas assigner une valeur supérieur à 100
        {
            trigger_error('L\' experience d\'un personnage ne peut pas dépasser 100', E_USER_WARNING);
            return;
        }

        $this->_experience = $experience;
    }

    // Liste des setters  
    public function setId($id)
    {
      // On convertit l'argument en nombre entier.
      // Si c'en était déjà un, rien ne changera.
      // Sinon, la conversion donnera le nombre 0 (à quelques exceptions près, mais rien d'important ici).
      $id = (int) $id;
      
      // On vérifie ensuite si ce nombre est bien strictement positif.
      if ($id > 0)
      {
        // Si c'est le cas, c'est tout bon, on assigne la valeur à l'attribut correspondant.
        $this->_id = $id;
      }
    }

    public function nomValide()
    {
        return !empty($this->_nom);
    }

    public function frapper(Personnage $perso)
    {
        // Avant tout : vérifier qu'on ne se frappe pas soi-même.
        // Si c'est le cas, on stoppe tout en renvoyant une valeur signifiant que le personnage ciblé est le personnage qui attaque.
        if($perso->getId() == $this->_id)
        {
            return self ::CEST_MOI;
        }
        // On indique au personnage frappé qu'il doit recevoir des dégâts.
        return $perso->recevoirDegats();
    }

    public function recevoirDegats()
    {
        // On augmente de 5 les dégâts.
        $this->_degats += 5;

        // Si on a 100 de dégâts ou plus, la méthode renverra une valeur signifiant que le personnage a été tué.
        if($this->_degats >= 100)
        {
            return self::PERSONNAGE_TUE;
        }

        // Sinon, elle renverra une valeur signifiant que le personnage a bien été frappé.
        return self::PERSONNAGE_FRAPPE;
    }
}
?>