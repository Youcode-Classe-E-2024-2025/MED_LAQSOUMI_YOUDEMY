<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Courses</h1>
        <a href="index.php?action=teacher&page=courses&create=1" class="btn btn-primary">Create New Course</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'] ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if ($courses): ?>
        <div class="row">
            <?php foreach ($courses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if ($course['image']): ?>
                            <img src="<?= $course['image'] ?>" class="card-img-top" alt="<?= $course['titre'] ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= $course['titre'] ?></h5>
                            <p class="card-text"><?= substr($course['description'], 0, 100) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-info"><?= $course['student_count'] ?? 0 ?> students</span>
                                <div class="btn-group">
                                    <a href="index.php?action=teacher&page=courses&edit=<?= $course['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $course['id'] ?>">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal<?= $course['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete the course "<?= $course['titre'] ?>"?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <a href="index.php?action=teacher&page=courses&delete=<?= $course['id'] ?>" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            You haven't created any courses yet. <a href="index.php?action=teacher&page=courses&create=1" class="alert-link">Create your first course</a>.
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
