<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h1>Admin Dashboard</h1>

    <div class="row mt-4">
        <!-- General Statistics -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">General Statistics</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Courses
                            <span class="badge bg-primary rounded-pill"><?= $stats['general']['total_courses'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Students
                            <span class="badge bg-primary rounded-pill"><?= $stats['general']['total_students'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Teachers
                            <span class="badge bg-primary rounded-pill"><?= $stats['general']['total_teachers'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Enrollments
                            <span class="badge bg-primary rounded-pill"><?= $stats['general']['total_enrollments'] ?></span>
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
                    <h6 class="card-subtitle mb-2 text-muted"><?= $stats['most_popular_course']['titre'] ?></h6>
                    <p class="card-text">
                        Students Enrolled: <?= $stats['most_popular_course']['student_count'] ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Courses per Category -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Courses per Category</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($stats['courses_per_category'] as $category): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= $category['nom'] ?>
                                <span class="badge bg-primary rounded-pill"><?= $category['count'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Top Teachers -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Teachers</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($stats['top_teachers'] as $teacher): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= $teacher['nom'] ?>
                                <span class="badge bg-primary rounded-pill"><?= $teacher['student_count'] ?> students</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
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
                        <a href="index.php?action=admin&page=users" class="btn btn-primary">Manage Users</a>
                        <a href="index.php?action=admin&page=content" class="btn btn-success">Manage Content</a>
                        <a href="index.php?action=admin&page=categories" class="btn btn-info">Manage Categories</a>
                        <a href="index.php?action=admin&page=tags" class="btn btn-warning">Manage Tags</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
