
# AtipikHouse





## Installation

il cloner le repertoire puis tapez la commande 

```bash
    cd atipikhouse 
    composer install
```


modifier la base de donnée dans le env 

```bash
    php bin/console doctrine:database:create
    php bin/console make:migration
```


Si besoin utiliser la commande suivante :
```bash
  php bin/console doctrine:migrations:migrate
```


## Run Locally


Go to the project directory

```bash
  cd atipykhouse
```

Install dependencies

```bash
  composer install
```

Start the server

```bash
  symphony server:start
```

