
/* COLUMNES REPETIDES */
CREATE TABLE T_UWWTD_final AS 
SELECT aux_t_uwwtps.uwwCode, aux_t_uwwtps.uwwState, aux_t_uwwtps.rptMStateKey FROM aux_t_uwwtps
LEFT JOIN aux_t_agglomerations ON aux_t_uwwtps.aggCode=aux_t_agglomerations.aggCode
LEFT JOIN aux_t_uwwtps_emission_load ON aux_t_uwwtps.uwwCode=aux_t_uwwtps_emission_load.uwwCode
LEFT JOIN aux_t_uwwtpagglos ON aux_t_uwwtps.uwwCode=aux_t_uwwtpagglos.uwwCode AND aux_t_agglomerations.aggCode=aux_t_uwwtpagglos.aggCode


/* COLUMNES NO REPETIDES */
CREATE TABLE T_UWWTD_final AS 
SELECT aux_t_uwwtps.uwwCode, aux_t_uwwtps.uwwState, aux_t_uwwtps.rptMStateKey, aux_t_uwwtps.aggCode, aux_t_uwwtps.uwwName, aux_t_uwwtps.uwwCollectingSystem, aux_t_uwwtps.uwwDateClosing, aux_t_uwwtps.uwwLatitude, aux_t_uwwtps.uwwLongitude, aux_t_uwwtps.uwwLoadEnteringUWWTP, aux_t_uwwtps.uwwCapacity, aux_t_uwwtps.uwwPrimaryTreatment, aux_t_uwwtps.uwwSecondaryTreatment, aux_t_uwwtps.uwwOtherTreatment, aux_t_uwwtps.uwwNRemoval, aux_t_uwwtps.uwwPRemoval, aux_t_uwwtps.uwwUV, aux_t_uwwtps.uwwChlorination, aux_t_uwwtps.uwwOzonation, aux_t_uwwtps.uwwSandFiltration, aux_t_uwwtps.uwwMicroFiltration, aux_t_uwwtps.uwwOther, aux_t_uwwtps.uwwBOD5Perf, aux_t_uwwtps.uwwCODPerf, aux_t_uwwtps.uwwTSSPerf, aux_t_uwwtps.uwwNtotPerf, aux_t_uwwtps.uwwPTotPerf, aux_t_uwwtps.uwwOtherPerf, aux_t_uwwtps.uwwEndLife, aux_t_uwwtps.uwwTypeofTreatment, aux_t_agglomerations.aggName, aux_t_agglomerations.aggEndLife, aux_t_agglomerations.aggGenerated, aux_t_agglomerations.aggLatitude, aux_t_agglomerations.aggLongitude, aux_t_agglomerations.C1, aux_t_agglomerations.C2, aux_t_agglomerations.C3, aux_t_agglomerations.aggState, aux_t_uwwtps_emission_load.emissionID, aux_t_uwwtps_emission_load.uwwBODIncoming, aux_t_uwwtps_emission_load.uwwCODIncoming, aux_t_uwwtps_emission_load.uwwNIncoming, aux_t_uwwtps_emission_load.uwwPIncoming, aux_t_uwwtps_emission_load.uwwBODDischarge, aux_t_uwwtps_emission_load.uwwCODDischarge, aux_t_uwwtps_emission_load.uwwNDischarge, aux_t_uwwtps_emission_load.uwwPDischarge, aux_t_uwwtpagglos.uww_aggloID, aux_t_uwwtpagglos.C4, aux_t_uwwtpagglos.C5 FROM aux_t_uwwtps
LEFT JOIN aux_t_agglomerations ON aux_t_uwwtps.aggCode=aux_t_agglomerations.aggCode
LEFT JOIN aux_t_uwwtps_emission_load ON aux_t_uwwtps.uwwCode=aux_t_uwwtps_emission_load.uwwCode
LEFT JOIN aux_t_uwwtpagglos ON aux_t_uwwtps.uwwCode=aux_t_uwwtpagglos.uwwCode AND aux_t_agglomerations.aggCode=aux_t_uwwtpagglos.aggCode