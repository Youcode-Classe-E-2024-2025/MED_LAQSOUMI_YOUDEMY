<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Courses</h1>
        <div>
            <a href="index.php?action=enseignant&page=courses&add=true" class="btn btn-success">Add New Course</a>
            <a href="index.php?action=enseignant" class="btn btn-secondary">Back to Dashboard</a>
        </div>
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
            <?php if (empty($cours)): ?>
                <div class="text-center py-4">
                    <h4 class="text-muted">No courses yet</h4>
                    <p>Start by adding your first course!</p>
                    <a href="index.php?action=enseignant&page=courses&add=true" class="btn btn-primary">Add Course</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cours as $course): ?>
                                <tr>
                                    <td><?= htmlspecialchars($course['titre']) ?></td>
                                    <td><?= htmlspecialchars($course['categorie_nom']) ?></td>
                                    <td><?= $course['nombre_inscrits'] ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="index.php?action=enseignant&page=courses&view=<?= $course['id'] ?>" 
                                               class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                            <a href="index.php?action=enseignant&page=courses&edit=<?= $course['id'] ?>" 
                                               class="btn btn-sm btn-warning">
                                                Edit
                                            </a>
                                            <a href="index.php?action=enseignant&page=enrollments&course=<?= $course['id'] ?>" 
                                               class="btn btn-sm btn-info">
                                                Enrollments
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal<?= $course['id'] ?>">
                                                Delete
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
                                                        Are you sure you want to delete course "<?= htmlspecialchars($course['titre']) ?>"?
                                                        This will also remove all enrollments.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <a href="index.php?action=enseignant&page=courses&delete=<?= $course['id'] ?>" 
                                                           class="btn btn-danger">Delete</a>
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
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
