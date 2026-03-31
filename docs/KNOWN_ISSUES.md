# ⚠️ Problèmes Connus

## 1. ❌ Erreur SQL - Script d'initialisation

### Symptôme
Le conteneur MySQL démarre mais échoue avec l'erreur:
```
ERROR 1064 (42000) at line 2: You have an error in your SQL syntax
```

### Cause
Le fichier `docker/mysql/init/02_update_schema.sql` utilise la syntaxe `ADD COLUMN IF NOT EXISTS` qui **n'est pas supportée par MySQL 8.0**.

```sql
-- ❌ NE FONCTIONNE PAS dans MySQL 8.0
ALTER TABLE articles 
ADD COLUMN IF NOT EXISTS title VARCHAR(255) NOT NULL AFTER category_id,
ADD COLUMN IF NOT EXISTS author VARCHAR(150) NOT NULL AFTER title,
ADD COLUMN IF NOT EXISTS views INT DEFAULT 0 AFTER body;
```

### Solution temporaire

**Option 1**: Supprimer `02_update_schema.sql` et intégrer les colonnes directement dans `01_schema.sql`

**Option 2**: Utiliser une syntaxe conditionnelle avec des procédures stockées

**Option 3**: Ignorer l'erreur si les colonnes existent déjà (accepter que le script échoue en cas de re-run)

### Fix recommandé

Modifier `docker/mysql/init/02_update_schema.sql`:

```sql
-- Solution 1: Sans IF NOT EXISTS (échoue au second run mais OK pour init)
ALTER TABLE articles 
ADD COLUMN title VARCHAR(255) NOT NULL DEFAULT '' AFTER category_id,
ADD COLUMN author VARCHAR(150) NOT NULL DEFAULT 'Admin' AFTER title,
ADD COLUMN views INT DEFAULT 0 AFTER body;

UPDATE articles SET title = CONCAT('Article ', id) WHERE title = '';
UPDATE articles SET author = 'Admin' WHERE author = '';

UPDATE articles SET title = "Tensions croissantes dans le Golfe" WHERE id = 1;
UPDATE articles SET title = "Analyse des cyber-conflits" WHERE id = 2;
```

Ou mieux:

```sql
-- Solution 2: Intégrer directement dans 01_schema.sql
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

### Workaround actuel

Pour l'instant, le schéma initial dans `01_schema.sql` ne contient pas les colonnes `title`, `author`, `views`.
Ces colonnes doivent être ajoutées manuellement ou le script `02_update_schema.sql` doit être corrigé.

### Impact

- ⚠️ Le conteneur DB démarre avec erreur mais finit par devenir "healthy"
- ⚠️ Les colonnes title, author, views peuvent ne pas être créées
- ⚠️ L'application peut échouer si elle attend ces colonnes

---

## 2. ❌ Module Apache headers non activé

### Symptôme
Erreur 500 sur toutes les pages avec message dans les logs:
```
Invalid command 'Header', perhaps misspelled or defined by a module not included
```

### Cause
Le fichier `.htaccess` utilise la directive `Header` du module `mod_headers` qui n'est **pas activé** dans le Dockerfile.

```apache
# Ligne dans .htaccess
Header unset ETag
Header unset Pragma
Header set Cache-Control "public, max-age=..."
```

### Solution

Ajouter dans `Dockerfile`:
```dockerfile
FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && a2enmod headers    # ← Ajouter cette ligne

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
```

### Workaround temporaire

Commenter les directives `Header` dans `.htaccess` ou accéder au conteneur et activer le module:
```bash
docker compose exec web bash
a2enmod headers
apache2ctl restart
```

### Impact

- 🔴 **BLOQUANT** - L'application retourne 500 sur toutes les pages
- ❌ Aucune page n'est accessible

---

## 3. ⚠️ Sécurité - Mots de passe en clair

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
