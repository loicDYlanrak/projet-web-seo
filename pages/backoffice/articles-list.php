<div class="articles-toolbar">
    <div class="filter-tabs" id="filter-tabs">
        <button class="filter-tab active" data-cat="all">Tous</button>
        <!-- Les catégories seront chargées dynamiquement -->
    </div>
    
</div>
<div class="articles-table-wrap">
    <table class="articles-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="articles-tbody"></tbody>
    </table>
    <div id="no-results" class="no-results hidden">Aucun article trouvé.</div>
</div>

<script>
let currentCategory = 'all';

// Charger les catégories pour les filtres
function loadCategoryFilters() {
    fetch('api/articles.php?action=getCategories')
        .then(response => response.json())
        .then(categories => {
            const filterTabs = document.getElementById('filter-tabs');
            // Garder le bouton "Tous"
            filterTabs.innerHTML = '<button class="filter-tab active" data-cat="all">Tous</button>';
            categories.forEach(cat => {
                filterTabs.innerHTML += `<button class="filter-tab" data-cat="${cat.name}">${cat.name}</button>`;
            });
            
            // Réattacher les événements
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    currentCategory = tab.dataset.cat;
                    loadArticles();
                });
            });
        });
}

function loadArticles() {
    const url = currentCategory === 'all' 
        ? 'api/articles.php?action=getArticles'
        : `api/articles.php?action=getArticles&category=${encodeURIComponent(currentCategory)}`;
    
    fetch(url)
        .then(response => response.json())
        .then(articles => {
            const tbody = document.getElementById('articles-tbody');
            const noResults = document.getElementById('no-results');
            
            if (articles.length === 0) {
                tbody.innerHTML = '';
                noResults.classList.remove('hidden');
                return;
            }
            
            noResults.classList.add('hidden');
            tbody.innerHTML = '';
            
            articles.forEach(article => {
                const row = tbody.insertRow();
                row.innerHTML = `
                    <td><img src="${article.image}" alt="${article.title}" style="width: 50px; height: 50px; object-fit: cover;"></td>
                    <td><strong>${escapeHtml(article.title)}</strong></td>
                    <td>${escapeHtml(article.author)}</td>
                    <td><span class="category-badge">${escapeHtml(article.category_name)}</span></td>
                    <td>${new Date(article.created_at).toLocaleDateString('fr-FR')}</td>
                    <td>
                        <button class="btn-icon" onclick="editArticle(${article.id})" title="Modifier">✏️</button>
                        <button class="btn-icon" onclick="deleteArticle(${article.id})" title="Supprimer">🗑️</button>
                    </td>
                `;
            });
        })
        .catch(error => console.error('Erreur:', error));
}

function editArticle(id) {
    window.location.href = `index.php?view=article-form&id=${id}`;
}

function deleteArticle(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
        fetch('api/articles.php?action=deleteArticle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadArticles();
                alert('Article supprimé avec succès!');
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => console.error('Erreur:', error));
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Charger les catégories et les articles
loadCategoryFilters();
loadArticles();
</script>