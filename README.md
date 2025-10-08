# README — site-web (Symfony + Docker)

Ce document explique comment **télécharger le projet depuis Git**, installer et lancer l'environnement Docker sous Windows, et exécuter les commandes nécessaires pour entrer dans les conteneurs et lancer des tâches (comme un cron).

---

## 1) Prérequis

* Git
* Docker Desktop pour Windows 11

> ⚠️ Sous Git Bash/MinTTY, certaines commandes Docker interactives nécessitent `winpty`.

---

## 2) Télécharger le projet

Ouvre ton terminal et exécute :

```bash
# Cloner le dépôt Git
git clone https://github.com/jeremy-desperrier/site-web.git site-web
cd site-web
```

---

## 3) Lancer l'environnement Docker

### Commande pour Windows PowerShell / CMD :

```powershell
docker-compose up --build
```

### Commande pour Git Bash / MinTTY :

```bash
winpty docker-compose up --build
```

> Utilise `-d` pour lancer les conteneurs en arrière-plan : `docker-compose up -d --build`

Cette commande démarre les services définis dans `docker-compose.yml` :

* `app` → Symfony / PHP
* `db` → MySQL

---

## 4) Entrer dans le conteneur Docker

### Conteneur Symfony (app)

* term :

```powershell
docker exec -it symfony_app bash
```

* Git Bash / MinTTY :

```bash
winpty docker exec -it symfony_app bash
```

### Conteneur MySQL (db)

* term :

```powershell
docker exec -it symfony_db bash
```

* Git Bash / MinTTY :

```bash
winpty docker exec -it symfony_db bash
```

---

## 5) Créer la base de donnée avant de s'avanturer sur le site

# Créer la base (si elle n’existe pas)
```bash
php bin/console doctrine:database:create
```
# Appliquer les migrations
```bash
php bin/console doctrine:migrations:migrate
```

---

## 6) Lancer la commande cron pour récuper le nombre d'utilisateur connecter aujourd'hui

Depuis le conteneur Symfony (`symfony_app`) :

```bash
# Exemple pour lancer un cron/script Symfony
php bin/console command:app:check-daily-connected-users
```

