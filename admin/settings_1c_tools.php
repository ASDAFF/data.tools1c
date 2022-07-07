<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$module_id = "data.tools1c";
CModule::IncludeModule($module_id);
if(isset($_REQUEST['update'])){
  CDataToolsEvent::update_table_profile();
}

if(($_GET["mode"] == "import") || ($_GET["mode"] == "checkauth")) {
    global $USER;
    $submit = (isset($_POST['submit'])) ? intval($_POST['submit']) : false;

    $bUSER_HAVE_ACCESS = false;
    if (isset($USER) && is_object($USER)) {
        $bUSER_HAVE_ACCESS = $USER->IsAdmin();
        if (!$bUSER_HAVE_ACCESS) {
            $GROUP_PERMISSIONS = explode(",", COption::GetOptionString("catalog", "1C_GROUP_PERMISSIONS", "1"));
            $arUserGroupArray = $USER->GetUserGroupArray();
            foreach ($GROUP_PERMISSIONS as $PERM) {
                if (in_array($PERM, $arUserGroupArray)) {
                    $bUSER_HAVE_ACCESS = true;
                    break;
                }
            }
        }
    }

    if ($_GET["mode"] == "checkauth" && $USER->IsAuthorized()) {
        echo "success\n";
        echo session_name() . "\n";
        echo session_id() . "\n";
        echo bitrix_sessid_get() . "\n";
        echo "timestamp=" . time() . "\n";
    } elseif (!$USER->IsAuthorized()) {
        echo "failure\nNo auth";
    } elseif (!$bUSER_HAVE_ACCESS) {
        echo "failure\naccess denied";
    } elseif (!CModule::IncludeModule('iblock')) {
        echo "failure\n";
    } elseif (($_GET["mode"] == "import")) {
        if ($submit) {
            //Здесь работаем с содержимым переданного файла.
            $uploadFile = $_FILES['datafile'];
            $tmp_name = $uploadFile['tmp_name'];
            $data_filename = $uploadFile['name'];
            if (!is_uploaded_file($tmp_name)) {
                die('Error loading file ' . $data_filename);
            } else {
                //Считываем файл в строку
                $data = file_get_contents($tmp_name);
                if(LANG_CHARSET == 'windows-1251'){
                    $data = mb_convert_encoding($data, 'windows-1251', 'utf-8');
                    if(substr($data, 0, 1) == '?'){
                        $data = substr( $data, 1);
                    }
                }
                if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/upload/1c_tools/')) {
                    if (!mkdir($_SERVER["DOCUMENT_ROOT"].'/upload/1c_tools/')) {
                        die("Error creating directory");
                    }
                }
                //Теперь нормальный файл можно сохранить на диске
                if(file_exists($_SERVER["DOCUMENT_ROOT"].'/upload/1c_tools/checks.xml')){
                    $file = $_SERVER["DOCUMENT_ROOT"]."/upload/1c_tools/checks.xml";
                    unset($file);
                }

                if (!empty($data) && ($fp = @fopen($_SERVER["DOCUMENT_ROOT"]."/upload/1c_tools/" . $data_filename, 'wb'))) {
                    @fwrite($fp, $data);
                    @fclose($fp);
                    CDataToolsEvent::UploadChecks();
                } else {
                    die('Error writing file ' . $data_filename);
                }
                @header('HTTP/1.1 200 Ok');
                @header('Content-type: text/html; charset=windows-1251');
                $answer = 'File ' . $data_filename . ' successfully loaded.';
                print ($answer);
            }
        }
    }
    die;
}
$module_id = "data.tools1c";

$arTabs = array();
IncludeModuleLangFile(__FILE__);


$APPLICATION->SetTitle(GetMessage('1CTOOLS_SETTING_TITLE'));

