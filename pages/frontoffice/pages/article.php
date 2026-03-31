<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>VertoNews – Article</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body>

<!-- DESKTOP -->
<div class="desktop-only">
  <nav class="desktop-nav">
    <div class="nav-top">
      <div class="nav-left">
        <button class="nav-icon-btn" onclick="goBack()"><i class="fas fa-arrow-left"></i></button>
        <span style="width:1px;height:22px;background:var(--border);display:inline-block;margin:0 6px;"></span>
        <button class="nav-icon-btn btn-hamburger"><i class="fas fa-bars"></i></button>
      </div>
      <div class="brand">VERTO<span>NEWS</span></div>
      <div class="nav-right">
        <button class="nav-icon-btn"><i class="far fa-bookmark"></i></button>
        <button class="nav-icon-btn"><i class="fas fa-share-nodes"></i></button>
        <a href="#"><i class="far fa-user-circle" style="font-size:21px;"></i></a>
      </div>
    </div>
    <div class="nav-categories">
      <a href="../index.html">Home</a>
      <a href="discover.html">New</a>
      <a href="#" class="active">Top News</a>
      <a href="#">Politics</a>
      <a href="#">Sports</a>
      <a href="#">Economy</a>
      <a href="#">Culture</a>
      <a href="#">Technology</a>
      <a href="#">Science</a>
      <a href="#">Health</a>
    </div>
  </nav>

  <div class="article-page">
    <span class="tag tag-sport">Sports</span>
    <h1>Will he retire? One more loss and Fury is finished!</h1>
    <div class="art-meta">
      <div class="author-row">
        <img src="https://i.pravatar.cc/40?img=11" alt="Adam Strong" />
        <span class="fw-700">Adam Strong</span>
        <span class="sep">·</span>
        <span class="text-muted">Trending · 10:00 AM, Today</span>
      </div>
      <div style="display:flex;gap:10px;">
        <button style="background:none;border:1px solid var(--border);border-radius:8px;padding:6px 14px;cursor:pointer;font-size:13px;">
          <i class="far fa-bookmark"></i> Save
        </button>
        <button style="background:none;border:1px solid var(--border);border-radius:8px;padding:6px 14px;cursor:pointer;font-size:13px;">
          <i class="fas fa-share-nodes"></i> Share
        </button>
      </div>
    </div>

    <img class="art-hero w-100" src="https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=900&q=80" alt="Boxing" style="height:420px;object-fit:cover;border-radius:14px;" />

    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
    <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt.</p>

    <blockquote style="border-left:4px solid var(--blue);padding:12px 20px;margin:24px 0;background:var(--blue-light);border-radius:0 10px 10px 0;font-style:italic;color:var(--gray-dark);">
      "The Usyk vs. Fury fight is on the horizon, but will it be the last for the Gypsy King? Tyson Fury, who recently narrowly escaped defeat in his last fights, is now facing the toughest challenge of his career."
    </blockquote>

    <p>Ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur.</p>
    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi.</p>

    <hr style="margin:30px 0;border-color:var(--border);" />

    <!-- Related articles -->
    <h2 style="font-size:1.1rem;font-family:var(--font-body);font-weight:700;margin-bottom:18px;">Related Stories</h2>
    <div class="row g-3">
      <div class="col-md-4">
        <div class="hover-card" onclick="navigate('article.html')" style="border-radius:12px;overflow:hidden;border:1px solid var(--border);">
          <img src="https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&q=80" alt="" style="height:150px;object-fit:cover;" />
          <div style="padding:14px;">
            <span class="tag" style="background:var(--tag-health);font-size:10px;">Health</span>
            <h3 style="font-size:14px;margin:8px 0 8px;font-family:var(--font-body);">New vaccine against a rare disease has been successfully tested</h3>
            <div class="author-row"><img src="https://i.pravatar.cc/30?img=11" alt="" /><span>Adam Strong</span><span>·</span><span>Today</span></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="hover-card" onclick="navigate('article.html')" style="border-radius:12px;overflow:hidden;border:1px solid var(--border);">
          <img src="https://images.unsplash.com/photo-1446776653964-20c1d3a81b06?w=400&q=80" alt="" style="height:150px;object-fit:cover;" />
          <div style="padding:14px;">
            <span class="tag" style="background:var(--tag-science);font-size:10px;">Science</span>
            <h3 style="font-size:14px;margin:8px 0 8px;font-family:var(--font-body);">Astronomers discover new exoplanet in habitable zone</h3>
            <div class="author-row"><img src="https://i.pravatar.cc/30?img=5" alt="" /><span>Mary Frost</span><span>·</span><span>Today</span></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="hover-card" onclick="navigate('article.html')" style="border-radius:12px;overflow:hidden;border:1px solid var(--border);">
          <img src="https://images.unsplash.com/photo-1509391366360-2e959784a276?w=400&q=80" alt="" style="height:150px;object-fit:cover;" />
          <div style="padding:14px;">
            <span class="tag" style="background:var(--tag-economy);font-size:10px;">Economy</span>
            <h3 style="font-size:14px;margin:8px 0 8px;font-family:var(--font-body);">Scientists have developed a new method of storing renewable energy</h3>
            <div class="author-row"><img src="https://i.pravatar.cc/30?img=8" alt="" /><span>Lucas Ray</span><span>·</span><span>Today</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="site-footer">
    <div class="footer-grid">
      <div><div class="brand">VERTO<span>NEWS</span></div><p>Your trusted source for breaking news.</p></div>
      <div><h5>Categories</h5><ul><li><a href="#">Politics</a></li><li><a href="#">Sports</a></li><li><a href="#">Health</a></li></ul></div>
      <div><h5>Company</h5><ul><li><a href="#">About Us</a></li><li><a href="#">Careers</a></li></ul></div>
      <div><h5>Legal</h5><ul><li><a href="#">Privacy</a></li><li><a href="#">Terms</a></li></ul></div>
    </div>
    <div class="footer-bottom"><span>© 2024 VertoNews.</span><span>Made with ❤️</span></div>
  </footer>
