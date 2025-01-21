<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Course Enrollments</h1>
        <a href="index.php?action=enseignant&page=courses" class="btn btn-secondary">Back to Courses</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Students Enrolled in "<?= htmlspecialchars($cours->getTitre()) ?>"</h5>
        </div>
        <div class="card-body">
            <?php if (empty($inscriptions)): ?>
                <div class="text-center py-4">
                    <h4 class="text-muted">No students enrolled yet</h4>
                    <p>Share your course link to get students enrolled!</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Enrollment Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscriptions as $inscription): ?>
                                <tr>
                                    <td><?= htmlspecialchars($inscription['nom']) ?></td>
                                    <td><?= htmlspecialchars($inscription['email']) ?></td>
                                    <td><?= date('M j, Y', strtotime($inscription['date_inscription'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $inscription['completed'] ? 'success' : 'warning' ?>">
                                            <?= $inscription['completed'] ? 'Completed' : 'In Progress' ?>
                                        </span>
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
