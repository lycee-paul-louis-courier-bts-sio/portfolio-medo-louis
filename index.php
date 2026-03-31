<?php
/**
 * Portfolio de Test - BTS SIO SLAM
 * Fichier principal - index.php
 * Tests : Variables, tableaux, boucles, fonctions, sessions, formulaires
 */

session_start();

// ─── Données de test ───────────────────────────────────────────────────────
$etudiant = [
    'nom'       => 'Dupont',
    'prenom'    => 'Marie',
    'classe'    => 'BTS SIO SLAM 2',
    'annee'     => date('Y'),
];

$projets = [
    ['titre' => 'Application de Gestion',  'techno' => 'PHP / MySQL',    'note' => 16],
    ['titre' => 'Site Vitrine Responsive', 'techno' => 'HTML / CSS / JS', 'note' => 18],
    ['titre' => 'API REST',                'techno' => 'PHP / JSON',      'note' => 15],
    ['titre' => 'Script Automatisation',   'techno' => 'Python',          'note' => 17],
];

$competences = ['PHP', 'MySQL', 'HTML5', 'CSS3', 'JavaScript', 'Git', 'Linux', 'Python'];

// ─── Traitement du formulaire de contact ──────────────────────────────────
$message_retour = '';
$type_message   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'contact') {
    $nom_contact     = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $email_contact   = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message_contact = htmlspecialchars(trim($_POST['message'] ?? ''));

    if (empty($nom_contact) || empty($email_contact) || empty($message_contact)) {
        $message_retour = '⚠️ Tous les champs sont obligatoires.';
        $type_message   = 'error';
    } elseif (!filter_var($email_contact, FILTER_VALIDATE_EMAIL)) {
        $message_retour = '⚠️ L\'adresse e-mail est invalide.';
        $type_message   = 'error';
    } else {
        $_SESSION['contact_count'] = ($_SESSION['contact_count'] ?? 0) + 1;
        $message_retour = "✅ Message reçu ! (Simulation — aucun e-mail envoyé) [Envoi #{$_SESSION['contact_count']}]";
        $type_message   = 'success';
    }
}

// ─── Fonctions utilitaires ────────────────────────────────────────────────

/**
 * Calcule la moyenne d'un tableau de projets.
 */
function calculerMoyenne(array $projets): float {
    if (empty($projets)) return 0.0;
    $total = array_sum(array_column($projets, 'note'));
    return round($total / count($projets), 2);
}

/**
 * Retourne la couleur CSS selon la note.
 */
function couleurNote(int $note): string {
    return match(true) {
        $note >= 18 => '#4ade80',
        $note >= 15 => '#facc15',
        $note >= 10 => '#fb923c',
        default     => '#f87171',
    };
}

/**
 * Retourne les infos PHP pertinentes pour les tests serveur.
 */
function getInfosServeur(): array {
    return [
        'Version PHP'       => PHP_VERSION,
        'Système'           => PHP_OS,
        'Serveur Web'       => $_SERVER['SERVER_SOFTWARE'] ?? 'Inconnu',
        'Hôte'              => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'Sessions actives'  => session_status() === PHP_SESSION_ACTIVE ? 'Oui ✅' : 'Non ❌',
        'Extensions JSON'   => extension_loaded('json')  ? 'Chargée ✅' : 'Manquante ❌',
        'Extension PDO'     => extension_loaded('pdo')   ? 'Chargée ✅' : 'Manquante ❌',
        'Extension mbstring'=> extension_loaded('mbstring') ? 'Chargée ✅' : 'Manquante ❌',
        'GD (images)'       => extension_loaded('gd')    ? 'Chargée ✅' : 'Manquante ❌',
        'Date/Heure serveur'=> date('d/m/Y H:i:s'),
        'Mémoire limite'    => ini_get('memory_limit'),
        'Upload max'        => ini_get('upload_max_filesize'),
    ];
}

$moyenne     = calculerMoyenne($projets);
$infosServeur = getInfosServeur();

