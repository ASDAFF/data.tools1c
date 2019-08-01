<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$iModuleID = "sns.tools1c";
CModule::IncludeModule($iModuleID);
CModule::IncludeModule('sale');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$iModuleID."/include.php");
IncludeModuleLangFile(__FILE__);
use Sns\Tools1c\ProfileTable;

$POST_RIGHT = $APPLICATION->GetGroupRight($iModuleID);
if ($POST_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}
//����� �������� ����

$sTableID = "tbl_sns1ctools_profile"; // ID �������
$oSort = new CAdminSorting($sTableID, "ID", "asc"); // ������ ����������
$lAdmin = new CAdminList($sTableID, $oSort); // �������� ������ ������

// ******************************************************************** //
//                           ������                                     //
// ******************************************************************** //

// *********************** CheckFilter ******************************** //
// �������� �������� ������� ��� �������� ������� � ��������� �������
function CheckFilter()
{
    global $FilterArr, $lAdmin;
    foreach ($FilterArr as $f) global $$f;

    // � ������ ������ ��������� ������.
    // � ����� ������ ����� ��������� �������� ���������� $find_���
    // � � ������ �������������� ������ ���������� �� �����������
    // ����������� $lAdmin->AddFilterError('�����_������').

    return count($lAdmin->arFilterErrors)==0; // ���� ������ ����, ������ false;
}
// *********************** /CheckFilter ******************************* //


if($POST_RIGHT=="W")
{
    $lAdmin->EditAction();
    // ������� �� ������ ���������� ���������
    if(count($FIELDS)>0){
        foreach($FIELDS as $ID=>$arFields)
        {

            if(!$lAdmin->IsUpdated($ID))
                continue;

            // �������� ��������� ������� ��������
            $DB->StartTransaction();
            $ID = IntVal($ID);
            $cData = new ProfileTable();
            if(!$cData->Update($ID, $arFields))
            {
                $lAdmin->AddGroupError(GetMessage($iModuleID.'_ERROR_DB').' '.$cData->LAST_ERROR, $ID);
                $DB->Rollback();
            }

            global $CACHE_MANAGER;
            $CACHE_MANAGER->ClearByTag($iModuleID);
            $DB->Commit();
        }
    }



}


// ��������� ��������� � ��������� ��������
if($POST_RIGHT=="W")
{
    $arID = $lAdmin->GroupAction();
    // ���� ������� "��� ���� ���������"
    if($_REQUEST['action_target']=='selected')
    {
        $rsData = ProfileTable::GetList(array(), array());
        while($arRes = $rsData->Fetch())
            $arID[] = $arRes['ID'];
    }


    if(empty($arID)){
        $arID = array();
    }
    // ������� �� ������ ���������
    if(count($arID)>0){
        foreach($arID as $ID)
        {
            if(strlen($ID)<=0)
                continue;
            $ID = IntVal($ID);

            // ��� ������� �������� �������� ��������� ��������
            switch($_REQUEST['action'])
            {
                // ��������
                case "delete":
                    @set_time_limit(0);
                    $DB->StartTransaction();
                    if(!ProfileTable::delete($ID))
                    {
                        $DB->Rollback();
                        $lAdmin->AddGroupError(GetMessage("rub_del_err"), $ID);
                    }
                    $DB->Commit();
                    break;
            }
        }
    }



}


// ������ �������� �������
$FilterArr = Array(
    //"find_NAME_1C",
    "find_USER_ID",
    "find_PROFILE_ID",
);

// �������������� ������
$lAdmin->InitFilter($FilterArr);
$arFilter = array();
// ���� ��� �������� ������� ���������, ���������� ���
if (CheckFilter())
{
    // �������� ������ ���������� ��� ������� CRubric::GetList() �� ������ �������� �������
    $arFilter = Array(
        "USER_ID" => $find_USER_ID,
        "PROFILE_ID" => $find_PROFILE_ID,
    );
}
if(is_null($find_USER_ID)) unset($arFilter['USER_ID']);
if(is_null($find_PROFILE_ID)) unset($arFilter['PROFILE_ID']);

