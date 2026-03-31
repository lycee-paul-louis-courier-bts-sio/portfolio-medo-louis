# Portfolio - \<NOM Prenom\>

![Bannière BTS SIO](https://firetoak.github.io/bts-sio_hebergement-portfolio-slam/assets/banniere_bts-sio.png)

---

## Contexte

En tant qu'étudiant en BTS SIO (Services Informatiques aux Organisations), le portfolio professionnel est l'élément central de votre évaluation finale. Il témoigne des compétences techniques et méthodologiques acquises au cours de votre formation.

Ce dépôt Git possède un double rôle fondamental dans une approche d'ingénierie moderne :

1.  **Le Versioning :** Il assure la sauvegarde, la traçabilité et l'historique complet du code source de votre site web.
2.  **Le Déploiement Continu :** Il est le point d'entrée de l'infrastructure d'hébergement du lycée. Chaque modification validée sur ce dépôt déclenche une mise à jour automatisée de votre site en production sur le serveur web privé (VPS), sans aucune intervention manuelle de votre part.

---

## Utiliser le dépôt

1.  **Cloner le dépôt sur votre machine locale.** Récupérez le code source pour commencer à travailler sur votre ordinateur.

```bash
git clone https://github.com/lycee-paul-louis-courier-bts-sio/portfolio-<nom-prenom>.git
```

2.  **Créer une branche de développement.** Il est déconseillé de travailler directement sur la branche de production. Créez une branche isolée pour vos tests.

```bash
git branch -c dev-nouvelle-fonctionnalite
```

3.  **Sauvegarder et valider vos modifications (Commit).** Une fois vos fichiers HTML/CSS/PHP modifiés, ajoutez-les à l'historique de Git.

```bash
git add .
git commit -m "feat: ajout de la page de présentation des projets"
```

4.  **Pousser et déployer en production.** Basculez sur la branche principale, fusionnez votre travail, et envoyez-le sur GitHub. Cette action déclenchera la mise à jour de votre site en ligne.

```bash
git switch main
git merge dev-nouvelle-fonctionnalite
git push origin main
```

---

## Configuration de la CI/CD

*Qu'est-ce que le "CI/CD" ?*

Le CI/CD (*Continuous Integration / Continuous Deployment* ou Intégration et Déploiement Continus) est une pratique standard dans le monde professionnel (DevOps/SRE). Concrètement, cela signifie la fin des transferts de fichiers manuels et risqués via des logiciels FTP (comme FileZilla).

Ici, nous utilisons **GitHub Actions**. Dès que vous effectuez un `git push` sur la branche `main`, un script automatisé (pipeline) s'exécute chez GitHub. Ce pipeline se connecte de façon hautement sécurisée (via clé SSH cryptée) au serveur Linux du lycée, et ordonne au serveur de télécharger uniquement vos nouveaux fichiers. Votre site web est ainsi mis à jour de façon invisible en moins de 10 secondes.

**Configuration requise :**

L'infrastructure étant centralisée, **vous n'avez aucun mot de passe ou adresse IP à configurer**. Les secrets de sécurité sont gérés au niveau de l'organisation GitHub par l'administrateur système.

Pour que la magie opère, vous devez simplement vous assurer que le fichier `.github/workflows/deploy.yml` existe à la racine de votre projet avec le code exact ci-dessous. **Ne modifiez pas ce fichier**, il est calibré pour communiquer avec le pare-feu du serveur.

```yaml
name: Déploiement du Portfolio
on:
  push:
    branches:
      - main
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Connexion sécurisée et mise à jour
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.VPS_HOST }}
          username: github-deploy-portfolio
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: ${{ github.event.repository.name }}
```

---

## Informations du dépôt

**Responsable infrastructure :** MEDO Louis - [Email](mailto:louis.medo@loutik.fr)  
**Mainteneur du dépôt :** <Nom prénom de l'étudiant> - [Email](mailto:email@etudiant.fr)