<?
$module_id = 'sns.tools1c';

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . $module_id . '/include.php');
IncludeModuleLangFile(__FILE__);
$showRightsTab = false;


// получим списки ставок
if (CModule::IncludeModule("catalog")) {
    $ar_vat_id = array('');
    $ar_vat_name = array(GetMessage('sns.tools1c_NONE'));
    $db_vat = CCatalogVat::GetList(array(), array(), array());
    while ($ar_vat = $db_vat->Fetch()) {

        $ar_vat_id[] = $ar_vat['ID'];
        $ar_vat_name[] = $ar_vat['NAME'];

    }
    $arSelVat = array('REFERENCE_ID' => $ar_vat_id, 'REFERENCE' => $ar_vat_name);
}

if (CModule::IncludeModule("iblock")) {
    $ar_iblock_id = array();
    $ar_vat_name = array();
    $resIblockProper = CIBlock::GetList(Array(), Array(), true);
    while ($ar_resIblockProper = $resIblockProper->Fetch()) {
        $ar_iblock_id[] = $ar_resIblockProper['ID'];
        $ar_iblock_name[] = $ar_resIblockProper['NAME'];

    }
    $arIblockProper = array('REFERENCE_ID' => $ar_iblock_id, 'REFERENCE' => $ar_iblock_name);
}

//
$arSelUpdateNone = array(
    'REFERENCE_ID' => array(
        'NAME',
        'PREVIEW_PICTURE',
        'PREVIEW_TEXT',
        'DETAIL_PICTURE',
        'DETAIL_TEXT',
        'TAGS'
    ),
    'REFERENCE' => array(
        GetMessage('sns.tools1c_SELECT_NONE_UPDATE_SEL1'),
        GetMessage('sns.tools1c_SELECT_NONE_UPDATE_SEL2'),
        GetMessage('sns.tools1c_SELECT_NONE_UPDATE_SEL3'),
        GetMessage('sns.tools1c_SELECT_NONE_UPDATE_SEL4'),
        GetMessage('sns.tools1c_SELECT_NONE_UPDATE_SEL5'),
        GetMessage('sns.tools1c_SELECT_NONE_UPDATE_SEL6'),
    )
);
$arTabs = array(
    array(
        'DIV' => 'edit1',
        'TAB' => GetMessage('sns.tools1c_edit1'),
        'ICON' => '',
        'TITLE' => GetMessage('sns.tools1c_edit1'),
        'SORT' => '10'
    ),
    array(
        'DIV' => 'edit20',
        'TAB' => GetMessage('sns.tools1c_edit20'),
        'ICON' => '',
        'TITLE' => GetMessage('sns.tools1c_edit20'),
        'SORT' => '20'
    ),
    array(
        'DIV' => 'edit30',
        'TAB' => GetMessage('sns.tools1c_edit30'),
        'ICON' => '',
        'TITLE' => GetMessage('sns.tools1c_edit30'),
        'SORT' => '30'
    )

);

$arGroups = array(
    'OPTION_10' => array('TITLE' => GetMessage('sns.tools1c_OPTION_10'), 'TAB' => 0),
    'OPTION_50' => array('TITLE' => GetMessage('sns.tools1c_OPTION_50'), 'TAB' => 0),
    'OPTION_40' => array('TITLE' => GetMessage('sns.tools1c_OPTION_40'), 'TAB' => 1),
    'OPTION_20' => array('TITLE' => GetMessage('sns.tools1c_OPTION_20'), 'TAB' => 2),
    'OPTION_30' => array('TITLE' => GetMessage('sns.tools1c_OPTION_30'), 'TAB' => 2),
);

