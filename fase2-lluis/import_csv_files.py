# -*- coding: utf-8 -*-
'''
  siusplau fes servir python >= 3.7

  Aquest script processa 5 fitxers csv obtinguts de
  https://www.eea.europa.eu/data-and-maps/data/waterbase-uwwtd-urban-waste-water-treatment-directive-5

  'dbo.VL_Agglomerations.csv'      : 'Agglomerations',
  'dbo.VLS_DischargePoints.csv'    : 'DischargePoints',
  'dbo.VL_UWWTPAgglos.csv'         : 'UWWTPAgglos',
  'dbo.V_UWWTPS_emission_load.csv' : 'UWWTPS_emission_load',
  'dbo.VL_UWWTPS.csv'              : 'UWWTPS',

  fa una serie d'operacions definides a "operacions.md" i guarda la base de
  dades resultant en un fitxer ".sqlite" per poder-lo manipular posteriorment
  amb sqlite3(1)
'''
import csv
import os
import sqlite3

#new sqlite file, new cursor to execute queries
db=sqlite3.connect("waterbase.sqlite");
c=db.cursor();

#create 5 new empty tables in sql
def create_table_structure():
  #Agglomerations
  '''
    Table contains information on agglomerations with generated load ≥ 2000 P.E.,
    including names, coordinates, generated load and information whether the load
    generated is collected through collecting system or addressed via Individual
    Appropriate Systems (IAS) or not collected not addressed via IAS. Field repID
    is related to the table T_ReportPeriod. Field aggNUTS is related to the field
    nutID in the codelist table T_NUTS. Field bigID is related to the codelist
    table T_BigCity. Fields aggState, aggChanges, aggMethodC1, aggMethodC2 and
    aggMethodWithoutTreatment are related to the field lovID in the codelist
    table T_LOV.
  '''
  c.execute('DROP TABLE IF EXISTS Agglomerations')
  c.execute('''CREATE TABLE Agglomerations (
    aggAgglomorationsID       Long integer,
    aggCode                   Text(32),
    aggName                   Text(255),
    aggBeginLife              Date,
    aggCalculation            Memo,
    aggChanges                Long integer,
    aggChangesComment         Memo,
    aggEndLife                Date,
    aggGenerated              Long integer,
    aggLatitude               Decimal,
    aggLongitude              Decimal,
    aggC1                     Decimal,
    aggMethodC1               Integer,
    aggC2                     Decimal,
    aggMethodC2               Integer,
    aggNUTS                   Integer,
    aggMethodWithoutTreatment Integer,
    aggPercWithoutTreatment   Decimal,
    aggState                  Integer,
    bigCityID                 Long integer,
    rptMStateKey              Text(2),
    ReportNetEnvelopeFileId   Long integer
  )''');

  #---------------------------------------------------------

  #DischargePoints
  '''
    Table contains information on individual points of discharge from treatment
    plants or collecting systems, localisation of discharge, link to specific
    treatment plant, type of receiving area into which the effluent/wastewater is
    discharged, related waterbody (or river basin), information on the discharge
    on land. Field repID is related to the table T_ReportPeriod. Field uwwID is
    related to the table T_UWWTPs, to ensure the link between Discharge point and
    UWWTP. Field rcaID is related to the table T_ReceivingArea, to ensure the
    link between Discharge point and Receiving area, into which the treated waste
    water is discharged. Field dcpNUTS is related to the field nutID in the
    codelist table T_NUTS. Fields dcpState, dcpWaterBodyType,
    dcpTypeOfReceivingArea, dcpSurfaceWaters, dcpNotAffect, dcpMSProvide and
    dcpCOMAccept are related to the field lovID in the codelist table T_LOV.
  '''
  c.execute('DROP TABLE IF EXISTS DischargePoints')
  c.execute('''CREATE TABLE DischargePoints (
    dcpDischargePointsID            Long integer,
    dcpState                        Integer,
    rptMStateKey                    Text(2),
    uwwCode                         Long integer,
    dcpCode                         Text(32),
    dcpName                         Text(255),
    dcpNuts                         Long integer,
    dcpLatitude                     Decimal,
    dcpLongitude                    Decimal,
    dcpWaterBodyType                Integer,
    dcpIrrigation                   Integer,
    dcpTypeOfReceivingArea          Integer,
    rcaCode                         integer,
    dcpSurfaceWaters                Integer,
    dcpWaterbodyID                  Text(64),
    dcpNotAffect                    Integer,
    dcpMSProvide                    Integer,
    dcpCOMAccept                    Integer,
    dcpGroundWater                  Text(64),
    dcpReceivingWater               Text(64),
    dcpWFDSubUnit                   Text(64),
    dcpWFDRBD                       Text(64),
    dcpRemarks                      Memo,
    dcpWFDRBDReferenceDate          Date,
    dcpWaterBodyReferenceDate       Date,
    dcpGroundWaterReferenceDate     Date,
    dcpReceivingWaterReferenceDate  Date,
    dcpWFDSubUnitReferenceDate      Date,
    ReportNetEnvelopeFileId         Long integer,
    dcpBeginLife                    Date,
    dcpEndLife                      Date
  )''');

  #UWWTPAgglos
  '''
    Table is a connection table combining data on agglomeration and urban waste
    water treatment plants allowing repoting of situations where the ratio
    agglomeration:UWWTP is 1:n or m:1. Field repID is related to the table
    T_ReportPeriod. Field uwwID is related to the table T_UWWTPs. Fields
    aucUwwCode and aucUwwName are related to the fields uwwCode and uwwName in
    the table T_UWWTPs. Field aggID is related to the table T_Agglomerations.
    Fields aucAggCode and aucAggName are related to the fields aggCode and
    aggName in the table T_Agglomerations. Field aucMethodPercEnteringUWWTP is
    related to the field lovID in the codelist table T_LOV.
  '''
  c.execute('DROP TABLE IF EXISTS UWWTPAgglos')
  c.execute('''CREATE TABLE UWWTPAgglos (
    aucUWWTP_AggloID            Long integer,
    rptMStateKey                Text(2),
    aucUwwCode                  Text(32),
    aucUwwName                  Text(255),
    aggID                       Long integer,
    aucAggCode                  Text(32),
    aucAggName                  Text(255),
    aucPercEnteringUWWTP        Decimal,
    aucMethodPercEnteringUWWTP  Integer,
    aucPercC2T                  Decimal,
    ReportNetEnvelopeFileId     Long integer
  )''');

  #UWWTPS
  '''
    Table includes data on individual waste water treatment plants and
    collecting systems without UWWTP, their localisation, capacity and actual
    load treated, type of treatment, aggregated data on the performance of
    plants. Field repID is related to the table T_ReportPeriod. Field aggID is
    related to the table T_Agglomerations. Field uwwNUTS is related to the field
    nutID in the codelist table T_NUTS. Fields uwwState, uwwCollectingSystem,
    uwwBOD5Perf, uwwCODPerf, uwwTSSPerf, uwwNTotPerf, uwwPTotPerf and
    uwwOtherPerf are related to the field lovID in the codelist table T_LOV.

    uwwState, rptMStateKey, aggCode, uwwCode, uwwName, uwwCollectingSystem,
    uwwDateClosing, uwwHistorie, uwwLatitude, uwwLongitude, uwwNUTS,
    uwwLoadEnteringUWWTP, uwwCapacity, uwwPrimaryTreatment,
    uwwSecondaryTreatment, uwwOtherTreatment, uwwNRemoval, uwwPRemoval, uwwUV,
    uwwChlorination, uwwOzonation, uwwSandFiltration, uwwMicroFiltration,
    uwwOther, uwwSpecification, uwwBOD5Perf, uwwCODPerf, uwwTSSPerf, uwwNTotPerf,
    uwwPTotPerf, uwwOtherPerf, uwwBeginLife, uwwEndLife, ReportNetEnvelopeFileId
  '''
  c.execute('DROP TABLE IF EXISTS UWWTPS')
  c.execute('''CREATE TABLE UWWTPS (
    uwwState                Integer,
    rptMStateKey            Text(2),
    aggCode                 Long integer,
    uwwCode                 Text(32),
    uwwName                 Text(255),
    uwwCollectingSystem     Integer,
    uwwDateClosing          Integer,
    uwwHistorie             Memo,
    uwwLatitude             Decimal,
    uwwLongitude            Decimal,
    uwwNUTS                 Integer,
    uwwLoadEnteringUWWTP    Long integer,
    uwwCapacity             Long integer,
    uwwPrimaryTreatment     Boolean,
    uwwSecondaryTreatment   Boolean,
    uwwOtherTreatment       Boolean,
    uwwNRemoval             Boolean,
    uwwPRemoval             Boolean,
    uwwUV                   Boolean,
    uwwChlorination         Boolean,
    uwwOzonation            Boolean,
    uwwSandFiltration       Boolean,
    uwwMicroFiltration      Boolean,
    uwwOther                Boolean,
    uwwSpecification        Text(255),
    uwwBOD5Perf             Integer,
    uwwCODPerf              Integer,
    uwwTSSPerf              Integer,
    uwwNTotPerf             Integer,
    uwwPTotPerf             Integer,
    uwwOtherPerf            Integer,
    uwwBeginLife            Date,
    uwwEndLife              Date,
    ReportNetEnvelopeFileId Long integer)'''
  );

  #UWWTPS_emission_load
  '''
    Table contains additional data on incoming and discharged loads of organic
    matter and nutrients, provided by some Member States beyond the scope of
    UWWTD compliance. The data can be linked with the T_UWWTPs for further
    characteristics for the specific plants. A QA flag for some records relates
    to certain criteria and should be considered by the user for specific
    purposes. Field uwwState is related to the field lovID in the codelist table
    T_LOV. Fields uwwID, uwwCode and uwwName are related to the table T_UWWTPs.

    uwwState, rptMStateKey, uwwCode, uwwName, uwwBODIncoming, uwwCODIncoming,
    uwwNIncoming, uwwPIncoming, uwwBODDischarge, uwwCODDischarge, uwwNDischarge,
    uwwPDischarge, QAflag, Remark
  '''
  c.execute('DROP TABLE IF EXISTS UWWTPS_emission_load')
  c.execute('''CREATE TABLE UWWTPS_emission_load (
    uwwState        Integer,
    rptMStateKey    Text(2),
    uwwCode         Text(32),
    uwwName         Text(255),
    uwwBODIncoming  Decimal,
    uwwCODIncoming  Decimal,
    uwwNIncoming    Decimal,
    uwwPIncoming    Decimal,
    uwwBODDischarge Decimal,
    uwwCODDischarge Decimal,
    uwwNDischarge   Decimal,
    uwwPDischarge   Decimal,
    QAflag          Text(1),
    Remark          Text(255))'''
  );

