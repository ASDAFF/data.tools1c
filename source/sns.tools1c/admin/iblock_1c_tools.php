<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');   
$iModuleID = "sns.tools1c";
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
$arrres = CSnsToolsProperty::GetList(array('NAME_1C' => 'DESC'),array(),array());
while($res = $arrres->Fetch()){
    printr($res);   
}*/


$sTableID = "tbl_sns1ctools_iblock"; // ID �������
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
  "find_IBLOCK_ID",      
);

// �������������� ������
$lAdmin->InitFilter($FilterArr);

// ���� ��� �������� ������� ���������, ���������� ���
if (CheckFilter())
{
  // �������� ������ ���������� ��� ������� CRubric::GetList() �� ������ �������� �������
  $arFilter = Array(
  //  "NAME_1C"  => $find_NAME_1C,    
    "IBLOCK_ID" => $find_IBLOCK_ID,  
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
        $cData = new CSnsToolsIblock;  
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
    $cData = new CSnsToolsIblock;
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
          if(!CSnsToolsIblock::Delete($ID))
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

// ������� ������ ��������
$cData = new CSnsToolsIblock;
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
  array(  "id"    =>"IBLOCK_ID",
    "content"  => GetMessage($iModuleID.'_list_title_IBLOCK_ID'),  
    "sort"    =>"IBLOCK_ID",
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


$propertyE1ctools = array(); 
$proper1ctools = CSnsToolsProperty::GetList(array(), array('PROPERTY_TYPE'=>'E'), array(''));
while($arrProper1ctools = $proper1ctools->Fetch()) { 
    $propertyE1ctools[$arrProper1ctools["PROPERTY_ID"]] = $arrProper1ctools;    
} 


// ������� �����������
if(CModule::IncludeModule("highloadblock")){
    $allHighLoadiblock = array();
    $allHighLoadiblockTable = array();
    $resHighLoadiblock = Bitrix\Highloadblock\HighloadBlockTable::getList(array());     
    while($ar_resHighLoadiblock = $resHighLoadiblock->fetch())
    {
        $allHighLoadiblock[$ar_resHighLoadiblock["ID"]] = $ar_resHighLoadiblock;  
        $allHighLoadiblockTable[$ar_resHighLoadiblock["TABLE_NAME"]] = $ar_resHighLoadiblock;      
    }     
}



 

$tableRow = array();
while($arRes = $rsData->NavNext(true, "f_")){
          
  $tableRow[$arRes['FOR_PROPERTY_ID']] = $arRes;
  
  
  
  if($arRes['IBLOCK_TYPE'] == 'HIGHLOAD'){
        if(!$allHighLoadiblock[$arRes['IBLOCK_ID']]) {
            CSnsToolsIblock::DeleteByIblockId($arRes['IBLOCK_ID']);                  
        } else {
            $row =& $lAdmin->AddRow($arRes['ID'], $arRes);             
        }
        
  }
  else {
   
      // ������� ������. ��������� - ��������� ������ CAdminListRow
      if(!$alliblock[$arRes['IBLOCK_ID']] || !$allproperty[$arRes['FOR_PROPERTY_ID']] || !$propertyE1ctools[$arRes['FOR_PROPERTY_ID']]){
          CSnsToolsIblock::DeleteByIblockId($arRes['IBLOCK_ID']);                 
          continue;
      } else {
          $row =& $lAdmin->AddRow($arRes['ID'], $arRes);    
      }      
   
      
  }
  

   

  // ����� �������� ����������� �������� ��� ��������� � �������������� ������         
  if($POST_RIGHT>="W") {           
      //�������� NAME ����� ��������������� ��� �����, � ������������ �������
     // $row->AddInputField("NAME_1C", array("size"=>20));    

      if($arRes['IBLOCK_TYPE'] == 'HIGHLOAD') {
            $link_IBLOCK_ID = '[<a href="highloadblock_rows_list.php?ENTITY='.$arRes['IBLOCK_ID'].'&lang='.LANG.'" target="_blank">'.$arRes['IBLOCK_ID'].'</a>] '.$allHighLoadiblock[$arRes['IBLOCK_ID']]['NAME'].' [HighLoad]' ;     
            $row->AddViewField("IBLOCK_ID", $link_IBLOCK_ID);          
      } else {
    
            $link_IBLOCK_ID = '[<a href="iblock_edit.php?type='.$alliblock[$arRes['IBLOCK_ID']]['IBLOCK_TYPE_ID'].'&lang='.LANG.'&ID='.$arRes['IBLOCK_ID'].'" target="_blank">'.$arRes['IBLOCK_ID'].'</a>] '.$alliblock[$arRes['IBLOCK_ID']]['NAME'].' ' ;     
            $row->AddViewField("IBLOCK_ID", $link_IBLOCK_ID);      
            
      }
     

      
      
      
       
      $link_FOR_PROPERTY_ID = '[<a href="iblock_edit_property.php?ID='.$arRes['FOR_PROPERTY_ID'].'&lang='.LANG.'&IBLOCK_ID='.$allproperty[$arRes['FOR_PROPERTY_ID']]['IBLOCK_ID'].'" target="_blank">'.$arRes['FOR_PROPERTY_ID'].'</a>] '.$allproperty[$arRes['FOR_PROPERTY_ID']]['NAME'].'' ;  
      $row->AddViewField("FOR_PROPERTY_ID", $link_FOR_PROPERTY_ID);   


    
      
      // �������� ����������� ���� � ������
      $arActions = array();
      if(count($arActions)>0){
        $row->AddActions($arActions);            
      }                           
  }    
  
}

     
//�������� ��� ������� ���� ���
foreach($propertyE1ctools as $prop) {
    $IblockHighload = '';
 
    //���� ��� ��� ����������� �������� ���
    if(empty($tableRow[$prop['PROPERTY_ID']])){
        
        if($prop['PROPERTY_TYPE']=='S:directory') {
            $IblockHighload = $allHighLoadiblockTable[$prop['PROPERTY_HIGHLOAD_TABLE']]['ID'];      
         
            CSnsToolsIblock::Add(array(
                'IBLOCK_ID' =>  $IblockHighload,
                'FOR_PROPERTY_ID' => $prop['PROPERTY_ID'],                    
                'TYPE' => $prop['TYPE'],
                "IBLOCK_TYPE" => 'HIGHLOAD'
            )); 
            
        }                         
    } 
       
}
 

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
        //"delete"=>GetMessage($iModuleID."_ACTION_DEL"), // ������� ��������� ��������      
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
$APPLICATION->SetTitle(GetMessage($iModuleID.'_IBLOCK_TITLE'));  

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
    GetMessage($iModuleID.'_list_title_IBLOCK_ID') 
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
    <td><?=GetMessage($iModuleID.'_list_title_IBLOCK_ID')?>:</td>
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