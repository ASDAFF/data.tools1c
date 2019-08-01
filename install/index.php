<?
/**
 * Copyright (c) 1/8/2019 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

IncludeModuleLangFile(__FILE__);

Class tools_1c extends CModule
{
    const MODULE_ID = 'tools.1c';
    var $MODULE_ID = 'tools.1c';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $strError = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__) . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = GetMessage("1c_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("1c_MODULE_DESC");

        $this->PARTNER_NAME = GetMessage("1c_PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("1c_PARTNER_URI");
    }

    function InstallDB($arParams = array())
    {
        RegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'CSnsToolsC', 'OnBuildGlobalMenu');

        RegisterModuleDependences('iblock', 'OnAfterIBlockElementAdd', self::MODULE_ID, 'CTool1sEvent', 'OnAfterIBlockElementAdd');
        RegisterModuleDependences('iblock', 'OnAfterIBlockElementUpdate', self::MODULE_ID, 'CTool1sEvent', 'OnAfterIBlockElementUpdate');

        RegisterModuleDependences('iblock', 'OnBeforeIBlockElementAdd', self::MODULE_ID, 'CTool1sEvent', 'OnBeforeIBlockElementAdd');
        RegisterModuleDependences('iblock', 'OnBeforeIBlockElementUpdate', self::MODULE_ID, 'CTool1sEvent', 'OnBeforeIBlockElementUpdate');

        RegisterModuleDependences('catalog', 'OnBeforeProductAdd', self::MODULE_ID, 'CTool1sEvent', 'OnBeforeProductAdd');
        RegisterModuleDependences('catalog', 'OnBeforeProductUpdate', self::MODULE_ID, 'CTool1sEvent', 'OnBeforeProductUpdate');
        RegisterModuleDependences('catalog', 'OnPriceAdd', self::MODULE_ID, 'CTool1sEvent', 'OnPriceAdd');
        RegisterModuleDependences('catalog', 'OnPriceUpdate', self::MODULE_ID, 'CTool1sEvent', 'OnPriceUpdate');

        return true;
    }

    function UnInstallDB($arParams = array())
    {
        UnRegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'CSnsToolsC', 'OnBuildGlobalMenu');

        UnRegisterModuleDependences('iblock', 'OnAfterIBlockElementAdd', self::MODULE_ID, 'CTool1sEvent', 'OnAfterIBlockElementAdd');
        UnRegisterModuleDependences('iblock', 'OnAfterIBlockElementUpdate', self::MODULE_ID, 'CTool1sEvent', 'OnAfterIBlockElementUpdate');

        UnRegisterModuleDependences('iblock', 'OnBeforeIBlockElementAdd', self::MODULE_ID, 'CTool1sEvent', 'OnBeforeIBlockElementAdd');
        UnRegisterModuleDependences('iblock', 'OnBeforeIBlockElementUpdate', self::MODULE_ID, 'CTool1sEvent', 'OnBeforeIBlockElementUpdate');

        UnRegisterModuleDependences('catalog', 'OnBeforeProductAdd', self::MODULE_ID, 'CTool1sEvent', 'OnBeforeProductAdd');
        UnRegisterModuleDependences('catalog', 'OnBeforeProductUpdate', self::MODULE_ID, 'CTool1sEvent', 'OnBeforeProductUpdate');
        UnRegisterModuleDependences('catalog', 'OnPriceAdd', self::MODULE_ID, 'CTool1sEvent', 'OnPriceAdd');
        UnRegisterModuleDependences('catalog', 'OnPriceUpdate', self::MODULE_ID, 'CTool1sEvent', 'OnPriceUpdate');

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
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/admin')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.' || $item == 'menu.php')
                        continue;
                    file_put_contents($file = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . self::MODULE_ID . '_' . $item,
                        '<' . '? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/' . self::MODULE_ID . '/admin/' . $item . '");?' . '>');
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/components')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.')
                        continue;
                    CopyDirFiles($p . '/' . $item, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/' . $item, $ReWrite = True, $Recursive = True);
                }
                closedir($dir);
            }
        }
        return true;
    }

    function UnInstallFiles()
    {
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/admin')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.')
                        continue;
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . self::MODULE_ID . '_' . $item);
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/components')) {
            if ($dir = opendir($p)) {
                while (false !== $item = readdir($dir)) {
                    if ($item == '..' || $item == '.' || !is_dir($p0 = $p . '/' . $item))
                        continue;

                    $dir0 = opendir($p0);
                    while (false !== $item0 = readdir($dir0)) {
                        if ($item0 == '..' || $item0 == '.')
                            continue;
                        DeleteDirFilesEx('/bitrix/components/' . $item . '/' . $item0);
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
    }

    function DoUninstall()
    {
        global $APPLICATION;
        UnRegisterModule(self::MODULE_ID);
        $this->UnInstallDB();
        $this->UnInstallFiles();
    }
}

?>
