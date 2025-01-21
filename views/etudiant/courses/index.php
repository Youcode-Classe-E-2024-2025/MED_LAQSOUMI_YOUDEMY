<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Browse Courses</h1>
        <a href="index.php?action=etudiant" class="btn btn-secondary">Back to Dashboard</a>
    </div>

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

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="index.php" method="GET" class="row g-3">
                <input type="hidden" name="action" value="etudiant">
                <input type="hidden" name="page" value="courses">
                
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                           placeholder="Search courses...">
                </div>
                
                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" 
                                <?= isset($_GET['category']) && $_GET['category'] == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="sort" class="form-label">Sort By</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="recent" <?= (!isset($_GET['sort']) || $_GET['sort'] == 'recent') ? 'selected' : '' ?>>
                            Most Recent
                        </option>
                        <option value="popular" <?= (isset($_GET['sort']) && $_GET['sort'] == 'popular') ? 'selected' : '' ?>>
                            Most Popular
                        </option>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Course Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($cours as $course): ?>
            <div class="col">
                <div class="card h-100">
                    <?php if (!empty($course['image'])): ?>
                        <img src="<?= htmlspecialchars($course['image']) ?>" class="card-img-top" alt="Course Image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($course['titre']) ?></h5>
                        <p class="card-text"><?= substr(htmlspecialchars($course['description']), 0, 100) ?>...</p>
                        
                        <div class="mb-2">
                            <span class="badge bg-primary"><?= htmlspecialchars($course['categorie_nom']) ?></span>
                            <?php foreach ($course['tags'] as $tag): ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($tag['nom']) ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                By <?= htmlspecialchars($course['enseignant_nom']) ?>
                            </small>
                            <small class="text-muted">
                                <?= $course['nombre_inscrits'] ?> students
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-grid">
                            <?php if ($course['is_enrolled']): ?>
                                <a href="index.php?action=etudiant&page=course&id=<?= $course['id'] ?>" 
                                   class="btn btn-success">Continue Learning</a>
                            <?php else: ?>
                                <a href="index.php?action=etudiant&page=enroll&course=<?= $course['id'] ?>" 
                                   class="btn btn-primary">Enroll Now</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Course pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $current_page == $i ? 'active' : '' ?>">
                        <a class="page-link" href="index.php?action=etudiant&page=courses&p=<?= $i ?><?= $query_string ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
