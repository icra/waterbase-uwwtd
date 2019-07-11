-- ----------------------------------------------------------
-- MDB Tools - A library for reading MS Access database files
-- Copyright (C) 2000-2011 Brian Bruns and others.
-- Files in libmdb are licensed under LGPL and the utilities under
-- the GPL, see COPYING.LIB and COPYING files respectively.
-- Check out http://mdbtools.sourceforge.net
-- ----------------------------------------------------------

-- That file uses encoding UTF-8

DROP TABLE IF EXISTS `T_UWWTPS_emission_load`;
CREATE TABLE `T_UWWTPS_emission_load`
 (
	`uwwState`			INTEGER, 
	`rptMStateKey`			varchar, 
	`uwwCode`			varchar, 
	`uwwName`			varchar, 
	`uwwBODIncoming`			INTEGER, 
	`uwwCODIncoming`			INTEGER, 
	`uwwNIncoming`			INTEGER, 
	`uwwPIncoming`			INTEGER, 
	`uwwBODDischarge`			INTEGER, 
	`uwwCODDischarge`			INTEGER, 
	`uwwNDischarge`			INTEGER, 
	`uwwPDischarge`			INTEGER, 
	`QAflag`			varchar, 
	`Remark`			varchar
);


-- CREATE Relationships ...
