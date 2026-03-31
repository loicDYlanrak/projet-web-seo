<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../../../includes/function.php';

header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

try {
    $pdo = dbConnection();
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    
    switch ($action) {
        case 'getArticles':
            $category = $_GET['category'] ?? null;
            $articles = findAllArticles($pdo, $category);
            echo json_encode($articles);
            break;
            
        case 'getArticle':
            $id = (int) ($_GET['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID invalide');
            }
            $article = findArticleById($pdo, $id);
            echo json_encode($article);
            break;
            
        case 'createArticle':
            $data = json_decode(file_get_contents('php://input'), true);
            
            $category = findCategoryByIdentifier($pdo, $data['category']);
            if (!$category) {
                throw new Exception('Catégorie invalide');
            }
            
            $articleData = [
                'category_id' => $category['id'],
                'author' => $data['author'],
                'body' => $data['body'],
                'images' => isset($data['images']) ? $data['images'] : []
            ];
            
            $articleId = createArticle($pdo, $articleData);
            
            echo json_encode(['success' => true, 'id' => $articleId]);
            break;
            
        case 'updateArticle':
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if (!$id) {
                throw new Exception('ID article manquant');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Récupérer l'ID de la catégorie
            $category = findCategoryByIdentifier($pdo, $data['category']);
            if (!$category) {
                throw new Exception('Catégorie invalide');
            }
            
            $articleData = [
                'category_id' => $category['id'],
                'author' => $data['author'],
                'body' => $data['body'],
                'images' => isset($data['images']) ? $data['images'] : [],
                'replace_images' => $data['replace_images'] ?? false
            ];
            
            $result = updateArticle($pdo, $id, $articleData);
            
            echo json_encode(['success' => $result]);
            break;
            
        case 'deleteArticle':
            $id = (int) ($_POST['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID invalide');
            }
            
            deleteArticle($pdo, $id);
            echo json_encode(['success' => true]);
            break;
            
        case 'getDashboardStats':
            $totalArticles = getTotalArticlesCount($pdo);
            $categories = getArticlesCountByCategory($pdo);
            $recentArticles = findAllArticlesWithMainImage($pdo);
            
            echo json_encode([
                'totalArticles' => $totalArticles,
                'categories' => $categories,
                'recentArticles' => $recentArticles
            ]);
            break;
            
        case 'getCategories':
            $categories = findAllCategories($pdo);
            echo json_encode($categories);
            break;
            
        default:
            throw new Exception('Action non valide');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}