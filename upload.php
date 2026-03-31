<?php
/**
 * Portfolio de Test — BTS SIO SLAM
 * upload.php — Test d'upload de fichiers
 */

session_start();

$upload_dir    = sys_get_temp_dir() . '/portfolio_uploads/';
$result        = null;
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'text/plain', 'application/pdf'];
$max_size      = 2 * 1024 * 1024; // 2 Mo

// Créer le dossier d'upload s'il n'existe pas
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// ─── Traitement de l'upload ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier'])) {
    $file = $_FILES['fichier'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        // Vérifications
        if ($file['size'] > $max_size) {
            $result = ['type' => 'error', 'msg' => '⚠️ Fichier trop volumineux (max 2 Mo).'];
        } elseif (!in_array($file['type'], $allowed_types, true)) {
            $result = ['type' => 'error', 'msg' => '⚠️ Type de fichier non autorisé : ' . htmlspecialchars($file['type'])];
        } elseif (!is_uploaded_file($file['tmp_name'])) {
            $result = ['type' => 'error', 'msg' => '⚠️ Sécurité : le fichier n\'a pas été uploadé via HTTP POST.'];
        } else {
            // Sécurisation du nom
            $ext         = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $safe_name   = 'upload_' . uniqid() . '.' . $ext;
            $destination = $upload_dir . $safe_name;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $result = [
                    'type'    => 'success',
                    'msg'     => '✅ Fichier uploadé avec succès (simulation serveur) !',
                    'details' => [
                        'Nom original'  => htmlspecialchars($file['name']),
                        'Nom sécurisé'  => $safe_name,
                        'Type MIME'     => $file['type'],
                        'Taille'        => number_format($file['size'] / 1024, 2) . ' Ko',
                        'Destination'   => $destination,
                        'is_file()'     => is_file($destination) ? 'Oui ✅' : 'Non ❌',
                    ],
                ];
                // Nettoyage immédiat pour la démo
                @unlink($destination);
            } else {
                $result = ['type' => 'error', 'msg' => '❌ Erreur lors du déplacement du fichier.'];
            }
        }
    } else {
        $codes = [
            UPLOAD_ERR_INI_SIZE   => 'Fichier dépasse upload_max_filesize (php.ini)',
            UPLOAD_ERR_FORM_SIZE  => 'Fichier dépasse MAX_FILE_SIZE du formulaire',
            UPLOAD_ERR_PARTIAL    => 'Upload partiel',
            UPLOAD_ERR_NO_FILE    => 'Aucun fichier envoyé',
            UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant',
            UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire sur le disque',
            UPLOAD_ERR_EXTENSION  => 'Extension PHP a stoppé l\'upload',
        ];
        $result = ['type' => 'error', 'msg' => '⚠️ Erreur upload : ' . ($codes[$file['error']] ?? 'Code ' . $file['error'])];
    }
}

// ─── Infos configuration upload PHP ────────────────────────────────────────
$upload_config = [
    'file_uploads'       => ini_get('file_uploads') ? 'Activé ✅' : 'Désactivé ❌',
    'upload_max_filesize'=> ini_get('upload_max_filesize'),
    'post_max_size'      => ini_get('post_max_size'),
    'max_file_uploads'   => ini_get('max_file_uploads'),
    'tmp_dir (php.ini)'  => ini_get('upload_tmp_dir') ?: sys_get_temp_dir(),
    'Dossier tmp lisible'=> is_readable(sys_get_temp_dir()) ? 'Oui ✅' : 'Non ❌',
    'Dossier tmp écrit.' => is_writable(sys_get_temp_dir()) ? 'Oui ✅' : 'Non ❌',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Upload — Portfolio Test</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#0a0a0f;--bg2:#11111a;--bg3:#1a1a28;--border:#2a2a42;--accent:#7c6af7;
              --accent2:#4df0c8;--accent3:#f7a26a;--text:#e8e8f0;--muted:#7070a0;
              --success:#4ade80;--error:#f87171;--radius:8px;
              --mono:'Space Mono',monospace;--sans:'Syne',sans-serif}
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
        @media(max-width:768px){.grid2{grid-template-columns:1fr}}

        .card{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:1.5rem}
        .card-head{padding:.7rem 1.2rem;background:var(--bg3);border-bottom:1px solid var(--border);
                   font-family:var(--mono);font-size:.72rem;color:var(--accent3);
                   border-radius:var(--radius) var(--radius) 0 0;margin:-1.5rem -1.5rem 1.2rem}
        .row{display:flex;justify-content:space-between;align-items:center;padding:.4rem 0;
             border-bottom:1px solid rgba(42,42,66,.4);font-family:var(--mono);font-size:.75rem}
        .row:last-child{border-bottom:none}
        .rk{color:var(--muted)}.rv{color:var(--accent2);text-align:right;max-width:60%;word-break:break-all}

        /* Upload zone */
        .upload-zone{border:2px dashed var(--border);border-radius:var(--radius);padding:3rem 2rem;
                     text-align:center;transition:all .2s;cursor:pointer;background:var(--bg2)}
        .upload-zone:hover,.upload-zone.drag-over{border-color:var(--accent);background:rgba(124,106,247,.05)}
        .upload-icon{font-size:3rem;margin-bottom:1rem;display:block}
        .upload-label{font-family:var(--mono);font-size:.85rem;color:var(--muted);margin-bottom:.5rem}
        .upload-hint{font-family:var(--mono);font-size:.7rem;color:var(--muted);opacity:.6}
        input[type="file"]{display:none}

        label.field-label{display:block;font-family:var(--mono);font-size:.72rem;color:var(--muted);
                           letter-spacing:.05em;margin-bottom:.4rem;margin-top:1rem}
        .btn{display:inline-flex;align-items:center;gap:.4rem;padding:.7rem 1.8rem;border:none;
             border-radius:var(--radius);background:var(--accent);color:#fff;font-family:var(--mono);
             font-size:.8rem;cursor:pointer;transition:all .2s;margin-top:1rem}
        .btn:hover{opacity:.85;transform:translateY(-1px)}

        .alert{padding:.9rem 1.2rem;border-radius:var(--radius);font-family:var(--mono);font-size:.8rem;
               border:1px solid;margin-bottom:1.5rem}
        .alert-success{background:rgba(74,222,128,.1);border-color:rgba(74,222,128,.3);color:var(--success)}
        .alert-error{background:rgba(248,113,113,.1);border-color:rgba(248,113,113,.3);color:var(--error)}

        .detail-table{margin-top:1rem;width:100%}
        .detail-table td{font-family:var(--mono);font-size:.75rem;padding:.4rem .8rem;
                         border-bottom:1px solid rgba(42,42,66,.4)}
        .detail-table td:first-child{color:var(--muted)}
        .detail-table td:last-child{color:var(--accent2)}

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
            <li><a href="utils.php">Utilitaires</a></li>
            <li><a href="upload.php" class="active">Upload</a></li>
            <li><a href="api.php">API JSON</a></li>
        </ul>
    </div>
