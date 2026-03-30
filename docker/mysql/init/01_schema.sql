CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    excerpt TEXT,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_articles_category FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO categories (id, slug, name) VALUES
    (1, 'tech', 'Tech'),
    (2, 'seo', 'SEO')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO articles (id, category_id, slug, title, excerpt, body) VALUES
    (1, 1, 'docker-php-setup', 'Docker PHP setup', 'Bootstrap a local PHP stack with Apache and MySQL.', 'This article explains how to run php:8.2-apache with mysql using docker compose and URL rewriting.'),
    (2, 2, 'rewrite-basics', 'Rewrite basics', 'Understand URL rewriting rules and parameters.', 'This article shows how id and idcat can be captured from friendly URLs and mapped to PHP pages.')
ON DUPLICATE KEY UPDATE title = VALUES(title), excerpt = VALUES(excerpt), body = VALUES(body);
