
(document Dante word passat a markdown):

## Taula T_UWWTPS 

Camp uwwUWWTPSID:
és el ID. Jo el canviaria per uwwID i començaria des de 1. 

Camp uwwState

Camp rptMStateKey

Camp aggCode: Permet NULL (però que avisi) 

Camp uwwCode

Camp uwwName: Permet NULL

Camp uwwCollectingSystem

Camp uwwDateClosing: Permet NULL
Tipus de camp: Year

Camp uwwLatitude

Camp uwwLongitude

Camp uwwLoadEnteringUWWTP
Permet NULL

Camp uwwCapacity
Permet NULL

Camp uwwPrimaryTreatment
Permet NULL

Camp uwwSecondaryTreatment
Permet NULL

Camp uwwOtherTreatment
Permet NULL

Camp uwwNRemoval
Permet NULL

Camp uwwPRemoval
Permet NULL

Camp uwwUV
Permet NULL

Camp UwwChlorination
Permet NULL

Camp uwwOzonation
Permet NULL

Camp uwwSandFiltration
Permet NULL

Camp uwwMicroFiltration
Permet NULL

Camp uwwOther
Permet NULL

Camp uwwBOD5Perf
Permet NULL

Camp uwwCODPerf
Permet NULL

Camp uwwTSSPerf
Permet NULL

Camp uwwNtotPerf
Permet NULL

Camp uwwPTotPerf
Permet NULL

Camp uwwOtherPerf
Permet NULL

Camp uwwEndLife
Permet NULL
Tipus camp: Year

1. Pujar taula (mdb, acces o csv) a mysql
2. Comprovar que no hi hagi camps repetits a uwwID. NO N’HI HA MAI, EL NUMERO EL POSA AUTOMATICAMENT EL SISTEMA (?)
3. Comprovar que no hi hagi duplicats a uwwCode. En cas que n’hi hagi, s’hauria de notificar per tal que l’usuari esculli quin registre vol eliminar 
4. Avisar si hi ha NULL en aquells camps on no es permet NULL + avisar si hi ha NULL a aggcode tot i que en permeti 
5. Tractaments

5.1. Tipus tractament

Primari: Pr
Secundari: S
Terciari: T  (si hi ha algun camp de uwwchlorination, uwwozonation, uwwmicrofiltrarion i uwwsandfiltration amb 1, ja es considera terciari) 
Nitrogen: N
Fòsfor: P

Combinacions possibles: NULL, Pr, PrS, PrST, PrN, PrP, PrNP, PrSN, PrSP, PrSNP, PrT, PrSTNP, PrSTN, PrSTP, PrTN, PrTP, PrTNP, S, SN, SP, SNP, ST, STN, STP, STNP, T, TN, TNP, TP, NP, N, P. 

Condicionals:

si uwwprimarytreatment = NULL; output = NULL
si uwwprimarytreatment = 0; output = No Treatment
if uwwprimarytreatment => "Pr"
if uwwsecondarytreatment => "S"
if (uwwchlorination or uwwozonation or uwwmicrofiltration or uwwsandfiltration) => "T"
if uwwNremoval => "N"
if uwwPremoval => "P"

Un cop fet els condicionals, caldrà afegir una columna amb el nom: uwwTypeofTreatment i el resultat de cada condicional

## Taula T_Agglomerations

aggCode (clau primària)

aggName 
Permet NULL

aggEndLife
Permet NULL
Tipus camp Year 

AggGenerated
Permet NULL

AggLatitude

AggLongitude 

AggC1 canviar per C1
Permet NULL 

AggC2 canviar per C2
Permet NULL 

AggPercWithoutTreatment canviar per C3
Permet NULL

AggState


1. Pujar taula (csv) a mysql. 
2. Comprovar que no hi hagi duplicats en aggCode. En cas afirmatiu, notificar per tal que l’usuari decideixi quin registre eliminar
3. Avisar quan hi ha registres amb NULL en aquells camps on no es permet NULL 


