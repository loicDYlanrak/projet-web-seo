# 🌐 Projet Web SEO

Application PHP moderne avec architecture orientée SEO, URL rewriting et gestion de contenu dynamique.

## 🚀 Démarrage Ultra-Rapide

**UNE SEULE COMMANDE:**
```bash
docker compose up -d --build
```

✅ C'est tout! L'application est accessible sur http://localhost:8080

## 📋 Prérequis

- Docker Desktop (Windows/Mac) ou Docker Engine + Docker Compose (Linux)
- Git

## 🏗️ Stack Technique

- **Backend**: PHP 8.2 avec Apache
- **Base de données**: MySQL 8.0
- **Conteneurisation**: Docker + Docker Compose
- **URL Rewriting**: Apache mod_rewrite
- **Accès DB**: PDO natif avec prepared statements

## 📂 Structure du projet

```
projet-web-seo/
├── config/                 # Configuration DB
│   └── database.php        # Connexion PDO
├── includes/               # Fonctions métier
│   └── function.php        # CRUD articles, users, images
├── pages/                  # Points d'entrée
│   ├── frontoffice/        # Interface publique
│   ├── backoffice/         # Interface admin
│   ├── modules.php         # Affichage articles dynamiques
│   └── db-test.php         # Test connexion DB
├── assets/                 # Ressources statiques
├── docker/                 # Configuration Docker
│   ├── apache/             # Config Apache
│   └── mysql/init/         # Scripts SQL init
├── docs/                   # Documentation
│   ├── QUICKSTART.md       # ⭐ Guide de démarrage
│   ├── DOCKER.md           # Guide Docker complet
│   ├── reprise-code.md     # Architecture technique
│   └── ...
├── .htaccess               # Règles URL rewriting
├── docker-compose.yml      # Orchestration services
├── Dockerfile              # Image web custom
└── .env                    # Variables d'environnement
```

## 🔗 URLs à tester

| URL | Description |
|-----|-------------|
| http://localhost:8080 | Page d'accueil |
| http://localhost:8080/pages/frontoffice/ | Interface publique |
| http://localhost:8080/pages/backoffice/ | Interface admin |
| http://localhost:8080/pages/db-test.php | ✅ Test connexion DB |
| http://localhost:8080/pages/article-1.html | Article via rewriting |
| http://localhost:8080/news-1-tech.html | Article avec catégorie |

## ✅ Vérification du démarrage

### 1. État des conteneurs
```bash
docker compose ps
```

Résultat attendu:
```
NAME                    STATUS         PORTS
projet-web-seo-web      Up             0.0.0.0:8080->80/tcp
projet-web-seo-db       Up (healthy)   0.0.0.0:3307->3306/tcp
```

### 2. Test de la base de données
Ouvrir: http://localhost:8080/pages/db-test.php

Résultat attendu:
```
Database status: ok
MySQL version: 8.0.x
Articles in table: 2
Images in table: 3
Users in table: 1
```

## 🔧 Commandes utiles

### Arrêter le projet
```bash
docker compose down
```

### Redémarrer (sans rebuild)
```bash
docker compose up -d
```

### Réinitialisation complète (⚠️ supprime les données)
```bash
docker compose down -v
docker compose up -d --build
```

### Voir les logs
```bash
docker compose logs -f web
docker compose logs -f db
```

## 🛠️ Développement

### Modifications en direct (Hot Reload)
Tous les fichiers sont montés en volume live: `./:/var/www/html`

**Aucun redémarrage nécessaire!** Modifiez vos fichiers PHP/HTML/CSS/JS et rechargez simplement la page.

### Accès au conteneur
```bash
# Shell dans le conteneur web
docker compose exec web bash

# MySQL CLI
docker compose exec db mysql -u seo_user -p seo_db
# Password: seo_password
```

### Structure de la base de données
- **categories**: Catégories d'articles
- **articles**: Articles avec title, author, body (HTML), views
- **images**: Images liées aux articles (sort_order)
- **users**: Utilisateurs (⚠️ passwords en clair en mode prototype)

## 📚 Documentation

### Guides de démarrage
- **[QUICKSTART.md](docs/QUICKSTART.md)** ⭐ - Guide de démarrage complet
- **[DOCKER.md](docs/DOCKER.md)** - Guide Docker détaillé

### Documentation technique
- **[reprise-code.md](docs/reprise-code.md)** - Architecture et organisation
- **[technical_documentation.md](docs/technical_documentation.md)** - Doc technique complète
- **[frontoffice_backoffice_features.md](docs/frontoffice_backoffice_features.md)** - Fonctionnalités

### Configuration
- **[config/database.php](config/database.php)** - Connexion PDO
- **[includes/function.php](includes/function.php)** - Fonctions CRUD

## 🎯 Fonctionnalités principales

### URL Rewriting (SEO-friendly)
- ✅ `/pages/article-{id}.html` → Affichage article
- ✅ `/news-{id}-{category}.html` → Article avec catégorie
- ✅ Compression gzip activée
- ✅ Cache headers optimisés
- ✅ Support WebP, SVG, fonts modernes

### Gestion de contenu
- ✅ CRUD articles avec éditeur TinyMCE
- ✅ Gestion multi-images par article
- ✅ Catégorisation des articles
- ✅ Upload d'images avec validation
- ✅ Extraction automatique du titre depuis H1

### Architecture propre
- ✅ Séparation config/includes/pages
- ✅ PDO avec prepared statements
- ✅ Fonctions réutilisables
- ✅ Factorisation du code métier

## 🐛 Dépannage

### Les conteneurs ne démarrent pas
```bash
docker compose logs web
docker compose logs db
```

### Port 8080 déjà utilisé
Modifier dans `docker-compose.yml`:
```yaml
ports:
  - "8081:80"  # Utiliser 8081 au lieu de 8080
```

### Base de données non initialisée
```bash
docker compose down -v
docker compose up -d --build
```

### Réinitialisation complète
```bash
docker compose down -v
docker system prune -f
docker compose up -d --build
```

## ⚠️ Notes importantes

### Mode prototype
- Les mots de passe utilisateurs sont en **texte brut** (table `users`)
- À remplacer par `password_hash()` en production

### Sécurité
- Le fichier `.env` contient les credentials MySQL
- Ne **jamais** commit ce fichier avec des vraies credentials
- En production: utiliser Docker Secrets ou un vault

### Performance
- Le volume bind mount (`./:/var/www/html`) est pratique en dev mais lent en production
- En production: copier les fichiers dans l'image Docker

## 🚀 Déploiement sur une autre machine

```bash
git clone <repository-url>
cd projet-web-seo
docker compose up -d --build
```

C'est tout! Docker téléchargera automatiquement les images nécessaires.

## 📝 Variables d'environnement (.env)

```env
MYSQL_ROOT_PASSWORD=rootpassword
MYSQL_DATABASE=seo_db
MYSQL_USER=seo_user
MYSQL_PASSWORD=seo_password
```

Ces variables sont automatiquement injectées dans les conteneurs.

## 📞 Support

Pour toute question sur:
- **Architecture**: Voir [docs/reprise-code.md](docs/reprise-code.md)
- **Docker**: Voir [docs/DOCKER.md](docs/DOCKER.md)
- **Démarrage**: Voir [docs/QUICKSTART.md](docs/QUICKSTART.md)

## 📄 Licence

Projet éducatif - SEO Web Application

