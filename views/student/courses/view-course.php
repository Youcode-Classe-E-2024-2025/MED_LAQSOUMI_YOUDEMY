<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="list-group mb-4">
                <a href="index.php?action=etudiant" class="list-group-item list-group-item-action">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="index.php?action=etudiant&page=my-courses" class="list-group-item list-group-item-action">
                    <i class="fas fa-graduation-cap"></i> My Courses
                </a>
                <a href="index.php?action=etudiant&page=courses" class="list-group-item list-group-item-action">
                    <i class="fas fa-search"></i> Browse Courses
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?action=etudiant">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?action=etudiant&page=courses">Courses</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($course['titre']) ?></li>
                </ol>
            </nav>

            <div class="card">
                <?php if ($course['image']): ?>
                    <img src="<?= htmlspecialchars($course['image']) ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($course['titre']) ?>"
                         style="height: 300px; object-fit: cover;">
                <?php endif; ?>
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="card-title"><?= htmlspecialchars($course['titre']) ?></h1>
                        <?php if (!$isEnrolled): ?>
                            <form action="index.php?action=etudiant&page=enroll" method="POST">
                                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt"></i> Enroll Now
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> Enrolled
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p class="text-muted mb-0">
                                <i class="fas fa-user"></i> Instructor
                            </p>
                            <p class="fw-bold"><?= htmlspecialchars($course['teacher_name']) ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-0">
                                <i class="fas fa-folder"></i> Category
                            </p>
                            <p class="fw-bold"><?= htmlspecialchars($course['category_name']) ?></p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-0">
                                <i class="fas fa-users"></i> Students Enrolled
                            </p>
                            <p class="fw-bold"><?= $course['student_count'] ?></p>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Course Description</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= nl2br(htmlspecialchars($course['description'])) ?></p>
                        </div>
                    </div>

                    <?php if ($isEnrolled): ?>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Course Progress</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($course['completed']): ?>
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i> 
                                        Congratulations! You have completed this course.
                                    </div>
                                <?php else: ?>
                                    <form action="index.php?action=etudiant&page=complete-course" method="POST">
                                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i> Mark as Completed
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
