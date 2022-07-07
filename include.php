<?
$module_id = 'data.tools1c';
   
CModule::IncludeModule("iblock");   
CModule::IncludeModule("catalog");   
CModule::AddAutoloadClasses(
    $module_id,
    array(
        "CModuleOptions" => "classes/general/CModuleOptions.php", 
        "CDataToolsEvent" => "classes/general/CDataTools1c.php",
        "CDataToolsIblock" => "classes/mysql/CDataToolsIblock.php",  
        "CDataToolsProperty" => "classes/mysql/CDataToolsProperty.php",
        "CDataToolsDiscount" => "classes/mysql/CDataToolsDiscount.php",
    )
);

?>