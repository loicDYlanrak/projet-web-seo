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
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (id, name) VALUES
    (1, 'Tech'),
    (2, 'SEO')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO articles (id, category_id, body) VALUES
    (1, 1, '<h2>Docker PHP setup</h2><p>This article explains how to run php:8.2-apache with mysql using docker compose and URL rewriting.</p>'),
    (2, 2, '<h2>Rewrite basics</h2><p>This article shows how id and idcat can be captured from friendly URLs and mapped to PHP pages.</p>')
ON DUPLICATE KEY UPDATE body = VALUES(body);

INSERT INTO images (id, article_id, image_url, alt_text, sort_order) VALUES
    (1, 1, '/template/images/docker-setup.jpg', 'Docker setup visual', 1),
    (2, 1, '/template/images/apache-rewrite.jpg', 'Apache rewrite rule visual', 2),
    (3, 2, '/template/images/rewrite-basics.jpg', 'URL rewriting basics', 1)
ON DUPLICATE KEY UPDATE image_url = VALUES(image_url), alt_text = VALUES(alt_text), sort_order = VALUES(sort_order);

INSERT INTO users (id, username, password_hash) VALUES
    (1, 'admin', '$2y$10$2wU8tQ4YzRok6pUJ7N0P5.YV77wQ5P3Yv1vP8uQ6n2O9m50Me3x3y')
ON DUPLICATE KEY UPDATE username = VALUES(username), password_hash = VALUES(password_hash);
