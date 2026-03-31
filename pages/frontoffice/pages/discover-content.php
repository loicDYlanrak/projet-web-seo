<?php
// includes/discover-content.php
$pageTitle = "Discover";
?>
<div class="discover-page">
  <h1 class="page-title">Discover</h1>
  <p class="page-sub">News from all around the world</p>

  <div class="search-bar" style="max-width:600px;">
    <i class="fas fa-search"></i>
    <input type="text" placeholder="Search" />
    <button class="filter-btn"><i class="fas fa-sliders-h"></i></button>
  </div>

  <div class="cat-filters" style="max-width:600px;margin-bottom:32px;">
    <button class="cat-btn <?php echo !$currentCategory ? 'active' : ''; ?>" onclick="navigate('/frontoffice/?page=discover')">All</button>
    <?php foreach ($categories as $cat): ?>
    <button class="cat-btn <?php echo $currentCategory == $cat['name'] ? 'active' : ''; ?>" onclick="navigate('/frontoffice/?page=category&category=<?php echo urlencode($cat['name']); ?>')"><?php echo htmlspecialchars($cat['name']); ?></button>
    <?php endforeach; ?>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="discover-list">
        <?php foreach ($articles as $article): ?>
        <div class="discover-card" onclick="navigate('/frontoffice/?page=article&slug=<?php echo urlencode($article['slug'] ?? $article['id']); ?>')" style="cursor:pointer;">
          <img class="thumb" src="<?php echo htmlspecialchars($article['image'] ?? 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&q=80'); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" />
          <div class="info">
            <div class="cat"><?php echo htmlspecialchars($article['category_name']); ?></div>
            <h4><?php echo htmlspecialchars($article['title']); ?></h4>
            <div class="meta">
              <img src="https://i.pravatar.cc/30?img=3" alt="" />
              <span><?php echo htmlspecialchars($article['author']); ?></span><span>·</span><span><?php echo date('M d, Y', strtotime($article['created_at'])); ?></span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($articles)): ?>
        <div class="text-center py-5">
          <p>No articles found in this category.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
    
    <div class="col-lg-4">
      <div class="sidebar-section">
        <h3>Trending authors</h3>
        <div style="display:flex;flex-direction:column;gap:14px;margin-top:8px;">
          <?php
          $authors = getTrendingAuthors($pdo);
          foreach (array_slice($authors, 0, 4) as $author):
          ?>
          <div class="author-card">
            <div class="a-info">
              <img src="https://i.pravatar.cc/50?img=<?php echo $author['id']; ?>" alt="<?php echo htmlspecialchars($author['name']); ?>" />
              <div>
                <div class="a-name"><?php echo htmlspecialchars($author['name']); ?></div>
                <div class="a-followers"><?php echo number_format($author['followers']); ?> followers</div>
              </div>
            </div>
            <button class="a-follow"><i class="fas fa-arrow-up-right-from-square" style="font-size:11px;"></i></button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>