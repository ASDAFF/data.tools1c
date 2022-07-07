<?
IncludeModuleLangFile(__FILE__);
$iModuleID = "data.tools1c";
if($APPLICATION->GetGroupRight($iModuleID)!="D"){     
    $aMenu = array(
        "parent_menu" => "global_menu_store",
        "section" => 'data.tools1c',
        "sort" => 1000,
        "text" => GetMessage("MENU_1CTOOLS_TEXT"),
        "title" => GetMessage("MENU_1CTOOLS_TITLE"),
        "url" => "settings_1c_tools.php?lang=".LANGUAGE_ID,   
        "icon" => "tools1c_menu_icon",
        "page_icon" => "tools1c_page_icon",
        "items_id" => "menu_data.tools1c",   
        "items" => array(
            array(
                "text" => GetMessage("MENU_1CTOOLS_SETTING_TEXT"),
                "url" => "settings_1c_tools.php?lang=".LANGUAGE_ID,
                "more_url" => array("settings_1c_tools.php"),
                "title" => GetMessage("MENU_1CTOOLS_SETTING_TITLE")
            ),
            array(
                "text" => GetMessage("MENU_1CTOOLS_PROPERTY_TEXT"),
                "url" => "property_1c_tools.php?lang=".LANGUAGE_ID,
                "more_url" => array("property_1c_tools.php"),
                "title" => GetMessage("MENU_1CTOOLS_PROPERTY_TITLE")
            ),
            array(
                "text" => GetMessage("MENU_1CTOOLS_IBLOCK_TEXT"),
                "url" => "iblock_1c_tools.php?lang=".LANGUAGE_ID,
                "more_url" => array("iblock_1c_tools.php"),
                "title" => GetMessage("MENU_1CTOOLS_IBLOCK_TITLE")
            ),  
            array(
                "text" => GetMessage("MENU_1CTOOLS_DISCONT_TEXT"),
                "url" => "discont_1c_tools.php?lang=".LANGUAGE_ID,
                "more_url" => array("discont_1c_tools.php"),
                "title" => GetMessage("MENU_1CTOOLS_DISCONT_TITLE")
            ),
            array(
                "text" => GetMessage("MENU_1CTOOLS_CHECKS_TEXT"),
                "url" => "checks_1c_tools.php?lang=".LANGUAGE_ID,
                "more_url" => array("checks_1c_tools.php"),
                "title" => GetMessage("MENU_1CTOOLS_CHECKS_TITLE")
            ),
             array(
                "text" => GetMessage("MENU_1CTOOLS_INSTRUCTION_TEXT"),
                "url" => "instruction_1c_tools.php?lang=".LANGUAGE_ID,
                "more_url" => array("instruction_1c_tools.php"),
                "title" => GetMessage("MENU_1CTOOLS_INSTRUCTION_TITLE")
            ),
            array(
                "text" => GetMessage("MENU_1CTOOLS_PROFILE_TEXT"),
                "url" => "profile_1c_tools.php?lang=".LANGUAGE_ID,
                "more_url" => array("profile_1c_tools.php"),
                "title" => GetMessage("MENU_1CTOOLS_PROFILE_TITLE")
            ),
        )
    );
    return $aMenu;
}            

return false;
?>