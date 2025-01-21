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
                        <p class="card-text text-muted">No courses yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="index.php?action=enseignant&page=courses" class="btn btn-primary">My Courses</a>
                        <a href="index.php?action=enseignant&page=courses&add=true" class="btn btn-success">Add New Course</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
