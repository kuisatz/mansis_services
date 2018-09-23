<?php
/**
 *Framework 
 *
 * @link 
 * @copyright Copyright (c) 2017
 * @license   
 */


namespace Slim;

class SlimHmacProxy extends \Proxy\Proxy {

    /**
     * a mapping array for related functions and proxy calls
     * to be executed
     * @var array()
     */
    protected $redirectMap = array('getReports_test' => 'restApiDefaultCall',
                                    'getDynamicForm_test' => 'restApiDefaultCall',
                                    'getOracleConnTest' => 'restApiDefaultCall',
        
        
        //** sysAccBodyDeffBLL ----------------------
                                    'fillAccBodyDeffGridx_sysaccbodydeff' => 'restApiDefaultCall',
                                    'pkFillAccBodyDeffGridx_sysaccbodydeff' => 'restApiDefaultCall',
                                    'pkAccBodyDeffDdList_sysaccbodydeff' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysaccbodydeff' => 'restApiDefaultCall',
        
                                    'pkInsertAct_sysaccbodydeff' => 'restApiDefaultCall',
                                    'pkUpdateAct_sysaccbodydeff' => 'restApiDefaultCall',
                                    'pkDeletedAct_sysaccbodydeff' => 'restApiDefaultCall',        
        
        //**---- sysAccBodyDeffBLL -------------------//  
            
        //**---- sysVehicleAppTypesBLL ------------------- 
        
                                    'pkVehicleAppTypesDdList_sysvehicleapptypes' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysvehicleapptypes' => 'restApiDefaultCall',
        
         //** sysVehicleAppTypesBLL ----------------------//
        
        
        //**---- sysvehiclebtobtsBLL ------------------- 
        
                                    'pkVehicleBtoBtsTypesDdList_sysvehiclebtobts' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysvehiclebtobts' => 'restApiDefaultCall',
        
         //** sysvehiclebtobtsBLL ----------------------//
        
        //**---- sysvehiclecaptypesBLL ------------------- 
        
                                    'pkVehicleCapTypesDdList_sysvehiclecaptypes' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysvehiclecaptypes' => 'restApiDefaultCall',
        
         //** sysvehiclecaptypesBLL ----------------------// 
        
        
           
        //**---- sysVehicleCkdcbuBLL ------------------- 
        
                                    'pkVehicleCkdCbuDdList_sysvehicleckdcbu' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysvehicleckdcbu' => 'restApiDefaultCall',
        
         //** sysVehicleCkdcbuBLL ----------------------// 
     
          //**---- sysVehicleConfigTypesBLL ------------------- 
        
                                    'pkVehicleConfigTypesDdList_sysvehicleconfigtypes' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysvehicleconfigtypes' => 'restApiDefaultCall',
        
         //** sysVehicleConfigTypesBLL ----------------------//    
        
        
        //**---- sysVehicleGroupTypesBLL ------------------- 
        
                                    'pkVehicleGroupTypesDdList_sysvehiclegrouptypes' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysvehiclegrouptypes' => 'restApiDefaultCall',
        
         //** sysVehicleGroupTypesBLL ----------------------//    
        
          //**---- sysVehicleGtModelsBLL ------------------- 
        
                                    'pkVehicleGtModelsDdList_sysvehiclegtmodels' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysvehiclegrouptypes' => 'restApiDefaultCall',
        
         //** sysVehicleGtModelsBLL ----------------------//    
        
          //**---- sysVehicleGtModelsBLL ------------------- 
        
                                    'pkVehicleGroupsDdList_sysvehiclegroups' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysvehiclegroups' => 'restApiDefaultCall',
        
         //** sysVehicleGtModelsBLL ----------------------//    
         //**---- sysAccBodyMatrixBLL ------------------- 
        
                                    'pkFillBodyMatrixGridx_sysaccbodymatrix' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysaccbodymatrix' => 'restApiDefaultCall',
        
                                    'pkInsertAct_sysaccbodymatrix' => 'restApiDefaultCall',
                                    'pkUpdateAct_sysaccbodymatrix' => 'restApiDefaultCall',
                                    'pkDeletedAct_sysaccbodymatrix' => 'restApiDefaultCall',  
        
         //** sysAccBodyMatrixBLL ----------------------//    
        
          //**---- sysAccBodyTypesBLL -------------------  
                                    'pkAccBodySuppDdList_sysaccbodytypes' => 'restApiDefaultCall', 
         //** sysAccBodyTypesBLL ----------------------//    
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
 //** infoDealerOwner ----------------------
                                    'pkgetAfterSalesDetayIsEmriAcikWithoutServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAracGirisSayilari_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAracGirisSayilariWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAracGirisSayilariAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAracGirisSayilariAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAracGirisSayilariYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAracGirisSayilariYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardAracGirisSayilari_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardAracGirisSayilariWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkgetAfterSalesDashboardDowntime_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardDowntimeWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGridDowntime_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGridDowntimeWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkgetAfterSalesDashboardVerimlilik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardVerimlilikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayVerimlilikYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayVerimlilikYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkgetAfterSalesDashboardKapasite_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardKapasiteWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayKapasiteYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayKapasiteYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkgetAfterSalesDashboardEtkinlik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardEtkinlikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayEtkinlikYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayEtkinlikYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkgetAfterSalesDashboardYedekParcaTS_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardYedekParcaTSWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaTS_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaTSWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaTSAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaTSAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaTSYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaTSYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkgetAfterSalesDashboardYedekParcaYS_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardYedekParcaYSWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaYS_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaYSWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaYSAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaYSAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaYSYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayYedekParcaYSYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
        
                                    'pkgetAfterSalesDashboardAtolyeCirosu_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardAtolyeCirosuWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAtolyeCirosu_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAtolyeCirosuWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAtolyeCirosuAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAtolyeCirosuAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAtolyeCirosuYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAtolyeCirosuYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
                                    
                                    'pkgetAfterSalesDashboardGarantiCirosu_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardGarantiCirosuWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGarantiCirosu_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGarantiCirosuWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGarantiCirosuAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGarantiCirosuAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGarantiCirosuYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGarantiCirosuYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    
        
                                    
                                    'pkgetAfterSalesDashboardDirekSatisCirosu_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardDirekSatisCirosuWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayDirekSatisCirosu_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayDirekSatisCirosuWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayDirekSatisCirosuAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayDirekSatisCirosuAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayDirekSatisCirosuYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayDirekSatisCirosuYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
        
        
                                    'pkgetAfterSalesDashboardMMCSI_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardMMCSIWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayMMCSIYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayMMCSIYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGridMMCSI_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGridMMCSIWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkgetAfterSalesDashboardMMCXI_infoDealerOwner'  => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardMMCXIWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayMMCXIYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayMMCXIYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGridMMCXI_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayGridMMCXIWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkgetAfterSalesDetayIsEmriAcikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsEmriAcikAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsEmriAcikAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsEmriAcikYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsEmriAcikYillik_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkfillServicesDdlist_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayBayiStok_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkgetAfterSalesDetayCiro_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayCiroWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayCiroAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayCiroAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayCiroYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayCiroYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkgetAfterSalesDetaySatisFaturalari_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetaySatisFaturalariWeeklyWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetaySatisFaturalariAylik_infoDealerOwner' => 'restApiDefaultCall', 
                                    'pkgetAfterSalesDetaySatisFaturalariAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetaySatisFaturalariYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetaySatisFaturalariYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsemriFaturalari_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsemriFaturalariWeeklyWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsemriFaturalariAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsemriFaturalariAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsemriFaturalariYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsemriFaturalariYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIcmalFaturalari_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIcmalFaturalariWeeklyWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIcmalFaturalariAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIcmalFaturalariAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIcmalFaturalariYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIcmalFaturalariYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAlisFaturalari_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAlisFaturalariWeeklyWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAlisFaturalariAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAlisFaturalariAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAlisFaturalariYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayAlisFaturalariYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsEmriAcilanKapanan_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsEmriAcilanKapananWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsEmriAcilanKapananAylik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsEmriAcilanKapananAylikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsEmriAcilanKapananYillik_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayIsEmriAcilanKapananYillikWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardIsEmriData_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardIsEmriDataWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardFaturaData_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardFaturaDataWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardCiroYedekParca_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardStoklar_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardStoklarWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayStoklarGrid_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayStoklarGridWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardIsEmriLastDataMusteri_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardIsEmriLastDataMusteriWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                     
        
        
        
                                    'pkgetAfterSalesDashboardFaalYedekParca_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDetayFaalYedekParca_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardFaalYagToplam_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardFaalStokToplam_infoDealerOwner' => 'restApiDefaultCall',
        
        
        
                                    'pkgetAfterSalesDashboardFaalYedekParcaWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardFaalYedekParcaServisDisiWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
                                    'pkgetAfterSalesDashboardFaalYagToplamWithServices_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesDashboardFaalStokToplamWithServices_infoDealerOwner' => 'restApiDefaultCall',
        
        
        
        
        
        
                                    'pkgetAfterSalesYedekParcaHedefServisli_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesYedekParcaHedefServissiz_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesYedekParcaPDFServissiz_infoDealerOwner' => 'restApiDefaultCall',
                                    'pkgetAfterSalesYedekParcaPDFServisli_infoDealerOwner' => 'restApiDefaultCall',
        
        
         //**---- infoDealerOwner ------------------- 
        
        

          //** afterSales ----------------------
                                    'getAfterSalesDetayIsEmriAcikWithoutServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAracGirisSayilari_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAracGirisSayilariWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAracGirisSayilariAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAracGirisSayilariAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAracGirisSayilariYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAracGirisSayilariYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardAracGirisSayilari_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardAracGirisSayilariWithServices_infoAfterSales' => 'restApiDefaultCall',
        
                                    'getAfterSalesDashboardDowntime_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardDowntimeWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGridDowntime_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGridDowntimeWithServices_infoAfterSales' => 'restApiDefaultCall',
        
                                    'getAfterSalesDashboardVerimlilik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardVerimlilikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayVerimlilikYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayVerimlilikYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
        
                                    'getAfterSalesDashboardKapasite_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardKapasiteWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayKapasiteYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayKapasiteYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
        
                                    'getAfterSalesDashboardEtkinlik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardEtkinlikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayEtkinlikYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayEtkinlikYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
        
                                    'getAfterSalesDashboardYedekParcaTS_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardYedekParcaTSWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaTS_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaTSWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaTSAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaTSAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaTSYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaTSYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
        
                                    'getAfterSalesDashboardYedekParcaYS_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardYedekParcaYSWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaYS_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaYSWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaYSAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaYSAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaYSYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayYedekParcaYSYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
        
        
                                    'getAfterSalesDashboardAtolyeCirosu_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardAtolyeCirosuWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAtolyeCirosu_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAtolyeCirosuWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAtolyeCirosuAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAtolyeCirosuAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAtolyeCirosuYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAtolyeCirosuYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
        
                                    
                                    'getAfterSalesDashboardGarantiCirosu_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardGarantiCirosuWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGarantiCirosu_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGarantiCirosuWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGarantiCirosuAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGarantiCirosuAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGarantiCirosuYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGarantiCirosuYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    
        
                                    
                                    'getAfterSalesDashboardDirekSatisCirosu_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardDirekSatisCirosuWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayDirekSatisCirosu_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayDirekSatisCirosuWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayDirekSatisCirosuAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayDirekSatisCirosuAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayDirekSatisCirosuYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayDirekSatisCirosuYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
        
        
        
                                    'getAfterSalesDashboardMMCSI_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardMMCSIWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayMMCSIYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayMMCSIYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGridMMCSI_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGridMMCSIWithServices_infoAfterSales' => 'restApiDefaultCall',
        
                                    'getAfterSalesDashboardMMCXI_infoAfterSales'  => 'restApiDefaultCall',
                                    'getAfterSalesDashboardMMCXIWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayMMCXIYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayMMCXIYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGridMMCXI_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayGridMMCXIWithServices_infoAfterSales' => 'restApiDefaultCall',
        
                                    'getAfterSalesDetayIsEmriAcikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsEmriAcikAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsEmriAcikAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsEmriAcikYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsEmriAcikYillik_infoAfterSales' => 'restApiDefaultCall',
        
                                    'fillServicesDdlist_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayBayiStok_infoAfterSales' => 'restApiDefaultCall',
        
                                    'getAfterSalesDetayCiro_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayCiroWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayCiroAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayCiroAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayCiroYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayCiroYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
        
                                    'getAfterSalesDetaySatisFaturalari_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetaySatisFaturalariWeeklyWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetaySatisFaturalariAylik_infoAfterSales' => 'restApiDefaultCall', 
                                    'getAfterSalesDetaySatisFaturalariAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetaySatisFaturalariYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetaySatisFaturalariYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsemriFaturalari_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsemriFaturalariWeeklyWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsemriFaturalariAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsemriFaturalariAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsemriFaturalariYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsemriFaturalariYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIcmalFaturalari_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIcmalFaturalariWeeklyWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIcmalFaturalariAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIcmalFaturalariAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIcmalFaturalariYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIcmalFaturalariYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAlisFaturalari_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAlisFaturalariWeeklyWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAlisFaturalariAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAlisFaturalariAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAlisFaturalariYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayAlisFaturalariYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsEmriAcilanKapanan_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsEmriAcilanKapananWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsEmriAcilanKapananAylik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsEmriAcilanKapananAylikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsEmriAcilanKapananYillik_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayIsEmriAcilanKapananYillikWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardIsEmriData_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardIsEmriDataWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardFaturaData_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardFaturaDataWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardCiroYedekParca_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardStoklar_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardStoklarWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayStoklarGrid_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayStoklarGridWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardIsEmriLastDataMusteri_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardIsEmriLastDataMusteriWithServices_infoAfterSales' => 'restApiDefaultCall',
        
        
        
        
                                    'getAfterSalesDashboardFaalYedekParca_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDetayFaalYedekParca_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardFaalYagToplam_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardFaalStokToplam_infoAfterSales' => 'restApiDefaultCall',
        
        
        
                                    'getAfterSalesDashboardFaalYedekParcaWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardFaalYedekParcaServisDisiWithServices_infoAfterSales' => 'restApiDefaultCall',
        
                                    'getAfterSalesDashboardFaalYagToplamWithServices_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesDashboardFaalStokToplamWithServices_infoAfterSales' => 'restApiDefaultCall',
        
        
        
        
        
        
                                    'getAfterSalesYedekParcaHedefServisli_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesYedekParcaHedefServissiz_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesYedekParcaPDFServissiz_infoAfterSales' => 'restApiDefaultCall',
                                    'getAfterSalesYedekParcaPDFServisli_infoAfterSales' => 'restApiDefaultCall',
        
        
         //**---- afterSales ------------------- 
        
        //** Sales ----------------------
                                    'getFunnelBasicData_infoSales' => 'restApiDefaultCall',
                                    'getFunnelOlcumData_infoSales' => 'restApiDefaultCall',
                                    'getKamyonSales_infoSales' => 'restApiDefaultCall',
                                    'getSalesDashboardData_infoSales' => 'restApiDefaultCall',
                                    'getDealerInvoice_infoSales' => 'restApiDefaultCall',
         //**---- Sales ------------------- 
        
        //** leftnavigation ----------------------
                                    'pkDelete_leftnavigation' => 'restApiDefaultCall',
                                    'pkGetAll_leftnavigation' => 'restApiDefaultCall',
                                    'pkInsert_leftnavigation' => 'restApiDefaultCall',
                                    'pkUpdate_leftnavigation' => 'restApiDefaultCall',
                                    'pkFillGrid_leftnavigation' => 'restApiDefaultCall',
                                    'pkGetLeftMenu_leftnavigation' => 'restApiDefaultCall',
                                    'pkFillGridForAdmin_leftnavigation' => 'restApiDefaultCall',
                                    'pkFillForAdminTree_leftnavigation' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_leftnavigation' => 'restApiDefaultCall',
        //**---- leftnavigation -------------------        
         //** infoUsers ----------------------
                                    'pkDelete_infoUsers' => 'restApiDefaultCall',
                                    'pkGetAll_infoUsers' => 'restApiDefaultCall',
                                    'pkInsert_infoUsers' => 'restApiDefaultCall',
                                    'tempInsert_infoUsers' => 'restApiDefaultCall', 
                                    'pkUpdate_infoUsers' => 'restApiDefaultCall',
                                    'pkFillGrid_infoUsers' => 'restApiDefaultCall',
                                    'pkDeletedAct_infoUsers' => 'restApiDefaultCall',
                                    'pktempUpdate_infoUsers' => 'restApiDefaultCall',
                                    'pkFillUsersListNpk_infoUsers' => 'restApiDefaultCall',
                                    'pkFillUsersInformationNpk_infoUsers' => 'restApiDefaultCall',
                                    'pkInsertConsultant_infoUsers' => 'restApiDefaultCall',
                                    'pkInsertUrgePerson_infoUsers' => 'restApiDefaultCall',
                                    'setPersonPassword_infoUsers' => 'restApiDefaultCall',
        
                                    'pkUpdateConsUserConfirmAct_infoUsers' => 'restApiDefaultCall',
                                    'pkFillUsersProfileInformationGuest_infoUsers' => 'restApiDefaultCall',
                                    'pkFillUsersProfileInformation_infoUsers' => 'restApiDefaultCall',
                                    'pkGetUserShortInformation_infoUsers' => 'restApiDefaultCall',
                                    'tempInsertSB_infoUsers' => 'restApiDefaultCall',
                                    'pkTempInsertSB_infoUsers' => 'restApiDefaultCall',
                                    'pkUpdateTempSB_infoUsers' => 'restApiDefaultCall',
                                    'pkDeletedActTempSB_infoUsers' => 'restApiDefaultCall',
                                    'pkFillTempUserLists_infoUsers' => 'restApiDefaultCall',
                                    'fillTempUserListsx_infoUsers' => 'restApiDefaultCall',
       
        
         //**---- infoUsers -------------------                                        
         //** syscountrys ----------------------
                                    'pkDelete_syscountrys' => 'restApiDefaultCall',
                                    'pkGetAll_syscountrys' => 'restApiDefaultCall',
                                    'pkInsert_syscountrys' => 'restApiDefaultCall',
                                    'pkUpdate_syscountrys' => 'restApiDefaultCall',
                                    'pkFillGrid_syscountrys' => 'restApiDefaultCall',
                                    'fillComboBox_syscountrys' => 'restApiDefaultCall',
                                    'pkInsertLanguageTemplate_syscountrys' => 'restApiDefaultCall',
         //**---- syscountrys -------------------        
        //** syscity ----------------------
                                    'pkDelete_syscity' => 'restApiDefaultCall',
                                    'pkGetAll_syscity' => 'restApiDefaultCall',
                                    'pkInsert_syscity' => 'restApiDefaultCall',
                                    'pkUpdate_syscity' => 'restApiDefaultCall',
                                    'pkFillGrid_syscity' => 'restApiDefaultCall',
                                    'fillComboBox_syscity' => 'restApiDefaultCall',   
                                    'pkInsertLanguageTemplate_syscity' => 'restApiDefaultCall',  
         //**---- syscity -------------------
         //** syslanguage ----------------------
                                    'pkDelete_syslanguage' => 'restApiDefaultCall',
                                    'pkGetAll_syslanguage' => 'restApiDefaultCall',
                                    'pkInsert_syslanguage' => 'restApiDefaultCall',
                                    'pkUpdate_syslanguage' => 'restApiDefaultCall',
                                    'pkFillGrid_syslanguage' => 'restApiDefaultCall',
                                    'fillComboBox_syslanguage' => 'restApiDefaultCall', 
                                    'fillComboBoxTsql_syslanguage' => 'restApiDefaultCall', 
                                    'pkFillLanguageDdList_syslanguage' => 'restApiDefaultCall',                                    
         //**---- syslanguage -------------------
          //** sysborough ----------------------
                                    'pkDelete_sysborough' => 'restApiDefaultCall',
                                    'pkGetAll_sysborough' => 'restApiDefaultCall',
                                    'pkInsert_sysborough' => 'restApiDefaultCall',
                                    'pkUpdate_sysborough' => 'restApiDefaultCall',
                                    'pkFillGrid_sysborough' => 'restApiDefaultCall',
                                    'fillComboBox_sysborough' => 'restApiDefaultCall',  
                                    'pkInsertLanguageTemplate_sysborough' => 'restApiDefaultCall',  
         //**---- sysborough -------------------   
           //** sysvillage ----------------------
                                    'pkDelete_sysvillage' => 'restApiDefaultCall',
                                    'pkGetAll_sysvillage' => 'restApiDefaultCall',
                                    'pkInsert_sysvillage' => 'restApiDefaultCall',
                                    'pkUpdate_sysvillage' => 'restApiDefaultCall',
                                    'pkFillGrid_sysvillage' => 'restApiDefaultCall',
                                    'fillComboBox_sysvillage' => 'restApiDefaultCall',  
                                    'pkInsertLanguageTemplate_sysvillage' => 'restApiDefaultCall',                           
         //**---- sysvillage -------------------  
         //** blLoginLogout ----------------------
                                    'pkDelete_blLoginLogout' => 'restApiDefaultCall',
                                    'pkGetAll_blLoginLogout' => 'restApiDefaultCall',
                                    'pkInsert_blLoginLogout' => 'restApiDefaultCall',
                                    'pkUpdate_blLoginLogout' => 'restApiDefaultCall',
                                    'pkControl_blLoginLogout' => 'restApiDefaultCall',
                                    'pkLoginControl_blLoginLogout' => 'restApiDefaultCall',
                                    'getPK_blLoginLogout' => 'restApiDefaultCall',  
                                    'pkSessionControl_blLoginLogout' => 'restApiDefaultCall',   
                                    'pkIsThere_blLoginLogout' => 'restApiDefaultCall',
                                    'pkAllPkGeneratedFromPrivate_blLoginLogout' => 'restApiDefaultCall', 
                                    'pkLogOut_blLoginLogout' => 'restApiDefaultCall',
        
        
         //**---- blLoginLogout -------------------   
         //** sysAclRoles ----------------------
                                    'pkDelete_sysAclRoles' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclRoles' => 'restApiDefaultCall',
                                    'pkInsert_sysAclRoles' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclRoles' => 'restApiDefaultCall',
                                    'pkUpdateChild_sysAclRoles' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclRoles' => 'restApiDefaultCall',
                                    'pkFillComboBoxMainRoles_sysAclRoles' => 'restApiDefaultCall',   
                                    'pkFillFullRolesDdList_sysAclRoles' => 'restApiDefaultCall',  
                                    'pkFillComboBoxRoles_sysAclRoles' => 'restApiDefaultCall',  
                                    'pkFillRolesTree_sysAclRoles' => 'restApiDefaultCall',  
                                    'pkFillRolesPropertiesList_sysAclRoles' => 'restApiDefaultCall', 
                                    'pkUpdateMakeActiveOrPassive_sysAclRoles' => 'restApiDefaultCall',
                                    'pkFillConsultantRolesDdlist_sysAclRoles' => 'restApiDefaultCall',
                                    'pkFillClusterRolesDdlist_sysAclRoles' => 'restApiDefaultCall',
                                    'pkFillRolesDdlist_sysAclRoles' => 'restApiDefaultCall',
         //**---- sysAclRoles -------------------  
         //** sysAclResources ----------------------
                                    'pkDelete_sysAclResources' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclResources' => 'restApiDefaultCall',
                                    'pkInsert_sysAclResources' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclResources' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclResources' => 'restApiDefaultCall',
                                    'pkFillComboBoxMainResources_sysAclResources' => 'restApiDefaultCall',   
                                    'pkFillComboBoxFullResources_sysAclResources' => 'restApiDefaultCall',  
                                    'pkFillResourcesTree_sysAclResources' => 'restApiDefaultCall',
                                    'pkFillPropertieslist_sysAclResources' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysAclResources' => 'restApiDefaultCall',
                                    'pkFillResourcesDdList_sysAclResources' => 'restApiDefaultCall', 
                                    'pkFillResourceGroups_sysAclResources' => 'restApiDefaultCall', 
         //**---- sysAclResources -------------------  
       //** sysAclPrivilege ----------------------
                                    'pkDelete_sysAclPrivilege' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclPrivilege' => 'restApiDefaultCall',
                                    'pkInsert_sysAclPrivilege' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclPrivilege' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclPrivilege' => 'restApiDefaultCall',
                                    'pkFillComboBoxFullPrivilege_sysAclPrivilege' => 'restApiDefaultCall',  
                                    'pkFillPrivilegesList_sysAclPrivilege' => 'restApiDefaultCall',  
                                    'pkUpdateMakeActiveOrPassive_sysAclPrivilege' => 'restApiDefaultCall',
                                    'pkFillResourceGroups_sysAclPrivilege' => 'restApiDefaultCall',
                                    'pkFillPrivilegesOfRoles_sysAclPrivilege' => 'restApiDefaultCall',
                                    'pkFillNotInPrivilegesOfRoles_sysAclPrivilege' => 'restApiDefaultCall',
                                    'pkFillPrivilegesOfRolesDdList_sysAclPrivilege' => 'restApiDefaultCall', 
                                    'pkFillPrivilegesOfRolesList_sysAclPrivilege' => 'restApiDefaultCall',
         //**---- sysAclPrivilege ------------------- 
         //** SysAclRrp ----------------------
                                    'pkDelete_sysAclRrp' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclRrp' => 'restApiDefaultCall',
                                    'pkInsert_sysAclRrp' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclRrp' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclRrp' => 'restApiDefaultCall',
                                    'pkFillComboBoxFullPrivilege_sysAclRrp' => 'restApiDefaultCall',  
                                    'pkFillRrpList_sysAclRrp' => 'restApiDefaultCall',  
                                    'pkUpdateMakeActiveOrPassive_sysAclRrp' => 'restApiDefaultCall',
                                    'pkTransferRolesPrivilege_sysAclRrp' => 'restApiDefaultCall',
         //**---- SysAclRrp -------------------         
         //** SysAclRrpRestservices ----------------------
                                    'pkDelete_sysAclRrpRestservices' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclRrpRestservices' => 'restApiDefaultCall',
                                    'pkInsert_sysAclRrpRestservices' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclRrpRestservices' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclRrpRestservices' => 'restApiDefaultCall',
                                    'pkFillComboBoxFullPrivilege_sysAclRrpRestservices' => 'restApiDefaultCall',  
                                    'pkFillRrpRestServicesList_sysAclRrpRestservices' => 'restApiDefaultCall',  
                                    'pkFillRestServicesOfPrivileges_sysAclRrpRestservices' => 'restApiDefaultCall',
                                    'pkFillNotInRestServicesOfPrivileges_sysAclRrpRestservices' => 'restApiDefaultCall',
                                    'pkFillNotInRestServicesOfPrivilegesTree_sysAclRrpRestservices' => 'restApiDefaultCall',
                                    'pkFillRestServicesOfPrivilegesTree_sysAclRrpRestservices' => 'restApiDefaultCall',
         //**---- SysAclRrpRestservices -------------------  
            //** SysAclRrpMap ----------------------
                                    'pkDelete_sysAclRrpMap' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclRrpMap' => 'restApiDefaultCall',
                                    'pkInsert_sysAclRrpMap' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclRrpMap' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclRrpMap' => 'restApiDefaultCall',
                                    'pkFillRrpMap_sysAclRrpMap' => 'restApiDefaultCall',
         //**---- SysAclRrpMap ------------------- 
         //** SysAclModules ----------------------
                                    'pkDelete_sysAclModules' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclModules' => 'restApiDefaultCall',
                                    'pkInsert_sysAclModules' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclModules' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclModules' => 'restApiDefaultCall',                                     
                                    'pkFillComboBoxFullModules_sysAclModules' => 'restApiDefaultCall',  
                                    'pkFillModulesTree_sysAclModules' => 'restApiDefaultCall',
                                    'pkFillModulesList_sysAclModules' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysAclModules' => 'restApiDefaultCall',
                                    'pkFillModulesDdList_sysAclModules' => 'restApiDefaultCall',
         //**---- SysAclModules -------------------  
         //** SysAclActions ----------------------
                                    'pkDelete_sysAclActions' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclActions' => 'restApiDefaultCall',
                                    'pkInsert_sysAclActions' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclActions' => 'restApiDefaultCall',
                                    'pkUpdateAct_sysAclActions' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclActions' => 'restApiDefaultCall',                                     
                                    'pkFillComboBoxFullAction_sysAclActions' => 'restApiDefaultCall',  
                                    'pkFillActionTree_sysAclActions' => 'restApiDefaultCall',
                                    'pkFillActionList_sysAclActions' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysAclActions' => 'restApiDefaultCall',
                                    'pkFillActionDdList_sysAclActions' => 'restApiDefaultCall', 
         //**---- SysAclActions -------------------  
          //** SysAclMenuTypesActions ----------------------
                                    'pkDelete_sysAclMenuTypesActions' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclMenuTypesActions' => 'restApiDefaultCall',
                                    'pkInsert_sysAclMenuTypesActions' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclMenuTypesActions' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclMenuTypesActions' => 'restApiDefaultCall', 
                                    
                                    'pkFillMenuTypesActionList_sysAclMenuTypesActions' => 'restApiDefaultCall',  
                                    'pkFillMenuTypesActionLeftList_sysAclMenuTypesActions' => 'restApiDefaultCall',                                    
                                    'pkUpdateMakeActiveOrPassive_sysAclMenuTypesActions' => 'restApiDefaultCall',
         //**---- SysAclMenuTypesActions -------------------  
         //** sysSpecificDefinitions ----------------------
                                    'fillMainDefinitions_sysSpecificDefinitions' => 'restApiDefaultCall',
                                    'fillFullDefinitions_sysSpecificDefinitions' => 'restApiDefaultCall',
                                    'fillCommunicationsTypes_sysSpecificDefinitions' => 'restApiDefaultCall',
                                    'fillBuildingType_sysSpecificDefinitions' => 'restApiDefaultCall',
                                    'fillOwnershipType_sysSpecificDefinitions' => 'restApiDefaultCall',
                                    'fillPersonnelTypes_sysSpecificDefinitions' => 'restApiDefaultCall',
                                    'fillAddressTypes_sysSpecificDefinitions' => 'restApiDefaultCall',
                                    'fillSexTypes_sysSpecificDefinitions' => 'restApiDefaultCall',
                                    'fillSubjectsTypes_sysSpecificDefinitions' => 'restApiDefaultCall',
                                    'fillDifficulty_sysSpecificDefinitions' => 'restApiDefaultCall', 
                                    'fillQuestionTime_sysSpecificDefinitions' => 'restApiDefaultCall', 
                                    'fillSubjectTypes_sysSpecificDefinitions' => 'restApiDefaultCall', 
                                    'fillAnswerTypes_sysSpecificDefinitions' => 'restApiDefaultCall', 
                                    'fillQuestionSourceType_sysSpecificDefinitions' => 'restApiDefaultCall', 
                                    'fillEducationType_sysSpecificDefinitions' => 'restApiDefaultCall', 
                                    'getserverkontrol_sysSpecificDefinitions' => 'restApiDefaultCall', 
        
        
        //**---- sysSpecificDefinitions -------------------    
         //** infoUsersCommunications ----------------------
                                    'pkDelete_infoUsersCommunications' => 'restApiDefaultCall',
                                    'pkGetAll_infoUsersCommunications' => 'restApiDefaultCall',
                                    'pkInsert_infoUsersCommunications' => 'restApiDefaultCall',
                                    'pkUpdate_infoUsersCommunications' => 'restApiDefaultCall',
                                    'pkFillGrid_infoUsersCommunications' => 'restApiDefaultCall',
                                    'pkDeletedAct_infoUsersCommunications' => 'restApiDefaultCall',
                                    'fillUserCommunicationsTypes_infoUsersCommunications' => 'restApiDefaultCall',
                                    'fillGridSingular_infoUsersCommunications' => 'restApiDefaultCall',                                   
                                    'pktempInsert_infoUsersCommunications' => 'restApiDefaultCall',
                                    'pktempUpdate_infoUsersCommunications' => 'restApiDefaultCall',
                                    'pktempDeletedAct_infoUsersCommunications' => 'restApiDefaultCall',
                                    'pktempFillUserCommunicationsTypes_infoUsersCommunications' => 'restApiDefaultCall',
                                    'pktempFillGridSingular_infoUsersCommunications' => 'restApiDefaultCall',
                                    'pktempFillGridSingularRowTotalCount_infoUsersCommunications' => 'restApiDefaultCall',
         //**---- infoUsersCommunications -------------------        
        //** InfoUsersAddresses ----------------------
                                    'pkDelete_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pkGetAll_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pkInsert_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pkUpdate_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pkFillGrid_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pkDeletedAct_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pkFillUserAddressesTypes_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pkFillGridSingular_infoUsersAddresses' => 'restApiDefaultCall',                                           
                                    'pktempInsert_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pktempUpdate_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pktempDeletedAct_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pktempFillUserAddressesTypes_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pktempFillGridSingular_infoUsersAddresses' => 'restApiDefaultCall',
                                    'pktempFillGridSingularRowTotalCount_infoUsersAddresses' => 'restApiDefaultCall',  
         //**---- InfoUsersAddresses -------------------
         //** BlActivationReport ----------------------
                                    'pkDelete_blActivationReport' => 'restApiDefaultCall',
                                    'pkGetAll_blActivationReport' => 'restApiDefaultCall',
                                    'pkInsert_blActivationReport' => 'restApiDefaultCall',
                                    'pkUpdate_blActivationReport' => 'restApiDefaultCall',        
                                    
                                    'pkGetConsultantOperation_blActivationReport' => 'restApiDefaultCall',
                                    'getAllFirmCount_blActivationReport' => 'restApiDefaultCall',
                                    'pkGetConsultantFirmCount_blActivationReport' => 'restApiDefaultCall',
                                    'pkGetConsultantUpDashBoardCount_blActivationReport' => 'restApiDefaultCall',
                                    'pkGetConsWaitingForConfirm_blActivationReport' => 'restApiDefaultCall',        
                                    'pkGetUrgeUpDashBoardCount_blActivationReport' => 'restApiDefaultCall',                                     
                                    'pkFillUrgeOrganizations_blActivationReport' => 'restApiDefaultCall', 
                                    'pkGetUrgeUpFirstDashBoardCount_blActivationReport' => 'restApiDefaultCall',         
        
        
        
         //**---- BlActivationReport -------------------  
         //** sysOperationTypes ----------------------
                                    'pkDelete_sysOperationTypes' => 'restApiDefaultCall',
                                    'pkGetAll_sysOperationTypes' => 'restApiDefaultCall',
                                    'pkInsert_sysOperationTypes' => 'restApiDefaultCall',
                                    'pkUpdate_sysOperationTypes' => 'restApiDefaultCall',
                                    'pkFillGrid_sysOperationTypes' => 'restApiDefaultCall', 
                                    'pkFillConsultantOperationsDropDown_sysOperationTypes' => 'restApiDefaultCall',
         //**---- sysOperationTypes -------------------           
        //** sysOperationTypesTools ----------------------
                                    'pkDelete_sysOperationTypesTools' => 'restApiDefaultCall',
                                    'pkGetAll_sysOperationTypesTools' => 'restApiDefaultCall',
                                    'pkInsert_sysOperationTypesTools' => 'restApiDefaultCall',
                                    'pkUpdate_sysOperationTypesTools' => 'restApiDefaultCall',
                                    'pkFillGrid_sysOperationTypesTools' => 'restApiDefaultCall',
                                    'pkFillConsultantOperationsToolsDropDown_sysOperationTypesTools' => 'restApiDefaultCall',
         //**---- sysOperationTypesTools -------------------                  
        //** InfoError ----------------------
                                    'pkDelete_infoError' => 'restApiDefaultCall',
                                    'pkGetAll_infoError' => 'restApiDefaultCall',
                                    'pkInsert_infoError' => 'restApiDefaultCall',
                                    'pkUpdate_infoError' => 'restApiDefaultCall',
                                    'pkFillGrid_infoError' => 'restApiDefaultCall',    
         //**---- InfoError -------------------   
        //** SysUnits ----------------------
                                    'pkDelete_sysUnits' => 'restApiDefaultCall',
                                    'pkInsert_sysUnits' => 'restApiDefaultCall',
                                    'pkUpdate_sysUnits' => 'restApiDefaultCall',
                                    'pkGetAll_sysUnits' => 'restApiDefaultCall',
                                    'pkFillGrid_sysUnits' => 'restApiDefaultCall',
                                    'pkGetUnits_sysUnits' => 'restApiDefaultCall',
                                    'pkFillUnitsTree_sysUnits' => 'restApiDefaultCall',   
                                    'pkUpdateMakeActiveOrPassive_sysUnits' => 'restApiDefaultCall',  
         //**---- SysUnits -------------------            
        //** sysUnitSystems ----------------------
                                    'pkDelete_sysUnitSystems' => 'restApiDefaultCall',
                                    'pkInsert_sysUnitSystems' => 'restApiDefaultCall',
                                    'pkUpdate_sysUnitSystems' => 'restApiDefaultCall',
                                    'pkGetAll_sysUnitSystems' => 'restApiDefaultCall',
                                    'pkFillGrid_sysUnitSystems' => 'restApiDefaultCall',
                                    'pkGetUnitSystems_sysUnitSystems' => 'restApiDefaultCall',
                                    'pkFillUnitSystemsTree_sysUnitSystems' => 'restApiDefaultCall',  
         //**---- sysUnitSystems -------------------
        //** HstryLogin ----------------------                                    
                                    'pkGetAll_hstryLogin' => 'restApiDefaultCall',
                                    'pkInsert_hstryLogin' => 'restApiDefaultCall',
                                    'pkFillGrid_hstryLogin' => 'restApiDefaultCall',
        //**---- HstryLogin -------------------   
         //** BlAdminActivationReport ----------------------                                  
                                    'pkGetAll_blAdminActivationReport' => 'restApiDefaultCall',
                                    'pkInsert_blAdminActivationReport' => 'restApiDefaultCall',
                                    'pkGetConsultantOperation_blAdminActivationReport' => 'restApiDefaultCall',
                                    'pkGetAllConsultantFirmCount_blAdminActivationReport' => 'restApiDefaultCall',
                                    'pkGetUpDashBoardCount_blAdminActivationReport' => 'restApiDefaultCall',
                                    'pkGetDashBoardHighCharts_blAdminActivationReport' => 'restApiDefaultCall',
         //**---- BlAdminActivationReport -------------------          
       //** LogConnection ----------------------                                  
                                    'pkGetAll_logConnection' => 'restApiDefaultCall',
                                    'pkInsert_logConnection' => 'restApiDefaultCall',
                                    'pkFillGrid_logConnection' => 'restApiDefaultCall',
         //**---- LogConnection -------------------   
         //** logServices ----------------------                                   
                                    'pkGetAll_logServices' => 'restApiDefaultCall',
                                    'pkInsert_logServices' => 'restApiDefaultCall',
                                    'pkFillGrid_logServices' => 'restApiDefaultCall',
        //**---- logServices -------------------
        //** LogAdmin ----------------------                                  
                                    'pkGetAll_logAdmin' => 'restApiDefaultCall',
                                    'pkInsert_logAdmin' => 'restApiDefaultCall',
                                    'pkFillGrid_logAdmin' => 'restApiDefaultCall',
         //**---- LogAdmin ------------------- 
        //** SysCertifications ----------------------
                                    'pkDelete_sysCertifications' => 'restApiDefaultCall',
                                    'pkGetAll_sysCertifications' => 'restApiDefaultCall',
                                    'pkInsert_sysCertifications' => 'restApiDefaultCall',
                                    'pkUpdate_sysCertifications' => 'restApiDefaultCall',
                                    'pkFillGrid_sysCertifications' => 'restApiDefaultCall',
                                    'pkFillCertificationsDdList_sysCertifications' => 'restApiDefaultCall',
        //**---- SysCertifications -------------------         
        //** InfoUsersSocialmedia ----------------------
                                    'pkDelete_infoUsersSocialmedia' => 'restApiDefaultCall',
                                    'pkGetAll_infoUsersSocialmedia' => 'restApiDefaultCall',
                                    'pkInsert_infoUsersSocialmedia' => 'restApiDefaultCall',
                                    'pkUpdate_infoUsersSocialmedia' => 'restApiDefaultCall',
                                    'pkFillGrid_infoUsersSocialmedia' => 'restApiDefaultCall',
                                    
                                    'pkFillSingularUsersSocialMedia_infoUsersSocialmedia' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_infoUsersSocialmedia' => 'restApiDefaultCall',                                   
                                    'pkDeletedAct_infoUsersSocialmedia' => 'restApiDefaultCall',
                                    'pkFillCompanyUsersSocialMediaNpk_infoUsersSocialmedia' => 'restApiDefaultCall',
        //**---- infoUsersSocialmedia ------------------- 
        //** SysSocialMedia ----------------------
                                    'pkDelete_sysSocialMedia' => 'restApiDefaultCall',
                                    'pkGetAll_sysSocialMedia' => 'restApiDefaultCall',
                                    'pkInsert_sysSocialMedia' => 'restApiDefaultCall',
                                    'pkUpdate_sysSocialMedia' => 'restApiDefaultCall',
                                    'pkFillGrid_sysSocialMedia' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysSocialMedia' => 'restApiDefaultCall',
                                    'pkFillSocicalMediaDdList_sysSocialMedia' => 'restApiDefaultCall', 
        //**---- SysSocialMedia ------------------- 
        //** SysUniversities ----------------------
                                    'pkDelete_sysUniversities' => 'restApiDefaultCall',
                                    'pkGetAll_sysUniversities' => 'restApiDefaultCall',
                                    'pkInsert_sysUniversities' => 'restApiDefaultCall',
                                    'pkUpdate_sysUniversities' => 'restApiDefaultCall',
                                    'pkFillGrid_sysUniversities' => 'restApiDefaultCall',                                    
                                    'pkUpdateMakeActiveOrPassive_sysUniversities' => 'restApiDefaultCall',
                                    'pkFillUniversityDdList_sysUniversities' => 'restApiDefaultCall', 
        //**---- SysUniversities ------------------- 
         //** SysMailServer ----------------------
                                    'pkDelete_sysMailServer' => 'restApiDefaultCall',
                                    'pkGetAll_sysMailServer' => 'restApiDefaultCall',
                                    'pkInsert_sysMailServer' => 'restApiDefaultCall',
                                    'pkUpdate_sysMailServer' => 'restApiDefaultCall',
                                    'pkFillGrid_sysMailServer' => 'restApiDefaultCall',                                    
                                    'pkUpdateMakeActiveOrPassive_sysMailServer' => 'restApiDefaultCall',
                                    'pkFillMailServerList_sysMailServer' => 'restApiDefaultCall', 
        //**---- SysMailServer -------------------                  
        //** InfoUsersVerbal ----------------------
                                    'pkDelete_infoUsersVerbal' => 'restApiDefaultCall',
                                    'pkGetAll_infoUsersVerbal' => 'restApiDefaultCall',
                                    'pkInsert_infoUsersVerbal' => 'restApiDefaultCall',
                                    'pkUpdate_infoUsersVerbal' => 'restApiDefaultCall',
                                    'pkFillGrid_infoUsersVerbal' => 'restApiDefaultCall',                                    
                                    'pkDeletedAct_infoUsersVerbal' => 'restApiDefaultCall',
                                    'pkFillUsersVerbalNpk_infoUsersVerbal' => 'restApiDefaultCall',
                                    'fillUsersVerbalNpkGuest_infoUsersVerbal' => 'restApiDefaultCall',
                                    'pkGetUserVerbalConsultant_infoUsersVerbal' => 'restApiDefaultCall',
        //**---- InfoUsersVerbal -------------------         
         //** SysMembershipTypes ----------------------
                                    'pkDelete_sysMembershipTypes' => 'restApiDefaultCall',
                                    'pkGetAll_sysMembershipTypes' => 'restApiDefaultCall',
                                    'pkInsert_sysMembershipTypes' => 'restApiDefaultCall',
                                    'pkUpdate_sysMembershipTypes' => 'restApiDefaultCall',        
                                    'pkFillClustersAllianceLists_sysMembershipTypes' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysMembershipTypes' => 'restApiDefaultCall',
                                    'pkFillMemberShipList_sysMembershipTypes' => 'restApiDefaultCall',
                                    'fillMemberShipList_sysMembershipTypes' => 'restApiDefaultCall',
         //**---- SysMembershipTypes -------------------         
          //** SysMenuTypes ----------------------
                                    'pkDelete_sysMenuTypes' => 'restApiDefaultCall',
                                    'pkGetAll_sysMenuTypes' => 'restApiDefaultCall',
                                    'pkInsert_sysMenuTypes' => 'restApiDefaultCall',
                                    'pkUpdate_sysMenuTypes' => 'restApiDefaultCall',        
                                    'pkFillClustersAllianceLists_sysMenuTypes' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysMenuTypes' => 'restApiDefaultCall',
                                    'pkFillMemberShipList_sysMenuTypes' => 'restApiDefaultCall',
                                    'pkFillMenuTypeList_sysMenuTypes' => 'restApiDefaultCall',
                                    'pkFillMenuTypeListGrid_sysMenuTypes' => 'restApiDefaultCall',
         //**---- SysMenuTypes -------------------         
         //** SysServicesGroups ----------------------
                                    'pkDelete_sysServicesGroups' => 'restApiDefaultCall',
                                    'pkGetAll_sysServicesGroups' => 'restApiDefaultCall',
                                    'pkInsert_sysServicesGroups' => 'restApiDefaultCall',
                                    'pkUpdate_sysServicesGroups' => 'restApiDefaultCall',
                                    'pkFillGrid_sysServicesGroups' => 'restApiDefaultCall',
                                    'pkDeleteAct_sysServicesGroups' => 'restApiDefaultCall',                                    
                                    'pkUpdateMakeActiveOrPassive_sysAclResources' => 'restApiDefaultCall',
                                    'pkFillServicesGroupsDdList_sysServicesGroups' => 'restApiDefaultCall', 
                                    'pkFillServicesGroupsList_sysServicesGroups' => 'restApiDefaultCall', 
                                    'pkUpdateMakeActiveOrPassive_sysServicesGroups' => 'restApiDefaultCall',
         //**---- SysServicesGroups -------------------           
         //** SysAclRestservices ----------------------
                                    'pkDelete_sysAclRestservices' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclRestservices' => 'restApiDefaultCall',
                                    'pkInsert_sysAclRestservices' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclRestservices' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclRestservices' => 'restApiDefaultCall',                 
                                    'pkDeleteAct_sysAclRestservices' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysAclRestservices' => 'restApiDefaultCall',
                                    'pkFillRestServicesList_sysAclRestservices' => 'restApiDefaultCall', 
         //**---- SysAclRestservices -------------------  
         //** SysAssignDefinition ----------------------
                                    'pkDelete_sysAssignDefinition' => 'restApiDefaultCall',
                                    'pkGetAll_sysAssignDefinition' => 'restApiDefaultCall',
                                    'pkInsert_sysAssignDefinition' => 'restApiDefaultCall',
                                    'pkUpdate_sysAssignDefinition' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAssignDefinition' => 'restApiDefaultCall',                 
                                    'pkDeleteAct_sysAssignDefinition' => 'restApiDefaultCall',
                                    'pkUpdateMakeActiveOrPassive_sysAssignDefinition' => 'restApiDefaultCall',
                                    'pkFillAssignDefinitionList_sysAssignDefinition' => 'restApiDefaultCall', 
                                    'pkFillAssignDefinitionDdList_sysAssignDefinition' => 'restApiDefaultCall', 
         //**---- SysAssignDefinition -------------------  
         //** SysAssignDefinitionRoles ----------------------
                                    'pkDelete_sysAssignDefinitionRoles' => 'restApiDefaultCall',
                                    'pkGetAll_sysAssignDefinitionRoles' => 'restApiDefaultCall',
                                    'pkInsert_sysAssignDefinitionRoles' => 'restApiDefaultCall',
                                    'pkUpdate_sysAssignDefinitionRoles' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAssignDefinitionRoles' => 'restApiDefaultCall',                 
                                    'pkUpdateMakeActiveOrPassive_sysAssignDefinitionRoles' => 'restApiDefaultCall',
                                    'pkFillAssignDefinitionRolesList_sysAssignDefinitionRoles' => 'restApiDefaultCall', 
                                    'pkFillAssignDefinitionRolesDdList_sysAssignDefinitionRoles' => 'restApiDefaultCall', 
                                    'pkFillConsultantRolesTree_sysAssignDefinitionRoles' => 'restApiDefaultCall', 
                                    'pkFillAssignDefinitionOfRoles_sysAssignDefinitionRoles' => 'restApiDefaultCall', 
                                    'pkFillNotInAssignDefinitionOfRoles_sysAssignDefinitionRoles' => 'restApiDefaultCall', 
         //**---- SysAssignDefinitionRoles -------------------           
        //** PgClass ----------------------
                                    'pkDelete_pgClass' => 'restApiDefaultCall',
                                    'pkGetAll_pgClass' => 'restApiDefaultCall',
                                    'pkInsert_pgClass' => 'restApiDefaultCall',
                                    'pkUpdate_pgClass' => 'restApiDefaultCall',
                                    'pkFillGrid_pgClass' => 'restApiDefaultCall',
                                    'pkFillInfoTablesDdList_pgClass' => 'restApiDefaultCall', 
         //**---- PgClass -------------------  
        //** SysOperationTypesRrp ----------------------
                                    'pkDelete_sysOperationTypesRrp' => 'restApiDefaultCall',
                                    'pkGetAll_sysOperationTypesRrp' => 'restApiDefaultCall',
                                    'pkInsert_sysOperationTypesRrp' => 'restApiDefaultCall',
                                    'pkUpdate_sysOperationTypesRrp' => 'restApiDefaultCall',
                                    'pkFillGrid_sysOperationTypesRrp' => 'restApiDefaultCall',                 
                                    'pkFillConsultantOperationsRrpList_sysOperationTypesRrp' => 'restApiDefaultCall', 
                                    'pkFillOperationTypesRrpList_sysOperationTypesRrp' => 'restApiDefaultCall', 
                                    'pkUpdateMakeActiveOrPassive_sysOperationTypesRrp' => 'restApiDefaultCall',
         //**---- SysOperationTypesRrp -------------------  
         //** SysAclActionRrp ----------------------
                                    'pkDelete_sysAclActionRrp' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclActionRrp' => 'restApiDefaultCall',
                                    'pkInsert_sysAclActionRrp' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclActionRrp' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclActionRrp' => 'restApiDefaultCall',
                                    'pkFillComboBoxFullPrivilege_sysAclActionRrp' => 'restApiDefaultCall',  
                                    'pkFillActionPrivilegesList_sysAclActionRrp' => 'restApiDefaultCall',  
                                    'pkUpdateMakeActiveOrPassive_sysAclActionRrp' => 'restApiDefaultCall',
                                    'pkFillActionResourceGroups_sysAclActionRrp' => 'restApiDefaultCall',
                                    'pkFillActionPrivilegesOfRoles_sysAclActionRrp' => 'restApiDefaultCall',
                                    'pkFillNotInActionPrivilegesOfRoles_sysAclActionRrp' => 'restApiDefaultCall',
                                    'pkFillActionPrivilegesOfRolesDdList_sysAclActionRrp' => 'restApiDefaultCall', 
                                    'pkFillActionPrivilegesOfRolesList_sysAclActionRrp' => 'restApiDefaultCall',
                                    'pkTransferRolesActionPrivilege_sysAclActionRrp' => 'restApiDefaultCall',
         //**---- SysAclActionRrp -------------------          
        //** SysAclActionRrpRestservices ----------------------
                                    'pkDelete_sysAclActionRrpRestservices' => 'restApiDefaultCall',
                                    'pkGetAll_sysAclActionRrpRestservices' => 'restApiDefaultCall',
                                    'pkInsert_sysAclActionRrpRestservices' => 'restApiDefaultCall',
                                    'pkUpdate_sysAclActionRrpRestservices' => 'restApiDefaultCall',
                                    'pkFillGrid_sysAclActionRrpRestservices' => 'restApiDefaultCall',
                                    'pkFillActionRrpRestServicesList_sysAclActionRrpRestservices' => 'restApiDefaultCall',  
                                    'pkFillActionRestServicesOfPrivileges_sysAclActionRrpRestservices' => 'restApiDefaultCall',
                                    'pkFillNotInActionRestServicesOfPrivileges_sysAclActionRrpRestservices' => 'restApiDefaultCall',
                                    'pkFillNotInActionRestServicesOfPrivilegesTree_sysAclActionRrpRestservices' => 'restApiDefaultCall',
                                    'pkFillActionRestServicesOfPrivilegesTree_sysAclActionRrpRestservices' => 'restApiDefaultCall',
         //**---- SysAclActionRrpRestservices -------------------                  
         //** InfoUsersSendingMail ----------------------
                                    'pkDelete_infoUsersSendingMail' => 'restApiDefaultCall',
                                    'pkGetAll_infoUsersSendingMail' => 'restApiDefaultCall',
                                    'pkInsert_infoUsersSendingMail' => 'restApiDefaultCall',
                                    'pkUpdate_infoUsersSendingMail' => 'restApiDefaultCall',
                                    'pkFillGrid_infoUsersSendingMail' => 'restApiDefaultCall',
                                    'sendMailTempUserRegistration_infoUsersSendingMail' => 'restApiDefaultCall',
         //**---- InfoUsersSendingMail ------------------- 
         //** ActProcessConfirm ----------------------
                                    'pkDelete_actProcessConfirm' => 'restApiDefaultCall',
                                    'pkGetAll_actProcessConfirm' => 'restApiDefaultCall',
                                    'pkInsert_actProcessConfirm' => 'restApiDefaultCall',
                                    'pkUpdate_actProcessConfirm' => 'restApiDefaultCall',
                                    'pkFillGrid_actProcessConfirm' => 'restApiDefaultCall',
                                    'pkGetConsultantJobs_actProcessConfirm' => 'restApiDefaultCall',
     
         //**---- ActProcessConfirm -------------------    
         //** syssectors ----------------------
                                    'pkDelete_sysSectors' => 'restApiDefaultCall',                                    
                                    'pkInsert_sysSectors' => 'restApiDefaultCall',
                                    'pkUpdate_sysSectors' => 'restApiDefaultCall',                                    
                                    'fillComboBox_sysSectors' => 'restApiDefaultCall',                                    
                                    'pkGetSectors_sysSectors' => 'restApiDefaultCall',
                                    'getSectors_sysSectors' => 'restApiDefaultCall',
           
         //**---- syssectors -------------------
         //** SysCorporation ----------------------
                                    'pkDelete_sysCorporation' => 'restApiDefaultCall',                                    
                                    'pkInsert_sysCorporation' => 'restApiDefaultCall',
                                    'pkUpdate_sysCorporation' => 'restApiDefaultCall',                                    
                                    'fillCorporationsTree_sysCorporation' => 'restApiDefaultCall',                                    
                                    'pkUpdateMakeActiveOrPassive_sysCorporation' => 'restApiDefaultCall', 
         //**---- SysCorporation -------------------
         //** SysSubjects ----------------------
                                    'pkDelete_sysSubjects' => 'restApiDefaultCall',                                    
                                    'pkInsert_sysSubjects' => 'restApiDefaultCall',
                                    'pkUpdate_sysSubjects' => 'restApiDefaultCall',                                    
                                    'fillSubjectsTree_sysSubjects' => 'restApiDefaultCall',                                    
                                    'pkUpdateMakeActiveOrPassive_sysSubjects' => 'restApiDefaultCall', 
                                    'fillBaseTree_sysSubjects' => 'restApiDefaultCall',
        
        
         //**---- SysSubjects -------------------
         //** SysLaws ----------------------
                                    'pkDelete_sysLaws' => 'restApiDefaultCall',                                    
                                    'pkInsert_sysLaws' => 'restApiDefaultCall',
                                    'pkUpdate_sysLaws' => 'restApiDefaultCall',                                    
                                    'fillLawsTree_sysLaws' => 'restApiDefaultCall',                                    
                                    'pkUpdateMakeActiveOrPassive_sysLaws' => 'restApiDefaultCall', 
         //**---- SysLaws -------------------
         
        //** InfoExams ----------------------
                                    'pkDelete_infoExams' => 'restApiDefaultCall',                                    
                                    'pkInsert_infoExams' => 'restApiDefaultCall',
                                    'pkUpdate_infoExams' => 'restApiDefaultCall',                                    
                                    'fillExamsTree_infoExams' => 'restApiDefaultCall',                                    
                                    'pkUpdateMakeActiveOrPassive_infoExams' => 'restApiDefaultCall', 
                                    'fillAllExamLists_infoExams' => 'restApiDefaultCall',
                                    'pkFillCorporationOfExam_infoExams' => 'restApiDefaultCall', 
                                    'pkFillNotInCorporationOfExam_infoExams' => 'restApiDefaultCall',
                                    'pkFillExamsTree_infoExams' => 'restApiDefaultCall',
        
         //**---- InfoExams -------------------
        //** InfoQuestions ----------------------
                                    'pkDelete_infoQuestions' => 'restApiDefaultCall',                                    
                                    'pkInsert_infoQuestions' => 'restApiDefaultCall',
                                    'pkInsertPassive_infoQuestions' => 'restApiDefaultCall',
        
                                    'pkUpdate_infoQuestions' => 'restApiDefaultCall', 
                                    'pkUpdateMakeActiveOrPassive_infoQuestions' => 'restApiDefaultCall', 
                                    'fillAllQuestionLists_infoQuestions' => 'restApiDefaultCall',  
                                    'pkFillAllQuestionLists_infoQuestions' => 'restApiDefaultCall',  
                                    'fillBaseOfQuestion_infoQuestions' => 'restApiDefaultCall',  
                                    'pkFillQuestionInfo_infoQuestions' => 'restApiDefaultCall',
         
         //**---- InfoQuestions -------------------
        //** InfoQuestionsSubjects ----------------------
                                    'pkDelete_infoQuestionsSubjects' => 'restApiDefaultCall',                                    
                                    'pkInsert_infoQuestionsSubjects' => 'restApiDefaultCall',
                                    'pkUpdate_infoQuestionsSubjects' => 'restApiDefaultCall', 
                                    'pkUpdateMakeActiveOrPassive_infoQuestionsSubjects' => 'restApiDefaultCall', 
                                    'fillSubjectOfQuestion_infoQuestionsSubjects' => 'restApiDefaultCall',  
                                    'pkTransferSubjectOfQuestion_infoQuestionsSubjects' => 'restApiDefaultCall',  
                                    'pkDeleteAction_infoQuestionsSubjects' => 'restApiDefaultCall', 
        
         //**---- InfoQuestionsSubjects -------------------
        //** InfoQuestionAnswers ----------------------
                                    'pkDelete_infoQuestionAnswers' => 'restApiDefaultCall',                                    
                                    'pkInsert_infoQuestionAnswers' => 'restApiDefaultCall',
                                    'pkUpdate_infoQuestionAnswers' => 'restApiDefaultCall', 
                                    'pkUpdateMakeActiveOrPassive_infoQuestionAnswers' => 'restApiDefaultCall', 
                                    'fillAnswerOfQuestionTree_infoQuestionAnswers' => 'restApiDefaultCall',  
                                    'pkUpdateCorrect_infoQuestionAnswers' => 'restApiDefaultCall',  
        
         //**---- InfoQuestionAnswers -------------------
          //** InfoExamQuestion ----------------------
                                    'pkDelete_infoExamQuestion' => 'restApiDefaultCall',                                    
                                    'pkInsert_infoExamQuestion' => 'restApiDefaultCall',
                                    'pkUpdate_infoExamQuestion' => 'restApiDefaultCall', 
                                    'pkUpdateMakeActiveOrPassive_infoExamQuestion' => 'restApiDefaultCall', 
                                    'fillQuestionOfExamTree_infoExamQuestion' => 'restApiDefaultCall',  
                                    'pkFillNotInQuestionOfExamLists_infoExamQuestion' => 'restApiDefaultCall',  
                                    'pkFillQuestionOfExamLists_infoExamQuestion' => 'restApiDefaultCall',
                                    'pkFillFullQuestionOfExamLists_infoExamQuestion' => 'restApiDefaultCall',
        
        
         //**---- InfoExamQuestion -------------------
          //** InfoExamSubjects ----------------------
                                    'pkDelete_infoExamSubjects' => 'restApiDefaultCall',                                    
                                    'pkInsert_infoExamSubjects' => 'restApiDefaultCall',
                                    'pkUpdate_infoExamSubjects' => 'restApiDefaultCall', 
                                    'pkUpdateMakeActiveOrPassive_infoExamSubjects' => 'restApiDefaultCall', 
                                    'fillSubjectOfExam_infoExamSubjects' => 'restApiDefaultCall',  
                                    'fillNotInSubjectOfExam_infoExamSubjects' => 'restApiDefaultCall',  
                                    'pkTransferSubjectOfExam_infoExamSubjects' => 'restApiDefaultCall',  
                                    'pkDeleteAction_infoExamSubjects' => 'restApiDefaultCall', 
         //**---- InfoExamSubjects -------------------
        //** InfoQuestionSources ----------------------
                                    'pkDelete_infoQuestionSources' => 'restApiDefaultCall',                                    
                                    'pkInsert_infoQuestionSources' => 'restApiDefaultCall',
                                    'pkUpdate_infoQuestionSources' => 'restApiDefaultCall', 
                                    'pkUpdateMakeActiveOrPassive_infoQuestionSources' => 'restApiDefaultCall', 
                                    'fillSourceOfQuestionTree_infoQuestionSources' => 'restApiDefaultCall',  
                                    'fillSourceOfQuestion_infoQuestionSources' => 'restApiDefaultCall',  

        
         //**---- InfoQuestionSources -------------------
        
        
        
        //** SysParams ----------------------
                                    'pkDelete_sysParams' => 'restApiDefaultCall',                                    
                                    'pkInsert_sysParams' => 'restApiDefaultCall',
                                    'pkUpdate_sysParams' => 'restApiDefaultCall', 
                                    'pkUpdateMakeActiveOrPassive_sysParams' => 'restApiDefaultCall', 
                                    'fillSysParams_sysParams' => 'restApiDefaultCall',  
                                     
         //**---- SysParams -------------------
        
        
        
        
        
    );

