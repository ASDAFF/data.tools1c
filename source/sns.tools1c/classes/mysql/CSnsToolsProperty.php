<?
class CSnsToolsProperty
{  
    function Add($arFields)
    {
        global $DB;
        $arInsert = $DB->PrepareInsert("b_snstools1c_property", $arFields);
        $strSql =
            "INSERT INTO b_snstools1c_property(".$arInsert[0].") ".
            "VALUES(".$arInsert[1].")";
        $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);       
        $ID = IntVal($DB->LastID());
        return $ID;
    } 
    
    function Update($ID ,$arFields)
    {
        global $DB;
        $ID = IntVal($ID);  
        $strUpdate = $DB->PrepareUpdate("b_snstools1c_property", $arFields);  
        $strSql = "UPDATE b_snstools1c_property SET ".$strUpdate." WHERE ID=".$ID;
        $res = $DB->Query($strSql, true, $err_mess.__LINE__);
        if($res == false) {
            return false;    
        }
        else {
            return $ID;     
        }
        
    } 
    
    function UpdateByPropertyId($ID ,$arFields)
    {
        global $DB;
        $ID = IntVal($ID);  
        $strUpdate = $DB->PrepareUpdate("b_snstools1c_property", $arFields);  
        $strSql = "UPDATE b_snstools1c_property SET ".$strUpdate." WHERE PROPERTY_ID=".$ID;
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
        $strSql = "SELECT P.ID FROM b_snstools1c_property P WHERE P.FOR_PROPERTY_ID='".$DB->ForSql($ORDER_ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();    
        $ID = $arRes["ID"];
        return $ID;        
    } 
                      
      
    function GetByID($ID)
    {
        global $DB;    
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.* FROM b_snstools1c_property P WHERE P.ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    }      
    
    function GetByPropertyId($ID)
    {
        global $DB;    
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.* FROM b_snstools1c_property P WHERE P.PROPERTY_ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    }      
    
    function Delete($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_snstools1c_property WHERE ID='".$DB->ForSql($ID)."'";
        $DB->Query($strSql, true);
        return true; 
    }
    
    function DeleteByPropertyId($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_snstools1c_property WHERE PROPERTY_ID='".$DB->ForSql($ID)."'";
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
                case "PROPERTY_ID":
                    $arSqlSearch[] = GetFilterQuery("P.PROPERTY_ID",$val);
                    break;  
                case "PROPERTY_IBLOCK":
                    $arSqlSearch[] = GetFilterQuery("P.PROPERTY_IBLOCK",$val);
                    break;   
                case "PROPERTY_TYPE":
                    $arSqlSearch[] = GetFilterQuery("P.PROPERTY_TYPE",$val);
                    break;                                                                               
                case "FOR_PROPERTY_ID":
                    $arSqlSearch[] = GetFilterQuery("P.FOR_PROPERTY_ID",$val);
                    break;                      
                case "TYPE":
                    $arSqlSearch[] = GetFilterQuery("P.TYPE",$val);
                    break; 
                case "NAME_1C":
                    $arSqlSearch[] = GetFilterQuery("P.NAME_1C",$val);
                    break;                     
                }
            }
        }
        

        //���������� �������  
        $arOrder = array();
        foreach($aSort as $key => $ord)
        {    
            $key = strtoupper($key);
            $ord = (strtoupper($ord) <> "ASC"? "DESC": "ASC");
            switch($key)
            {
                case "ID":        $arOrder[$key] = "P.ID ".$ord; break;
                case "PROPERTY_ID":    $arOrder[$key] = "P.PROPERTY_ID ".$ord; break;
                case "PROPERTY_IBLOCK":    $arOrder[$key] = "P.PROPERTY_IBLOCK ".$ord; break;  
                case "PROPERTY_TYPE":    $arOrder[$key] = "P.PROPERTY_TYPE ".$ord; break;
                case "FOR_PROPERTY_ID":    $arOrder[$key] = "P.FOR_PROPERTY_ID ".$ord; break;   
                case "TYPE":    $arOrder[$key] = "P.TYPE ".$ord; break;                                                                                                       
                case "NAME_1C":    $arOrder[$key] = "P.NAME_1C ".$ord; break;    
                
            }        
        }
        if(count($arOrder) <= 0)
        {
            $arOrder["ID"] = "P.ID DESC";
        }
        

        // ��� ������� ������� ���� � ����� ���� � ������� 
        $b_class_sel = array(
            "ID",
            "PROPERTY_ID",
            "PROPERTY_IBLOCK",
            "PROPERTY_TYPE",
            "FOR_PROPERTY_ID",
            "TYPE",
            "NAME_1C",
        );
        
          //��������� ������ �� �������  
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
                // ���� ���� � �����
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
            FROM b_snstools1c_property P
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