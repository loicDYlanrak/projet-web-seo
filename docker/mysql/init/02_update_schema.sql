-- Ajouter les colonnes manquantes à la table articles
ALTER TABLE articles 
ADD COLUMN IF NOT EXISTS title VARCHAR(255) NOT NULL AFTER category_id,
ADD COLUMN IF NOT EXISTS author VARCHAR(150) NOT NULL AFTER title,
ADD COLUMN IF NOT EXISTS views INT DEFAULT 0 AFTER body;

-- Mettre à jour les articles existants avec des valeurs par défaut
UPDATE articles SET title = CONCAT('Article ', id) WHERE title IS NULL OR title = '';
UPDATE articles SET author = 'Admin' WHERE author IS NULL OR author = '';

update articles set title = "Tensions croissantes dans le Golfe" where id = 1;
update articles set title = "Analyse des cyber-conflits" where id = 2;