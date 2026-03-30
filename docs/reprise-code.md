# Guide de reprise de code

## 1) Objectif de ce document
Ce guide explique rapidement:
- comment demarrer le projet localement
- comment est organisee l'architecture
- ou modifier le code selon le besoin
- comment diagnostiquer les pannes frequentes

Le but est de permettre une reprise en moins de 30 minutes.

## 2) Vue d'ensemble de l'architecture
Le projet est une application PHP servie par Apache dans Docker, avec MySQL comme base de donnees.

Composants:
- Service web: conteneur PHP 8.2 Apache
- Service base de donnees: conteneur MySQL 8.0
- Rewriting URL: gere par Apache via .htaccess
- Acces DB: couche PDO factorisee dans config et includes

## 3) Structure des dossiers importants
- Docker et infra
  - Dockerfile: image web basee sur php:8.2-apache
  - docker-compose.yml: orchestration web + db, volumes, variables env
  - docker/apache/000-default.conf: config Apache (AllowOverride All)
  - docker/mysql/init/01_schema.sql: schema initial + donnees seed
  - .env: variables de connexion MySQL

- Backend PHP
  - config/database.php: fonctions de connexion PDO (envValue, dbConnection)
  - includes/function.php: fonctions mutualisees metier (articles, images, users)
  - pages/modules.php: endpoint principal alimente par rewriting
  - pages/db-test.php: page de diagnostic de connexion DB

- Frontend/templates
  - template/: pages HTML/CSS/JS statiques existantes

- Routing
  - .htaccess: regles de rewriting actives

## 4) Flux d'une requete rewrite
Exemple URL:
- /pages/article-1.html
- /news-1-tech.html

Chemin d'execution:
1. Apache recoit l'URL
2. .htaccess applique RewriteRule vers pages/modules.php
3. modules.php lit id/idcat depuis GET
4. modules.php appelle les fonctions de includes/function.php
5. includes/function.php utilise dbConnection() de config/database.php puis execute les requetes
6. Requete SQL sur MySQL puis rendu HTML

## 5) Demarrage local (sans telechargement d'images)
Prerequis:
- images locales presentes: php:8.2-apache, mysql:8.0

Commandes:
1. Verifier les images
   - docker images --format "{{.Repository}}:{{.Tag}}"
2. Build sans pull
   - docker compose build --pull=false
3. Start sans pull
   - docker compose up -d --pull never
4. Verifier l'etat
   - docker compose ps

Arret:
- docker compose down

## 6) URLs de verification rapide
- Application: http://localhost:8080
- Test connexion DB: http://localhost:8080/pages/db-test.php
- Rewrite 1: http://localhost:8080/essai.html
- Rewrite 2: http://localhost:8080/pages/article-1.html
- Rewrite 3: http://localhost:8080/news-1-tech.html

Resultat attendu pour db-test:
- Database status: ok
- MySQL version visible
- compteur d'articles > 0
- compteur d'images > 0
- compteur d'utilisateurs > 0

## 7) Ou coder selon le besoin
- Changer la connexion DB ou options PDO:
  - config/database.php

- Ajouter des requetes SQL metier:
  - includes/function.php

- Modifier le rendu de la page article dynamique:
  - pages/modules.php

- Ajouter des regles de rewriting:
  - .htaccess

- Modifier schema/tables seed:
  - docker/mysql/init/01_schema.sql
  - Attention: ces scripts sont joues a l'initialisation du volume MySQL.

Schema simplifie actuel:
- categories: id, name
- articles: id, category_id, body (HTML)
- images: id, article_id, image_url, alt_text, sort_order
- users: id, username, password (texte brut, mode prototype)

## 8) Convention de factorisation appliquee
Factorisation minimale mise en place:
- config/: configuration technique
- includes/: fonctions reutilisables (repositories)
- pages/: points d'entree web
- docker/: infra conteneur

Principe:
- Eviter les requetes SQL directement dans les pages
- Garder la connexion DB dans config/database.php
- Centraliser les fonctions metier SQL dans includes/function.php
- Garder modules.php comme orchestration + rendu

Note prototype:
- Les mots de passe utilisateurs sont en texte brut pour aller plus vite.
- Cette approche ne doit pas etre conservee en production.

## 9) Reinitialiser la base proprement
Si vous modifiez le schema SQL et voulez recharger depuis zero:
1. docker compose down -v
2. docker compose up -d --build --pull never

Le flag -v supprime le volume MySQL pour rejouer 01_schema.sql.

## 10) Runbook de diagnostic
Probleme: DB unhealthy
- verifier logs DB: docker compose logs db --tail 200
- verifier creds .env
- verifier que mysql:8.0 est disponible localement

Probleme: rewrite ne marche pas
- verifier .htaccess
- verifier AllowOverride All dans docker/apache/000-default.conf
- verifier module rewrite actif dans image web

Probleme: changements non visibles
- verifier volume bind dans docker-compose.yml: ./:/var/www/html
- verifier que les fichiers modifies sont dans le dossier projet monte

## 11) Limites actuelles et prochaines etapes
Limites:
- modules.php contient encore du HTML inline
- pas de routeur centralise
- pas de tests automatises

Prochaines etapes recommandees:
1. Extraire le rendu HTML dans des templates PHP dedies
2. Ajouter un routeur simple pour separer controleur et vue
3. Ajouter des tests d'integration pour rewrite + DB
4. Ajouter des migrations SQL versionnees
