<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h1>My Learning Dashboard</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="list-group">
                <a href="index.php?action=etudiant" 
                   class="list-group-item list-group-item-action active">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="index.php?action=etudiant&page=my-courses" 
                   class="list-group-item list-group-item-action">
                    <i class="fas fa-graduation-cap"></i> My Courses
                </a>
                <a href="index.php?action=etudiant&page=courses" 
                   class="list-group-item list-group-item-action">
                    <i class="fas fa-search"></i> Browse Courses
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recently Enrolled Courses</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($myCourses)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x mb-3 text-muted"></i>
                            <h5>You haven't enrolled in any courses yet</h5>
                            <p class="mb-3">Start learning by browsing our available courses</p>
                            <a href="index.php?action=etudiant&page=courses" 
                               class="btn btn-primary">
                                Browse Courses
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            <?php foreach (array_slice($myCourses, 0, 4) as $course): ?>
                                <div class="col">
                                    <div class="card h-100">
                                        <?php if ($course['image']): ?>
                                            <img src="<?= htmlspecialchars($course['image']) ?>" 
                                                 class="card-img-top" 
                                                 alt="<?= htmlspecialchars($course['titre']) ?>"
                                                 style="height: 150px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <?= htmlspecialchars($course['titre']) ?>
                                            </h5>
                                            <p class="card-text text-muted">
                                                By <?= htmlspecialchars($course['teacher_name']) ?>
                                            </p>
                                            <div class="progress mb-2">
                                                <div class="progress-bar" 
                                                     role="progressbar" 
                                                     style="width: <?= $course['completed'] ? '100%' : '0%' ?>" 
                                                     aria-valuenow="<?= $course['completed'] ? '100' : '0' ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    <?= $course['completed'] ? 'Completed' : 'In Progress' ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="index.php?action=etudiant&page=course&id=<?= $course['id'] ?>" 
                                               class="btn btn-primary btn-sm">
                                                Continue Learning
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($myCourses) > 4): ?>
                            <div class="text-center mt-4">
                                <a href="index.php?action=etudiant&page=my-courses" 
                                   class="btn btn-outline-primary">
                                    View All My Courses
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
