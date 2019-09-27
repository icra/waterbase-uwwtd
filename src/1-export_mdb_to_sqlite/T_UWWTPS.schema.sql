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
	`uwwUWWTPSID`			INTEGER, 
	`uwwState`			INTEGER, 
	`rptMStateKey`			varchar, 
	`aggCode`			varchar, 
	`uwwCode`			varchar, 
	`uwwName`			varchar, 
	`uwwCollectingSystem`			varchar, 
	`uwwDateClosing`			DateTime, 
	`uwwHistorie`			TEXT, 
	`uwwLatitude`			INTEGER, 
	`uwwLongitude`			INTEGER, 
	`uwwNUTS`			varchar, 
	`uwwLoadEnteringUWWTP`			INTEGER, 
	`uwwCapacity`			INTEGER, 
	`uwwPrimaryTreatment`			INTEGER, 
	`uwwSecondaryTreatment`			INTEGER, 
	`uwwOtherTreatment`			INTEGER, 
	`uwwNRemoval`			INTEGER, 
	`uwwPRemoval`			INTEGER, 
	`uwwUV`			INTEGER, 
	`uwwChlorination`			INTEGER, 
	`uwwOzonation`			INTEGER, 
	`uwwSandFiltration`			INTEGER, 
	`uwwMicroFiltration`			INTEGER, 
	`uwwOther`			INTEGER, 
	`uwwSpecification`			varchar, 
	`uwwBOD5Perf`			varchar, 
	`uwwCODPerf`			varchar, 
	`uwwTSSPerf`			varchar, 
	`uwwNTotPerf`			varchar, 
	`uwwPTotPerf`			varchar, 
	`uwwOtherPerf`			varchar, 
	`uwwBeginLife`			DateTime, 
	`uwwEndLife`			DateTime, 
	`ReportNetEnvelopeFileId`			INTEGER
);


-- CREATE Relationships ...