create_table_structure();

#fill sql tables with csv info
os.chdir('waterbase_UWWTD_v6_csv') #go to folder
csv_filenames={
  'dbo.VL_Agglomerations.csv'      : 'Agglomerations',
  'dbo.VLS_DischargePoints.csv'    : 'DischargePoints',
  'dbo.VL_UWWTPAgglos.csv'         : 'UWWTPAgglos',
  'dbo.V_UWWTPS_emission_load.csv' : 'UWWTPS_emission_load',
  'dbo.VL_UWWTPS.csv'              : 'UWWTPS',
}

#iterate csv filenames
for csv_filename, sql_tablename in csv_filenames.items():
  with open(csv_filename,'r',encoding='utf-8') as csv_file:
    print("Importing %s... " % csv_filename, end='')
    reader     = csv.DictReader(csv_file)            #object, csv reader
    fieldnames = tuple(reader.fieldnames)            #tuple,  column names
    keys       = ",".join(fieldnames)                #string, column names
    quest_mks  = ','.join(list('?'*len(fieldnames))) #string, question marks

    #iterate csv rows
    for row in reader:
      values = tuple(row.values())
      c.execute('INSERT INTO '+sql_tablename+' ('+keys+') VALUES ('+quest_mks+')', values)

    #show inserted rows
    c.execute("SELECT COUNT(*) FROM "+sql_tablename)
    print("Done (%d rows)" % (c.fetchone()[0]) )

    #save changes
    db.commit()
