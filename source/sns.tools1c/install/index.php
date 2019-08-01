<?
IncludeModuleLangFile(__FILE__);
Class sns_tools1c extends CModule
{
    const MODULE_ID = 'sns.tools1c';
    var $MODULE_ID = 'sns.tools1c'; 
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $strError = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__)."/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = GetMessage("sns.tools1c_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("sns.tools1c_MODULE_DESC");

        $this->PARTNER_NAME = GetMessage("sns.tools1c_PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("sns.tools1c_PARTNER_URI");
    }

    function InstallDB($arParams = array())
    {
        
        global $DB, $DBType, $APPLICATION;   
        
        $this->errors = false;

        if(!$DB->Query("SELECT 'x' FROM b_snstools1c_property", true))
        {
            $this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/db/".$DBType."/install.sql");
        }
        
        if($this->errors !== false)
        {
            $APPLICATION->ThrowException(implode("", $this->errors));
            return false;
        }  
                 
       // RegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'CSnsToolsC', 'OnBuildGlobalMenu');

        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->registerEventHandler("sale","OnSaleOrderSaved",self::MODULE_ID,"CSnsToolsEvent","notDuplicateDocumentsForOrder");
        $eventManager->registerEventHandler("main","OnAfterUserAdd",self::MODULE_ID,"CSnsToolsEvent","OnAfterUserAddHandler");
        $eventManager->registerEventHandler("main","OnBeforeProlog",self::MODULE_ID,"CSnsToolsEvent","checkFileRequest");
        $eventManager->registerEventHandler("sale","OnBeforeOrderAdd",self::MODULE_ID,'\Sns\Tools1c\EventHandler',"OnOrderAddHundlers");
//        $eventManager->registerEventHandler("sale","OnSaleComponentOrderUserResult",self::MODULE_ID,'CSnsToolsEvent',"offDublicateInn");

        RegisterModuleDependences('iblock', 'OnBeforeIBlockSectionAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockSectionAdd');
        RegisterModuleDependences('iblock', 'OnBeforeIBlockSectionUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockSectionUpdate');

        
        RegisterModuleDependences('iblock', 'OnAfterIBlockElementAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnAfterIBlockElementAdd');
        RegisterModuleDependences('iblock', 'OnAfterIBlockElementUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnAfterIBlockElementUpdate'); 
         
        RegisterModuleDependences('iblock', 'OnBeforeIBlockElementAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockElementAdd');
        RegisterModuleDependences('iblock', 'OnBeforeIBlockElementUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockElementUpdate');
            
        RegisterModuleDependences('catalog', 'OnBeforeProductAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeProductAdd');  
        RegisterModuleDependences('catalog', 'OnBeforeProductUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeProductUpdate'); 
        RegisterModuleDependences('catalog', 'OnPriceAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnPriceAdd');  
        RegisterModuleDependences('catalog', 'OnPriceUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnPriceUpdate');   
               
        RegisterModuleDependences('sale', 'OnSaleBeforeCancelOrder', self::MODULE_ID, 'CSnsToolsEvent', 'OnSaleBeforeCancelOrder');

        RegisterModuleDependences('sale', 'OnOrderSave', self::MODULE_ID, 'CSnsToolsEvent', 'OnOrder');

        RegisterModuleDependences('catalog', 'OnSuccessCatalogImport1C', self::MODULE_ID, 'CSnsToolsEvent', 'OnSuccessCatalogImport1C');
        RegisterModuleDependences('iblock', 'OnBeforeIBlockPropertyAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockPropertyAdd');   
     
        //��� ������ �� ������������� � ����������
        RegisterModuleDependences('iblock', 'OnIBlockPropertyDelete', self::MODULE_ID, 'CSnsToolsEvent', 'OnIBlockPropertyDelete');
        RegisterModuleDependences('iblock', 'OnBeforeIBlockPropertyUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockPropertyUpdate');
        RegisterModuleDependences('iblock', 'OnIBlockDelete', self::MODULE_ID, 'CSnsToolsEvent', 'OnIBlockDelete');
     
        //��� ������ �� ��������
        RegisterModuleDependences('catalog', 'OnDiscountDelete', self::MODULE_ID, 'CSnsToolsEvent', 'OnDiscountDelete');   
        RegisterModuleDependences('catalog', 'OnDiscountUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnDiscountUpdate');                     
        RegisterModuleDependences('catalog', 'OnBeforeDiscountUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeDiscountUpdate');
        if(CModule::IncludeModule('main')){
            CAgent::AddAgent("CSnsToolsEvent::update_table_profile(true);", self::MODULE_ID, "N", 86400, "", "Y", "", 30);
        }

        return true;
    }

    function UnInstallDB($arParams = array())
    {
        
        global $DB, $DBType, $APPLICATION;
        
        
        
        $this->errors = false;
        if(array_key_exists("savedata", $arParams) && $arParams["savedata"] != "Y")
        {
            $this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/db/".$DBType."/uninstall.sql");

            if($this->errors !== false)
            {
                $APPLICATION->ThrowException(implode("", $this->errors));
                return false;
            }
        }
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->unRegisterEventHandler("sale","OnSaleOrderSaved",self::MODULE_ID,"CSnsToolsEvent","notDuplicateDocumentsForOrder");
        $eventManager->unRegisterEventHandler("main","OnAfterUserAdd",self::MODULE_ID,"CSnsToolsEvent","OnAfterUserAddHandler");
        $eventManager->unRegisterEventHandler("main","OnBeforeProlog",self::MODULE_ID,"CSnsToolsEvent","checkFileRequest");
        $eventManager->unRegisterEventHandler("sale","OnBeforeOrderAdd",self::MODULE_ID,'\Sns\Tools1c\EventHandler',"OnOrderAddHundlers");
        $eventManager->unRegisterEventHandler("sale","OnSaleComponentOrderUserResult",self::MODULE_ID,'CSnsToolsEvent',"offDublicateInn");
        //UnRegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'CSnsToolsC', 'OnBuildGlobalMenu');
        UnRegisterModuleDependences('iblock', 'OnBeforeIBlockSectionAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockSectionAdd');
        UnRegisterModuleDependences('iblock', 'OnBeforeIBlockSectionUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockSectionUpdate');
        
        
        UnRegisterModuleDependences('iblock', 'OnAfterIBlockElementAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnAfterIBlockElementAdd');
        UnRegisterModuleDependences('iblock', 'OnAfterIBlockElementUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnAfterIBlockElementUpdate');
        
        UnRegisterModuleDependences('iblock', 'OnBeforeIBlockElementAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockElementAdd');
        UnRegisterModuleDependences('iblock', 'OnBeforeIBlockElementUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockElementUpdate');
                
        UnRegisterModuleDependences('catalog', 'OnBeforeProductAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeProductAdd');  
        UnRegisterModuleDependences('catalog', 'OnBeforeProductUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeProductUpdate'); 
        UnRegisterModuleDependences('catalog', 'OnPriceAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnPriceAdd');  
        UnRegisterModuleDependences('catalog', 'OnPriceUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnPriceUpdate');         
        
        UnRegisterModuleDependences('sale', 'OnSaleBeforeCancelOrder', self::MODULE_ID, 'CSnsToolsEvent', 'OnSaleBeforeCancelOrder');

        UnRegisterModuleDependences('sale', 'OnOrderSave', self::MODULE_ID, 'CSnsToolsEvent', 'OnOrder');

        UnRegisterModuleDependences('catalog', 'OnSuccessCatalogImport1C', self::MODULE_ID, 'CSnsToolsEvent', 'OnSuccessCatalogImport1C');    
        UnRegisterModuleDependences('iblock', 'OnBeforeIBlockPropertyAdd', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockPropertyAdd'); 
        
        //��� ������ �� ������������� � ����������
        UnRegisterModuleDependences('iblock', 'OnIBlockPropertyDelete', self::MODULE_ID, 'CSnsToolsEvent', 'OnIBlockPropertyDelete');
        UnRegisterModuleDependences('iblock', 'OnBeforeIBlockPropertyUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeIBlockPropertyUpdate');
        UnRegisterModuleDependences('iblock', 'OnIBlockDelete', self::MODULE_ID, 'CSnsToolsEvent', 'OnIBlockDelete');        
        
        //��� ������ �� ��������
        UnRegisterModuleDependences('catalog', 'OnDiscountDelete', self::MODULE_ID, 'CSnsToolsEvent', 'OnDiscountDelete');   
        UnRegisterModuleDependences('catalog', 'OnDiscountUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnDiscountUpdate');                     
        UnRegisterModuleDependences('catalog', 'OnBeforeDiscountUpdate', self::MODULE_ID, 'CSnsToolsEvent', 'OnBeforeDiscountUpdate');


        CAgent::RemoveModuleAgents(self::MODULE_ID);
        UnRegisterModule(self::MODULE_ID);   
        
        return true;
    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function InstallFiles($arParams = array())
    {
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/admin', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin', true);      
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/themes/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/", true, true);  
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/images",  $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/".self::MODULE_ID."", true, true);    
        
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
        {
            if ($dir = opendir($p))
            {
                while (false !== $item = readdir($dir))
                {
                    if ($item == '..' || $item == '.')
                        continue;
                    CopyDirFiles($p.'/'.$item, $_SERVER['DOCUMENT_ROOT'].'/bitrix/components/'.$item, $ReWrite = True, $Recursive = True);
                }
                closedir($dir);
            }
        }
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/admin', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin');    
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/themes/.default/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default");
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/images/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/images');        
        
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
        {
            if ($dir = opendir($p))
            {
                while (false !== $item = readdir($dir))
                {
                    if ($item == '..' || $item == '.' || !is_dir($p0 = $p.'/'.$item))
                        continue;

                    $dir0 = opendir($p0);
                    while (false !== $item0 = readdir($dir0))
                    {
                        if ($item0 == '..' || $item0 == '.')
                            continue;
                        DeleteDirFilesEx('/bitrix/components/'.$item.'/'.$item0);
                    }
                    closedir($dir0);
                }
                closedir($dir);
            }
        }
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        $this->InstallFiles();
        $this->InstallDB();
        RegisterModule(self::MODULE_ID);
        $APPLICATION->IncludeAdminFile(GetMessage("sns.tools1c.MODULE_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/step.php");  
    }

    function DoUninstall()
    {
        global $APPLICATION, $step;
        
        $step = IntVal($step);
        if($step<2)
        {
            $APPLICATION->IncludeAdminFile(GetMessage("sns.tools1c.MODULE_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/unstep1.php");
        }
        elseif($step==2)
        {       
            $this->UnInstallDB(array(
                "savedata" => $_REQUEST["savedata"],
            ));
            $this->UnInstallFiles();
           
            $GLOBALS["errors"] = $this->errors;

            $APPLICATION->IncludeAdminFile(GetMessage("sns.tools1c.MODULE_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/unstep2.php");
           
        }     
    }
}
?>
