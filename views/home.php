<?php require_once __DIR__ . '/layouts/header.php'; ?>

<div class="container mt-4">
    <!-- Hero Section -->
    <div class="row align-items-center py-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold">Welcome to YouDemy</h1>
            <p class="lead mb-4">Discover a world of knowledge with our diverse range of online courses. Learn from expert instructors and advance your skills at your own pace.</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a href="index.php?action=courses" class="btn btn-primary btn-lg px-4 me-md-2">Browse Courses</a>
                <?php if (!isset($_SESSION['user'])): ?>
                    <a href="index.php?action=register" class="btn btn-outline-primary btn-lg px-4">Join Now</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-6">
            <img src="assets/images/hero-image.jpg" alt="Online Learning" class="img-fluid rounded shadow">
        </div>
    </div>

    <!-- Featured Categories -->
    <section class="py-5">
        <h2 class="text-center mb-4">Popular Categories</h2>
        <div class="row g-4">
            <?php
            require_once __DIR__ . '/../models/Category.php';
            $categories = Category::findAll();
            foreach ($categories as $category): ?>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($category['nom']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($category['description']) ?></p>
                            <a href="index.php?action=courses&category=<?= $category['id'] ?>" 
                               class="btn btn-outline-primary">Explore Courses</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-5 bg-light rounded">
        <div class="container">
            <h2 class="text-center mb-4">Why Choose YouDemy?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                        <h4>Expert Instructors</h4>
                        <p>Learn from industry experts who are passionate about teaching.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                        <h4>Learn at Your Pace</h4>
                        <p>Access course content anytime, anywhere, and learn at your own speed.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-certificate fa-3x text-primary mb-3"></i>
                        <h4>Get Certified</h4>
                        <p>Earn certificates upon completion to showcase your achievements.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 text-center">
        <h2 class="mb-4">Ready to Start Learning?</h2>
        <p class="lead mb-4">Join thousands of students already learning on YouDemy</p>
        <a href="index.php?action=courses" class="btn btn-primary btn-lg">Get Started</a>
    </section>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
