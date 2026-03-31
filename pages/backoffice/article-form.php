<div class="form-card">
    <h2 id="form-title">Nouvel article</h2>
    <input type="hidden" id="edit-id" />
    <div class="form-grid">
        <div class="form-col-left">
            <div class="form-group">
                <label>Contenu / Description *</label>
                <textarea id="f-body" rows="6" placeholder="Rédigez le contenu de l'article…"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Date *</label>
                    <?php
                    $aujourdhui = date('Y-m-d');
                    ?>

                    <input type="date" name="date_debut" value="<?php echo $aujourdhui; ?>">
                </div>
                <div class="form-group">
                    <label>Catégorie *</label>
                    <select id="f-category">
                        <option value="">-- Chargement... --</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-col-right">
            <div class="form-group">
                <label>Images</label>
                <div class="image-upload-area" id="upload-area"
                    onclick="document.getElementById('f-image-file').click()">
                    <div class="upload-placeholder" id="upload-placeholder">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        <span>Cliquer pour téléverser</span>
                        <small>PNG, JPG, WEBP</small>
                    </div>
                </div>
                <input type="file" id="f-image-file" accept="image/*" multiple style="display:none"
                    onchange="handleImageUpload(event)" />
                <div id="images-container" class="images-container"></div>
            </div>
        </div>
    </div>
    <div id="form-error" class="form-error hidden"></div>
    <div class="form-actions">
        <button class="btn-secondary" onclick="cancelForm()">Annuler</button>
        <button class="btn-primary" onclick="saveArticle()">Enregistrer l'article</button>
    </div>
</div>

<script>
    let categories = [];

    // Charger les catégories
    function loadCategories() {
        fetch('api/articles.php?action=getCategories')
            .then(response => response.json())
            .then(data => {
                categories = data;
                const select = document.getElementById('f-category');
                select.innerHTML = '<option value="">-- Choisir --</option>';
                data.forEach(cat => {
                    select.innerHTML += `<option value="${cat.name}">${cat.name}</option>`;
                });
            })
            .catch(error => console.error('Erreur:', error));
    }

    function previewFromUrl(url) {
        const previewImg = document.getElementById('preview-img');
        const uploadPlaceholder = document.getElementById('upload-placeholder');

        if (url) {
            previewImg.src = url;
            previewImg.classList.remove('hidden');
            uploadPlaceholder.classList.add('hidden');
        } else {
            previewImg.classList.add('hidden');
            uploadPlaceholder.classList.remove('hidden');
        }
    }

    function cancelForm() {
        document.getElementById('edit-id').value = '';
        document.getElementById('f-title').value = '';
        if (tinymce.get('f-body')) {
            tinymce.get('f-body').setContent('');
        }
        document.getElementById('f-author').value = '';
        document.getElementById('f-category').value = '';
        document.getElementById('f-image').value = '';

        const imageContainer = document.getElementById('images-container');
        imageContainer.innerHTML = '';

        document.getElementById('preview-img').classList.add('hidden');
        document.getElementById('upload-placeholder').classList.remove('hidden');
        document.getElementById('form-title').textContent = 'Nouvel article';
        document.getElementById('form-error').classList.add('hidden');
    }

    function saveArticle() {
        const id = document.getElementById('edit-id').value;

        let title = '';
        const bodyContent = tinymce.get('f-body').getContent();
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = bodyContent;
        const firstH1 = tempDiv.querySelector('h1');

        if (firstH1) {
            title = firstH1.textContent.trim();
        }

        const body = tempDiv.innerHTML.trim();

        const author = 'Administrateur'; 

        const category = document.getElementById('f-category').value;

        const images = [];
        document.querySelectorAll('#images-container .image-item input[name="images[]"]').forEach(input => {
            if (input.value) {
                images.push(input.value);
            }
        });

        if (!title || !body || !author || !category) {
            showError('Veuillez remplir tous les champs obligatoires (titre H1, contenu, catégorie)');
            return;
        }

        const articleData = {
            title: title,
            body: body,
            author: author,
            category: category,
            images: images
        };

        if (id) {
            articleData.id = id;
        }

        const url = id ? `api/articles.php?action=updateArticle&id=${id}` : 'api/articles.php?action=createArticle';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(articleData)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(id ? 'Article modifié avec succès!' : 'Article créé avec succès!');
                    setTimeout(() => {
                        window.location.href = 'index.php?view=articles';
                    }, 1000);
                } else {
                    showError(data.error || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showError('Erreur de connexion au serveur');
            });
    }

    function handleImageUpload(event) {
        const files = event.target.files;
        const imageContainer = document.getElementById('images-container');

        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            // Vérifier le type de fichier
            if (!file.type.startsWith('image/')) {
                showError(`Le fichier "${file.name}" n'est pas une image`);
                continue;
            }

            // Vérifier la taille (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                showError(`L'image "${file.name}" dépasse 5MB`);
                continue;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                const imageItem = document.createElement('div');
                imageItem.className = 'image-item';
                imageItem.setAttribute('data-filename', file.name.length > 15 ? file.name.substring(0, 12) + '...' : file.name);

                // Stocker les données de l'image
                const imageData = e.target.result;

                imageItem.innerHTML = `
                <img src="${imageData}" alt="${escapeHtml(file.name)}">
                <button type="button" onclick="removeImage(this, '${imageData}')" title="Supprimer">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <input type="hidden" name="images[]" value="${imageData}">
            `;

                imageContainer.appendChild(imageItem);
            };

            reader.readAsDataURL(file);
        }

        // Réinitialiser l'input file pour permettre de re-uploader les mêmes fichiers
        event.target.value = '';
    }

    function removeImage(button, imageData) {
        const imageItem = button.closest('.image-item');
        if (imageItem) {
            imageItem.remove();
        }
    }

    // Fonction utilitaire pour échapper le HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    function showError(message) {
        const errorDiv = document.getElementById('form-error');
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
        setTimeout(() => {
            errorDiv.classList.add('hidden');
        }, 5000);
    }

    // Charger les catégories au démarrage
    loadCategories();
</script>