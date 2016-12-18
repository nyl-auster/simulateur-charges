CALCULETTE TNS
========================

**DEPRECATED : ce projet n'est plus maintenu, tout a été réecrit en angular JS sur une nouvelle version :
https://github.com/nyl-auster/eurl-calculator
**

Calcule les charges d'un travailleur non salarié en profession libérale pour une EURL à l'impot sur la société.
Basée sur symfony 2

DEPLOIEMENT
-----------

1 - composer install à la racine
2 - Donner les droits d'écriture à apache sur les dossiers logs et cache :

```
chgrp -R _www app/cache
chgrp -R _www app/logs
chmod -R g+w app/cache
chmod -R g+w app/logs
```
Ne pas hésiter à supprimer complètement cache/* et logs/* en cas de souci persistent.

UTILISATION
------------

Se rendre sur l'url  "eurl/is/pl"


