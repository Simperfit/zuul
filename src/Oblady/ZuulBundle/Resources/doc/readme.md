Zuul le maitre de clef.

TODO

  Must Have
   - refaire la generation d'un .ssh/config
   - 
   - améliorer la position du bouton mot de passe perdu/ le supprimer quand on a renvoyer le mot de passe
   - ajouter la clef des neuros au clef connu
   - ameliorer la gestion des droits 
   - remplacer une clef 
       -> un formulaire saisie de la nouvelle clef
       -> parcourir tous les serveurs
       ->si on peut se conencter et que la clef de l'utilisateur et en place 
          -> on remplace la clef par celle fourni
       -> a la fin on met a jour l'utilisateur 
   -> ameliorer l'affichage des liste d'utilisateurs
   
   
  Cool To Have 
   - generation d'un fichier tmux pour un cluster ?
   - Ajouter la gestion de plusieurs clef par utilisateur...
     -> Une tache cron qui passe chaque jour qui fait un rapport :
        - Les serveurs ou la clef maitre n'est pas installer
        - La liste des clef inconnu par serveurs
     ->afficher une corbeille pour effacer une clef info
     ->afficher une super corbeille pour supprimer une clef inconnue sur tous les serveurs
     -> creation d'utilisateur : mail avec mot de passe et url, 
     -> nom court machine pour .ssh/config a rajouter au lieu du nom qui contient de espace 
        
