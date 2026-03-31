<?php
require_once 'config.php';
requireLogin();

$view = getCurrentView();
$theme = getTheme();

$editArticle = null;
if ($view === 'article-form' && isset($_GET['id'])) {
    $articleId = (int) $_GET['id'];
    try {
        $pdo = dbConnection();
        $editArticle = findArticleById($pdo, $articleId);
        if ($editArticle) {
            $images = findImagesByArticleId($pdo, $articleId);
            $editArticle['images'] = $images;
        }
    } catch (Exception $e) {
        // Ignorer l'erreur
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="<?php echo $theme; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VertoNews - Administration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tiny.cloud/1/x7dn4o6gh9jr91ldui8z4p55ugpr26owqko9rbafmtvo3116/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    
    <?php if ($editArticle): ?>
    <script>
        // Données d'édition de l'article
        window.editArticleData = {
            id: <?php echo json_encode($editArticle['id']); ?>,
            title: <?php echo json_encode($editArticle['title']); ?>,
            body: <?php echo json_encode($editArticle['body']); ?>,
            author: <?php echo json_encode($editArticle['author']); ?>,
            category: <?php echo json_encode($editArticle['category_name']); ?>,
            images: <?php echo json_encode(array_map(function($img) { return $img['image_url']; }, $editArticle['images'] ?? [])); ?>
        };
        console.log('Données d\'édition chargées:', window.editArticleData);
    </script>
    <?php endif; ?>
</head>

<body>
    <div class="app">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <?php include 'topbar.php'; ?>
            <div class="content-area">
                <?php
                // Inclure la vue appropriée
                switch ($view) {
                    case 'dashboard':
                        include 'dashboard.php';
                        break;
                    case 'home':
                        include 'backoffice_home.php';
                        break;
                    case 'articles':
                        include 'articles-list.php';
                        break;
                    case 'article-form':
                        include 'article-form.php';
                        break;
                    default:
                        include 'dashboard.php';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>

</html>