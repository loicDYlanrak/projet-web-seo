<?php
// includes/article-content.php
$pageTitle = $article['title'];
?>
<!-- DESKTOP ARTICLE -->
<div class="article-page">
  <span class="tag tag-sport"><?php echo htmlspecialchars($article['category_name']); ?></span>
  <h1><?php echo htmlspecialchars($article['title']); ?></h1>
  <div class="art-meta">
    <div class="author-row">
      <img src="https://i.pravatar.cc/40?img=11" alt="<?php echo htmlspecialchars($article['author']); ?>" />
      <span class="fw-700"><?php echo htmlspecialchars($article['author']); ?></span>
      <span class="sep">·</span>
      <span class="text-muted">Trending · <?php echo date('h:i A, M d', strtotime($article['created_at'])); ?></span>
    </div>
    <!-- <div style="display:flex;gap:10px;">
      <button style="background:none;border:1px solid var(--border);border-radius:8px;padding:6px 14px;cursor:pointer;font-size:13px;">
        <i class="far fa-bookmark"></i> Save
      </button>
      <button style="background:none;border:1px solid var(--border);border-radius:8px;padding:6px 14px;cursor:pointer;font-size:13px;">
        <i class="fas fa-share-nodes"></i> Share
      </button>
    </div> -->
  </div>

  <?php if (!empty($article['images'])): ?>
  <img class="art-hero w-100" src="<?php echo htmlspecialchars($article['images'][0]['image_url']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" style="height:420px;object-fit:cover;border-radius:14px;" />
  <?php else: ?>
  <img class="art-hero w-100" src="https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=900&q=80" alt="<?php echo htmlspecialchars($article['title']); ?>" style="height:420px;object-fit:cover;border-radius:14px;" />
  <?php endif; ?>

  <?php echo $article['body']; ?>

  <hr style="margin:30px 0;border-color:var(--border);" />

  <!-- Related articles -->
  <h2 style="font-size:1.1rem;font-family:var(--font-body);font-weight:700;margin-bottom:18px;">Related Stories</h2>
  <div class="row g-3">
    <?php foreach (array_slice($relatedArticles, 0, 3) as $related): ?>
    <div class="col-md-4">
      <div class="hover-card" onclick="navigate('/pages/frontoffice/?page=article&slug=<?php echo urlencode($related['slug'] ?? $related['id']); ?>')" style="border-radius:12px;overflow:hidden;border:1px solid var(--border);">
        <img src="<?php echo htmlspecialchars($related['image'] ?? 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&q=80'); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>" style="height:150px;object-fit:cover;" />
        <div style="padding:14px;">
          <span class="tag" style="background:var(--tag-<?php echo strtolower($related['category_name']); ?>);font-size:10px;"><?php echo htmlspecialchars($related['category_name']); ?></span>
          <h3 style="font-size:14px;margin:8px 0 8px;font-family:var(--font-body);"><?php echo htmlspecialchars($related['title']); ?></h3>
          <div class="author-row"><img src="https://i.pravatar.cc/30?img=11" alt="" /><span><?php echo htmlspecialchars($related['author']); ?></span><span>·</span><span><?php echo date('M d', strtotime($related['created_at'])); ?></span></div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- MOBILE ARTICLE -->
<div class="mobile-only">
  <header class="mob-back-header">
    <button class="back-btn" onclick="goBack()"><i class="fas fa-chevron-left"></i></button>
    <div class="art-actions">
      <button><i class="far fa-bookmark"></i></button>
      <button><i class="fas fa-ellipsis"></i></button>
    </div>
  </header>

  <div class="mobile-art-page">
    <?php if (!empty($article['images'])): ?>
    <img class="mob-art-hero" src="<?php echo htmlspecialchars($article['images'][0]['image_url']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" />
    <?php else: ?>
    <img class="mob-art-hero" src="https://images.unsplash.com/photo-1517649763962-0c623066013b?w=800&q=80" alt="<?php echo htmlspecialchars($article['title']); ?>" />
    <?php endif; ?>

    <div class="mob-art-body">
      <span class="tag tag-sport"><?php echo htmlspecialchars($article['category_name']); ?></span>
      <h1><?php echo htmlspecialchars($article['title']); ?></h1>
      <div class="mob-art-meta">
        <span style="font-size:11px;background:var(--gray-light);padding:3px 10px;border-radius:50px;">Trending</span>
        <span>· <?php echo date('h:i A', strtotime($article['created_at'])); ?></span>
      </div>

      <?php echo substr($article['body'], 0, 500); ?>
    </div>

    <!-- Source card -->
    <div class="mob-source-card">
      <div class="source-head">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/CNN_International_logo.svg/200px-CNN_International_logo.svg.png" alt="Source" style="border-radius:50%;object-fit:contain;background:#cc0000;padding:4px;" />
        <div>
          <div class="name"><?php echo htmlspecialchars($article['author']); ?> <span style="display:inline-block;width:14px;height:14px;background:var(--blue);border-radius:50%;text-align:center;line-height:14px;font-size:8px;color:#fff;vertical-align:middle;">✓</span></div>
        </div>
      </div>
      <p><?php echo htmlspecialchars(substr(strip_tags($article['body']), 0, 200)) . '...'; ?></p>
    </div>
  </div>
</div>