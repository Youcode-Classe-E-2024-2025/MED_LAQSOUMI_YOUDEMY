<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Enrolled Courses</h1>
        <a href="index.php?action=etudiant" class="btn btn-secondary">Back to Dashboard</a>
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

    <?php if (empty($enrollments)): ?>
        <div class="alert alert-info">
            <h4 class="alert-heading">No courses yet!</h4>
            <p>You haven't enrolled in any courses yet. Start your learning journey today!</p>
            <hr>
            <a href="index.php?action=etudiant&page=courses" class="btn btn-primary">Browse Courses</a>
        </div>
    <?php else: ?>
        <!-- Course Progress Cards -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($enrollments as $enrollment): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php if (!empty($enrollment['course']['image'])): ?>
                            <img src="<?= htmlspecialchars($enrollment['course']['image']) ?>" 
                                 class="card-img-top" alt="Course Image">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($enrollment['course']['titre']) ?></h5>
                            
                            <div class="mb-3">
                                <span class="badge bg-primary">
                                    <?= htmlspecialchars($enrollment['course']['categorie_nom']) ?>
                                </span>
                                <?php foreach ($enrollment['course']['tags'] as $tag): ?>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($tag['nom']) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="progress mb-3">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: <?= $enrollment['progress'] ?>%"
                                     aria-valuenow="<?= $enrollment['progress'] ?>" 
                                     aria-valuemin="0" aria-valuemax="100">
                                    <?= $enrollment['progress'] ?>%
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">
                                    Enrolled: <?= date('M d, Y', strtotime($enrollment['date_inscription'])) ?>
                                </small>
                                <?php if ($enrollment['completed']): ?>
                                    <span class="badge bg-success">Completed</span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="card-text">
                                <small class="text-muted">
                                    By <?= htmlspecialchars($enrollment['course']['enseignant_nom']) ?>
                                </small>
                            </p>
                        </div>
                        
                        <div class="card-footer">
                            <div class="d-grid gap-2">
                                <a href="index.php?action=etudiant&page=course&id=<?= $enrollment['course']['id'] ?>" 
                                   class="btn btn-primary">Continue Learning</a>
                                <?php if (!$enrollment['completed']): ?>
                                    <a href="index.php?action=etudiant&page=complete&course=<?= $enrollment['course']['id'] ?>" 
                                       class="btn btn-success">Mark as Completed</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
