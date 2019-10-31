-- ----------------------------------------------------------
-- MDB Tools - A library for reading MS Access database files
-- Copyright (C) 2000-2011 Brian Bruns and others.
-- Files in libmdb are licensed under LGPL and the utilities under
-- the GPL, see COPYING.LIB and COPYING files respectively.
-- Check out http://mdbtools.sourceforge.net
-- ----------------------------------------------------------

-- That file uses encoding UTF-8

DROP TABLE IF EXISTS `T_Agglomerations`;
CREATE TABLE `T_Agglomerations`
 (
	`aggAccOverflowNumber`			varchar, 
	`aggAccOverflows`			varchar, 
	`aggBeginLife`			DateTime, 
	`aggBeginLife_original`			varchar, 
	`aggBestTechnicalKnowledge`			varchar, 
	`aggC1`			INTEGER, 
	`aggC2`			INTEGER, 
	`aggCalculation`			TEXT, 
	`aggCapacity`			varchar, 
	`aggChanges`			varchar, 
	`aggChangesComment`			TEXT, 
	`aggCode`			varchar, 
	`aggDilutionRates`			varchar, 
	`aggEndLife`			DateTime, 
	`aggExistMaintenancePlan`			varchar, 
	`aggExplanationOther`			varchar, 
	`aggForecast`			varchar, 
	`aggGenerated`			INTEGER, 
	`aggHaveRegistrationSystem`			varchar, 
	`aggLatitude`			INTEGER, 
	`aggLongitude`			INTEGER, 
	`aggMethodC1`			varchar, 
	`aggMethodC2`			varchar, 
	`aggMethodWithoutTreatment`			varchar, 
	`aggName`			varchar, 
	`aggNUTS`			varchar, 
	`aggOtherMeasures`			varchar, 
	`aggPercPrimTreatment`			INTEGER, 
	`aggPercSecTreatment`			INTEGER, 
	`aggPercStringentTreatment`			INTEGER, 
	`aggPercWithoutTreatment`			INTEGER, 
	`aggPeriodOver`			varchar, 
	`aggPressureTest`			varchar, 
	`aggRemarks`			TEXT, 
	`aggSewageNetwork`			varchar, 
	`aggSewerOverflows_m3`			varchar, 
	`aggSewerOverflows_pe`			varchar, 
	`aggState`			varchar, 
	`aggVideoInspections`			varchar, 
	`bigCityID`			varchar, 
	`repCode`			varchar, 
	`rptMStateKey`			varchar, 
	`aggDateArt3`			DateTime, 
	`aggDateArt4`			DateTime, 
	`aggDateArt5`			DateTime, 
	`aggHyperlink`			varchar
);


-- CREATE Relationships ...
