<!DOCTYPE html>
<?php session_start(); ?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API JSON — Portfolio Test</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#0a0a0f;--bg2:#11111a;--bg3:#1a1a28;--border:#2a2a42;--accent:#7c6af7;
              --accent2:#4df0c8;--accent3:#f7a26a;--text:#e8e8f0;--muted:#7070a0;
              --radius:8px;--mono:'Space Mono',monospace;--sans:'Syne',sans-serif}
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

        .routes-list{display:flex;flex-direction:column;gap:.75rem}
        .route-item{display:flex;align-items:center;gap:1rem;background:var(--bg2);
                    border:1px solid var(--border);border-radius:var(--radius);padding:.9rem 1.2rem;
                    cursor:pointer;transition:border-color .2s}
        .route-item:hover{border-color:var(--accent)}
        .method{font-family:var(--mono);font-size:.65rem;padding:.25rem .6rem;border-radius:3px;
                font-weight:700;letter-spacing:.05em;min-width:45px;text-align:center}
        .get{background:rgba(77,240,200,.15);color:var(--accent2);border:1px solid rgba(77,240,200,.3)}
        .post{background:rgba(247,162,106,.15);color:var(--accent3);border:1px solid rgba(247,162,106,.3)}
        .route-path{font-family:var(--mono);font-size:.78rem;color:var(--text);flex:1}
        .route-desc{font-family:var(--mono);font-size:.7rem;color:var(--muted)}

        /* Testeur */
        .tester{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden}
        .tester-header{padding:.9rem 1.2rem;background:var(--bg3);border-bottom:1px solid var(--border);
                       display:flex;align-items:center;gap:1rem}
        .url-input{flex:1;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);
                   padding:.55rem .9rem;color:var(--accent2);font-family:var(--mono);font-size:.78rem}
        .url-input:focus{outline:none;border-color:var(--accent)}
        .btn{padding:.55rem 1.2rem;border:none;border-radius:var(--radius);background:var(--accent);
             color:#fff;font-family:var(--mono);font-size:.75rem;cursor:pointer;transition:opacity .2s}
        .btn:hover{opacity:.85}
        .tester-body{padding:1.2rem}
        .response-area{background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);
                       padding:1.2rem;font-family:var(--mono);font-size:.72rem;color:var(--accent2);
                       min-height:200px;max-height:500px;overflow:auto;white-space:pre;line-height:1.7}
        .response-meta{display:flex;gap:1.5rem;margin-bottom:.8rem;font-family:var(--mono);font-size:.7rem}
        .meta-item{color:var(--muted)}.meta-val{color:var(--accent3)}

        /* Quick links */
        .quick-links{display:flex;flex-wrap:wrap;gap:.6rem;margin-bottom:1.2rem}
        .ql{font-family:var(--mono);font-size:.7rem;padding:.3rem .8rem;border:1px solid var(--border);
            border-radius:3px;background:var(--bg2);color:var(--muted);cursor:pointer;transition:all .2s}
        .ql:hover{border-color:var(--accent2);color:var(--accent2)}

        footer{padding:2rem 0;text-align:center;font-family:var(--mono);font-size:.72rem;color:var(--muted)}
        footer a{color:var(--accent);text-decoration:none}

        .loading{color:var(--muted);animation:blink 1s step-end infinite}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
    </style>
</head>
<body>

<nav>
    <div class="container">
        <a class="nav-logo" href="index.php">&lt; retour</a>
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="utils.php">Utilitaires</a></li>
            <li><a href="upload.php">Upload</a></li>
            <li><a href="api_doc.php" class="active">API JSON</a></li>
        </ul>
    </div>
</nav>

<div class="page-header">
    <div class="container">
        <h1>API <span style="color:var(--accent)">JSON</span></h1>
        <p>// Endpoint REST simulé — Test des en-têtes, du routeur PHP, et des réponses JSON</p>
    </div>
</div>

<!-- ── Routes disponibles ─────────────────────────────────────────────────── -->
<section>
    <div class="container">
        <div class="sh">
            <span class="stag">routes</span>
            <h2 class="stitle">Endpoints disponibles</h2>
            <div class="sdiv"></div>
        </div>
        <div class="routes-list">
            <div class="route-item" onclick="setUrl('api.php?route=info')">
                <span class="method get">GET</span>
                <span class="route-path">api.php?route=info</span>
                <span class="route-desc">Informations de l'API</span>
            </div>
            <div class="route-item" onclick="setUrl('api.php?route=projets')">
                <span class="method get">GET</span>
                <span class="route-path">api.php?route=projets</span>
                <span class="route-desc">Liste de tous les projets</span>
            </div>
            <div class="route-item" onclick="setUrl('api.php?route=projets&id=2')">
                <span class="method get">GET</span>
                <span class="route-path">api.php?route=projets&id=2</span>
                <span class="route-desc">Projet par identifiant</span>
            </div>
            <div class="route-item" onclick="setUrl('api.php?route=competences')">
                <span class="method get">GET</span>
                <span class="route-path">api.php?route=competences</span>
                <span class="route-desc">Liste des compétences</span>
            </div>
            <div class="route-item" onclick="setUrl('api.php?route=competences&categorie=frontend')">
                <span class="method get">GET</span>
                <span class="route-path">api.php?route=competences&categorie=frontend</span>
                <span class="route-desc">Filtre par catégorie</span>
            </div>
            <div class="route-item" onclick="setUrl('api.php?route=stats')">
                <span class="method get">GET</span>
                <span class="route-path">api.php?route=stats</span>
                <span class="route-desc">Statistiques globales</span>
            </div>
            <div class="route-item" onclick="setUrl('api.php?route=echo'); setMethod('POST')">
                <span class="method post">POST</span>
                <span class="route-path">api.php?route=echo</span>
                <span class="route-desc">Renvoie le body reçu</span>
            </div>
        </div>
    </div>
