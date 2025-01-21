<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Course Details</h1>
        <div>
            <?php if ($course['published']): ?>
                <a href="index.php?action=admin&page=courses&unpublish=<?= $course['id'] ?>" 
                   class="btn btn-warning">
                    <i class="fas fa-eye-slash"></i> Unpublish
                </a>
            <?php else: ?>
                <a href="index.php?action=admin&page=courses&publish=<?= $course['id'] ?>" 
                   class="btn btn-success">
                    <i class="fas fa-check"></i> Publish
                </a>
            <?php endif; ?>
            <button type="button" class="btn btn-danger" 
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteModal">
                <i class="fas fa-trash"></i> Delete
            </button>
            <a href="index.php?action=admin&page=courses" class="btn btn-secondary">Back to Courses</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <?php if ($course['image']): ?>
                            <img src="<?= htmlspecialchars($course['image']) ?>" 
                                 alt="Course thumbnail" 
                                 class="img-thumbnail me-3" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        <?php endif; ?>
                        <div>
                            <h2 class="card-title mb-1"><?= htmlspecialchars($course['titre']) ?></h2>
                            <p class="text-muted mb-0">
                                By <?= htmlspecialchars($course['teacher_name']) ?> | 
                                Category: <?= htmlspecialchars($course['category_name']) ?>
                            </p>
                        </div>
                    </div>

                    <h5>Description</h5>
                    <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>

                    <h5>Content</h5>
                    <div class="content-preview">
                        <?= nl2br(htmlspecialchars($course['contenu'])) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Course Statistics</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Status
                            <span class="badge bg-<?= $course['published'] ? 'success' : 'warning' ?>">
                                <?= $course['published'] ? 'Published' : 'Draft' ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Students Enrolled
                            <span class="badge bg-primary"><?= $course['student_count'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Created
                            <span><?= date('M j, Y', strtotime($course['created_at'])) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Last Updated
                            <span><?= date('M j, Y', strtotime($course['updated_at'])) ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the course "<?= htmlspecialchars($course['titre']) ?>"?</p>
                <p class="text-danger">
                    <strong>Warning:</strong> This will also remove all enrollments and student progress.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="index.php?action=admin&page=courses&delete=<?= $course['id'] ?>" 
                   class="btn btn-danger">Delete Course</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
