symfony new my_project_directory --version="6.3.*" --webapp

 symfony console about


objectif: télécharger des fichiers dans un dossiers protégé  et pas dans le dossier public 



 ## ------------------ git flow ------------------------

voir les commandes

```console
gitflow -h
```

```console
git flow init -d
```
le -d accepte la nom des branches proposées

### ------------------ feature -------------------

git flow feature start <name>  (démarre une branche feature)

git flow feature publish <name> (si on veu la push)

git flow feature finish <name>  

 (termine la branche, ramène la branche dévelop et mergela feature et sup la feature)

### ------------------- release --------------------

git flow release start <name> (name = 1.0.0 par exemple ca prépare une livraison a la prod)

git flow release finish <name> (ca merge dans la master et se replace sur develop )

### --------------------- hotfix --------------------

git flow hotfix start <name> (nouvelle branche depuis main)

git flow hotfix finish <name> (merge dans la main)

## ---------------------------------------------------------


