LLISTA TASQUES A CONVERTIR A CODI
---------------------------------

PUNTS DE DESCÀRREGA + AGLOMERACIONS + DEPURADORES
-------------------------------------------------
  - mirar si per cada aglomeració - depuradora - punt de descàrrega estan més
    lluny de 50 km
  - si falla la comprovació de 50km entre aglomeracio depuradora i punt de descàrrega:
  - [  ] geolocalització es correspongui amb el país
    - llista bounding boxes (?)
    - coordenades countries; https://gist.github.com/graydon/11198540
    - comprovar que siguin ETRS89 (?)
    - buscar mètode per trobar distància entre 2 coordenades

DEPURADORES
-----------
  - [  ] que la geolocalització es correspongui amb el país
  - [no cal] Tipus tractament. Comprovacions:
    - afegir una columna amb el nom "uwwTypeofTreatment"
    crear string "PrSTNP"
    si uwwprimarytreatment == ""  => "No Treatment"
    if uwwprimarytreatment        => "Pr"
    if uwwsecondarytreatment      => "S"
    if (uwwchlorination or uwwozonation or uwwmicrofiltration or
        uwwsandfiltration)        => "T"
    if uwwNremoval                => "N"
    if uwwPremoval                => "P"

  - [TODO] buscar depuradores que no tenen discharge points crear
   un discharge point amb les coordenades de la depuradora a
   la id hi posem la mateixa que la depuradora "xxx", per
   exemple: "xxx-discharge-point"

EMISSION LOADS (EXTENSIO DEPURADORES)
-------------------------------------
  - [TODO] buscar duplicats a uwwCode.
  SELECT * FROM T_UWWTPs_emission_load GROUP BY uwwCode HAVING COUNT(uwwCode)>1;

PUNTS DE DESCÀRREGA
-------------------
  - si hi ha més d'un punt de descàrrega, selecciona el més proper a la
  coordenada de la depuradora. 

UWWTP-AGGLOs (relacional depuradora-aglomeració)
------------------------------------------------
  1. buscar duplicats amb aucID

  2. comprovació percentatge PE == 100%
  sqlite> SELECT aggC1,aggC2,aggPercWithoutTreatment,aucPercEnteringUWWTP,aucPercC2T FROM T_Agglome
  rations AS a,T_UWWTP_Agglo AS ua WHERE a.aggCode=ua.aucAggCode;

  aggPercWithoutTreatment es C3
  aucPercEnteringUWWTP, es C4
  aucPercC2T, es C5

  llavors, a la taula aglomeracions:

  c1: percentatge de PE de l'aglomeració que entra al sistema de col·lectors.
  c2: percentatge de PE de l'aglomeració que va a IAS.
  c3: percentatge de PE de l'aglomeració que és open defecation.
  c4: percentatge de C1 que entra a depuradora.
  c5: percentatge de PE de l'aglomeració que entra a depuradora a través de cisterna.  Rate of generated load of agglomeration transported to this UWWTP by trucks (%)

  per cada aglomeració: Crear un nou camp anomenat "C6" = C1 - C4
  c6: percentatge de c1 que no entra a depuradora

  comprovar que C1 + C2 + C3 + C5 == 100

  Si camp Comprovació no dóna 100:
  1. si Error == C5  => C2 = C2 – C5
  2. si Error == C6  => C1 = C1 – C6  ; C4 = C4 – C6
  3. else Distribuir l’error. Exemple:

  | C1 | C2 | C3 | C5 | Comprovació |
  | 95 | 5  | 3  | 3 | 106 |

  | C1 | C2 | C3 | C5 | Comprovació |
  | (95/106)x100 = 89,62 | (5/106)x100 = 4,72 | (3/106)x100 = 2,83 | (3/106)x100 =2,83 | 100 |

  després de comprovar, tornar a mirar si sumen 100%

  s'ha de crear un punt de descàrrega per cada aglomeració que correspon a
  open defecation (que es pot fer servir o no) "punts que no van a
  depuradora"
