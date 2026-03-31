<?php
require_once 'config.php';
requireLogin();

$view = getCurrentView();
$theme = getTheme();


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
    <script>
        tinymce.init({
            selector: '#f-body',
            height: 400,
            menubar: true,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
            readonly: false
        });
    </script>
</head>

<body>
    <div class="app">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <?php include 'topbar.php'; ?>
            <div class="content-area">
                <?php
                // Vérifier si on est en mode édition
                if ($view === 'article-form' && isset($_GET['id'])) {
                    $articleId = (int) $_GET['id'];
                    try {
                        $pdo = dbConnection();
                        $article = findArticleById($pdo, $articleId);
                        if ($article) {
                            echo "<script>
                                window.editMode = true;
                                window.editId = {$articleId};
                                window.editData = {
                                    title: " . json_encode($article['title']) . ",
                                    body: " . json_encode($article['body']) . ",
                                    author: " . json_encode($article['author']) . ",
                                    category: " . json_encode($article['category_name']) . ",
                                    image_url: " . json_encode($article['image_url'] ?? '') . "
                                };
                            </script>";
                        }
                    } catch (Exception $e) {
                        // Ignorer l'erreur
                    }
                }

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

    <script>
        // Si on est en mode édition, charger les données
        if (window.editMode && window.editData) {
            setTimeout(() => {
                document.getElementById('edit-id').value = window.editId;
                document.getElementById('f-title').value = window.editData.title;
                document.getElementById('f-author').value = window.editData.author;
                document.getElementById('f-category').value = window.editData.category;
                document.getElementById('f-image').value = window.editData.image_url;

                if (window.editData.image_url) {
                    document.getElementById('preview-img').src = window.editData.image_url;
                    document.getElementById('preview-img').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                }

                if (window.editData.body) {
                    tinymce.get('f-body').setContent(window.editData.body);
                }

                document.getElementById('form-title').textContent = 'Modifier l\'article';
            }, 500);
        }
    </script>

    <script src="js/app.js"></script>
</body>

</html>