// ─── Incrément de visite via session ──────────────────────────────────────
$_SESSION['visites'] = ($_SESSION['visites'] ?? 0) + 1;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Test — BTS SIO SLAM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        /* ── Variables ─────────────────────────────────────────── */
        :root {
            --bg:         #0a0a0f;
            --bg2:        #11111a;
            --bg3:        #1a1a28;
            --border:     #2a2a42;
            --accent:     #7c6af7;
            --accent2:    #4df0c8;
            --accent3:    #f7a26a;
            --text:       #e8e8f0;
            --muted:      #7070a0;
            --success:    #4ade80;
            --error:      #f87171;
            --radius:     8px;
            --mono:       'Space Mono', monospace;
            --sans:       'Syne', sans-serif;
        }

        /* ── Reset & base ──────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--sans);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Bruit de fond ─────────────────────────────────────── */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0; opacity: .5;
        }

        /* ── Layout ────────────────────────────────────────────── */
        .container { max-width: 1100px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 1; }

        /* ── Navigation ────────────────────────────────────────── */
        nav {
            position: sticky; top: 0; z-index: 100;
            background: rgba(10, 10, 15, .85);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            padding: .9rem 0;
        }
        nav .container { display: flex; justify-content: space-between; align-items: center; }
        .nav-logo {
            font-family: var(--mono);
            font-size: .85rem;
            color: var(--accent2);
            text-decoration: none;
            letter-spacing: .05em;
        }
        .nav-links { display: flex; gap: 1.8rem; list-style: none; }
        .nav-links a {
            font-size: .8rem;
            color: var(--muted);
            text-decoration: none;
            font-family: var(--mono);
            letter-spacing: .05em;
            transition: color .2s;
        }
        .nav-links a:hover { color: var(--accent); }

        /* ── Section header ────────────────────────────────────── */
        .section-header {
            display: flex; align-items: center; gap: 1rem;
            margin-bottom: 2rem;
        }
        .section-tag {
            font-family: var(--mono);
            font-size: .7rem;
            color: var(--accent);
            background: rgba(124, 106, 247, .12);
            border: 1px solid rgba(124, 106, 247, .3);
            padding: .25rem .65rem;
            border-radius: 2px;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
        }
        .section-divider {
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, var(--border), transparent);
        }

        /* ── Hero ──────────────────────────────────────────────── */
        .hero {
            padding: 7rem 0 5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        .hero-eyebrow {
            font-family: var(--mono);
            font-size: .75rem;
            color: var(--accent2);
            letter-spacing: .12em;
            text-transform: uppercase;
            margin-bottom: 1rem;
            display: flex; align-items: center; gap: .5rem;
        }
        .hero-eyebrow::before {
            content: '';
            display: inline-block;
            width: 2rem; height: 1px;
            background: var(--accent2);
        }
        .hero h1 {
            font-size: clamp(2.4rem, 4vw, 3.5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.2rem;
        }
        .hero h1 span { color: var(--accent); }
        .hero-sub {
            color: var(--muted);
            font-size: 1rem;
            margin-bottom: 2rem;
            font-family: var(--mono);
        }
        .hero-badges { display: flex; flex-wrap: wrap; gap: .5rem; }
        .badge {
            font-family: var(--mono);
            font-size: .7rem;
            padding: .3rem .75rem;
            border-radius: 2px;
            border: 1px solid var(--border);
            color: var(--muted);
            background: var(--bg2);
        }

        /* Carte serveur côté hero */
        .server-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .server-card-header {
            padding: .75rem 1.2rem;
            background: var(--bg3);
            border-bottom: 1px solid var(--border);
            font-family: var(--mono);
            font-size: .72rem;
            color: var(--accent2);
            display: flex; align-items: center; gap: .5rem;
        }
        .dot { width: 8px; height: 8px; border-radius: 50%; }
        .dot-r { background: #f87171; }
        .dot-y { background: #facc15; }
        .dot-g { background: #4ade80; }
        .server-card-body { padding: 1.2rem; }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .45rem 0;
            border-bottom: 1px solid rgba(42,42,66,.5);
            font-family: var(--mono);
            font-size: .72rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-key { color: var(--muted); }
        .info-val { color: var(--accent2); text-align: right; max-width: 55%; word-break: break-all; }

        /* ── Sections générales ─────────────────────────────────── */
        section { padding: 5rem 0; border-top: 1px solid var(--border); }

        /* ── Grille de cartes ──────────────────────────────────── */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.2rem;
        }
        .card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            transition: border-color .2s, transform .2s;
        }
        .card:hover {
            border-color: var(--accent);
            transform: translateY(-3px);
        }
        .card-titre {
            font-weight: 700;
            margin-bottom: .4rem;
            font-size: 1rem;
        }
        .card-techno {
            font-family: var(--mono);
            font-size: .7rem;
            color: var(--muted);
            margin-bottom: 1rem;
        }
        .note-bar-bg {
            height: 4px;
            background: var(--bg3);
            border-radius: 2px;
            overflow: hidden;
        }
        .note-bar {
            height: 100%;
            border-radius: 2px;
            transition: width .8s ease;
        }
        .note-label {
            display: flex;
            justify-content: space-between;
            font-family: var(--mono);
            font-size: .68rem;
            margin-top: .4rem;
            color: var(--muted);
        }

        /* ── Compétences ───────────────────────────────────────── */
        .competences-grid {
            display: flex; flex-wrap: wrap; gap: .75rem;
        }
        .comp-tag {
            font-family: var(--mono);
            font-size: .8rem;
            padding: .5rem 1.1rem;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: var(--bg2);
            color: var(--text);
            transition: all .2s;
            cursor: default;
        }
        .comp-tag:hover {
            border-color: var(--accent2);
            color: var(--accent2);
            background: rgba(77, 240, 200, .06);
        }

        /* ── Statistiques ──────────────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.2rem;
        }
        .stat-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.8rem 1.5rem;
            text-align: center;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--accent);
            line-height: 1;
            margin-bottom: .5rem;
        }
        .stat-label {
            font-family: var(--mono);
            font-size: .7rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        /* ── Tableau PHP ───────────────────────────────────────── */
        .table-wrap { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: var(--mono);
            font-size: .78rem;
        }
        thead tr {
            background: var(--bg3);
            border-bottom: 2px solid var(--border);
        }
        th, td { padding: .85rem 1.2rem; text-align: left; }
        th { color: var(--muted); text-transform: uppercase; letter-spacing: .08em; font-size: .65rem; }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(124,106,247,.06); }
        td { color: var(--text); }
        .td-accent { color: var(--accent2); }

        /* ── Tests PHP ─────────────────────────────────────────── */
        .tests-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .test-block {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .test-block-header {
            padding: .8rem 1.2rem;
            background: var(--bg3);
            border-bottom: 1px solid var(--border);
            font-family: var(--mono);
            font-size: .72rem;
            color: var(--accent3);
            letter-spacing: .05em;
        }
        .test-block-body { padding: 1.2rem; }
        .code-output {
            font-family: var(--mono);
            font-size: .78rem;
            color: var(--accent2);
            line-height: 1.8;
        }
        .code-comment { color: var(--muted); }

        /* ── Formulaire ────────────────────────────────────────── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; }
        .form-group { display: flex; flex-direction: column; gap: .4rem; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-family: var(--mono); font-size: .72rem; color: var(--muted); letter-spacing: .05em; }
        input, textarea {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: .75rem 1rem;
            color: var(--text);
            font-family: var(--sans);
            font-size: .9rem;
            transition: border-color .2s;
            resize: vertical;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
        }
        .btn {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .75rem 2rem;
            border: none;
            border-radius: var(--radius);
            background: var(--accent);
            color: #fff;
            font-family: var(--mono);
            font-size: .8rem;
            letter-spacing: .05em;
            cursor: pointer;
            transition: opacity .2s, transform .15s;
        }
        .btn:hover { opacity: .85; transform: translateY(-1px); }
        .alert {
            padding: .9rem 1.2rem;
            border-radius: var(--radius);
            font-family: var(--mono);
            font-size: .8rem;
            margin-bottom: 1.5rem;
            border: 1px solid;
        }
        .alert-success { background: rgba(74,222,128,.1); border-color: rgba(74,222,128,.3); color: var(--success); }
        .alert-error   { background: rgba(248,113,113,.1); border-color: rgba(248,113,113,.3); color: var(--error); }

        /* ── Footer ────────────────────────────────────────────── */
        footer {
            border-top: 1px solid var(--border);
            padding: 2.5rem 0;
            text-align: center;
            font-family: var(--mono);
            font-size: .72rem;
            color: var(--muted);
        }
        footer span { color: var(--accent); }

        /* ── Responsive ────────────────────────────────────────── */
        @media (max-width: 768px) {
            .hero { grid-template-columns: 1fr; gap: 2.5rem; padding: 4rem 0 3rem; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .tests-grid { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

<!-- ── Navigation ──────────────────────────────────────────────────────── -->
<nav>
    <div class="container">
        <a class="nav-logo" href="#">&gt;_ portfolio-test.php</a>
        <ul class="nav-links">
            <li><a href="#projets">Projets</a></li>
            <li><a href="#tests">Tests PHP</a></li>
            <li><a href="#serveur">Serveur</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="utils.php">Utilitaires</a></li>
            <li><a href="upload.php">Upload</a></li>
        </ul>
    </div>
</nav>

<!-- ── Hero ────────────────────────────────────────────────────────────── -->
<section style="border-top:none; padding-bottom:0;">
    <div class="container">
        <div class="hero">
            <div>
                <p class="hero-eyebrow"><?= htmlspecialchars($etudiant['classe']) ?></p>
                <h1><?= htmlspecialchars($etudiant['prenom']) ?><br><span><?= htmlspecialchars($etudiant['nom']) ?></span></h1>
                <p class="hero-sub">
                    // Portfolio de test — Année <?= $etudiant['annee'] ?><br>
                    // Apache + PHP — Vérification serveur
                </p>
                <div class="hero-badges">
                    <?php foreach ($competences as $c): ?>
                        <span class="badge"><?= htmlspecialchars($c) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Carte serveur -->
            <div class="server-card">
                <div class="server-card-header">
                    <span class="dot dot-r"></span>
                    <span class="dot dot-y"></span>
                    <span class="dot dot-g"></span>
                    &nbsp;infos_serveur.php
                </div>
                <div class="server-card-body">
                    <?php foreach ($infosServeur as $cle => $valeur): ?>
                        <div class="info-row">
                            <span class="info-key"><?= htmlspecialchars($cle) ?></span>
                            <span class="info-val"><?= htmlspecialchars($valeur) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Statistiques ─────────────────────────────────────────────────────── -->
<section>
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= count($projets) ?></div>
                <div class="stat-label">Projets réalisés</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count($competences) ?></div>
                <div class="stat-label">Compétences</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $moyenne ?></div>
                <div class="stat-label">Moyenne / 20</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $_SESSION['visites'] ?></div>
                <div class="stat-label">Visites (session)</div>
            </div>
        </div>
    </div>
</section>

<!-- ── Projets ──────────────────────────────────────────────────────────── -->
<section id="projets">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">foreach</span>
            <h2 class="section-title">Projets réalisés</h2>
            <div class="section-divider"></div>
        </div>

        <div class="cards-grid">
            <?php foreach ($projets as $index => $projet): ?>
                <div class="card">
                    <div class="card-titre"><?= htmlspecialchars($projet['titre']) ?></div>
                    <div class="card-techno"><?= htmlspecialchars($projet['techno']) ?></div>
                    <div class="note-bar-bg">
                        <div class="note-bar"
                             style="width:<?= ($projet['note'] / 20) * 100 ?>%; background:<?= couleurNote($projet['note']) ?>;">
                        </div>
                    </div>
                    <div class="note-label">
                        <span>Note</span>
                        <span style="color:<?= couleurNote($projet['note']) ?>;"><?= $projet['note'] ?>/20</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── Compétences ──────────────────────────────────────────────────────── -->
<section>
    <div class="container">
        <div class="section-header">
            <span class="section-tag">array</span>
            <h2 class="section-title">Compétences</h2>
            <div class="section-divider"></div>
        </div>
        <div class="competences-grid">
            <?php foreach ($competences as $comp): ?>
                <span class="comp-tag"><?= htmlspecialchars($comp) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── Tests PHP ────────────────────────────────────────────────────────── -->
<section id="tests">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">php</span>
            <h2 class="section-title">Tests PHP en direct</h2>
            <div class="section-divider"></div>
        </div>

        <div class="tests-grid">

            <!-- Test boucles -->
            <div class="test-block">
                <div class="test-block-header">// Test boucles (for, while)</div>
                <div class="test-block-body">
                    <div class="code-output">
                        <span class="code-comment">// Boucle for — table de 3</span><br>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            3 × <?= $i ?> = <?= 3 * $i ?><br>
                        <?php endfor; ?>
                        <br>
                        <span class="code-comment">// Boucle while — puissances de 2</span><br>
                        <?php
                        $n = 1; $count = 0;
                        while ($count < 6): ?>
                            2^<?= $count ?> = <?= $n ?><br>
                        <?php $n *= 2; $count++; endwhile; ?>
                    </div>
                </div>
            </div>

            <!-- Test chaînes -->
            <div class="test-block">
                <div class="test-block-header">// Test fonctions chaînes</div>
                <div class="test-block-body">
                    <div class="code-output">
                        <?php
                        $texte = "BTS SIO SLAM Option développement";
                        ?>
                        <span class="code-comment">// strlen()</span><br>
                        Longueur : <?= strlen($texte) ?> caractères<br><br>
                        <span class="code-comment">// strtoupper()</span><br>
                        <?= htmlspecialchars(strtoupper($texte)) ?><br><br>
                        <span class="code-comment">// str_word_count()</span><br>
                        Mots : <?= str_word_count($texte) ?><br><br>
                        <span class="code-comment">// substr()</span><br>
                        Extrait : <?= htmlspecialchars(substr($texte, 0, 7)) ?>
                    </div>
                </div>
            </div>

            <!-- Test tableaux -->
            <div class="test-block">
                <div class="test-block-header">// Test fonctions tableaux</div>
                <div class="test-block-body">
                    <div class="code-output">
                        <?php
                        $notes = [12, 18, 14, 9, 16, 11];
                        sort($notes);
                        ?>
                        <span class="code-comment">// Notes triées (sort)</span><br>
                        [<?= implode(', ', $notes) ?>]<br><br>
                        <span class="code-comment">// max / min / array_sum</span><br>
                        Max : <?= max($notes) ?> | Min : <?= min($notes) ?><br>
                        Somme : <?= array_sum($notes) ?><br><br>
                        <span class="code-comment">// array_filter (≥ 12)</span><br>
                        [<?= implode(', ', array_filter($notes, fn($n) => $n >= 12)) ?>]
                    </div>
                </div>
            </div>

            <!-- Test date / math -->
            <div class="test-block">
                <div class="test-block-header">// Test date() & math</div>
                <div class="test-block-body">
                    <div class="code-output">
                        <span class="code-comment">// Fonctions date</span><br>
                        Jour : <?= date('l') ?><br>
                        Date : <?= date('d/m/Y') ?><br>
                        Timestamp : <?= time() ?><br><br>
                        <span class="code-comment">// Fonctions math</span><br>
                        √144 = <?= sqrt(144) ?><br>
                        π ≈ <?= round(M_PI, 6) ?><br>
                        rand(1,100) = <?= rand(1, 100) ?><br>
                        abs(-42) = <?= abs(-42) ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── Tableau récapitulatif ────────────────────────────────────────────── -->
<section id="serveur">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">table</span>
            <h2 class="section-title">Récapitulatif projets</h2>
            <div class="section-divider"></div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Technologie</th>
                        <th>Note</th>
                        <th>Mention</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projets as $i => $p): ?>
                        <tr>
                            <td class="td-accent"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></td>
                            <td><?= htmlspecialchars($p['titre']) ?></td>
                            <td><?= htmlspecialchars($p['techno']) ?></td>
                            <td style="color:<?= couleurNote($p['note']) ?>; font-weight:700;"><?= $p['note'] ?>/20</td>
                            <td>
                                <?php
                                echo match(true) {
                                    $p['note'] >= 18 => 'Très bien',
                                    $p['note'] >= 15 => 'Bien',
                                    $p['note'] >= 12 => 'Assez bien',
                                    $p['note'] >= 10 => 'Passable',
                                    default          => 'Insuffisant',
                                };
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p style="margin-top:1.2rem; font-family:var(--mono); font-size:.75rem; color:var(--muted);">
            → Moyenne calculée par PHP : <span style="color:var(--accent2);"><?= $moyenne ?>/20</span>
            &nbsp;|&nbsp; Visites session : <span style="color:var(--accent2);"><?= $_SESSION['visites'] ?></span>
        </p>
    </div>
</section>

<!-- ── Formulaire de contact ────────────────────────────────────────────── -->
<section id="contact">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">$_POST</span>
            <h2 class="section-title">Formulaire de contact</h2>
            <div class="section-divider"></div>
        </div>

        <?php if ($message_retour): ?>
            <div class="alert alert-<?= $type_message ?>"><?= $message_retour ?></div>
        <?php endif; ?>

        <form method="POST" action="#contact">
            <input type="hidden" name="action" value="contact">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nom">NOM_COMPLET</label>
                    <input type="text" id="nom" name="nom"
                           placeholder="Votre nom"
                           value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="email">EMAIL</label>
                    <input type="email" id="email" name="email"
                           placeholder="votre@email.fr"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group full">
                    <label for="message">MESSAGE</label>
                    <textarea id="message" name="message" rows="5"
                              placeholder="Votre message..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">
                        &#9654; Envoyer le message
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- ── Footer ───────────────────────────────────────────────────────────── -->
<footer>
    <div class="container">
        <p>
            Portfolio de test &mdash; <span><?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></span>
            &mdash; <?= htmlspecialchars($etudiant['classe']) ?> &mdash; <?= $etudiant['annee'] ?>
        </p>
        <p style="margin-top:.5rem;">
            PHP <?= PHP_VERSION ?> &middot; Apache &middot;
            <a href="utils.php" style="color:var(--accent); text-decoration:none;">Utilitaires</a> &middot;
            <a href="upload.php" style="color:var(--accent); text-decoration:none;">Upload</a> &middot;
            <a href="api.php" style="color:var(--accent); text-decoration:none;">API JSON</a>
        </p>
    </div>
</footer>

</body>
</html>
