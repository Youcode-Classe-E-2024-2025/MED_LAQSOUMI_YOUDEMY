<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <!-- Course Content -->
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?action=etudiant">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?action=etudiant&page=courses">Courses</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($course['titre']) ?></li>
                </ol>
            </nav>

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

            <div class="card mb-4">
                <?php if (!empty($course['image'])): ?>
                    <img src="<?= htmlspecialchars($course['image']) ?>" 
                         class="card-img-top" alt="Course Image">
                <?php endif; ?>
                
                <div class="card-body">
                    <h1 class="card-title"><?= htmlspecialchars($course['titre']) ?></h1>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary">
                            <?= htmlspecialchars($course['categorie_nom']) ?>
                        </span>
                        <?php foreach ($course['tags'] as $tag): ?>
                            <span class="badge bg-secondary">
                                <?= htmlspecialchars($tag['nom']) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>

                    <p class="card-text"><?= nl2br(htmlspecialchars($course['description'])) ?></p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>What you'll learn</h5>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($course['objectifs'] as $objectif): ?>
                                    <li class="list-group-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        <?= htmlspecialchars($objectif) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Prerequisites</h5>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($course['prerequis'] as $prerequis): ?>
                                    <li class="list-group-item">
                                        <i class="fas fa-arrow-right text-primary me-2"></i>
                                        <?= htmlspecialchars($prerequis) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Content Sections -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Course Content</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="courseContent">
                        <?php foreach ($course['sections'] as $index => $section): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" 
                                            type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#section<?= $index ?>">
                                        <?= htmlspecialchars($section['titre']) ?>
                                    </button>
                                </h2>
                                <div id="section<?= $index ?>" 
                                     class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>"
                                     data-bs-parent="#courseContent">
                                    <div class="accordion-body">
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($section['lessons'] as $lesson): ?>
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="fas fa-play-circle text-primary me-2"></i>
                                                            <?= htmlspecialchars($lesson['titre']) ?>
                                                        </div>
                                                        <span class="badge bg-light text-dark">
                                                            <?= $lesson['duree'] ?> min
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Course Information</h5>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <i class="fas fa-user text-primary me-2"></i>
                            Instructor: <?= htmlspecialchars($course['enseignant_nom']) ?>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-users text-primary me-2"></i>
                            <?= $course['nombre_inscrits'] ?> students enrolled
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <?= $course['duree_totale'] ?> hours of content
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            Last updated: <?= date('M d, Y', strtotime($course['date_mise_a_jour'])) ?>
                        </li>
                    </ul>

                    <?php if ($isEnrolled): ?>
                        <div class="d-grid gap-2">
                            <a href="#courseContent" class="btn btn-success">
                                <i class="fas fa-play me-2"></i>Start Learning
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="d-grid gap-2">
                            <a href="index.php?action=etudiant&page=enroll&course=<?= $course['id'] ?>" 
                               class="btn btn-primary">
                                <i class="fas fa-graduation-cap me-2"></i>Enroll Now
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($course['autres_cours'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">More from this Instructor</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php foreach ($course['autres_cours'] as $autre_cours): ?>
                                <a href="index.php?action=etudiant&page=course&id=<?= $autre_cours['id'] ?>" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= htmlspecialchars($autre_cours['titre']) ?></h6>
                                        <small class="text-muted"><?= $autre_cours['nombre_inscrits'] ?> students</small>
                                    </div>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($autre_cours['categorie_nom']) ?>
                                    </small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