// ******************************************************************** //
//                ������� ��������� ������                              //
// ******************************************************************** //
$arFilterOrder = array();
$rsData = ProfileTable::GetList(Array('order'=>array($by=>$order),'filter'=> $arFilter));
$content = array();
while($data = $rsData->fetch()){
    $user = CUser::GetByID($data['USER_ID'])->fetch();
    $user_profile = CSaleOrderUserProps::GetByID($data['PROFILE_ID']);
    $content['USER'][$user['ID']] = $user;
    $content['PROFILE'][$user_profile['ID']] = $user_profile;
}
//
// ����������� ������ � ��������� ������ CAdminResult
$rsData = new CAdminResult($rsData, $sTableID);

// ���������� CDBResult �������������� ������������ ���������.
$rsData->NavStart();

// �������� ����� ������������� ������� � �������� ������ $lAdmin
$lAdmin->NavText($rsData->GetNavPrint(GetMessage($iModuleID.'_NAV_TITLE')));

// ******************************************************************** //
//                ���������� ������ � ������                            //
// ******************************************************************** //

$lAdmin->AddHeaders(array(
    array("id"    =>"ID",
        "content"  =>'ID',
        "sort"    =>"ID",
        "align"    =>"right",
        "default"  =>true,
    ),
    array(  "id"  =>"USER_ID",
        "content"  => GetMessage($iModuleID.'_list_title_USER_ID'),
        "sort"    =>"USER_ID",
        "default"  =>true,
    ),

    array("id" =>"PROFILE_ID",
        "content"  =>  GetMessage($iModuleID.'_list_title_PROFILE_ID'),
        "sort"    =>"PROFILE_ID",
        "default"  =>true,
    ),
    array("id" =>"INN",
        "content"  =>  GetMessage($iModuleID.'_list_title_INN'),
        "sort"    =>"INN",
        "default"  =>true,
    ),
    array("id" =>"PROFILE_TYPE",
        "content"  =>  GetMessage($iModuleID.'_list_title_PROFILE_TYPE'),
        "sort"    =>"PROFILE_TYPE",
        "default"  =>true,
    ),
));

//$checks = CSaleOrder::GetList(array(), $arFilterOrder);
//$id_products = array();
//$arrOrder = array();
//while ($id = $checks->fetch()) {
//    $basket = \Bitrix\Sale\Order::load($id['ID']);
//    $basketItems = $basket->getBasket();
//    foreach ($basketItems as $basketItem) {
//        $id_products[$id['ID']][] = $basketItem->getField('PRODUCT_ID');
//        $arrOrder['ID'][] = $basketItem->getField('PRODUCT_ID');
//    }
//}
//ProfileTable::GetList();
//$rsUsers = CUser::GetList(($by="personal_country"), ($order="desc"), array('ID'=>$data['USER_ID'])); // �������� �������������




//������� ������
//$arrProduct = array();
//
//$arSelect = Array("ID", "NAME", "IBLOCK_ID", "IBLOCK_TYPE_ID");
//$res = CIBlockElement::GetList(Array(), $arrOrder, false, false, $arSelect);
//while($arFields = $res->Fetch())
//{
//    $arrProduct[$arFields['ID']] = $arFields;
//}


while($arRes = $rsData->NavNext(true, "f_")):

    // ������� ������. ��������� - ��������� ������ CAdminListRow

//    if(!$arrOrder[$f_ORDER_ID]){
//        continue;
//    }
//    else {
    $row =& $lAdmin->AddRow($f_ID, $arRes);
