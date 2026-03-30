<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

function findArticleById(PDO $pdo, int $articleId): ?array
{
    $stmt = $pdo->prepare('SELECT a.id, a.category_id, a.body, c.name AS category_name FROM articles a INNER JOIN categories c ON c.id = a.category_id WHERE a.id = :id LIMIT 1');
    $stmt->execute(['id' => $articleId]);
    $article = $stmt->fetch();

    return $article !== false ? $article : null;
}

function findCategoryByIdentifier(PDO $pdo, string $identifier): ?array
{
    if ($identifier === '') {
        return null;
    }

    if (ctype_digit($identifier)) {
        $stmt = $pdo->prepare('SELECT id, name FROM categories WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => (int) $identifier]);
        $category = $stmt->fetch();

        return $category !== false ? $category : null;
    }

    $stmt = $pdo->prepare('SELECT id, name FROM categories WHERE LOWER(name) = LOWER(:name) LIMIT 1');
    $stmt->execute(['name' => $identifier]);
    $category = $stmt->fetch();

    return $category !== false ? $category : null;
}

function findImagesByArticleId(PDO $pdo, int $articleId): array
{
    $stmt = $pdo->prepare('SELECT id, image_url, alt_text, sort_order FROM images WHERE article_id = :article_id ORDER BY sort_order ASC, id ASC');
    $stmt->execute(['article_id' => $articleId]);

    return $stmt->fetchAll();
}

function findUserByUsername(PDO $pdo, string $username): ?array
{
    $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    return $user !== false ? $user : null;
}

function verifyUserCredentials(PDO $pdo, string $username, string $plainPassword): bool
{
    $user = findUserByUsername($pdo, $username);
    if ($user === null) {
        return false;
    }

    return hash_equals((string) $user['password'], $plainPassword);
}
