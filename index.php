<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$title = 'Portfolio | Louis MEDO';
renderHeader($title, 'presentation');
?>
<main class="page">
    <section class="hero">
        <p class="hero-tag">BTS SIO - Option SISR</p>
        <h1>Louis MEDO</h1>
        <p class="hero-intro">
            Passionne d'informatique, je me forme aux metiers de l'infrastructure,
            des systemes et des reseaux avec une approche rigoureuse et orientee solutions. Test01
        </p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="projets.php">Voir mes projets</a>
            <a class="btn btn-secondary" href="mailto:louis.medo@example.com">Me contacter</a>
        </div>
    </section>

    <section class="section-grid" aria-label="Informations de presentation">
        <article class="card">
            <h2>Mon profil</h2>
            <p>
                Je suis etudiant en BTS SIO, specialise en SISR.
                J'aime comprendre le fonctionnement des infrastructures,
                automatiser les taches techniques et renforcer la disponibilite des services.
            </p>
        </article>

        <article class="card">
            <h2>Competences en cours</h2>
            <ul>
                <li>Administration Linux et services reseau</li>
                <li>Virtualisation et architecture serveur</li>
                <li>Securisation de postes et de services</li>
                <li>Scripts shell et automatisation</li>
            </ul>
        </article>

        <article class="card">
            <h2>Objectif professionnel</h2>
            <p>
                Mettre mes compétences techniques au service d'equipes IT pour concevoir,
                maintenir et faire evoluer des infrastructures fiables et securisees.
            </p>
        </article>
    </section>
</main>
<?php renderFooter(); ?>