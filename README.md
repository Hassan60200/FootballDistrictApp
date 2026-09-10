# DDD Football

Projet d'apprentissage du **Domain-Driven Design (DDD)** avec Symfony, à travers une application simple de gestion d'un club de football amateur.

## Objectif du projet

Ce dépôt sert de terrain d'exercice pour pratiquer les concepts du DDD (Entités, Value Objects, Agrégats, Repository, Application Services) sur un cas concret, en dehors d'un contexte MVC classique.

## Fonctionnalités

- **Gestion des équipes** — suivi des équipes seniors du club
- **Gestion des joueurs** — profils des joueurs rattachés aux équipes
- **Gestion des coachs** — encadrement des équipes par les entraîneurs
- **Gestion des matchs** — calendrier, résultats, et convocation des joueurs pour chaque match
- **Administration** — gestion des accès et des comptes administrateurs
- **Notification des joueurs** — information des joueurs convoqués (email et/ou notification téléphone)

## Architecture

Le projet suit une architecture **DDD organisée par contexte métier** (vertical slicing), où chaque domaine possède ses propres couches :

```
src/
├── Admin/
│   ├── Domain/          # Entités, Value Objects, règles métier, interfaces de Repository
│   ├── Application/     # Use cases (Command + Handler)
│   └── Infrastructure/  # Controllers, implémentation Doctrine des Repository
├── Coach/
├── Match/
├── Player/
└── Team/
```

Chaque domaine respecte le principe d'inversion de dépendance : la couche `Domain` ne dépend d'aucun framework ni de Doctrine, l'`Infrastructure` implémente les interfaces définies par le `Domain`.

## Stack technique

- **PHP 8.4+**
- **Symfony 8.1**
- **Doctrine ORM**
- **MySQL 8.0**
- **Docker / Docker Compose** — environnement de développement entièrement conteneurisé

## Installation

```bash
docker compose up -d --build
docker compose exec php composer install
docker compose exec php php bin/console doctrine:database:create
docker compose exec php php bin/console doctrine:migrations:migrate
```

## Statut

Projet en cours d'apprentissage — la structure évolue au fil de l'avancement et des essais autour des concepts DDD.