    /**
     * hmac object
     * @var \vendor\hmac\Hmac
     */
    protected $hmacObj;
    
    /**
     * Dal object
     * @var \vendor\dal\Dal
     */
    protected $dalObject;

    /**
     * constructor 
     */
    public function __construct() {
        
        parent::__construct();
        $this->hmacObj = new \Hmac\Hmac();
        $this->dalObject = new \Dal\Dal();
    }

    /**
     * redirect to the service endpoint
     */
    public function redirect() {
        try { 
            $execFunction = $this->resolveRedirectMap();
            $this->setEndPointByClosure();
            echo $this->$execFunction();
        } catch (\Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
     /**
      * 
      * @return boolean
      * @author Mustafa Zeynel Dağlı
      * @since version 0.3
      */
     public function servicePkRequired() { 
         if($this->isServicePkRequired == null) {
             $params = $this->getRequestParams();
             if(substr(trim($params['url']),0,2) == 'pk' && 
                     substr(trim($params['url']),0,6) != 'pktemp') {
                $this->isServicePkRequired = true;
                return $this->isServicePkRequired ;
             }
             $this->isServicePkRequired = false;
             $this->isServicePkRequired;
         } else {
             return $this->isServicePkRequired;
         }
     }
       
    /**
     * Rest api 'GET' call (Curl lib)
     * function overriden
     * @author Mustafa Zeynel Dağlı
     * @version 0.2
     * @todo conside check if request is ssl encypted (https)
     */
    public function restApiDefaultCall() { 
    
        /* $encrypt = new \vendor\Encrypt\EncryptManual('test');
          $encryptValue = $encrypt->encrypt_times(4, 'kullanici:sifre');
          //print_r('--'.$encryptValue.'--');
          $decryptValue = $encrypt->decrypt_times(4, $encryptValue);
          //print_r('??'.$decryptValue.'??'); */
        $this->servicePkRequired();
        $this->servicePkTempRequired();
        $this->serviceCpkRequired();
        $this->setEncryptClass();
        $params = null;
        $params = $this->getRequestParams();
  
        /**
         * controlling public key if public key is necessary for this service and
         * public key not found forwarder is in effect then making forward
         * @since version 0.3 06/01/2016
         */
        if(!isset($params['pk']) || $params['pk']==null) $this->publicKeyNotFoundRedirect();
        
        /**
         * controlling public temp key if public key is necessary for this service and
         * public temp key not found forwarder is in effect then making forward
         * @since version 0.3 27/01/2016
         */
        if(!isset($params['pktemp']) || $params['pktemp']==null) $this->publicKeyTempNotFoundRedirect();
        
        /**
         * controlling company public key if company public key is necessary for this service and
         * public temp key not found forwarder is in effect then making forward
         * @since version 0.3 27/01/2016
         */
        if(!isset($params['cpk']) || $params['cpk']==null) $this->publicKeyCompanyNotFoundRedirect();
        
        /**
         * getting public key if user registered    
         * @author Mustafa Zeynel Dağlı
         * @since 06/01/2016 version 0.3
         */ 
        if(isset($params['pk']) &&  $this->isServicePkRequired) {
            $resultSet = $this->dalObject->pkIsThere(array('pk' => $params['pk']));
        //   print_r($resultSet);
            
            if(!isset($resultSet['resultSet'][0]['kontrol'])) $this->userNotRegisteredRedirect ();
        }
   
        /**
         * company public  key processes wrapper
         * @author Mustafa Zeynel Dağlı
         * @since 0.3 09/06/2016
         * @todo after detail tests code description will be removed
         */
        $this->publicCompanyKeyProcessControler($params);
        
        /**
         * public  key processes wrapper
         * @author Mustafa Zeynel Dağlı
         * @since 0.3 27/01/2016
         * @todo after detail tests code description will be removed
         */
        $this->publicKeyProcessControler($params);
        
        /**
         * public temp key processes wrapper
         * @author Mustafa Zeynel Dağlı
         * @since 0.3 27/01/2016
         * @todo after detail tests code description will be removed
         */
        $this->publicKeyTempProcessControler($params);
        
        
        $preparedParams = $this->prepareGetParams();
        //$preparedParams = $this->prepareGetParams('', array('pk'));
        if (($ch = @curl_init()) == false) {
            header("HTTP/1.1 500", true, 500);
            die("Cannot initialize CURL session. Is CURL enabled for your PHP installation?");
        }
        //print_r($this->restApiFullPathUrl.'?'.$preparedParams);
        curl_setopt($ch, CURLOPT_URL, $this->restApiFullPathUrl . '?' . $preparedParams); //Url together with parameters
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->getCallTimeOut()); //Timeout (Default 7 seconds)
        
        /**
         * wrapper for curl header for public key and more
         * @author Mustafa Zeynel Dağlı
         * @since 0.3 27/01/2016
         * @todo after detail tests code description will be removed
         */
        $headerForPublicKeyArr = $this->setCurlHeaderForPublicKey($ch);  
        
        /**
         * wrapper for curl header for public key and more
         * @author Mustafa Zeynel Dağlı
         * @since 0.3 27/01/2016
         * @todo after detail tests code description will be removed
         */
        $headerForPublicKeyTempArr = $this->setCurlHeaderForPublicKeyTemp($ch);
        $curlHeaderArr = array_merge($headerForPublicKeyArr,$headerForPublicKeyTempArr);
        
        
        
        $headerForLogArr = $this->setCurlHeaderForInsertUpdateDelete();
        $curlHeaderArr = array_merge($curlHeaderArr,$headerForLogArr);
        //print_r($headerForLogArr);
        //print_r($curlHeaderArr);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaderArr);
        
        /**
         * if request is ssl encrypted consider header options below
         * @author Mustafa Zeynel Dağlı
         * @since 23/12/2015
         */
        /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);*/
        curl_setopt($ch, CURLOPT_HEADER, 0); // we don’t want also to get the header information that we receive.
        //sleep(10);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response == false) {
            die("curl_exec() failed. Error: " . curl_error($ch));
        }

