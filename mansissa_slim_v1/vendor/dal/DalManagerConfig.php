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
                // oracle 
                'infoAfterSalesOraclePDO' => 'DAL\Factory\PDO\Oracle\InfoAfterSalesFactory',
                'infoSalesOraclePDO' => 'DAL\Factory\PDO\Oracle\InfoSalesFactory',
                'infoDealerOwnerOraclePDO' => 'DAL\Factory\PDO\Oracle\InfoDealerOwnerFactory',
                
                ////////////////////////////
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
                
                   
                
                
                ////////////////////////////
                
                
                 
                
                
                
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
              
               
                
                
                // oracle 
                
                
              // postgresql 
                'reportConfigurationPDO' => 'DAL\Factory\PDO\Postgresql\ReportConfigurationFactory',
                'cmpnyEqpmntPDO' => 'DAL\Factory\PDO\Postgresql\CmpnyEqpmntFactory',
                'sysNavigationLeftPDO' => 'DAL\Factory\PDO\Postgresql\SysNavigationLeftFactory',                
                'infoUsersPDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersFactory',
                'sysCountrysPDO' => 'DAL\Factory\PDO\Postgresql\SysCountrysFactory',
                'sysCityPDO' => 'DAL\Factory\PDO\Postgresql\SysCityFactory',
                'sysLanguagePDO' => 'DAL\Factory\PDO\Postgresql\SysLanguageFactory',
                'sysBoroughPDO' => 'DAL\Factory\PDO\Postgresql\SysBoroughFactory',
                'sysVillagePDO' => 'DAL\Factory\PDO\Postgresql\SysVillageFactory',      
                'blLoginLogoutPDO' => 'DAL\Factory\PDO\Postgresql\BlLoginLogoutFactory',                   
                'sysAclRolesPDO' => 'DAL\Factory\PDO\Postgresql\SysAclRolesFactory',   
                'sysAclResourcesPDO' => 'DAL\Factory\PDO\Postgresql\SysAclResourcesFactory',   
                'sysAclPrivilegePDO' => 'DAL\Factory\PDO\Postgresql\SysAclPrivilegeFactory',   
                'sysAclRrpMapPDO' => 'DAL\Factory\PDO\Postgresql\SysAclRrpMapFactory',  
                'sysSpecificDefinitionsPDO' => 'DAL\Factory\PDO\Postgresql\SysSpecificDefinitionsFactory', 
                'infoUsersCommunicationsPDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersCommunicationsFactory', 
                'infoUsersAddressesPDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersAddressesFactory', 
                'blActivationReportPDO' => 'DAL\Factory\PDO\Postgresql\BlActivationReportFactory',                 
                'sysOperationTypesPDO' => 'DAL\Factory\PDO\Postgresql\SysOperationTypesFactory',
                'sysOperationTypesToolsPDO' => 'DAL\Factory\PDO\Postgresql\SysOperationTypesToolsFactory', 
                'infoErrorPDO' => 'DAL\Factory\PDO\Postgresql\InfoErrorFactory',                 
                'sysUnitsPDO' => 'DAL\Factory\PDO\Postgresql\SysUnitsFactory',                                
                'hstryLoginPDO' => 'DAL\Factory\PDO\Postgresql\HstryLoginFactory',
                'blAdminActivationReportPDO' => 'DAL\Factory\PDO\Postgresql\BlAdminActivationReportFactory',                
                'sysCertificationsPDO' => 'DAL\Factory\PDO\Postgresql\SysCertificationsFactory', 
                'sysUnitSystemsPDO' => 'DAL\Factory\PDO\Postgresql\SysUnitSystemsFactory',                                                
                'infoUsersSocialmediaPDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersSocialmediaFactory',                
                'sysSocialMediaPDO' => 'DAL\Factory\PDO\Postgresql\SysSocialMediaFactory',                
                'sysMailServerPDO' => 'DAL\Factory\PDO\Postgresql\SysMailServerFactory',                                
                'infoUsersVerbalPDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersVerbalFactory',
                'infoUsersProductsServicesPDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersProductsServicesFactory',                
                'sysMembershipTypesPDO' => 'DAL\Factory\PDO\Postgresql\SysMembershipTypesFactory',
                'sysAclRrpPDO' => 'DAL\Factory\PDO\Postgresql\SysAclRrpFactory',
                'sysUniversitiesPDO' => 'DAL\Factory\PDO\Postgresql\SysUniversitiesFactory',                
                'sysMenuTypesPDO' => 'DAL\Factory\PDO\Postgresql\SysMenuTypesFactory',
                'sysAclModulesPDO' => 'DAL\Factory\PDO\Postgresql\SysAclModulesFactory',
                'sysAclActionsPDO' => 'DAL\Factory\PDO\Postgresql\SysAclActionsFactory',
                'sysAclMenuTypesActionsPDO' => 'DAL\Factory\PDO\Postgresql\SysAclMenuTypesActionsFactory',
                'sysAclRrpRestservicesPDO' => 'DAL\Factory\PDO\Postgresql\SysAclRrpRestservicesFactory',
                'sysServicesGroupsPDO' => 'DAL\Factory\PDO\Postgresql\SysServicesGroupsFactory',
                'sysAclRestservicesPDO' => 'DAL\Factory\PDO\Postgresql\SysAclRestservicesFactory',
                'sysAssignDefinitionPDO' => 'DAL\Factory\PDO\Postgresql\SysAssignDefinitionFactory',   
                'sysAssignDefinitionRolesPDO' => 'DAL\Factory\PDO\Postgresql\SysAssignDefinitionRolesFactory',   
                'sysAclActionRrpPDO' => 'DAL\Factory\PDO\Postgresql\SysAclActionRrpFactory',   
                'sysAclActionRrpRestservicesPDO' => 'DAL\Factory\PDO\Postgresql\SysAclActionRrpRestservicesFactory',                                   
                'infoUsersSendingMailPDO' => 'DAL\Factory\PDO\Postgresql\InfoUsersSendingMailFactory',
                                 
                'logConnectionPDO' => 'DAL\Factory\PDO\Postgresql\LogConnectionFactory',
                'logServicesPDO' => 'DAL\Factory\PDO\Postgresql\LogServicesFactory',                
                'logAdminPDO' => 'DAL\Factory\PDO\Postgresql\LogAdminFactory',
                
                'sysOperationTypesRrpPDO' => 'DAL\Factory\PDO\Postgresql\SysOperationTypesRrpFactory',   
                'pgClassPDO' => 'DAL\Factory\PDO\Postgresql\PgClassFactory',
                'actProcessConfirmPDO' => 'DAL\Factory\PDO\Postgresql\ActProcessConfirmFactory',
                
                'actUsersActionStatisticsPDO' => 'DAL\Factory\PDO\Postgresql\ActUsersActionStatisticsFactory',
                'sysNotificationRestservicesPDO' => 'DAL\Factory\PDO\Postgresql\SysNotificationRestservicesFactory',
                'sysSectorsPDO' => 'DAL\Factory\PDO\Postgresql\SysSectorsFactory',
               
               
                // postgresql
                
                // sqlserver
                // sqlserver
                
                
                
                
                
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
