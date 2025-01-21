<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h1>Teacher Dashboard</h1>

    <div class="row mt-4">
        <!-- Teaching Statistics -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Teaching Statistics</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Courses
                            <span class="badge bg-primary rounded-pill"><?= $stats['teaching']['total_courses'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Students
                            <span class="badge bg-primary rounded-pill"><?= $stats['teaching']['total_students'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Average Completion Rate
                            <span class="badge bg-primary rounded-pill"><?= number_format($stats['teaching']['avg_completion_rate'], 1) ?>%</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Most Popular Course -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Most Popular Course</h5>
                </div>
                <div class="card-body">
                    <?php if ($stats['most_popular_course']): ?>
                        <h6 class="card-subtitle mb-2 text-muted"><?= $stats['most_popular_course']['titre'] ?></h6>
                        <p class="card-text">
                            Students Enrolled: <?= $stats['most_popular_course']['enrollment_count'] ?>
                        </p>
                    <?php else: ?>
                        <p class="card-text text-muted">No courses available yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Recent Courses -->
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Your Courses</h5>
                    <a href="index.php?action=teacher&page=courses&create=1" class="btn btn-primary btn-sm">Add New Course</a>
                </div>
                <div class="card-body">
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
                        <p class="text-muted">You haven't created any courses yet.</p>
                        <a href="index.php?action=teacher&page=courses&create=1" class="btn btn-primary">Create Your First Course</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
