--------------------------------------------------------------------------------
# reunió oriol i lluís | 25/11/2019
--------------------------------------------------------------------------------

## accions generals
  - [fet] mirar com fer git pull només de la web per no sobreescriure base de dades
    local oriol.

  [fet] problemes generals:
  1. sempre que mirem depuradores:       columna uwwState ha de ser 1 (no pot ser 0 ni 2).
  1. sempre que mirem discharge-points : columna dcpState ha de ser 1 (no pot ser 0 ni 2).
  1. sempre que mirem aglomeracions:     columna aggState ha de ser 1 (no pot ser 0 ni 2).

## accions a convertir a comandes SQL
  1. [FET] depuradores que no es troben a la taula de connexions (T-UWWTPAgglos):
  cal mirar la columna uwwState, i ignorar les que són 0 ò 2.

  resultat: soluciona tots els problemes menys una depuradora.

  2. [FET] depuradores que no estan a la taula T-DischargePoints:
  cal mirar la columna uwwState, i ignorar les que són 0 ò 2.

  resultat: soluciona tots els problemes.

  3. [FET] dcps que el seu codi no es troba a taula T-UWWTPS:
  cal mirar la columna dcpState, i ignorar els que són 0 ò 2.

  resultat: soluciona tots els problemes.

  4. [FET] aglomeracions que no es troben a la taula de connexions (T-UWWTPAgglos):
  cal mirar la columna aggState, i ignorar els que són 0 ò 2.

  resultat: queden 2221 errors dels 3070 inicials.

  després,
  cercar aglomeracions que quedin a la taula T-UWWTPS, columna aggCode.
  i fer nova connexió entre depuradora i aglomeració a la taula T-UWWTPAgglos.

  (esperar: oriol mirarà columnes depuradora que coincideixen amb T-UWWTPAgglos)

  després [FET],
  extreure les aglomeracions que tenen menys de 2000 habitants (T-Agglomerations,
  columna aggGenerated). s'assumeix que les aggs < 2000 habitants tenen "on site
  treatment".

  després,
  - mirar quantes en queden.
  en cas que en quedin moltes:
    - associar aglomeració a la depuradora més propera (difícil de programar).
  si en queden poques, es solucionarà manualment.

  5. [FET] depuradores amb múltipes dcps:
  treure dcps amb dcpState==0 o dcpState==2.
  en cas que en quedin molts:
    - quedar-nos amb el que estigui més a prop.
  si en queden pocs, es solucionarà manualment.

--------------------------------------------------------------------------------
# reunió oriol, lluís, lluís | 2/12/2019
--------------------------------------------------------------------------------

trobar aglomeracions IAS a (columna "aggRemarks"): paraules clau:
  - IAS
  - septic

## cas aglomeracions sense depuradora:

- crear nova taula de conexions entre "aglomeració" i "IAS".

- crear nova taula de conexions entre "aglomeració" i "res" (no tenen les
  parules clau a la columna de remark).

- excloure de problemes trobats les aglomeracions que estiguin incloses en
  aquestes dues noves taules.
