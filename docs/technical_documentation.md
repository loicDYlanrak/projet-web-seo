# Documentation Technique du Projet Web SEO

## Introduction
Ce document technique fournit une vue d'ensemble complète du projet Web SEO. Il explique la structure des fichiers, les fonctionnalités principales et les configurations nécessaires pour comprendre et maintenir le projet.

---

## Structure du Projet
Voici une description des principaux dossiers et fichiers du projet :

### Racine du Projet
- **docker-compose.yml** : Fichier de configuration pour orchestrer les services Docker (Apache, MySQL, etc.).
- **Dockerfile** : Définit l'image Docker utilisée pour le projet.
- **index.php** : Point d'entrée principal de l'application.
- **README.md** : Documentation générale du projet.
- **sample-rewriting.txt** : Exemple de règles de réécriture pour le serveur web.
- **todo.md** : Liste des tâches à effectuer pour le projet.

### Dossier `assets`
- **image/** : Contient les ressources graphiques (images).
- **js/** : Contient les fichiers JavaScript pour les fonctionnalités front-end.

### Dossier `config`
- **database.php** : Fichier de configuration pour la connexion à la base de données.

### Dossier `docker`
- **apache/000-default.conf** : Configuration Apache pour le serveur web.
- **mysql/init/** :
  - **01_schema.sql** : Script SQL pour créer le schéma initial de la base de données.
  - **02_update_schema.sql** : Script SQL pour mettre à jour le schéma de la base de données.

### Dossier `docs`
- **docker/** : Documentation spécifique à Docker.
- **regle.txt** : Règles ou directives pour le projet.
- **reprise-code.md** : Notes sur la reprise ou la maintenance du code.

### Dossier `includes`
- **function.php** : Contient des fonctions PHP réutilisables dans tout le projet.
- **tinymce/** :
  - **js/** : Fichiers JavaScript pour TinyMCE, un éditeur de texte enrichi.
  - **plugins/** : Plugins pour TinyMCE (ex. : gestion des listes, insertion de médias).
  - **skins/** : Thèmes et styles pour TinyMCE.

### Dossier `pages`
- **db-test.php** : Script pour tester la connexion à la base de données.
- **modules.php** : Gestion des modules ou fonctionnalités spécifiques.

#### Sous-dossier `backoffice`
- **article-form.php** : Formulaire pour créer ou modifier des articles.
- **articles-list.php** : Liste des articles dans le back-office.
- **dashboard.php** : Tableau de bord pour les administrateurs.
- **login.php** : Page de connexion pour les administrateurs.
- **css/style.css** : Styles CSS pour le back-office.
- **js/app.js** : Scripts JavaScript pour le back-office.

#### Sous-dossier `frontoffice`
- **index.php** : Page d'accueil du front-office.
- **css/style.css** : Styles CSS pour le front-office.
- **js/main.js** : Scripts JavaScript pour le front-office.
- **pages/** :
  - **article.php** : Page pour afficher un article spécifique.
  - **discover.php** : Page pour découvrir de nouveaux contenus.
  - **footer.php** : Pied de page commun à toutes les pages.
  - **header.php** : En-tête commun à toutes les pages.

---

## Fonctionnalités Principales

### Front-Office
- Affichage des articles et des contenus.
- Navigation entre les différentes sections (Accueil, Découverte, etc.).
- Design responsive grâce à Bootstrap.

### Back-Office
- Gestion des articles (création, modification, suppression).
- Tableau de bord pour les statistiques et les informations importantes.
- Authentification sécurisée pour les administrateurs.

### Base de Données
- Scripts SQL pour initialiser et mettre à jour le schéma de la base de données.
- Configuration centralisée dans `config/database.php`.

---

## Configuration et Déploiement

### Prérequis
- Docker et Docker Compose installés.
- Accès à un navigateur web moderne.

### Étapes
1. Cloner le dépôt du projet.
2. Lancer les services Docker :
   ```bash
   docker-compose up -d
   ```
3. Accéder à l'application :
   - Front-office : `http://localhost`
   - Back-office : `http://localhost/pages/backoffice/login`

---

## Notes Supplémentaires
- Les fichiers CSS et JS sont organisés par section (front-office et back-office).
- TinyMCE est utilisé pour l'édition de texte enrichi dans le back-office.
- Les règles de réécriture (sample-rewriting.txt) peuvent être adaptées selon les besoins.

---

## Contact
Pour toute question ou assistance, veuillez contacter l'équipe de développement.