$arOptions = array(
    'CHEXBOX_QUALITY' => array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage('sns.tools1c_CHEXBOX_QUALITY_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'N',
        'SORT' => '10',
        'NOTES' => GetMessage('sns.tools1c_CHEXBOX_QUALITY_DESCR'),
    ),
    'INT_QUALITY_DEFAULT' => array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage('sns.tools1c_INT_QUALITY_DEFAULT_TITLE'),
        'TYPE' => 'INT',
        'DEFAULT' => '',
        'SORT' => '20',
        'REFRESH' => 'N',
        'NOTES' => GetMessage('sns.tools1c_INT_QUALITY_DEFAULT_DESCR'),
        'SIZE' => '3'
    ),
    'SELECT_VAT' => array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage('sns.tools1c_SELECT_VAT_TITLE'),
        'TYPE' => 'SELECT',
        'VALUES' => $arSelVat,
        'SORT' => '30',
        'NOTES' => GetMessage('sns.tools1c_SELECT_VAT_DESCR'),
    ),
    'CHEXBOX_VAT_INCLUDED' => array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage('sns.tools1c_CHEXBOX_VAT_INCLUDED_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'N',
        'SORT' => '40',
        'NOTES' => GetMessage('sns.tools1c_CHEXBOX_VAT_INCLUDED_DESCR'),
    ),


    'CHEXBOX_ARTICLE' => array(
        'GROUP' => 'OPTION_20',
        'TITLE' => GetMessage('sns.tools1c_CHEXBOX_ARTICLE_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'N',
        'SORT' => '10',
        'NOTES' => GetMessage('sns.tools1c_CHEXBOX_ARTICLE_DESCR'),
    ),
    'OFFERS_ARTICLE' => array(
        'GROUP' => 'OPTION_20',
        'TITLE' => GetMessage('sns.tools1c_OFFERS_ARTICLE_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'CML2_ARTICLE',
        'SORT' => '20',
        'REFRESH' => 'N',
        'NOTES' => GetMessage('sns.tools1c_OFFERS_ARTICLE_DESCR'),
    ),
    'CATALOG_ARTICLE' => array(
        'GROUP' => 'OPTION_20',
        'TITLE' => GetMessage('sns.tools1c_CATALOG_ARTICLE_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'CML2_ARTICLE',
        'SORT' => '20',
        'REFRESH' => 'N',
        'NOTES' => GetMessage('sns.tools1c_CATALOG_ARTICLE_DESCR'),
    ),
    'OFFERS_LINK' => array(
        'GROUP' => 'OPTION_20',
        'TITLE' => GetMessage('sns.tools1c_OFFERS_LINK_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'CML2_LINK',
        'SORT' => '30',
        'REFRESH' => 'N',
        'NOTES' => GetMessage('sns.tools1c_OFFERS_LINK_DESCR'),
    ),


    'CHEXBOX_OFFERS_PROPERTIES' => array(
        'GROUP' => 'OPTION_30',
        'TITLE' => GetMessage('sns.tools1c_CHEXBOX_OFFERS_PROPERTIES_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'N',
        'SORT' => '10',
        'NOTES' => GetMessage('sns.tools1c_CHEXBOX_OFFERS_PROPERTIES_DESCR'),
    ),
    'CHEXBOX_OFFERS_PROPERTIES_STRING' => array(
        'GROUP' => 'OPTION_30',
        'TITLE' => GetMessage('sns.tools1c_CHEXBOX_OFFERS_PROPERTIES_STRING_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'N',
        'SORT' => '10',
        'NOTES' => GetMessage('sns.tools1c_CHEXBOX_OFFERS_PROPERTIES_STRING_DESCR'),
    ),
    'OFFERS_ATTRIBUTES' => array(
        'GROUP' => 'OPTION_30',
        'TITLE' => GetMessage('sns.tools1c_OFFERS_ATTRIBUTES_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'CML2_ATTRIBUTES',
        'SORT' => '50',
        'REFRESH' => 'N',
        'NOTES' => GetMessage('sns.tools1c_OFFERS_ATTRIBUTES_DESCR'),
    ),


    'CHEXBOX_MULTIPROPER' => array(
        'GROUP' => 'OPTION_40',
        'TITLE' => GetMessage('sns.tools1c_CHEXBOX_MULTIPROPER_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'N',
        'SORT' => '10',
        'NOTES' => GetMessage('sns.tools1c_CHEXBOX_MULTIPROPER_DESCR'),
    ),
    'STRING_MULTIPROPER_RAZDEL' => array(
        'GROUP' => 'OPTION_40',
        'TITLE' => GetMessage('sns.tools1c_STRING_MULTIPROPER_RAZDEL_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => '||',
        'SORT' => '20',
        'REFRESH' => 'N',
        'NOTES' => GetMessage('sns.tools1c_STRING_MULTIPROPER_RAZDEL_DESCR'),
    ),
    'STRING_MULTIPROPER_ID' => array(
        'GROUP' => 'OPTION_40',
        'TITLE' => GetMessage('sns.tools1c_STRING_MULTIPROPER_ID_TITLE'),
        'TYPE' => 'STRING',
        'VALUES' => '',
        'SORT' => '30',
        'NOTES' => GetMessage('sns.tools1c_STRING_MULTIPROPER_ID_DESCR'),
        'SIZE' => '70'
    ),


    'SELECT_NONE_UPDATE' => array(
        'GROUP' => 'OPTION_50',
        'TITLE' => GetMessage('sns.tools1c_SELECT_NONE_UPDATE_TITLE'),
        'TYPE' => 'MSELECT',
        'VALUES' => $arSelUpdateNone,
        'SORT' => '30',
        'NOTES' => GetMessage('sns.tools1c_SELECT_NONE_UPDATE_DESCR'),
    ),
    /* 
    'STRING_NONE_UPDATE_PROPER' => array(
       'GROUP' => 'OPTION_50',
       'TITLE' => GetMessage('sns.tools1c_STRING_NONE_UPDATE_PROPER_TITLE'),
       'TYPE' => 'STRING',
       'VALUES' => '',
       'SORT' => '40', 
       'NOTES' => GetMessage('sns.tools1c_STRING_NONE_UPDATE_PROPER_DESCR'),    
       'SIZE' => '30'                  
    ),           
       */
);


/*
Конструктор класса CModuleOptions
$module_id - ID модуля
$arTabs - массив вкладок с параметрами
$arGroups - массив групп параметров
$arOptions - собственно сам массив, содержащий параметры
$showRightsTab - определяет надо ли показывать вкладку с настройками прав доступа к модулю ( true / false )
*/

$opt = new CModuleOptions($module_id, $arTabs, $arGroups, $arOptions, $showRightsTab);
$opt->ShowHTML();

?>
