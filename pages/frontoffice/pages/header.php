<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>VertoNews - <?php echo $pageTitle ?? 'Top News'; ?> : Actualites Sur la guerre en Iran</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<!-- ============================================================
     DESKTOP LAYOUT
     ============================================================ -->
<div class="desktop-only">

  <!-- NAV -->
  <nav class="desktop-nav">
    <div class="nav-top">
      <div class="nav-left">
        <!-- <button class="nav-icon-btn"><i class="fas fa-search"></i></button> -->
        <span style="width:1px;height:22px;background:var(--border);display:inline-block;margin:0 2px;"></span>
        <!-- <button class="nav-icon-btn"><i class="fas fa-bars"></i></button> -->
      </div>
      <div class="brand">VERTO<span>NEWS</span></div>
      <div class="nav-right">
        <a href="/pages/backoffice/login" class="btn btn-sm btn-outline-dark me-2" style="border-radius: 20px; font-weight: 600; font-size: 12px;">
          <i class="fas fa-lock me-1"></i> ADMIN
        </a>
        
        <span style="width:1px;height:22px;background:var(--border);display:inline-block;margin:0 2px;"></span>
        <a href="#" class="ms-2"><i class="far fa-user-circle" style="font-size:21px;"></i></a>
      </div>
    </div>
    <div class="nav-categories">
      <a href="/pages/frontoffice/">Home</a>
      <a href="/pages/frontoffice/?page=discover">New</a>
      <a href="/pages/frontoffice/?page=home&tab=top" class="<?php echo ($page == 'home' && isset($_GET['tab']) && $_GET['tab'] == 'top') ? 'active' : ''; ?>">Top News</a>
      
    </div>
  </nav>
<?php
// Fermeture du PHP pour permettre le HTML pur
?>