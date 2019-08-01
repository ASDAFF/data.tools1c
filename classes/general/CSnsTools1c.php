<?
        
class CSnsToolsEvent
{     

    function OnAfterIBlockElementAdd(&$arFields) {
    
        //заполним артикул в предложениях   
        // начало
        $CHEXBOX_ARTICLE = COption::GetOptionString('sns.tools1c' , "CHEXBOX_ARTICLE");     
        if(CModule::IncludeModule("iblock") && $CHEXBOX_ARTICLE == 'Y') {
            if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) {    
                $OFFERS_ARTICLE = COption::GetOptionString('sns.tools1c' , "OFFERS_ARTICLE");
                $OFFERS_LINK = COption::GetOptionString('sns.tools1c' , "OFFERS_LINK");
                $CATALOG_ARTICLE = COption::GetOptionString('sns.tools1c' , "CATALOG_ARTICLE");  
                
                $arSelectOffers = Array("PROPERTY_".$OFFERS_ARTICLE ,  "PROPERTY_".$OFFERS_LINK.".PROPERTY_".$CATALOG_ARTICLE);
                $arFilterOffers = Array("ID"=>$arFields['ID']);
                $resOffers = CIBlockElement::GetList(Array(), $arFilterOffers, false, false, $arSelectOffers);
                $arrOffers = $resOffers->Fetch();
                $cml_linkId = $arrOffers['PROPERTY_'.$OFFERS_LINK.'_VALUE'];
                $articleOffers = $arrOffers['PROPERTY_'.$OFFERS_ARTICLE.'_VALUE'];
                $articleElement = $arrOffers['PROPERTY_'.$OFFERS_LINK.'_PROPERTY_'.$CATALOG_ARTICLE.'_VALUE'];                               
                if($articleElement != $articleOffers) {
                     CIBlockElement::SetPropertyValueCode($arFields['ID'], $OFFERS_ARTICLE, $articleElement);            
                }                                
            } 
        }
        //конец 
        
        

        
        
