# Utilisateurs de l'application LET
Le contenu de l'applicaiton **LET** est protégé par un système d'authentification reposant sur [Argon2Id](https://argon2.online/).

Il peut être utile de disposer, lors du développement, de comptes utilisateurs factices permettant de gérer et tester les fonctions de l'application.

**LET** utilise la dépendance **or-fixtures** en environnement de développement.

Vous pouvez utilisez la **fixture** **Users** afin de générer ces utilisateurs factices dans votre environnement.

Pour ce faire suivez ces étapes : 
- [x] dans le répertoire *src/DataFixtures*, copiez le fichier "users.dist.yaml" en "users.yaml"
- [x] Éditez ce fichier afin de créer vos utilisateurs factices selon l'exemple fournit dans le fichier **dist** d'origine tel que :
``` 
plop: # identifiant sympa de votre utilisateur
    email: plop@example.com # adresse email (sert d'identifiant au "log-in")
    clear: tagada # mot de passe en clair
    roles:
        - admin # role admin de l'application
```
- [x] Importez la fixture dans la base de données : ``` console doctrine:fixtures:load --append --group group_users ``` 