/* ═══════════════════════════════════════════
   VERTONEWS BACKOFFICE — app.js
   Full CRUD for articles (localStorage)
═══════════════════════════════════════════ */

'use strict';

// ── CREDENTIALS ──────────────────────────────
const CREDENTIALS = { username: 'admin', password: 'admin' };

// ── STORAGE KEY ───────────────────────────────
const STORE_KEY = 'vertonews_articles';

// ── SEED DATA ─────────────────────────────────
const SEED_ARTICLES = [
  {
    id: 1,
    title: "Will he retire? One more loss and Fury is finished!",
    body: 'The Usyk vs. Fury fight is on the horizon, but will it be the last for the "Gypsy King"? Tyson Fury, who recently narrowly escaped defeat in his last fights, is now facing the toughest challenge of his career — a confrontation with the undefeated Oleksandr Usyk."',
    author: "Adam Strong",
    category: "Sport",
    date: "2025-03-30T10:00:00",
    image: "https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&q=80"
  },
  {
    id: 2,
    title: "Astronomers discover new exoplanet in habitable zone",
    body: "A team of astronomers has identified a new Earth-like exoplanet orbiting within the habitable zone of a nearby star, raising fresh hopes for the possibility of extraterrestrial life.",
    author: "Mary Frost",
    category: "Science",
    date: "2025-03-30T10:00:00",
    image: "https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?w=800&q=80"
  },
  {
    id: 3,
    title: "Scientists have developed a new method of storing renewable energy",
    body: "Researchers at MIT have unveiled a breakthrough technology that stores solar and wind energy as heat in molten silicon, potentially solving one of renewable energy's biggest challenges.",
    author: "Lucas Ray",
    category: "Economy",
    date: "2025-03-30T13:00:00",
    image: "https://images.unsplash.com/photo-1509391366360-2e959784a276?w=800&q=80"
  },
  {
    id: 4,
    title: "New vaccine against a rare disease has been successfully tested",
    body: "Phase 3 clinical trials have confirmed a 94% efficacy rate for a new vaccine targeting a rare but devastating autoimmune condition. Regulatory approval is expected within months.",
    author: "Adam Strong",
    category: "Health",
    date: "2025-03-30T18:00:00",
    image: "https://images.unsplash.com/photo-1584036561566-baf8f5f1b144?w=800&q=80"
  },
  {
    id: 5,
    title: "AI startup raises $400M to accelerate robotics research",
    body: "A Silicon Valley startup focused on general-purpose robotics has secured $400 million in Series C funding, signaling growing investor confidence in autonomous systems.",
    author: "Samantha Hayes",
    category: "Technology",
    date: "2025-03-29T09:00:00",
    image: "https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800&q=80"
  }
];

// ── STATE ─────────────────────────────────────
let articles = [];
let currentFilter = 'all';
let searchQuery   = '';
let pendingDeleteId = null;

// ══════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
  loadArticles();
  setupFilterTabs();
  // Press Enter on login
  document.getElementById('login-pass').addEventListener('keydown', e => {
    if (e.key === 'Enter') doLogin();
  });
});

function loadArticles() {
  const stored = localStorage.getItem(STORE_KEY);
  if (stored) {
    articles = JSON.parse(stored);
  } else {
    articles = SEED_ARTICLES.map(a => ({ ...a }));
    saveToStorage();
  }
}

function saveToStorage() {
  localStorage.setItem(STORE_KEY, JSON.stringify(articles));
}

// ══════════════════════════════════════════════
// AUTH
// ══════════════════════════════════════════════
function doLogin() {
  const user = document.getElementById('login-user').value.trim();
  const pass = document.getElementById('login-pass').value;
  const err  = document.getElementById('login-error');

  if (user === CREDENTIALS.username && pass === CREDENTIALS.password) {
    err.classList.add('hidden');
    document.getElementById('login-screen').classList.remove('active');
    document.getElementById('login-screen').classList.add('hidden');
    document.getElementById('app').classList.remove('hidden');
    showView('dashboard');
  } else {
    err.classList.remove('hidden');
    document.getElementById('login-pass').value = '';
    document.getElementById('login-pass').focus();
  }
}

function doLogout() {
  document.getElementById('app').classList.add('hidden');
  document.getElementById('login-screen').classList.remove('hidden');
  document.getElementById('login-screen').classList.add('active');
  document.getElementById('login-user').value = '';
  document.getElementById('login-pass').value = '';
}