## Taula T_DischargePoints

dcpDiscargePointsID
és la clau primaria. Jo canviaria per dcpID i començaria des d’1 

dcpState 

uwwCode

dcpCode (mirar si és aquesta la clau primària) (no poden haverhi duplicats)

dcpName
Permet NULL

dcpLatitude

dcpLongitude

dcpWaterBodyType

dcpIrrigation
Permet NULL

dcpTypeofReceivingArea
Permet NULL

rcaCode
Permet NULL

dcpSurfaceWaters
Permet NULL

dcpWaterBodyID
Permet NULL

dcpEndLife 
Permet NULL
Tipus camp: Year

1. Pujar taula (csv) a mysql
2. Comprovar que no hi hagi camps repetits a dcpID (pendent revisar, de moment no aplicar). 
3. Avisar quan hi ha registres amb NULL en aquells camps on no es permet NULL 

## Taula T_UWWTPS_emission_load

uwwCode

uwwName
Permet NULL

uwwBODIncoming
Permet NULL

uwwCODIncoming
Permet NULL

uwwNIncoming
Permet NULL

uwwPIncoming 
Permet NULL

uwwBODDischarge
Permet NULL

uwwCODDischarge
Permet NULL

uwwNDischarge
Permet NULL

uwwPDischarge 
Permet NULL

1. Pujar taula csv a mysql
2. Comprovar que no hi hagi duplicats a uwwCode. En cas que n’hi hagi, s’hauria de notificar per tal que l’usuari esculli quin registre vol eliminar 
3. Notificar si hi ha registres en NULL en aquells camps on no es permet NULL 

## Taula T_UWWTPAgglos 

AucUWWTP_AggloID
És la clau primaria. Jo la canviaria per uww_aggloID, i començaria des d’1 

aucUwwCode canviar per uwwCode

aucAggCode canviar per aggCode

aucPercEnteringUWWTP, canviar per C4 
Permet NULL

aucPercC2T, canviar per C5 
Permet NULL

1. Pujar taula (mdb, acces o csv) a mysql
2. Comprovar que no hi hagi camps repetits a uww_aggloID
3. Notificar si hi ha registres amb NULL en aquells camps que no permet NULL

Ajuntar totes les taules 

T_UWWTPS + T_Agglomeration + T_UWWTPS_emission_load + T_DisxhchargePoint (aquesta de moment no juntar) + relacions Agg-Uww

Crear un nou camp anomenat C6 = C1-C4
Crear un nou camp anomenat comprovació = C1 + C2 + C3 +C5 (ha de donar 100)
Operacions: 

Si camp Comprovació no dóna 100:
1. si Error == C5  C2 = C2 – C5
2. si Error == C6  C1 = C1 – C6  ; C4 = C4 – C6 
3. else Distribuir l’error. Exemple: 

| C1 | C2 | C3 | C5 | Comprovació |
|---------------------------------|
| 95 | 5 | 3 | 3 | 106

| C1 | C2 | C3 | C5 | Comprovació |
(95/106)x100 = 89,62 | (5/106)x100 = 4,72 | (3/106)x100 = 2,83 | (3/106)x100 =2,83 | 100

Nom output: T_UWWTD_final 

err = Comprovació – 100;
Si (err == C5) // aplicar 1.
  C2_aux = C2 – C5;
  Comprovació_aux = C1 + C2 + C3 + C5;
      err_aux = comprovació_aux – 100;
  Si (err_aux > 0) // mirar si err == C6 i aplicar 2.
Sino Si (err == C6) // aplicar 2.
  C1_aux = C1 – C6;
C4_aux = C4 – C6;
Comprovació_aux = C1 + C2 + C3 + C5;
err_aux = comprovació_aux – 100;
Si (err_aux > 0) // aplicar 3.
Sino // Aplicar 3 (sempre funciona)
