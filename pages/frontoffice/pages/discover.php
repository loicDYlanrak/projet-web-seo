<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>VertoNews – Discover</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body>

<!-- ============================================================
     DESKTOP
     ============================================================ -->
<div class="desktop-only">
  <nav class="desktop-nav">
    <div class="nav-top">
      <div class="nav-left">
        <button class="nav-icon-btn"><i class="fas fa-search"></i></button>
        <span style="width:1px;height:22px;background:var(--border);display:inline-block;margin:0 2px;"></span>
        <button class="nav-icon-btn"><i class="fas fa-bars"></i></button>
      </div>
      <div class="brand">VERTO<span>NEWS</span></div>
      <div class="nav-right">
        <a href="#"><i class="fab fa-x-twitter" style="font-size:18px;"></i></a>
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <span style="width:1px;height:22px;background:var(--border);display:inline-block;margin:0 2px;"></span>
        <a href="#"><i class="far fa-user-circle" style="font-size:21px;"></i></a>
      </div>
    </div>
    <div class="nav-categories">
      <a href="../index.html">Home</a>
      <a href="discover.html" class="active">New</a>
      <a href="#">Top News</a>
      <a href="#">Politics</a>
      <a href="#">Sports</a>
      <a href="#">Economy</a>
      <a href="#">Culture</a>
      <a href="#">Technology</a>
      <a href="#">Science</a>
      <a href="#">Health</a>
    </div>
  </nav>

  <div class="discover-page">
    <h1 class="page-title">Discover</h1>
    <p class="page-sub">News from all around the world</p>

    <div class="search-bar" style="max-width:600px;">
      <i class="fas fa-search"></i>
      <input type="text" placeholder="Search" />
      <button class="filter-btn"><i class="fas fa-sliders-h"></i></button>
    </div>

    <div class="cat-filters" style="max-width:600px;margin-bottom:32px;">
      <button class="cat-btn active">All</button>
      <button class="cat-btn">Politic</button>
      <button class="cat-btn">Sport</button>
      <button class="cat-btn">Education</button>
      <button class="cat-btn">Games</button>
      <button class="cat-btn">Health</button>
      <button class="cat-btn">Science</button>
      <button class="cat-btn">Economy</button>
    </div>

    <div class="row g-4">
      <!-- Cards -->
      <div class="col-lg-8">
        <div class="discover-list">
          <div class="discover-card" onclick="navigate('article.html')" style="cursor:pointer;">
            <img class="thumb" src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&q=80" alt="Volleyball" />
            <div class="info">
              <div class="cat">Sports</div>
              <h4>What Training Do Volleyball Players Need?</h4>
              <div class="meta">
                <img src="https://i.pravatar.cc/30?img=3" alt="" />
                <span>McKindney</span><span>·</span><span>Feb 27, 2023</span>
              </div>
            </div>
          </div>
          <div class="discover-card" onclick="navigate('article.html')" style="cursor:pointer;">
            <img class="thumb" src="https://images.unsplash.com/photo-1580894894513-541e068a3e2b?w=300&q=80" alt="Education" />
            <div class="info">
              <div class="cat">Education</div>
              <h4>Secondary school places: When do parents find out?</h4>
              <div class="meta">
                <img src="https://i.pravatar.cc/30?img=7" alt="" />
                <span>Rosemary</span><span>·</span><span>Feb 28, 2023</span>
              </div>
            </div>
          </div>
          <div class="discover-card" onclick="navigate('article.html')" style="cursor:pointer;">
            <img class="thumb" src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=300&q=80" alt="Fire" />
            <div class="info">
              <div class="cat">World</div>
              <h4>6 Houses Destroyed In Massive Fire In Assam's K.</h4>
              <div class="meta">
                <img src="https://i.pravatar.cc/30?img=14" alt="" />
                <span>Aslam K.</span><span>·</span><span>Feb 27, 2023</span>
              </div>
            </div>
          </div>
          <div class="discover-card" onclick="navigate('article.html')" style="cursor:pointer;">
            <img class="thumb" src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=300&q=80" alt="Road crashes" />
            <div class="info">
              <div class="cat">World</div>
              <h4>At least 35 people killed in separate road crashes across Asia</h4>
              <div class="meta">
                <img src="https://i.pravatar.cc/30?img=2" alt="" />
                <span>James Carter</span><span>·</span><span>Feb 27, 2023</span>
              </div>
            </div>
          </div>
          <div class="discover-card" onclick="navigate('article.html')" style="cursor:pointer;">
            <img class="thumb" src="https://images.unsplash.com/photo-1446776653964-20c1d3a81b06?w=300&q=80" alt="Space" />
            <div class="info">
              <div class="cat">Science</div>
              <h4>Astronomers discover new exoplanet in habitable zone</h4>
              <div class="meta">
                <img src="https://i.pravatar.cc/30?img=5" alt="" />
                <span>Mary Frost</span><span>·</span><span>Today, 10:00 AM</span>
              </div>
            </div>
          </div>
          <div class="discover-card" onclick="navigate('article.html')" style="cursor:pointer;">
            <img class="thumb" src="https://images.unsplash.com/photo-1509391366360-2e959784a276?w=300&q=80" alt="Energy" />
            <div class="info">
              <div class="cat">Economy</div>
              <h4>Scientists have developed a new method of storing renewable energy</h4>
              <div class="meta">
                <img src="https://i.pravatar.cc/30?img=8" alt="" />
                <span>Lucas Ray</span><span>·</span><span>Today, 1:00 PM</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Sidebar -->
      <div class="col-lg-4">
        <div class="sidebar-section">
          <h3>Trending authors</h3>
          <div style="display:flex;flex-direction:column;gap:14px;margin-top:8px;">
            <div class="author-card">
              <div class="a-info">
                <img src="https://i.pravatar.cc/50?img=11" alt="Adam Strong" />
                <div><div class="a-name">Adam Strong</div><div class="a-followers">14.3K followers</div></div>
              </div>
              <button class="a-follow"><i class="fas fa-arrow-up-right-from-square" style="font-size:11px;"></i></button>
            </div>
            <div class="author-card">
              <div class="a-info">
                <img src="https://i.pravatar.cc/50?img=9" alt="Samantha Hayes" />
                <div><div class="a-name">Samantha Hayes</div><div class="a-followers">18.7K followers</div></div>
              </div>
              <button class="a-follow"><i class="fas fa-arrow-up-right-from-square" style="font-size:11px;"></i></button>
            </div>
            <div class="author-card">
              <div class="a-info">
                <img src="https://i.pravatar.cc/50?img=3" alt="McKindney" />
                <div><div class="a-name">McKindney</div><div class="a-followers">9.1K followers</div></div>
              </div>
              <button class="a-follow"><i class="fas fa-arrow-up-right-from-square" style="font-size:11px;"></i></button>
            </div>
            <div class="author-card">
              <div class="a-info">
                <img src="https://i.pravatar.cc/50?img=5" alt="Mary Frost" />
                <div><div class="a-name">Mary Frost</div><div class="a-followers">22.0K followers</div></div>
              </div>
              <button class="a-follow"><i class="fas fa-arrow-up-right-from-square" style="font-size:11px;"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="site-footer">
    <div class="footer-grid">
      <div>
        <div class="brand">VERTO<span>NEWS</span></div>
        <p>Your trusted source for breaking news and in-depth stories from around the world.</p>
      </div>
      <div><h5>Categories</h5><ul><li><a href="#">Politics</a></li><li><a href="#">Sports</a></li><li><a href="#">Health</a></li></ul></div>
      <div><h5>Company</h5><ul><li><a href="#">About Us</a></li><li><a href="#">Careers</a></li><li><a href="#">Advertise</a></li></ul></div>
      <div><h5>Legal</h5><ul><li><a href="#">Privacy</a></li><li><a href="#">Terms</a></li></ul></div>
    </div>
    <div class="footer-bottom"><span>© 2024 VertoNews.</span><span>Made with ❤️</span></div>
  </footer>
