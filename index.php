<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CI/CD Test</title>
    <style>
        body { font-family: monospace; background: #0d1117; color: #c9d1d9; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .card { background: #161b22; border: 1px solid #30363d; border-radius: 8px; padding: 2rem 3rem; text-align: center; }
        h1 { color: #58a6ff; margin-bottom: .5rem; }
        .badge { display: inline-block; background: #238636; color: #fff; padding: .3rem .8rem; border-radius: 20px; font-size: .8rem; margin-top: 1rem; }
        .info { color: #8b949e; font-size: .85rem; margin-top: 1.5rem; line-height: 2; }
    </style>
</head>
<body>
    <div class="card">
        <h1>✅ Déploiement OK</h1>
        <p>Pipeline CI/CD opérationnel</p>
        <span class="badge">BUILD PASSED</span>
        <div class="info">
            PHP <?= PHP_VERSION ?> · <?= date('d/m/Y H:i:s') ?><br>
            Serveur : <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Apache' ?>
        </div>
    </div>
</body>
</html>