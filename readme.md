# Portfolio - Louis MEDO

Portfolio simple en PHP avec 2 pages:

- Page de presentation: `index.php`
- Page des projets: `projets.php`

## Structure

- `index.php`: contenu de presentation (profil, competences, objectif)
- `projets.php`: affichage des projets via une boucle PHP
- `data/projects.php`: source de donnees des projets
- `includes/layout.php`: en-tete et pied de page partages
- `assets/css/style.css`: styles globaux et responsive

## Maintenance

- Ajouter un projet: modifier le tableau dans `data/projects.php`
- Modifier la navigation: adapter `includes/layout.php`
- Ajuster le design: modifier `assets/css/style.css`

## Bonnes pratiques appliquees

- `declare(strict_types=1);` sur les fichiers PHP
- Separation entre donnees (`data/`), structure (`includes/`) et presentation (`assets/`)
- Echappement des sorties HTML avec `htmlspecialchars`
- HTML semantique et CSS centralise