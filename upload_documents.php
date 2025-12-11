<?php
require_once 'config.php';

if(!isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();
$conn = $db->connect();

// Handle document upload
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $document_type = sanitize($_POST['document_type']);
    
    if(isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        $file_type = $_FILES['document']['type'];
        $file_size = $_FILES['document']['size'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if(!in_array($file_type, $allowed_types)) {
            showError('Invalid file type. Only JPG, PNG and PDF allowed');
        } elseif($file_size > $max_size) {
            showError('File too large. Maximum 5MB allowed');
        } else {
            $file_extension = pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION);
            $new_filename = 'doc_' . $_SESSION['user_id'] . '_' . $document_type . '_' . time() . '.' . $file_extension;
            $upload_path = UPLOAD_PATH . 'documents/' . $new_filename;
            
            if(move_uploaded_file($_FILES['document']['tmp_name'], $upload_path)) {
                $document_url = 'uploads/documents/' . $new_filename;
                
                $stmt = $conn->prepare("INSERT INTO documents (user_id, document_type, document_url) VALUES (?, ?, ?)");
                
                if($stmt->execute([$_SESSION['user_id'], $document_type, $document_url])) {
                    showSuccess('Document uploaded successfully! Awaiting verification.');
                    redirect('upload_documents.php');
                } else {
                    showError('Failed to save document information');
                }
            } else {
                showError('Failed to upload file');
            }
        }
    } else {
        showError('Please select a file');
    }
}

// Get user documents
$stmt = $conn->prepare("SELECT * FROM documents WHERE user_id = ? ORDER BY uploaded_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$documents = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Documents - TITAN'S DRIVE</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <section class="documents-section">
        <div class="container">
            <h1 class="section-title">Document Upload</h1>
            <p class="text-center">Upload your documents for verification to complete bookings</p>
            
            <?php if($error = getError()): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if($success = getSuccess()): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="documents-grid">
                <!-- Upload Form -->
                <div class="upload-card">
                    <h2>Upload New Document</h2>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="document_type">Document Type *</label>
                            <select class="form-control" id="document_type" name="document_type" required>
                                <option value="">Select Document Type</option>
                                <option value="license">Driving License</option>
                                <option value="id_proof">ID Proof (Aadhar/PAN/Passport)</option>
                                <option value="address_proof">Address Proof</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="document">Upload Document *</label>
                            <input type="file" class="form-control" id="document" name="document" 
                                   accept=".jpg,.jpeg,.png,.pdf" required>
                            <small>Accepted formats: JPG, PNG, PDF (Max 5MB)</small>
                        </div>
                        
                        <div class="document-preview" id="preview" style="display:none;">
                            <img id="previewImage" src="" alt="Preview" style="max-width: 100%; border-radius: 8px;">
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">Upload Document</button>
                    </form>
                    
                    <div class="upload-info mt-3">
                        <h3>Important Information</h3>
                        <ul>
                            <li>All documents must be clear and readable</li>
                            <li>Documents will be verified within 24 hours</li>
                            <li>Valid driving license is mandatory for car rental</li>
                            <li>Ensure document is not expired</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Uploaded Documents -->
                <div class="uploaded-docs-card">
                    <h2>My Documents</h2>
                    
                    <?php if(count($documents) > 0): ?>
                        <div class="documents-list">
                            <?php foreach($documents as $doc): ?>
                                <div class="document-item">
                                    <div class="doc-icon">
                                        <?php if(strpos($doc['document_url'], '.pdf') !== false): ?>
                                            📄
                                        <?php else: ?>
                                            🖼️
                                        <?php endif; ?>
                                    </div>
                                    <div class="doc-info">
                                        <h4><?php echo ucfirst(str_replace('_', ' ', $doc['document_type'])); ?></h4>
                                        <p class="doc-date">Uploaded: <?php echo date('d M Y', strtotime($doc['uploaded_at'])); ?></p>
                                        <span class="badge badge-<?php echo $doc['verification_status']; ?>">
                                            <?php echo ucfirst($doc['verification_status']); ?>
                                        </span>
                                        <?php if($doc['verification_status'] == 'rejected' && $doc['rejection_reason']): ?>
                                            <p class="rejection-reason">Reason: <?php echo htmlspecialchars($doc['rejection_reason']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="doc-actions">
                                        <a href="<?php echo $doc['document_url']; ?>" target="_blank" class="btn-small">View</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>No documents uploaded yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    <script>
        document.getElementById('document').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if(file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('preview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    
    <style>
        .documents-section {
            padding: 3rem 0;
            background: var(--light-color);
        }
        
        .documents-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .upload-card, .uploaded-docs-card {
            background: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .upload-card h2, .uploaded-docs-card h2 {
            margin-bottom: 1.5rem;
            color: var(--dark-color);
        }
        
        .document-preview {
            margin: 1rem 0;
            padding: 1rem;
            background: var(--light-color);
            border-radius: 8px;
        }
        
        .upload-info {
            padding: 1.5rem;
            background: var(--light-color);
            border-radius: 8px;
        }
        
        .upload-info h3 {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .upload-info ul {
            padding-left: 1.5rem;
        }
        
        .upload-info li {
            margin-bottom: 0.5rem;
            color: var(--gray-color);
        }
        
        .documents-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .document-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: var(--light-color);
            border-radius: 10px;
        }
        
        .doc-icon {
            font-size: 2.5rem;
        }
        
        .doc-info {
            flex: 1;
        }
        
        .doc-info h4 {
            margin-bottom: 0.3rem;
            color: var(--dark-color);
        }
        
        .doc-date {
            font-size: 0.85rem;
            color: var(--gray-color);
            margin-bottom: 0.5rem;
        }
        
        .badge-verified {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .rejection-reason {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--danger-color);
        }
        
        @media (max-width: 768px) {
            .documents-grid {
                grid-template-columns: 1fr;
            }
            
            .document-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</body>
</html>