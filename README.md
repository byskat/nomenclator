# Nomenclàtor

## Estructura

    .
    ├── documents       # Documents mostrats a +info (comisió del nomenclàtor)
    │   ├── actes
    │   └── reglament
    ├── resources		# Tots els recursos del projecte
    │   ├── css           # Fitxers d'estil (tant css com sass)
    │   ├── fonts         # Fonts utilitzades en local
    │   ├── img           # Imatges del projecte (en desús)
    │   ├── js            # 
    │   ├── php           # 
    │   └── vendor        # Totes les dependències
    ├── index.php		# Pàgina principal del nomenclàtor
    ├── +info.php		# Comisió del nomenclàtor
    └── filtre.json 	# Filtre configurable per la cerca

Info del [Javascript](#javascript), [PHP](#php) i [Dependències](#desenvolupat-amb)

## Configuració

### Filtre

El filtre del cercador es pot configurar mitjançant el document *filtre.json* que conté les dades per mostrar i generar les consultes a la base de dades.
	
```
  {
      "codi_tipus_via" : {
          "nom" : "Vies",
          "valors" : {
              "CR" : "Carrer",
              "PL" : "Plaça",
              "PT" : "Passatge",
              "RT" : "Rotonda",
              "PJ" : "Pujada",
              "PG" : "Passeig"
          }
      }
  }
```

L'estructura és un objecte que conté diversos paràmetres que alhora contenen un objecte. En aquest cas, és un objecte que s'anomena "codi_tipus_via" (que és el nom del camp de la base de dades sobre el que filtrarem). A més conté dos paràmetres:
<ul>
	<li><b>nom</b>: un string que serà el que es dibuixi en el front end.</li>
    <li><b>valors</b>: un objecte/array associatiu, que contindrà els valors de la base de dades pel qual volem filtrar a la clau i l'string per mostrar al front end com a valor.</li>
</ul>
Si es volen afegir nous camps per filtrar, senzillament es dóna un nou paràmetre amb la mateixa estructura:

```
  {
      "codi_tipus_via" : {
          ...
      },
      "canvi_any" : {
          ...
      }
  }
```
#### Valors

La clau de l'objecte que es passa a "valors" és el valor que es troba a la base de dades, és a dir, ha d'existir prèviament, perquè si no, no filtrarà res i no apareixeran resultats. Tot i que hi ha excepcions:

```
  ...
  "valors" : {
    "home" 		: "Home",
    "dona" 		: "Dona",
    "neutre" 	: "Neutre",
    "home|dona" : "Mixte"  Com podem veure aqui
  }
  ...
```

En aquest cas podem veure com s'utilitza "home|dona" per filtrar tant per home com per dona "sumant" ambdós casos, això és possible perquè la consulta generada fa servir 'SIMILAR TO' i per tant es poden concatenar valors afegint si s'agreguen (|, or), o s'exclouen (&, and). També permet l'us de comodins (wildcards: "%").

La generació del filtre es fa en local utilitzant aquest arxiu, per tant, no comprova que els camps i valors descrits a l'arxiu siguin correctes, donant error en l'execució de la consulta si hi ha errors.

### Base de Dades

En aquest projecte les dades de connexió a la base de dades es troben separades de l'arxiu on es fa la connexió i s'executen les consultes. Això es fa per agilitzar el procés en cas de canvi de servidor, però sobretot com a mesura de seguretat.

L'arxiu s'ha de col·locar a './resources/php/' amb una estructura com aquesta:

```
  [database]
  connector = pgsql
  host = server.org
  port = 5432
  username = admin
  password = 12345
  dbname = nomenclator
```
El nom de l'arxiu per defecte és "*web_umat_nomenclator.ini*", però es pot canviar sempre que es modifiqui la declaració de l'objecte `$db` que es troba a "*server.php*".
```
  ...
    $db = new Db("nom_nou.ini");
  ...
```

## Javascript

El javascript del projecte està dividit en dos fitxers, main-script.js i functions.js.

A main-script principalment hi trobàrem la inicialització de la pàgina (dibuixar el filtre, el selector de l'abecedari i carregar les dades per primer cop) i els handlers dels botons, finestra, teclat, etc...

A functions hi trobarem la major part del codi. Aquest es troba comentat (en anglès).

## PHP

El PHP del projecte està dividit en dos fitxers, server.php i postgre.class.php, incloent-hi també l'arxiu de configuració de la base de dades (ini).

Els dos primers fitxers fan referencia al backend o server, que assumeix el rol d'API i per tant, pot ser [separat del projecte](#frontend--backend). postgre.class es fa servir com intermediaria per utilitzar correctament la connexió de la base de dades. Ambdós fitxers són comentats (anglès).

## Desplegament

Per desplegar l'aplicació tal com està al repositori només cal tenir una instal·lació vàlida d'un servidor web amb postgre i col·locar la carpeta del projecte a un directori públic (www/htdocs).

Un cop fet, s'ha de [configurar](#base-de-dades) l'accés a la base de dades.

### Frontend / Backend

És possible que es vulgui separar el frontend del backend; en aquest cas, per poder fer-ho s'ha de separar el contingut de resources/php/ i col·locar-lo al directori desitjat (i tingui accés a la base de dades i estigui [configurada](#base-de-dades)).
També serà necessari actualitzar la localització del servidor al javascript (o, en aquest cas, frontend), modificant el valor de la variable 'serverURL' que es troba a la capçalera de functions.js.

```
  ...
    // Where the data is requested.
    var serverURL = "nou/path/al/server.php";
  ...
```

Alhora, server.php ha de tenir accés a la classe adjunta postgre.class.php.

## Desenvolupat amb

* [Bootstrap](http://getbootstrap.com/) - Frontend framework
* [JQuery](https://jquery.com/) - Llibreria de javascript
* [SASS](http://sass-lang.com/) - Llenguatge de fulles d'estil
