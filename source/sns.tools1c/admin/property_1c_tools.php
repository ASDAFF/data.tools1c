<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');   
$iModuleID = "sns.tools1c";
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$iModuleID."/include.php");
IncludeModuleLangFile(__FILE__);

//�������� ����
$POST_RIGHT = $APPLICATION->GetGroupRight($iModuleID);   
if ($POST_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));         
} 
//����� �������� ����  


$sTableID = "tbl_sns1ctools_property"; // ID �������
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
  "find_ID",   
  "find_NAME_1C",    
  "find_PROPERTY_TYPE",
);

// �������������� ������
$lAdmin->InitFilter($FilterArr);

// ���� ��� �������� ������� ���������, ���������� ���
if (CheckFilter())
{
  // �������� ������ ���������� ��� ������� CRubric::GetList() �� ������ �������� �������
  $arFilter = Array(
    "ID" => $find_ID, 
    "NAME_1C"  => $find_NAME_1C,   
    "PROPERTY_TYPE"  => $find_PROPERTY_TYPE,
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
        $cData = new CSnsToolsProperty;  
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
   $arID = array(); 
   $arID = $lAdmin->GroupAction(); 
  // ���� ������� "��� ���� ���������"
  if($_REQUEST['action_target']=='selected')
  {
    $cData = new CSnsToolsProperty;
    $rsData = $cData->GetList(array($by=>$order), $arFilter);
    while($arRes = $rsData->Fetch()){
      $arID[] = $arRes['ID'];
      $arIDfields[$arRes['ID']] = $arRes;       
    }

  }
   
  if(empty($arID)){
    $arID = array();    
  }
  // ������� �� ������ ���������
  if(count($arID)>0){  
      foreach($arID as $key=>$ID)
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
          if(!CSnsToolsProperty::Delete($ID))
          {
               
            $DB->Rollback();
            $lAdmin->AddGroupError(GetMessage("rub_del_err"), $ID);
          }  else {
            CSnsToolsIblock::DeleteByForPropertyId($arIDfields[$ID]['PROPERTY_ID']);      
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

// ������� ������ ��������
$cData = new CSnsToolsProperty;
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
  array(  "id"    =>"NAME_1C",
    "content"  => GetMessage($iModuleID.'_list_title_NAME_1C'),
    "sort"    =>"NAME_1C",
    "align"    =>"left",
    "default"  =>true,    
  ),
  array("id"    =>"PROPERTY_ID",
    "content"  => GetMessage($iModuleID.'_list_title_PROPERTY_ID'), 
    "sort"    =>"PROPERTY_ID",
    "align"    =>"left",
    "default"  =>true,    
  ),
  array(  "id"    =>"PROPERTY_IBLOCK",
    "content"  => GetMessage($iModuleID.'_list_title_PROPERTY_IBLOCK'),  
    "sort"    =>"PROPERTY_IBLOCK",
    "default"  =>true,    
  ),
  array("id" =>"PROPERTY_TYPE",
    "content"  =>  GetMessage($iModuleID.'_list_title_PROPERTY_TYPE'),   
    "sort"    =>"PROPERTY_TYPE",
    "default"  =>true,    
  ),
  array("id" =>"TYPE",
    "content"  =>  GetMessage($iModuleID.'_list_title_TYPE'),   
    "sort"    =>"TYPE",
    "default"  =>true,    
  ),  
  array("id" =>"FOR_PROPERTY_ID",
    "content"  =>  GetMessage($iModuleID.'_list_title_FOR_PROPERTY_ID'),   
    "sort"    =>"FOR_PROPERTY_ID",
    "default"  =>true,    
  ),    
  array("id" =>"PROPERTY_HIGHLOAD_TABLE",
    "content"  =>  GetMessage($iModuleID.'_list_title_PROPERTY_HIGHLOAD_TABLE'),   
    "sort"    =>"PROPERTY_HIGHLOAD_TABLE",
    "default"  =>true,    
  ),  
  
));

//������� ��� ��������
$allproperty = array();
$properties = CIBlockProperty::GetList(Array(), Array());
while ($prop_fields = $properties->GetNext())
{
    $allproperty[$prop_fields["ID"]] = $prop_fields;
}

$alliblock = array();
$iblock = CIBlock::GetList(Array(), Array());
while ($iblock_fields = $iblock->GetNext())
{
    $alliblock[$iblock_fields["ID"]] = $iblock_fields;
}




while($arRes = $rsData->NavNext(true, "f_")):
  
  // ������� ������. ��������� - ��������� ������ CAdminListRow
 
  
  
  if(!$allproperty[$f_PROPERTY_ID]) {
      CSnsToolsProperty::DeleteByPropertyId($f_PROPERTY_ID); 
      continue;
  }  
  else{
      $row =& $lAdmin->AddRow($f_ID, $arRes); 
  } 
       

  
  // ����� �������� ����������� �������� ��� ��������� � �������������� ������         
  if($POST_RIGHT>="W") {  
    //�������� NAME ����� ��������������� ��� �����, � ������������ �������
    $row->AddInputField("NAME_1C", array("size"=>20));    
       
       
    $link_PROPERTY_IBLOCK = '[<a href="iblock_edit.php?type='.$alliblock[$f_PROPERTY_IBLOCK]['IBLOCK_TYPE_ID'].'&lang='.LANG.'&ID='.$f_PROPERTY_IBLOCK.'" target="_blank">'.$f_PROPERTY_IBLOCK.'</a>] '.$alliblock[$f_PROPERTY_IBLOCK]['NAME'].'' ;     
    $row->AddViewField("PROPERTY_IBLOCK", $link_PROPERTY_IBLOCK);   
     
    $link_PROPERTY_ID = '[<a href="iblock_edit_property.php?ID='.$f_PROPERTY_ID.'&lang='.LANG.'&IBLOCK_ID='.$allproperty[$f_PROPERTY_ID]['IBLOCK_ID'].'" target="_blank">'.$f_PROPERTY_ID.'</a>] '.$allproperty[$f_PROPERTY_ID]['NAME'].'' ;     
    $row->AddViewField("PROPERTY_ID", $link_PROPERTY_ID); 
    
    $link_FOR_PROPERTY_ID = '[<a href="iblock_edit_property.php?ID='.$f_FOR_PROPERTY_ID.'&lang='.LANG.'&IBLOCK_ID='.$allproperty[$f_FOR_PROPERTY_ID]['IBLOCK_ID'].'" target="_blank">'.$f_FOR_PROPERTY_ID.'</a>] '.$allproperty[$f_FOR_PROPERTY_ID]['NAME'].'' ;     
    $row->AddViewField("FOR_PROPERTY_ID", $link_FOR_PROPERTY_ID);
    
    
    $row->AddViewField("PROPERTY_TYPE", GetMessage($iModuleID.'_list_title_PROPERTY_TYPE_LIST_'.$f_PROPERTY_TYPE));
    
    //$row->AddInputField("PROPERTY_ID", array("size"=>20));    
    //$row->AddViewField("NAME_1C", $f_NAME_1C);   
     /*
     if(GetMessage($iModuleID.'_direct_'.$f_TYPE)) {
        $name_TYPE = GetMessage($iModuleID.'_direct_'.$f_TYPE);  
        $row->AddViewField("TYPE", $name_TYPE);              
      } */
     
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
$APPLICATION->SetTitle(GetMessage($iModuleID.'_PROPERTY_TITLE'));  

// �� ������� ��������� ���������� ������ � �����
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// ******************************************************************** //
//                ����� �������                                         //
// ******************************************************************** //
 

// �������� ������ �������
$oFilter = new CAdminFilter(
  $sTableID."_filter",
  array(
    GetMessage($iModuleID.'_list_title_NAME_1C'),
    GetMessage($iModuleID.'_list_title_PROPERTY_TYPE') 
  )
);
?>
<form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
<?$oFilter->Begin();?>
<tr>
  <td>ID:</td>
  <td>
    <input type="text" name="find_ID" size="47" value="<?echo htmlspecialchars($find_ID)?>">
  </td>
</tr>
<tr>
  <td><?=GetMessage($iModuleID.'_list_title_NAME_1C')?>:</td>
  <td>
    <input type="text" name="find_NAME_1C" size="47" value="<?echo htmlspecialchars($find_NAME_1C)?>">
  </td>
</tr>

<tr>
  <td><?=GetMessage($iModuleID.'_list_title_PROPERTY_TYPE')?> </td>
  <td>
    <?
    $arr = array(
      "reference" => array(
        GetMessage($iModuleID."_list_title_PROPERTY_TYPE_LIST_S"),
        GetMessage($iModuleID."_list_title_PROPERTY_TYPE_LIST_L"),
        GetMessage($iModuleID."_list_title_PROPERTY_TYPE_LIST_E"), 
        GetMessage($iModuleID."_list_title_PROPERTY_TYPE_LIST_S:directory"),
      ),
      "reference_id" => array(
        "S",
        "L",        
        "E",
        "S:directory"
      )
    );
    echo SelectBoxFromArray("find_PROPERTY_TYPE", $arr,  $find_PROPERTY_TYPE, GetMessage($iModuleID."_list_title_PROPERTY_TYPE_LIST_ALL"), "");
    ?>
  </td>
</tr>

<?
$oFilter->Buttons(array("table_id"=>$sTableID, "url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
$oFilter->End();
?>
</form>

<?
/*  
$re = CSnsToolsProperty::GetList(array(), array('PROPERTY_IBLOCK'=>29), array(''));
while($arrRes = $re->Fetch()) { 
    printr($arrRes);    
} 

         
$d = CSnsToolsEvent::GetMultipleProperElement(array(
    'ID' => '16365',
    'IBLOCK_ID' => '52'
));
printr($d);   
 */ 
 
/*
$siteId = array();
$rsSites = CSite::GetList($by="sort", $order="desc", Array());
while ($arSite = $rsSites->Fetch())
{
    $siteId[] = $arSite['LID'];
}  
 
$arrFields = array(
    ''    
);

  
$siteid = CSnsToolsEvent::GetIblockSiteById(171);

$arLogic = array ( 
    "CLASS_ID" => "CondGroup", 
    "DATA" => array ( 
        "All" => "OR", 
        "True" => "True", 
    ), 
    "CHILDREN" => array ( 
        array("CLASS_ID" => "CondIBElement", "DATA" => array ("logic" => "Equal", "value" => 22460)), 
        array("CLASS_ID" => "CondIBElement", "DATA" => array ("logic" => "Equal", "value" => 22487)),
        
    ), 
); 

$arrFields = array(
    'SITE_ID' => $siteid[0],
    'ACTIVE' => 'Y',
    'NAME' => '������ �� 1�',
    'VALUE_TYPE' => 'P',
    'VALUE' => 20,
    'CURRENCY' => 'RUB',
    'CONDITIONS' => $arLogic, 
);

//$s = CCatalogDiscount::Add($arrFields);  
$s = CCatalogDiscount::Update(2,$arrFields);     
printr($S);
*/      

        
?>

<?// ������� ������� ������ ���������
$lAdmin->DisplayList();
?>


<?
// ���������� ��������
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>

