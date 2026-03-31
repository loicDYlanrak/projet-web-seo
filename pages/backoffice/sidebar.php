<?php
$currentView = getCurrentView();
?>
<aside class="sidebar">
    <div class="sidebar-logo">
        <span class="logo-verto">VERTO</span><span class="logo-news">NEWS</span>
    </div>
    <nav class="sidebar-nav">
        <a href="<?php echo url('dashboard'); ?>" class="nav-item <?php echo $currentView === 'dashboard' ? 'active' : ''; ?>" data-view="dashboard">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="<?php echo url('articles'); ?>" class="nav-item <?php echo $currentView === 'articles' ? 'active' : ''; ?>" data-view="articles">
            <svg viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h10M4 18h8"/></svg>
            Articles
        </a>
      
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">A</div>
            <div>
                <div class="user-name">Admin</div>
                <div class="user-role">Rédacteur en chef</div>
            </div>
        </div>
        <div class="sidebar-actions" style="display: flex; gap: 6px;">
            <button class="btn-icon" id="theme-toggle" onclick="toggleTheme()" title="Basculer le thème">
                <svg viewBox="0 0 24 24" id="theme-icon"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            </button>
            <form action="logout.php" method="POST" style="display: inline;">
                <button type="submit" class="btn-icon del" title="Déconnexion">
                    <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>