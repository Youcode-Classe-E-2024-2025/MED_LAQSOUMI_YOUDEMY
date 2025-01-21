<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>User Management</h1>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utilisateurs as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['nom']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 
                                        ($user['role'] === 'enseignant' ? 'success' : 'primary') ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['role'] === 'enseignant' && !$user['validated']): ?>
                                        <span class="badge bg-warning">Pending Validation</span>
                                    <?php else: ?>
                                        <span class="badge bg-<?= $user['active'] ? 'success' : 'danger' ?>">
                                            <?= $user['active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['role'] !== 'admin'): ?>
                                        <?php if ($user['role'] === 'enseignant' && !$user['validated']): ?>
                                            <a href="index.php?action=admin&page=users&validate=<?= $user['id'] ?>" 
                                               class="btn btn-sm btn-success">
                                                Validate
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($user['active']): ?>
                                            <a href="index.php?action=admin&page=users&suspend=<?= $user['id'] ?>" 
                                               class="btn btn-sm btn-warning">
                                                Suspend
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?action=admin&page=users&activate=<?= $user['id'] ?>" 
                                               class="btn btn-sm btn-info">
                                                Activate
                                            </a>
                                        <?php endif; ?>
                                        
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal<?= $user['id'] ?>">
                                            Delete
                                        </button>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal<?= $user['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirm Deletion</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete user <?= htmlspecialchars($user['nom']) ?>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <a href="index.php?action=admin&page=users&delete=<?= $user['id'] ?>" 
                                                           class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
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
