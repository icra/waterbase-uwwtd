-- ----------------------------------------------------------
-- MDB Tools - A library for reading MS Access database files
-- Copyright (C) 2000-2011 Brian Bruns and others.
-- Files in libmdb are licensed under LGPL and the utilities under
-- the GPL, see COPYING.LIB and COPYING files respectively.
-- Check out http://mdbtools.sourceforge.net
-- ----------------------------------------------------------

-- That file uses encoding UTF-8

DROP TABLE IF EXISTS `T_UWWTPAgglos`;
CREATE TABLE `T_UWWTPAgglos`
 (
	`aggID`			varchar, 
	`aucAggCode`			varchar, 
	`aucAggName`			varchar, 
	`aucMethodPercEnteringUWWTP`			varchar, 
	`aucPercC2T`			INTEGER, 
	`aucPercEnteringUWWTP`			INTEGER, 
	`aucUwwCode`			varchar, 
	`aucUwwName`			varchar, 
	`aucUWWTP_AggloID`			INTEGER, 
	`repCode`			varchar, 
	`rptMStateKey`			varchar
);


-- CREATE Relationships ...
