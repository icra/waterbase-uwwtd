--------------------------------------------------------------------------------
# reunió oriol, lluís | 25/11/2019
--------------------------------------------------------------------------------
  * [fet] git pull sense sobreescriure base de dades oriol.

  * [fet] problemes generals:
    1. sempre que mirem depuradores:       columna uwwState ha de ser 1 (no pot ser 0 ni 2).
    2. sempre que mirem discharge-points : columna dcpState ha de ser 1 (no pot ser 0 ni 2).
    3. sempre que mirem aglomeracions:     columna aggState ha de ser 1 (no pot ser 0 ni 2).

  * accions a convertir a comandes SQL
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
    extreure les aglomeracions que tenen menys de 2000 habitants
    (T-Agglomerations, columna aggGenerated). s'assumeix que les aggs amb menys
    de 2000 habitants tenen "on site treatment".

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
  * [fet] trobar aglomeracions IAS a (columna "aggRemarks"): paraules clau:
    - IAS
    - septic

  * [fet] millores web

--------------------------------------------------------------------------------
# reunió oriol, lluís | 12/12/2019
--------------------------------------------------------------------------------
  * [fet] millores web

  * Cas aglomeracions sense depuradora:

    1. [fet] crear nova taula de conexions entre "aglomeració" i "IAS".

      ```
      T_conn_agg_IAS:
      1 | id           | integer
      2 | aggCode      | varchar
      3 | aggName      | varchar
      4 | rptMStateKey | varchar
      ```

    2. crear nova taula de connexions entre "aglomeració" i "res" (no tenen les
       parules clau a la columna de remark).  "descarreguen al riu".
      ```
      T_agg_NOTCON:
      1 | id           | integer
      2 | aggCode      | varchar
      3 | aggName      | varchar
      4 | rptMStateKey | varchar
      ```

    3. excloure de problemes trobats les aglomeracions que estiguin incloses en
       aquestes dues noves taules i que no estiguin a la taula de connexions.