//Проверка прав
$CONS_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($CONS_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php");


function OptionGetValue($key) {
    $result = COption::GetOptionString("data.tools1c",$key);
    if($_REQUEST[$key]){
        $result = $_REQUEST[$key];
    }
    return $result;
}

$site_ids = array();
$rsSites = CSite::GetList($by="sort", $order="asc", Array());
while ($arSite = $rsSites->Fetch())
{
    $site_ids['REFERENCE_ID'][] = $arSite['LID'];
    $site_ids['REFERENCE'][] = '['.$arSite['LID'].']'.$arSite['NAME'];
}




$module_status = CModule::IncludeModuleEx($module_id);
if($module_status == '0') {
    echo GetMessage('DEMO_MODULE');
}
elseif($module_status == '3'){
    echo GetMessage('DEMO_MODULE');
}


//получим список HL
if(OptionGetValue('EDITIONS')!='' && CModule::IncludeModule('highloadblock')){
    $hl_list = array();
    $id_hl = array();
    $entity = \Bitrix\Highloadblock\HighloadBlockTable::getList(array());
    while ($item = $entity->fetch()) {
        $hl_list['REFERENCE_ID'][] = $item['NAME'];
        $hl_list['REFERENCE'][] = $item['NAME'];
        $id_hl[$item['NAME']] = $item['ID'];
    }
    if (isset($id_hl[OptionGetValue('HL_FOR_DISCOUNT_CARD_11_2')])) {
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($id_hl[OptionGetValue('HL_FOR_DISCOUNT_CARD_11_2')])->fetch();
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $fields_hl_for_discount = array();
        foreach ($entity->getFields() as $name => $field) {
            if ($name != 'ID') {
                $fields_hl_for_discount['REFERENCE_ID'][] = $name;
                $fields_hl_for_discount['REFERENCE'][] = $name;
            }
        }
    }
    if (isset($id_hl[OptionGetValue('DISCOUNT_CARD_11_2')])) {
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($id_hl[OptionGetValue('DISCOUNT_CARD_11_2')])->fetch();
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $fields_hl_for_discount_skidki = array();
        foreach ($entity->getFields() as $name => $field) {
            if ($name != 'ID') {
                $fields_hl_for_discount_skidki['REFERENCE_ID'][] = $name;
                $fields_hl_for_discount_skidki['REFERENCE'][] = $name;
            }
        }
    }
    if (isset($id_hl[OptionGetValue('CONDITION_DISCOUNT_CARD_11_2')])) {
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($id_hl[OptionGetValue('CONDITION_DISCOUNT_CARD_11_2')])->fetch();
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $fields_hl_for_discount_condition = array();
        foreach ($entity->getFields() as $name => $field) {
            if ($name != 'ID') {
                $fields_hl_for_discount_condition['REFERENCE_ID'][] = $name;
                $fields_hl_for_discount_condition['REFERENCE'][] = $name;
            }
        }
    }
}
//Стандарные настройки
if(OptionGetValue('EDITIONS')=='YT11-2'){

    $KartyLoyalnosti = 'KartyLoyalnosti';
    $KartyLoyalnosti_UF_PARTNER = 'UF_PARTNER';
    $KartyLoyalnosti_UF_VLADELETS = 'UF_VLADELETS';
    $KartyLoyalnosti_UF_POMETKAUDALENIYA = 'UF_POMETKAUDALENIYA';
    $KartyLoyalnosti_code = 'UF_SHTRIKHKOD';

    $SkidkiNatsenki = 'SkidkiNatsenki';
    $SkidkiNatsenki_UF_POMETKAUDALENIYA = 'UF_POMETKAUDALENIYA';
    $SkidkiNatsenki_UF_NAME = 'UF_NAME';
    $SkidkiNatsenki_UF_RODITEL = 'UF_RODITEL';
    $SkidkiNatsenki_UF_ZNACHENIESKIDKINA = 'UF_ZNACHENIESKIDKINA';

    $UsloviyaPredostavleniyaSkidokNatsenok = 'UsloviyaPredostavleniyaSkidokNatsenok';
    $UsloviyaPredostavleniyaSkidokNatsenok_UF_POMETKAUDALENIYA = 'UF_POMETKAUDALENIYA';
    $UsloviyaPredostavleniyaSkidokNatsenok_UF_VALYUTAOGRANICHEN = 'UF_VALYUTAOGRANICHEN';
    $UsloviyaPredostavleniyaSkidokNatsenok_UF_RODITEL = 'UF_RODITEL';
    $UsloviyaPredostavleniyaSkidokNatsenok_UF_ZNACHENIEUSLOVIYA = 'UF_ZNACHENIEUSLOVIYA';
    $UsloviyaPredostavleniyaSkidokNatsenok_UF_NAME = 'UF_NAME';

}

if(OptionGetValue('EDITIONS')=='KA'){
    $KartyLoyalnosti = 'InformatsionnyeKarty';
    $KartyLoyalnosti_UF_PARTNER = 'UF_VLADELETSKARTY';
    $KartyLoyalnosti_UF_VLADELETS = 'UF_VIDDISKONTNOYKART';
    $KartyLoyalnosti_UF_POMETKAUDALENIYA = 'UF_POMETKAUDALENIYA';
    $KartyLoyalnosti_code = 'UF_KODKARTY';

    $SkidkiNatsenki = 'TipySkidokNatsenok';
    $SkidkiNatsenki_UF_POMETKAUDALENIYA = 'UF_POMETKAUDALENIYA';
    $SkidkiNatsenki_UF_NAME = 'UF_NAME';
    $SkidkiNatsenki_UF_RODITEL = 'UF_NAME';
    $SkidkiNatsenki_UF_ZNACHENIESKIDKINA = 'UF_PROTSENTSKIDKINAT';

    $UsloviyaPredostavleniyaSkidokNatsenok = 'TipySkidokNatsenok';
    $UsloviyaPredostavleniyaSkidokNatsenok_UF_POMETKAUDALENIYA = 'UF_POMETKAUDALENIYA';
    $UsloviyaPredostavleniyaSkidokNatsenok_UF_VALYUTAOGRANICHEN = 'UF_VALYUTA';
    $UsloviyaPredostavleniyaSkidokNatsenok_UF_RODITEL = 'UF_NAME';
    $UsloviyaPredostavleniyaSkidokNatsenok_UF_ZNACHENIEUSLOVIYA = 'UF_ZNACHENIEUSLOVIYA';
    $UsloviyaPredostavleniyaSkidokNatsenok_UF_NAME = 'UF_NAME';
}


// получим списки ставок
// START
if (CModule::IncludeModule("catalog"))
{
    $ar_vat_id = array('');
    $ar_vat_name = array(GetMessage($module_id.'_NONE'));
    $db_vat = CCatalogVat::GetList(array(), array() , array());
    while ($ar_vat = $db_vat->Fetch())
    {

       $ar_vat_id[] = $ar_vat['ID'];
       $ar_vat_name[] = $ar_vat['NAME'];

    }
    $arSelVat = array('REFERENCE_ID' => $ar_vat_id, 'REFERENCE' => $ar_vat_name);
}
// END

//получим инфоблоки 
//START
if(CModule::IncludeModule("iblock")) {
    $ar_iblock_id = array();
    $ar_vat_name = array();
    $resIblockProper = CIBlock::GetList(Array(), Array(), true);
    while($ar_resIblockProper =  $resIblockProper->Fetch())
    {
        $ar_iblock_id[] = $ar_resIblockProper['ID'];
        $ar_iblock_name[] = $ar_resIblockProper['NAME'];

    }
    $arIblockProper = array('REFERENCE_ID' => $ar_iblock_id, 'REFERENCE' => $ar_iblock_name);


    //получим тип инфоблока
    $db_iblock_type = CIBlockType::GetList(Array(), Array());
    while($ar_iblock_type = $db_iblock_type->Fetch())
    {
        $ar_resIblockType_id[] = $ar_iblock_type["ID"];
        if($arIBType = CIBlockType::GetByIDLang($ar_iblock_type["ID"], LANG))
        {
            $ar_resIblockType_name[] = '['.$ar_iblock_type["ID"].'] '.htmlspecialcharsEx($arIBType["NAME"])."";
        }
    }
    $arIblockType = array('REFERENCE_ID' => $ar_resIblockType_id, 'REFERENCE' => $ar_resIblockType_name);

}
//END

// получим виды цен
// START
$dbPriceType = CCatalogGroup::GetList(
    array("SORT" => "ASC"),
    array()
);
while ($arPriceType = $dbPriceType->Fetch())
{
    $arPrice_id[] = $arPriceType['ID'];
    $arPrice_name[] = '['.$arPriceType['ID'].'] '.$arPriceType['NAME'];
}
$arPriceArrList = array('REFERENCE_ID' => $arPrice_id, 'REFERENCE' => $arPrice_name);
// END


$arSelDiscontLastDiscount = array(
    'REFERENCE_ID' => array(
        '',
        'N',
        'Y'
    ),
    'REFERENCE' => array(
        GetMessage($module_id.'_SELECT_DISCONT_LAST_DISCOUNT_VALUE_'),
        GetMessage($module_id.'_SELECT_DISCONT_LAST_DISCOUNT_VALUE_N'),
        GetMessage($module_id.'_SELECT_DISCONT_LAST_DISCOUNT_VALUE_Y'),
    )

);

//получим группы пользователей
$arSelGroupUsers = array();
$rsGroupsUser = CGroup::GetList($by,$sort,array());
while($arGroupsUser = $rsGroupsUser->Fetch()){
    $arSelGroupUsers['REFERENCE_ID'][] = $arGroupsUser["ID"];
    $arSelGroupUsers['REFERENCE'][] = '['.$arGroupsUser["ID"].'] '.$arGroupsUser["NAME"];
}




//
$arSelUpdateNone = array(
   'REFERENCE_ID' => array(
        'ACTIVE',
        'NAME',
        'IBLOCK_SECTION',
        'PREVIEW_PICTURE',
        'PREVIEW_TEXT',
        'DETAIL_PICTURE',
        'DETAIL_TEXT',
        'TAGS',
       'IBLOCK_SECTION_ID'
   ),
   'REFERENCE' => array(
        GetMessage($module_id.'_SELECT_NONE_UPDATE_ACTIVE'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_NAME'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_IBLOCK_SECTION'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_PREVIEW_PICTURE'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_PREVIEW_TEXT'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_DETAIL_PICTURE'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_DETAIL_TEXT'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_TAGS'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_IBLOCK_SECTION_ID'),
   )
);


$arSelAddNone = array(
   'REFERENCE_ID' => array(
        'PREVIEW_PICTURE',
        'PREVIEW_TEXT',
        'DETAIL_PICTURE',
        'DETAIL_TEXT',
        'TAGS'
   ),
   'REFERENCE' => array(
        GetMessage($module_id.'_SELECT_NONE_UPDATE_PREVIEW_PICTURE'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_PREVIEW_TEXT'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_DETAIL_PICTURE'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_DETAIL_TEXT'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_TAGS'),
   )
);

 $arSelAddNoneForSection = array(
    'REFERENCE_ID' => array(
        'DESCRIPTION',
        'PICTURE'
    ),
    'REFERENCE' => array(
        GetMessage($module_id.'_SELECT_NONE_UPDATE_FOR_SECTION_DESCRIPTION'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_FOR_SECTION_PICTURE'),
    )
);

$arSelUpdateNoneForSection = array(
    'REFERENCE_ID' => array(
        'ACTIVE',
        'NAME',
        'DESCRIPTION',
        'PICTURE'
    ),
    'REFERENCE' => array(
        GetMessage($module_id.'_SELECT_NONE_UPDATE_FOR_SECTION_ACTIVE'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_FOR_SECTION_NAME'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_FOR_SECTION_DESCRIPTION'),
        GetMessage($module_id.'_SELECT_NONE_UPDATE_FOR_SECTION_PICTURE'),
    )
);
$arEdition = array(
    'REFERENCE_ID' => array(
        'YT11-2',
        'KA'
    ),
    'REFERENCE' => array(
        GetMessage($module_id.'_EDITION_11_2_DESCRIPTION'),
        GetMessage($module_id.'_EDITION_KA_DESCRIPTION'),
    )
);

$arTabs = array(
   array(
      'DIV' => 'edit1',
      'TAB' => GetMessage($module_id.'_edit1'),
      'ICON' => '',
      'TITLE' => GetMessage($module_id.'_edit1'),
      'SORT' => '10'
   ),
   array(
      'DIV' => 'edit20',
      'TAB' => GetMessage($module_id.'_edit20'),
      'ICON' => '',
      'TITLE' => GetMessage($module_id.'_edit20'),
      'SORT' => '20'
   ),
   array(
      'DIV' => 'edit30',
      'TAB' => GetMessage($module_id.'_edit30'),
      'ICON' => '',
      'TITLE' => GetMessage($module_id.'_edit30'),
      'SORT' => '30'
   ),
   array(
      'DIV' => 'edit35',
      'TAB' => GetMessage($module_id.'_edit35'),
      'ICON' => '',
      'TITLE' => GetMessage($module_id.'_edit35'),
      'SORT' => '35'
   ),
    array(
        'DIV' => 'edit37',
        'TAB' => GetMessage($module_id.'_edit37'),
        'ICON' => '',
        'TITLE' => GetMessage($module_id.'_edit37'),
        'SORT' => '37'
    ),
    array(
        'DIV' => 'edit38',
        'TAB' => GetMessage($module_id.'_edit38'),
        'ICON' => '',
        'TITLE' => GetMessage($module_id.'_edit38'),
        'SORT' => '38'
    ),
   array(
      'DIV' => 'edit40',
      'TAB' => GetMessage($module_id.'_edit40'),
      'ICON' => '',
      'TITLE' => GetMessage($module_id.'_edit40'),
      'SORT' => '40'
   ),
    array(
        'DIV' => 'edit50',
        'TAB' => GetMessage($module_id.'_edit50'),
        'ICON' => '',
        'TITLE' => GetMessage($module_id.'_edit50'),
        'SORT' => '50'
    )

);

$arGroups = array(
   'OPTION_5' => array('TITLE' => GetMessage($module_id.'_OPTION_5'), 'TAB' => 0),
   'OPTION_10' => array('TITLE' => GetMessage($module_id.'_OPTION_10'), 'TAB' => 0),
   'OPTION_15' => array('TITLE' => GetMessage($module_id.'_OPTION_15'), 'TAB' => 0),
   'OPTION_70' => array('TITLE' => GetMessage($module_id.'_OPTION_70'), 'TAB' => 0),
   'OPTION_50' => array('TITLE' => GetMessage($module_id.'_OPTION_50'), 'TAB' => 0),
   'OPTION_100' => array('TITLE' => GetMessage($module_id.'_OPTION_100'), 'TAB' => 0),
   'OPTION_110' => array('TITLE' => GetMessage($module_id.'_OPTION_110'), 'TAB' => 0),

   'OPTION_40' => array('TITLE' => GetMessage($module_id.'_OPTION_40'), 'TAB' => 1),
   'OPTION_42' => array('TITLE' => GetMessage($module_id.'_OPTION_42'), 'TAB' => 1),
   'OPTION_43' => array('TITLE' => GetMessage($module_id.'_OPTION_43'), 'TAB' => 1),
   'OPTION_45' => array('TITLE' => GetMessage($module_id.'_OPTION_45'), 'TAB' => 1),
   'OPTION_150' => array('TITLE' => GetMessage($module_id.'_OPTION_150'), 'TAB' => 1),


   'OPTION_18' => array('TITLE' => GetMessage($module_id.'_OPTION_18'), 'TAB' => 2),
   'OPTION_20' => array('TITLE' => GetMessage($module_id.'_OPTION_20'), 'TAB' => 2),
   'OPTION_30' => array('TITLE' => GetMessage($module_id.'_OPTION_30'), 'TAB' => 2),
   'OPTION_31' => array('TITLE' => GetMessage($module_id.'_OPTION_31'), 'TAB' => 2),
   'OPTION_33' => array('TITLE' => GetMessage($module_id.'_OPTION_33'), 'TAB' => 2),

   'OPTION_79' => array('TITLE' => GetMessage($module_id.'_OPTION_79'), 'TAB' => 3),
   'OPTION_80' => array('TITLE' => GetMessage($module_id.'_OPTION_80'), 'TAB' => 3),
   'OPTION_90' => array('TITLE' => GetMessage($module_id.'_OPTION_90'), 'TAB' => 3),

   'OPTION_140' => array('TITLE' => GetMessage($module_id.'_OPTION_140'), 'TAB' => 4),
   'OPTION_160' => array('TITLE' => GetMessage($module_id.'_OPTION_160'), 'TAB' => 4),
   'OPTION_170' => array('TITLE' => GetMessage($module_id.'_OPTION_170'), 'TAB' => 4),
   'OPTION_180' => array('TITLE' => GetMessage($module_id.'_OPTION_180'), 'TAB' => 4),

   'OPTION_200' => array('TITLE' => GetMessage($module_id.'_OPTION_200'), 'TAB' => 5),

   'OPTION_60' => array('TITLE' => GetMessage($module_id.'_OPTION_60'), 'TAB' => 6),
   'OPTION_65' => array('TITLE' => GetMessage($module_id.'_OPTION_65'), 'TAB' => 6),
   'OPTION_250' => array('TITLE' => GetMessage($module_id.'_OPTION_250'), 'TAB' => 7),
);


$arOptions['MANAGED_CACHE_ON'] = array(
    'GROUP' => 'OPTION_5',
    'TITLE' => GetMessage($module_id.'_MANAGED_CACHE_ON_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '10',
    'DEFAULT' => 'Y',
    'NOTES' => GetMessage($module_id.'_MANAGED_CACHE_ON_DESCR'),
);

$arOptions['UPDATE_FASET_FILLTER'] = array(
    'GROUP' => 'OPTION_5',
    'TITLE' => GetMessage($module_id.'_UPDATE_FASET_FILLTER_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '10',
    'DEFAULT' => 'N',
);


$arOptions['EDITIONS'] = array(
    'GROUP' => 'OPTION_140',
    'TITLE' => GetMessage($module_id.'_EDITIONS_TITLE'),
    'TYPE' => 'SELECT',
    'VALUES' => $arEdition,
    'SORT' => '30',
    'REFRESH' => 'Y',
    'NOTES' => GetMessage($module_id.'_EDITIONS_DESCR'),
);


if(OptionGetValue('EDITIONS')=='YT11-2' ||  OptionGetValue('EDITIONS')=='KA') {
    $arOptions['EVENTS'] = array(
        'GROUP' => 'OPTION_140',
        'TITLE' => GetMessage($module_id.'_EVENTS_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'N',
        'SORT' => '40',
        'DEFAULT' => 'Y',
        'NOTES' => GetMessage($module_id.'_EVENTS_DESCR'),
    );
    $arOptions['ALLOW_CARD_ADD_GROUP_11_2'] = array(
        'GROUP' => 'OPTION_160',
        'TITLE' => GetMessage($module_id.'_ALLOW_CARD_ADD_GROUP_11_2_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y',
        'SORT' => '40',
        'DEFAULT' => 'N',
        'NOTES' => GetMessage($module_id.'_ALLOW_CARD_ADD_GROUP_11_2_DESCR'),
    );
    if(OptionGetValue('ALLOW_CARD_ADD_GROUP_11_2')=='Y') {
        $arOptions['HL_FOR_DISCOUNT_CARD_11_2'] = array(
            'GROUP' => 'OPTION_160',
            'TITLE' => GetMessage($module_id . '_HL_FOR_DISCOUNT_CARD_11_2_TITLE'),
            'TYPE' => 'SELECT',
            'REFRESH' => 'N',
            'VALUES' => $hl_list,
            'SORT' => '41',
            'DEFAULT' => $KartyLoyalnosti,
            'NOTES' => GetMessage($module_id . '_HL_FOR_DISCOUNT_CARD_11_2_DESCR'),
        );
        if (OptionGetValue('EVENTS') == 'Y') {
            $arOptions['CLEAR_HL_DISC'] = array(
                'GROUP' => 'OPTION_160',
                'TITLE' => GetMessage($module_id . '_CLEAR_HL_DISC_TITLE') . OptionGetValue("HL_FOR_DISCOUNT_CARD_11_2"),
                'TYPE' => 'CHECKBOX',
                'REFRESH' => 'N',
                'SORT' => '43',
                'DEFAULT' => 'N',
                'NOTES' => GetMessage($module_id . '_CLEAR_HL_DISC_DESCR'),
            );
        }
        $arOptions['FIELDS_ACTIVE_CARD_11_2'] = array(
            'GROUP' => 'OPTION_160',
            'TITLE' => GetMessage($module_id . '_FIELDS_ACTIVE_CARD_11_2_TITLE'),
            'TYPE' => 'SELECT',
            'VALUES' => $fields_hl_for_discount,
            'REFRESH' => 'N',
            'SORT' => '44',
            'DEFAULT' => $KartyLoyalnosti_UF_POMETKAUDALENIYA,
            'NOTES' => '',
        );
        $arOptions['FIELDS_VLADELETS_CARD_11_2'] = array(
            'GROUP' => 'OPTION_160',
            'TITLE' => GetMessage($module_id . '_FIELDS_VLADELETS_CARD_11_2_TITLE'),
            'TYPE' => 'SELECT',
            'VALUES' => $fields_hl_for_discount,
            'REFRESH' => 'N',
            'SORT' => '45',
            'DEFAULT' => $KartyLoyalnosti_UF_VLADELETS,
            'NOTES' => '',
        );

        $arOptions['ALLOW_CARD_ADD_ON_USER_11_2'] = array(
            'GROUP' => 'OPTION_170',
            'TITLE' => GetMessage($module_id . '_ALLOW_CARD_ADD_ON_USER_11_2_TITLE'),
            'TYPE' => 'CHECKBOX',
            'REFRESH' => 'N',
            'SORT' => '50',
            'DEFAULT' => 'N',
            'NOTES' => GetMessage($module_id . '_ALLOW_CARD_ADD_ON_USER_11_2_DESCR'),
        );
        $arOptions['FIELDS_PARTNER_CARD_11_2'] = array(
            'GROUP' => 'OPTION_170',
            'TITLE' => GetMessage($module_id . '_FIELDS_PARTNER_CARD_11_2_TITLE'),
            'TYPE' => 'SELECT',
            'VALUES' => $fields_hl_for_discount,
            'REFRESH' => 'N',
            'SORT' => '51',
            'DEFAULT' => $KartyLoyalnosti_UF_PARTNER,
            'NOTES' => '',
        );
        $arOptions['ADD_USER_GROUP_ON_REG'] = array(
            'GROUP' => 'OPTION_170',
            'TITLE' => GetMessage($module_id . '_ADD_USER_GROUP_ON_REG_TITLE'),
            'TYPE' => 'CHECKBOX',
            'REFRESH' => 'N',
            'SORT' => '52',
            'NOTES' => '',
        );
        $arOptions['IMPORT_DISCOUNT_CODE_ON_USER'] = array(
            'GROUP' => 'OPTION_170',
            'TITLE' => GetMessage($module_id . '_IMPORT_DISCOUNT_CODE_ON_USER_TITLE'),
            'TYPE' => 'CHECKBOX',
            'REFRESH' => 'N',
            'SORT' => '53',
            'NOTES' => '',
        );
        $arOptions['ADD_USER_GROUP_ON_REG_DISCOUNT'] = array(
            'GROUP' => 'OPTION_170',
            'TITLE' => GetMessage($module_id.'_ADD_USER_GROUP_ON_REG_DISCOUNT_TITLE'),
            'TYPE' => 'STRING',
            'SORT' => '60',
            'REFRESH' => 'N',
            'SIZE' => '60',
            'NOTES' => GetMessage($module_id.'_ADD_USER_GROUP_ON_REG_DISCOUNT_DESCR'),
        );
        $arOptions['ADD_USER_GROUP_ON_REG_CODE'] = array(
            'GROUP' => 'OPTION_170',
            'TITLE' => GetMessage($module_id.'_ADD_USER_GROUP_ON_REG_CODE_TITLE'),
            'TYPE' => 'SELECT',
            'SORT' => '60',
            'REFRESH' => 'N',
            'VALUES' => $fields_hl_for_discount,
            'DEFAULT' => $KartyLoyalnosti_code,
            'SIZE' => '60',
            'NOTES' => GetMessage($module_id.'_ADD_USER_GROUP_ON_REG_CODE_DESCR'),
        );

            $arOptions['ALLOW_DISCOUNT_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_ALLOW_DISCOUNT_11_2_TITLE'),
                'TYPE' => 'CHECKBOX',
                'REFRESH' => 'N',
                'SORT' => '52',
                'DEFAULT' => 'N',
                'NOTES' => GetMessage($module_id . '_ALLOW_DISCOUNT_11_2_DESCR'),
            );
            if(OptionGetValue('EDITIONS')=='KA'){
                $arOptions['NO_GROUPS'] = array(
                    'GROUP' => 'OPTION_180',
                    'TITLE' => GetMessage($module_id . '_NO_GROUPS_TITLE'),
                    'TYPE' => 'CHECKBOX',
                    'REFRESH' => 'N',
                    'SORT' => '54',
                    'DEFAULT' => 'N',
                    'NOTES' => GetMessage($module_id . '_NO_GROUPS_DESCR'),
                );
            }
            $arOptions['DISCOUNT_CARD_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_DISCOUNT_CARD_11_2_TITLE'),
                'TYPE' => 'SELECT',
                'VALUES' => $hl_list,
                'REFRESH' => 'N',
                'SORT' => '60',
                'DEFAULT' => $SkidkiNatsenki,
                'NOTES' => GetMessage($module_id . '_DISCOUNT_CARD_11_2_DESCR'),
            );
            if (OptionGetValue('EVENTS') == 'Y') {
                $arOptions['CLEAR_HL_DISC_SKIDKI'] = array(
                    'GROUP' => 'OPTION_180',
                    'TITLE' => GetMessage($module_id . '_CLEAR_HL_DISC_TITLE') . OptionGetValue("DISCOUNT_CARD_11_2"),
                    'TYPE' => 'CHECKBOX',
                    'REFRESH' => 'N',
                    'SORT' => '60',
                    'DEFAULT' => 'N',
                    'NOTES' => GetMessage($module_id . '_CLEAR_HL_DISC_DESCR'),
                );
            }
            $arOptions['FIELDS_NAME_DISCOUNT_CARD_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_FIELDS_NAME_DISCOUNT_CARD_11_2_TITLE'),
                'TYPE' => 'SELECT',
                'VALUES' => $fields_hl_for_discount_skidki,
                'REFRESH' => 'N',
                'SORT' => '60',
                'DEFAULT' => $SkidkiNatsenki_UF_NAME,
                'NOTES' => '',
            );
            $arOptions['FIELDS_ACTIVE_DISCOUNT_CARD_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_FIELDS_ACTIVE_DISCOUNT_CARD_11_2_TITLE'),
                'TYPE' => 'SELECT',
                'VALUES' => $fields_hl_for_discount_skidki,
                'REFRESH' => 'N',
                'SORT' => '60',
                'DEFAULT' => $SkidkiNatsenki_UF_POMETKAUDALENIYA,
                'NOTES' => '',
            );
            $arOptions['FIELDS_GROUP_DISCOUNT_CARD_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_FIELDS_GROUP_DISCOUNT_CARD_11_2_TITLE'),
                'TYPE' => 'SELECT',
                'VALUES' => $fields_hl_for_discount_skidki,
                'REFRESH' => 'N',
                'SORT' => '60',
                'DEFAULT' => $SkidkiNatsenki_UF_RODITEL,
                'NOTES' => '',
            );
            $arOptions['FIELDS_VALUE_DISCOUNT_CARD_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_FIELDS_VALUE_DISCOUNT_CARD_11_2_TITLE'),
                'TYPE' => 'SELECT',
                'VALUES' => $fields_hl_for_discount_skidki,
                'REFRESH' => 'N',
                'SORT' => '60',
                'DEFAULT' => $SkidkiNatsenki_UF_ZNACHENIESKIDKINA,
                'NOTES' => '',
            );
            $arOptions['CONDITION_DISCOUNT_CARD_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_CONDITION_DISCOUNT_CARD_11_2_TITLE'),
                'TYPE' => 'SELECT',
                'VALUES' => $hl_list,
                'REFRESH' => 'N',
                'SORT' => '80',
                'DEFAULT' => $UsloviyaPredostavleniyaSkidokNatsenok,
                'NOTES' => GetMessage($module_id . '_CONDITION_DISCOUNT_CARD_11_2_DESCR'),
            );
            if (OptionGetValue('EVENTS') == 'Y') {
                $arOptions['CLEAR_HL_DISC_COND'] = array(
                    'GROUP' => 'OPTION_180',
                    'TITLE' => GetMessage($module_id . '_CLEAR_HL_DISC_TITLE') . OptionGetValue("CONDITION_DISCOUNT_CARD_11_2"),
                    'TYPE' => 'CHECKBOX',
                    'REFRESH' => 'N',
                    'SORT' => '80',
                    'DEFAULT' => 'N',
                    'NOTES' => GetMessage($module_id . '_CLEAR_HL_DISC_DESCR'),
                );
            }
            $arOptions['FIELDS_NAME_CONDITION_DISCOUNT_CARD_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_FIELDS_NAME_CONDITION_DISCOUNT_CARD_11_2_TITLE'),
                'TYPE' => 'SELECT',
                'VALUES' => $fields_hl_for_discount_condition,
                'REFRESH' => 'N',
                'SORT' => '80',
                'DEFAULT' => $UsloviyaPredostavleniyaSkidokNatsenok_UF_NAME,
                'NOTES' => '',
            );
            $arOptions['FIELDS_ACTIVE_CONDITION_DISCOUNT_CARD_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_FIELDS_ACTIVE_CONDITION_DISCOUNT_CARD_11_2_TITLE'),
                'TYPE' => 'SELECT',
                'VALUES' => $fields_hl_for_discount_condition,
                'REFRESH' => 'N',
                'SORT' => '80',
                'DEFAULT' => $UsloviyaPredostavleniyaSkidokNatsenok_UF_POMETKAUDALENIYA,
                'NOTES' => '',
            );
            $arOptions['FIELDS_GROUP_CONDITION_DISCOUNT_CARD_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_FIELDS_GROUP_CONDITION_DISCOUNT_CARD_11_2_TITLE'),
                'TYPE' => 'SELECT',
                'VALUES' => $fields_hl_for_discount_condition,
                'REFRESH' => 'N',
                'SORT' => '80',
                'DEFAULT' => $UsloviyaPredostavleniyaSkidokNatsenok_UF_RODITEL,
                'NOTES' => '',
            );
            $arOptions['FIELDS_CURRENCY_CONDITION_DISCOUNT_CARD_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_FIELDS_CURRENCY_CONDITION_DISCOUNT_CARD_11_2_TITLE'),
                'TYPE' => 'SELECT',
                'VALUES' => $fields_hl_for_discount_condition,
                'REFRESH' => 'N',
                'SORT' => '80',
                'DEFAULT' => $UsloviyaPredostavleniyaSkidokNatsenok_UF_VALYUTAOGRANICHEN,
                'NOTES' => '',
            );
            $arOptions['FIELDS_VALUE_CONDITION_DISCOUNT_CARD_11_2'] = array(
                'GROUP' => 'OPTION_180',
                'TITLE' => GetMessage($module_id . '_FIELDS_VALUE_CONDITION_DISCOUNT_CARD_11_2_TITLE'),
                'TYPE' => 'SELECT',
                'VALUES' => $fields_hl_for_discount_condition,
                'REFRESH' => 'N',
                'SORT' => '80',
                'DEFAULT' => $UsloviyaPredostavleniyaSkidokNatsenok_UF_ZNACHENIEUSLOVIYA,
                'NOTES' => '',
            );
        }
}
$arOptions['ATTRIBUTES_FOR_PRODUCT'] = array(
    'GROUP' => 'OPTION_150',
    'TITLE' => GetMessage($module_id.'_ATTRIBUTES_FOR_PRODUCT_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '10',
    'DEFAULT' => 'N',
    'NOTES' => GetMessage($module_id.'_ATTRIBUTES_FOR_PRODUCT_DESCR'),
);

$arOptions['CHEXBOX_QUALITY'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_QUALITY_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_QUALITY_DESCR'),
);
$arOptions['INT_QUALITY_DEFAULT'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_INT_QUALITY_DEFAULT_TITLE'),
    'TYPE' => 'INT',
    'DEFAULT' => '',
    'SORT' => '20',
    'REFRESH' => 'N',
    'NOTES' => GetMessage($module_id.'_INT_QUALITY_DEFAULT_DESCR'),
    'SIZE' => '3'
);
$arOptions['SELECT_VAT'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_SELECT_VAT_TITLE'),
    'TYPE' => 'SELECT',
    'VALUES' => $arSelVat,
    'SORT' => '30',
    'NOTES' => GetMessage($module_id.'_SELECT_VAT_DESCR'),
);
$arOptions['CHEXBOX_VAT_INCLUDED'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_VAT_INCLUDED_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '40',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_VAT_INCLUDED_DESCR'),
);
$arOptions['CHEXBOX_ZERO_STOCK'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_ZERO_STOCK_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '50',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_ZERO_STOCK_DESCR'),
);
$arOptions['CHEXBOX_ZERO_STOCK_DEACTIVE'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_ZERO_STOCK_DEACTIVE_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '55',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_ZERO_STOCK_DEACTIVE_DESCR'),
);
$arOptions['DEACTIVATION'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_DEACTIVATION_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'SORT' => '55',
    'NOTES' => GetMessage($module_id.'_DEACTIVATION_DESCR'),
);
if (OptionGetValue('DEACTIVATION') == 'Y') {
    $arOptions['DEACTIVATION_PROPERTY'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id . '_DEACTIVATION_PROPERTY_TITLE'),
        'TYPE' => 'STRING',
        'REFRESH' => 'N',
        'DEFAULT' => 'DEAKTIVIROVAT',
        'SORT' => '55',
        'NOTES' => GetMessage($module_id . '_DEACTIVATION_PROPERTY_DESCR'),
    );
}



