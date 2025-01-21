<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h1>My Courses</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="index.php?action=etudiant" 
                   class="list-group-item list-group-item-action">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="index.php?action=etudiant&page=my-courses" 
                   class="list-group-item list-group-item-action active">
                    <i class="fas fa-graduation-cap"></i> My Courses
                </a>
                <a href="index.php?action=etudiant&page=courses" 
                   class="list-group-item list-group-item-action">
                    <i class="fas fa-search"></i> Browse Courses
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <?php if (empty($enrolledCourses)): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-book-open fa-3x mb-3 text-muted"></i>
                        <h4>You haven't enrolled in any courses yet</h4>
                        <p class="text-muted mb-4">
                            Start your learning journey by browsing our available courses
                        </p>
                        <a href="index.php?action=etudiant&page=courses" 
                           class="btn btn-primary">
                            Browse Courses
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <?php foreach ($enrolledCourses as $course): ?>
                        <div class="col">
                            <div class="card h-100">
                                <?php if ($course['image']): ?>
                                    <img src="<?= htmlspecialchars($course['image']) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($course['titre']) ?>"
                                         style="height: 200px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= htmlspecialchars($course['titre']) ?>
                                    </h5>
                                    <p class="card-text text-muted">
                                        By <?= htmlspecialchars($course['teacher_name']) ?><br>
                                        Category: <?= htmlspecialchars($course['category_name']) ?>
                                    </p>
                                    <div class="progress mb-3">
                                        <div class="progress-bar" 
                                             role="progressbar" 
                                             style="width: <?= $course['completed'] ? '100%' : '0%' ?>" 
                                             aria-valuenow="<?= $course['completed'] ? '100' : '0' ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?= $course['completed'] ? 'Completed' : 'In Progress' ?>
                                        </div>
                                    </div>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            Enrolled on: <?= date('M j, Y', strtotime($course['enrollment_date'])) ?>
                                        </small>
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <div class="d-grid">
                                        <a href="index.php?action=etudiant&page=course&id=<?= $course['id'] ?>" 
                                           class="btn btn-primary">
                                            <?= $course['completed'] ? 'Review Course' : 'Continue Learning' ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
