# TestSymfony

Projet utilisé pour un test Symfony; un service permettant la gestion des données dans un fichier YAML.
TODO : 
- rendre le path du YAML relatif par rapport au serveur
- mettre un front élégant

Pour utiliser le projet, il faut au préalable Symfony (si le serveur n'est pas hébergé) et composer.

1- faire un composer update pour télécharger les dépendances

2- vérifier le path du fichier .yaml à traiter ; path présent dans src/controller/YamlController.php , ligne 21

3- Si le serveur n'est pas déjà hébergé, taper la commande "symfony server:start".

4- Une fois dans le server, aller dans l'adresse /yaml, et vous aurez accès au service YAML.
