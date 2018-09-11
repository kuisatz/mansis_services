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
                  'afterSalesBLL' => 'BLL\BLL_POSTGRE\InfoAfterSales',
                  'salesBLL' => 'BLL\BLL_POSTGRE\InfoSales',
                  'dealerOwnerBLL' => 'BLL\BLL_POSTGRE\InfoDealerOwner',
              
                     ////////////////////////////
                   'sysAccBodyDeffBLL' => 'BLL\BLL_POSTGRE\SysAccBodyDeff',
                   'sysAccBodyMatrixBLL' => 'BLL\BLL_POSTGRE\SysAccBodyMatrix',
                   'sysAccBodySuppBLL' => 'BLL\BLL_POSTGRE\SysAccBodySupp',
                   'sysAccBodyTypesBLL' => 'BLL\BLL_POSTGRE\SysAccBodyTypes',
                   'sysAccDeffBLL' => 'BLL\BLL_POSTGRE\SysAccDeff',
                   'sysAccSupplierMatrixBLL' => 'BLL\BLL_POSTGRE\SysAccSupplierMatrix',
                   'sysAccessoriesMatrixBLL' => 'BLL\BLL_POSTGRE\SysAccessoriesMatrix',
                   'sysAccessoryOptionsBLL' => 'BLL\BLL_POSTGRE\SysAccessoryOptions',
                   'sysApprovalMechanismBLL' => 'BLL\BLL_POSTGRE\SysApprovalMechanism',
                   'sysBbContractTypesBLL' => 'BLL\BLL_POSTGRE\SysBbContractTypes',
                   'sysBranchesDealersDeffBLL' => 'BLL\BLL_POSTGRE\SysBranchesDealersDeff',
                   'sysBuybackMatrixBLL' => 'BLL\BLL_POSTGRE\SysBuybackMatrix',
                   'sysBuybackQuotasBLL' => 'BLL\BLL_POSTGRE\SysBuybackQuotas',
                   'sysBuybackTypesBLL' => 'BLL\BLL_POSTGRE\SysBuybackTypes',
                   'sysCalendarTypesBLL' => 'BLL\BLL_POSTGRE\SysCalendarTypes', 
                   'sysCampaignVehiclesBLL' => 'BLL\BLL_POSTGRE\SysCampaignVehicles',
                   'sysCampaignsBLL' => 'BLL\BLL_POSTGRE\SysCampaigns',
                   'sysCauseOfLosingwinningBLL' => 'BLL\BLL_POSTGRE\SysCauseOfLosingwinning',
                   'sysChannelTypesBLL' => 'BLL\BLL_POSTGRE\SysChannelTypes',
                   'sysCommissionDefinitionsBLL' => 'BLL\BLL_POSTGRE\SysCommissionDefinitions',
                   'sysCommissionExtrasBLL' => 'BLL\BLL_POSTGRE\SysCommissionExtras',
                   'sysCommissionPricerangeDefsBLL' => 'BLL\BLL_POSTGRE\SysCommissionPricerangeDefs',
                   'sysContractTypesBLL' => 'BLL\BLL_POSTGRE\SysContractTypes',
                   'sysCountryRegionsBLL' => 'BLL\BLL_POSTGRE\SysCountryRegions',
                   'sysCsActStatutypesBLL' => 'BLL\BLL_POSTGRE\SysCsActStatutypes',
                   'sysCsActivationTypesBLL' => 'BLL\BLL_POSTGRE\SysCsActivationTypes',
                   'sysCsStatuTypesBLL' => 'BLL\BLL_POSTGRE\SysCsStatuTypes',
                   'sysCurrenciesBLL' => 'BLL\BLL_POSTGRE\SysCurrencies',
                   'sysCurrencyFixBLL' => 'BLL\BLL_POSTGRE\SysCurrencyFix',
                   'sysCurrencyTypesBLL' => 'BLL\BLL_POSTGRE\SysCurrencyTypes',
                   'sysCustomerApplicationTypesBLL' => 'BLL\BLL_POSTGRE\SysCustomerApplicationTypes',
                   'sysCustomerCategoriesBLL' => 'BLL\BLL_POSTGRE\SysCustomerCategories',
                   'sysCustomerReliabilityBLL' => 'BLL\BLL_POSTGRE\SysCustomerReliability',
                   'sysCustomerSectorTypesBLL' => 'BLL\BLL_POSTGRE\SysCustomerSectorTypes',
                   'sysCustomerSegmentTypesBLL' => 'BLL\BLL_POSTGRE\SysCustomerSegmentTypes',
                   'sysCustomerTurnoverRatesBLL' => 'BLL\BLL_POSTGRE\SysCustomerTurnoverRates',
                   'sysDemoAllocationTypesBLL' => 'BLL\BLL_POSTGRE\SysDemoAllocationTypes',
                   'sysDemoQuotasBLL' => 'BLL\BLL_POSTGRE\SysDemoQuotas',
                   'sysDepartmentsBLL' => 'BLL\BLL_POSTGRE\SysDepartments',
                   'sysDiscountRatesDeffBLL' => 'BLL\BLL_POSTGRE\SysDiscountRatesDeff',
                   'sysEducationDefinitionsBLL' => 'BLL\BLL_POSTGRE\SysEducationDefinitions',
                   'sysEducationsSalesmanBLL' => 'BLL\BLL_POSTGRE\SysEducationsSalesman',
                   'sysEmbraceBranchNoCodeBLL' => 'BLL\BLL_POSTGRE\SysEmbraceBranchNoCode',
                   'sysEmbraceBranchDealershipBLL' => 'BLL\BLL_POSTGRE\SysEmbraceBranchDealership', 
                   'sysFinanceTypesBLL' => 'BLL\BLL_POSTGRE\SysFinanceTypes',
                   'sysFixedSalesCostsBLL' => 'BLL\BLL_POSTGRE\SysFixedSalesCosts',
                   'sysKpnumbersBLL' => 'BLL\BLL_POSTGRE\SysKpnumbers',
                   'sysMileagesBLL' => 'BLL\BLL_POSTGRE\SysMileages',
                   'sysMonthsBLL' => 'BLL\BLL_POSTGRE\SysMonths',
                   'sysNumericalRangesBLL' => 'BLL\BLL_POSTGRE\SysNumericalRanges',
                   'sysOmtBLL' => 'BLL\BLL_POSTGRE\SysOmt',
                   'sysPriorityTypeBLL' => 'BLL\BLL_POSTGRE\SysPriorityType',
                   'sysProbabilitiesBLL' => 'BLL\BLL_POSTGRE\SysProbabilities',
                   'sysRmDeffBLL' => 'BLL\BLL_POSTGRE\SysRmDeff',
                   'sysRmMatrixBLL' => 'BLL\BLL_POSTGRE\SysRmMatrix',
                   'sysRmSubsidyMatrixBLL' => 'BLL\BLL_POSTGRE\SysRmSubsidyMatrix',
                   'sysRmTypesBLL' => 'BLL\BLL_POSTGRE\SysRmTypes',
                   'sysRoadtypesBLL' => 'BLL\BLL_POSTGRE\SysRoadtypes',
                   'sysSalesLimitsDeffBLL' => 'BLL\BLL_POSTGRE\SysSalesLimitsDeff',
                   'sysSalesLimitsMatrixBLL' => 'BLL\BLL_POSTGRE\SysSalesLimitsMatrix',
                   'sysSalesLimitsRolesBLL' => 'BLL\BLL_POSTGRE\SysSalesLimitsRoles',
                   'sysSalesProvisionTypesBLL' => 'BLL\BLL_POSTGRE\SysSalesProvisionTypes',
                   'sysSalesProvisionsBLL' => 'BLL\BLL_POSTGRE\SysSalesProvisions',
                   'sysServicesGroupsBLL' => 'BLL\BLL_POSTGRE\SysServicesGroups',
                   'sysSisHierarchyBLL' => 'BLL\BLL_POSTGRE\SysSisHierarchy',
                   'sysSisQuotasBLL' => 'BLL\BLL_POSTGRE\SysSisQuotas',
                   'sysSisQuotasMatrixBLL' => 'BLL\BLL_POSTGRE\SysSisQuotasMatrix',
                   'sysSisStatusBLL' => 'BLL\BLL_POSTGRE\SysSisStatus',
                   'sysStrategicImportancesBLL' => 'BLL\BLL_POSTGRE\SysStrategicImportances',
                   'sysSupplierBLL' => 'BLL\BLL_POSTGRE\SysSupplier',
                   'sysTerrainsBLL' => 'BLL\BLL_POSTGRE\SysTerrains',
                   'sysTitlesBLL' => 'BLL\BLL_POSTGRE\SysTitles', 
                   'sysTopusedProvisionsBLL' => 'BLL\BLL_POSTGRE\SysTopusedProvisions', 
                   'sysTopusedBranchdealersBLL' => 'BLL\BLL_POSTGRE\SysTopusedBranchdealers',
                   'sysTopusedIntakesBLL' => 'BLL\BLL_POSTGRE\SysTopusedIntakes',  
                   'sysVatPolicyTypesBLL' => 'BLL\BLL_POSTGRE\SysVatPolicyTypes',
                   'sysVehicleAppTypesBLL' => 'BLL\BLL_POSTGRE\SysVehicleAppTypes',
                   'sysVehicleAuditSheetDefBLL' => 'BLL\BLL_POSTGRE\SysVehicleAuditSheetDef',
                   'sysVehicleBrandBLL' => 'BLL\BLL_POSTGRE\SysVehicleBrand',
                   'sysVehicleCapTypesBLL' => 'BLL\BLL_POSTGRE\SysVehicleCapTypes',
                   'sysVehicleConfigTypesBLL' => 'BLL\BLL_POSTGRE\SysVehicleConfigTypes',
                   'sysVehicleGroupTypesBLL' => 'BLL\BLL_POSTGRE\SysVehicleGroupTypes',
                   'sysVehicleGroupsBLL' => 'BLL\BLL_POSTGRE\SysVehicleGroups',
                   'sysVehicleGtModelsBLL' => 'BLL\BLL_POSTGRE\SysVehicleGtModels',
                   'sysVehicleModelVariantsBLL' => 'BLL\BLL_POSTGRE\SysVehicleModelVariants',
                   'sysVehiclesBLL' => 'BLL\BLL_POSTGRE\SysVehicles',
                   'sysVehicleBtobtsBLL' => 'BLL\BLL_POSTGRE\SysVehicleBtobts', 
                   'sysWarrantiesBLL' => 'BLL\BLL_POSTGRE\SysWarranties', 
                   'sysWarrantyMatrixBLL' => 'BLL\BLL_POSTGRE\SysWarrantyMatrix',
                   'sysWarrantyTypesBLL' => 'BLL\BLL_POSTGRE\SysWarrantyTypes',
                   'sysVehiclesEndgroupsBLL' => 'BLL\BLL_POSTGRE\SysVehiclesEndgroups',
                 
                ////////////////////////////
                  
                 //'test' => 'Utill\BLL\Test\Test'
                 'reportConfigurationBLL' => 'BLL\BLL_POSTGRE\ReportConfiguration',
                 'cmpnyEqpmntBLL' => 'BLL\BLL_POSTGRE\CmpnyEqpmnt',
                 'sysNavigationLeftBLL' => 'BLL\BLL_POSTGRE\SysNavigationLeft',
                 'infoUsersBLL' => 'BLL\BLL_POSTGRE\InfoUsers',
                 'sysCountrysBLL' => 'BLL\BLL_POSTGRE\SysCountrys',
                 'sysCityBLL' => 'BLL\BLL_POSTGRE\SysCity',
                 'sysLanguageBLL' => 'BLL\BLL_POSTGRE\SysLanguage',
                 'sysBoroughBLL' => 'BLL\BLL_POSTGRE\SysBorough',
                 'sysVillageBLL' => 'BLL\BLL_POSTGRE\SysVillage',
                 'blLoginLogoutBLL' => 'BLL\BLL_POSTGRE\BlLoginLogout',
                 'sysAclRolesBLL' => 'BLL\BLL_POSTGRE\SysAclRoles',
                 'sysAclResourcesBLL' => 'BLL\BLL_POSTGRE\SysAclResources',
                 'sysAclPrivilegeBLL' => 'BLL\BLL_POSTGRE\SysAclPrivilege',
                 'sysAclRrpMapBLL' => 'BLL\BLL_POSTGRE\SysAclRrpMap',  
                 'sysSpecificDefinitionsBLL' => 'BLL\BLL_POSTGRE\SysSpecificDefinitions',   
                 'infoUsersCommunicationsBLL' => 'BLL\BLL_POSTGRE\InfoUsersCommunications',   
                 'infoUsersAddressesBLL' => 'BLL\BLL_POSTGRE\InfoUsersAddresses',   
                 'blActivationReportBLL' => 'BLL\BLL_POSTGRE\BlActivationReport',
                 'sysOperationTypesBLL' => 'BLL\BLL_POSTGRE\SysOperationTypes',
                 'sysOperationTypesToolsBLL' => 'BLL\BLL_POSTGRE\SysOperationTypesTools',
                 'infoErrorBLL' => 'BLL\BLL_POSTGRE\InfoError',
                 'sysUnitsBLL' => 'BLL\BLL_POSTGRE\SysUnits',
                 'hstryLoginBLL' => 'BLL\BLL_POSTGRE\HstryLogin',
                 'blAdminActivationReportBLL' => 'BLL\BLL_POSTGRE\BlAdminActivationReport',
                 'sysCertificationsBLL' => 'BLL\BLL_POSTGRE\SysCertifications',
                 'sysUnitSystemsBLL' => 'BLL\BLL_POSTGRE\SysUnitSystems',
                 'infoUsersSocialmediaBLL' => 'BLL\BLL_POSTGRE\InfoUsersSocialmedia',
                 'sysSocialMediaBLL' => 'BLL\BLL_POSTGRE\SysSocialMedia',
                 'sysMailServerBLL' => 'BLL\BLL_POSTGRE\SysMailServer',                 
                 'infoUsersVerbalBLL' => 'BLL\BLL_POSTGRE\InfoUsersVerbal',
                 'infoUsersProductsServicesBLL' => 'BLL\BLL_POSTGRE\InfoUsersProductsServices',
                 'sysMembershipTypesBLL' => 'BLL\BLL_POSTGRE\SysMembershipTypes',
                 'sysAclRrpBLL' => 'BLL\BLL_POSTGRE\SysAclRrp',
                 'sysUniversitiesBLL' => 'BLL\BLL_POSTGRE\SysUniversities',
                 'sysMenuTypesBLL' => 'BLL\BLL_POSTGRE\SysMenuTypes',
                 'sysAclModulesBLL' => 'BLL\BLL_POSTGRE\SysAclModules',
                 'sysAclActionsBLL' => 'BLL\BLL_POSTGRE\SysAclActions',
                 'sysAclMenuTypesActionsBLL' => 'BLL\BLL_POSTGRE\SysAclMenuTypesActions',
                 'sysAclRrpRestservicesBLL' => 'BLL\BLL_POSTGRE\SysAclRrpRestservices',
                 'sysServicesGroupsBLL' => 'BLL\BLL_POSTGRE\SysServicesGroups',
                 'sysAclRestservicesBLL' => 'BLL\BLL_POSTGRE\SysAclRestservices',
                 'sysAssignDefinitionBLL' => 'BLL\BLL_POSTGRE\SysAssignDefinition',
                 'sysAssignDefinitionRolesBLL' => 'BLL\BLL_POSTGRE\SysAssignDefinitionRoles',
                 'sysAclActionRrpBLL' => 'BLL\BLL_POSTGRE\SysAclActionRrp',
                 'sysAclActionRrpRestservicesBLL' => 'BLL\BLL_POSTGRE\SysAclActionRrpRestservices',
                 'infoUsersSendingMailBLL' => 'BLL\BLL_POSTGRE\InfoUsersSendingMail',                      
                 
                 'logConnectionBLL' => 'BLL\BLL_POSTGRE\LogConnection',  
                 'logServicesBLL' => 'BLL\BLL_POSTGRE\LogServices',
                 'logAdminBLL' => 'BLL\BLL_POSTGRE\LogAdmin',
                 
                 'opUserIdBLL' => 'BLL\BLL_POSTGRE\InfoUsers', 
                 'operationsTypesBLL' => 'BLL\BLL_POSTGRE\SysOperationTypesRrp',  
                 'languageIdBLL' => 'BLL\BLL_POSTGRE\SysLanguage',
                 'operationTableNameBLL' => 'BLL\BLL_POSTGRE\PgClass',
                 'consultantProcessSendBLL' => 'BLL\BLL_POSTGRE\ActProcessConfirm',  
                 'SesionIdBLL' => 'BLL\BLL_POSTGRE\InfoUsers', 
                 
                'pgClassBLL' => 'BLL\BLL_POSTGRE\PgClass',
                'sysOperationTypesRrpBLL' => 'BLL\BLL_POSTGRE\SysOperationTypesRrp',
                'actProcessConfirmBLL' => 'BLL\BLL_POSTGRE\ActProcessConfirm',
                 
                'actUsersActionStatisticsBLL' => 'BLL\BLL_POSTGRE\ActUsersActionStatistics',
                'sysNotificationRestservicesBLL' => 'BLL\BLL_POSTGRE\SysNotificationRestservices',
                 
             
                  
                 
             ),
             'factories' => [
                 //'reportConfigurationPDO' => 'BLL\BLL_POSTGRE\ReportConfiguration',
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




