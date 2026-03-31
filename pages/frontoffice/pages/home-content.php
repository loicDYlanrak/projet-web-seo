<?php
// includes/home-content.php
$pageTitle = "Actualités sur la guerre en Iran";
?>
  <!-- HERO SECTION -->
  <div class="hero-section">
    <h1 class="main-title">Actualités sur la guerre en Iran</h1>
    <h2 class="sub-title">Restez informé les derniers informations sur la guerre en Iran</h2>
  </div>

  <!-- ARTICLE LINKS -->
  <div class="article-links">
    <ul class="alls" style="list-style:none;padding:0;">
      <?php foreach ($latestArticles as $article): ?>
      <li style="margin-bottom:20px;">
            <a href="/pages/frontoffice/?page=article&slug=<?php echo urlencode($article['slug'] ?? $article['id']); ?>" class="article-link" style="font-size:1.2rem;font-weight:700;color:var(--dark);text-decoration:none;">

        <div style="display:flex;gap:16px;align-items:flex-start;">
          <img src="<?php echo htmlspecialchars($article['image'] ?? 'https://via.placeholder.com/150'); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" style="width:100px;height:100px;border-radius:8px;object-fit:cover;flex-shrink:0;" />
          <div>
              <?php echo htmlspecialchars($article['title']); ?>
            
            <p style="font-size:0.9rem;color:var(--gray-dark);line-height:1.5;margin:8px 0;">
              <?php echo htmlspecialchars(substr(strip_tags($article['body'] ?? ''), 0, 100)) . '...'; ?>
            </p>
          </div>
        </div>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>