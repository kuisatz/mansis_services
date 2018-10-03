<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace BLL;

/**
 * Business LAyer MAnager config class
 * Uses zend Framework manager config infrastructure
 */
class BLLManagerConfig{
    
    /**
     * constructor
     */
    public function __construct() {
        
    }
    
    /**
     * config array for zend service manager config
     * @var array
     */
    protected $config= array(
        // Initial configuration with which to seed the ServiceManager.
        // Should be compatible with Zend\ServiceManager\Config.
         'service_manager' => array(
             'invokables' => array(
                  'afterSalesBLL' => 'BLL\BLL\InfoAfterSales',
                  'salesBLL' => 'BLL\BLL\InfoSales',
                  'dealerOwnerBLL' => 'BLL\BLL\InfoDealerOwner',
              
                 
                 
                 
                 
                 
                 
                   'infoCustomerBLL' => 'BLL\BLL\InfoCustomer',
                    'infoProjectBLL' => 'BLL\BLL\InfoProject',
                 
                 
                 
                     ////////////////////////////
                   'sysAccBodyDeffBLL' => 'BLL\BLL\SysAccBodyDeff',
                   'sysAccBodyMatrixBLL' => 'BLL\BLL\SysAccBodyMatrix',
                   'sysAccBodySuppBLL' => 'BLL\BLL\SysAccBodySupp',
                   'sysAccBodyTypesBLL' => 'BLL\BLL\SysAccBodyTypes',
                   'sysAccDeffBLL' => 'BLL\BLL\SysAccDeff',
                   'sysAccSupplierMatrixBLL' => 'BLL\BLL\SysAccSupplierMatrix',
                   'sysAccessoriesMatrixBLL' => 'BLL\BLL\SysAccessoriesMatrix',
                   'sysAccessoryOptionsBLL' => 'BLL\BLL\SysAccessoryOptions',
                   'sysApprovalMechanismBLL' => 'BLL\BLL\SysApprovalMechanism',
                   'sysBbContractTypesBLL' => 'BLL\BLL\SysBbContractTypes',
                   'sysBranchesDealersDeffBLL' => 'BLL\BLL\SysBranchesDealersDeff',
                   'sysBuybackMatrixBLL' => 'BLL\BLL\SysBuybackMatrix',
                   'sysBuybackQuotasBLL' => 'BLL\BLL\SysBuybackQuotas',
                   'sysBuybackTypesBLL' => 'BLL\BLL\SysBuybackTypes',
                   'sysCalendarTypesBLL' => 'BLL\BLL\SysCalendarTypes', 
                   'sysCampaignVehiclesBLL' => 'BLL\BLL\SysCampaignVehicles',
                   'sysCampaignsBLL' => 'BLL\BLL\SysCampaigns',
                   'sysCauseOfLosingwinningBLL' => 'BLL\BLL\SysCauseOfLosingwinning',
                   'sysChannelTypesBLL' => 'BLL\BLL\SysChannelTypes',
                   'sysCommissionDefinitionsBLL' => 'BLL\BLL\SysCommissionDefinitions',
                   'sysCommissionExtrasBLL' => 'BLL\BLL\SysCommissionExtras',
                   'sysCommissionPricerangeDefsBLL' => 'BLL\BLL\SysCommissionPricerangeDefs',
                   'sysContractTypesBLL' => 'BLL\BLL\SysContractTypes',
                   'sysCountryRegionsBLL' => 'BLL\BLL\SysCountryRegions',
                   'sysCsActStatutypesBLL' => 'BLL\BLL\SysCsActStatutypes',
                   'sysCsActivationTypesBLL' => 'BLL\BLL\SysCsActivationTypes',
                   'sysCsStatuTypesBLL' => 'BLL\BLL\SysCsStatuTypes',
                   'sysCurrenciesBLL' => 'BLL\BLL\SysCurrencies',
                   'sysCurrencyFixBLL' => 'BLL\BLL\SysCurrencyFix',
                   'sysCurrencyTypesBLL' => 'BLL\BLL\SysCurrencyTypes',
                   'sysCustomerApplicationTypesBLL' => 'BLL\BLL\SysCustomerApplicationTypes',
                   'sysCustomerCategoriesBLL' => 'BLL\BLL\SysCustomerCategories',
                   'sysCustomerReliabilityBLL' => 'BLL\BLL\SysCustomerReliability',
                   'sysCustomerSectorTypesBLL' => 'BLL\BLL\SysCustomerSectorTypes',
                   'sysCustomerSegmentTypesBLL' => 'BLL\BLL\SysCustomerSegmentTypes',
                   'sysCustomerTurnoverRatesBLL' => 'BLL\BLL\SysCustomerTurnoverRates',
                   'sysCustomerTypesBLL' => 'BLL\BLL\SysCustomerTypes',
                   'sysDemoAllocationTypesBLL' => 'BLL\BLL\SysDemoAllocationTypes',
                   'sysDemoQuotasBLL' => 'BLL\BLL\SysDemoQuotas',
                   'sysDepartmentsBLL' => 'BLL\BLL\SysDepartments',
                   'sysDiscountRatesDeffBLL' => 'BLL\BLL\SysDiscountRatesDeff',
                   'sysEducationDefinitionsBLL' => 'BLL\BLL\SysEducationDefinitions',
                   'sysEducationsSalesmanBLL' => 'BLL\BLL\SysEducationsSalesman',
                   'sysEmbraceBranchNoCodeBLL' => 'BLL\BLL\SysEmbraceBranchNoCode',
                   'sysEmbraceBranchDealershipBLL' => 'BLL\BLL\SysEmbraceBranchDealership', 
                   'sysFinanceTypesBLL' => 'BLL\BLL\SysFinanceTypes',
                   'sysFixedSalesCostsBLL' => 'BLL\BLL\SysFixedSalesCosts',
                   'sysKpnumbersBLL' => 'BLL\BLL\SysKpnumbers',
                   'sysMileagesBLL' => 'BLL\BLL\SysMileagesx',
                   'sysMonthsBLL' => 'BLL\BLL\SysMonthsx',
                   'sysNumericalRangesBLL' => 'BLL\BLL\SysNumericalRanges',
                   'sysOmtBLL' => 'BLL\BLL\SysOmt',
                   'sysPriorityTypeBLL' => 'BLL\BLL\SysPriorityType',
                   'sysProbabilitiesBLL' => 'BLL\BLL\SysProbabilities',
                   'sysRmDeffBLL' => 'BLL\BLL\SysRmDeff',
                   'sysRmMatrixBLL' => 'BLL\BLL\SysRmMatrix',
                   'sysRmSubsidyMatrixBLL' => 'BLL\BLL\SysRmSubsidyMatrix',
                   'sysRmTypesBLL' => 'BLL\BLL\SysRmTypes',
                   'sysRoadtypesBLL' => 'BLL\BLL\SysRoadtypes',
                   'sysSalesLimitsDeffBLL' => 'BLL\BLL\SysSalesLimitsDeff',
                   'sysSalesLimitsMatrixBLL' => 'BLL\BLL\SysSalesLimitsMatrix',
                   'sysSalesLimitsRolesBLL' => 'BLL\BLL\SysSalesLimitsRoles',
                   'sysSalesProvisionTypesBLL' => 'BLL\BLL\SysSalesProvisionTypes',
                   'sysSalesProvisionsBLL' => 'BLL\BLL\SysSalesProvisions',
                   'sysServicesGroupsBLL' => 'BLL\BLL\SysServicesGroups',
                   'sysSisHierarchyBLL' => 'BLL\BLL\SysSisHierarchy',
                   'sysSisQuotasBLL' => 'BLL\BLL\SysSisQuotas',
                   'sysSisQuotasMatrixBLL' => 'BLL\BLL\SysSisQuotasMatrix',
                   'sysSisStatusBLL' => 'BLL\BLL\SysSisStatus',
                   'sysStrategicImportancesBLL' => 'BLL\BLL\SysStrategicImportances',
                   'sysSupplierBLL' => 'BLL\BLL\SysSupplier',
                   'sysTerrainsBLL' => 'BLL\BLL\SysTerrains',
                   'sysTonnageBLL' => 'BLL\BLL\SysTonnage', 
                   'sysTitlesBLL' => 'BLL\BLL\SysTitles', 
                   'sysTopusedProvisionsBLL' => 'BLL\BLL\SysTopusedProvisions', 
                   'sysTopusedBranchdealersBLL' => 'BLL\BLL\SysTopusedBranchdealers',
                   'sysTopusedIntakesBLL' => 'BLL\BLL\SysTopusedIntakes',  
                   'sysVatPolicyTypesBLL' => 'BLL\BLL\SysVatPolicyTypes',
                   'sysVehicleAppTypesBLL' => 'BLL\BLL\SysVehicleAppTypes',
                   'sysVehicleAuditSheetDefBLL' => 'BLL\BLL\SysVehicleAuditSheetDef',
                   'sysVehicleBrandBLL' => 'BLL\BLL\SysVehicleBrand',
                   'sysVehicleCapTypesBLL' => 'BLL\BLL\SysVehicleCapTypes',
                   'sysVehicleCkdcbuBLL' => 'BLL\BLL\SysVehicleCkdcbu',
                   'sysVehicleConfigTypesBLL' => 'BLL\BLL\SysVehicleConfigTypes',
                   'sysVehicleGroupTypesBLL' => 'BLL\BLL\SysVehicleGroupTypes',
                   'sysVehicleGroupsBLL' => 'BLL\BLL\SysVehicleGroups',
                   'sysVehicleGtModelsBLL' => 'BLL\BLL\SysVehicleGtModels',
                   'sysVehicleModelVariantsBLL' => 'BLL\BLL\SysVehicleModelVariants',
                   'sysVehiclesBLL' => 'BLL\BLL\SysVehicles',
                   'sysVehicleBtobtsBLL' => 'BLL\BLL\SysVehicleBtobts', 
                   'sysWarrantiesBLL' => 'BLL\BLL\SysWarranties', 
                   'sysWarrantyMatrixBLL' => 'BLL\BLL\SysWarrantyMatrix',
                   'sysWarrantyTypesBLL' => 'BLL\BLL\SysWarrantyTypes',
                   'sysVehiclesEndgroupsBLL' => 'BLL\BLL\SysVehiclesEndgroups',
                 
                ////////////////////////////
                  
                 //'test' => 'Utill\BLL\Test\Test'
                 'reportConfigurationBLL' => 'BLL\BLL\ReportConfiguration',
                 'cmpnyEqpmntBLL' => 'BLL\BLL\CmpnyEqpmnt',
                 'sysNavigationLeftBLL' => 'BLL\BLL\SysNavigationLeft',
                 'infoUsersBLL' => 'BLL\BLL\InfoUsers',
                 'sysCountrysBLL' => 'BLL\BLL\SysCountrys',
                 'sysCityBLL' => 'BLL\BLL\SysCity',
                 'sysLanguageBLL' => 'BLL\BLL\SysLanguage',
                 'sysBoroughBLL' => 'BLL\BLL\SysBorough',
                 'sysVillageBLL' => 'BLL\BLL\SysVillage',
                 'blLoginLogoutBLL' => 'BLL\BLL\BlLoginLogout',
                 'sysAclRolesBLL' => 'BLL\BLL\SysAclRoles',
                 'sysAclResourcesBLL' => 'BLL\BLL\SysAclResources',
                 'sysAclPrivilegeBLL' => 'BLL\BLL\SysAclPrivilege',
                 'sysAclRrpMapBLL' => 'BLL\BLL\SysAclRrpMap',  
                 'sysSpecificDefinitionsBLL' => 'BLL\BLL\SysSpecificDefinitions',   
                 'infoUsersCommunicationsBLL' => 'BLL\BLL\InfoUsersCommunications',   
                 'infoUsersAddressesBLL' => 'BLL\BLL\InfoUsersAddresses',   
                 'blActivationReportBLL' => 'BLL\BLL\BlActivationReport',
                 'sysOperationTypesBLL' => 'BLL\BLL\SysOperationTypes',
                 'sysOperationTypesToolsBLL' => 'BLL\BLL\SysOperationTypesTools',
                 'infoErrorBLL' => 'BLL\BLL\InfoError',
                 'sysUnitsBLL' => 'BLL\BLL\SysUnits',
                 'hstryLoginBLL' => 'BLL\BLL\HstryLogin',
                 'blAdminActivationReportBLL' => 'BLL\BLL\BlAdminActivationReport',
                 'sysCertificationsBLL' => 'BLL\BLL\SysCertifications',
                 'sysUnitSystemsBLL' => 'BLL\BLL\SysUnitSystems',
                 'infoUsersSocialmediaBLL' => 'BLL\BLL\InfoUsersSocialmedia',
                 'sysSocialMediaBLL' => 'BLL\BLL\SysSocialMedia',
                 'sysMailServerBLL' => 'BLL\BLL\SysMailServer',                 
                 'infoUsersVerbalBLL' => 'BLL\BLL\InfoUsersVerbal',
                 'infoUsersProductsServicesBLL' => 'BLL\BLL\InfoUsersProductsServices',
                 'sysMembershipTypesBLL' => 'BLL\BLL\SysMembershipTypes',
                 'sysAclRrpBLL' => 'BLL\BLL\SysAclRrp',
                 'sysUniversitiesBLL' => 'BLL\BLL\SysUniversities',
                 'sysMenuTypesBLL' => 'BLL\BLL\SysMenuTypes',
                 'sysAclModulesBLL' => 'BLL\BLL\SysAclModules',
                 'sysAclActionsBLL' => 'BLL\BLL\SysAclActions',
                 'sysAclMenuTypesActionsBLL' => 'BLL\BLL\SysAclMenuTypesActions',
                 'sysAclRrpRestservicesBLL' => 'BLL\BLL\SysAclRrpRestservices',
                 'sysServicesGroupsBLL' => 'BLL\BLL\SysServicesGroups',
                 'sysAclRestservicesBLL' => 'BLL\BLL\SysAclRestservices',
                 'sysAssignDefinitionBLL' => 'BLL\BLL\SysAssignDefinition',
                 'sysAssignDefinitionRolesBLL' => 'BLL\BLL\SysAssignDefinitionRoles',
                 'sysAclActionRrpBLL' => 'BLL\BLL\SysAclActionRrp',
                 'sysAclActionRrpRestservicesBLL' => 'BLL\BLL\SysAclActionRrpRestservices',
                 'infoUsersSendingMailBLL' => 'BLL\BLL\InfoUsersSendingMail',                      
                 
                 'logConnectionBLL' => 'BLL\BLL\LogConnection',  
                 'logServicesBLL' => 'BLL\BLL\LogServices',
                 'logAdminBLL' => 'BLL\BLL\LogAdmin',
                 
                 'opUserIdBLL' => 'BLL\BLL\InfoUsers', 
                 'operationsTypesBLL' => 'BLL\BLL\SysOperationTypesRrp',  
                 'languageIdBLL' => 'BLL\BLL\SysLanguage',
                 'operationTableNameBLL' => 'BLL\BLL\PgClass',
                 'consultantProcessSendBLL' => 'BLL\BLL\ActProcessConfirm',  
                 'SesionIdBLL' => 'BLL\BLL\InfoUsers', 
                 
                'pgClassBLL' => 'BLL\BLL\PgClass',
                'sysOperationTypesRrpBLL' => 'BLL\BLL\SysOperationTypesRrp',
                'actProcessConfirmBLL' => 'BLL\BLL\ActProcessConfirm',
                 
                'actUsersActionStatisticsBLL' => 'BLL\BLL\ActUsersActionStatistics',
                'sysNotificationRestservicesBLL' => 'BLL\BLL\SysNotificationRestservices',
                 
             
                  
                 
             ),
             'factories' => [
                 //'reportConfigurationPDO' => 'BLL\BLL\ReportConfiguration',
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




