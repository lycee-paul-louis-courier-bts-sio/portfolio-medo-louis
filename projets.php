<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$projects = require __DIR__ . '/data/projects.php';

renderHeader('Projets | Louis MEDO', 'projects');
?>
<main class="page">
    <section class="hero hero-small">
        <p class="hero-tag">Mes realisations</p>
        <h1>Exemples de projets</h1>
        <p class="hero-intro">
            Voici quelques projets representatifs de mon parcours en BTS SIO,
            option SISR, autour de l'infrastructure, du reseau et de la securite.
        </p>
    </section>

    <section class="projects" aria-label="Liste des projets realises">
        <?php foreach ($projects as $project): ?>
            <article class="project-card">
                <header>
                    <p class="project-context"><?= htmlspecialchars($project['context'], ENT_QUOTES, 'UTF-8') ?></p>
                    <h2><?= htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                </header>
                <p><?= htmlspecialchars($project['description'], ENT_QUOTES, 'UTF-8') ?></p>
                <ul class="skills-list" aria-label="Competences mobilisees">
                    <?php foreach ($project['skills'] as $skill): ?>
                        <li><?= htmlspecialchars($skill, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </article>
        <?php endforeach; ?>
    </section>
</main>
<?php renderFooter(); ?>