</div>

<!-- ============================================================
     MOBILE
     ============================================================ -->
<div class="mobile-only">
  <header class="mobile-header">
    <button class="back-btn" style="background:none;border:none;cursor:pointer;" onclick="goBack()"><i class="fas fa-chevron-left"></i></button>
    <span style="font-weight:700;font-size:15px;">Discover</span>
    <div></div>
  </header>

  <div style="padding:20px 20px 10px;">
    <h1 style="font-family:var(--font-display);font-size:2rem;">Discover</h1>
    <p style="color:var(--gray-mid);font-size:14px;margin-bottom:18px;">News from all around the world</p>
    <div class="search-bar">
      <i class="fas fa-search" style="color:var(--gray-mid);"></i>
      <input type="text" placeholder="Search" />
      <button class="filter-btn"><i class="fas fa-sliders-h"></i></button>
    </div>
    <div class="cat-filters" style="margin-bottom:4px;">
      <button class="cat-btn active">All</button>
      <button class="cat-btn">Politic</button>
      <button class="cat-btn">Sport</button>
      <button class="cat-btn">Education</button>
      <button class="cat-btn">Games</button>
      <button class="cat-btn">Health</button>
    </div>
  </div>

  <div class="discover-list" style="padding: 0 20px;">
    <div class="discover-card" onclick="navigate('article-mobile.html')">
      <img class="thumb" src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=300&q=80" alt="Volleyball" />
      <div class="info">
        <div class="cat">Sports</div>
        <h4>What Training Do Volleyball Players Need?</h4>
        <div class="meta"><img src="https://i.pravatar.cc/30?img=3" alt="" /><span>McKindney</span><span>·</span><span>Feb 27, 2023</span></div>
      </div>
    </div>
    <div class="discover-card" onclick="navigate('article-mobile.html')">
      <img class="thumb" src="https://images.unsplash.com/photo-1580894894513-541e068a3e2b?w=300&q=80" alt="Education" />
      <div class="info">
        <div class="cat">Education</div>
        <h4>Secondary school places: When do parents find out?</h4>
        <div class="meta"><img src="https://i.pravatar.cc/30?img=7" alt="" /><span>Rosemary</span><span>·</span><span>Feb 28, 2023</span></div>
      </div>
    </div>
    <div class="discover-card" onclick="navigate('article-mobile.html')">
      <img class="thumb" src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=300&q=80" alt="Fire" />
      <div class="info">
        <div class="cat">World</div>
        <h4>6 Houses Destroyed In Massive Fire In Assam's K.</h4>
        <div class="meta"><img src="https://i.pravatar.cc/30?img=14" alt="" /><span>Aslam K.</span><span>·</span><span>Feb 27, 2023</span></div>
      </div>
    </div>
    <div class="discover-card" onclick="navigate('article-mobile.html')">
      <img class="thumb" src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=300&q=80" alt="Road crashes" />
      <div class="info">
        <div class="cat">World</div>
        <h4>At least 35 people killed in separate road crashes in AS</h4>
        <div class="meta"><img src="https://i.pravatar.cc/30?img=2" alt="" /><span>James Carter</span><span>·</span><span>Feb 27, 2023</span></div>
      </div>
    </div>
  </div>
</div>

<nav class="mobile-bottom-nav">
  <button class="mob-nav-item" onclick="navigate('../index.html')"><i class="fas fa-home"></i></button>
  <button class="mob-nav-item active-home"><i class="fas fa-globe"></i><span>Discover</span></button>
  <button class="mob-nav-item"><i class="far fa-bookmark"></i></button>
  <button class="mob-nav-item"><i class="far fa-user"></i></button>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>
<script>
  function navigate(p) { window.location.href = p; }
  function goBack() { if(history.length>1) history.back(); else window.location.href='../index.html'; }
</script>
</body>
</html>
