# Docker Symfony

A [Docker](https://www.docker.com/)-based installer and runtime for the [Symfony](https://symfony.com) web framework, with full [HTTP/2](https://symfony.com/doc/current/weblink.html), HTTP/3 and HTTPS support.

## Getting Started

1. Install **Docker Compose**
2. Run `docker compose build --no-cache` to build fresh images *Pour installer les dépendences*
3. Run `docker compose up --pull --wait` to start the project *Démarrer le serveur sur docker*
4. Open `https://localhost:8080` in your web browser / *Ce lien fonctionne (J'ai changer le port de `8000` à `8080`)*
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Features

* Création d'un article avec le framework Symfony
* Utilisation d'un template du site [startbootstrap](https://startbootstrap.com/templates) pour un dashboard
* Création d'une entity Article avec comme colonnes : 
    * `id: int, titre: string, texte: text, etat: bool, date: DateImmutable`
* Fonctions :
    * Creation d'Article
    * Voir Article (En prenant en paramêtre son id)
    * *A venir*


## Documentation

- [Documentation symfony](https://symfony.com/doc/current/doctrine.html)

## License

Docker Symfony est disponible sous la licence MIT.

## Credits

Created by [Kévin Dunglas](https://dunglas.fr), co-maintained by [Maxime Helias](https://twitter.com/maxhelias) and sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
