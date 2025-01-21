<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Course</h1>
        <a href="index.php?action=teacher&page=courses" class="btn btn-secondary">Back to Courses</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="index.php?action=teacher&page=courses&edit=<?= $course['id'] ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titre" class="form-label">Course Title</label>
                    <input type="text" class="form-control" id="titre" name="titre" value="<?= $course['titre'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?= $course['description'] ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="contenu" class="form-label">Content</label>
                    <textarea class="form-control" id="contenu" name="contenu" rows="10" required><?= $course['contenu'] ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="categorie_id" class="form-label">Category</label>
                    <select class="form-select" id="categorie_id" name="categorie_id" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $category['id'] == $course['categorie_id'] ? 'selected' : '' ?>>
                                <?= $category['nom'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Course Image</label>
                    <?php if ($course['image']): ?>
                        <div class="mb-2">
                            <img src="<?= $course['image'] ?>" alt="Current course image" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <small class="form-text text-muted">Leave empty to keep current image</small>
                </div>

                <button type="submit" class="btn btn-primary">Update Course</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
