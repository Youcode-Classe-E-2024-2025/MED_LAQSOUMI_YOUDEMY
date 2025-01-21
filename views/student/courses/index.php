<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Browse Courses</h1>
        </div>
        <div class="col-md-4">
            <form action="index.php" method="GET" class="d-flex gap-2">
                <input type="hidden" name="action" value="etudiant">
                <input type="hidden" name="page" value="courses">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search courses..." 
                       value="<?= htmlspecialchars($search ?? '') ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="list-group mb-4">
                <a href="index.php?action=etudiant" 
                   class="list-group-item list-group-item-action">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="index.php?action=etudiant&page=my-courses" 
                   class="list-group-item list-group-item-action">
                    <i class="fas fa-graduation-cap"></i> My Courses
                </a>
                <a href="index.php?action=etudiant&page=courses" 
                   class="list-group-item list-group-item-action active">
                    <i class="fas fa-search"></i> Browse Courses
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Categories</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="index.php?action=etudiant&page=courses" 
                           class="list-group-item list-group-item-action <?= !isset($_GET['category']) ? 'active' : '' ?>">
                            All Categories
                        </a>
                        <?php foreach ($categories as $category): ?>
                            <a href="index.php?action=etudiant&page=courses&category=<?= $category['id'] ?>" 
                               class="list-group-item list-group-item-action <?= (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'active' : '' ?>">
                                <?= htmlspecialchars($category['nom']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($coursesData['courses'] as $course): ?>
                    <div class="col">
                        <div class="card h-100">
                            <?php if ($course['image']): ?>
                                <img src="<?= htmlspecialchars($course['image']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($course['titre']) ?>"
                                     style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($course['titre']) ?></h5>
                                <p class="card-text text-muted">
                                    By <?= htmlspecialchars($course['teacher_name']) ?>
                                </p>
                                <p class="card-text">
                                    <?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <?= $course['student_count'] ?> students enrolled
                                    </small>
                                    <a href="index.php?action=etudiant&page=course&id=<?= $course['id'] ?>" 
                                       class="btn btn-primary btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($coursesData['total_pages'] > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $coursesData['total_pages']; $i++): ?>
                            <li class="page-item <?= $i === $coursesData['current_page'] ? 'active' : '' ?>">
                                <a class="page-link" 
                                   href="index.php?action=etudiant&page=courses&p=<?= $i ?><?= isset($_GET['category']) ? '&category=' . $_GET['category'] : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
