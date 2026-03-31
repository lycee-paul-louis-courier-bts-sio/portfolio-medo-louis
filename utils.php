<?php
/**
 * Portfolio de Test — BTS SIO SLAM
 * utils.php — Tests avancés : regex, JSON, fichiers, sessions, cookies
 */

session_start();

// ─── Gestion des actions de session ───────────────────────────────────────
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reset_session':
            session_destroy();
            header('Location: utils.php');
            exit;
        case 'set_cookie':
            setcookie('test_portfolio', 'valeur_ok_' . time(), time() + 3600, '/');
            header('Location: utils.php#cookies');
            exit;
        case 'del_cookie':
            setcookie('test_portfolio', '', time() - 3600, '/');
            header('Location: utils.php#cookies');
            exit;
    }
}

// ─── Compteur de visite ────────────────────────────────────────────────────
$_SESSION['utils_visits'] = ($_SESSION['utils_visits'] ?? 0) + 1;
$_SESSION['derniere_visite'] = date('H:i:s');

// ─── Tests Regex ───────────────────────────────────────────────────────────
$tests_regex = [
    ['label' => 'E-mail valide',   'pattern' => '/^[\w.+-]+@[\w-]+\.[a-z]{2,}$/i', 'sujet' => 'contact@bts-sio.fr'],
    ['label' => 'E-mail invalide', 'pattern' => '/^[\w.+-]+@[\w-]+\.[a-z]{2,}$/i', 'sujet' => 'pas-un-email'],
    ['label' => 'Code postal FR',  'pattern' => '/^\d{5}$/',                         'sujet' => '75001'],
    ['label' => 'Téléphone FR',    'pattern' => '/^0[1-9](\d{2}){4}$/',             'sujet' => '0612345678'],
    ['label' => 'URL https',       'pattern' => '/^https:\/\/[\w.-]+\.[a-z]{2,}/',  'sujet' => 'https://bts-sio.example.fr'],
    ['label' => 'IPv4',            'pattern' => '/^\d{1,3}(\.\d{1,3}){3}$/',        'sujet' => '192.168.1.1'],
];

