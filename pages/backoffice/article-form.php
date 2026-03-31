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
                <label>ImageS</label>
                <div class="image-upload-area" id="upload-area"
                    onclick="document.getElementById('f-image-file').click()">
                    <img id="preview-img" src="" alt="" class="hidden" />
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

    // Gérer l'upload d'image
    function handleImageUpload(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imgUrl = e.target.result;
                document.getElementById('f-image').value = imgUrl;
                previewFromUrl(imgUrl);
            };
            reader.readAsDataURL(file);
        }
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
        document.getElementById('f-body').value = '';
        document.getElementById('f-author').value = '';
        document.getElementById('f-category').value = '';
        document.getElementById('f-image').value = '';
        document.getElementById('preview-img').classList.add('hidden');
        document.getElementById('upload-placeholder').classList.remove('hidden');
        document.getElementById('form-title').textContent = 'Nouvel article';
        document.getElementById('form-error').classList.add('hidden');
    }

    function saveArticle() {
        const id = document.getElementById('edit-id').value;
        const title = document.getElementById('f-title').value;
        const body = tinymce.get('f-body').getContent(); // Récupérer le contenu TinyMCE
        const author = document.getElementById('f-author').value;
        const category = document.getElementById('f-category').value;

        // Récupérer les images uploadées
        const images = [];
        const imageFiles = document.querySelectorAll('.image-item input[type="file"]');
        imageFiles.forEach(fileInput => {
            if (fileInput.files.length > 0) {
                images.push(fileInput.files[0]);
            }
        });

        // Récupérer les images existantes (URLs)
        const existingImages = [];
        document.querySelectorAll('.existing-image').forEach(imgElement => {
            existingImages.push(imgElement.dataset.url);
        });

        if (!body || !author || !category) {
            showError('Veuillez remplir tous les champs obligatoires');
            return;
        }

        // Créer FormData pour envoyer les fichiers
        const formData = new FormData();
        formData.append('author', author);
        formData.append('category', category);
        formData.append('body', body);

        images.forEach((image, index) => {
            formData.append(`images[${index}]`, image);
        });

        if (existingImages.length > 0) {
            formData.append('existing_images', JSON.stringify(existingImages));
        }

        const url = id ? `api/articles.php?action=updateArticle&id=${id}` : 'api/articles.php?action=createArticle';

        fetch(url, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(id ? 'Article modifié avec succès!' : 'Article créé avec succès!');
                    window.location.href = 'index.php?view=articles';
                } else {
                    showError(data.error || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showError('Erreur de connexion au serveur');
            });
    }

    // Modifier handleImageUpload pour supporter plusieurs images
    function handleImageUpload(event) {
        const files = event.target.files;
        const imageContainer = document.getElementById('images-container');

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function (e) {
                const imageItem = document.createElement('div');
                imageItem.className = 'image-item';
                imageItem.innerHTML = `
                <img src="${e.target.result}" alt="Aperçu">
                <button type="button" onclick="this.parentElement.remove()">Supprimer</button>
                <input type="hidden" name="images[]" value="${e.target.result}">
            `;
                imageContainer.appendChild(imageItem);
            };

            reader.readAsDataURL(file);
        }
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