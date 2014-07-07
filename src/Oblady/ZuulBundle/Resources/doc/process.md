KeyManager 
    Methode Public

      -> Charger la config d'un serveur 
      -> Tester l'existance de la clef master
      -> Installer la clef Master

      -> Ajouter une clef ssh a un serveur
      -> Verifier qu'une clef ssh existe sur le serveur
      -> Retirer une clef ssh du serveur


UserManager
  
   Methode
      setUser
      Addkey
	 
        Pour chaque Groupe
            ->Pour chaque Serveur lié au groupe
               -> Ajouter la clef   
        Pour chaque Serveur a l'utilisateur
               -> Ajouter la clef
        Pour chaque Cluster
            ->Pour chaque Serveur lié au Cluster
               -> Ajouter la clef 

        Optimisation:
            Crer un tableau de serveur avec fusion 
               Group->getServer
               User->getServer
               Cluster->getServer
            Pour chaquer server
                -Ajouter la clef

ServerManager

    AddUser

        -> Pour chaque Clef de l'utilisateur 
           - Ajouter la clef Job

    AddGroup
        -> Pour chaque Utilisateur du groupe
            -> Appeler AddUser


GroupeManager
    AddUser
       ->Pour chaque Serveur lié au groupe
           -> appeler AddUser
       ->Pour cheque CLuter lié au groupe
           -> appeler AddUser

ClusterManager
   AddUser
      -> pour Chaque serveur 
        ->Appler AddUser
   AddGroup
      -> pour cheque Serveur
        ->Appeler AddGroup
   AddServer
      ->Pour chaque Utilisateur
         Appler AddUser sur le serveur
      -> Pour chaque groupe
         Appeler AddGroup pour le serveur

  
  
