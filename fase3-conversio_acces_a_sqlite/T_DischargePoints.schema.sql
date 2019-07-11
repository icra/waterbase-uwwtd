-- ----------------------------------------------------------
-- MDB Tools - A library for reading MS Access database files
-- Copyright (C) 2000-2011 Brian Bruns and others.
-- Files in libmdb are licensed under LGPL and the utilities under
-- the GPL, see COPYING.LIB and COPYING files respectively.
-- Check out http://mdbtools.sourceforge.net
-- ----------------------------------------------------------

-- That file uses encoding UTF-8

DROP TABLE IF EXISTS `T_DischargePoints`;
CREATE TABLE `T_DischargePoints`
 (
	`dcpDischargePointsID`			INTEGER, 
	`dcpState`			INTEGER, 
	`rptMStateKey`			varchar, 
	`uwwCode`			varchar, 
	`dcpCode`			varchar, 
	`dcpName`			varchar, 
	`dcpNuts`			varchar, 
	`dcpLatitude`			REAL, 
	`dcpLongitude`			REAL, 
	`dcpWaterBodyType`			varchar, 
	`dcpIrrigation`			varchar, 
	`dcpTypeOfReceivingArea`			varchar, 
	`rcaCode`			varchar, 
	`dcpSurfaceWaters`			INTEGER, 
	`dcpWaterbodyID`			varchar, 
	`dcpNotAffect`			INTEGER, 
	`dcpMSProvide`			INTEGER, 
	`dcpCOMAccept`			INTEGER, 
	`dcpGroundWater`			varchar, 
	`dcpReceivingWater`			varchar, 
	`dcpWFDSubUnit`			varchar, 
	`dcpWFDRBD`			varchar, 
	`dcpRemarks`			TEXT, 
	`dcpWFDRBDReferenceDate`			DateTime, 
	`dcpWaterBodyReferenceDate`			DateTime, 
	`dcpGroundWaterReferenceDate`			DateTime, 
	`dcpReceivingWaterReferenceDate`			DateTime, 
	`dcpWFDSubUnitReferenceDate`			DateTime, 
	`ReportNetEnvelopeFileId`			INTEGER, 
	`dcpBeginLife`			DateTime, 
	`dcpEndLife`			DateTime
);


-- CREATE Relationships ...
