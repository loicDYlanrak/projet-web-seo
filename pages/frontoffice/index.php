<?php
// index.php - Point d'entrée unique du frontoffice
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/function.php';

// Initialisation de la connexion à la base de données (une seule fois)
$pdo = dbConnection();

// Déterminer la page à afficher
$page = $_GET['page'] ?? 'home';

$slug = $_GET['slug'] ?? null;
$category = $_GET['category'] ?? null;

// Récupérer les données nécessaires selon la page
$articles = [];
$article = null;
$categories = [];
$currentCategory = null;

// Récupérer toutes les catégories pour le menu
$categories = findAllCategories($pdo);

switch ($page) {
    case 'home':
        // Récupérer les articles pour la page d'accueil
        $featuredArticles = findAllArticles($pdo, null, 5);
        $latestArticles = findAllArticles($pdo, null, 6, 5);
        $breakingNews = findLatestArticles($pdo, 3);
        break;
        
    case 'discover':
        // Récupérer tous les articles pour la page discover
        $articles = findAllArticles($pdo, $category);
        $currentCategory = $category;
        break;
        
    case 'article':
        // Récupérer un article spécifique par son slug ou ID
        if ($slug) {
            $article = findArticleBySlug($pdo, $slug);
        } elseif (isset($_GET['id'])) {
            $article = findArticleById($pdo, (int)$_GET['id']);
        }
        
        if (!$article) {
            $page = '404';
        } else {
            // Récupérer les images de l'article
            $article['images'] = findImagesByArticleId($pdo, $article['id']);
            // Récupérer les articles connexes
            $relatedArticles = findRelatedArticles($pdo, $article['id'], $article['category_id']);
        }
        break;
        
    case 'category':
        // Récupérer les articles par catégorie
        if ($category) {
            $categoryInfo = findCategoryByIdentifier($pdo, $category);
            if ($categoryInfo) {
                $articles = findAllArticles($pdo, $categoryInfo['name']);
                $currentCategory = $categoryInfo;
            } else {
                $page = '404';
            }
        } else {
            $page = '404';
        }
        break;
        
    default:
        $page = '404';
        break;
}

// Inclure l'en-tête avec le DOCTYPE
include __DIR__ . '/pages/header.php';
?>

<!-- Contenu principal selon la page -->
<?php if ($page === 'home'): ?>
    <?php include __DIR__ . '/pages/home-content.php'; ?>
<?php elseif ($page === 'discover'): ?>
    <?php include __DIR__ . '/pages/discover-content.php'; ?>
<?php elseif ($page === 'article' && $article): ?>
    <?php include __DIR__ . '/pages/article-content.php'; ?>
<?php elseif ($page === 'category'): ?>
    <?php include __DIR__ . '/pages/category-content.php'; ?>
<?php else: ?>
    <?php include __DIR__ . '/pages/404-content.php'; ?>
<?php endif; ?>

<?php
// Inclure le pied de page
include __DIR__ . '/pages/footer.php';
?>