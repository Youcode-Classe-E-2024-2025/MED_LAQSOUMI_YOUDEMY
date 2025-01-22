<?php require_once 'includes/header.php'; ?>

<div class="container mt-4">
    <h2>Statistiques Globales</h2>

    <div class="row">
        <!-- Répartition par catégorie -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Répartition des Cours par Catégorie</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Catégorie</th>
                                    <th>Nombre de Cours</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['cours_par_categorie'] as $cat): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($cat['categorie']) ?></td>
                                        <td><?= $cat['total'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cours le plus populaire -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Cours le Plus Populaire</h5>
                    <?php if ($stats['cours_plus_populaire']): ?>
                        <p class="card-text">
                            <strong>Titre:</strong> <?= htmlspecialchars($stats['cours_plus_populaire']['titre']) ?><br>
                            <strong>Nombre d'étudiants:</strong> <?= $stats['cours_plus_populaire']['total_etudiants'] ?>
                        </p>
                    <?php else: ?>
                        <p class="card-text">Aucun cours n'a encore d'inscriptions.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top 3 Enseignants -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Top 3 des Enseignants</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Position</th>
                                    <th>Nom</th>
                                    <th>Nombre de Cours</th>
                                    <th>Total Étudiants</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['top_enseignants'] as $index => $enseignant): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($enseignant['nom']) ?></td>
                                        <td><?= $enseignant['total_cours'] ?></td>
                                        <td><?= $enseignant['total_etudiants'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
