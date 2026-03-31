# 🚀 Démarrage Rapide

## Prérequis
- Docker Desktop installé et démarré
- Git (pour cloner le projet)

## Démarrage en 3 étapes

### 1️⃣ Cloner le projet
```bash
git clone <url-du-repo>
cd projet-web-seo
```

### 2️⃣ Lancer Docker (UNE SEULE COMMANDE)
```bash
docker compose up -d --build
```

⏱️ **Temps estimé**: 2-3 minutes (première fois avec téléchargement des images)

Cette commande:
- ✅ Build l'image web PHP 8.2 + Apache
- ✅ Télécharge MySQL 8.0
- ✅ Configure les volumes et réseaux
- ✅ Initialise la base de données avec schéma et données
- ✅ Démarre les services

### 3️⃣ Vérifier que tout fonctionne
```bash
docker compose ps
```

Vous devriez voir:
```
NAME                      STATUS         PORTS
projet-web-seo-web        Up             0.0.0.0:8080->80/tcp
projet-web-seo-db         Up (healthy)   0.0.0.0:3307->3306/tcp
```

## 🔗 URLs à tester

| URL | Description |
|-----|-------------|
| http://localhost:8080 | Page d'accueil (redirige vers frontoffice) |
| http://localhost:8080/pages/db-test.php | Test de connexion à la base de données |
| http://localhost:8080/pages/frontoffice/ | Interface publique |
| http://localhost:8080/pages/backoffice/ | Interface d'administration |
| http://localhost:8080/pages/article-1.html | Article via URL rewriting |
| http://localhost:8080/news-1-tech.html | Article avec catégorie via rewriting |

## ✅ Vérification de la DB

Ouvrez: http://localhost:8080/pages/db-test.php

Vous devriez voir:
```
Database status: ok
MySQL version: 8.0.x
Articles in table: 2
Images in table: 3
Users in table: 1
```

## 🛑 Arrêt du projet

```bash
docker compose down
```

## 🔄 Redémarrage (sans rebuild)

```bash
docker compose up -d
```

## 🗑️ Réinitialisation complète

Pour supprimer toutes les données et repartir de zéro:
```bash
docker compose down -v
docker compose up -d --build
```

## 📝 Modifications en direct

Tous les fichiers PHP/HTML/CSS/JS sont montés en volume live.
**Aucun redémarrage n'est nécessaire** pour voir vos changements!

Modifiez simplement vos fichiers et rechargez la page dans le navigateur.

## 🐛 Problèmes courants

### Le conteneur web ne démarre pas
```bash
docker compose logs web
```

### La base de données ne répond pas
```bash
docker compose logs db
```

### Port 8080 déjà utilisé
Changez le port dans `docker-compose.yml`:
```yaml
ports:
  - "8081:80"  # Au lieu de 8080:80
```

### Réinitialiser sans supprimer les images Docker
```bash
docker compose down
docker system prune -f
docker compose up -d --build
```

## 📚 Documentation complète

- [Guide de reprise technique](./reprise-code.md) - Architecture et organisation du code
- [README.md](../README.md) - Vue d'ensemble du projet
- [Features frontoffice/backoffice](./frontoffice_backoffice_features.md) - Fonctionnalités implémentées
