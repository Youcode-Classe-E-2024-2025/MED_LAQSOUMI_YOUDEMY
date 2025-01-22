<?php require_once 'includes/header.php'; ?>

<div class="container mt-4">
    <h2>Gestion des Cours</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Enseignant</th>
                    <th>Catégorie</th>
                    <th>Status</th>
                    <th>Inscrits</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= htmlspecialchars($course['id']) ?></td>
                        <td><?= htmlspecialchars($course['titre']) ?></td>
                        <td><?= htmlspecialchars($course['enseignant_nom']) ?></td>
                        <td><?= htmlspecialchars($course['categorie_nom']) ?></td>
                        <td>
                            <span class="badge <?= $course['status'] === 'approved' ? 'bg-success' : 'bg-warning' ?>">
                                <?= htmlspecialchars($course['status']) ?>
                            </span>
                        </td>
                        <td><?= $course['total_inscrits'] ?> étudiants</td>
                        <td>
                            <form action="index.php?action=gererContenus" method="POST" class="d-inline">
                                <input type="hidden" name="cours_id" value="<?= $course['id'] ?>">
                                <?php if ($course['status'] !== 'approved'): ?>
                                    <button type="submit" name="action" value="approuver" class="btn btn-success btn-sm">
                                        Approuver
                                    </button>
                                <?php endif; ?>
                                <?php if ($course['status'] !== 'rejected'): ?>
                                    <button type="submit" name="action" value="rejeter" class="btn btn-warning btn-sm">
                                        Rejeter
                                    </button>
                                <?php endif; ?>
                                <button type="submit" name="action" value="supprimer" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
