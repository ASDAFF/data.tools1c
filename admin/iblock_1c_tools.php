<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');   
$iModuleID = "data.tools1c";
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$iModuleID."/include.php");    
IncludeModuleLangFile(__FILE__);

//Проверка прав
//Проверка прав
$POST_RIGHT = $APPLICATION->GetGroupRight($iModuleID);   
if ($POST_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));         
} 
//Конец проверки прав  

 
/*
$arrres = CDataToolsProperty::GetList(array('NAME_1C' => 'DESC'),array(),array());
while($res = $arrres->Fetch()){
    printr($res);   
}*/


$sTableID = "tbl_data1ctools_iblock"; // ID таблицы
$oSort = new CAdminSorting($sTableID, "ID", "asc"); // объект сортировки
$lAdmin = new CAdminList($sTableID, $oSort); // основной объект списка


// ******************************************************************** //
//                           ФИЛЬТР                                     //
// ******************************************************************** //

// *********************** CheckFilter ******************************** //
// проверку значений фильтра для удобства вынесем в отдельную функцию
function CheckFilter()
{
  global $FilterArr, $lAdmin;
  foreach ($FilterArr as $f) global $$f;

  // В данном случае проверять нечего. 
  // В общем случае нужно проверять значения переменных $find_имя
  // и в случае возниконовения ошибки передавать ее обработчику 
  // посредством $lAdmin->AddFilterError('текст_ошибки').
  
  return count($lAdmin->arFilterErrors)==0; // если ошибки есть, вернем false;
}
// *********************** /CheckFilter ******************************* //

// опишем элементы фильтра
$FilterArr = Array(
  //"find_NAME_1C", 
  "find_IBLOCK_ID",      
);

// инициализируем фильтр
$lAdmin->InitFilter($FilterArr);

// если все значения фильтра корректны, обработаем его
if (CheckFilter())
{
  // создадим массив фильтрации для выборки CRubric::GetList() на основе значений фильтра
  $arFilter = Array(
  //  "NAME_1C"  => $find_NAME_1C,    
    "IBLOCK_ID" => $find_IBLOCK_ID,  
  );
}


// ******************************************************************** //
//                ОБРАБОТКА ДЕЙСТВИЙ НАД ЭЛЕМЕНТАМИ СПИСКА              //
// ******************************************************************** //

