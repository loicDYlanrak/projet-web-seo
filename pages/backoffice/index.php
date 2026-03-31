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
        if (window.editMode && window.editData) {
            function loadEditData() {
                if (typeof tinymce !== 'undefined' && tinymce.get('f-body')) {
                    const editor = tinymce.get('f-body');

                    const editIdField = document.getElementById('edit-id');
                    if (editIdField) editIdField.value = window.editId;

                    if (window.editData.title) {
                        let currentContent = editor.getContent();
                        if (!currentContent.includes('<h1>')) {
                            const titleHtml = `<h1>${escapeHtml(window.editData.title)}</h1>`;
                            editor.setContent(titleHtml + currentContent);
                        }
                    }

                    const authorField = document.getElementById('f-author');
                    if (authorField) authorField.value = window.editData.author;

                    const categoryField = document.getElementById('f-category');
                    if (categoryField) categoryField.value = window.editData.category;

                    const imageField = document.getElementById('f-image');
                    if (imageField && window.editData.image_url) {
                        imageField.value = window.editData.image_url;
                        const previewImg = document.getElementById('preview-img');
                        const uploadPlaceholder = document.getElementById('upload-placeholder');
                        if (previewImg && uploadPlaceholder) {
                            previewImg.src = window.editData.image_url;
                            previewImg.classList.remove('hidden');
                            uploadPlaceholder.classList.add('hidden');
                        }
                    }

                    if (window.editData.body) {
                        editor.setContent(window.editData.body);
                    }

                    const formTitle = document.getElementById('form-title');
                    if (formTitle) formTitle.textContent = 'Modifier l\'article';
                } else {
                    setTimeout(loadEditData, 100);
                }
            }

            setTimeout(loadEditData, 500);
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>

    <script src="js/app.js"></script>
</body>

</html>