<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

function findArticleById(PDO $pdo, int $articleId): ?array
{
    $stmt = $pdo->prepare('SELECT a.id, a.category_id, a.body, a.title, a.author, a.created_at, c.name AS category_name FROM articles a INNER JOIN categories c ON c.id = a.category_id WHERE a.id = :id LIMIT 1');
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

    return (string) $user['password'] == $plainPassword;
}

/**
 * Récupérer tous les articles
 */
function findAllArticles(PDO $pdo, ?string $category = null): array
{
    if ($category && $category !== 'all') {
        $stmt = $pdo->prepare('
            SELECT 
                a.id, 
                a.title, 
                a.author, 
                a.created_at, 
                c.name as category_name,
                (
                    SELECT i.image_url 
                    FROM images i 
                    WHERE i.article_id = a.id 
                    ORDER BY i.id ASC 
                    LIMIT 1
                ) as image
            FROM articles a 
            INNER JOIN categories c ON c.id = a.category_id 
            WHERE LOWER(c.name) = LOWER(:category)
            ORDER BY a.created_at DESC
        ');
        $stmt->execute(['category' => $category]);
    } else {
        $stmt = $pdo->query('
            SELECT 
                a.id, 
                a.title, 
                a.author, 
                a.created_at, 
                c.name as category_name,
                (
                    SELECT i.image_url 
                    FROM images i 
                    WHERE i.article_id = a.id 
                    ORDER BY i.id ASC 
                    LIMIT 1
                ) as image
            FROM articles a 
            INNER JOIN categories c ON c.id = a.category_id 
            ORDER BY a.created_at DESC
        ');
    }
    
    return $stmt->fetchAll();
}

/**
 * Récupérer tous les articles pour le dashboard (avec image principale)
 */
function findAllArticlesWithMainImage(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT a.id, a.title, a.author, a.created_at, c.name as category_name,
                                (SELECT image_url FROM images WHERE article_id = a.id ORDER BY sort_order ASC LIMIT 1) as main_image
                         FROM articles a 
                         INNER JOIN categories c ON c.id = a.category_id 
                         ORDER BY a.created_at DESC 
                         LIMIT 5');
    
    return $stmt->fetchAll();
}

/**
 * Récupérer toutes les catégories
 */
function findAllCategories(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT id, name FROM categories ORDER BY name');
    return $stmt->fetchAll();
}

/**
 * Récupérer le nombre d'articles par catégorie
 */
function getArticlesCountByCategory(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT c.name, COUNT(a.id) as count 
                         FROM categories c 
                         LEFT JOIN articles a ON a.category_id = c.id 
                         GROUP BY c.id, c.name 
                         ORDER BY count DESC');
    return $stmt->fetchAll();
}

/**
 * Récupérer le nombre total d'articles
 */
function getTotalArticlesCount(PDO $pdo): int
{
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM articles');
    $result = $stmt->fetch();
    return (int) $result['total'];
}

/**
 * Créer un nouvel article
 */
function createArticle(PDO $pdo, array $data): int
{
    // Démarrer une transaction
    $pdo->beginTransaction();
    
    try {
        // Extraire le titre du contenu H1
        $title = extractTitleFromBody($data['body']);
        
        // Insérer l'article
        $stmt = $pdo->prepare('INSERT INTO articles (category_id, title, author, body, created_at) 
                               VALUES (:category_id, :title, :author, :body, :created_at)');
        $stmt->execute([
            'category_id' => $data['category_id'],
            'title' => $title,
            'author' => $data['author'],
            'body' => $data['body'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $articleId = (int) $pdo->lastInsertId();
        
        // Insérer les images si elles existent
        if (!empty($data['images']) && is_array($data['images'])) {
            $sortOrder = 1;
            foreach ($data['images'] as $imageData) {
                // Traiter l'upload de l'image
                $imageUrl = uploadImage($imageData);
                if ($imageUrl) {
                    $stmt = $pdo->prepare('INSERT INTO images (article_id, image_url, alt_text, sort_order) 
                                           VALUES (:article_id, :image_url, :alt_text, :sort_order)');
                    $stmt->execute([
                        'article_id' => $articleId,
                        'image_url' => $imageUrl,
                        'alt_text' => $data['title'] ?? $title,
                        'sort_order' => $sortOrder++
                    ]);
                }
            }
        }
        
        $pdo->commit();
        return $articleId;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
function updateArticle(PDO $pdo, int $articleId, array $data): bool
{
    $pdo->beginTransaction();
    
    try {
        // Extraire le titre du contenu H1
        $title = extractTitleFromBody($data['body']);
        
        // Mettre à jour l'article
        $stmt = $pdo->prepare('UPDATE articles 
                               SET category_id = :category_id, 
                                   title = :title, 
                                   author = :author, 
                                   body = :body 
                               WHERE id = :id');
        $stmt->execute([
            'id' => $articleId,
            'category_id' => $data['category_id'],
            'title' => $title,
            'author' => $data['author'],
            'body' => $data['body']
        ]);
        
        // Gérer les images
        if (isset($data['images']) && is_array($data['images'])) {
            // Récupérer les images existantes
            $stmt = $pdo->prepare('SELECT id, image_url FROM images WHERE article_id = :article_id ORDER BY sort_order');
            $stmt->execute(['article_id' => $articleId]);
            $existingImages = $stmt->fetchAll();
            
            // Supprimer les anciennes images si nécessaire
            if (!empty($data['replace_images'])) {
                // Supprimer les anciens fichiers
                foreach ($existingImages as $existingImage) {
                    deleteImageFile($existingImage['image_url']);
                }
                // Supprimer les enregistrements
                $stmt = $pdo->prepare('DELETE FROM images WHERE article_id = :article_id');
                $stmt->execute(['article_id' => $articleId]);
                $existingImages = [];
            }
            
            // Ajouter les nouvelles images
            $currentSortOrder = count($existingImages) + 1;
            foreach ($data['images'] as $imageData) {
                $imageUrl = uploadImage($imageData);
                if ($imageUrl) {
                    $stmt = $pdo->prepare('INSERT INTO images (article_id, image_url, alt_text, sort_order) 
                                           VALUES (:article_id, :image_url, :alt_text, :sort_order)');
                    $stmt->execute([
                        'article_id' => $articleId,
                        'image_url' => $imageUrl,
                        'alt_text' => $data['title'] ?? $title,
                        'sort_order' => $currentSortOrder++
                    ]);
                }
            }
        }
        
        $pdo->commit();
        return true;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function deleteArticle(PDO $pdo, int $articleId): bool
{
    $stmt = $pdo->prepare('DELETE FROM articles WHERE id = :id');
    return $stmt->execute(['id' => $articleId]);
}

function extractTitleFromBody(string $body): string
{
    $pattern = '/<h1[^>]*>(.*?)<\/h1>/i';
    if (preg_match($pattern, $body, $matches)) {
        return strip_tags($matches[1]);
    }
    
    $plainText = strip_tags($body);
    return substr($plainText, 0, 100) ?: 'Article sans titre';
}

function uploadImage($imageData): ?string
{
    if (is_string($imageData) && filter_var($imageData, FILTER_VALIDATE_URL)) {
        return $imageData;
    }
    
    if (is_string($imageData) && strpos($imageData, 'data:image') === 0) {
        return saveBase64Image($imageData);
    }
    
    if (is_array($imageData) && isset($imageData['tmp_name']) && is_uploaded_file($imageData['tmp_name'])) {
        return saveUploadedFile($imageData);
    }
    
    return null;
}

function saveBase64Image(string $base64String): ?string
{
    // Extraire le type et les données
    if (preg_match('/^data:image\/(\w+);base64,(.+)$/', $base64String, $matches)) {
        $imageType = $matches[1];
        $imageData = base64_decode($matches[2]);
        
        // Créer un nom de fichier unique
        $filename = uniqid('img_', true) . '.' . $imageType;
        $uploadPath = __DIR__ . '/../assets/image/' . $filename;
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir(__DIR__ . '/../assets/image')) {
            mkdir(__DIR__ . '/../assets/image', 0777, true);
        }
        
        // Sauvegarder le fichier
        if (file_put_contents($uploadPath, $imageData)) {
            return '/assets/image/' . $filename;
        }
    }
    
    return null;
}

/**
 * Sauvegarder un fichier uploadé
 */
function saveUploadedFile(array $file): ?string
{
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return null;
    }
    
    if ($file['size'] > $maxSize) {
        return null;
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_', true) . '.' . $extension;
    $uploadPath = __DIR__ . '/../assets/image/' . $filename;
    
    // Créer le dossier s'il n'existe pas
    if (!is_dir(__DIR__ . '/../assets/image')) {
        mkdir(__DIR__ . '/../assets/image', 0777, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return '/assets/image/' . $filename;
    }
    
    return null;
}

/**
 * Supprimer un fichier image
 */
function deleteImageFile(string $imageUrl): bool
{
    if (empty($imageUrl)) {
        return false;
    }
    
    // Extraire le chemin du fichier
    $filePath = __DIR__ . '/..' . $imageUrl;
    if (file_exists($filePath) && is_file($filePath)) {
        return unlink($filePath);
    }
    
    return false;
}