// ══════════════════════════════════════════════
// VIEW ROUTING
// ══════════════════════════════════════════════
const viewTitles = {
  dashboard:    'Dashboard',
  articles:     'Gestion des articles',
  'new-article':'Nouvel article'
};

function showView(name) {
  // hide all views
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
  const target = document.getElementById('view-' + name);
  if (target) target.classList.add('active');

  // update nav
  document.querySelectorAll('.nav-item').forEach(n => {
    n.classList.toggle('active', n.dataset.view === name);
  });

  // update topbar title
  document.getElementById('topbar-title').textContent = viewTitles[name] || '';

  // render the right view
  if (name === 'dashboard')   renderDashboard();
  if (name === 'articles')    renderArticlesTable();
  if (name === 'new-article') resetForm();
}

// bind nav items
document.querySelectorAll('.nav-item').forEach(item => {
  item.addEventListener('click', e => {
    e.preventDefault();
    showView(item.dataset.view);
  });
});

// ══════════════════════════════════════════════
// DASHBOARD
// ══════════════════════════════════════════════
function renderDashboard() {
  document.getElementById('stat-total').textContent = articles.length;

  // Recent articles (last 4)
  const recent = [...articles].sort((a, b) => b.id - a.id).slice(0, 4);
  const recentEl = document.getElementById('recent-list');
  recentEl.innerHTML = recent.map(a => `
    <div class="recent-row" onclick="editArticle(${a.id})">
      <img class="recent-thumb" src="${a.image || ''}" onerror="this.src=''" alt="">
      <div class="recent-info">
        <div class="recent-title">${escHtml(a.title)}</div>
        <div class="recent-meta">${escHtml(a.author)} · ${escHtml(a.category)}</div>
      </div>
    </div>
  `).join('');

  // Categories breakdown
  const cats = {};
  articles.forEach(a => { cats[a.category] = (cats[a.category] || 0) + 1; });
  const catEl = document.getElementById('cat-list');
  catEl.innerHTML = Object.entries(cats).map(([cat, count]) => `
    <div class="cat-row">
      <span class="cat-badge cat-${cat}">${cat}</span>
      <span class="cat-count">${count} article${count > 1 ? 's' : ''}</span>
    </div>
  `).join('');
}

// ══════════════════════════════════════════════
// ARTICLES TABLE
// ══════════════════════════════════════════════
function setupFilterTabs() {
  document.getElementById('filter-tabs').addEventListener('click', e => {
    const tab = e.target.closest('.filter-tab');
    if (!tab) return;
    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    currentFilter = tab.dataset.cat;
    renderArticlesTable();
  });
}

function filterArticles(q) {
  searchQuery = q.toLowerCase();
  renderArticlesTable();
}

function getFiltered() {
  return articles.filter(a => {
    const matchCat = currentFilter === 'all' || a.category === currentFilter;
    const matchQ   = !searchQuery ||
      a.title.toLowerCase().includes(searchQuery) ||
      a.author.toLowerCase().includes(searchQuery) ||
      a.category.toLowerCase().includes(searchQuery);
    return matchCat && matchQ;
  });
}

function renderArticlesTable() {
  const filtered = getFiltered();
  const tbody    = document.getElementById('articles-tbody');
  const noRes    = document.getElementById('no-results');

  if (filtered.length === 0) {
    tbody.innerHTML = '';
    noRes.classList.remove('hidden');
    return;
  }
  noRes.classList.add('hidden');

  tbody.innerHTML = filtered.map(a => `
    <tr>
      <td>
        <img class="table-thumb"
          src="${escHtml(a.image || '')}"
          onerror="this.style.opacity='0.3'"
          alt="">
      </td>
      <td><div class="table-title">${escHtml(a.title)}</div></td>
      <td class="table-author">${escHtml(a.author)}</td>
      <td><span class="cat-badge cat-${escHtml(a.category)}">${escHtml(a.category)}</span></td>
      <td class="table-date">${formatDate(a.date)}</td>
      <td>
        <div class="table-actions">
          <button class="btn-icon edit" title="Modifier" onclick="editArticle(${a.id})">
            <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </button>
          <button class="btn-icon del" title="Supprimer" onclick="openDeleteModal(${a.id})">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
          </button>
        </div>
      </td>
    </tr>
  `).join('');
}

// ══════════════════════════════════════════════
// ARTICLE FORM (Create / Edit)
// ══════════════════════════════════════════════
function resetForm() {
  document.getElementById('form-title').textContent = 'Nouvel article';
  document.getElementById('edit-id').value  = '';
  document.getElementById('f-title').value  = '';
  document.getElementById('f-body').value   = '';
  document.getElementById('f-author').value = '';
  document.getElementById('f-category').value = '';
  document.getElementById('f-image').value  = '';
  clearPreview();
  hideFormError();
}

