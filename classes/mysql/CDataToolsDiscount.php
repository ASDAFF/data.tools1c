<?
class CDataToolsDiscount
{  
  
    function Add($arFields)
    {
        global $DB;
        $arInsert = $DB->PrepareInsert("b_datatools1c_discont", $arFields);
        $strSql =
            "INSERT INTO b_datatools1c_discont(".$arInsert[0].") ".
            "VALUES(".$arInsert[1].")";
        $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
        $ID = IntVal($DB->LastID());

        return $ID;
    } 
    
    function Update($ID ,$arFields)
    {
        global $DB;
        $ID = IntVal($ID);  
        $strUpdate = $DB->PrepareUpdate("b_datatools1c_discont", $arFields);  
        $strSql = "UPDATE b_datatools1c_discont SET ".$strUpdate." WHERE ID=".$ID;
        $res = $DB->Query($strSql, true, $err_mess.__LINE__);
        if($res == false) {
            return false;    
        }
        else {
            return $ID;     
        }
        
    } 
    
    function GetIdByDiscontId($ORDER_ID)
    {
        global $DB;    
        $ORDER_ID = IntVal($ORDER_ID);
        $strSql = "SELECT P.ID FROM b_datatools1c_discont P WHERE P.DISCONT_ID='".$DB->ForSql($ORDER_ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();    
        $ID = $arRes["ID"];
        return $ID;        
    } 
                      
      
    function GetById($ID)
    {
        global $DB;    
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.* FROM b_datatools1c_discont P WHERE P.ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    }      
    
    function GetByDiscontId($ID)
    {
        global $DB;    
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.* FROM b_datatools1c_discont P WHERE P.DISCONT_ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    }      
    
    function Delete($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_datatools1c_discont WHERE ID='".$DB->ForSql($ID)."'";
        $DB->Query($strSql, true);
        return true; 
    }
    
    function DeleteByDiscontId($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_datatools1c_discont WHERE DISCONT_ID='".$DB->ForSql($ID)."'";
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
                case "DISCONT_ID":
                    $arSqlSearch[] = GetFilterQuery("P.DISCONT_ID",$val);
                    break;  
                case "DISCONT_VALUE":
                    $arSqlSearch[] = GetFilterQuery("P.DISCONT_VALUE",$val);
                    break;   
                case "TYPE":
                    $arSqlSearch[] = GetFilterQuery("P.TYPE",$val);
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
                case "DISCONT_ID":    $arOrder[$key] = "P.DISCONT_ID ".$ord; break;
            }
        }
        if(count($arOrder) <= 0)
        {
            $arOrder["ID"] = "P.ID DESC";
        }
        

        // все таблицы которые есть и могут быть в запросе 
        $b_newitcdek_order_sel = array(
            "ID",
            "DISCONT_ID",
            "DISCONT_VALUE",
            "TYPE"
        );
        
        //подчистим лишнее из запроса  
        foreach($arSelect as $k => $v) {
            if(!in_array($v, $b_newitcdek_order_sel)) {
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
            FROM b_datatools1c_discont P
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