# P8 - Améliorez une application existante de ToDo & Co [![Codacy Badge](https://api.codacy.com/project/badge/Grade/ac9e46df4c20413b95462c1c97d9e511)](https://app.codacy.com/gh/btolan-karudev/project8oc?utm_source=github.com&utm_medium=referral&utm_content=btolan-karudev/project8oc&utm_campaign=Badge_Grade)

## Installation

*   Clonez ou téléchargez le repository GitHub :
```system
git clone https://github.com/btolan-karudev/project8oc
```
*   Configurez le fichier .env

*   Installez les dépendances avec Composer :
```system
composer install
```

*   Créez la base de données :
```system
php bin/console doctrine:database:create
```

*   Créez la structure de la base de données :
```system
php bin/console doctrine:schema:create
```

*   Créez la base de données et la structure vous permettant de tester :
```system
php bin/console d:d:c --env=test
php bin/console d:s:u --env=test -f
```

## Tests

*   Les tests :
```system
php bin/phpunit
```

*   Rapport de couverture de code :
```system
php bin/phpunit --coverage-html public/coverage
```
## Les issues

Vous retrouvez les issues ici [TO DO List Issues](https://github.com/btolan-karudev/project8oc/issues)

## Le Code Coverage

Vous retrouvez L’ensemble des fichiers HTML générés par PHPUnit dans le dir public/coverage

![PHPUnit Coverage!](/assets/img/Coverage.JPG "PHPunit Coverage")
