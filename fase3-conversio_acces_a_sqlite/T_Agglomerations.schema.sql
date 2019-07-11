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
	`aggAgglomorationsID`			INTEGER, 
	`aggCode`			varchar, 
	`aggName`			varchar, 
	`aggBeginLife`			DateTime, 
	`aggCalculation`			TEXT, 
	`aggChanges`			varchar, 
	`aggChangesComment`			TEXT, 
	`aggEndLife`			DateTime, 
	`aggGenerated`			INTEGER, 
	`aggLatitude`			INTEGER, 
	`aggLongitude`			INTEGER, 
	`aggC1`			INTEGER, 
	`aggMethodC1`			varchar, 
	`aggC2`			INTEGER, 
	`aggMethodC2`			varchar, 
	`aggNUTS`			varchar, 
	`aggMethodWithoutTreatment`			varchar, 
	`aggPercWithoutTreatment`			INTEGER, 
	`aggState`			varchar, 
	`bigCityID`			varchar, 
	`rptMStateKey`			varchar, 
	`ReportNetEnvelopeFileId`			INTEGER
);


-- CREATE Relationships ...
