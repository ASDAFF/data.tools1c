<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$iModuleID = "sns.tools1c";
CModule::IncludeModule($iModuleID);
CModule::IncludeModule('sale');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$iModuleID."/include.php");
IncludeModuleLangFile(__FILE__);
use Sns\Tools1c\ChecksTable;

$POST_RIGHT = $APPLICATION->GetGroupRight($iModuleID);
if ($POST_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}
//����� �������� ����

$sTableID = "tbl_sns1ctools_checks"; // ID �������
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
            $cData = new ChecksTable();
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
        $rsData = ChecksTable::GetList(array(), array());
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
                    $is_delete = COption::GetOptionString($iModuleID, "DELETE_ORDER");
                    if($is_delete == 'Y'){
                        CSaleOrder::PayOrder($ID, "N", True, True, 0, array());
                            $delete = ChecksTable::getById($ID)->fetch();
                    }

                    @set_time_limit(0);
                    $DB->StartTransaction();
                    if(!ChecksTable::delete($ID))
                    {
                        $DB->Rollback();
                        $lAdmin->AddGroupError(GetMessage("rub_del_err"), $ID);
                    }
                    if($is_delete == 'Y')
                        CSaleOrder::delete($delete['ORDER_ID']);

                    $DB->Commit();
                    break;
            }
        }
    }



}


// ������ �������� �������
$FilterArr = Array(
    //"find_NAME_1C",
    "find_ORDER_ID",
    "find_CHECK_NUMBER",
);

// �������������� ������
$lAdmin->InitFilter($FilterArr);
$arFilter = array();
// ���� ��� �������� ������� ���������, ���������� ���
if (CheckFilter())
{
    // �������� ������ ���������� ��� ������� CRubric::GetList() �� ������ �������� �������
    $arFilter = Array(
        "ORDER_ID" => $find_ORDER_ID,
        "CHECK_NUMBER" => $find_CHECK_NUMBER,
    );
}
if(is_null($find_ORDER_ID)) unset($arFilter['ORDER_ID']);
if(is_null($find_CHECK_NUMBER)) unset($arFilter['CHECK_NUMBER']);

// ******************************************************************** //
//                ������� ��������� ������                              //
// ******************************************************************** //
$arFilterOrder = array();
$rsData = ChecksTable::GetList(Array('order'=>array($by=>$order),'filter'=> $arFilter));
while($order = $rsData->fetch()){
    $arFilterOrder['ID'][] = $order['ORDER_ID'];
}

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
    array(  "id"  =>"ORDER_ID",
        "content"  => GetMessage($iModuleID.'_list_title_ORDER_ID'),
        "sort"    =>"ORDER_ID",
        "default"  =>true,
    ),

    array("id" =>"CHECK_NUMBER",
        "content"  =>  GetMessage($iModuleID.'_list_title_CHECK_NUMBER'),
        "sort"    =>"CHECK_NUMBER",
        "default"  =>true,
    ),
    array("id" =>"CHECK_NAME",
        "content"  =>  GetMessage($iModuleID.'_list_title_CHECK_NAME'),
        "sort"    =>"CHECK_NAME",
        "default"  =>true,
    ),
    /*     */
    array("id" =>"PRODUCT",
        "content"  =>  GetMessage($iModuleID.'_list_title_PRODUCT'),
        "default"  =>true,
    ),
));

$checks = CSaleOrder::GetList(array(), $arFilterOrder);
$id_products = array();
$arrOrder = array();
while ($id = $checks->fetch()) {
    $basket = \Bitrix\Sale\Order::load($id['ID']);
    $basketItems = $basket->getBasket();
    foreach ($basketItems as $basketItem) {
        $id_products[$id['ID']][] = $basketItem->getField('PRODUCT_ID');
        $arrOrder['ID'][] = $basketItem->getField('PRODUCT_ID');
    }


}




//������� ������
$arrProduct = array();

$arSelect = Array("ID", "NAME", "IBLOCK_ID", "IBLOCK_TYPE_ID");
$res = CIBlockElement::GetList(Array(), $arrOrder, false, false, $arSelect);
while($arFields = $res->Fetch())
{
    $arrProduct[$arFields['ID']] = $arFields;
}


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

        $link_ORDER_ID = '[<a href="sale_order_edit.php?ID='.$f_ORDER_ID.'&lang='.LANG.'" target="_blank">'.$f_ORDER_ID.'</a>] ';
        $row->AddViewField("ORDER_ID", $link_ORDER_ID);

        $text_PRODUCT = '';

        foreach($id_products[$f_ORDER_ID] as $prodId){

            if($arrProduct[$prodId]) {
                $text_PRODUCT .= '[<a href="iblock_element_edit.php?IBLOCK_ID='.$arrProduct[$prodId]['IBLOCK_ID'].'&type='.$arrProduct[$prodId]['IBLOCK_TYPE_ID'].'&ID='.$prodId.'&lang='.LANG.'" target="_blank">'.$prodId.'</a>] '.$arrProduct[$prodId]['NAME'].'<br />';
            }

        }

        $row->AddViewField("PRODUCT", $text_PRODUCT);

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

$lAdmin->AddAdminContextMenu();


// ******************************************************************** //
//                �����                                                 //
// ******************************************************************** //

// �������������� �����
$lAdmin->CheckListMode();

// ��������� ��������� ��������
$APPLICATION->SetTitle(GetMessage($iModuleID.'_ORDER_TITLE'));

// �� ������� ��������� ���������� ������ � �����
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// ******************************************************************** //
//                ����� �������                                         //
// ******************************************************************** //

// �������� ������ �������
$oFilter = new CAdminFilter(
    $sTableID."_filter",
    array(
        GetMessage($iModuleID.'_list_title_ORDER_ID'),
        GetMessage($iModuleID.'_list_title_CHECK_NUMBER')
    )
);?>

    <form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
        <?$oFilter->Begin();?>

        <tr>
            <td><?=GetMessage($iModuleID.'_list_title_ORDER_ID')?>:</td>
            <td>
                <input type="text" name="find_ORDER_ID" size="47" value="<?echo htmlspecialchars($find_ORDER_ID)?>">
            </td>
        </tr>
        <tr>
            <td><?=GetMessage($iModuleID.'_list_title_CHECK_NUMBER')?>:</td>
            <td>
                <input type="text" name="find_CHECK_NUMBER" size="47" value="<?echo htmlspecialchars($find_CHECK_NUMBER)?>">
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


<?// ������� ������� ������ ���������
$lAdmin->DisplayList();
?>


<?
//���������� ��������
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>