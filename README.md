
# AtipikHouse





## Installation

il faut cloner le repertoire


modifier la base de donnée dans le env 

```bash
    cd atipikhouse 
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
  symfony server:start
```

