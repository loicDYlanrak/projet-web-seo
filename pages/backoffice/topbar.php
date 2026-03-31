<?php
$currentView = getCurrentView();
$viewTitles = [
    'dashboard' => 'Dashboard',
    'articles' => 'Gestion des articles',
    'new-article' => 'Nouvel article'
];
$title = isset($viewTitles[$currentView]) ? $viewTitles[$currentView] : 'Dashboard';
?>
<header class="topbar">
    <div class="topbar-title"><?php echo $title; ?></div>
    <div class="topbar-actions">
        <div class="search-box">
            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="search-input" placeholder="Rechercher un article…" oninput="filterArticles(this.value)"/>
        </div>
        <button class="btn-primary" onclick="window.location.href='index.php?view=article-form'">+ Nouvel article</button>
    </div>
</header>