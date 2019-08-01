<?
$module_id = 'sns.tools1c';
   
CModule::IncludeModule("iblock");   
CModule::IncludeModule("catalog");   
CModule::AddAutoloadClasses(
    $module_id,
    array(
        "CModuleOptions" => "classes/general/CModuleOptions.php", 
        "CSnsToolsEvent" => "classes/general/CSnsTools1c.php",
        "CSnsToolsIblock" => "classes/mysql/CSnsToolsIblock.php",  
        "CSnsToolsProperty" => "classes/mysql/CSnsToolsProperty.php",
        "CSnsToolsDiscount" => "classes/mysql/CSnsToolsDiscount.php",
    )
);

?>