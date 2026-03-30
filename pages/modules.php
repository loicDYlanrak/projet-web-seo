<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/article_repository.php';

$id = trim((string) ($_GET['id'] ?? ''));
$idcat = trim((string) ($_GET['idcat'] ?? ''));

$errorMessage = '';
$article = null;
$category = null;

if ($id === '') {
	http_response_code(400);
	$errorMessage = 'Missing article identifier in URL.';
} else {
	try {
		$pdo = dbConnection();
		$article = findArticleByIdentifier($pdo, $id);

		if ($article === null) {
			http_response_code(404);
			$errorMessage = 'Article not found.';
		} else {
			$category = $idcat !== ''
				? findCategoryByIdentifier($pdo, $idcat)
				: findCategoryByIdentifier($pdo, (string) $article['category_id']);
		}
	} catch (Throwable $exception) {
		http_response_code(500);
		$errorMessage = 'Database error: ' . $exception->getMessage();
	}
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Article module</title>
</head>
<body>
	<h1>Article module</h1>

	<?php if ($errorMessage !== ''): ?>
		<p><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php else: ?>
		<p><strong>Id:</strong> <?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?></p>
		<p><strong>IdCAT:</strong> <?= htmlspecialchars($idcat !== '' ? $idcat : (string) ($article['category_id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>

		<h2><?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
		<p><?= htmlspecialchars((string) $article['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
		<p><?= htmlspecialchars((string) $article['body'], ENT_QUOTES, 'UTF-8'); ?></p>

		<?php if ($category !== null): ?>
			<p>Category: <?= htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8'); ?> (<?= htmlspecialchars((string) $category['slug'], ENT_QUOTES, 'UTF-8'); ?>)</p>
		<?php endif; ?>
	<?php endif; ?>
</body>
</html>