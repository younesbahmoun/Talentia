<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une nouvelle offre</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .form-card {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border-top: 4px solid #0d6efd;
        }
        
        .form-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #444;
        }
        
        .required-star {
            color: #dc3545;
        }
        
        .image-preview-container {
            text-align: center;
            margin-top: 20px;
        }
        
        .image-preview {
            max-width: 100%;
            max-height: 200px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 10px;
            display: none;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        
        .char-counter {
            font-size: 12px;
            color: #6c757d;
            text-align: right;
            margin-top: 5px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <!-- <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-briefcase me-2"></i>JobBoard
                </h1>
                <a href="offres.html" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux offres
                </a>
            </div>
        </div>
    </div> -->

    <!-- Formulaire -->
    <div class="container">
        <div class="form-card">
            <h2 class="form-title">
                <i class="fas fa-plus-circle text-primary me-2"></i>
                Publier une nouvelle offre d'emploi
            </h2>
            
            <form id="offreForm" method="POST" action="">
                
                <!-- Titre -->
                <div class="form-group">
                    <label for="titre" class="form-label">
                        Titre de l'offre <span class="required-star">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="titre" 
                           name="titre" 
                           placeholder="Ex: Développeur Laravel Senior"
                           required>
                    <!-- <div class="form-text">Maximum 255 caractères</div> -->
                </div>
                
                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="form-label">
                        Description détaillée <span class="required-star">*</span>
                    </label>
                    <textarea class="form-control" 
                              id="description" 
                              name="description" 
                              rows="5"
                              placeholder="Décrivez les missions, compétences requises, avantages..."
                              required></textarea>
                    <div class="char-counter" id="descriptionCounter">0 caractères</div>
                </div>
                
                <!-- Image (URL) -->
                <div class="form-group">
                    <label for="image" class="form-label">
                        URL de l'image <span class="required-star">*</span>
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="image" 
                           name="image" 
                           placeholder="https://example.com/image.jpg"
                           required>
                    <div class="form-text">Entrez l'URL complète de l'image (max 255 caractères)</div>
                    
                    <!-- Aperçu de l'image -->
                    <div class="image-preview-container mt-3">
                        <img id="imagePreview" class="image-preview" alt="Aperçu de l'image">
                    </div>
                </div>
                
                <div class="row">
                    <!-- Entreprise -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="entreprise" class="form-label">
                                Entreprise <span class="required-star">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="entreprise" 
                                   name="entreprise" 
                                   placeholder="Nom de l'entreprise"
                                   required>
                        </div>
                    </div>
                    
                    <!-- Type de contrat -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type_contrat" class="form-label">
                                Type de contrat <span class="required-star">*</span>
                            </label>
                            <select class="form-select" id="type_contrat" name="type_contrat" required>
                                <option value="">Sélectionnez...</option>
                                <option value="CDI">CDI</option>
                                <option value="CDD">CDD</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Stage">Stage</option>
                                <option value="Alternance">Alternance</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div>
                        <span class="required-star">*</span> Champs obligatoires
                    </div>
                    <div>
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-redo me-1"></i> Réinitialiser
                        </button>
                        <button type="submit" class="btn btn-primary btn-submit">
                            <i class="fas fa-paper-plane me-1"></i> Publier l'offre
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personnalisés -->
    <script>
        // Compteur de caractères pour la description
        // document.getElementById('description').addEventListener('input', function(e) {
        //     const length = e.target.value.length;
        //     document.getElementById('descriptionCounter').textContent = length + ' caractères';
            
        //     // Changer la couleur selon la longueur
        //     const counter = document.getElementById('descriptionCounter');
        //     if (length < 50) {
        //         counter.style.color = '#dc3545';
        //     } else if (length < 100) {
        //         counter.style.color = '#fd7e14';
        //     } else {
        //         counter.style.color = '#198754';
        //     }
        // });
        
        // // Aperçu de l'image depuis URL
        // document.getElementById('image').addEventListener('input', function(e) {
        //     const url = e.target.value;
        //     const preview = document.getElementById('imagePreview');
            
        //     if (url && isValidUrl(url)) {
        //         preview.src = url;
        //         preview.style.display = 'block';
        //     } else {
        //         preview.style.display = 'none';
        //     }
        // });
        
        // function isValidUrl(string) {
        //     try {
        //         new URL(string);
        //         return true;
        //     } catch (_) {
        //         return false;
        //     }
        // }
        
        // // Validation avant soumission
        // document.getElementById('offreForm').addEventListener('submit', function(e) {
        //     const titre = document.getElementById('titre').value;
        //     const description = document.getElementById('description').value;
            
        //     if (description.length < 20) {
        //         e.preventDefault();
        //         alert('La description doit contenir au moins 20 caractères.');
        //         document.getElementById('description').focus();
        //         return false;
        //     }
            
        //     if (titre.length > 255) {
        //         e.preventDefault();
        //         alert('Le titre ne doit pas dépasser 255 caractères.');
        //         document.getElementById('titre').focus();
        //         return false;
        //     }
            
        //     // Afficher un message de confirmation
        //     if (confirm('Voulez-vous vraiment publier cette offre ?')) {
        //         return true;
        //     } else {
        //         e.preventDefault();
        //         return false;
        //     }
        // });
        
        // // Afficher un message de bienvenue
        // window.addEventListener('DOMContentLoaded', (event) => {
        //     console.log('Formulaire de création d\'offre chargé');
        // });
    </script>

</body>
</html>