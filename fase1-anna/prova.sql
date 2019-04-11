-- phpMyAdmin SQL Dump

-- Estructura de tabla para la tabla `t_agglomerations`
DROP TABLE IF EXISTS `t_agglomerations`;
CREATE TABLE IF NOT EXISTS `t_agglomerations` (
  `aggCode` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `aggName` longtext COLLATE utf8_unicode_ci,
  `aggEndLife` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aggGenerated` int(30) DEFAULT NULL,
  `aggLatitude` float(12,8) NOT NULL,
  `aggLongitude` float(12,8) NOT NULL,
  `C1` float(12,8) DEFAULT NULL,
  `C2` float(12,8) DEFAULT NULL,
  `C3` float(12,8) DEFAULT NULL,
  `aggState` int(3) NOT NULL,
  PRIMARY KEY (`aggCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Estructura de tabla para la tabla `t_dischargepoints`
DROP TABLE IF EXISTS `t_dischargepoints`;
CREATE TABLE IF NOT EXISTS `t_dischargepoints` (
  `dcpID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dcpState` int(3) NOT NULL,
  `uwwCode` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `dcpCode` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `dcpName` longtext COLLATE utf8_unicode_ci,
  `dcpLatitude` float(12,8) NOT NULL,
  `dcpLongitude` float(12,8) NOT NULL,
  `dcpWaterBodyType` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `dcpIrrigation` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dcpTypeofReceivingArea` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rcaCode` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dcpSurfaceWaters` int(3) DEFAULT NULL,
  `dcpWaterBodyID` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dcpEndLife` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`dcpID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Estructura de tabla para la tabla `t_uwwtpagglos`
DROP TABLE IF EXISTS `t_uwwtpagglos`;
CREATE TABLE IF NOT EXISTS `t_uwwtpagglos` (
  `uww_aggloID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uwwCode` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `aggCode` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `C4` float(12,8) DEFAULT NULL,
  `C5` float(12,8) DEFAULT NULL,
  PRIMARY KEY (`uww_aggloID`),
  INDEX (`aggCode`),
  FOREIGN KEY (`aggCode`) REFERENCES t_agglomerations(`aggCode`),
  INDEX (`uwwCode`),
  FOREIGN KEY (`aggCode`) REFERENCES t_uwwtps(`aggCode`)
  
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Estructura de tabla para la tabla `t_uwwtps`
DROP TABLE IF EXISTS `t_uwwtps`;
CREATE TABLE IF NOT EXISTS `t_uwwtps` (
  `uwwCode` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `uwwState` int(5) NOT NULL,
  `rptMStateKey` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `aggCode` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `uwwName` longtext COLLATE utf8_unicode_ci,
  `uwwCollectingSystem` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `uwwDateClosing` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uwwLatitude` double(12,8) NOT NULL,
  `uwwLongitude` double(12,8) NOT NULL,
  `uwwLoadEnteringUWWTP` int(15) DEFAULT NULL,
  `uwwCapacity` int(15) DEFAULT NULL,
  `uwwPrimaryTreatment` int(3) DEFAULT NULL,
  `uwwSecondaryTreatment` int(3) DEFAULT NULL,
  `uwwOtherTreatment` int(3) DEFAULT NULL,
  `uwwNRemoval` int(3) DEFAULT NULL,
  `uwwPRemoval` int(3) DEFAULT NULL,
  `uwwUV` int(3) DEFAULT NULL,
  `uwwChlorination` int(3) DEFAULT NULL,
  `uwwOzonation` int(3) DEFAULT NULL,
  `uwwSandFiltration` int(3) DEFAULT NULL,
  `uwwMicroFiltration` int(3) DEFAULT NULL,
  `uwwOther` int(3) DEFAULT NULL,
  `uwwBOD5Perf` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uwwCODPerf` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uwwTSSPerf` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uwwNtotPerf` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uwwPTotPerf` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uwwOtherPerf` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uwwEndLife` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uwwTypeofTreatment` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`uwwCode`),
  INDEX (`aggCode`),
  FOREIGN KEY (`aggCode`) REFERENCES t_agglomerations(`aggCode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Estructura de tabla para la tabla `t_uwwtps_emission_load`
DROP TABLE IF EXISTS `t_uwwtps_emission_load`;
CREATE TABLE IF NOT EXISTS `t_uwwtps_emission_load` (
  `emissionID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uwwCode` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `uwwName` longtext COLLATE utf8_unicode_ci,
  `uwwBODIncoming` int(15) DEFAULT NULL,
  `uwwCODIncoming` int(15) DEFAULT NULL,
  `uwwNIncoming` int(15) DEFAULT NULL,
  `uwwPIncoming` int(15) DEFAULT NULL,
  `uwwBODDischarge` int(15) DEFAULT NULL,
  `uwwCODDischarge` int(15) DEFAULT NULL,
  `uwwNDischarge` int(15) DEFAULT NULL,
  `uwwPDischarge` int(15) DEFAULT NULL,
  PRIMARY KEY (`emissionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
COMMIT;
