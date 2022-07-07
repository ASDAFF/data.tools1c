<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$iModuleID = "data.tools1c";
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$iModuleID."/include.php");
IncludeModuleLangFile(__FILE__);

//�������� ����
//�������� ����
$POST_RIGHT = $APPLICATION->GetGroupRight($iModuleID);
if ($POST_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}
//����� �������� ����

/*
$arrres = CDataToolsProperty::GetList(array('NAME_1C' => 'DESC'),array(),array());
while($res = $arrres->Fetch()){
    printr($res);
}*/


$sTableID = "tbl_data1ctools_discont"; // ID �������
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

// ������ �������� �������
$FilterArr = Array(
  //"find_NAME_1C",
  "find_DISCONT_ID",
);

// �������������� ������
$lAdmin->InitFilter($FilterArr);

// ���� ��� �������� ������� ���������, ���������� ���
if (CheckFilter())
{
  // �������� ������ ���������� ��� ������� CRubric::GetList() �� ������ �������� �������
  $arFilter = Array(
  //  "NAME_1C"  => $find_NAME_1C,
    "DISCONT_ID" => $find_IBLOCK_ID,
  );
}


// ******************************************************************** //
//                ��������� �������� ��� ���������� ������              //
// ******************************************************************** //

// ���������� ����������������� ���������
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
        $cData = new CDataToolsDiscount;
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
    $cData = new CDataToolsDiscount;
    $rsData = $cData->GetList(array($by=>$order), $arFilter);
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
          if(!CDataToolsDiscount::Delete($ID))
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


// ******************************************************************** //
//                ������� ��������� ������                              //
// ******************************************************************** //


$cData = new CDataToolsDiscount;
$rsData = $cData->GetList(array($by=>$order), $arFilter);


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
  /*
  array(  "id"    =>"NAME_1C",
    "content"  => GetMessage($iModuleID.'_list_title_NAME_1C'),
    "sort"    =>"NAME_1C",
    "align"    =>"left",
    "default"  =>true,
  ),  */
  array(  "id"    =>"DISCONT_ID",
    "content"  => GetMessage($iModuleID.'_list_title_DISCONT_ID'),
    "sort"    =>"DISCONT_ID",
    "default"  =>true,
  ),

  array("id" =>"DISCONT_VALUE",
    "content"  =>  GetMessage($iModuleID.'_list_title_DISCONT_VALUE'),
    "sort"    =>"DISCONT_VALUE",
    "default"  =>true,
  ),
  /*     */
  array("id" =>"PRODUCT",
    "content"  =>  GetMessage($iModuleID.'_list_title_PRODUCT'),
    "default"  =>true,
  ),


));
CModule::IncludeModule('sale');
//������� ������
$arrDiscont = array();
$arrDiscontProduct = array();
$type = COption::GetOptionString('data.tools1c' , "DISCOUNT_CONDITION_BASKET");
if($type == 'Y'){
    $dbProductDiscounts = CSaleDiscount::GetList(
        array("SORT" => "ASC"),
        array(),
        false,
        false,
        array(
            "ID", "NAME", "PRODUCT_ID", "CONDITIONS", "APPLICATION", "ACTIONS"
        )
    );
}else {
    $dbProductDiscounts = CCatalogDiscount::GetList(
        array("SORT" => "ASC"),
        array(),
        false,
        false,
        array(
            "ID", "NAME", "PRODUCT_ID", "CONDITIONS"
        )
    );
}
while ($arProductDiscounts = $dbProductDiscounts->Fetch())
{

    $CONDITIONS = array();
    if($type=='Y')
        $CONDITIONS = unserialize($arProductDiscounts["ACTIONS"]);
        else
    $CONDITIONS = unserialize($arProductDiscounts["CONDITIONS"]);

    if(count($CONDITIONS["CHILDREN"]) == 0){
        $arProductDiscounts['PRODUCT_ID'] = 9999999999;
    }

    $arrDiscont[$arProductDiscounts['ID']]['NAME'] = $arProductDiscounts['NAME'];
    if($type == 'Y') {
        foreach ($CONDITIONS['CHILDREN'] as $item) {
            foreach ($item["CHILDREN"] as $it) {
                if(isset($it['DATA']['value'][0]))
                    $arrDiscont[$arProductDiscounts['ID']]['PRODUCT_ID'][] = $it['DATA']['value'][0];
                else
                    $arrDiscont[$arProductDiscounts['ID']]['PRODUCT_ID'][] = $it['DATA']['value'];
            }
        }
    }else{
        $arrDiscont[$arProductDiscounts['ID']]['PRODUCT_ID'][] = $arProductDiscounts['PRODUCT_ID'];
    }
    $arrDiscontProduct[] = $arProductDiscounts['PRODUCT_ID'];
}
//die;
//������� ������
$arrProduct = array();

