# Projet Bibliotheque - Installation & Lancement

## Prerequis
- PHP 8.2+
- Composer
- Symfony CLI (optionnel mais recommande)
- Node.js 18+ et npm
- MySQL/MariaDB

## Base de donnees
Le dump de recette est fourni a la racine : `dump_recette.sql`.

Commandes (exemple Windows/PowerShell) :
1. Creer la base si besoin :
```
mysql -u root -proot -e "CREATE DATABASE IF NOT EXISTS saebiblio;"
```
2. Importer le dump :
```
mysql -u root -proot saebiblio < dump_recette.sql
```

Si vous ne souhaitez pas utiliser le dump :
- `php bin/console doctrine:database:create`
- `php bin/console doctrine:migrations:migrate`
- `php bin/console doctrine:fixtures:load --purge-with-truncate`

## Backend (Symfony)
Dans `backend_biblio` :
1. Installer les dependances :
```
composer install
```
2. Verifier `DATABASE_URL` dans `backend_biblio/.env`.
3. Generer les cles JWT (Windows/PowerShell) :
```
$env:OPENSSL_CONF="C:\chemin\vers\backend_biblio\config\openssl.cnf"
php bin/console lexik:jwt:generate-keypair --overwrite
```
4. Lancer le serveur :
```
symfony server:start --port=8008
```

Commande utile (expiration des reservations J+7) :
```
php bin/console app:reservations:expire
```

## Frontend (Angular)
Dans `frontend_biblio` :
1. Installer les dependances :
```
npm install
```
2. Lancer le front :
```
npm run start
```

## URLs
- API : https://127.0.0.1:8008/api
- Front : http://localhost:4200

## Comptes de test (dump_recette.sql)
- admin@biblio.fr / admin (ROLE_ADMIN, ROLE_RESPONABLE, ROLE_BIBLIOTHECAIRE, ROLE_ADHERENT)
- alice.dupont@biblio.fr / password
- anthony.durand@biblio.fr / password
- andre.lefevre@biblio.fr / password
- bruno.martin@biblio.fr / password (ROLE_BIBLIOTHECAIRE)
- robert.bertrand@biblio.fr / password (ROLE_RESPONSABLE)