$arOptions['CHEXBOX_IBLOCK_UNIQUE'] = array(
    'GROUP' => 'OPTION_15',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_IBLOCK_UNIQUE_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_IBLOCK_UNIQUE_DESCR'),
);



$arOptions['OFFERS_ARTICLE'] = array(
    'GROUP' => 'OPTION_18',
    'TITLE' => GetMessage($module_id.'_OFFERS_ARTICLE_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'CML2_ARTICLE',
    'SORT' => '20',
    'REFRESH' => 'N',
    'NOTES' => GetMessage($module_id.'_OFFERS_ARTICLE_DESCR'),
);
$arOptions['CATALOG_ARTICLE'] = array(
    'GROUP' => 'OPTION_18',
    'TITLE' => GetMessage($module_id.'_CATALOG_ARTICLE_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'CML2_ARTICLE',
    'SORT' => '20',
    'REFRESH' => 'N',
    'NOTES' => GetMessage($module_id.'_CATALOG_ARTICLE_DESCR'),
);

$arOptions['OFFERS_ATTRIBUTES'] = array(
    'GROUP' => 'OPTION_18',
    'TITLE' => GetMessage($module_id.'_OFFERS_ATTRIBUTES_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'CML2_ATTRIBUTES',
    'SORT' => '50',
    'REFRESH' => 'N',
    'NOTES' => GetMessage($module_id.'_OFFERS_ATTRIBUTES_DESCR'),
);
$arOptions['OFFERS_MORE_PHOTO'] = array(
    'GROUP' => 'OPTION_18',
    'TITLE' => GetMessage($module_id.'_OFFERS_MORE_PHOTO_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'MORE_PHOTO',
    'SORT' => '60',
    'REFRESH' => 'N',
    'NOTES' => GetMessage($module_id.'_OFFERS_MORE_PHOTO_DESCR'),
);
$arOptions['CATALOG_MORE_PHOTO'] = array(
    'GROUP' => 'OPTION_18',
    'TITLE' => GetMessage($module_id.'_CATALOG_MORE_PHOTO_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'MORE_PHOTO',
    'SORT' => '70',
    'REFRESH' => 'N',
    'NOTES' => GetMessage($module_id.'_CATALOG_MORE_PHOTO_DESCR'),
);




   //артикул предложения
$arOptions['CHEXBOX_ARTICLE'] = array(
    'GROUP' => 'OPTION_20',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_ARTICLE_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_ARTICLE_DESCR'),
);


   //  характеристики торговых предложений
$arOptions['CHEXBOX_OFFERS_PROPERTIES'] = array(
    'GROUP' => 'OPTION_30',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_OFFERS_PROPERTIES_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '5',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_OFFERS_PROPERTIES_DESCR'),
);
$arOptions['OFFERS_PROPERTIES_IBLOCK'] = array(
    'GROUP' => 'OPTION_30',
    'TITLE' => GetMessage($module_id.'_OFFERS_PROPERTIES_IBLOCK_TITLE'),
    'TYPE' => 'SELECT',
    'VALUES' => $arIblockType,
    'SORT' => '8',
    'NOTES' => GetMessage($module_id.'_OFFERS_PROPERTIES_IBLOCK_DESCR'),
);

$arOptions['CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD'] = array(
    'GROUP' => 'OPTION_31',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'SORT' => '10',
);
if(OptionGetValue('CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD')=='Y') {
    $arOptions['CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD_DOP_INFO'] = array(
        'GROUP' => 'OPTION_31',
        'TITLE' => GetMessage($module_id.'_CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD_DOP_INFO_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'N',
        'SORT' => '12',
        'NOTES' => GetMessage($module_id.'_CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD_DESCR'),
    );
    $arOptions['OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE'] = array(
        'GROUP' => 'OPTION_31',
        'TITLE' => GetMessage($module_id.'_OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE_TITLE'),
        'TYPE' => 'STRING',
        'SORT' => '60',
        'REFRESH' => 'N',
        'SIZE' => '60',
        'NOTES' => GetMessage($module_id.'_OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE_DESCR'),
    );
    $arOptions['OFFERS_PROPERTIES_HIGHLOAD_NOT_VALUE_PROPS'] = array(
        'GROUP' => 'OPTION_31',
        'TITLE' => GetMessage($module_id.'_OFFERS_PROPERTIES_HIGHLOAD_NOT_VALUE_PROPS_TITLE'),
        'TYPE' => 'STRING',
        'SORT' => '62',
        'REFRESH' => 'N',
        'SIZE' => '60',
        'NOTES' => GetMessage($module_id.'_OFFERS_PROPERTIES_HIGHLOAD_NOT_VALUE_PROPS_DESCR'),
    );
}



$arOptions['CHEXBOX_OFFERS_PROPERTIES_STRING'] = array(
    'GROUP' => 'OPTION_30',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_OFFERS_PROPERTIES_STRING_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '20',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_OFFERS_PROPERTIES_STRING_DESCR'),
);
$arOptions['CHEXBOX_OFFERS_PROPERTIES_LIST'] = array(
    'GROUP' => 'OPTION_30',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_OFFERS_PROPERTIES_LIST_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '30',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_OFFERS_PROPERTIES_LIST_DESCR'),
);
//фотографии в торговые предложения
$arOptions['CHEXBOX_OFFERS_MORE_PHOTO'] = array(
    'GROUP' => 'OPTION_33',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_OFFERS_MORE_PHOTO_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_OFFERS_MORE_PHOTO_DESCR'),
);
if(OptionGetValue('CHEXBOX_OFFERS_MORE_PHOTO')=='Y') {
    $arOptions['OFFERS_MORE_PHOTO_RAZDEL'] = array(
        'GROUP' => 'OPTION_33',
        'TITLE' => GetMessage($module_id.'_OFFERS_MORE_PHOTO_RAZDEL_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => '||',
        'SORT' => '20',
        'REFRESH' => 'N',
        'NOTES' => GetMessage($module_id.'_OFFERS_MORE_PHOTO_RAZDEL_DESCR'),
        'SIZE' => '10',
    );
    $arOptions['CHEXBOX_OFFERS_MORE_PHOTO_PREVIEW_PICTURE'] = array(
        'GROUP' => 'OPTION_33',
        'TITLE' => GetMessage($module_id.'_CHEXBOX_OFFERS_MORE_PHOTO_PREVIEW_PICTURE_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'N',
        'SORT' => '30',
        'NOTES' => GetMessage($module_id.'_CHEXBOX_OFFERS_MORE_PHOTO_PREVIEW_PICTURE_DESCR'),
    );
    $arOptions['CHEXBOX_OFFERS_MORE_PHOTO_DETAIL_PICTURE'] = array(
        'GROUP' => 'OPTION_33',
        'TITLE' => GetMessage($module_id.'_CHEXBOX_OFFERS_MORE_PHOTO_DETAIL_PICTURE_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'N',
        'SORT' => '40',
        'NOTES' => GetMessage($module_id.'_CHEXBOX_OFFERS_MORE_PHOTO_DETAIL_PICTURE_DESCR'),
    );
    $arOptions['CHEXBOX_OFFERS_MORE_PHOTO_MORE_ATTRIBUTES'] = array(
        'GROUP' => 'OPTION_33',
        'TITLE' => GetMessage($module_id.'_CHEXBOX_OFFERS_MORE_PHOTO_MORE_ATTRIBUTES_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y',
        'SORT' => '50',
        'NOTES' => GetMessage($module_id.'_CHEXBOX_OFFERS_MORE_PHOTO_MORE_ATTRIBUTES_DESCR'),
    );
    $arOptions['DUPLICATE_PHOTO_OFFERS'] = array(
        'GROUP' => 'OPTION_33',
        'TITLE' => GetMessage($module_id.'_DUPLICATE_PHOTO_OFFERS_TITLE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'N',
        'SORT' => '50',
        'NOTES' => GetMessage($module_id.'_DUPLICATE_PHOTO_OFFERS_DESCR'),
    );
    if(OptionGetValue('CHEXBOX_OFFERS_MORE_PHOTO_MORE_ATTRIBUTES')=='Y'){
        $arOptions['CHEXBOX_OFFERS_MORE_PHOTO_MORE_ATTRIBUTES_COUNT'] = array(
            'GROUP' => 'OPTION_33',
            'TITLE' => GetMessage($module_id.'_CHEXBOX_OFFERS_MORE_PHOTO_MORE_ATTRIBUTES_COUNT_TITLE'),
            'TYPE' => 'STRING',
            'REFRESH' => 'Y',
            'SORT' => '60',
            'NOTES' => GetMessage($module_id.'_CHEXBOX_OFFERS_MORE_PHOTO_MORE_ATTRIBUTES_COUNT_DESCR'),
        );
    }

}



$arOptions['CHEXBOX_MULTIPROPER'] = array(
    'GROUP' => 'OPTION_40',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_MULTIPROPER_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_MULTIPROPER_DESCR'),
);
$arOptions['CHEXBOX_MULTIPROPER_OFFERS'] = array(
    'GROUP' => 'OPTION_40',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_MULTIPROPER_OFFERS_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'SORT' => '15',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_MULTIPROPER_DESCR'),
);
if(OptionGetValue('CHEXBOX_MULTIPROPER')=='Y' || OptionGetValue('CHEXBOX_MULTIPROPER_OFFERS')=='Y') {
    $arOptions['STRING_MULTIPROPER_RAZDEL'] = array(
        'GROUP' => 'OPTION_40',
        'TITLE' => GetMessage($module_id.'_STRING_MULTIPROPER_RAZDEL_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => '||',
        'SORT' => '20',
        'REFRESH' => 'N',
        'NOTES' => GetMessage($module_id.'_STRING_MULTIPROPER_RAZDEL_DESCR'),
        'SIZE' => '10',
    );
    $arOptions['STRING_MULTIPROPER_ID'] = array(
        'GROUP' => 'OPTION_40',
        'TITLE' => GetMessage($module_id.'_STRING_MULTIPROPER_ID_TITLE'),
        'TYPE' => 'STRING',
        'VALUES' => '',
        'SORT' => '30',
        'NOTES' => GetMessage($module_id.'_STRING_MULTIPROPER_ID_DESCR'),
        'SIZE' => '70'
    );
    $arOptions['STRING_MULTIPROPER_ID_NO'] = array(
        'GROUP' => 'OPTION_40',
        'TITLE' => GetMessage($module_id.'_STRING_MULTIPROPER_ID_NO_TITLE'),
        'TYPE' => 'STRING',
        'VALUES' => '',
        'SORT' => '30',
        'NOTES' => GetMessage($module_id.'_STRING_MULTIPROPER_ID_NO_DESCR'),
        'SIZE' => '70'
    );
}





$arOptions['CHEXBOX_TRAITS_STRING'] = array(
    'GROUP' => 'OPTION_42',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_TRAITS_STRING_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_TRAITS_STRING_DESCR'),
);


if(OptionGetValue('CHEXBOX_TRAITS_STRING')=='Y'){
    $arOptions['GOODS_TRAITS_STRING'] = array(
        'GROUP' => 'OPTION_42',
        'TITLE' => GetMessage($module_id.'_GOODS_TRAITS_STRING_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'CML2_TRAITS',
        'REFRESH' => 'N',
        'SORT' => '10',
        'NOTES' => GetMessage($module_id.'_GOODS_TRAITS_STRING_DESCR'),
    );
}



$arOptions['CHEXBOX_GOODS_PROPERTIES_HIGHLOAD'] = array(
    'GROUP' => 'OPTION_43',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_GOODS_PROPERTIES_HIGHLOAD_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_GOODS_PROPERTIES_HIGHLOAD_DESCR'),
);



if(OptionGetValue('CHEXBOX_GOODS_PROPERTIES_HIGHLOAD')=='Y') {
    $arOptions['GOODS_PROPERTIES_HIGHLOAD_ONE_CAN'] = array(
        'GROUP' => 'OPTION_43',
        'TITLE' => GetMessage($module_id.'_GOODS_PROPERTIES_HIGHLOAD_ONE_CAN_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'CML2_MANUFACTURER',
        'REFRESH' => 'N',
        'SORT' => '30',
        'SIZE' => '70',
        'NOTES' => GetMessage($module_id.'_GOODS_PROPERTIES_HIGHLOAD_ONE_CAN_DESCR'),
    );
    $arOptions['GOODS_PROPERTIES_HIGHLOAD_MUST_NOT'] = array(
        'GROUP' => 'OPTION_43',
        'TITLE' => GetMessage($module_id.'_GOODS_PROPERTIES_HIGHLOAD_MUST_NOT_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'CML2_ARTICLE,CML2_TRAITS',
        'REFRESH' => 'N',
        'SORT' => '40',
        'SIZE' => '70',
        'NOTES' => GetMessage($module_id.'_GOODS_PROPERTIES_HIGHLOAD_MUST_NOT_DESCR'),
    );
    $arOptions['GOODS_PROPERTIES_HIGHLOAD_MUST_VALUE_NOT'] = array(
        'GROUP' => 'OPTION_43',
        'TITLE' => GetMessage($module_id.'_GOODS_PROPERTIES_HIGHLOAD_MUST_VALUE_NOT_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => '0',
        'REFRESH' => 'N',
        'SORT' => '45',
        'SIZE' => '70',
        'NOTES' => GetMessage($module_id.'_GOODS_PROPERTIES_HIGHLOAD_MUST_VALUE_NOT_DESCR'),
    );
}



$arOptions['CHEXBOX_GOODS_PROPERTIES'] = array(
    'GROUP' => 'OPTION_45',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_GOODS_PROPERTIES_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_GOODS_PROPERTIES_DESCR'),
);
if(OptionGetValue('CHEXBOX_GOODS_PROPERTIES')=='Y') {
    $arOptions['GOODS_PROPERTIES_IBLOCK'] = array(
        'GROUP' => 'OPTION_45',
        'TITLE' => GetMessage($module_id.'_GOODS_PROPERTIES_IBLOCK_TITLE'),
        'TYPE' => 'SELECT',
        'VALUES' => $arIblockType,
        'SORT' => '20',
        'NOTES' => GetMessage($module_id.'_GOODS_PROPERTIES_IBLOCK_DESCR'),
    );
    $arOptions['GOODS_PROPERTIES_ONE_CAN'] = array(
        'GROUP' => 'OPTION_45',
        'TITLE' => GetMessage($module_id.'_GOODS_PROPERTIES_ONE_CAN_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'CML2_MANUFACTURER',
        'REFRESH' => 'N',
        'SORT' => '30',
        'SIZE' => '70',
        'NOTES' => GetMessage($module_id.'_GOODS_PROPERTIES_ONE_CAN_DESCR'),
    );
    $arOptions['GOODS_PROPERTIES_MUST_NOT'] = array(
        'GROUP' => 'OPTION_45',
        'TITLE' => GetMessage($module_id.'_GOODS_PROPERTIES_MUST_NOT_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'CML2_ARTICLE,CML2_TRAITS',
        'REFRESH' => 'N',
        'SORT' => '40',
        'SIZE' => '70',
        'NOTES' => GetMessage($module_id.'_GOODS_PROPERTIES_MUST_NOT_DESCR'),
    );
}






$arOptions['SELECT_NONE_UPDATE'] = array(
    'GROUP' => 'OPTION_50',
    'TITLE' => GetMessage($module_id.'_SELECT_NONE_UPDATE_TITLE'),
    'TYPE' => 'MSELECT',
    'VALUES' => $arSelUpdateNone,
    'SORT' => '30',
    'NOTES' => GetMessage($module_id.'_SELECT_NONE_UPDATE_DESCR'),
);


$arOptions['SELECT_NONE_ADD'] = array(
    'GROUP' => 'OPTION_70',
    'TITLE' => GetMessage($module_id.'_SELECT_NONE_ADD_TITLE'),
    'TYPE' => 'MSELECT',
    'VALUES' => $arSelAddNone,
    'SORT' => '30',
    'NOTES' => GetMessage($module_id.'_SELECT_NONE_ADD_DESCR'),
);


$arOptions['SELECT_NONE_ADD_FOR_SECTION'] = array(
    'GROUP' => 'OPTION_100',
    'TITLE' => GetMessage($module_id.'_SELECT_NONE_ADD_FOR_SECTION_TITLE'),
    'TYPE' => 'MSELECT',
    'VALUES' => $arSelAddNoneForSection,
    'SORT' => '30',
    'NOTES' => GetMessage($module_id.'_SELECT_NONE_ADD_FOR_SECTION_DESCR'),
);

$arOptions['SELECT_NONE_UPDATE_FOR_SECTION'] = array(
    'GROUP' => 'OPTION_110',
    'TITLE' => GetMessage($module_id.'_SELECT_NONE_UPDATE_FOR_SECTION_TITLE'),
    'TYPE' => 'MSELECT',
    'VALUES' => $arSelUpdateNoneForSection,
    'SORT' => '30',
    'NOTES' => GetMessage($module_id.'_SELECT_NONE_UPDATE_FOR_SECTION_DESCR'),
);


$arOptions['CHEXBOX_DISCONT_EMPTY_DELETE'] = array(
    'GROUP' => 'OPTION_79',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_DISCONT_EMPTY_DELETE_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '20',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_DISCONT_EMPTY_DELETE_DESCR'),
);
$arOptions['DISCOUNT_CONDITION_BASKET'] = array(
    'GROUP' => 'OPTION_79',
    'TITLE' => GetMessage($module_id.'_DISCOUNT_CONDITION_BASKET_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '21',
    'NOTES' => GetMessage($module_id.'_DISCOUNT_CONDITION_BASKET_DESCR'),
);


$arOptions['SELECT_DISCONT_LAST_DISCOUNT'] = array(
    'GROUP' => 'OPTION_79',
    'TITLE' => GetMessage($module_id.'_SELECT_DISCONT_LAST_DISCOUNT_TITLE'),
    'TYPE' => 'SELECT',
    'VALUES' => $arSelDiscontLastDiscount,
    'SORT' => '30',
    'NOTES' => GetMessage($module_id.'_SELECT_DISCONT_LAST_DISCOUNT_DESCR'),
);

$arOptions['SELECT_DISCONT_GROUP_IDS'] = array(
    'GROUP' => 'OPTION_79',
    'TITLE' => GetMessage($module_id.'_SELECT_DISCONT_GROUP_IDS_TITLE'),
    'TYPE' => 'MSELECT',
    'VALUES' => $arSelGroupUsers,
    'SORT' => '40',
    'NOTES' => GetMessage($module_id.'_SELECT_DISCONT_GROUP_IDS_DESCR'),
    'SIZE' => '8'
);

$arOptions['SELECT_DISCONT_CATALOG_GROUP_IDS'] = array(
    'GROUP' => 'OPTION_79',
    'TITLE' => GetMessage($module_id.'_SELECT_DISCONT_CATALOG_GROUP_IDS_TITLE'),
    'TYPE' => 'MSELECT',
    'VALUES' => $arPriceArrList,
    'SORT' => '50',
    'NOTES' => GetMessage($module_id.'_SELECT_DISCONT_CATALOG_GROUP_IDS_DESCR'),
    'SIZE' => '4',
    'WIDTH' => '300'
);



$arOptions['CHEXBOX_DISCONT'] = array(
    'GROUP' => 'OPTION_80',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_DISCONT_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'SORT' => '20',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_DISCONT_DESCR'),
);

//$arOptions['SITE_DISCONT'] = array(
//    'GROUP' => 'OPTION_80',
//    'TITLE' => GetMessage($module_id.'_SITE_DISCONT_TITLE'),
//    'TYPE' => 'MSELECT',
//    "VALUES" => $site_ids,
//    'SORT' => '20',
//    'NOTES' => GetMessage($module_id.'_SITE_DISCONT_DESCR'),
//);
if(OptionGetValue('CHEXBOX_DISCONT')=='Y') {
    $arOptions['DISCONT_CODE'] = array(
        'GROUP' => 'OPTION_80',
        'TITLE' => GetMessage($module_id.'_DISCONT_CODE_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'SKIDKA',
        'SORT' => '30',
        'REFRESH' => 'N',
        'NOTES' => GetMessage($module_id.'_DISCONT_CODE_DESCR'),
    );
}



$arOptions['CHEXBOX_DISCONT_PRICE'] = array(
    'GROUP' => 'OPTION_90',
    'TITLE' => GetMessage($module_id.'_CHEXBOX_DISCONT_PRICE_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y',
    'SORT' => '20',
    'NOTES' => GetMessage($module_id.'_CHEXBOX_DISCONT_PRICE_DESCR'),
);
if(OptionGetValue('CHEXBOX_DISCONT_PRICE')=='Y') {
    $arOptions['DISCONT_PRICE_BASE'] = array(
        'GROUP' => 'OPTION_90',
        'TITLE' => GetMessage($module_id.'_DISCONT_PRICE_BASE_TITLE'),
        'TYPE' => 'SELECT',
        'VALUES' => $arPriceArrList,
        'SORT' => '30',
        'NOTES' => GetMessage($module_id.'_DISCONT_PRICE_BASE_DESCR'),
    );
    $arOptions['DISCONT_PRICE_DISCOUNT'] = array(
        'GROUP' => 'OPTION_90',
        'TITLE' => GetMessage($module_id.'_DISCONT_PRICE_DISCOUNT_TITLE'),
        'TYPE' => 'SELECT',
        'VALUES' => $arPriceArrList,
        'SORT' => '40',
        'NOTES' => GetMessage($module_id.'_DISCONT_PRICE_DISCOUNT_DESCR'),
    );
}



$arOptions['NOT_CHANGE_CANCEL_ORDER'] = array(
    'GROUP' => 'OPTION_60',
    'TITLE' => GetMessage($module_id.'_NOT_CHANGE_CANCEL_ORDER_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_NOT_CHANGE_CANCEL_ORDER_DESCR'),
);
$arOptions['NOT_DUPLICATE_PAYMENT'] = array(
    'GROUP' => 'OPTION_65',
    'TITLE' => GetMessage($module_id.'_NOT_DUPLICATE_PAYMENT_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '20',
    'NOTES' => GetMessage($module_id.'_NOT_DUPLICATE_PAYMENT_DESCR'),
);

$arOptions['NOT_DUPLICATE_SHIPMENT'] = array(
    'GROUP' => 'OPTION_65',
    'TITLE' => GetMessage($module_id.'_NOT_DUPLICATE_SHIPMENT_TITLE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'N',
    'SORT' => '30',
    'NOTES' => GetMessage($module_id.'_NOT_DUPLICATE_SHIPMENT_DESCR'),
);

$arOptions['DATE_SHIPMENT_ORDER'] = array(
    'GROUP' => 'OPTION_60',
    'TITLE' => GetMessage($module_id.'_DATE_SHIPMENT_ORDER_TITLE'),
    'SORT' => '40',
    'TYPE' => 'DATE_ORDER',
    'NOTES' => GetMessage($module_id.'_DATE_SHIPMENT_ORDER_DESCR'),
);

$arOptions['DELETE_ORDER'] = array(
    'GROUP' => 'OPTION_200',
    'TITLE' => GetMessage($module_id.'_DELETE_ORDER_TITLE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'N',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_DELETE_ORDER_DESCR'),
);


$arOptions['TYPE_IBLOCK_CHECKS_PRODUCT'] = array(
    'GROUP' => 'OPTION_200',
    'TITLE' => GetMessage($module_id.'_TYPE_IBLOCK_CHECKS_PRODUCT_TITLE'),
    'TYPE' => 'SELECT',
    'VALUES' => $arIblockType,
    'DEFAULT' => '1c_catalog',
    'SORT' => '30',
    'NOTES' => GetMessage($module_id.'_TYPE_IBLOCK_CHECKS_PRODUCT_DESCR'),
);

$arOptions['IBLOCK_CHECKS_CODE'] = array(
    'GROUP' => 'OPTION_200',
    'TITLE' => GetMessage($module_id.'_IBLOCK_CHECKS_CODE_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'CHECKS',
    'SORT' => '32',
    'REFRESH' => 'N',
    'NOTES' => GetMessage($module_id.'_IBLOCK_CHECKS_CODE_DESCR'),
);
$arOptions['IBLOCK_CHECKS_NAME'] = array(
    'GROUP' => 'OPTION_200',
    'TITLE' => GetMessage($module_id.'_IBLOCK_CHECKS_NAME_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage($module_id.'_IBLOCK_CHECKS_NAME'),
    'SORT' => '34',
    'REFRESH' => 'N',
    'NOTES' => GetMessage($module_id.'_IBLOCK_CHECKS_NAME_DESCR'),
);


$arOptions['IBLOCK_PRODUCTS'] = array(
    'GROUP' => 'OPTION_200',
    'TITLE' => GetMessage($module_id.'_IBLOCK_PRODUCTS_TITLE'),
    'TYPE' => 'SELECT',
    'VALUES' => $arIblockProper,
    'SORT' => '29',
    'REFRESH' => 'N',
    'NOTES' => GetMessage($module_id.'_IBLOCK_PRODUCTS_DESCR'),
);

$arOptions['ON_ADD_USER_CREATE_PROFILE'] = array(
    'GROUP' => 'OPTION_250',
    'TITLE' => GetMessage($module_id.'_ON_ADD_USER_CREATE_PROFILE_TITLE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'N',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_ON_ADD_USER_CREATE_PROFILE_DESC'),
);
if(OptionGetValue('CHEXBOX_GOODS_PROPERTIES') == 'Y'){
    $arOptions['CHEXBOX_GOODS_PROPERTIES_CODE'] = array(
        'GROUP' => 'OPTION_45',
        'TITLE' => GetMessage($module_id.'_CHEXBOX_GOODS_PROPERTIES_CODE_TITLE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'N',
        'SORT' => '10',
        'NOTES' => GetMessage($module_id.'_CHEXBOX_GOODS_PROPERTIES_CODE_DESC'),
    );
}

$arOptions['ON_ADD_USER_CREATE_PROFILE_ORDER'] = array(
    'GROUP' => 'OPTION_250',
    'TITLE' => GetMessage($module_id.'_ON_ADD_USER_CREATE_PROFILE_ORDER_TITLE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'N',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_ON_ADD_USER_CREATE_PROFILE_ORDER_DESC'),
);

$arOptions['CREATE_ORDER'] = array(
    'GROUP' => 'OPTION_250',
    'TITLE' => GetMessage($module_id.'_CREATE_ORDER_TITLE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'N',
    'SORT' => '10',
    'NOTES' => GetMessage($module_id.'_CREATE_ORDER_DESC'),
);
$arOptions['DICOUNT_SITE'] = array(
    'GROUP' => 'OPTION_79',
    'TITLE' => GetMessage($module_id.'_DICOUNT_SITE_TITLE'),
    'TYPE' => 'MSELECT',
    'VALUES' => $site_ids,
    'SORT' => '50',
    'NOTES' => GetMessage($module_id.'_DICOUNT_SITE_DESCR'),
);
$arOptions['UPDATE_PROFILE'] = array(
    'GROUP' => 'OPTION_250',
    'TITLE' => GetMessage($module_id.'_UPDATE_PROFILE_TITLE'),
    'TYPE' => 'CUSTOM',
    'VALUE' => '<input type="submit" name="update" value="'.GetMessage($module_id.'_UPDATE_PROFILE_BUT').'">',//'<input type="submit" name="UPDATE_PROFILE_TABLE" value="'.GetMessage($module_id.'_UPDATE_PROFILE_BUT').'">',
    'SORT' => '50',
    'NOTES' => GetMessage($module_id.'_UPDATE_PROFILE_DESCR'),
);
$arOptions['UNIC_INN'] = array(
    'GROUP' => 'OPTION_250',
    'TITLE' => GetMessage($module_id.'_UNIC_INN_TITLE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'N',
    'SORT' => '45',
    'NOTES' => GetMessage($module_id.'_UNIC_INN_DESC'),
);
//$arOptions['IS_UPDATE_PROFILE'] = array(
//    'GROUP' => 'OPTION_250',
//    'TITLE' => GetMessage($module_id.'_IS_UPDATE_PROFILE_TITLE'),
//    'TYPE' => 'CHECKBOX',
//    'DEFAULT' => 'Y',
//    'REFRESH' => 'N',
//    'SORT' => '45',
//    'NOTES' => GetMessage($module_id.'_IS_UPDATE_PROFILE_DESC'),
//);
/*
Конструктор класса CModuleOptions
$module_id - ID модуля
$arTabs - массив вкладок с параметрами
$arGroups - массив групп параметров
$arOptions - собственно сам массив, содержащий параметры
$showRightsTab - определяет надо ли показывать вкладку с настройками прав доступа к модулю ( true / false )
*/

/*
$opt = new CModuleOptions($module_id, $arTabs, $arGroups, $arOptions, $showRightsTab);
$opt->ShowHTML();
*/
?>

<a name="form"></a>



<?
$RIGHT = $APPLICATION->GetGroupRight($module_id);
if($RIGHT != "D") {


    if($RIGHT >= "W") {
        $showRightsTab = true;
    }

    $opt = new CModuleOptions($module_id, $arTabs, $arGroups, $arOptions, $showRightsTab);
    $opt->ShowHTML();
}


$tabControl = new CAdminTabControl("tabControl", $arTabs);
CJSCore::Init(array("jquery"));
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>