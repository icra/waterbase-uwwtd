LLISTA TASQUES PENDENTS A CONVERTIR A CODI (trobar problemes automatitzat)
---------------------------------
(document word dante)

PUNTS DE DESCÀRREGA + AGLOMERACIONS + DEPURADORES
-------------------------------------------------
  - [DIFÍCIL] si falla la comprovació de 50km entre aglomeracio depuradora i punt de descàrrega:
    - comprovar que geolocalització cau dins el el país:
      - llista bounding boxes (?)
      - coordenades countries; https://gist.github.com/graydon/11198540
      - comprovar que siguin ETRS89 (?)

DEPURADORES
-----------
  - [DIFÍCIL] que la geolocalització es correspongui amb el país
  - [NO ÚTIL] afegir una columna amb el nom "uwwTypeofTreatment"
    - crear string "PrSTNP"
      si uwwprimarytreatment == ""  => "No Treatment"
      if uwwprimarytreatment        => "Pr"
      if uwwsecondarytreatment      => "S"
      if (uwwchlorination or uwwozonation or uwwmicrofiltration or uwwsandfiltration) => "T"
      if uwwNremoval                => "N"
      if uwwPremoval                => "P"

  - [REDEFINIR] buscar depuradores que no tenen discharge points crear
   un discharge point amb les coordenades de la depuradora a
   la id hi posem la mateixa que la depuradora "xxx", per
   exemple: "xxx-discharge-point"

PUNTS DE DESCÀRREGA
-------------------
  - [DETECCIÓ DUPLICATS?] si hi ha més d'un punt de descàrrega, selecciona el més proper a la
  coordenada de la depuradora (?)

UWWTP-AGGLOs (relacional depuradora-aglomeració)
------------------------------------------------
  - comprovació percentatge PE == 100%
    sqlite>
      SELECT aggC1,aggC2,aggPercWithoutTreatment,aucPercEnteringUWWTP,aucPercC2T
      FROM T_Agglomerations AS a, T_UWWTPAgglos AS ua WHERE a.aggCode=ua.aucAggCode;

  aggC1                   es C1
  aggC2                   es C2
  aggPercWithoutTreatment es C3
  aucPercEnteringUWWTP    es C4
  aucPercC2T              es C5

  llavors, a la taula aglomeracions:

  c1: percentatge de PE de l'aglomeració que entra al sistema de col·lectors.
  c2: percentatge de PE de l'aglomeració que va a IAS.
  c3: percentatge de PE de l'aglomeració que és open defecation.
  c4: percentatge de C1 que entra a depuradora.
  c5: percentatge de PE de l'aglomeració que entra a depuradora a través de cisterna.
    Rate of generated load of agglomeration transported to this UWWTP by trucks (%)

  per cada aglomeració: Crear un nou camp anomenat "C6" = C1 - C4
  c6: percentatge de c1 que no entra a depuradora

  comprovar que C1 + C2 + C3 + C5 == 100

  Si camp Comprovació no dóna 100:
    [NO GAIRE CLAR]
    1. si Error == C5  => C2 = C2 – C5
    2. si Error == C6  => C1 = C1 – C6  ; C4 = C4 – C6
    3. else Distribuir l’error. Exemple:

    | C1 | C2 | C3 | C5 | Comprovació |
    | 95 | 5  | 3  | 3  | 106 |

    passar a:

    | C1                   | C2                 | C3                 | C5                | Comprovació
    | (95/106)x100 = 89,62 | (5/106)x100 = 4,72 | (3/106)x100 = 2,83 | (3/106)x100 =2,83 | 100

  - [REDEFINIR] s'ha de crear un punt de descàrrega per cada aglomeració que correspon a
  open defecation (que es pot fer servir o no) "punts que no van a
  depuradora"
