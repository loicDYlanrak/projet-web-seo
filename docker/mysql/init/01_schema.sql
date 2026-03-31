DROP TABLE IF EXISTS images;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_articles_category FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    sort_order INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_images_article FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO categories (id, name) VALUES
    (6, 'Geopolitique')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Insertion des articles sur l'Iran
INSERT INTO articles (id, category_id, body) VALUES
    (1, 6, '<h1>Tensions croissantes dans le Golfe</h1><p>Les recentes manœuvres militaires dans le detroit d\'Ormuz soulevent des inquietudes internationales quant a la stabilite regionale et aux exportations de petrole.</p>'),
    (2, 6, '<h1>Analyse des cyber-conflits</h1><p>Au-dela des affrontements physiques, une guerre invisible se joue sur le terrain numerique, ciblant les infrastructures strategiques de part et d\'autre.</p>')
ON DUPLICATE KEY UPDATE body = VALUES(body);

-- Mise a jour des images associees
INSERT INTO images (id, article_id, image_url, alt_text, sort_order) VALUES
    (1, 1, '/assets/image/iran-military-exercise.jpg', 'Manœuvres militaires iraniennes', 1),
    (2, 1, '/assets/image/strait-of-hormuz-map.jpg', 'Carte strategique du detroit d\'Ormuz', 2),
    (3, 2, '/assets/image/cyber-warfare-grid.jpg', 'Representation de la cyberguerre', 1)
ON DUPLICATE KEY UPDATE image_url = VALUES(image_url), alt_text = VALUES(alt_text), sort_order = VALUES(sort_order);

INSERT INTO users (id, username, password) VALUES
    (1, 'admin', 'admin')
ON DUPLICATE KEY UPDATE username = VALUES(username), password = VALUES(password);
