# Acces local - MAUNAIS MAINGARD

Tu ne peux pas utiliser `npm run dev` dans `proposition-1-premium` (ou 2/3), car ces dossiers sont en HTML/CSS/JS statiques et n'ont pas de `package.json`.

## Methode recommandee (PowerShell)

1. Ouvre un terminal PowerShell a la racine du repo:

```powershell
cd "C:\Users\Maxime\Antigravity\opencode"
```

2. Lance un serveur local:

```powershell
python -m http.server 8090 --directory "Site web\Maingard"
```

3. Ouvre dans le navigateur:

```text
http://127.0.0.ok1:8090
```

Tu arrives sur la page hub avec les 3 propositions.

## Methode robuste (depuis n'importe quel dossier)

Si tu veux eviter les erreurs de chemin relatives, utilise le chemin absolu:

```powershell
python -m http.server 8090 --directory "C:\Users\Maxime\Antigravity\opencode\Site web\Maingard"
```

Puis ouvre `http://127.0.0.1:8090`.

## Si tu es deja dans `proposition-1-premium`

Ton cas dans la capture vient de la commande:

```powershell
python -m http.server 8090 --directory "Site web\Maingard"
```

Depuis `proposition-1-premium`, ce chemin est faux (il pointe vers un dossier qui n'existe pas), donc tu obtiens un `404 File not found`.

Utilise une de ces 2 options:

- Servir tout le dossier `Maingard` (hub + 3 propositions):

```powershell
python -m http.server 8090 --directory ".."
```

- Servir uniquement la proposition 1:

```powershell
python -m http.server 8090
```

Dans ce 2e cas, ouvre `http://127.0.0.1:8090/index.html`.

## Commandes exactes par site

### 1) Hub (les 3 propositions)

Depuis n'importe quel dossier:

```powershell
python -m http.server 8090 --directory "C:\Users\Maxime\Antigravity\opencode\Site web\Maingard"
```

URL:

```text
http://127.0.0.1:8090
```

### 2) Proposition 1 uniquement

Commande directe (depuis n'importe quel dossier):

```powershell
python -m http.server 8091 --directory "C:\Users\Maxime\Antigravity\opencode\Site web\Maingard\proposition-1-premium"
```

URL:

```text
http://127.0.0.1:8091
```

### 3) Proposition 2 uniquement

Commande directe (depuis n'importe quel dossier):

```powershell
python -m http.server 8092 --directory "C:\Users\Maxime\Antigravity\opencode\Site web\Maingard\proposition-2-premium"
```

URL:

```text
http://127.0.0.1:8092
```

### 4) Proposition 3 uniquement

Commande directe (depuis n'importe quel dossier):

```powershell
python -m http.server 8093 --directory "C:\Users\Maxime\Antigravity\opencode\Site web\Maingard\proposition-3-premium"
```

URL:

```text
http://127.0.0.1:8093
```

### Arreter un serveur

Dans le terminal correspondant: `Ctrl + C`.

## Pourquoi tu as eu ERR_CONNECTION_REFUSED

- `npm run dev` a echoue (pas de `package.json`), donc aucun serveur n'a demarre.
- Sans serveur actif, `127.0.0.1:8090` refuse la connexion.

## Verifications rapides

- Verifier que Python est installe:

```powershell
python --version
```

- Si le port 8090 est deja pris, utilise un autre port:

```powershell
python -m http.server 8091 --directory "Site web\Maingard"
```

puis ouvre `http://127.0.0.1:8091`.

## Arreter le serveur

Dans le terminal ou tourne le serveur: `Ctrl + C`.
