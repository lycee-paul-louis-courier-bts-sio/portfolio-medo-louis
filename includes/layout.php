<?php
declare(strict_types=1);

function renderHeader(string $title, string $activePage): void
{
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio de Louis MEDO, etudiant en BTS SIO option SISR.">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="index.php">Louis MEDO</a>
        <nav aria-label="Navigation principale">
            <ul class="nav-list">
                <li>
                    <a href="index.php" class="<?= $activePage === 'presentation' ? 'is-active' : '' ?>">
                        Presentation
                    </a>
                </li>
                <li>
                    <a href="projets.php" class="<?= $activePage === 'projects' ? 'is-active' : '' ?>">
                        Projets
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <?php
}

function renderFooter(): void
{
    ?>
    <footer class="site-footer">
        <p>© <?= date('Y') ?> Louis MEDO - Portfolio BTS SIO SISR</p>
    </footer>
</body>
</html>
    <?php
}