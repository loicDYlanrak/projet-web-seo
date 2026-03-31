# ⚠️ Problèmes Connus

## ✅ Problèmes Résolus (2026-03-31)

### 1. ~~❌ Module Apache headers non activé~~ → ✅ **RÉSOLU**

**Symptôme**: Erreur 500 sur toutes les pages avec message dans les logs:
```
Invalid command 'Header', perhaps misspelled or defined by a module not included
```

**Cause**: Le fichier `.htaccess` utilisait la directive `Header` du module `mod_headers` qui n'était **pas activé** dans le Dockerfile.

**Solution Appliquée**: 
Ajouté `a2enmod headers` dans `Dockerfile`:
```dockerfile
FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && a2enmod headers    # ← Ajouté

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
```

**Status**: ✅ **RÉSOLU** - Application accessible, aucune erreur 500

---

### 2. ~~❌ Erreur SQL - Script d'initialisation~~ → ✅ **RÉSOLU**

**Symptôme**: Le conteneur MySQL démarrait mais échouait avec l'erreur:
```
ERROR 1064 (42000) at line 2: You have an error in your SQL syntax
```

**Cause**: Le fichier `docker/mysql/init/02_update_schema.sql` utilisait la syntaxe `ADD COLUMN IF NOT EXISTS` qui **n'est pas supportée par MySQL 8.0**.

**Solution Appliquée**:
1. Supprimé `02_update_schema.sql`
2. Intégré les colonnes `title`, `author`, `views` directement dans `01_schema.sql`:

```sql
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL DEFAULT '',
    author VARCHAR(150) NOT NULL DEFAULT 'Admin',
    body TEXT NOT NULL,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_articles_category FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

**Status**: ✅ **RÉSOLU** - Base initialisée correctement, toutes les colonnes présentes

---

## 🟡 Points de Vigilance (Acceptable en Dev)

### 3. ⚠️ Sécurité - Mots de passe en clair

### Symptôme
Les mots de passe utilisateurs sont stockés en texte brut dans la table `users`.

```sql
INSERT INTO users (id, username, password) VALUES
    (1, 'admin', 'admin');  -- ⚠️ Mot de passe en clair
```

### Cause
Mode prototype pour aller vite. La fonction `verifyUserCredentials()` compare directement les strings:
```php
return (string) $user['password'] == $plainPassword;
```

### Solution

1. Modifier `01_schema.sql` pour hasher les mots de passe:
```sql
-- Utiliser PASSWORD() ou générer hash en PHP
INSERT INTO users (id, username, password) VALUES
    (1, 'admin', '$2y$10$...');  -- Hash bcrypt
```

2. Modifier `includes/function.php`:
```php
function verifyUserCredentials(PDO $pdo, string $username, string $plainPassword): bool
{
    $user = findUserByUsername($pdo, $username);
    if ($user === null) {
        return false;
    }
    
    return password_verify($plainPassword, $user['password']);
}
```

### Impact

- 🟡 **SÉCURITÉ CRITIQUE** - Ne pas utiliser en production
- ⚠️ Tous les mots de passe sont visibles en clair dans la DB

---

## 4. ℹ️ Port MySQL exposé

### Description
Le port MySQL (3307) est exposé sur l'hôte.

```yaml
db:
  ports:
    - "3307:3306"
```

### Recommandation

En production, ne pas exposer le port MySQL. Supprimer la directive `ports:` du service `db` dans `docker-compose.yml`.

Le service web peut toujours accéder à MySQL via le réseau Docker interne (hostname `db`).

### Impact

- 🟡 **SÉCURITÉ FAIBLE** - Permet connexion externe à MySQL
- ℹ️ Utile en développement pour outils externes (MySQL Workbench, etc.)

---

## 5. ℹ️ Variables d'environnement en clair

### Description
Le fichier `.env` contient les credentials MySQL en clair:
```env
MYSQL_ROOT_PASSWORD=rootpassword
MYSQL_USER=seo_user
MYSQL_PASSWORD=seo_password
```

### Recommandation

- Ajouter `.env` dans `.gitignore`
- Créer `.env.example` avec valeurs factices
- En production: utiliser Docker Secrets ou un vault (HashiCorp Vault, AWS Secrets Manager)

### Impact

- 🟡 **RISQUE DE FUITE** - Si commit dans Git
- ℹ️ Acceptable en développement local

---

## 📊 Résumé des Priorités

| Problème | Priorité | Impact | Status |
|----------|----------|--------|--------|
| Module headers manquant | 🔴 **P0** | Bloquant - App inaccessible | À corriger |
| Erreur SQL `IF NOT EXISTS` | 🟠 **P1** | Dégradé - Colonnes manquantes | À corriger |
| Mots de passe en clair | 🟡 **P2** | Sécurité critique | Documenté |
| Port MySQL exposé | 🟢 **P3** | Sécurité faible | Acceptable dev |
| `.env` en clair | 🟢 **P3** | Risque de fuite | Acceptable dev |

---

## 🔧 Quick Fix - Démarrage Temporaire

Pour faire fonctionner l'application immédiatement:

```bash
# 1. Activer le module headers
docker compose exec web a2enmod headers
docker compose exec web apache2ctl restart

# 2. Tester
curl http://localhost:8080/pages/db-test.php
```

Ou mieux, corriger le `Dockerfile` et rebuild:
```bash
# Ajouter a2enmod headers dans Dockerfile
docker compose down
docker compose up -d --build
```