</div>

<!-- MOBILE -->
<div class="mobile-only">
  <header class="mob-back-header">
    <button class="back-btn" onclick="goBack()"><i class="fas fa-chevron-left"></i></button>
    <div class="art-actions">
      <button><i class="far fa-bookmark"></i></button>
      <button><i class="fas fa-ellipsis"></i></button>
    </div>
  </header>

  <div class="mobile-art-page">
    <img class="mob-art-hero" src="https://images.unsplash.com/photo-1517649763962-0c623066013b?w=800&q=80" alt="Cycling" />

    <div class="mob-art-body">
      <span class="tag tag-sport">Sports</span>
      <h1>Alexander wears modified helmet in road races</h1>
      <div class="mob-art-meta">
        <span style="font-size:11px;background:var(--gray-light);padding:3px 10px;border-radius:50px;">Trending</span>
        <span>· 6 hours ago</span>
      </div>

      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.</p>
      <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    </div>

    <!-- Source card -->
    <div class="mob-source-card">
      <div class="source-head">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/CNN_International_logo.svg/200px-CNN_International_logo.svg.png" alt="CNN" style="border-radius:50%;object-fit:contain;background:#cc0000;padding:4px;" />
        <div>
          <div class="name">CNN Indonesia <span style="display:inline-block;width:14px;height:14px;background:var(--blue);border-radius:50%;text-align:center;line-height:14px;font-size:8px;color:#fff;vertical-align:middle;">✓</span></div>
        </div>
      </div>
      <p>As a tech department, we're usually pretty good at spotting tech that's out of the ordinary. During time trials we used to seeing new aero innovation, and while there are certainly aero tricks used in road stages, they are harder to spot.</p>
      <p>A case in point, throughout the Volta ao Algarve, Alexander Kristoff has been wearing an old discontinued time trial helmet, complete with visor removed, in the road stages with...</p>
    </div>
  </div>
</div>

<nav class="mobile-bottom-nav">
  <button class="mob-nav-item" onclick="navigate('../index.html')"><i class="fas fa-home"></i></button>
  <button class="mob-nav-item" onclick="navigate('discover.html')"><i class="fas fa-globe"></i></button>
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
