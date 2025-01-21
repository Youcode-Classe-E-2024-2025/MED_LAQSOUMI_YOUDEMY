<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h1>Student Dashboard</h1>

    <div class="row mt-4">
        <!-- Learning Statistics -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Learning Statistics</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Enrolled Courses
                            <span class="badge bg-primary rounded-pill"><?= $stats['learning']['total_courses'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Completed Courses
                            <span class="badge bg-success rounded-pill"><?= $stats['learning']['completed_courses'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Completion Rate
                            <span class="badge bg-info rounded-pill"><?= number_format($stats['learning']['completion_rate'], 1) ?>%</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_activity)): ?>
                        <p class="text-muted">No recent activity</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($recent_activity as $activity): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= htmlspecialchars($activity['titre']) ?></h6>
                                        <small class="text-muted"><?= $activity['date'] ?></small>
                                    </div>
                                    <p class="mb-1"><?= $activity['description'] ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
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
                        <a href="index.php?action=etudiant&page=courses" class="btn btn-primary">Browse Courses</a>
                        <a href="index.php?action=etudiant&page=enrolled" class="btn btn-success">My Enrolled Courses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
