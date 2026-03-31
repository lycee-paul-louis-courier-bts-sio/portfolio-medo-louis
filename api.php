<?php
/**
 * Portfolio de Test — BTS SIO SLAM
 * api.php — Endpoint API REST simulé (sans base de données)
 * Retourne du JSON selon la route demandée via $_GET['route']
 */

// ─── Headers CORS & JSON ────────────────────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('X-Powered-By: PHP/' . PHP_VERSION . ' — Portfolio Test BTS SIO');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ─── Données ────────────────────────────────────────────────────────────────
$projets = [
    ['id' => 1, 'titre' => 'Application de Gestion',  'techno' => 'PHP / MySQL',     'note' => 16, 'annee' => 2024],
    ['id' => 2, 'titre' => 'Site Vitrine Responsive', 'techno' => 'HTML / CSS / JS', 'note' => 18, 'annee' => 2024],
    ['id' => 3, 'titre' => 'API REST',                'techno' => 'PHP / JSON',      'note' => 15, 'annee' => 2025],
    ['id' => 4, 'titre' => 'Script Automatisation',   'techno' => 'Python',          'note' => 17, 'annee' => 2025],
];

$competences = [
    ['id' => 1, 'nom' => 'PHP',        'niveau' => 4, 'categorie' => 'backend'],
    ['id' => 2, 'nom' => 'MySQL',      'niveau' => 3, 'categorie' => 'database'],
    ['id' => 3, 'nom' => 'HTML5',      'niveau' => 5, 'categorie' => 'frontend'],
    ['id' => 4, 'nom' => 'CSS3',       'niveau' => 4, 'categorie' => 'frontend'],
    ['id' => 5, 'nom' => 'JavaScript', 'niveau' => 3, 'categorie' => 'frontend'],
    ['id' => 6, 'nom' => 'Git',        'niveau' => 4, 'categorie' => 'outils'],
    ['id' => 7, 'nom' => 'Linux',      'niveau' => 3, 'categorie' => 'outils'],
    ['id' => 8, 'nom' => 'Python',     'niveau' => 2, 'categorie' => 'backend'],
];

// ─── Helpers ────────────────────────────────────────────────────────────────
function respond(mixed $data, int $status = 200, string $message = 'OK'): void {
    http_response_code($status);
    echo json_encode([
        'status'    => $status,
        'message'   => $message,
        'timestamp' => time(),
        'data'      => $data,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

function error404(string $msg = 'Route introuvable'): void {
    respond(null, 404, $msg);
}

// ─── Routeur ─────────────────────────────────────────────────────────────────
$route  = $_GET['route']  ?? 'info';
$id     = isset($_GET['id']) ? (int) $_GET['id'] : null;
$method = $_SERVER['REQUEST_METHOD'];

switch ($route) {

    // GET /api.php?route=info
    case 'info':
        respond([
            'api'         => 'Portfolio Test API',
            'version'     => '1.0.0',
            'php'         => PHP_VERSION,
            'serveur'     => $_SERVER['SERVER_SOFTWARE'] ?? 'Apache',
            'date'        => date('Y-m-d\TH:i:sP'),
            'routes'      => [
                'GET  api.php?route=info'              => 'Informations API',
                'GET  api.php?route=projets'           => 'Liste des projets',
                'GET  api.php?route=projets&id=1'      => 'Projet par ID',
                'GET  api.php?route=competences'       => 'Liste des compétences',
                'GET  api.php?route=competences&categorie=frontend' => 'Filtre par catégorie',
                'GET  api.php?route=stats'             => 'Statistiques globales',
                'POST api.php?route=echo'              => 'Renvoie le body reçu',
            ],
        ]);
        break;

    // GET /api.php?route=projets[&id=N]
    case 'projets':
        global $projets;
        if ($id !== null) {
            $found = array_values(array_filter($projets, fn($p) => $p['id'] === $id));
            $found ? respond($found[0]) : error404("Projet #$id introuvable");
        } else {
            respond(['count' => count($projets), 'items' => $projets]);
        }
        break;

    // GET /api.php?route=competences[&categorie=frontend]
    case 'competences':
        global $competences;
        $cat = $_GET['categorie'] ?? null;
        $list = $cat
            ? array_values(array_filter($competences, fn($c) => $c['categorie'] === $cat))
            : $competences;
        respond(['count' => count($list), 'items' => $list]);
        break;

    // GET /api.php?route=stats
    case 'stats':
        global $projets, $competences;
        $notes   = array_column($projets, 'note');
        respond([
            'projets'     => count($projets),
            'competences' => count($competences),
            'note_max'    => max($notes),
            'note_min'    => min($notes),
            'note_moy'    => round(array_sum($notes) / count($notes), 2),
            'categories'  => array_unique(array_column($competences, 'categorie')),
        ]);
        break;

    // POST /api.php?route=echo
    case 'echo':
        if ($method !== 'POST') {
            respond(null, 405, 'Méthode non autorisée. Utilisez POST.');
        }
        $body = file_get_contents('php://input');
        $decoded = json_decode($body, true);
        respond([
            'echo'     => $decoded ?? $body,
            'headers'  => [
                'Content-Type' => $_SERVER['CONTENT_TYPE'] ?? 'non défini',
                'User-Agent'   => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 80),
            ],
            'method'   => $method,
            'taille'   => strlen($body) . ' octets',
        ]);
        break;

    default:
        error404("Route '$route' inconnue. Consultez api.php?route=info");
}
