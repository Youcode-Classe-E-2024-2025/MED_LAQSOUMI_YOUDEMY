<?php require_once 'includes/header.php'; ?>

<div class="container mt-4">
    <h2>Gestion des Utilisateurs</h2>
    
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
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Status</th>
                    <th>Stats</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <span class="badge <?= $user['status'] === 'active' ? 'bg-success' : 'bg-warning' ?>">
                                <?= htmlspecialchars($user['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['role'] === 'enseignant'): ?>
                                <?= $user['total_cours'] ?> cours
                            <?php elseif ($user['role'] === 'etudiant'): ?>
                                <?= $user['total_inscriptions'] ?> inscriptions
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="index.php?action=gererUtilisateurs" method="POST" class="d-inline">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <?php if ($user['status'] !== 'active'): ?>
                                    <button type="submit" name="action" value="activer" class="btn btn-success btn-sm">
                                        Activer
                                    </button>
                                <?php else: ?>
                                    <button type="submit" name="action" value="suspendre" class="btn btn-warning btn-sm">
                                        Suspendre
                                    </button>
                                <?php endif; ?>
                                <button type="submit" name="action" value="supprimer" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
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