function editArticle(id) {
  const a = articles.find(x => x.id === id);
  if (!a) return;

  showView('new-article');
  document.getElementById('form-title').textContent = 'Modifier l\'article';
  document.getElementById('edit-id').value  = a.id;
  document.getElementById('f-title').value  = a.title;
  document.getElementById('f-body').value   = a.body;
  document.getElementById('f-author').value = a.author;
  document.getElementById('f-category').value = a.category;
  document.getElementById('f-image').value  = a.image || '';
  if (a.image) setPreview(a.image);
  else clearPreview();
  hideFormError();
}

function saveArticle() {
  const title    = document.getElementById('f-title').value.trim();
  const body     = document.getElementById('f-body').value.trim();
  const author   = document.getElementById('f-author').value.trim();
  const category = document.getElementById('f-category').value;
  const image    = document.getElementById('f-image').value.trim() ||
                   (document.getElementById('preview-img').src || '');
  const editId   = document.getElementById('edit-id').value;

  if (!title || !body || !author || !category) {
    showFormError('Veuillez remplir tous les champs obligatoires (*).');
    return;
  }

  if (editId) {
    // UPDATE
    const idx = articles.findIndex(a => a.id === parseInt(editId));
    if (idx !== -1) {
      articles[idx] = { ...articles[idx], title, body, author, category, image };
      saveToStorage();
      showToast('Article mis à jour avec succès !');
    }
  } else {
    // CREATE
    const newId = articles.length ? Math.max(...articles.map(a => a.id)) + 1 : 1;
    articles.unshift({
      id: newId, title, body, author, category, image,
      date: new Date().toISOString()
    });
    saveToStorage();
    showToast('Article créé avec succès !');
  }

  showView('articles');
}

function cancelForm() {
  showView('articles');
}

// ── Image handling ─────────────────────────────
function handleImageUpload(event) {
  const file = event.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    setPreview(e.target.result);
    document.getElementById('f-image').value = e.target.result;
  };
  reader.readAsDataURL(file);
}

function previewFromUrl(url) {
  if (url) setPreview(url);
  else clearPreview();
}

function setPreview(src) {
  const img  = document.getElementById('preview-img');
  const ph   = document.getElementById('upload-placeholder');
  img.src    = src;
  img.classList.remove('hidden');
  ph.classList.add('hidden');
}

function clearPreview() {
  const img = document.getElementById('preview-img');
  const ph  = document.getElementById('upload-placeholder');
  img.src   = '';
  img.classList.add('hidden');
  ph.classList.remove('hidden');
}

// ── Form error ─────────────────────────────────
function showFormError(msg) {
  const el = document.getElementById('form-error');
  el.textContent = msg;
  el.classList.remove('hidden');
}
function hideFormError() {
  document.getElementById('form-error').classList.add('hidden');
}

// ══════════════════════════════════════════════
// DELETE
// ══════════════════════════════════════════════
function openDeleteModal(id) {
  pendingDeleteId = id;
  document.getElementById('modal-delete').classList.remove('hidden');
}

function closeDeleteModal() {
  pendingDeleteId = null;
  document.getElementById('modal-delete').classList.add('hidden');
}

function confirmDelete() {
  if (pendingDeleteId === null) return;
  articles = articles.filter(a => a.id !== pendingDeleteId);
  saveToStorage();
  closeDeleteModal();
  renderArticlesTable();
  renderDashboard();
  showToast('Article supprimé.', true);
}

// close modal on overlay click
document.getElementById('modal-delete').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeDeleteModal();
});

// ══════════════════════════════════════════════
// TOAST
// ══════════════════════════════════════════════
let toastTimer = null;

function showToast(msg, isError = false) {
  const toast = document.getElementById('toast');
  toast.textContent = msg;
  toast.className = 'toast' + (isError ? ' error' : '');
  toast.classList.remove('hidden');
  if (toastTimer) clearTimeout(toastTimer);
  toastTimer = setTimeout(() => {
    toast.classList.add('hidden');
  }, 3200);
}

// ══════════════════════════════════════════════
// HELPERS
// ══════════════════════════════════════════════
function escHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function formatDate(dateStr) {
  if (!dateStr) return '—';
  const d = new Date(dateStr);
  return d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
}
