# Todo List - Mini-projet Web Design (Mars 2026)

## Répartition des rôles

| Personne | Rôle principal |
|----------|----------------|
| **Toky** | Base de données, Backoffice, Docker, Documentation technique |
| **Loic** | Frontoffice, Intégration HTML/CSS, SEO, Tests Lighthouse |

---

## 1. Initialisation et environnement Docker

### Toky
- [ ] Créer un `Dockerfile` pour l’environnement de développement (PHP, Apache, ou autre selon le langage choisi)
- [ ] Créer un `docker-compose.yml` avec :
  - Service web (PHP/Apache ou Node.js)
  - Service base de données (MySQL ou PostgreSQL)
  - Service phpMyAdmin (si nécessaire)
- [ ] Tester le fonctionnement des conteneurs
- [ ] S’assurer que le projet existant est bien monté dans le conteneur
- [ ] Documenter les commandes Docker dans le fichier technique

---

## 2. Modélisation et création de la base de données

### Toky
- [ ] Analyser les contenus à afficher (articles, catégories, images, dates, etc.)
- [ ] Créer le MCD/MLD (modèle conceptuel/logique)
- [ ] Générer le script SQL de création des tables :
  - Table `users` (login, mot de passe hashé)
  - Table `contents` (id, titre, contenu, image, date, catégorie, statut, etc.)
  - Table `categories` (optionnel)
- [ ] Ajouter un utilisateur par défaut pour le backoffice (user/pass)
- [ ] Ajouter des données de test (contenus factices)
- [ ] Documenter la modélisation avec captures dans le fichier technique

---

## 3. Frontoffice (FO)

### Loic
- [X] Créer la structure HTML/CSS du site d’information (thème : guerre en Iran)
- [ ] Respecter la hiérarchie des titres (`h1`, `h2`, ...)
- [ ] Afficher les contenus depuis la base de données
- [ ] Intégrer les images avec attribut `alt`
- [ ] Mettre en place l’URL rewriting (ex. `/article/1/titre-article`)
- [ ] Créer un fichier `.htaccess` ou équivalent pour les routes
- [ ] S’assurer que les balises méta (description, keywords, viewport) sont présentes
- [ ] Ajouter une pagination ou une page d’accueil avec articles récents

---

## 4. Backoffice (BO)

### Toky
- [ ] Créer une interface de connexion sécurisée
- [ ] Utiliser le login/mot de passe par défaut défini dans la base
- [ ] Créer un tableau de bord pour gérer les contenus :
  - Ajouter un article
  - Modifier un article
  - Supprimer un article
  - Gérer les images
- [ ] Appliquer l’URL rewriting pour les pages du BO
- [ ] Vérifier que les droits d’accès sont bien restreints

---

## 5. Optimisation SEO et tests Lighthouse

### Loic
- [ ] Vérifier et corriger la structure des titres (`h1` unique, hiérarchie)
- [ ] Ajouter les balises méta dynamiques (titre, description)
- [ ] Vérifier les attributs `alt` de toutes les images
- [ ] Générer un rapport Lighthouse (mobile et desktop) avant correction
- [ ] Optimiser les performances (images, CSS/JS minifiés, cache)
- [ ] Générer un second rapport Lighthouse après corrections
- [ ] Capturer les résultats pour la documentation

---

## 6. Documentation technique finale

### Toky (avec contribution de B)
- [ ] Rédiger la documentation technique au format demandé :
  - Captures d’écran du Frontoffice
  - Captures d’écran du Backoffice (avec login par défaut)
  - Modélisation de la base de données (MCD/MLD ou capture SQL)
  - Numéros d’étudiants des deux membres
  - Explication de l’URL rewriting
  - Commande Docker pour lancer le projet
- [ ] Vérifier que tout est cohérent et complet

---

## 7. Livraison

### Toky
- [ ] Générer un fichier `.zip` contenant tout le projet fonctionnel avec Docker
- [ ] Pousser le projet sur un dépôt public (GitHub ou GitLab)
- [ ] Préparer le lien du dépôt pour le formulaire
- [ ] Déposer le `.zip` et la documentation sur Forms avant le **mardi 31 mars à 14h00**

---

## À faire ensemble (vérifications finales)

- [ ] Vérifier que le projet fonctionne avec `docker-compose up`
- [ ] Tester l’URL rewriting sur toutes les pages
- [ ] Tester la connexion au backoffice avec les identifiants par défaut
- [ ] Faire un test Lighthouse final
- [ ] Relire la documentation technique