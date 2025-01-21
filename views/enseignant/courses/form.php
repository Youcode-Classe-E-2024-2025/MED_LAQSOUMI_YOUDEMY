<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= isset($cours) ? 'Edit Course' : 'Add New Course' ?></h1>
        <a href="index.php?action=enseignant&page=courses" class="btn btn-secondary">Back to Courses</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="index.php?action=enseignant&page=courses<?= isset($cours) ? '&edit=' . $cours->getId() : '&add=true' ?>" 
                  method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titre" class="form-label">Title</label>
                    <input type="text" class="form-control" id="titre" name="titre" required
                           value="<?= isset($cours) ? htmlspecialchars($cours->getTitre()) : '' ?>">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?= isset($cours) ? htmlspecialchars($cours->getDescription()) : '' ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="contenu" class="form-label">Content</label>
                    <textarea class="form-control" id="contenu" name="contenu" rows="10" required><?= isset($cours) ? htmlspecialchars($cours->getContenu()) : '' ?></textarea>
                    <div class="form-text">
                        You can use Markdown formatting for your content. HTML is also supported.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="categorie_id" class="form-label">Category</label>
                    <select class="form-select" id="categorie_id" name="categorie_id" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" 
                                <?= isset($cours) && $cours->getCategorie()->getId() == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tags" class="form-label">Tags</label>
                    <select class="form-select" id="tags" name="tags[]" multiple>
                        <?php foreach ($tags as $tag): ?>
                            <option value="<?= $tag['id'] ?>"
                                <?= isset($selectedTags) && in_array($tag['id'], $selectedTags) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tag['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">
                        Hold Ctrl (Windows) or Command (Mac) to select multiple tags.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <?= isset($cours) ? 'Update Course' : 'Create Course' ?>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Initialize Select2 for better tag selection -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if Select2 is available
    if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
        $('#tags').select2({
            placeholder: 'Select tags',
            allowClear: true
        });
    }
});
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
