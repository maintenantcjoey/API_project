# Bilmo

[![forthebadge](http://forthebadge.com/images/badges/built-with-love.svg)](http://forthebadge.com)


## Pour commencer

Diagrammes UML dans le dossier Google Drive: (https://drive.google.com/drive/folders/1cNwiM2BJG9xTkSUHLmZ6HqDdcc_XTlUD?usp=sharing),
le résultat codacy y est également disponible, ainsi qu'un jeu de donnée initale.

### Pré-requis

- Composer (https://getcomposer.org/)
- symfony (version à jour)
- php (version supérieure ou égale a 5.4)

### Installation

- Cloner le repository git
- Exécuter la commande symfony composer install
- Mettre à jour le ficher .env pour accéder à la base de donnée locale
- Exécuter la commande php bin/console doctrine:database puis php bin/console doctrine:create afin de créer la base  de donées
- Exécuter la commande php bin/console make:migration pour créer le fichier de migration, puis php bin/console doctrine:migrations:migrate afin de récupérer les entités et la    base de données ainsi créée
- Exécuter la commande symfony console doctrine:fixtures:load

## Démarrage

- Exécuter la commande symfony server:start

## Fabriqué avec

* Bootstrap (https://getbootstrap.com/) - Framework CSS
* Composer (https://getcomposer.org/) - Gestionnaire de dépendances
* Twig (https://twig.symfony.com/) - Moteur de template
* Visual Studio Code (https://code.visualstudio.com/) - IDE



