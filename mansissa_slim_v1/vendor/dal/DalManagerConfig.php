<?php

/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace DAL;

/**
 * class called for DAL manager config 
 * DAL manager uses Zend Service manager and 
 * config class is compliant zend service config structure
 * @author Okan CIRAN
 */
class DalManagerConfig {

    /**
     * constructor
     */
    public function __construct() {
        
    }

    /**
     * config array for zend service manager config
     * @var array
     */
    protected $config = array(
        // Initial configuration with which to seed the ServiceManager.
        // Should be compatible with Zend\ServiceManager\Config.
        'service_manager' => array(
            'invokables' => array(
            //'test' => 'Utill\BLL\Test\Test'
            ),
            'factories' => [
                // oracle   //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 
                  'infoAfterSalesOraclePDO' => 'DAL\Factory\PDO\Oracle\InfoAfterSalesFactory',
                  'infoSalesOraclePDO' => 'DAL\Factory\PDO\Oracle\InfoSalesFactory',
                  'infoDealerOwnerOraclePDO' => 'DAL\Factory\PDO\Oracle\InfoDealerOwnerFactory',
                 
                   'sysAccBodyDeffOraPDO' => 'DAL\Factory\PDO\Oracle\SysAccBodyDeffFactory',
                   'sysAccBodyMatrixOraPDO' => 'DAL\Factory\PDO\Oracle\SysAccBodyMatrixFactory',
                   'sysAccBodySuppOraPDO' => 'DAL\Factory\PDO\Oracle\SysAccBodySuppFactory',
                   'sysAccBodyTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysAccBodyTypesFactory',
                   'sysAccDeffOraPDO' => 'DAL\Factory\PDO\Oracle\SysAccDeffFactory',
                   'sysAccSupplierMatrixOraPDO' => 'DAL\Factory\PDO\Oracle\SysAccSupplierMatrixFactory',
                   'sysAccessoriesMatrixOraPDO' => 'DAL\Factory\PDO\Oracle\SysAccessoriesMatrixFactory',
                   'sysAccessoryOptionsOraPDO' => 'DAL\Factory\PDO\Oracle\SysAccessoryOptionsFactory',
                   'sysApprovalMechanismOraPDO' => 'DAL\Factory\PDO\Oracle\SysApprovalMechanismFactory',
                   'sysBbContractTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysBbContractTypesFactory',
                   'sysBranchesDealersDeffOraPDO' => 'DAL\Factory\PDO\Oracle\SysBranchesDealersDeffFactory',
                   'sysBuybackMatrixOraPDO' => 'DAL\Factory\PDO\Oracle\SysBuybackMatrixFactory',
                   'sysBuybackQuotasOraPDO' => 'DAL\Factory\PDO\Oracle\SysBuybackQuotasFactory',
                   'sysBuybackTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysBuybackTypesFactory',
                   'sysCalendarTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCalendarTypesFactory', 
                   'sysCampaignVehiclesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCampaignVehiclesFactory',
                   'sysCampaignsOraPDO' => 'DAL\Factory\PDO\Oracle\SysCampaignsFactory',
                   'sysCauseOfLosingwinningOraPDO' => 'DAL\Factory\PDO\Oracle\SysCauseOfLosingwinningFactory',
                   'sysChannelTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysChannelTypesFactory',
                   'sysCommissionDefinitionsOraPDO' => 'DAL\Factory\PDO\Oracle\SysCommissionDefinitionsFactory',
                   'sysCommissionExtrasOraPDO' => 'DAL\Factory\PDO\Oracle\SysCommissionExtrasFactory',
                   'sysCommissionPricerangeDefsOraPDO' => 'DAL\Factory\PDO\Oracle\SysCommissionPricerangeDefsFactory',
                   'sysContractTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysContractTypesFactory',
                   'sysCountryRegionsOraPDO' => 'DAL\Factory\PDO\Oracle\SysCountryRegionsFactory',
                   'sysCsActStatutypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCsActStatutypesFactory',
                   'sysCsActivationTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCsActivationTypesFactory',
                   'sysCsStatuTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCsStatuTypesFactory',
                   'sysCurrenciesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCurrenciesFactory',
                   'sysCurrencyFixOraPDO' => 'DAL\Factory\PDO\Oracle\SysCurrencyFixFactory',
                   'sysCurrencyTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCurrencyTypesFactory',
                   'sysCustomerApplicationTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCustomerApplicationTypesFactory',
                   'sysCustomerCategoriesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCustomerCategoriesFactory',
                   'sysCustomerReliabilityOraPDO' => 'DAL\Factory\PDO\Oracle\SysCustomerReliabilityFactory',
                   'sysCustomerSectorTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCustomerSectorTypesFactory',
                   'sysCustomerSegmentTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCustomerSegmentTypesFactory',
                   'sysCustomerTurnoverRatesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCustomerTurnoverRatesFactory',
              'sysCustomerTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysCustomerTypesFactory',
                   'sysDemoAllocationTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysDemoAllocationTypesFactory',
                   'sysDemoQuotasOraPDO' => 'DAL\Factory\PDO\Oracle\SysDemoQuotasFactory',
                   'sysDepartmentsOraPDO' => 'DAL\Factory\PDO\Oracle\SysDepartmentsFactory',
                   'sysDiscountRatesDeffOraPDO' => 'DAL\Factory\PDO\Oracle\SysDiscountRatesDeffFactory',
                   'sysEducationDefinitionsOraPDO' => 'DAL\Factory\PDO\Oracle\SysEducationDefinitionsFactory',
                   'sysEducationsSalesmanOraPDO' => 'DAL\Factory\PDO\Oracle\SysEducationsSalesmanFactory',
                   'sysEmbraceBranchNoCodeOraPDO' => 'DAL\Factory\PDO\Oracle\SysEmbraceBranchNoCodeFactory',
                   'sysEmbraceBranchDealershipOraPDO' => 'DAL\Factory\PDO\Oracle\SysEmbraceBranchDealershipFactory',               
                   'sysFinanceTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysFinanceTypesFactory',
                   'sysFixedSalesCostsOraPDO' => 'DAL\Factory\PDO\Oracle\SysFixedSalesCostsFactory',
                   'sysKpnumbersOraPDO' => 'DAL\Factory\PDO\Oracle\SysKpnumbersFactory',
                   'sysMileagesOraPDO' => 'DAL\Factory\PDO\Oracle\SysMileagesFactory',
                   'sysMonthsOraPDO' => 'DAL\Factory\PDO\Oracle\SysMonthsFactory',
                   'sysNumericalRangesOraPDO' => 'DAL\Factory\PDO\Oracle\SysNumericalRangesFactory',
                   'sysOmtOraPDO' => 'DAL\Factory\PDO\Oracle\SysOmtFactory',
                   'sysPriorityTypeOraPDO' => 'DAL\Factory\PDO\Oracle\SysPriorityTypeFactory',
                   'sysProbabilitiesOraPDO' => 'DAL\Factory\PDO\Oracle\SysProbabilitiesFactory',
                   'sysRmDeffOraPDO' => 'DAL\Factory\PDO\Oracle\SysRmDeffFactory',
                   'sysRmMatrixOraPDO' => 'DAL\Factory\PDO\Oracle\SysRmMatrixFactory',
                   'sysRmSubsidyMatrixOraPDO' => 'DAL\Factory\PDO\Oracle\SysRmSubsidyMatrixFactory',
                   'sysRmTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysRmTypesFactory',
                   'sysRoadtypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysRoadtypesFactory',
                   'sysSalesLimitsDeffOraPDO' => 'DAL\Factory\PDO\Oracle\SysSalesLimitsDeffFactory',
                   'sysSalesLimitsMatrixOraPDO' => 'DAL\Factory\PDO\Oracle\SysSalesLimitsMatrixFactory',
                   'sysSalesLimitsRolesOraPDO' => 'DAL\Factory\PDO\Oracle\SysSalesLimitsRolesFactory',
                   'sysSalesProvisionTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysSalesProvisionTypesFactory',
                   'sysSalesProvisionsOraPDO' => 'DAL\Factory\PDO\Oracle\SysSalesProvisionsFactory',
                   'sysServicesGroupsOraPDO' => 'DAL\Factory\PDO\Oracle\SysServicesGroupsFactory',
                   'sysSisHierarchyOraPDO' => 'DAL\Factory\PDO\Oracle\SysSisHierarchyFactory',
                   'sysSisQuotasOraPDO' => 'DAL\Factory\PDO\Oracle\SysSisQuotasFactory',
                   'sysSisQuotasMatrixOraPDO' => 'DAL\Factory\PDO\Oracle\SysSisQuotasMatrixFactory',
                   'sysSisStatusOraPDO' => 'DAL\Factory\PDO\Oracle\SysSisStatusFactory',
                   'sysStrategicImportancesOraPDO' => 'DAL\Factory\PDO\Oracle\SysStrategicImportancesFactory',
                   'sysSupplierOraPDO' => 'DAL\Factory\PDO\Oracle\SysSupplierFactory',
                   'sysTerrainsOraPDO' => 'DAL\Factory\PDO\Oracle\SysTerrainsFactory',
                   'sysTitlesOraPDO' => 'DAL\Factory\PDO\Oracle\SysTitlesFactory', 
                   'sysTopusedProvisionsOraPDO' => 'DAL\Factory\PDO\Oracle\SysTopusedProvisionsFactory',
                   'sysTopusedIntakesOraPDO' => 'DAL\Factory\PDO\Oracle\SysTopusedIntakesFactory',  
                   'sysTopusedBranchdealersOraPDO' => 'DAL\Factory\PDO\Oracle\SysTopusedBranchdealersFactory',  
                   'sysVatPolicyTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysVatPolicyTypesFactory',
                   'sysVehicleAppTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehicleAppTypesFactory',
                   'sysVehicleAuditSheetDefOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehicleAuditSheetDefFactory',
                   'sysVehicleBrandOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehicleBrandFactory',
                   'sysVehicleCapTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehicleCapTypesFactory',
                   'sysVehicleCkdcbuOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehicleCkdcbuFactory',
                   'sysVehicleConfigTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehicleConfigTypesFactory',
                   'sysVehicleGroupTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehicleGroupTypesFactory',
                   'sysVehicleGroupsOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehicleGroupsFactory',
                   'sysVehicleGtModelsOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehicleGtModelsFactory',
                   'sysVehicleModelVariantsOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehicleModelVariantsFactory',
                   'sysVehiclesOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehiclesFactory',
                   'sysVehicleBtobtsOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehicleBtobtsFactory',                 
                   'sysWarrantiesOraPDO' => 'DAL\Factory\PDO\Oracle\SysWarrantiesFactory', 
                   'sysWarrantyMatrixOraPDO' => 'DAL\Factory\PDO\Oracle\SysWarrantyMatrixFactory',
                   'sysWarrantyTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysWarrantyTypesFactory',                 
                   'sysVehiclesEndgroupsOraPDO' => 'DAL\Factory\PDO\Oracle\SysVehiclesEndgroupsFactory',
                
                   
                
                
                //////////////////////////// <><><><><><><>
                
                 
                
                'reportConfigurationOraPDO' => 'DAL\Factory\PDO\Oracle\ReportConfigurationFactory',
                'cmpnyEqpmntOraPDO' => 'DAL\Factory\PDO\Oracle\CmpnyEqpmntFactory',
                'sysNavigationLeftOraPDO' => 'DAL\Factory\PDO\Oracle\SysNavigationLeftFactory',                
                'infoUsersOraPDO' => 'DAL\Factory\PDO\Oracle\InfoUsersFactory',
                'sysCountrysOraPDO' => 'DAL\Factory\PDO\Oracle\SysCountrysFactory',
                'sysCityPOraDO' => 'DAL\Factory\PDO\Oracle\SysCityFactory',
                'sysLanguageOraPDO' => 'DAL\Factory\PDO\Oracle\SysLanguageFactory',
                'sysBoroughOraPDO' => 'DAL\Factory\PDO\Oracle\SysBoroughFactory',
                'sysVillageOraPDO' => 'DAL\Factory\PDO\Oracle\SysVillageFactory',      
                'blLoginLogoutOraPDO' => 'DAL\Factory\PDO\Oracle\BlLoginLogoutFactory',                   
                'sysAclRolesOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclRolesFactory',   
                'sysAclResourcesOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclResourcesFactory',   
                'sysAclPrivilegeOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclPrivilegeFactory',   
                'sysAclRrpMapOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclRrpMapFactory',  
                'sysSpecificDefinitionsOraPDO' => 'DAL\Factory\PDO\Oracle\SysSpecificDefinitionsFactory', 
                'infoUsersCommunicationsOraPDO' => 'DAL\Factory\PDO\Oracle\InfoUsersCommunicationsFactory', 
                'infoUsersAddressesOraPDO' => 'DAL\Factory\PDO\Oracle\InfoUsersAddressesFactory', 
                'blActivationReportOraPDO' => 'DAL\Factory\PDO\Oracle\BlActivationReportFactory',                 
                'sysOperationTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysOperationTypesFactory',
                'sysOperationTypesToolsOraPDO' => 'DAL\Factory\PDO\Oracle\SysOperationTypesToolsFactory', 
                'infoErrorOraPDO' => 'DAL\Factory\PDO\Oracle\InfoErrorFactory',                 
                'sysUnitsOraPDO' => 'DAL\Factory\PDO\Oracle\SysUnitsFactory',                                
                'hstryLoginOraPDO' => 'DAL\Factory\PDO\Oracle\HstryLoginFactory',
                'blAdminActivationReportOraPDO' => 'DAL\Factory\PDO\Oracle\BlAdminActivationReportFactory',                
                'sysCertificationsOraPDO' => 'DAL\Factory\PDO\Oracle\SysCertificationsFactory', 
                'sysUnitSystemsOraPDO' => 'DAL\Factory\PDO\Oracle\SysUnitSystemsFactory',                                                
                'infoUsersSocialmediaOraPDO' => 'DAL\Factory\PDO\Oracle\InfoUsersSocialmediaFactory',                
                'sysSocialMediaOraPDO' => 'DAL\Factory\PDO\Oracle\SysSocialMediaFactory',                
                'sysMailServerOraPDO' => 'DAL\Factory\PDO\Oracle\SysMailServerFactory',                                
                'infoUsersVerbalOraPDO' => 'DAL\Factory\PDO\Oracle\InfoUsersVerbalFactory',
                'infoUsersProductsServicesOraPDO' => 'DAL\Factory\PDO\Oracle\InfoUsersProductsServicesFactory',                
                'sysMembershipTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysMembershipTypesFactory',
                'sysAclRrpOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclRrpFactory',
                'sysUniversitiesOraPDO' => 'DAL\Factory\PDO\Oracle\SysUniversitiesFactory',                
                'sysMenuTypesOraPDO' => 'DAL\Factory\PDO\Oracle\SysMenuTypesFactory',
                'sysAclModulesOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclModulesFactory',
                'sysAclActionsOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclActionsFactory',
                'sysAclMenuTypesActionsOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclMenuTypesActionsFactory',
                'sysAclRrpRestservicesOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclRrpRestservicesFactory',
                'sysServicesGroupsOraPDO' => 'DAL\Factory\PDO\Oracle\SysServicesGroupsFactory',
                'sysAclRestservicesOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclRestservicesFactory',
                'sysAssignDefinitionOraPDO' => 'DAL\Factory\PDO\Oracle\SysAssignDefinitionFactory',   
                'sysAssignDefinitionRolesOraPDO' => 'DAL\Factory\PDO\Oracle\SysAssignDefinitionRolesFactory',   
                'sysAclActionRrpOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclActionRrpFactory',   
                'sysAclActionRrpRestservicesOraPDO' => 'DAL\Factory\PDO\Oracle\SysAclActionRrpRestservicesFactory',                                   
                'infoUsersSendingMailOraPDO' => 'DAL\Factory\PDO\Oracle\InfoUsersSendingMailFactory',
                                 
                'logConnectionOraPDO' => 'DAL\Factory\PDO\Oracle\LogConnectionFactory',
                'logServicesOraPDO' => 'DAL\Factory\PDO\Oracle\LogServicesFactory',                
                'logAdminOraPDO' => 'DAL\Factory\PDO\Oracle\LogAdminFactory',
                
                'sysOperationTypesRrpOraPDO' => 'DAL\Factory\PDO\Oracle\SysOperationTypesRrpFactory',   
                'pgClassOraPDO' => 'DAL\Factory\PDO\Oracle\PgClassFactory',
                'actProcessConfirmOraPDO' => 'DAL\Factory\PDO\Oracle\ActProcessConfirmFactory',
                
                'actUsersActionStatisticsOraPDO' => 'DAL\Factory\PDO\Oracle\ActUsersActionStatisticsFactory',
                'sysNotificationRestservicesOraPDO' => 'DAL\Factory\PDO\Oracle\SysNotificationRestservicesFactory',
               
                
              // oracle  //******************************************************************************************
                
                
                
                
                
                
              // postgresql //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 
                
                
                
                'infoCustomerPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoCustomerFactory',
                'infoProjectPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoProjectFactory',
                'infoProjectVehicleModelsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoProjectVehicleModelsFactory',
                
                
                
                'infoAfterSalesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoAfterSalesFactory',
                'infoSalesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoSalesFactory',
                'infoDealerOwnerPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoDealerOwnerFactory',
                 ///////////////////////////////////////////////////////////////////////////////
                'sysAccBodyDeffPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAccBodyDeffFactory',
                'sysAccBodyMatrixPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAccBodyMatrixFactory',
                'sysAccBodySuppPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAccBodySuppFactory',
                'sysAccBodyTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAccBodyTypesFactory',
                'sysAccDeffPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAccDeffFactory',
                'sysAccSupplierMatrixPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAccSupplierMatrixFactory',
                'sysAccessoriesMatrixPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAccessoriesMatrixFactory',
                'sysAccessoryOptionsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAccessoryOptionsFactory',
                'sysApprovalMechanismPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysApprovalMechanismFactory',
                'sysBbContractTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysBbContractTypesFactory',
                'sysBranchesDealersDeffPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysBranchesDealersDeffFactory',
                'sysBuybackMatrixPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysBuybackMatrixFactory',
                'sysBuybackQuotasPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysBuybackQuotasFactory',
                'sysBuybackTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysBuybackTypesFactory',
                'sysCalendarTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCalendarTypesFactory', 
                'sysCampaignVehiclesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCampaignVehiclesFactory',
                'sysCampaignsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCampaignsFactory',
                'sysCauseOfLosingwinningPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCauseOfLosingwinningFactory',
                'sysChannelTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysChannelTypesFactory',
                'sysCommissionDefinitionsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCommissionDefinitionsFactory',
                'sysCommissionExtrasPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCommissionExtrasFactory',
                'sysCommissionPricerangeDefsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCommissionPricerangeDefsFactory',
                'sysContractTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysContractTypesFactory',
                'sysCountryRegionsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCountryRegionsFactory',
                'sysCsActStatutypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCsActStatutypesFactory',
                'sysCsActivationTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCsActivationTypesFactory',
                'sysCsStatuTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCsStatuTypesFactory',
                'sysCurrenciesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCurrenciesFactory',
                'sysCurrencyFixPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCurrencyFixFactory',
                'sysCurrencyTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCurrencyTypesFactory',
                'sysCustomerApplicationTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCustomerApplicationTypesFactory',
                'sysCustomerCategoriesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCustomerCategoriesFactory',
                'sysCustomerReliabilityPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCustomerReliabilityFactory',
                'sysCustomerSectorTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCustomerSectorTypesFactory',
                'sysCustomerSegmentTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCustomerSegmentTypesFactory',
                'sysCustomerTurnoverRatesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCustomerTurnoverRatesFactory',
                'sysCustomerTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCustomerTypesFactory',
                'sysDemoAllocationTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysDemoAllocationTypesFactory',
                'sysDemoQuotasPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysDemoQuotasFactory',
                'sysDepartmentsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysDepartmentsFactory',
                'sysDiscountRatesDeffPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysDiscountRatesDeffFactory',
                'sysEducationDefinitionsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysEducationDefinitionsFactory',
                'sysEducationsSalesmanPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysEducationsSalesmanFactory',
                'sysEmbraceBranchNoCodePostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysEmbraceBranchNoCodeFactory',
                'sysEmbraceBranchDealershipPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysEmbraceBranchDealershipFactory',               
                'sysFinanceTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysFinanceTypesFactory',
                'sysFixedSalesCostsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysFixedSalesCostsFactory',
                'sysHorsepowerPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysHorsepowerFactory',                
                'sysKpnumbersPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysKpnumbersFactory',
                'sysMileagesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysMileagesFactory',
                'sysMonthsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysMonthsFactory',
                'sysNumericalRangesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysNumericalRangesFactory',
                'sysOmtPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysOmtFactory',
                'sysPriorityTypePostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysPriorityTypeFactory',
                'sysProbabilitiesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysProbabilitiesFactory',
                'sysRmDeffPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysRmDeffFactory',
                'sysRmMatrixPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysRmMatrixFactory',
                'sysRmSubsidyMatrixPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysRmSubsidyMatrixFactory',
                'sysRmTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysRmTypesFactory',
                'sysRoadtypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysRoadtypesFactory',
                'sysSalesLimitsDeffPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSalesLimitsDeffFactory',
                'sysSalesLimitsMatrixPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSalesLimitsMatrixFactory',
                'sysSalesLimitsRolesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSalesLimitsRolesFactory',
                'sysSalesProvisionTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSalesProvisionTypesFactory',
                'sysSalesProvisionsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSalesProvisionsFactory',
                'sysServicesGroupsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysServicesGroupsFactory',
                'sysSisHierarchyPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSisHierarchyFactory',
                'sysSisQuotasPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSisQuotasFactory',
                'sysSisQuotasMatrixPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSisQuotasMatrixFactory',
                'sysSisStatusPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSisStatusFactory',
                'sysStrategicImportancesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysStrategicImportancesFactory',
                'sysSupplierPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSupplierFactory',
                'sysTerrainsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysTerrainsFactory',
                'sysTonnagePostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysTonnageFactory', 
                'sysTitlesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysTitlesFactory', 
                'sysTopusedProvisionsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysTopusedProvisionsFactory',
                'sysTopusedIntakesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysTopusedIntakesFactory',  
                'sysTopusedBranchdealersPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysTopusedBranchdealersFactory',  
                'sysVatPolicyTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVatPolicyTypesFactory',
                'sysVehicleAppTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehicleAppTypesFactory',
                'sysVehicleAuditSheetDefPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehicleAuditSheetDefFactory',
                'sysVehicleBrandPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehicleBrandFactory',
                'sysVehicleCapTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehicleCapTypesFactory',
                'sysVehicleCkdcbuPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehicleCkdcbuFactory',
                'sysVehicleConfigTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehicleConfigTypesFactory',
                'sysVehicleGroupTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehicleGroupTypesFactory',
                'sysVehicleGroupsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehicleGroupsFactory',
                'sysVehicleGtModelsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehicleGtModelsFactory',
                'sysVehicleModelVariantsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehicleModelVariantsFactory',
                'sysVehiclesTradePostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehiclesTradeFactory',  
                'sysVehiclesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehiclesFactory',
                'sysVehicleBtobtsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehicleBtobtsFactory',                 
                'sysWarrantiesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysWarrantiesFactory', 
                'sysWarrantyMatrixPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysWarrantyMatrixFactory',
                'sysWarrantyTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysWarrantyTypesFactory',                 
                'sysVehiclesEndgroupsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVehiclesEndgroupsFactory',
                
                 //////////////////////////// <><><><><><><>
                'reportConfigurationPostgrePDO' => 'DAL\Factory\PDO\Postgresql\ReportConfigurationFactory',
                'cmpnyEqpmntPostgrePDO' => 'DAL\Factory\PDO\Postgresql\CmpnyEqpmntFactory',
                'sysNavigationLeftPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysNavigationLeftFactory',                
                'infoUsersPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersFactory',
                'sysCountrysPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCountrysFactory',
                'sysCityPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCityFactory',
                'sysLanguagePostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysLanguageFactory',
                'sysBoroughPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysBoroughFactory',
                'sysVillagePostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysVillageFactory',      
                'blLoginLogoutPostgrePDO' => 'DAL\Factory\PDO\Postgresql\BlLoginLogoutFactory',                   
                'sysAclRolesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclRolesFactory',   
                'sysAclResourcesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclResourcesFactory',   
                'sysAclPrivilegePostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclPrivilegeFactory',   
                'sysAclRrpMapPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclRrpMapFactory',  
                'sysSpecificDefinitionsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSpecificDefinitionsFactory', 
                'infoUsersCommunicationsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersCommunicationsFactory', 
                'infoUsersAddressesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersAddressesFactory', 
                'blActivationReportPostgrePDO' => 'DAL\Factory\PDO\Postgresql\BlActivationReportFactory',                 
                'sysOperationTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysOperationTypesFactory',
                'sysOperationTypesToolsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysOperationTypesToolsFactory', 
                'infoErrorPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoErrorFactory',                 
                'sysUnitsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysUnitsFactory',                                
                'hstryLoginPostgrePDO' => 'DAL\Factory\PDO\Postgresql\HstryLoginFactory',
                'blAdminActivationReportPostgrePDO' => 'DAL\Factory\PDO\Postgresql\BlAdminActivationReportFactory',                
                'sysCertificationsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysCertificationsFactory', 
                'sysUnitSystemsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysUnitSystemsFactory',                                                
                'infoUsersSocialmediaPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersSocialmediaFactory',                
                'sysSocialMediaPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSocialMediaFactory',                
                'sysMailServerPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysMailServerFactory',                                
                'infoUsersVerbalPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersVerbalFactory',
                'infoUsersProductsServicesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersProductsServicesFactory',                
                'sysMembershipTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysMembershipTypesFactory',
                'sysAclRrpPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclRrpFactory',
                'sysUniversitiesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysUniversitiesFactory',                
                'sysMenuTypesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysMenuTypesFactory',
                'sysAclModulesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclModulesFactory',
                'sysAclActionsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclActionsFactory',
                'sysAclMenuTypesActionsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclMenuTypesActionsFactory',
                'sysAclRrpRestservicesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclRrpRestservicesFactory',
                'sysServicesGroupsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysServicesGroupsFactory',
                'sysAclRestservicesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclRestservicesFactory',
                'sysAssignDefinitionPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAssignDefinitionFactory',   
                'sysAssignDefinitionRolesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAssignDefinitionRolesFactory',   
                'sysAclActionRrpPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclActionRrpFactory',   
                'sysAclActionRrpRestservicesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysAclActionRrpRestservicesFactory',                                   
                'infoUsersSendingMailPostgrePDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersSendingMailFactory',
                                 
                'logConnectionPostgrePDO' => 'DAL\Factory\PDO\Postgresql\LogConnectionFactory',
                'logServicesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\LogServicesFactory',                
                'logAdminPostgrePDO' => 'DAL\Factory\PDO\Postgresql\LogAdminFactory',
                
                'sysOperationTypesRrpPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysOperationTypesRrpFactory',   
                'pgClassPostgrePDO' => 'DAL\Factory\PDO\Postgresql\PgClassFactory',
                'actProcessConfirmPostgrePDO' => 'DAL\Factory\PDO\Postgresql\ActProcessConfirmFactory',
                
                'actUsersActionStatisticsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\ActUsersActionStatisticsFactory',
                'sysNotificationRestservicesPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysNotificationRestservicesFactory',
                'sysSectorsPostgrePDO' => 'DAL\Factory\PDO\Postgresql\SysSectorsFactory',
               
               
                // postgresql  //******************************************************************************************
                
                // sqlserver
                // sqlserver //******************************************************************************************
                
                
                
                
                
            ],
        ),
    );

    /**
     * return config array for zend service manager config
     * @return array | null
     * @author Okan CIRAN
     */
    public function getConfig() {
        return $this->config['service_manager'];
    }

}