// ─── Test JSON ─────────────────────────────────────────────────────────────
$donnees_json = [
    'etudiant' => ['nom' => 'Dupont', 'prenom' => 'Marie', 'classe' => 'BTS SIO SLAM 2'],
    'notes'    => [14, 16, 18, 12],
    'actif'    => true,
    'timestamp'=> time(),
];
$json_encode = json_encode($donnees_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$json_decode = json_decode($json_encode, true);

// ─── Test fichiers ─────────────────────────────────────────────────────────
$tmp_dir  = sys_get_temp_dir();
$tmp_file = $tmp_dir . '/portfolio_test_' . session_id() . '.txt';

$file_write_ok = file_put_contents($tmp_file, "Test écriture — " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
$file_read     = is_file($tmp_file) ? file_get_contents($tmp_file) : 'Fichier non lisible';
$file_size     = is_file($tmp_file) ? filesize($tmp_file) : 0;

// ─── Helpers ───────────────────────────────────────────────────────────────
function badge_ok(bool $ok): string {
    return $ok
        ? '<span style="color:#4ade80;font-family:monospace;font-size:.75rem;">✅ OK</span>'
        : '<span style="color:#f87171;font-family:monospace;font-size:.75rem;">❌ KO</span>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilitaires PHP — Portfolio Test</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:#0a0a0f; --bg2:#11111a; --bg3:#1a1a28;
            --border:#2a2a42; --accent:#7c6af7; --accent2:#4df0c8; --accent3:#f7a26a;
            --text:#e8e8f0; --muted:#7070a0; --radius:8px;
            --mono:'Space Mono',monospace; --sans:'Syne',sans-serif;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{background:var(--bg);color:var(--text);font-family:var(--sans);line-height:1.6;min-height:100vh}
        .container{max-width:1100px;margin:0 auto;padding:0 2rem}

        nav{position:sticky;top:0;z-index:100;background:rgba(10,10,15,.85);backdrop-filter:blur(14px);
            border-bottom:1px solid var(--border);padding:.9rem 0}
        nav .container{display:flex;justify-content:space-between;align-items:center}
        .nav-logo{font-family:var(--mono);font-size:.85rem;color:var(--accent2);text-decoration:none}
        .nav-links{display:flex;gap:1.8rem;list-style:none}
        .nav-links a{font-size:.8rem;color:var(--muted);text-decoration:none;font-family:var(--mono);transition:color .2s}
        .nav-links a:hover,.nav-links a.active{color:var(--accent)}

        .page-header{padding:4rem 0 3rem;border-bottom:1px solid var(--border)}
        .page-header h1{font-size:2.2rem;font-weight:800;margin-bottom:.5rem}
        .page-header p{color:var(--muted);font-family:var(--mono);font-size:.8rem}

        section{padding:3rem 0;border-bottom:1px solid var(--border)}
        .sh{display:flex;align-items:center;gap:1rem;margin-bottom:1.8rem}
        .stag{font-family:var(--mono);font-size:.7rem;color:var(--accent);background:rgba(124,106,247,.12);
              border:1px solid rgba(124,106,247,.3);padding:.25rem .65rem;border-radius:2px;letter-spacing:.1em;text-transform:uppercase}
        .stitle{font-size:1.3rem;font-weight:800}
        .sdiv{flex:1;height:1px;background:linear-gradient(to right,var(--border),transparent)}

        .grid2{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem}
        .grid3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.2rem}
        @media(max-width:768px){.grid2,.grid3{grid-template-columns:1fr}}

        .card{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem}
        .card-head{padding:.7rem 1.2rem;background:var(--bg3);border-bottom:1px solid var(--border);
                   font-family:var(--mono);font-size:.72rem;color:var(--accent3);border-radius:var(--radius) var(--radius) 0 0;margin:-1.5rem -1.5rem 1.2rem}
        pre{font-family:var(--mono);font-size:.72rem;color:var(--accent2);line-height:1.7;white-space:pre-wrap;word-break:break-all}
        .comment{color:var(--muted)}
        .row{display:flex;justify-content:space-between;align-items:center;padding:.4rem 0;
             border-bottom:1px solid rgba(42,42,66,.4);font-family:var(--mono);font-size:.75rem}
        .row:last-child{border-bottom:none}
        .rk{color:var(--muted)}.rv{color:var(--accent2);text-align:right}

        table{width:100%;border-collapse:collapse;font-family:var(--mono);font-size:.75rem}
        thead tr{background:var(--bg3);border-bottom:2px solid var(--border)}
        th,td{padding:.8rem 1rem;text-align:left}
        th{color:var(--muted);text-transform:uppercase;letter-spacing:.07em;font-size:.65rem}
        tbody tr{border-bottom:1px solid var(--border)}
        tbody tr:hover{background:rgba(124,106,247,.05)}

        .btn{display:inline-flex;align-items:center;gap:.4rem;padding:.55rem 1.2rem;border:1px solid var(--border);
             border-radius:var(--radius);background:var(--bg2);color:var(--text);font-family:var(--mono);
             font-size:.72rem;text-decoration:none;transition:all .2s;cursor:pointer}
        .btn:hover{border-color:var(--accent);color:var(--accent)}
        .btn-accent{background:var(--accent);border-color:var(--accent);color:#fff}
        .btn-accent:hover{opacity:.85}
        .btn-danger{border-color:#f87171;color:#f87171}
        .btn-danger:hover{background:rgba(248,113,113,.1)}
        .btn-group{display:flex;gap:.75rem;flex-wrap:wrap;margin-top:1rem}

        .cookie-val{font-family:var(--mono);font-size:.78rem;color:var(--accent2);
                    background:var(--bg3);padding:.5rem .9rem;border-radius:4px;margin-top:.6rem;display:inline-block}

        footer{padding:2rem 0;text-align:center;font-family:var(--mono);font-size:.72rem;color:var(--muted)}
        footer a{color:var(--accent);text-decoration:none}
    </style>
</head>
<body>

<nav>
    <div class="container">
        <a class="nav-logo" href="index.php">&lt; retour</a>
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="utils.php" class="active">Utilitaires</a></li>
            <li><a href="upload.php">Upload</a></li>
            <li><a href="api.php">API JSON</a></li>
        </ul>
    </div>
</nav>

<div class="page-header">
    <div class="container">
        <h1>Utilitaires <span style="color:var(--accent)">PHP</span></h1>
        <p>// Tests avancés : Regex, JSON, Fichiers, Sessions, Cookies</p>
    </div>
</div>

<!-- ── Sessions ─────────────────────────────────────────────────────────── -->
<section id="sessions">
    <div class="container">
        <div class="sh">
            <span class="stag">$_SESSION</span>
            <h2 class="stitle">Sessions PHP</h2>
            <div class="sdiv"></div>
        </div>
        <div class="grid2">
            <div class="card">
                <div class="card-head">// Données de session actuelle</div>
                <div class="row"><span class="rk">session_id()</span><span class="rv"><?= substr(session_id(), 0, 20) ?>...</span></div>
                <div class="row"><span class="rk">session_status()</span><span class="rv"><?= session_status() === PHP_SESSION_ACTIVE ? 'ACTIVE ✅' : 'INACTIVE' ?></span></div>
                <div class="row"><span class="rk">Visites utils.php</span><span class="rv" style="color:var(--accent)"><?= $_SESSION['utils_visits'] ?></span></div>
                <div class="row"><span class="rk">Dernière visite</span><span class="rv"><?= $_SESSION['derniere_visite'] ?></span></div>
                <div class="row"><span class="rk">Visites index.php</span><span class="rv"><?= $_SESSION['visites'] ?? 0 ?></span></div>
                <div class="row"><span class="rk">Contact envoyés</span><span class="rv"><?= $_SESSION['contact_count'] ?? 0 ?></span></div>
                <div class="btn-group">
                    <a href="?action=reset_session" class="btn btn-danger">⟳ Réinitialiser session</a>
                </div>
            </div>
            <div class="card">
                <div class="card-head">// var_dump de $_SESSION</div>
                <pre><?php
                    $safe_session = $_SESSION;
                    echo htmlspecialchars(print_r($safe_session, true));
                ?></pre>
            </div>
        </div>
    </div>
</section>

<!-- ── Cookies ──────────────────────────────────────────────────────────── -->
<section id="cookies">
    <div class="container">
        <div class="sh">
            <span class="stag">$_COOKIE</span>
            <h2 class="stitle">Cookies</h2>
            <div class="sdiv"></div>
        </div>
        <div class="card" style="max-width:600px">
            <div class="card-head">// Test de lecture/écriture cookie</div>
            <?php if (isset($_COOKIE['test_portfolio'])): ?>
                <p style="color:#4ade80;font-family:var(--mono);font-size:.8rem;">✅ Cookie présent :</p>
                <span class="cookie-val"><?= htmlspecialchars($_COOKIE['test_portfolio']) ?></span>
                <div class="btn-group">
                    <a href="?action=del_cookie" class="btn btn-danger">✕ Supprimer le cookie</a>
                </div>
            <?php else: ?>
                <p style="color:var(--muted);font-family:var(--mono);font-size:.8rem;">Aucun cookie de test trouvé.</p>
                <div class="btn-group">
                    <a href="?action=set_cookie" class="btn btn-accent">+ Créer un cookie</a>
                </div>
            <?php endif; ?>
            <div style="margin-top:1.5rem;padding-top:1.2rem;border-top:1px solid var(--border)">
                <span class="comment" style="font-family:var(--mono);font-size:.72rem;">// Tous les cookies disponibles</span>
                <pre style="margin-top:.6rem"><?= htmlspecialchars(print_r($_COOKIE, true)) ?></pre>
            </div>
        </div>
    </div>
</section>

<!-- ── Regex ────────────────────────────────────────────────────────────── -->
<section id="regex">
    <div class="container">
        <div class="sh">
            <span class="stag">preg_match</span>
            <h2 class="stitle">Expressions régulières</h2>
            <div class="sdiv"></div>
        </div>
        <table>
            <thead>
                <tr><th>Label</th><th>Sujet testé</th><th>Pattern</th><th>Résultat</th></tr>
            </thead>
            <tbody>
                <?php foreach ($tests_regex as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['label']) ?></td>
                        <td style="color:var(--accent3)"><?= htmlspecialchars($t['sujet']) ?></td>
                        <td style="color:var(--muted);font-size:.68rem"><?= htmlspecialchars($t['pattern']) ?></td>
                        <td><?= badge_ok((bool) preg_match($t['pattern'], $t['sujet'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- ── JSON ─────────────────────────────────────────────────────────────── -->
<section id="json">
    <div class="container">
        <div class="sh">
            <span class="stag">json</span>
            <h2 class="stitle">Encodage / Décodage JSON</h2>
            <div class="sdiv"></div>
        </div>
        <div class="grid2">
            <div class="card">
                <div class="card-head">// json_encode() — PHP → JSON</div>
                <pre><?= htmlspecialchars($json_encode) ?></pre>
                <div style="margin-top:1rem;font-family:var(--mono);font-size:.72rem;color:var(--muted)">
                    Longueur : <?= strlen($json_encode) ?> octets
                </div>
            </div>
            <div class="card">
                <div class="card-head">// json_decode() — JSON → PHP</div>
                <pre><?= htmlspecialchars(print_r($json_decode, true)) ?></pre>
                <div style="margin-top:.6rem">
                    <span class="rk" style="font-family:var(--mono);font-size:.72rem;color:var(--muted)">Prénom récupéré : </span>
                    <span style="color:var(--accent2);font-family:var(--mono);font-size:.78rem">
                        <?= htmlspecialchars($json_decode['etudiant']['prenom'] ?? 'N/A') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Fichiers ──────────────────────────────────────────────────────────── -->
<section id="fichiers">
    <div class="container">
        <div class="sh">
            <span class="stag">file_*</span>
            <h2 class="stitle">Lecture / Écriture de fichiers</h2>
            <div class="sdiv"></div>
        </div>
        <div class="grid2">
            <div class="card">
                <div class="card-head">// Informations fichier temporaire</div>
                <div class="row"><span class="rk">Chemin</span><span class="rv" style="font-size:.65rem"><?= htmlspecialchars($tmp_file) ?></span></div>
                <div class="row"><span class="rk">Écriture (file_put_contents)</span><span class="rv"><?= badge_ok($file_write_ok !== false) ?></span></div>
                <div class="row"><span class="rk">file_exists()</span><span class="rv"><?= badge_ok(file_exists($tmp_file)) ?></span></div>
                <div class="row"><span class="rk">is_readable()</span><span class="rv"><?= badge_ok(is_readable($tmp_file)) ?></span></div>
                <div class="row"><span class="rk">is_writable()</span><span class="rv"><?= badge_ok(is_writable($tmp_file)) ?></span></div>
                <div class="row"><span class="rk">Taille</span><span class="rv"><?= $file_size ?> octets</span></div>
                <div class="row"><span class="rk">sys_get_temp_dir()</span><span class="rv"><?= htmlspecialchars($tmp_dir) ?></span></div>
            </div>
            <div class="card">
                <div class="card-head">// Contenu lu (file_get_contents)</div>
                <pre><?= htmlspecialchars($file_read) ?></pre>
                <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border)">
                    <span class="comment" style="font-family:var(--mono);font-size:.72rem">// Infos répertoire temporaire</span>
                    <div class="row" style="margin-top:.5rem"><span class="rk">disk_free_space()</span>
                        <span class="rv"><?= round(disk_free_space($tmp_dir) / 1e9, 2) ?> Go</span></div>
                    <div class="row"><span class="rk">disk_total_space()</span>
                        <span class="rv"><?= round(disk_total_space($tmp_dir) / 1e9, 2) ?> Go</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Fonctions utilitaires PHP ─────────────────────────────────────────── -->
<section>
    <div class="container">
        <div class="sh">
            <span class="stag">functions</span>
            <h2 class="stitle">Fonctions diverses PHP</h2>
            <div class="sdiv"></div>
        </div>
        <div class="grid3">
            <div class="card">
                <div class="card-head">// Chaînes</div>
                <pre><?php
                    $s = "  Hello BTS SIO  ";
                    echo "<span class='comment'>// trim()</span>\n";
                    echo htmlspecialchars(trim($s)) . "\n\n";
                    echo "<span class='comment'>// str_replace()</span>\n";
                    echo htmlspecialchars(str_replace('BTS SIO', 'SIO-SLAM', trim($s))) . "\n\n";
                    echo "<span class='comment'>// ucwords()</span>\n";
                    echo htmlspecialchars(ucwords(strtolower(trim($s)))) . "\n\n";
                    echo "<span class='comment'>// sprintf()</span>\n";
                    echo htmlspecialchars(sprintf('Note: %.1f / 20', 15.666));
                ?></pre>
            </div>
            <div class="card">
                <div class="card-head">// Tableaux avancés</div>
                <pre><?php
                    $a = range(1, 8);
                    echo "<span class='comment'>// range(1,8)</span>\n";
                    echo '[' . implode(',', $a) . "]\n\n";

                    $b = array_map(fn($x) => $x ** 2, $a);
                    echo "<span class='comment'>// array_map (carré)</span>\n";
                    echo '[' . implode(',', $b) . "]\n\n";

                    $c = array_filter($b, fn($x) => $x > 10);
                    echo "<span class='comment'>// array_filter (>10)</span>\n";
                    echo '[' . implode(',', $c) . "]\n\n";

                    echo "<span class='comment'>// array_reduce (somme)</span>\n";
                    echo 'Σ = ' . array_reduce($c, fn($carry, $x) => $carry + $x, 0);
                ?></pre>
            </div>
            <div class="card">
                <div class="card-head">// Date & heure</div>
                <pre><?php
                    echo "<span class='comment'>// Formats date()</span>\n";
                    echo date('d/m/Y') . "\n";
                    echo date('H:i:s') . "\n";
                    echo date('D, d M Y') . "\n\n";
                    echo "<span class='comment'>// mktime & diff</span>\n";
                    $debut = mktime(0,0,0,9,1,2024);
                    $maintenant = time();
                    $jours = (int)(($maintenant - $debut) / 86400);
                    echo "Jours depuis 01/09/24 :\n";
                    echo $jours . " jours\n\n";
                    echo "<span class='comment'>// date_create</span>\n";
                    $d = date_create('2025-06-30');
                    echo "Fin BTS : " . htmlspecialchars(date_format($d, 'd/m/Y'));
                ?></pre>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <p>&larr; <a href="index.php">Retour au portfolio</a> &middot;
           <a href="upload.php">Test Upload</a> &middot;
           <a href="api.php">API JSON</a></p>
    </div>
</footer>

</body>
</html>
