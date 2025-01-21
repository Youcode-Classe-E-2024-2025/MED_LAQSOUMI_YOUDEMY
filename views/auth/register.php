<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">Register for YouDemy</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error'] ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?action=register" method="POST">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   required minlength="6">
                            <div class="form-text">Password must be at least 6 characters long.</div>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">I want to</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Choose your role</option>
                                <option value="etudiant">Learn on YouDemy</option>
                                <option value="enseignant">Teach on YouDemy</option>
                            </select>
                        </div>
                        <div id="teacherInfo" class="alert alert-info d-none">
                            Note: Teacher accounts require admin validation before you can start creating courses.
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Already have an account? <a href="index.php?action=login">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    const teacherInfo = document.getElementById('teacherInfo');
    if (this.value === 'enseignant') {
        teacherInfo.classList.remove('d-none');
    } else {
        teacherInfo.classList.add('d-none');
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
