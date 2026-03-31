# Portfolio de Test — BTS SIO SLAM
## Vérification serveur Apache + PHP

---

## 📁 Structure des fichiers

```
portfolio-test/
├── index.php       → Page principale (variables, boucles, fonctions, formulaire POST)
├── utils.php       → Tests avancés (sessions, cookies, regex, JSON, fichiers)
├── upload.php      → Test d'upload de fichiers ($_FILES, sécurité)
├── api.php         → Endpoint API REST JSON (routeur PHP natif)
├── api_doc.php     → Documentation + testeur interactif de l'API
├── .htaccess       → Configuration Apache (sécurité, compression, cache)
└── README.md       → Ce fichier
```

---

## 🚀 Installation

1. Copier le dossier `portfolio-test/` dans la racine Apache :
   - **WAMP** : `C:/wamp64/www/`
   - **XAMPP** : `C:/xampp/htdocs/`
   - **Linux** : `/var/www/html/`

2. Accéder via le navigateur :
   ```
   http://localhost/portfolio-test/
   ```

---

## ✅ Ce qui est testé

| Fichier        | Fonctionnalités testées |
|---------------|------------------------|
| `index.php`   | Variables, tableaux, `foreach`, fonctions, `match`, `$_POST`, sessions, `htmlspecialchars`, `filter_input` |
| `utils.php`   | `$_SESSION`, `$_COOKIE`, `preg_match`, `json_encode/decode`, `file_put_contents`, `file_get_contents`, fonctions chaînes & dates |
| `upload.php`  | `$_FILES`, `move_uploaded_file`, `is_uploaded_file`, types MIME, sécurité upload |
| `api.php`     | Headers JSON, routeur `switch`, `http_response_code`, `json_encode`, `file_get_contents('php://input')` |
| `api_doc.php` | Fetch API JS, testeur interactif |
| `.htaccess`   | `mod_rewrite`, `mod_headers`, `mod_deflate`, `mod_expires`, sécurité |

---

## 🔧 Prérequis serveur

- **Apache 2.4+** avec `mod_rewrite`, `mod_headers` activés
- **PHP 8.0+** (utilise `match`, fonctions fléchées `fn()`)
- Extensions PHP recommandées : `json`, `pdo`, `mbstring`, `gd`, `fileinfo`

---

## 🛡️ Bonnes pratiques appliquées

- `htmlspecialchars()` sur toutes les sorties utilisateur
- `filter_input()` / `filter_var()` pour la validation
- `is_uploaded_file()` avant tout traitement de fichier
- Noms de fichiers sécurisés avec `uniqid()`
- Sessions démarrées avec `session_start()`
- Aucune base de données requise

---

*BTS SIO SLAM — Portfolio de test serveur*