//    }

    // ����� �������� ����������� �������� ��� ��������� � �������������� ������
    if($POST_RIGHT>="W") {

        $link_USER_ID = '[<a href="user_edit.php?ID='.$f_USER_ID.'&lang='.LANG.'" target="_blank">'.$f_USER_ID.'</a>] '.$content['USER'][$f_USER_ID]['LAST_NAME'].' '.$content['USER'][$f_USER_ID]['NAME'].' '.$content['USER'][$f_USER_ID]['SECOND_NAME'];
        $row->AddViewField("USER_ID", $link_USER_ID);

        $text = '[<a href="sale_buyers_profile_edit.php?ID='.$f_PROFILE_ID.'&lang='.LANG.'" target="_blank">'.$f_PROFILE_ID.'</a>] '.$content['PROFILE'][$f_PROFILE_ID]['NAME'];


        $row->AddViewField("PROFILE_ID", $text);

        // �������� ����������� ���� � ������
        $arActions = array();
        if(count($arActions)>0){
            $row->AddActions($arActions);
        }
    }




endwhile;

// ������ �������
$lAdmin->AddFooter(
    array(
        array(
            "title"=> GetMessage("MAIN_ADMIN_LIST_SELECTED"),
            "value"=>$rsData->SelectedRowsCount()), // ���-�� ���������
        array(
            "counter"=>true,
            "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"),
            "value"=>"0"
        ), // ������� ��������� ���������
    )
);

// ��������� ��������
if($POST_RIGHT>="W") {

    $GroupActionTable = array(
        "delete"=>GetMessage($iModuleID."_ACTION_DEL"), // ������� ��������� ��������
    );

}


$lAdmin->AddGroupActionTable($GroupActionTable);

// ******************************************************************** //
//                ���������������� ����                                 //
// ******************************************************************** //
$aContext = array(
    array(
        "TEXT"=>GetMessage($iModuleID.'_UPDATE'),
        "LINK"=>"#update",
        'LINK_PARAM'=>'onClick="update(); return false;"',
        "TITLE"=>GetMessage($iModuleID.'_UPDATE'),
    ),
);
$lAdmin->AddAdminContextMenu($aContext);


// ******************************************************************** //
//                �����                                                 //
// ******************************************************************** //

// �������������� �����
$lAdmin->CheckListMode();

// ��������� ��������� ��������
$APPLICATION->SetTitle(GetMessage($iModuleID.'_PROFILE_TITLE'));

// �� ������� ��������� ���������� ������ � �����
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// ******************************************************************** //
//                ����� �������                                         //
// ******************************************************************** //

// �������� ������ �������
$oFilter = new CAdminFilter(
    $sTableID."_filter",
    array(
        GetMessage($iModuleID.'_list_title_USER_ID'),
        GetMessage($iModuleID.'_list_title_PROFILE_ID')
    )
);?>

    <form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
        <?$oFilter->Begin();?>

        <tr>
            <td><?=GetMessage($iModuleID.'_list_title_USER_ID')?>:</td>
            <td>
                <input type="text" name="find_USER_ID" size="47" value="<?echo htmlspecialchars($find_USER_ID)?>">
            </td>
        </tr>
        <tr>
            <td><?=GetMessage($iModuleID.'_list_title_PROFILE_ID')?>:</td>
            <td>
                <input type="text" name="find_PROFILE_ID" size="47" value="<?echo htmlspecialchars($find_PROFILE_ID)?>">
            </td>
        </tr>
        <?
        $oFilter->Buttons(array("table_id"=>$sTableID, "url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
        $oFilter->End();
        ?>
    </form>

<?
/*
$re = CSnsToolsIblock::GetList();
while($arrRes = $re->Fetch()) {
   printr($arrRes);
}
 */

?>
<script>

    function update(){
        BX.ajax({
            url: '/bitrix/admin/settings_1c_tools.php',
            data: {
                update: true
            },
            method: 'POST',
            dataType: 'json',
            async: true,
            processData: true,
            scriptsRunFirst: true,
            emulateOnload: true,
            start: true,
            cache: false,
            onsuccess: function(data){
                location.reload();
        },
        onfailure: function(){
            location.reload();
        }
    });
    }
</script>

<?// ������� ������� ������ ���������
$lAdmin->DisplayList();
?>


<?
//���������� ��������
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>