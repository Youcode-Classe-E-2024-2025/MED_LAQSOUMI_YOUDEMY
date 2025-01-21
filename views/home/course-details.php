<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($course['titre']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <?php if ($course['image']): ?>
                            <img src="<?= htmlspecialchars($course['image']) ?>" 
                                 alt="Course thumbnail" 
                                 class="img-thumbnail me-3" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        <?php endif; ?>
                        <div>
                            <h1 class="card-title mb-1"><?= htmlspecialchars($course['titre']) ?></h1>
                            <p class="text-muted mb-0">
                                By <?= htmlspecialchars($course['teacher_name']) ?> | 
                                Category: <?= htmlspecialchars($course['category_name']) ?>
                            </p>
                        </div>
                    </div>

                    <h4>Description</h4>
                    <p class="card-text"><?= nl2br(htmlspecialchars($course['description'])) ?></p>

                    <h4>What You'll Learn</h4>
                    <div class="content-preview mb-4">
                        <?= nl2br(htmlspecialchars($course['contenu'])) ?>
                    </div>

                    <?php if (!isset($_SESSION['user'])): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Please <a href="index.php?action=login">login</a> or 
                            <a href="index.php?action=register">register</a> to enroll in this course.
                        </div>
                    <?php elseif ($_SESSION['user']['role'] === 'etudiant'): ?>
                        <a href="index.php?action=etudiant&page=enroll&course=<?= $course['id'] ?>" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-graduation-cap"></i> Enroll Now
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Course Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Students Enrolled
                            <span class="badge bg-primary rounded-pill">
                                <?= $course['student_count'] ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Category
                            <span class="badge bg-secondary rounded-pill">
                                <?= htmlspecialchars($course['category_name']) ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Last Updated
                            <span><?= date('M j, Y', strtotime($course['updated_at'])) ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <?php if (!isset($_SESSION['user'])): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Get Started Today</h5>
                        <p class="card-text">
                            Create an account to enroll in this course and start learning!
                        </p>
                        <a href="index.php?action=register" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-user-plus"></i> Register Now
                        </a>
                        <a href="index.php?action=login" class="btn btn-outline-primary w-100">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
