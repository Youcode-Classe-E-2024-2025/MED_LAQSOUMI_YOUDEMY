<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Bulk Insert Tags</h1>
        <a href="index.php?action=admin&page=tags" class="btn btn-secondary">Back to Tags</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Insert Multiple Tags</h5>
        </div>
        <div class="card-body">
            <form action="index.php?action=admin&page=tags&bulk=true" method="POST">
                <div class="mb-3">
                    <label for="tags" class="form-label">Tag Names</label>
                    <textarea class="form-control" id="tags" name="tags" rows="5" required 
                              placeholder="Enter tag names separated by commas"></textarea>
                    <div class="form-text">
                        Enter multiple tag names separated by commas. Example: PHP, JavaScript, HTML, CSS
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Insert Tags</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
