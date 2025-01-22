<?php require_once 'includes/header.php'; ?>

<div class="container mt-4">
    <h2>Gestion des Tags</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Ajouter des Tags</h5>
            <form action="index.php?action=insererTags" method="POST">
                <div class="form-group">
                    <label for="tags">Tags (séparés par des virgules)</label>
                    <textarea class="form-control" id="tags" name="tags" rows="3" 
                              placeholder="Exemple: php, javascript, html, css"></textarea>
                    <small class="form-text text-muted">
                        Entrez plusieurs tags séparés par des virgules. Les tags existants seront ignorés.
                    </small>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Ajouter les Tags</button>
            </form>
        </div>
    </div>

    <?php if (!empty($existingTags)): ?>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Tags Existants</h5>
            <div class="tags-container">
                <?php foreach ($existingTags as $tag): ?>
                    <span class="badge bg-secondary me-2 mb-2"><?= htmlspecialchars($tag['nom']) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
