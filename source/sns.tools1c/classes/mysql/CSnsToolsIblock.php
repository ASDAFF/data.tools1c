<?
class CSnsToolsIblock
{  
  
    function Add($arFields)
    {
        global $DB;
        $arInsert = $DB->PrepareInsert("b_snstools1c_iblock", $arFields);
        $strSql =
            "INSERT INTO b_snstools1c_iblock(".$arInsert[0].") ".
            "VALUES(".$arInsert[1].")";
        $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);       
        $ID = IntVal($DB->LastID());
        return $ID;
    } 
    
    function Update($ID ,$arFields)
    {
        global $DB;
        $ID = IntVal($ID);  
        $strUpdate = $DB->PrepareUpdate("b_snstools1c_iblock", $arFields);  
        $strSql = "UPDATE b_snstools1c_iblock SET ".$strUpdate." WHERE ID=".$ID;
        $res = $DB->Query($strSql, true, $err_mess.__LINE__);
        if($res == false) {
            return false;    
        }
        else {
            return $ID;     
        }
        
    } 
    
    function GetIdByForPropertyId($ORDER_ID)
    {
        global $DB;    
        $ORDER_ID = IntVal($ORDER_ID);
        $strSql = "SELECT P.ID FROM b_snstools1c_iblock P WHERE P.FOR_PROPERTY_ID='".$DB->ForSql($ORDER_ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();    
        $ID = $arRes["ID"];
        return $ID;        
    } 
                      
      
    function GetById($ID)
    {
        global $DB;    
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.* FROM b_snstools1c_iblock P WHERE P.ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    }      
    
    function GetByIblockId($ID)
    {
        global $DB;    
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.* FROM b_snstools1c_iblock P WHERE P.IBLOCK_ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    }      
    
    function Delete($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_snstools1c_iblock WHERE ID='".$DB->ForSql($ID)."'";
        $DB->Query($strSql, true);
        return true; 
    }
    
    function DeleteByIblockId($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_snstools1c_iblock WHERE IBLOCK_ID='".$DB->ForSql($ID)."'";
        $DB->Query($strSql, true);
        return true; 
    }
    
    function DeleteByForPropertyId($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_snstools1c_iblock WHERE FOR_PROPERTY_ID='".$DB->ForSql($ID)."'";
        $DB->Query($strSql, true);
        return true; 
    }    
    
        
    function GetList($aSort=Array(), $arFilter=Array(),  $arSelect=Array())
    {
        global $DB;
        $arSqlSearch = Array();
        $arSqlSearch_h = Array();
        $strSqlSearch = ""; 
 

        if (is_array($arFilter))
        {
            foreach($arFilter as $key=>$val)
            {
                if (!is_array($val) && (strlen($val)<=0 || $val=="NOT_REF"))
                    continue;

                switch(strtoupper($key))
                {
                case "ID":
                    $arSqlSearch[] = GetFilterQuery("P.ID",$val);
                    break;
                case "IBLOCK_ID":
                    $arSqlSearch[] = GetFilterQuery("P.IBLOCK_ID",$val);
                    break;  
                case "FOR_PROPERTY_ID":
                    $arSqlSearch[] = GetFilterQuery("P.FOR_PROPERTY_ID",$val);
                    break;                                                              
                case "TYPE":
                    $arSqlSearch[] = GetFilterQuery("P.TYPE",$val);
                    break;                     
                case "IBLOCK_TYPE":
                    $arSqlSearch[] = GetFilterQuery("P.IBLOCK_TYPE",$val);
                    break;                     
                }
            }
        }                        
        

        //сортировка запроса
        $arOrder = array();
        foreach($aSort as $key => $ord)
        {    
            $key = strtoupper($key);
            $ord = (strtoupper($ord) <> "ASC"? "DESC": "ASC");
            switch($key)
            {
                case "ID":        $arOrder[$key] = "P.ID ".$ord; break;
                case "IBLOCK_ID":    $arOrder[$key] = "P.IBLOCK_ID ".$ord; break;
            }
        }
        if(count($arOrder) <= 0)
        {
            $arOrder["ID"] = "P.ID DESC";
        }
        

        // все таблицы которые есть и могут быть в запросе 
        $b_class_sel = array(
            "ID",
            "IBLOCK_ID",
            "FOR_PROPERTY_ID",
            "TYPE",
            "IBLOCK_TYPE"
        );
        
        //подчистим лишнее из запроса  
        foreach($arSelect as $k => $v) {
            if(!in_array($v, $b_class_sel)) {
                unset($arSelect[$k]);        
            }        
        } 
        if(!empty($arSelect)) {

            $dateFields = array(
                'DATE_CHECKIN',
                'DATE_CHECKOUT'
            ); 
            $iOrder = 0;
            foreach($arSelect as $selectVal) {
                if($iOrder != 0){    
                    $strSqlSelect .= ",";              
                }
                // если поле с датой
                if(in_array($selectVal, $dateFields)) {
                    $strSqlSelect .= "
                        ".$DB->DateToCharFunction("P.".$selectVal)." ". $selectVal ." 
                    ";                         
                }
                else {
                    $strSqlSelect .= "
                        P.".$selectVal."     
                    ";                     
                }
                $iOrder++;
            }
        }
        else {
            $strSqlSelect = "P.*";
        } 
               
       
        $strSqlOrder = " ORDER BY ".implode(", ", $arOrder);
        $strSqlSearch = GetFilterSqlSearch($arSqlSearch);
        $strSql = "
            SELECT 
                 ".$strSqlSelect."            
            FROM b_snstools1c_iblock P
            WHERE
            ".$strSqlSearch."
        ";
        if(count($arSqlSearch_h)>0)
        {
            $strSqlSearch_h = GetFilterSqlSearch($arSqlSearch_h);
            $strSql = $strSql." HAVING ".$strSqlSearch_h;
        }
        $strSql.=$strSqlOrder;   
        $res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
        $res->is_filtered = (IsFiltered($strSqlSearch));
        return $res;
    }
        
}
?>