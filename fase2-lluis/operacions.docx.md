(document Dante word passat a markdown):

LLISTA TASQUES A CONVERTIR A CODI
=================================

AGLOMERACIONS
-------------
  1. [OK] trobar duplicades (aggCode)
     "SELECT * FROM T_Agglomerations GROUP BY aggCode HAVING COUNT(aggCode)>1"

     * no n'hi ha

  2. [OK] no hi pot haver buits a longitud i latitud
     SELECT aggName,aggLongitude,aggLatitude 
     FROM   T_Agglomerations
     WHERE  aggLongitude is NULL
     OR     aggLatitude  is NULL;

     * n'hi ha 11:
      Gundelsheim-Höchstberg-Tiefenbach
      Gundelsheim-Obergriesheim
      Crailsheim-Onolzheim
      Jagstzell
      Riesbürg-Pflaumloch
      Seckach
      Waldbrunn-Strümpfelbrunn
      Oberes Rinschbachtal,Osterburken-Bofsheim
      Mühlacker-Mühlhausen
      Gutsbezirk Münsingen-Böttental
      Münsingen-Gundelfingen

  3. [  ] que la geolocalització es correspongui amb el país llista bounding boxes
    - coordenades countries; https://gist.github.com/graydon/11198540
    - comprovar que siguin ETRS89

DEPURADORES
-----------
  1. [OK] trobar duplicades (uwwCode)
    - "SELECT * FROM T_UWWTPs GROUP BY uwwCode HAVING COUNT(uwwCode)>1"
    - n'hi ha 2:
      1289677|1|IT||IT13Q13000000015|ROCCA_DI_CAMBIO|ISCON|||42.2381889018|13.4983916337|ITF11|2917|1500||1|0|0|0|0|1|0|0|0|0||P|P|P||||||27565
      1289987|1|IT||IT13Q13000000015|ROCCA_DI_CAMBIO|ISCON|||376103.823054|4677314.11236|ITF11|2917|1500||1|0|0|0|0|1|0|0|0|0||P|P|P||||||27565
      1289883|1|IT||IT16Q150000002|CHIATONA|ISCON|||40.5234|17.0485|ITF43|0|8000|0|1|0|1|1|1|1|0|1|0|0|||||NR|NR|NR|||27565
      1289988|1|IT||IT16Q150000002|CHIATONA|ISCON|||40.5234|17.0485|ITF43|0|8000|0|1|0|1|1|1|1|0|1|0|0||NA|NA|NA|NR|NR|NR|||27565

  2. [  ] que la geolocalització es correspongui amb el país

  3. troba buits a longitud i latitud
    - "SELECT * FROM T_UWWTPs WHERE uwwLatitude='' OR uwwLongitude='';"
    - n'hi ha 2569
    - posar la mateixa geolocalització de l'aglomeració

    SELECT 
      u.uwwName,
      u.uwwCode,
      a.aggName
    FROM 
      T_UWWTPS         as u,
      T_Agglomerations as a
    WHERE 
      (u.uwwLatitude is NULL 
      OR 
      u.uwwLongitude is NULL)
    AND
      a.aggCode = u.aggCode;

   4. Tipus tractament. Comprovacions:
    si uwwprimarytreatment == ""  => "No Treatment"
    if uwwprimarytreatment        => "Pr"
    if uwwsecondarytreatment      => "S"
    if (uwwchlorination or uwwozonation or uwwmicrofiltration or
        uwwsandfiltration)        => "T"
    if uwwNremoval                => "N"
    if uwwPremoval                => "P"

    afegir una columna amb el nom "uwwTypeofTreatment"
    crear string "PrSTNP"

   5. buscar depuradores que no tenen discharge points crear
   un discharge point amb les coordenades de la depuradora a
   la id hi posem la mateixa que la depuradora "xxx", per
   exemple: "xxx-discharge-point"

EMISSION LOADS (EXTENSIO DEPURADORES)
-------------------------------------
  1. Comprovar que no hi hagi duplicats a uwwCode.
  sqlite> SELECT * FROM T_UWWTPs_emission_load GROUP BY uwwCode HAVING COUNT(uwwCode)>1;


PUNTS DE DESCÀRREGA
-------------------
  1. comprovar camps repetits
  "SELECT * FROM T_DischargePoints GROUP BY dcpID HAVING COUNT(dcpID)>1"

  2. si hi ha més d'un punt de descàrrega, selecciona el més proper a la coordenada de la depuradora.
  "SELECT uwwCode FROM T_DischargePoints GROUP BY uwwCode HAVING COUNT(uwwCode)>1"

UWWTP-AGGLOs (relacional depuradora-aglomeració)
------------
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

  s'ha de crear un punt de descàrrega per cada aglomeració que correspon a open defecation (que es pot fer servir o no)
  "punts que no van a depuradora"

PUNTS DE DESCÀRREGA + AGLOMERACIONS + DEPURADORES
-------------------------------------------------
  1. mirar si per cada aglomeració - depuradora - punt de
  descàrrega estan més lluny de 50 km
