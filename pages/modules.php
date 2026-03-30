<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/function.php';

$id = trim((string) ($_GET['id'] ?? ''));
$idcat = trim((string) ($_GET['idcat'] ?? ''));

$errorMessage = '';
$article = null;
$category = null;

if ($id === '') {
	http_response_code(400);
	$errorMessage = 'Missing article identifier in URL.';
} else {
	if (!ctype_digit($id)) {
		http_response_code(400);
		$errorMessage = 'Article identifier must be numeric.';
	}

	try {
		if ($errorMessage === '') {
			$pdo = dbConnection();
			$article = findArticleById($pdo, (int) $id);
		}

		if ($article === null) {
			if ($errorMessage === '') {
				http_response_code(404);
				$errorMessage = 'Article not found.';
			}
		} else {
			$category = $idcat !== ''
				? findCategoryByIdentifier($pdo, $idcat)
				: findCategoryByIdentifier($pdo, (string) $article['category_id']);
			$images = findImagesByArticleId($pdo, (int) $article['id']);
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
		<div><?= (string) $article['body']; ?></div>

		<?php if ($category !== null): ?>
			<p>Category: <?= htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8'); ?></p>
		<?php endif; ?>

		<?php if (!empty($images)): ?>
			<h3>Images</h3>
			<ul>
				<?php foreach ($images as $image): ?>
					<li>
						<?= htmlspecialchars((string) $image['image_url'], ENT_QUOTES, 'UTF-8'); ?>
						<?php if (!empty($image['alt_text'])): ?>
							- <?= htmlspecialchars((string) $image['alt_text'], ENT_QUOTES, 'UTF-8'); ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	<?php endif; ?>
</body>
</html>