        // свяжем справочники с торговыми предложениями
       // начало
       $CHEXBOX_OFFERS_PROPERTIES = COption::GetOptionString('sns.tools1c' , "CHEXBOX_OFFERS_PROPERTIES");      
       if(CModule::IncludeModule("iblock") && $CHEXBOX_OFFERS_PROPERTIES == 'Y') { 

           if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) { 

               $OFFERS_ATTRIBUTES = COption::GetOptionString('sns.tools1c' , "OFFERS_ATTRIBUTES");       
               $db_props = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], array("sort" => "asc"), Array("CODE" => $OFFERS_ATTRIBUTES));
               while ($ob = $db_props->GetNext())
               {
                      
                    // получим id инфоблока

                    $ob['DESCRIPTION'] = trim($ob['DESCRIPTION']); 
                    $Iblockres = CIBlock::GetList(Array(), Array('NAME' => $ob['DESCRIPTION']), true);
                    while($ar_Iblockres = $Iblockres->Fetch())
                    {
                        $Iblock = $ar_Iblockres['ID'];
                    } 
                      
                    if($Iblock) {
                        $arFilter = Array(
                            "IBLOCK_ID" => $Iblock, 
                            "ACTIVE_DATE"=>"Y", 
                            "ACTIVE"=>"Y", 
                            "NAME" => $ob['VALUE']
                        );
                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, Array("ID"));
                        $resArr = $res->Fetch();
                        if($resArr['ID']){
                            //получим свойство куда записать
                            $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("NAME"=>$ob['DESCRIPTION'], "IBLOCK_ID"=>$arFields['IBLOCK_ID']));
                            while ($prop_fields = $properties->GetNext())
                            {
                                if($prop_fields['PROPERTY_TYPE'] == 'E'){
                                    $propSave_E = $prop_fields["CODE"];       
                                }          
                            }
                            
                            if($propSave_E) {
                                CIBlockElement::SetPropertyValues($arFields['ID'], $arFields['IBLOCK_ID'], $resArr['ID'], $propSave_E);    
                            } 
             
                        }                   
                    }    
               }               
           }
                   
       }          
       //конец      
        
       // выгрузим свойства характеристик в отдельные свойста типа стройка 
       // начало         
       $CHEXBOX_OFFERS_PROPERTIES_STRING = COption::GetOptionString('sns.tools1c' , "CHEXBOX_OFFERS_PROPERTIES_STRING");      
       if(CModule::IncludeModule("iblock") && $CHEXBOX_OFFERS_PROPERTIES_STRING == 'Y') { 

           if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) { 
               $OFFERS_ATTRIBUTES = COption::GetOptionString('sns.tools1c' , "OFFERS_ATTRIBUTES");       
               $db_props = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], array("sort" => "asc"), Array("CODE" => $OFFERS_ATTRIBUTES));
               while ($ob = $db_props->GetNext())
               { 
                    //получим свойство куда записать 
                    $ob['DESCRIPTION'] = trim($ob['DESCRIPTION']);
                    $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("NAME"=>$ob['DESCRIPTION'], "IBLOCK_ID"=>$arFields['IBLOCK_ID']));
                    while ($prop_fields = $properties->GetNext())
                    {
                        if($prop_fields['PROPERTY_TYPE'] == 'S') {
                            $propSave_S = $prop_fields["CODE"];           
                        }                                
                    }       
                    if($propSave_S) {
                        CIBlockElement::SetPropertyValues($arFields['ID'], $arFields['IBLOCK_ID'], $ob['VALUE'], $propSave_S);        
                    }                      
               }               
           }            
       }          
       //конец          
           
        // реализуем множественные свойства
        // начало
       $CHEXBOX_MULTIPROPER = COption::GetOptionString('sns.tools1c' , "CHEXBOX_MULTIPROPER");  
       if(CModule::IncludeModule("iblock") && $CHEXBOX_MULTIPROPER == 'Y') { 
           if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) { 
               
                $STRING_MULTIPROPER_ID = COption::GetOptionString('sns.tools1c' , "STRING_MULTIPROPER_ID"); 
                if(!empty($STRING_MULTIPROPER_ID)) {
                    $STRING_MULTIPROPER_ID = explode(',' , $STRING_MULTIPROPER_ID);
                    if(!is_array($STRING_MULTIPROPER_ID)) {
                        $STRING_MULTIPROPER_ID = array($STRING_MULTIPROPER_ID);     
                    }       
                }

                               
                $STRING_MULTIPROPER_RAZDEL = COption::GetOptionString('sns.tools1c' , "STRING_MULTIPROPER_RAZDEL"); 
                 
      
                $spacer =  array($STRING_MULTIPROPER_RAZDEL);
                $properties = array();
                $db_props = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], array("sort" => "asc"), Array());
                while ($ob = $db_props->GetNext())
                {
                    // получим свойство в котором обнаружили разделитель
                    if(!empty($STRING_MULTIPROPER_ID)) {
                        if(in_array($ob['CODE'] , $STRING_MULTIPROPER_ID)) {
                            $properties[$ob['ID']][] = $ob;                        
                        }                     
                    }
                    else {
                        $properties[$ob['ID']][] = $ob;  
                    }
                }  
                
                foreach ($properties as $keyprops => $prop) {
                     // удалим пустые значения
                     foreach($prop as $key_v => $values) {
                        if(empty($values['VALUE'])){
                           unset($prop[$key_v]);     
                        }
                     }
                     if ((!empty($prop)) && (count($prop) <= 2)) {                          
                         foreach ($prop as $key_pro => $v) { 
                             // пройдемся по массиву с разделителями
                             foreach($spacer as $spacer_value) {
                                 if (strpos($v['VALUE'], $spacer_value) !== false) { 
                                      // разделим строку на массив
                                      //$v['VALUE'] = str_replace(" ","",$v['VALUE']);
                                      $v['VALUE'] = trim($v['VALUE']);
                                      $PROPERTY_VALUE = array();
                                      $arr_element_prop = array(); 
                                      $arr_element_prop = explode($spacer_value, $v['VALUE']);
                                      foreach($arr_element_prop as $key_elem => $element_prop) { 
                                            $element_prop = trim($element_prop);   
                                            $PROPERTY_VALUE['n'.$key_elem] = array(
                                                'VALUE'=>$element_prop
                                            );                                         
                                      }
                                      CIBlockElement::SetPropertyValuesEx($arFields['ID'], $arFields['IBLOCK_ID'], array($keyprops => $PROPERTY_VALUE));    
                                 }                       
                             }    
                         } 
                     }                     
                }            
               
           }
       }        
       //конец            
           
                 
    }
    
    function OnAfterIBlockElementUpdate(&$arFields){
        
        //заполним артикул в предложениях   
        // начало
        $CHEXBOX_ARTICLE = COption::GetOptionString('sns.tools1c' , "CHEXBOX_ARTICLE");     
        if(CModule::IncludeModule("iblock") && $CHEXBOX_ARTICLE == 'Y') {
            if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) {    
                $OFFERS_ARTICLE = COption::GetOptionString('sns.tools1c' , "OFFERS_ARTICLE");
                $OFFERS_LINK = COption::GetOptionString('sns.tools1c' , "OFFERS_LINK");
                $CATALOG_ARTICLE = COption::GetOptionString('sns.tools1c' , "CATALOG_ARTICLE");  
                
                $arSelectOffers = Array("PROPERTY_".$OFFERS_ARTICLE ,  "PROPERTY_".$OFFERS_LINK.".PROPERTY_".$CATALOG_ARTICLE);
                $arFilterOffers = Array("ID"=>$arFields['ID']);
                $resOffers = CIBlockElement::GetList(Array(), $arFilterOffers, false, false, $arSelectOffers);
                $arrOffers = $resOffers->Fetch();
                $cml_linkId = $arrOffers['PROPERTY_'.$OFFERS_LINK.'_VALUE'];
                $articleOffers = $arrOffers['PROPERTY_'.$OFFERS_ARTICLE.'_VALUE'];
                $articleElement = $arrOffers['PROPERTY_'.$OFFERS_LINK.'_PROPERTY_'.$CATALOG_ARTICLE.'_VALUE'];                               
                if($articleElement != $articleOffers) {
                     CIBlockElement::SetPropertyValueCode($arFields['ID'], $OFFERS_ARTICLE, $articleElement);            
                }                                
            } 
        }
        //конец
        
       // свяжем справочники с торговыми предложениями
       // начало
       $CHEXBOX_OFFERS_PROPERTIES = COption::GetOptionString('sns.tools1c' , "CHEXBOX_OFFERS_PROPERTIES");      
       if(CModule::IncludeModule("iblock") && $CHEXBOX_OFFERS_PROPERTIES == 'Y') { 

           if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) { 

               $OFFERS_ATTRIBUTES = COption::GetOptionString('sns.tools1c' , "OFFERS_ATTRIBUTES");       
               $db_props = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], array("sort" => "asc"), Array("CODE" => $OFFERS_ATTRIBUTES));
               while ($ob = $db_props->GetNext())
               {
                      
                    // получим id инфоблока

                    $ob['DESCRIPTION'] = trim($ob['DESCRIPTION']); 
                    $Iblockres = CIBlock::GetList(Array(), Array('NAME' => $ob['DESCRIPTION']), true);
                    while($ar_Iblockres = $Iblockres->Fetch())
                    {
                        $Iblock = $ar_Iblockres['ID'];
                    } 
                      
                    if($Iblock) {
                        $arFilter = Array(
                            "IBLOCK_ID" => $Iblock, 
                            "ACTIVE_DATE"=>"Y", 
                            "ACTIVE"=>"Y", 
                            "NAME" => $ob['VALUE']
                        );
                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, Array("ID"));
                        $resArr = $res->Fetch();
                        if($resArr['ID']){
                            //получим свойство куда записать
                            $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("NAME"=>$ob['DESCRIPTION'], "IBLOCK_ID"=>$arFields['IBLOCK_ID']));
                            while ($prop_fields = $properties->GetNext())
                            {
                                if($prop_fields['PROPERTY_TYPE'] == 'E'){
                                    $propSave_E = $prop_fields["CODE"];       
                                }          
                            }
                            
                            if($propSave_E) {
                                CIBlockElement::SetPropertyValues($arFields['ID'], $arFields['IBLOCK_ID'], $resArr['ID'], $propSave_E);    
                            }              
                        }                   
                    }    
               }               
           }
                   
       }          
       //конец      
       
       // выгрузим свойства характеристик в отдельные свойста типа стройка 
       // начало    
       $CHEXBOX_OFFERS_PROPERTIES_STRING = COption::GetOptionString('sns.tools1c' , "CHEXBOX_OFFERS_PROPERTIES_STRING");      
       if(CModule::IncludeModule("iblock") && $CHEXBOX_OFFERS_PROPERTIES_STRING == 'Y') { 

           if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) { 
               $OFFERS_ATTRIBUTES = COption::GetOptionString('sns.tools1c' , "OFFERS_ATTRIBUTES");       
               $db_props = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], array("sort" => "asc"), Array("CODE" => $OFFERS_ATTRIBUTES));
               while ($ob = $db_props->GetNext())
               { 
                    //получим свойство куда записать 
                    $ob['DESCRIPTION'] = trim($ob['DESCRIPTION']);
                    $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("NAME"=>$ob['DESCRIPTION'], "IBLOCK_ID"=>$arFields['IBLOCK_ID']));
                    while ($prop_fields = $properties->GetNext())
                    {
                        if($prop_fields['PROPERTY_TYPE'] == 'S') {
                            $propSave_S = $prop_fields["CODE"];           
                        }                                
                    }       
                    if($propSave_S) {
                        CIBlockElement::SetPropertyValues($arFields['ID'], $arFields['IBLOCK_ID'], $ob['VALUE'], $propSave_S);        
                    }                      
               }               
           }            
       }          
       //конец            
 
        // реализуем множественные свойства
        // начало
       $CHEXBOX_MULTIPROPER = COption::GetOptionString('sns.tools1c' , "CHEXBOX_MULTIPROPER");  
       if(CModule::IncludeModule("iblock") && $CHEXBOX_MULTIPROPER == 'Y') { 
           if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) { 
               
                $STRING_MULTIPROPER_ID = COption::GetOptionString('sns.tools1c' , "STRING_MULTIPROPER_ID"); 
                if(!empty($STRING_MULTIPROPER_ID)) {
                    $STRING_MULTIPROPER_ID = explode(',' , $STRING_MULTIPROPER_ID);
                    if(!is_array($STRING_MULTIPROPER_ID)) {
                        $STRING_MULTIPROPER_ID = array($STRING_MULTIPROPER_ID);     
                    }       
                }

                               
                $STRING_MULTIPROPER_RAZDEL = COption::GetOptionString('sns.tools1c' , "STRING_MULTIPROPER_RAZDEL"); 
                 
      
                $spacer =  array($STRING_MULTIPROPER_RAZDEL);
                $properties = array();
                $db_props = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], array("sort" => "asc"), Array());
                while ($ob = $db_props->GetNext())
                {
                    // получим свойство в котором обнаружили разделитель
                    if(!empty($STRING_MULTIPROPER_ID)) {
                        if(in_array($ob['CODE'] , $STRING_MULTIPROPER_ID)) {
                            $properties[$ob['ID']][] = $ob;                        
                        }                     
                    }
                    else {
                        $properties[$ob['ID']][] = $ob;  
                    }
                }  
                
                foreach ($properties as $keyprops => $prop) {
                     // удалим пустые значения
                     foreach($prop as $key_v => $values) {
                        if(empty($values['VALUE'])){
                           unset($prop[$key_v]);     
                        }
                     }
                     if ((!empty($prop)) && (count($prop) <= 2)) {                          
                         foreach ($prop as $key_pro => $v) { 
                             // пройдемся по массиву с разделителями
                             foreach($spacer as $spacer_value) {
                                 if (strpos($v['VALUE'], $spacer_value) !== false) { 
                                      // разделим строку на массив
                                      //$v['VALUE'] = str_replace(" ","",$v['VALUE']);
                                      $v['VALUE'] = trim($v['VALUE']);
                                      $PROPERTY_VALUE = array();
                                      $arr_element_prop = array(); 
                                      $arr_element_prop = explode($spacer_value, $v['VALUE']);
                                      foreach($arr_element_prop as $key_elem => $element_prop) { 
                                            $element_prop = trim($element_prop);   
                                            $PROPERTY_VALUE['n'.$key_elem] = array(
                                                'VALUE'=>$element_prop
                                            );                                         
                                      }
                                      CIBlockElement::SetPropertyValuesEx($arFields['ID'], $arFields['IBLOCK_ID'], array($keyprops => $PROPERTY_VALUE));    
                                 }                       
                             }    
                         } 
                     }                     
                }            
               
           }
       }        
       //конец 
        
    }   
     
     
    function OnBeforeIBlockElementUpdate(&$arFields) { 

        // не обновлять элементы при выгрузке из 1С
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) {
            
            $SELECT_NONE_UPDATE = unserialize(COption::GetOptionString('sns.tools1c' , "SELECT_NONE_UPDATE"));
            if(in_array('PREVIEW_TEXT',$SELECT_NONE_UPDATE)) {
                $SELECT_NONE_UPDATE[] = 'PREVIEW_TEXT_TYPE';    
            }  
            if(in_array('DETAIL_TEXT',$SELECT_NONE_UPDATE)) {
                $SELECT_NONE_UPDATE[] = 'DETAIL_TEXT_TYPE';              
            }  

            if($SELECT_NONE_UPDATE){
                foreach($SELECT_NONE_UPDATE as $selVal) {
                    $selVal = trim($selVal);   
                    unset($arFields[$selVal]);    
                }            
            }

            /*   
            $STRING_NONE_UPDATE_PROPER = COption::GetOptionString('sns.tools1c' , "STRING_NONE_UPDATE_PROPER");
            $STRING_NONE_UPDATE_PROPER = explode(',' , $STRING_NONE_UPDATE_PROPER); 
            if($STRING_NONE_UPDATE_PROPER){    
                foreach($STRING_NONE_UPDATE_PROPER as $selValProp) {
                    $selValProp = trim($selValProp);
                    unset($arFields['PROPERTY_VALUES'][$selValProp]);    
                }  
            }  
            */   
                      
        }

         
    }  
       
      
    function OnBeforeProductAdd(&$arFields){
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import') {
            $CHEXBOX_QUALITY = COption::GetOptionString('sns.tools1c' , "CHEXBOX_QUALITY");
            $INT_QUALITY_DEFAULT = COption::GetOptionString('sns.tools1c' , "INT_QUALITY_DEFAULT");
            $CHEXBOX_VAT_INCLUDED = COption::GetOptionString('sns.tools1c' , "CHEXBOX_VAT_INCLUDED"); 
            $SELECT_VAT = COption::GetOptionString('sns.tools1c' , "SELECT_VAT");

            if($CHEXBOX_QUALITY == 'Y') {
                $arFields["QUANTITY_TRACE"] = $CHEXBOX_QUALITY;
            }
            if($INT_QUALITY_DEFAULT) {
                $arFields["QUANTITY"] = $INT_QUALITY_DEFAULT;
            }            
            if($CHEXBOX_VAT_INCLUDED == 'Y') {
                $arFields['VAT_INCLUDED'] = $CHEXBOX_VAT_INCLUDED;              
            }            
            if($SELECT_VAT) {
                $arFields["VAT_ID"] = $SELECT_VAT;     
            }
            
        } 
               
    } 
    
    function OnBeforeProductUpdate($ID, &$arFields){
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import') {
            $CHEXBOX_QUALITY = COption::GetOptionString('sns.tools1c' , "CHEXBOX_QUALITY");
            $INT_QUALITY_DEFAULT = COption::GetOptionString('sns.tools1c' , "INT_QUALITY_DEFAULT");
            $CHEXBOX_VAT_INCLUDED = COption::GetOptionString('sns.tools1c' , "CHEXBOX_VAT_INCLUDED"); 
            $SELECT_VAT = COption::GetOptionString('sns.tools1c' , "SELECT_VAT");

            if($CHEXBOX_QUALITY == 'Y') {
                $arFields["QUANTITY_TRACE"] = $CHEXBOX_QUALITY;
            }
            if($INT_QUALITY_DEFAULT) {
                $arFields["QUANTITY"] = $INT_QUALITY_DEFAULT;
            }            
            if($CHEXBOX_VAT_INCLUDED == 'Y') {
                $arFields['VAT_INCLUDED'] = $CHEXBOX_VAT_INCLUDED;              
            }            
            if($SELECT_VAT) {
                $arFields["VAT_ID"] = $SELECT_VAT;     
            }
            
        }  

    }    

    function OnPriceAdd($ID, $arFields){

       
    }  
    function OnPriceUpdate($ID, $arFields){

       
    }
    
}
?>