</nav>

<div class="page-header">
    <div class="container">
        <h1>Test <span style="color:var(--accent)">Upload</span></h1>
        <p>// Vérification de la gestion des fichiers uploadés ($_FILES)</p>
    </div>
</div>

<!-- ── Config PHP ──────────────────────────────────────────────────────────── -->
<section>
    <div class="container">
        <div class="sh">
            <span class="stag">php.ini</span>
            <h2 class="stitle">Configuration serveur (upload)</h2>
            <div class="sdiv"></div>
        </div>
        <div class="card" style="max-width:600px">
            <div class="card-head">// Directives upload — php.ini</div>
            <?php foreach ($upload_config as $k => $v): ?>
                <div class="row">
                    <span class="rk"><?= htmlspecialchars($k) ?></span>
                    <span class="rv"><?= htmlspecialchars($v) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── Formulaire d'upload ─────────────────────────────────────────────────── -->
<section>
    <div class="container">
        <div class="sh">
            <span class="stag">$_FILES</span>
            <h2 class="stitle">Formulaire d'upload</h2>
            <div class="sdiv"></div>
        </div>

        <?php if ($result): ?>
            <div class="alert alert-<?= $result['type'] ?>">
                <?= $result['msg'] ?>
            </div>
            <?php if (!empty($result['details'])): ?>
                <div class="card" style="max-width:600px;margin-bottom:2rem">
                    <div class="card-head">// Détails du fichier reçu</div>
                    <table class="detail-table">
                        <?php foreach ($result['details'] as $k => $v): ?>
                            <tr><td><?= htmlspecialchars($k) ?></td><td><?= htmlspecialchars($v) ?></td></tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="grid2">
            <div>
                <form method="POST" enctype="multipart/form-data" action="upload.php">
                    <input type="hidden" name="MAX_FILE_SIZE" value="2097152">

                    <label for="fichier_input">
                        <div class="upload-zone" id="upload-zone">
                            <span class="upload-icon">&#128196;</span>
                            <p class="upload-label">Cliquez ou glissez un fichier ici</p>
                            <p class="upload-hint">JPEG, PNG, GIF, WEBP, TXT, PDF — max 2 Mo</p>
                            <p class="upload-hint" id="file-name" style="color:var(--accent2);margin-top:.5rem"></p>
                        </div>
                    </label>
                    <input type="file" id="fichier_input" name="fichier"
                           accept=".jpg,.jpeg,.png,.gif,.webp,.txt,.pdf">

                    <button type="submit" class="btn">&#9654; Tester l'upload</button>
                </form>
            </div>

            <div class="card">
                <div class="card-head">// Types autorisés & vérifications</div>
                <?php foreach ($allowed_types as $mime): ?>
                    <div class="row">
                        <span class="rk"><?= htmlspecialchars($mime) ?></span>
                        <span style="color:#4ade80;font-family:var(--mono);font-size:.72rem">✅</span>
                    </div>
                <?php endforeach; ?>
                <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);
                            font-family:var(--mono);font-size:.72rem;color:var(--muted)">
                    <p>// Sécurités appliquées :</p>
                    <p style="margin-top:.5rem;line-height:2">
                        ✓ Vérif. MIME type<br>
                        ✓ Vérif. taille max<br>
                        ✓ is_uploaded_file()<br>
                        ✓ Nom sécurisé (uniqid)<br>
                        ✓ htmlspecialchars() sur l'affichage<br>
                        ✓ Suppression après démo
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <p>&larr; <a href="index.php">Retour au portfolio</a> &middot;
           <a href="utils.php">Utilitaires</a> &middot;
           <a href="api.php">API JSON</a></p>
    </div>
</footer>

<script>
    const input = document.getElementById('fichier_input');
    const zone  = document.getElementById('upload-zone');
    const label = document.getElementById('file-name');

    input.addEventListener('change', () => {
        label.textContent = input.files[0] ? '📎 ' + input.files[0].name : '';
    });

    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            label.textContent = '📎 ' + e.dataTransfer.files[0].name;
        }
    });
</script>
</body>
</html>
