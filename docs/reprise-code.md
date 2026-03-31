# Guide de reprise de code

## 1) Objectif de ce document
Ce guide explique rapidement:
- comment démarrer le projet localement avec Docker
- comment est organisée l'architecture
- où modifier le code selon le besoin
- comment diagnostiquer les pannes fréquentes

Le but est de permettre une reprise en moins de 15 minutes avec **une seule commande Docker**.

## 2) Vue d'ensemble de l'architecture
Le projet est une application PHP servie par Apache dans Docker, avec MySQL comme base de données.

**Stack technique:**
- **PHP**: 8.2 avec Apache
- **Base de données**: MySQL 8.0
- **Conteneurisation**: Docker + Docker Compose
- **URL Rewriting**: Apache mod_rewrite via .htaccess
- **ORM**: PDO natif (pas de framework externe)

**Architecture applicative:**
```
projet-web-seo/
├── config/            # Configuration DB et connexions
├── includes/          # Fonctions métier réutilisables
├── pages/            # Points d'entrée web (frontoffice, backoffice, modules)
├── assets/           # Ressources statiques (CSS, JS, images)
├── docker/           # Configuration infrastructure
│   ├── apache/       # Configuration Apache
│   └── mysql/init/   # Scripts SQL d'initialisation
├── docs/             # Documentation technique
├── .htaccess         # Règles de rewriting URL
├── docker-compose.yml # Orchestration des services
└── Dockerfile        # Image web PHP+Apache
```

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

## 5) Démarrage rapide (UNE SEULE COMMANDE)

### 🚀 Démarrage complet
```bash
docker compose up -d --build
```

Cette commande unique:
1. Build l'image web (PHP 8.2 + Apache + extensions)
2. Télécharge l'image MySQL 8.0 (si nécessaire)
3. Crée et démarre les deux conteneurs
4. Initialise la base de données avec le schéma et les données de seed
5. Attend que MySQL soit healthy avant de démarrer le web

### ⏸️ Arrêt
```bash
docker compose down
```

### 🗑️ Réinitialisation complète (supprime données)
Si vous voulez repartir de zéro (drop volumes + rebuild):
```bash
docker compose down -v
docker compose up -d --build
```

Le flag `-v` supprime les volumes MySQL pour rejouer les scripts d'initialisation.

### 🔍 Vérification de l'état
```bash
docker compose ps
docker compose logs web --tail 50
docker compose logs db --tail 50
```

### ⚙️ Mode développement (sans pull)
Si vous travaillez hors ligne avec les images déjà téléchargées:
```bash
docker compose build --pull=never
docker compose up -d --pull never
```

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

**Schéma de base de données actuel:**

```sql
-- Table categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table articles
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,        -- Ajouté dans 02_update_schema.sql
    author VARCHAR(150) NOT NULL,       -- Ajouté dans 02_update_schema.sql
    body TEXT NOT NULL,
    views INT DEFAULT 0,                -- Ajouté dans 02_update_schema.sql
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Table images
CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    sort_order INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);

-- Table users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,     -- ⚠️ Texte brut en prototype
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Données de seed (catégorie Géopolitique + 2 articles sur l'Iran):**
- Catégorie: Géopolitique (id=6)
- Article 1: "Tensions croissantes dans le Golfe"
- Article 2: "Analyse des cyber-conflits"
- 3 images associées aux articles
- User admin: admin/admin

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

## 9) Réinitialiser la base proprement
Si vous modifiez le schéma SQL et voulez recharger depuis zéro:
```bash
docker compose down -v
docker compose up -d --build
```

Le flag `-v` supprime le volume MySQL pour rejouer tous les scripts d'initialisation:
- `01_schema.sql`: Création des tables et données initiales
- `02_update_schema.sql`: Ajout des colonnes title, author, views

**⚠️ Attention**: Cette commande efface toutes les données de la base!

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