// сохранение отредактированных элементов  
if($POST_RIGHT=="W")
{
  $lAdmin->EditAction();  
  // пройдем по списку переданных элементов  
  if(count($FIELDS)>0){
      foreach($FIELDS as $ID=>$arFields)
      {
        
        if(!$lAdmin->IsUpdated($ID))
          continue;
        
        // сохраним изменения каждого элемента
        $DB->StartTransaction();
        $ID = IntVal($ID);  
        $cData = new CDataToolsIblock;  
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

 
// обработка одиночных и групповых действий
if($POST_RIGHT=="W")
{
   $arID = $lAdmin->GroupAction(); 
  // если выбрано "Для всех элементов"
  if($_REQUEST['action_target']=='selected')
  {
    $cData = new CDataToolsIblock;
    $rsData = $cData->GetList(array($by=>$order), $arFilter);
    while($arRes = $rsData->Fetch())
      $arID[] = $arRes['ID'];
  }

  
  if(empty($arID)){
    $arID = array();    
  }  
  // пройдем по списку элементов
  if(count($arID)>0){ 
      
      foreach($arID as $ID)
      {
        if(strlen($ID)<=0)
          continue;
           $ID = IntVal($ID);
        
        // для каждого элемента совершим требуемое действие
        switch($_REQUEST['action'])
        {
        // удаление
        case "delete":
          @set_time_limit(0);
          $DB->StartTransaction();
          if(!CDataToolsIblock::Delete($ID))
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
//                ВЫБОРКА ЭЛЕМЕНТОВ СПИСКА                              //
// ******************************************************************** //

// выберем список рассылок
$cData = new CDataToolsIblock;
$rsData = $cData->GetList(array($by=>$order), $arFilter);


// преобразуем список в экземпляр класса CAdminResult
$rsData = new CAdminResult($rsData, $sTableID);

// аналогично CDBResult инициализируем постраничную навигацию.
$rsData->NavStart();

// отправим вывод переключателя страниц в основной объект $lAdmin
$lAdmin->NavText($rsData->GetNavPrint(GetMessage($iModuleID.'_NAV_TITLE')));

// ******************************************************************** //
//                ПОДГОТОВКА СПИСКА К ВЫВОДУ                            //
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

//получим все свойства
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
$proper1ctools = CDataToolsProperty::GetList(array(), array('PROPERTY_TYPE'=>'E'), array(''));
while($arrProper1ctools = $proper1ctools->Fetch()) { 
    $propertyE1ctools[$arrProper1ctools["PROPERTY_ID"]] = $arrProper1ctools;    
} 


// получим справочники
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
            CDataToolsIblock::DeleteByIblockId($arRes['IBLOCK_ID']);                  
        } else {
            $row =& $lAdmin->AddRow($arRes['ID'], $arRes);             
        }
        
  }
  else {
   
      // создаем строку. результат - экземпляр класса CAdminListRow
      if(!$alliblock[$arRes['IBLOCK_ID']] || !$allproperty[$arRes['FOR_PROPERTY_ID']] || !$propertyE1ctools[$arRes['FOR_PROPERTY_ID']]){
          CDataToolsIblock::DeleteByIblockId($arRes['IBLOCK_ID']);                 
          continue;
      } else {
          $row =& $lAdmin->AddRow($arRes['ID'], $arRes);    
      }      
   
      
  }
  

   

  // далее настроим отображение значений при просмотре и редаткировании списка         
  if($POST_RIGHT>="W") {           
      //параметр NAME будет редактироваться как текст, а отображаться ссылкой
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


    
      
      // применим контекстное меню к строке
      $arActions = array();
      if(count($arActions)>0){
        $row->AddActions($arActions);            
      }                           
  }    
  
}

     
//создадим еще таблицв если нет
foreach($propertyE1ctools as $prop) {
    $IblockHighload = '';
 
    //если еще нет справочника создадим его
    if(empty($tableRow[$prop['PROPERTY_ID']])){
        
        if($prop['PROPERTY_TYPE']=='S:directory') {
            $IblockHighload = $allHighLoadiblockTable[$prop['PROPERTY_HIGHLOAD_TABLE']]['ID'];      
         
            CDataToolsIblock::Add(array(
                'IBLOCK_ID' =>  $IblockHighload,
                'FOR_PROPERTY_ID' => $prop['PROPERTY_ID'],                    
                'TYPE' => $prop['TYPE'],
                "IBLOCK_TYPE" => 'HIGHLOAD'
            )); 
            
        }                         
    } 
       
}
 

// резюме таблицы
$lAdmin->AddFooter(
  array(
    array(
        "title"=> GetMessage("MAIN_ADMIN_LIST_SELECTED"), 
        "value"=>$rsData->SelectedRowsCount()), // кол-во элементов
        array(
            "counter"=>true, 
            "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), 
            "value"=>"0"
        ), // счетчик выбранных элементов
  )
);

// групповые действия
if($POST_RIGHT>="W") {

    $GroupActionTable = array(
        //"delete"=>GetMessage($iModuleID."_ACTION_DEL"), // удалить выбранные элементы      
    ); 
       
}


$lAdmin->AddGroupActionTable($GroupActionTable);

// ******************************************************************** //
//                АДМИНИСТРАТИВНОЕ МЕНЮ                                 //
// ******************************************************************** //

$lAdmin->AddAdminContextMenu();   


// ******************************************************************** //
//                ВЫВОД                                                 //
// ******************************************************************** //

// альтернативный вывод
$lAdmin->CheckListMode();

// установим заголовок страницы
$APPLICATION->SetTitle(GetMessage($iModuleID.'_IBLOCK_TITLE'));  

// не забудем разделить подготовку данных и вывод
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// ******************************************************************** //
//                ВЫВОД ФИЛЬТРА                                         //
// ******************************************************************** //
 
// создадим объект фильтра
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
$re = CDataToolsIblock::GetList();
while($arrRes = $re->Fetch()) { 
    printr($arrRes);    
} 
  */      

?>


<?// выведем таблицу списка элементов
$lAdmin->DisplayList();
?>


<?
//завершение страницы
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>