$arSelect = Array("ID", "NAME", "IBLOCK_ID", "IBLOCK_TYPE_ID");
$arFilter = Array("ID"=> $arrDiscontProduct);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($arFields = $res->Fetch())
{
    $arrProduct[$arFields['ID']] = $arFields;
}


while($arRes = $rsData->NavNext(true, "f_")):

  // ������� ������. ��������� - ��������� ������ CAdminListRow

  if(!$arrDiscont[$f_DISCONT_ID]){
      CDataToolsDiscount::DeleteByDiscontId($f_DISCONT_ID);
      continue;
  }
  else {
    $row =& $lAdmin->AddRow($f_ID, $arRes);
  }



  // ����� �������� ����������� �������� ��� ��������� � �������������� ������
  if($POST_RIGHT>="W") {
      //�������� NAME ����� ��������������� ��� �����, � ������������ �������
     // $row->AddInputField("NAME_1C", array("size"=>20));
    if($type == 'Y'){
        $link_DISCONT_ID = '[<a href="sale_discount_edit.php?ID='.$f_DISCONT_ID.'&lang='.LANG.'" target="_blank">'.$f_DISCONT_ID.'</a>] '.$arrDiscont[$f_DISCONT_ID]['NAME'] ;
        $row->AddViewField("DISCONT_ID", $link_DISCONT_ID);
    }else{
        $link_DISCONT_ID = '[<a href="cat_discount_edit.php?ID='.$f_DISCONT_ID.'&lang='.LANG.'" target="_blank">'.$f_DISCONT_ID.'</a>] '.$arrDiscont[$f_DISCONT_ID]['NAME'] ;
        $row->AddViewField("DISCONT_ID", $link_DISCONT_ID);
    }



     $link_DISCONT_VALUE = $f_DISCONT_VALUE.' ('.GetMessage($iModuleID.'_list_TYPE_VALUE_'.$f_TYPE).')';
     $row->AddViewField("DISCONT_VALUE", $link_DISCONT_VALUE);


     $text_PRODUCT = '';
     foreach($arrDiscont[$f_DISCONT_ID]['PRODUCT_ID'] as $prodId){
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
$APPLICATION->SetTitle(GetMessage($iModuleID.'_DISCONT_TITLE'));

// �� ������� ��������� ���������� ������ � �����
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// ******************************************************************** //
//                ����� �������                                         //
// ******************************************************************** //

// �������� ������ �������
$oFilter = new CAdminFilter(
  $sTableID."_filter",
  array(
   // GetMessage($iModuleID.'_list_title_NAME_1C'),
    GetMessage($iModuleID.'_list_title_DISCONT_ID')
  )
);?>

<form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
<?$oFilter->Begin();?>
<? /*
<tr>
    <td><?=GetMessage($iModuleID.'_list_title_NAME_1C')?>:</td>
    <td>
        <input type="text" name="find_NAME_1C" size="47" value="<?echo htmlspecialchars($find_NAME_1C)?>">
    </td>
</tr>
*/?>
<tr>
    <td><?=GetMessage($iModuleID.'_list_title_DISCONT_ID')?>:</td>
    <td>
        <input type="text" name="find_IBLOCK_ID" size="47" value="<?echo htmlspecialchars($find_IBLOCK_ID)?>">
    </td>
</tr>
<?
$oFilter->Buttons(array("table_id"=>$sTableID, "url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
$oFilter->End();
?>
</form>

<?
 /*
$re = CDataToolsIblock::GetList();
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