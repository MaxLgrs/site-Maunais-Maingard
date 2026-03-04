# MAUNAIS MAINGARD - Propositions web

Ce dossier contient 3 propositions de mini-sites (6 pages chacune) :

- `proposition-1-premium`
- `proposition-2-premium`
- `proposition-3-premium`

La page d'entree pour la presentation client est :

- `index.html`

## Lancer en local

Depuis la racine du repo :

```bash
python -m http.server 8090 --directory "Site web/Maingard"
```

Puis ouvrir :

- `http://127.0.0.1:8090`

## Publication GitHub Pages

Un workflow est ajoute :

- `.github/workflows/deploy-maingard-proposals.yml`

Au push sur `main`, il publie automatiquement `Site web/Maingard` sur GitHub Pages.
