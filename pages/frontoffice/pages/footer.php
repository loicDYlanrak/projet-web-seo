<?php
// includes/footer.php
?>
  <!-- FOOTER -->
  <footer class="site-footer">
    <div class="footer-grid">
      <div>
        <div class="brand">VERTO<span>NEWS</span></div>
        <p>Your trusted source for breaking news, in-depth analysis, and stories that matter — from around the world.</p>
      </div>
      <div>
        <h5>Categories</h5>
        <ul>
          <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
          <li><a href="/frontoffice/?page=category&category=<?php echo urlencode($cat['name']); ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h5>Company</h5>
        <ul>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Advertise</a></li>
          <li><a href="#">Press</a></li>
        </ul>
      </div>
      <div>
        <h5>Legal</h5>
        <ul>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Use</a></li>
          <li><a href="#">Cookie Policy</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2024 VertoNews. All rights reserved.</span>
      <span>Made with ❤️ for curious minds</span>
    </div>
  </footer>
</div>

<!-- ============================================================
     MOBILE LAYOUT
     ============================================================ -->
<div class="mobile-only">

  <!-- MOBILE HEADER -->
  <header class="mobile-header">
    <button class="hamburger">Actu: Guerre en Iran </button>
    <div class="mob-icons">
      <!-- <button><i class="fas fa-search"></i></button>
      <button style="position:relative;">
        <i class="fas fa-bell"></i>
        <span class="notif-dot"></span>
      </button> -->
    </div>
  </header>

<?php if ($page === 'home'): ?>
  <!-- BREAKING NEWS -->
  <div style="padding: 18px 20px 8px;">
    <div class="section-header">
      <h2>Breaking News</h2>
      <span class="view-all" onclick="navigate('/pages/frontoffice/?page=discover')">View all</span>
    </div>
  </div>

  <div class="breaking-carousel">
    <?php foreach ($breakingNews as $news): ?>
    <div class="breaking-card hover-card" onclick="navigate('/pages/frontoffice/?page=article&slug=<?php echo urlencode($news['slug'] ?? $news['id']); ?>')">
      <img src="<?php echo htmlspecialchars($news['image'] ?? 'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=600&q=80'); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" />
      <div class="overlay"></div>
      <div class="card-body">
        <span class="tag tag-sport"><?php echo htmlspecialchars($news['category_name']); ?></span>
        <div class="mob-meta">
          <img src="https://i.pravatar.cc/30?img=15" alt="" />
          <span><?php echo htmlspecialchars($news['author']); ?></span>
          <span class="verified">✓</span>
          <span>· <?php echo date('M d, Y', strtotime($news['created_at'])); ?></span>
        </div>
        <h3><?php echo htmlspecialchars($news['title']); ?></h3>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="carousel-dots">
    <?php for ($i = 0; $i < count($breakingNews); $i++): ?>
    <div class="dot <?php echo $i === 0 ? 'active' : ''; ?>"></div>
    <?php endfor; ?>
  </div>

  <!-- RECOMMENDATIONS -->
  <div style="padding: 4px 20px 10px;">
    <div class="section-header">
      <h2>Recommendation</h2>
      <span class="view-all" onclick="navigate('/pages/frontoffice/?page=discover')">View all</span>
    </div>
  </div>

  <?php foreach (array_slice($latestArticles, 0, 3) as $rec): ?>
  <div class="mob-rec-card" onclick="navigate('/pages/frontoffice/?page=article&slug=<?php echo urlencode($rec['slug'] ?? $rec['id']); ?>')">
    <img class="thumb" src="<?php echo htmlspecialchars($rec['image'] ?? 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&q=80'); ?>" alt="<?php echo htmlspecialchars($rec['title']); ?>" />
    <div class="info">
      <div class="cat"><?php echo htmlspecialchars($rec['category_name']); ?></div>
      <h4><?php echo htmlspecialchars($rec['title']); ?></h4>
      <div class="meta">
        <img src="https://i.pravatar.cc/30?img=3" alt="" />
        <span><?php echo htmlspecialchars($rec['author']); ?></span><span>·</span><span><?php echo date('M d, Y', strtotime($rec['created_at'])); ?></span>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

<?php endif; ?>

<?php if ($page === 'article'): ?>
  <!-- MOBILE ARTICLE VIEW -->
  <div class="mobile-only">
    <header class="mob-back-header">
      <button class="back-btn" onclick="goBack()"><i class="fas fa-chevron-left"></i></button>
      <!-- <div class="art-actions">
        <button><i class="far fa-bookmark"></i></button>
        <button><i class="fas fa-ellipsis"></i></button>
      </div> -->
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

      
    </div>
  </div>
<?php else: ?>
  <!-- EXISTING FOOTER CONTENT -->
  <footer class="site-footer">
    <div class="footer-grid">
      <div>
        <div class="brand">VERTO<span>NEWS</span></div>
        <p>Your trusted source for breaking news, in-depth analysis, and stories that matter — from around the world.</p>
      </div>
      <div>
        <h5>Categories</h5>
        <ul>
          <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
          <li><a href="/frontoffice/?page=category&category=<?php echo urlencode($cat['name']); ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h5>Company</h5>
        <ul>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Advertise</a></li>
          <li><a href="#">Press</a></li>
        </ul>
      </div>
      <div>
        <h5>Legal</h5>
        <ul>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Use</a></li>
          <li><a href="#">Cookie Policy</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2024 VertoNews. All rights reserved.</span>
      <span>Made with ❤️ for curious minds</span>
    </div>
  </footer>
<?php endif; ?>

</div><!-- /mobile-only -->

<!-- MOBILE BOTTOM NAV -->
<nav class="mobile-bottom-nav">
  <button class="mob-nav-item <?php echo $page === 'home' ? 'active-home' : ''; ?>" onclick="navigate('/frontoffice/')">
    <i class="fas fa-home"></i>
    <span>Home</span>
  </button>
  <button class="mob-nav-item" onclick="navigate('/pages/frontoffice/?page=discover')">
    <i class="fas fa-globe" style="color:#cc0000;font-size:24px;"></i>
  </button>
  
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/frontoffice/js/main.js"></script>
<script>
  function navigate(p) { window.location.href = p; }
  function goBack() { if(history.length>1) history.back(); else window.location.href='/frontoffice/'; }
</script>
</body>
</html>