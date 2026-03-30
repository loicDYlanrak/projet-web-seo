<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

function findArticleByIdentifier(PDO $pdo, string $identifier): ?array
{
    if ($identifier === '') {
        return null;
    }

    if (ctype_digit($identifier)) {
        $stmt = $pdo->prepare('SELECT a.*, c.slug AS category_slug, c.name AS category_name FROM articles a INNER JOIN categories c ON c.id = a.category_id WHERE a.id = :id LIMIT 1');
        $stmt->execute(['id' => (int) $identifier]);
        $article = $stmt->fetch();

        return $article !== false ? $article : null;
    }

    $stmt = $pdo->prepare('SELECT a.*, c.slug AS category_slug, c.name AS category_name FROM articles a INNER JOIN categories c ON c.id = a.category_id WHERE a.slug = :slug LIMIT 1');
    $stmt->execute(['slug' => $identifier]);
    $article = $stmt->fetch();

    return $article !== false ? $article : null;
}

function findCategoryByIdentifier(PDO $pdo, string $identifier): ?array
{
    if ($identifier === '') {
        return null;
    }

    if (ctype_digit($identifier)) {
        $stmt = $pdo->prepare('SELECT id, slug, name FROM categories WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => (int) $identifier]);
        $category = $stmt->fetch();

        return $category !== false ? $category : null;
    }

    $stmt = $pdo->prepare('SELECT id, slug, name FROM categories WHERE slug = :slug LIMIT 1');
    $stmt->execute(['slug' => $identifier]);
    $category = $stmt->fetch();

    return $category !== false ? $category : null;
}
