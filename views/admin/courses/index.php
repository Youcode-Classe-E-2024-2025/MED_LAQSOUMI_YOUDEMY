<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Course Management</h1>
        <a href="index.php?action=admin" class="btn btn-secondary">Back to Dashboard</a>
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Teacher</th>
                            <th>Category</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($course['image']): ?>
                                            <img src="<?= htmlspecialchars($course['image']) ?>" 
                                                 alt="Course thumbnail" 
                                                 class="img-thumbnail me-2" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div>
                                            <strong><?= htmlspecialchars($course['titre']) ?></strong>
                                            <small class="d-block text-muted">
                                                Created: <?= date('M j, Y', strtotime($course['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($course['teacher_name']) ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?= htmlspecialchars($course['category_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <?= $course['student_count'] ?> enrolled
                                    </span>
                                </td>
                                <td>
                                    <?php if ($course['published']): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?action=admin&page=courses&view=<?= $course['id'] ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        
                                        <?php if ($course['published']): ?>
                                            <a href="index.php?action=admin&page=courses&unpublish=<?= $course['id'] ?>" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-eye-slash"></i> Unpublish
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?action=admin&page=courses&publish=<?= $course['id'] ?>" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Publish
                                            </a>
                                        <?php endif; ?>
                                        
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal<?= $course['id'] ?>">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
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
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