</section>

<!-- ── Testeur interactif ─────────────────────────────────────────────────── -->
<section>
    <div class="container">
        <div class="sh">
            <span class="stag">testeur</span>
            <h2 class="stitle">Testeur interactif</h2>
            <div class="sdiv"></div>
        </div>

        <div class="quick-links">
            <span class="ql" onclick="setUrl('api.php?route=info')">info</span>
            <span class="ql" onclick="setUrl('api.php?route=projets')">projets</span>
            <span class="ql" onclick="setUrl('api.php?route=projets&id=1')">projet #1</span>
            <span class="ql" onclick="setUrl('api.php?route=projets&id=99')">404 test</span>
            <span class="ql" onclick="setUrl('api.php?route=competences')">compétences</span>
            <span class="ql" onclick="setUrl('api.php?route=competences&categorie=backend')">backend</span>
            <span class="ql" onclick="setUrl('api.php?route=stats')">stats</span>
        </div>

        <div class="tester">
            <div class="tester-header">
                <select id="method" style="background:var(--bg3);border:1px solid var(--border);
                    color:var(--accent3);font-family:var(--mono);font-size:.75rem;padding:.5rem .8rem;
                    border-radius:var(--radius);cursor:pointer">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                </select>
                <input type="text" id="url-input" class="url-input" value="api.php?route=info">
                <button class="btn" onclick="callApi()">&#9654; Envoyer</button>
            </div>

            <div class="tester-body">
                <div id="body-input-wrap" style="display:none;margin-bottom:1rem">
                    <label style="font-family:var(--mono);font-size:.72rem;color:var(--muted);display:block;margin-bottom:.4rem">
                        // Body JSON (POST)
                    </label>
                    <textarea id="body-input" rows="4"
                        style="width:100%;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);
                               padding:.8rem;color:var(--text);font-family:var(--mono);font-size:.75rem;resize:vertical">{"message": "test", "valeur": 42}</textarea>
                </div>

                <div class="response-meta" id="response-meta" style="display:none">
                    <span class="meta-item">Status : <span class="meta-val" id="meta-status">—</span></span>
                    <span class="meta-item">Durée : <span class="meta-val" id="meta-time">—</span></span>
                    <span class="meta-item">Taille : <span class="meta-val" id="meta-size">—</span></span>
                </div>

                <div class="response-area" id="response-area">
// Cliquez sur une route ou appuyez sur "Envoyer"...</div>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <p>&larr; <a href="index.php">Retour au portfolio</a> &middot;
           <a href="utils.php">Utilitaires</a> &middot;
           <a href="upload.php">Upload</a></p>
    </div>
</footer>

<script>
    const urlInput    = document.getElementById('url-input');
    const methodSel   = document.getElementById('method');
    const responseEl  = document.getElementById('response-area');
    const metaEl      = document.getElementById('response-meta');
    const bodyWrap    = document.getElementById('body-input-wrap');
    const bodyInput   = document.getElementById('body-input');

    methodSel.addEventListener('change', () => {
        bodyWrap.style.display = methodSel.value === 'POST' ? 'block' : 'none';
    });

    function setUrl(url) { urlInput.value = url; }
    function setMethod(m) { methodSel.value = m; bodyWrap.style.display = m === 'POST' ? 'block' : 'none'; }

    async function callApi() {
        const url    = urlInput.value.trim();
        const method = methodSel.value;
        if (!url) return;

        responseEl.textContent = '// Chargement...';
        responseEl.className = 'response-area loading';
        metaEl.style.display = 'none';

        const start = Date.now();
        try {
            const opts = { method, headers: { 'Content-Type': 'application/json' } };
            if (method === 'POST') opts.body = bodyInput.value;

            const res  = await fetch(url, opts);
            const text = await res.text();
            const elapsed = Date.now() - start;

            let formatted = text;
            try { formatted = JSON.stringify(JSON.parse(text), null, 2); } catch {}

            responseEl.textContent = formatted;
            responseEl.className = 'response-area';

            document.getElementById('meta-status').textContent = res.status + ' ' + res.statusText;
            document.getElementById('meta-time').textContent   = elapsed + ' ms';
            document.getElementById('meta-size').textContent   = new Blob([text]).size + ' octets';
            metaEl.style.display = 'flex';
        } catch (err) {
            responseEl.textContent = '// Erreur réseau : ' + err.message;
            responseEl.className = 'response-area';
        }
    }

    // Entrée clavier sur l'URL
    urlInput.addEventListener('keydown', e => { if (e.key === 'Enter') callApi(); });
</script>
</body>
</html>
