<div class="stats-grid">
    <div class="stat-card accent-blue">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h10M4 18h8"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value" id="stat-total">0</span>
            <span class="stat-label">Articles publiés</span>
        </div>
    </div>
    <div class="stat-card accent-green">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value" id="stat-categories">0</span>
            <span class="stat-label">Catégories actives</span>
        </div>
    </div>
    <div class="stat-card accent-orange">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value" id="stat-authors">0</span>
            <span class="stat-label">Auteurs actifs</span>
        </div>
    </div>
    <div class="stat-card accent-purple">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value" id="stat-views">0</span>
            <span class="stat-label">Vues aujourd'hui</span>
        </div>
    </div>
</div>

<div class="dashboard-bottom">
    <div class="recent-articles">
        <h3>Articles récents</h3>
        <div id="recent-list"></div>
    </div>
    <div class="categories-panel">
        <h3>Répartition par catégorie</h3>
        <div id="cat-list"></div>
    </div>
</div>

<script>
function loadDashboardStats() {
    fetch('api/articles.php?action=getDashboardStats')
        .then(response => response.json())
        .then(data => {
            // Mettre à jour le total des articles
            document.getElementById('stat-total').textContent = data.totalArticles;
            
            // Mettre à jour les catégories actives (statistique)
            const totalCategories = data.categories.filter(cat => cat.count > 0).length;
            document.getElementById('stat-categories').textContent = totalCategories;
            
            // Mettre à jour les auteurs uniques
            const uniqueAuthors = [...new Set(data.recentArticles.map(a => a.author))];
            document.getElementById('stat-authors').textContent = uniqueAuthors.length;
            
            // Afficher les articles récents avec l'ancien style
            const recentEl = document.getElementById('recent-list');
            recentEl.innerHTML = data.recentArticles.map(article => `
                <div class="recent-row" onclick="editArticle(${article.id})">
                    <img class="recent-thumb" src="${article.main_image || article.image || ''}" onerror="this.src=''" alt="">
                    <div class="recent-info">
                        <div class="recent-title">${escapeHtml(article.title)}</div>
                        <div class="recent-meta">${escapeHtml(article.author)} · ${escapeHtml(article.category_name)}</div>
                    </div>
                </div>
            `).join('');
            
            // Afficher la répartition par catégorie avec l'ancien style
            const catEl = document.getElementById('cat-list');
            catEl.innerHTML = data.categories
                .filter(cat => cat.count > 0)
                .map(cat => `
                    <div class="cat-row">
                        <span class="cat-badge cat-${cat.name}">${escapeHtml(cat.name)}</span>
                        <span class="cat-count">${cat.count} article${cat.count > 1 ? 's' : ''}</span>
                    </div>
                `).join('');
        })
        .catch(error => {
            console.error('Erreur lors du chargement des statistiques:', error);
            // Optionnel: afficher un message d'erreur
            showToast('Erreur de chargement des statistiques', true);
        });
}

// Fonction utilitaire pour échapper le HTML (identique à celle dans app.js)
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

loadDashboardStats();
</script>