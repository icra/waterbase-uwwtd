-- ----------------------------------------------------------
-- MDB Tools - A library for reading MS Access database files
-- Copyright (C) 2000-2011 Brian Bruns and others.
-- Files in libmdb are licensed under LGPL and the utilities under
-- the GPL, see COPYING.LIB and COPYING files respectively.
-- Check out http://mdbtools.sourceforge.net
-- ----------------------------------------------------------

-- That file uses encoding UTF-8

DROP TABLE IF EXISTS `T_UWWTPS`;
CREATE TABLE `T_UWWTPS`
 (
	`aggCode`			varchar, 
	`repCode`			varchar, 
	`rptMStateKey`			varchar, 
	`uwwAccidents`			INTEGER, 
	`uwwBadDesign`			INTEGER, 
	`uwwBadPerformance`			INTEGER, 
	`uwwBeginLife`			DateTime, 
	`uwwBOD5Perf`			varchar, 
	`uwwBODDischargeCalculated`			INTEGER, 
	`uwwBODDischargeEstimated`			INTEGER, 
	`uwwBODDischargeMeasured`			INTEGER, 
	`uwwBODIncomingCalculated`			INTEGER, 
	`uwwBODIncomingEstimated`			INTEGER, 
	`uwwBODIncomingMeasured`			INTEGER, 
	`uwwCapacity`			INTEGER, 
	`uwwChlorination`			INTEGER, 
	`uwwCODDischargeCalculated`			INTEGER, 
	`uwwCODDischargeEstimated`			INTEGER, 
	`uwwCODDischargeMeasured`			INTEGER, 
	`uwwCode`			varchar, 
	`uwwCODIncomingCalculated`			INTEGER, 
	`uwwCODIncomingEstimated`			INTEGER, 
	`uwwCODIncomingMeasured`			INTEGER, 
	`uwwCODPerf`			varchar, 
	`uwwCollectingSystem`			varchar, 
	`uwwDateClosing`			DateTime, 
	`uwwEndLife`			DateTime, 
	`uwwHistorie`			TEXT, 
	`uwwHyperlink`			varchar, 
	`uwwInformation`			TEXT, 
	`uwwLatitude`			INTEGER, 
	`uwwLoadEnteringUWWTP`			INTEGER, 
	`uwwLongitude`			INTEGER, 
	`uwwMethodWasteWaterTreated`			varchar, 
	`uwwMicroFiltration`			INTEGER, 
	`uwwName`			varchar, 
	`uwwNDischargeCalculated`			INTEGER, 
	`uwwNDischargeEstimated`			INTEGER, 
	`uwwNDischargeMeasured`			INTEGER, 
	`uwwNIncomingCalculated`			INTEGER, 
	`uwwNIncomingEstimated`			INTEGER, 
	`uwwNIncomingMeasured`			INTEGER, 
	`uwwNRemoval`			INTEGER, 
	`uwwNTotPerf`			varchar, 
	`uwwNUTS`			varchar, 
	`uwwOther`			INTEGER, 
	`uwwOtherPerf`			varchar, 
	`uwwOtherTreatment`			INTEGER, 
	`uwwOzonation`			INTEGER, 
	`uwwPDischargeCalculated`			INTEGER, 
	`uwwPDischargeEstimated`			INTEGER, 
	`uwwPDischargeMeasured`			INTEGER, 
	`uwwPIncomingCalculated`			INTEGER, 
	`uwwPIncomingEstimated`			INTEGER, 
	`uwwPIncomingMeasured`			INTEGER, 
	`uwwPRemoval`			INTEGER, 
	`uwwPrimaryTreatment`			INTEGER, 
	`uwwPTotPerf`			varchar, 
	`uwwRemarks`			TEXT, 
	`uwwSandFiltration`			INTEGER, 
	`uwwSecondaryTreatment`			INTEGER, 
	`uwwSpecification`			varchar, 
	`uwwState`			INTEGER, 
	`uwwTSSPerf`			varchar, 
	`uwwUV`			INTEGER, 
	`uwwWasteWaterTreated`			INTEGER
);


-- CREATE Relationships ...