        return $response;
    }
    
    /**
     * wrapper function for curl header for insert, update , delete operations log
     * @author Mustafa Zeynel Dağlı
     * @version 0.3 21/03/2016
     * @todo after detailed test curl obj will be removed
     */
    protected function setCurlHeaderForInsertUpdateDelete() {
        
       $logArray = array();
        if($this->isInsertOperationLogged) { 
            $logArray[] = 'X-InsertOperationLogged : true';
           // $logArray[] = 'X-IP: ' . $ip['ip'] . '';
          //  $logArray[] = 'X-IP: '. $this->hmacObj->getRealIp() . '';
            //print_r('--isServicePkRequired--');
            /*curl_setopt($chLocal, CURLOPT_HTTPHEADER, array(
                'X-Public: ' . $this->hmacObj->getPublicKey() . '',
                'X-Hash: ' . $this->hmacObj->getHash() . '',
                'X-Nonce:' . $this->hmacObj->getNonce(),
                //'X-IP:'.serialize($_SERVER),
                'X-TimeStamp:' . $this->hmacObj->setTimeStamp($this->encryptClass
                                ->encrypt('' . time() . ''))  /// replay attack lar için oki
            ));*/
        }
        
        if($this->isDeleteOperationLogged) {
            $logArray[] = 'X-DeleteOperationLogged : true';
        }
        
        if($this->isUpdateOperationLogged) {
            $logArray[] = 'X-UpdateOperationLogged : true';
        }
          
       /* if($this->isServicePkRequired == false) {
        $logArray[] = 'X-IP: '. $this->hmacObj->getRealIp() . '';
        }
        */
        return $logArray;
    }
    
    /**
     * wrapper function for curl header for public keys and more
     * @author Mustafa Zeynel Dağlı
     * @version 0.3 27/01/2016
     * @todo after detailed test curl obj will be removed
     */
    protected function setCurlHeaderForPublicKey($ch = null) {
        $chLocal = $ch;
        $logArray = array();
        /**
         * if service has to be secure then prepare header for security
         * parameters
         * @author Mustafa Zeynel Dağlı
         * @since version 0.3 06/01/2016
         */
        $logArray[] = 'X-IP: '. $this->hmacObj->getRealIp() . '';
        if($this->isServicePkRequired) {
            //print_r('--isServicePkRequired--');
            $logArray[] = 'X-Public: ' . $this->hmacObj->getPublicKey() . '';
            $logArray[] = 'X-Hash: ' . $this->hmacObj->getHash() . '';
            $logArray[] = 'X-Nonce:' . $this->hmacObj->getNonce();
            $logArray[] = 'X-TimeStamp:' . $this->hmacObj->setTimeStamp($this->encryptClass
                                                         ->encrypt('' . time() . ''));
          //  $logArray[] = 'X-IP: '. $this->hmacObj->getRealIp() . '';
            /*curl_setopt($chLocal, CURLOPT_HTTPHEADER, array(
                'X-Public: ' . $this->hmacObj->getPublicKey() . '',
                'X-Hash: ' . $this->hmacObj->getHash() . '',
                'X-Nonce:' . $this->hmacObj->getNonce(),
                //'X-IP:'.serialize($_SERVER),
                'X-TimeStamp:' . $this->hmacObj->setTimeStamp($this->encryptClass
                                ->encrypt('' . time() . ''))  /// replay attack lar için oki
            ));*/
        }
        return $logArray;
        //return $chLocal;
    }
    
    /**
     * wrapper function for curl header for public temp keys and more
     * @author Mustafa Zeynel Dağlı
     * @version 0.3 27/01/2016
     */
    protected function setCurlHeaderForPublicKeyTemp($ch = null) {
        $chLocal = $ch;
        $logArray = array();
        /**
         * if service has to be secure then prepare header for security
         * parameters
         * @author Mustafa Zeynel Dağlı
         * @since version 0.3 27/01/2016
         * @todo after detailed test curl obj will be removed
         */
        //     $logArray[] = 'X-IP: '. $this->hmacObj->getRealIp() . '';
        if($this->isServicePkTempRequired) {
            $logArray[] = 'X-Public-Temp: ' . $this->hmacObj->getPublicKey() . '';
            $logArray[] = 'X-Hash-Temp: ' . $this->hmacObj->getHash() . '';
            $logArray[] = 'X-Nonce:' . $this->hmacObj->getNonce();
            $logArray[] = 'X-TimeStamp:' . $this->hmacObj->setTimeStamp($this->encryptClass
                                                         ->encrypt('' . time() . ''));
            //$logArray[] = 'X-IP: '. $this->hmacObj->getRealIp() . '';
            
            /*curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'X-Public-Temp: ' . $this->hmacObj->getPublicKey() . '',
                'X-Hash-Temp: ' . $this->hmacObj->getHash() . '',
                'X-Nonce:' . $this->hmacObj->getNonce(),
                //'X-IP:'.serialize($_SERVER),
                'X-TimeStamp:' . $this->hmacObj->setTimeStamp($this->encryptClass
                                ->encrypt('' . time() . ''))  /// replay attack lar için oki
            ));*/
        }
        return $logArray;
        //return $chLocal;
    }

    /**
     * public key temp control processes has been wrapped
     * @param array $params
     * @return mixed array | null
     * @throws Exception
     */
    private function publicKeyTempProcessControler($params) {
       // $this->hmacObj->setRealIp(); 
        if($this->isServicePkTempRequired) {
            /**
            * getting private key temp due to public key temp
            * @author Mustafa Zeynel Dağlı
            * @since 27/01/2016 version 0.3
            */
           if(isset($params['pktemp'])) $resultSetTemp = $this->dalObject->getPrivateKeyTemp($params['pktemp']);
           /**
            * if not get private temp key due to public temp key
            * forward to private not found
            * @author Mustafa Zeynel Dağlı
            * @since 27/01/2016 version 0.3 
            */
           if(empty($resultSetTemp['resultSet'])) $this->privateKeyTempNotFoundRedirect();
           /**
            * if service has to be secure then prepare hash for public and private temp keys
            * @author Mustafa Zeynel Dağlı
            * @since version 0.3 27/01/2016
            */
            if(!isset($resultSetTemp['resultSet'][0]['sf_private_key_value_temp'])){
                throw new \Exception ('SlimHmacProxy->restApiDefaultCall() method private temp key not found!!');
            }
            if(!isset($params['pktemp'])) {
                throw new \Exception ('SlimHmacProxy->restApiDefaultCall() method public temp key not found!!');
            } else {
                $this->hmacObj->setPublicKey($params['pktemp']); 
            }
            
            //$this->hmacObj->setPrivateKey('e249c439ed7697df2a4b045d97d4b9b7e1854c3ff8dd668c779013653913572e');
            $this->hmacObj->setPrivateKey($resultSetTemp['resultSet'][0]['sf_private_key_value_temp']);
            
            $this->hmacObj->setRequestParams($this->getRequestParamsWithoutPublicKeyTemp());
            $this->hmacObj->makeHmac();
            //print_r($this->hmacObj);
           
           return $resultSetTemp;
        } else {
            return null;
        }
        
    }
    
    /**
     * public key control processes has been wrapped
     * @param array $params
     * @return mixed array | null
     * @throws Exception
     * @author Mustafa Zeynel Dağlı
     * @since 0.3 27/01/2016
     */
    private function publicKeyProcessControler($params) { 
        $resultSet;
        $this->hmacObj->setRealIp(); 
        if($this->isServicePkRequired) {
            /**
            * getting private key due to public key
            * @author Mustafa Zeynel Dağlı
            * @since 05/01/2016 version 0.3
            */
           if(isset($params['pk'])) $resultSet = $this->dalObject->getPrivateKey($params['pk']);

           /**
            * if not get private key due to public key
            * forward to private not found
            * @author Mustafa Zeynel Dağlı
            * @since 06/01/2016 version 0.3
            */
           if(empty($resultSet['resultSet'])) $this->privateKeyNotFoundRedirect();

           /**
            * if service has to be secure then prepare hash for public and private keys
            * @author Mustafa Zeynel Dağlı
            * @since version 0.3 06/01/2016
            */
            if(!isset($resultSet['resultSet'][0]['sf_private_key_value'])){
                throw new Exception ('SlimHmacProxy->restApiDefaultCall() method private key not found!!');
            }

            if(!isset($params['pk'])) {
                throw new Exception ('SlimHmacProxy->restApiDefaultCall() method public key not found!!');
            } else {
                $this->hmacObj->setPublicKey($params['pk']);
            }
            
            //$this->hmacObj->setPrivateKey('e249c439ed7697df2a4b045d97d4b9b7e1854c3ff8dd668c779013653913572e');
            $this->hmacObj->setPrivateKey($resultSet['resultSet'][0]['sf_private_key_value']);
            $this->hmacObj->setRoleId($resultSet['resultSet'][0]['role_id']);
            $this->hmacObj->setRequestParams($this->getRequestParamsWithoutPublicKey());
            $this->hmacObj->makeHmac();
            //print_r($this->hmacObj);
           

           return $resultSet;
        } else {
            return null;
        }
        
    }
    
    /**
     * company public key control processes has been wrapped
     * @param type $params
     * @return type
     * @author Mustafa Zeynel Dağlı
     * @since 0.4 09/06/2016
     */
    private function publicCompanyKeyProcessControler($params) {
        $resultSet; 
        if($this->isServiceCpkRequired) {
            /**
            * getting private key due to public key
            * @author Mustafa Zeynel Dağlı
            * @since 05/01/2016 version 0.3
            */
           if(isset($params['cpk']) && isset($params['pk'])) $resultSet = $this->dalObject->isUserBelongToCompany($params['pk'], 
                                                                                                                  $params['cpk']);

           /**
            * if user id and company key does not match
            * forward to user not belong to company
            * @author Mustafa Zeynel Dağlı
            * @since 06/01/2016 version 0.3
            */
           if(empty($resultSet['resultSet'])) $this->userNotBelongCompany();

           return $resultSet;
        } else {
            return null;
        }
        
    }
    

    public function setEndPointByClosure(Array $EndPointClosure = null) {
        
        $xxroleid = $this->hmacObj->getRoleID(); 
     //   print_r($xxroleid) ;        
            
        
        $endPointFunction = $this->getRestApiEndPointFunction();
        $endPointFunctionLen = strlen($endPointFunction);
        $endPointFunctionPos = strrpos($endPointFunction, "_");
        $endPointFunctionStart = $endPointFunctionLen - $endPointFunctionPos;
        $endPoint = substr($endPointFunction, - $endPointFunctionStart);
        //  print_r($endPoint); 
        switch ($endPoint) {
            case "_test":
                echo "_test!";
                break;
            
            
            case "_sysaccbodytypes": 
                $this->setRestApiEndPoint('sysaccbodytypes.php/');
                break; 
             case "_sysaccbodymatrix": 
                $this->setRestApiEndPoint('sysaccbodymatrix.php/');
                break; 
            case "_sysvehiclegroups": 
                $this->setRestApiEndPoint('sysvehiclegroups.php/');
                break; 
            case "_sysvehiclegtmodels": 
                $this->setRestApiEndPoint('sysvehiclegtmodels.php/');
                break; 
            case "_sysvehiclegrouptypes": 
                $this->setRestApiEndPoint('sysvehiclegrouptypes.php/');
                break; 
            case "_sysvehicleconfigtypes": 
                $this->setRestApiEndPoint('sysvehicleconfigtypes.php/');
                break;
            case "_sysvehicleckdcbu": 
                $this->setRestApiEndPoint('sysvehicleckdcbu.php/');
                break; 
            case "_sysvehiclecaptypes": 
                $this->setRestApiEndPoint('sysvehiclecaptypes.php/');
                break; 
            case "_sysvehiclebtobts": 
                $this->setRestApiEndPoint('sysvehiclebtobts.php/');
                break; 
            case "_sysvehicleapptypes": 
                $this->setRestApiEndPoint('sysvehicleapptypes.php/');
                break; 
            case "_sysaccbodydeff": 
                $this->setRestApiEndPoint('sysaccbodydeff.php/');
                break;  
            case "_infoDealerOwner": 
                $this->setRestApiEndPoint('infodealerowner.php/');
                break; 
            case "_infoSales": 
                $this->setRestApiEndPoint('infosales.php/');
                break;
            case "_infoAfterSales": 
                $this->setRestApiEndPoint('infoaftersales.php/');
                break;
            case "_blLoginLogout": 
                $this->setRestApiEndPoint('blLoginLogout.php/');
                break;
            case "_infoUsersVerbal":
                $this->setRestApiEndPoint('infousersverbal.php/');
                break;
            case "_leftnavigation":
                $this->setRestApiEndPoint('leftnavigation.php/');
                break;
            case "_infoUsers":
                $this->setRestApiEndPoint('infousers.php/');
                break;
            case "_syscountrys":
                $this->setRestApiEndPoint('syscountrys.php/');
                break;
            case "_syscity":
                $this->setRestApiEndPoint('syscity.php/');
                break;
            case "_syslanguage":
                $this->setRestApiEndPoint('syslanguage.php/');
                break;
            case "_sysborough":
                $this->setRestApiEndPoint('sysborough.php/');
                break;
            case "_sysvillage":
                $this->setRestApiEndPoint('sysvillage.php/');
                break; 
            case "_sysAclRoles":
                $this->setRestApiEndPoint('sysaclroles.php/');
                break;
            case "_sysAclResources":
                $this->setRestApiEndPoint('sysaclresources.php/');
                break;
            case "_sysAclPrivilege":
                $this->setRestApiEndPoint('sysaclprivilege.php/');
                break;
            case "_sysAclRrpMap":
                $this->setRestApiEndPoint('sysAclRrpMap.php/');
                break;
            case "_sysSpecificDefinitions":
                $this->setRestApiEndPoint('sysspecificdefinitions.php/');
                break;
            case "_infoUsersCommunications":
                $this->setRestApiEndPoint('infouserscommunications.php/');
                break;
            case "_infoUsersAddresses":
                $this->setRestApiEndPoint('infousersaddresses.php/');
                break;
            case "_blActivationReport":
                $this->setRestApiEndPoint('BlActivationReport.php/');
                break;
            case "_sysOperationTypes":
                $this->setRestApiEndPoint('sysOperationTypes.php/');
                break;
            case "_sysOperationTypesTools":
                $this->setRestApiEndPoint('sysoperationtypestools.php/');
                break;
            case "_infoError":
                $this->setRestApiEndPoint('infoerror.php/');
                break;
            case "_sysUnits":
                $this->setRestApiEndPoint('sysunits.php/');
                break;
            case "_hstryLogin":
                $this->setRestApiEndPoint('hstrylogin.php/');
                break;
            case "_blAdminActivationReport":
                $this->setRestApiEndPoint('bladminactivationreport.php/');
                break;
            case "_logConnection":
                $this->setRestApiEndPoint('logconnection.php/');
                break;
            case "_logServices":
                $this->setRestApiEndPoint('logservices.php/');
                break;
            case "_logAdmin":
                $this->setRestApiEndPoint('logadmin.php/');
                break;
            case "_sysCertifications":
                $this->setRestApiEndPoint('syscertifications.php/');
                break;
            case "_sysUnitSystems":
                $this->setRestApiEndPoint('sysunitsystems.php/');
                break;
            case "_infoUsersSocialmedia":
                $this->setRestApiEndPoint('infouserssocialmedia.php/');
                break;
            case "_sysSocialMedia":
                $this->setRestApiEndPoint('syssocialmedia.php/');
                break;
            case "_sysMailServer":
                $this->setRestApiEndPoint('sysmailserver.php/');
                break;
            case "_sysClusters":
                $this->setRestApiEndPoint('sysclusters.php/');
                break; 
            case "_infoUsersProductsServices":
                $this->setRestApiEndPoint('infousersproductsservices.php/');
                break;
            case "_sysMembershipTypes":
                $this->setRestApiEndPoint('sysmembershiptypes.php/');
                break;
            case "_sysAclRrp":
                $this->setRestApiEndPoint('sysaclrrp.php/');
                break;
            case "_sysUniversities":
                $this->setRestApiEndPoint('sysuniversities.php/');
                break;
            case "_sysMenuTypes":
                $this->setRestApiEndPoint('sysmenutypes.php/');
                break;
            case "_sysAclModules":
                $this->setRestApiEndPoint('sysaclmodules.php/');
                break;
            case "_sysAclActions":
                $this->setRestApiEndPoint('sysaclactions.php/');
                break;
            case "_sysAclMenuTypesActions":
                $this->setRestApiEndPoint('sysaclmenutypesactions.php/');
                break;
            case "_sysAclRrpRestservices":
                $this->setRestApiEndPoint('sysaclrrprestservices.php/');
                break;
            case "_sysServicesGroups":
                $this->setRestApiEndPoint('sysservicesgroups.php/');
                break;
            case "_sysAclRestservices":
                $this->setRestApiEndPoint('sysaclrestservices.php/');
                break;
            case "_sysAssignDefinition":
                $this->setRestApiEndPoint('sysassigndefinitionroles.php/');
                break;
            case "_sysAssignDefinitionRoles":
                $this->setRestApiEndPoint('sysassigndefinitionroles.php/');
                break;
            case "_pgClass":
                $this->setRestApiEndPoint('pgclass.php/');
                break;
            case "_sysOperationTypesRrp":
                $this->setRestApiEndPoint('sysoperationtypesrrp.php/');
                break;
            case "_sysAclActionRrp":
                $this->setRestApiEndPoint('sysaclactionrrp.php/');
                break;
            case "_sysAclActionRrpRestservices":
                $this->setRestApiEndPoint('sysaclactionrrprestservices.php/');
                break;
            case "_infoUsersSendingMail":
                $this->setRestApiEndPoint('infouserssendingmail.php/');
                break;
            case "_actProcessConfirm":
                $this->setRestApiEndPoint('actprocessconfirm.php/');
                break;
            case "_sysSectors":
                $this->setRestApiEndPoint('syssectors.php/');
                break;
            case "_sysCorporation":
                $this->setRestApiEndPoint('syscorporation.php/');
                break;
            case "_sysSubjects":
                $this->setRestApiEndPoint('syssubjects.php/');
                break;
            case "_sysParams":
                $this->setRestApiEndPoint('sysparams.php/');
                break;
            case "_InfoDuyuru":
                $this->setRestApiEndPoint('infoduyuru.php/');
                break;
           

            default:
                echo "EndPoint Bulunamadı ...... ";
        }

        //    print_r($this->restApiBaseUrl.$this->restApiEndPoint.$this->restApiEndPointFunction);
        $this->setRestApiFullPathUrl($this->restApiBaseUrl . $this->restApiEndPoint . $this->restApiEndPointFunction);

            
    }

    /**
     * set Hmac object for HMAC security
     * @param \vendor\hmac\Hmac $hmacObj
     * @version 0.2
     * @author Mustafa Zeynel Dağlı
     */
    public function setHmacObj(\vendor\hmac\Hmac $hmacObj) {
        $this->hmacObj = $hmacObj;
    }

    /**
     * get Hmac object for HMAC security
     * @return \vendor\hmac\Hmac $hmacObj
     * @version 0.2
     * @author Mustafa Zeynel Dağlı
     */
    public function getHmacObj() { 
        return $this->hmacObj;
    }

}
