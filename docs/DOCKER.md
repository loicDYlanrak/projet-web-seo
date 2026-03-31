# 📦 Guide Docker

## Architecture des conteneurs

Le projet utilise **Docker Compose** pour orchestrer 2 services:

```yaml
services:
  web:      # Serveur PHP 8.2 + Apache
  db:       # Base de données MySQL 8.0
```

### Service `web`
- **Image base**: `php:8.2-apache`
- **Extensions**: `pdo_mysql`, `mod_rewrite`
- **Port exposé**: 8080 → 80 (container)
- **Volume**: `./:/var/www/html` (live reload)
- **Configuration Apache**: `docker/apache/000-default.conf`

### Service `db`
- **Image**: `mysql:8.0`
- **Port exposé**: 3307 → 3306 (container)
- **Volume persistant**: `mysql_data` (données DB)
- **Scripts init**: `docker/mysql/init/` (exécutés au premier démarrage)
- **Healthcheck**: Ping MySQL toutes les 10s

## Fichiers Docker importants

### 1. `docker-compose.yml`
Orchestration des services, ports, volumes, variables d'environnement.

**Points clés:**
- `depends_on` avec `condition: service_healthy` garantit que MySQL est prêt avant le démarrage du web
- `pull_policy: never` évite le téléchargement automatique (mode hors ligne)
- `restart: unless-stopped` redémarre automatiquement les conteneurs

### 2. `Dockerfile`
Build de l'image web personnalisée.

```dockerfile
FROM php:8.2-apache

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite

# Copie de la config Apache
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
```

### 3. `.env`
Variables d'environnement pour MySQL.

```env
MYSQL_ROOT_PASSWORD=rootpassword
MYSQL_DATABASE=seo_db
MYSQL_USER=seo_user
MYSQL_PASSWORD=seo_password
```

**⚠️ Important**: Ne jamais commit ce fichier avec des vraies credentials en production!

### 4. `docker/apache/000-default.conf`
Configuration du VirtualHost Apache.

**Point critique**: `AllowOverride All` permet le fonctionnement du `.htaccess` pour l'URL rewriting.

### 5. `docker/mysql/init/`
Scripts SQL exécutés automatiquement lors de la **première initialisation** du conteneur MySQL.

**Ordre d'exécution** (alphabétique):
1. `01_schema.sql` - Création des tables + données seed
2. `02_update_schema.sql` - Ajout des colonnes title, author, views

**⚠️ Ces scripts ne sont exécutés qu'une seule fois**. Pour les rejouer, il faut supprimer le volume MySQL avec `docker compose down -v`.

## Commandes Docker utiles

### Démarrage et arrêt
```bash
# Démarrer (build + start)
docker compose up -d --build

# Démarrer sans rebuild
docker compose up -d

# Arrêter
docker compose down

# Arrêter + supprimer volumes
docker compose down -v

# Arrêter + supprimer images
docker compose down --rmi all
```

### Logs et debugging
```bash
# Voir les logs de tous les services
docker compose logs

# Logs en temps réel (follow)
docker compose logs -f

# Logs d'un service spécifique
docker compose logs web
docker compose logs db

# Dernières 50 lignes
docker compose logs web --tail 50

# État des conteneurs
docker compose ps

# Inspection détaillée
docker inspect projet-web-seo-web
docker inspect projet-web-seo-db
```

### Accès aux conteneurs
```bash
# Shell dans le conteneur web
docker compose exec web bash

# Shell dans le conteneur db (MySQL)
docker compose exec db bash

# MySQL CLI directement
docker compose exec db mysql -u seo_user -p seo_db
# Mot de passe: seo_password
```

### Rebuild et nettoyage
```bash
# Rebuild une image spécifique
docker compose build web

# Rebuild sans cache
docker compose build --no-cache web

# Supprimer les conteneurs arrêtés
docker container prune

# Supprimer les images non utilisées
docker image prune

# Supprimer les volumes non utilisés
docker volume prune

# Nettoyage complet (⚠️ attention!)
docker system prune -a
```

## Volumes

### Volume applicatif (bind mount)
```yaml
volumes:
  - ./:/var/www/html
```

Monte le dossier du projet dans le conteneur.
✅ **Avantage**: Modifications en temps réel sans rebuild.

### Volume MySQL (named volume)
```yaml
volumes:
  mysql_data:
```

Volume nommé persistant pour les données de la base.
✅ **Avantage**: Les données survivent aux redémarrages.
⚠️ **Attention**: Supprimer ce volume = perdre toutes les données!

## Réseau Docker

Docker Compose crée automatiquement un réseau bridge pour permettre la communication entre conteneurs.

**Résolution DNS interne:**
- Le conteneur `web` peut accéder à `db` via le hostname `db`
- Le conteneur `db` peut accéder à `web` via le hostname `web`

