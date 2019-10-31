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
	`dcpBeginLife`			DateTime, 
	`dcpCode`			varchar, 
	`dcpCOMAccept`			INTEGER, 
	`dcpDischargePointsID`			INTEGER, 
	`dcpEndLife`			DateTime, 
	`dcpGeometry`			varchar, 
	`dcpGroundWater`			varchar, 
	`dcpGroundWaterReferenceDate`			DateTime, 
	`dcpIrrigation`			varchar, 
	`dcpLatitude`			REAL, 
	`dcpLongitude`			REAL, 
	`dcpMSProvide`			INTEGER, 
	`dcpName`			varchar, 
	`dcpNotAffect`			INTEGER, 
	`dcpNuts`			varchar, 
	`dcpReceivingWater`			varchar, 
	`dcpReceivingWaterReferenceDate`			DateTime, 
	`dcpRemarks`			TEXT, 
	`dcpState`			INTEGER, 
	`dcpSurfaceWaters`			INTEGER, 
	`dcpTypeOfReceivingArea`			varchar, 
	`dcpWaterbodyID`			varchar, 
	`dcpWaterBodyReferenceDate`			DateTime, 
	`dcpWaterBodyType`			varchar, 
	`dcpWFDRBD`			varchar, 
	`dcpWFDRBDReferenceDate`			DateTime, 
	`dcpWFDSubUnit`			varchar, 
	`dcpWFDSubUnitReferenceDate`			DateTime, 
	`rcaCode`			varchar, 
	`repCode`			varchar, 
	`rptMStateKey`			varchar, 
	`uwwCode`			varchar
);


-- CREATE Relationships ...