C'est pourquoi dans `config/database.php`, on utilise:
```php
$host = envValue('DB_HOST', 'db');  // 'db' résout vers le conteneur MySQL
```

## Healthcheck MySQL

Le service `db` inclut un healthcheck pour garantir que MySQL est prêt avant de lancer le web.

```yaml
healthcheck:
  test: ["CMD-SHELL", "mysqladmin ping -h localhost -uroot -p$$MYSQL_ROOT_PASSWORD --silent"]
  interval: 10s
  timeout: 5s
  retries: 10
  start_period: 60s
```

**Fonctionnement:**
- Teste la connexion MySQL toutes les 10 secondes
- Maximum 10 tentatives
- Période de démarrage de 60 secondes
- Le service `web` attend que le healthcheck passe à `healthy`

## Mode hors ligne (pull_policy: never)

Pour travailler sans connexion Internet:

```yaml
pull_policy: never
```

Cette directive empêche Docker de tenter de télécharger les images.

**Condition**: Les images doivent déjà être présentes localement.

Vérifier les images disponibles:
```bash
docker images | grep -E "php:8.2-apache|mysql:8.0"
```

## Ports exposés

| Service | Port Host | Port Container | URL |
|---------|-----------|----------------|-----|
| web | 8080 | 80 | http://localhost:8080 |
| db | 3307 | 3306 | localhost:3307 (client SQL externe) |

**Note**: Le port 3307 est utilisé pour éviter les conflits avec un éventuel MySQL local sur le port standard 3306.

## Déploiement sur une autre machine

### Option 1: Avec accès Internet (recommandé)
```bash
git clone <repo>
cd projet-web-seo
docker compose up -d --build
```

Docker téléchargera automatiquement les images nécessaires.

### Option 2: Sans accès Internet
1. Exporter les images sur la machine source:
   ```bash
   docker save php:8.2-apache -o php-apache.tar
   docker save mysql:8.0 -o mysql.tar
   ```

2. Transférer les fichiers `.tar` vers la machine cible

3. Importer les images sur la machine cible:
   ```bash
   docker load -i php-apache.tar
   docker load -i mysql.tar
   ```

4. Lancer le projet:
   ```bash
   docker compose up -d --build
   ```

## Bonnes pratiques

### ✅ À faire
- Toujours utiliser `docker compose down` avant de faire des modifications à `docker-compose.yml`
- Vérifier les logs en cas de problème: `docker compose logs`
- Utiliser des volumes nommés pour les données importantes (MySQL)
- Définir des healthchecks pour les services critiques
- Utiliser `.env` pour les variables sensibles (et ne pas commit ce fichier)

### ❌ À éviter
- Ne jamais supprimer les volumes sans backup: `docker compose down -v`
- Ne pas hardcoder les credentials dans `docker-compose.yml`
- Ne pas utiliser le tag `latest` pour les images en production
- Ne pas exposer le port MySQL (3306) en production sans firewall
- Ne pas stocker les mots de passe en clair (table `users` actuelle)

## Debugging courant

### Le conteneur web redémarre en boucle
```bash
docker compose logs web
```
Vérifier les erreurs PHP ou Apache.

### Le conteneur db n'est jamais "healthy"
```bash
docker compose logs db
```
Vérifier que MySQL démarre correctement et que les credentials sont corrects.

### Les modifications PHP ne sont pas prises en compte
Vérifier le volume mount:
```bash
docker compose exec web ls -la /var/www/html
```

### Erreur "port already in use"
Changer les ports dans `docker-compose.yml` ou arrêter le service qui utilise le port:
```bash
# Windows
netstat -ano | findstr :8080
taskkill /PID <PID> /F

# Linux/Mac
lsof -i :8080
kill -9 <PID>
```

### Réinitialisation complète ne fonctionne pas
```bash
docker compose down -v
docker system prune -f
docker compose up -d --build --force-recreate
```

## Sécurité

### ⚠️ Points de vigilance actuels (mode prototype)
1. Mots de passe en clair dans la table `users`
2. Credentials MySQL simples dans `.env`
3. Port MySQL exposé (3307)
4. Pas de chiffrement TLS/SSL
5. AllowOverride All (impact performance)

### 🔒 Recommandations pour la production
1. Utiliser `password_hash()` et `password_verify()` pour les users
2. Utiliser Docker Secrets ou un vault pour les credentials
3. Ne pas exposer le port MySQL (supprimer `ports:` du service db)
4. Activer TLS avec Let's Encrypt
5. Limiter AllowOverride aux directives nécessaires
6. Utiliser un reverse proxy (Nginx) devant Apache
7. Implémenter rate limiting et WAF
