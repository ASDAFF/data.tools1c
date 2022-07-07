<?
use Bitrix\Main\SystemException;
use Data\Tools1c\EventsTable;
use Data\Tools1c\ChecksTable;
use Data\Tools1c\ProfileTable;
use Data\Tools1c\LogHandler;
use Data\Tools1c\OrderTable;
use Bitrix\Main\Context,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem;

IncludeModuleLangFile(__FILE__);
class CDataToolsEvent
{

    const MODULE_ID = 'data.tools1c';
    const CACHE_TIME_TOOLS = 1800;

    public function getDemo()
    {
        $module_id = "data.tools1c";
        $sotbit_DEMO = CModule::IncludeModuleEx($module_id);
        //$sotbit_DEMO = 3;
        if($sotbit_DEMO==3)
        {
            return false;
        }
        else return true;
    }

    function CacheConstantCheck() {
        if(COption::GetOptionString(self::MODULE_ID, "MANAGED_CACHE_ON", "Y") == "Y")
        {
            define("BX_COMP_MANAGED_CACHE", true);
            return 'Y';
        }
        elseif(COption::GetOptionString(self::MODULE_ID, "MANAGED_CACHE_ON") == "N")
        {
            define("BX_COMP_MANAGED_CACHE", false);
            return 'N';
        }
    }

    //получим инфоблок по ID
    //START
    function GetIblockSiteById_NoCache($IBLOCK_ID=false) {
        $vars = array();
        if(!CModule::IncludeModule("iblock")) {
            return $vars;
        }
        $res = CIBlock::GetSite($IBLOCK_ID);
        while($ar_res = $res->GetNext()) {
            $vars[] = $ar_res['LID'];
        }
        return $vars;
    }

    function GetIblockSiteById($IBLOCK_ID=false) {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetIblockSiteById_NoCache($IBLOCK_ID);
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetIblockSiteById';
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|4|GetIblockSiteById|'.$IBLOCK_ID;
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetIblockSiteById_NoCache($IBLOCK_ID);


            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetIblockSiteById');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;
    }
    //END


    //получим все highload инфоблоки
    //START
    function GetHighLoadIblock_NoCache() {
        $vars = array();
        if(!CModule::IncludeModule("highloadblock")) {
            return $vars;
        }
        $res = Bitrix\Highloadblock\HighloadBlockTable::getList(array());
        while($ar_res = $res->fetch())
        {
            $vars[$ar_res["ID"]] = $ar_res;
        }
        return $vars;
    }

    function GetHighLoadIblock() {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetHighLoadIblock_NoCache();
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetHighLoadIblock';
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|4|GetHighLoadIblock|';
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetHighLoadIblock_NoCache();

            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetHighLoadIblock');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;
    }
    //END

    //получим свойства для конкретного инфоблока с кэшированием
    //START
    function GetPropertyNameIblock_NoCache($IBLOCK_ID=false, $FOR_PROPERTY_ID=false, $PROPERTY_TYPE=false) {
        $vars = array();
        $properties = CDataToolsProperty::GetList(array(), array('PROPERTY_IBLOCK'=>$IBLOCK_ID, 'FOR_PROPERTY_ID'=>$FOR_PROPERTY_ID, 'PROPERTY_TYPE'=>$PROPERTY_TYPE));
        while($prop_fields = $properties->Fetch()) {
            if($prop_fields['PROPERTY_TYPE'] == $PROPERTY_TYPE) {
                $vars[] = $prop_fields;
            }
        }
        return $vars;
    }

    function GetPropertyNameIblock($IBLOCK_ID=false, $FOR_PROPERTY_ID=false, $PROPERTY_TYPE=false) {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetPropertyNameIblock_NoCache($IBLOCK_ID, $FOR_PROPERTY_ID, $PROPERTY_TYPE);
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetPropertyNameIblock';
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetPropertyNameIblock|'.$IBLOCK_ID.'|'.$FOR_PROPERTY_ID.'|'.$PROPERTY_TYPE;
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            // поиск по свойствам 1С инструменты
            $vars = self::GetPropertyNameIblock_NoCache($IBLOCK_ID, $FOR_PROPERTY_ID, $PROPERTY_TYPE);


            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetPropertyNameIblock');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;
    }
    //END



    //получим id инфоблока
    //START
    function GetIblockDirectoryByProperty_NoCache($FOR_PROPERTY_ID=false, $TYPE=false) {
        $vars = array();
        $fillterIblock = array(
            'FOR_PROPERTY_ID'=>$FOR_PROPERTY_ID
        );

        if($TYPE == "HIGHLOAD") {
            $fillterIblock["IBLOCK_TYPE"] = "HIGHLOAD";
        }

        $Iblockres = CDataToolsIblock::GetList(array(), $fillterIblock, array(''));
        while($ar_Iblockres = $Iblockres->Fetch()) {
            $vars = $ar_Iblockres['IBLOCK_ID'];
        }
        return $vars;
    }

    function GetIblockDirectoryByProperty($FOR_PROPERTY_ID=false, $TYPE=false) {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetIblockDirectoryByProperty_NoCache($FOR_PROPERTY_ID, $TYPE);
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetIblockDirectoryByProperty';
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetIblockDirectoryByProperty|'.$FOR_PROPERTY_ID.'|'.$TYPE;
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetIblockDirectoryByProperty_NoCache($FOR_PROPERTY_ID, $TYPE);


            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetIblockDirectoryByProperty');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;
    }
    //END


    //получим элемент
    //START
    function GetPropertyElementIblock_NoCache($IBLOCK_ID=false, $ELEMENT_ID=false) {
        $vars = array();
        if(!CModule::IncludeModule("iblock")) {
            return $vars;
        }

        $db_props = CIBlockElement::GetProperty($IBLOCK_ID, $ELEMENT_ID, array("sort" => "asc"), Array());
        while ($arr_Props = $db_props->GetNext())
        {
            $vars[] = $arr_Props;
        }
        return $vars;
    }

    function GetPropertyElementIblock($IBLOCK_ID=false, $ELEMENT_ID=false) {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetPropertyElementIblock_NoCache($IBLOCK_ID, $ELEMENT_ID);
            return $vars;
        }


        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetPropertyElementIblock';
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetPropertyElementIblock|'.$ELEMENT_ID.'|'.$IBLOCK_ID;
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetPropertyElementIblock_NoCache($IBLOCK_ID, $ELEMENT_ID);

            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetPropertyElementIblock');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;

    }
    //END

    //получим список id элементов по внешнему коду
    //START
    function GetXmlIdElementByOffers_NoCache($IBLOCK_OFFERS_ID=false) {
        $vars = array();
        if(!CModule::IncludeModule("iblock")) {
            return $vars;
        }

        $IB_INFO = CCatalogSKU::GetInfoByOfferIBlock($IBLOCK_OFFERS_ID);
        $arSelect = Array("ID",  "XML_ID");
        $arFilter = Array("IBLOCK_ID"=>$IB_INFO['PRODUCT_IBLOCK_ID']);
        $resElem = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while($arrElem = $resElem->Fetch())
        {
            $vars[$arrElem['XML_ID']] = $arrElem['ID'];
        }

        return $vars;
    }

    function GetXmlIdElementByOffers($IBLOCK_OFFERS_ID=false) {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetXmlIdElementByOffers_NoCache($IBLOCK_OFFERS_ID);
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetXmlIdElementByOffers';
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetXmlIdElementByOffers|'.$IBLOCK_OFFERS_ID;

        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {

            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetXmlIdElementByOffers_NoCache($IBLOCK_OFFERS_ID);


            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetXmlIdElementByOffers');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.

        }
        return $vars;

    }
    //END


    //получим артикулы элементов
    //START
    function GetArticleElementByOffers_NoCache($IBLOCK_OFFERS_ID=false) {
        $vars = array();
        if(!CModule::IncludeModule("iblock")) {
            return $vars;
        }

        $PROP_ATICLES = COption::GetOptionString('data.tools1c' , "CATALOG_ARTICLE");
        $IB_INFO = CCatalogSKU::GetInfoByOfferIBlock($IBLOCK_OFFERS_ID);
        $arSelect = Array("ID",  "PROPERTY_".$PROP_ATICLES);
        $arFilter = Array("IBLOCK_ID"=>$IB_INFO['PRODUCT_IBLOCK_ID']);
        $resElem = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while($arrElem = $resElem->Fetch())
        {
            $vars[$arrElem['ID']] = $arrElem['PROPERTY_'.$PROP_ATICLES.'_VALUE'];
        }

        return $vars;
    }

    function GetArticleElementByOffers($IBLOCK_OFFERS_ID=false) {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetArticleElementByOffers_NoCache($IBLOCK_OFFERS_ID);
            return $vars;
        }

        $PROP_ATICLES = COption::GetOptionString('data.tools1c' , "CATALOG_ARTICLE");
        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetArticleElementByOffers';
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetArticleElementByOffers|'.$IBLOCK_OFFERS_ID.'|'.$PROP_ATICLES;

        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {

            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetArticleElementByOffers_NoCache($IBLOCK_OFFERS_ID);


            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetArticleElementByOffers');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.

        }
        return $vars;

    }
    //END

    //получим все описания файлов
    //START
    function GetDescriptionFileIblock_NoCache() {
        $vars = array();
        if(!CModule::IncludeModule("iblock")) {
            return $vars;
        }

        $res = CFile::GetList(array(), array("MODULE_ID"=>"iblock"));
        while($res_arr = $res->GetNext()) {
            if($res_arr['DESCRIPTION']) {
                $vars[$res_arr['ID']] = $res_arr['DESCRIPTION'];
            }
        }

        return $vars;
    }

    function GetDescriptionFileIblock() {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetDescriptionFileIblock_NoCache();
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetDescriptionFileIblock';
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|4|GetDescriptionFileIblock|';
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetDescriptionFileIblock_NoCache();


            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetDescriptionFileIblock');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;
    }
    //END

    //получим картинки элементов
    //START
    function GetMorePhotoElementByOffers_NoCache($IBLOCK_OFFERS_ID=false) {
        $vars = array();
        if(!CModule::IncludeModule("iblock")) {
            return $vars;
        }

        $PROP_MORE_PHOTO = COption::GetOptionString('data.tools1c' , "CATALOG_MORE_PHOTO");

        $Descript_File_Iblock = CDataToolsEvent::GetDescriptionFileIblock();

        $IB_INFO = CCatalogSKU::GetInfoByOfferIBlock($IBLOCK_OFFERS_ID);
        $arSelect = Array("ID",  "PROPERTY_".$PROP_MORE_PHOTO , "DETAIL_PICTURE", "PREVIEW_PICTURE");
        $arFilter = Array("IBLOCK_ID"=>$IB_INFO['PRODUCT_IBLOCK_ID']);
        $resElem = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while($arrElem = $resElem->Fetch())
        {
            if($Descript_File_Iblock[$arrElem['PREVIEW_PICTURE']]){
                $vars[$arrElem['ID']][$arrElem['PREVIEW_PICTURE']] = $Descript_File_Iblock[$arrElem['PREVIEW_PICTURE']];
            }

            if($Descript_File_Iblock[$arrElem['DETAIL_PICTURE']]){
                $vars[$arrElem['ID']][$arrElem['DETAIL_PICTURE']] = $Descript_File_Iblock[$arrElem['DETAIL_PICTURE']];
            }

            if($Descript_File_Iblock[$arrElem['PROPERTY_'.$PROP_MORE_PHOTO.'_VALUE']]){
                $vars[$arrElem['ID']][$arrElem['PROPERTY_'.$PROP_MORE_PHOTO.'_VALUE']] = $Descript_File_Iblock[$arrElem['PROPERTY_'.$PROP_MORE_PHOTO.'_VALUE']];
            }
        }

        return $vars;
    }


    function GetMorePhotoElementByOffers($IBLOCK_OFFERS_ID=false) {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetMorePhotoElementByOffers_NoCache($IBLOCK_OFFERS_ID);
            return $vars;
        }

        $PROP_MORE_PHOTO = COption::GetOptionString('data.tools1c' , "CATALOG_MORE_PHOTO");

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetMorePhotoElementByOffers';
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetMorePhotoElementByOffers|'.$IBLOCK_OFFERS_ID.'|'.$PROP_MORE_PHOTO;

        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {

            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);

            $vars = array();
            $vars = self::GetMorePhotoElementByOffers_NoCache($IBLOCK_OFFERS_ID);

            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetMorePhotoElementByOffers');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.

        }
        return $vars;

    }
    //END

    //получим элементы справочника
    //START
    function GetDirectoryElement_NoCache($IBLOCK_ID=false) {
        $vars = array();
        if(!CModule::IncludeModule("iblock")) {
            return $vars;
        }

        $arFilter = Array(
            "IBLOCK_ID" => $IBLOCK_ID,
            "ACTIVE_DATE"=>"Y",
            "ACTIVE"=>"Y",
        );
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, Array("ID","NAME"));
        while ($resArr = $res->GetNext())
        {
            $vars[$resArr['NAME']] = $resArr['ID'];
        }

        return $vars;
    }

    function GetDirElement($IBLOCK_ID=false, $id) {
        $vars = array();
        if(!CModule::IncludeModule("iblock")) {
            return $vars;
        }

        $arFilter = Array(
            "IBLOCK_ID" => $IBLOCK_ID,
            "ACTIVE_DATE"=>"Y",
            "ACTIVE"=>"Y",
        );
        if(!is_null($id)){
            $arFilter['XML_ID'] = $id;
        }
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, Array("ID","NAME", "XML_ID"));
        while ($resArr = $res->GetNext())
        {
            $vars[$resArr['NAME']][] = $resArr['ID'];
        }

        return $vars;
    }

    function GetDirectoryElement($IBLOCK_ID=false) {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetDirectoryElement_NoCache($IBLOCK_ID);
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetDirectoryElement_'.$IBLOCK_ID;
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetDirectoryElement|'.$IBLOCK_ID;
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetDirectoryElement_NoCache($IBLOCK_ID);


            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetDirectoryElement_'.$IBLOCK_ID);
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;

    }
    //END


    //получим все ID элементов в инфоблоках
    //START
    function GetAllIdElementIblock_NoCache() {
        $vars = array();
        if(!CModule::IncludeModule("iblock")) {
            return $vars;
        }

        $res = CIBlockElement::GetList(Array(), array(), false, false, Array("ID"));
        while ($resArr = $res->GetNext())
        {
            $vars[$resArr['ID']] = $resArr['ID'];
        }

        return $vars;
    }

    function GetAllIdElementIblock() {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetAllIdElementIblock_NoCache();
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetAllIdElementIblock';
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetAllIdElementIblock|';
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetAllIdElementIblock_NoCache();


            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetAllIdElementIblock');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;

    }
    //END



    //получим элементы справочника HighLoad
    //START
    function GetDirectoryHighLoadElement_NoCache($IBLOCK_HIGHLOAD_ID=false) {
        $vars = array();
        if(!CModule::IncludeModule("highloadblock")) {
            return $vars;
        }

        //подучим инфоблок, данные по нему
        $rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('ID'=>$IBLOCK_HIGHLOAD_ID)));
        $arData = $rsData->fetch();
        $INFO_entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arData);
        $main_query = new \Bitrix\Main\Entity\Query($INFO_entity);
        //Зададим параметры запроса, любой параметр можно опустить
        $main_query->setSelect(array('ID','UF_NAME', "UF_XML_ID","UF_LINK","UF_DESCRIPTION","UF_FULL_DESCRIPTION","UF_SORT","UF_FILE","UF_DEF"));
        //Выполним запрос
        $result = $main_query->exec();
        //Получаем результат по привычной схеме
        $result = new CDBResult($result);
        $arLang = array();
        while ($row = $result->Fetch()){
            //$vars[$row['UF_NAME']] = $row['ID'];
            $vars[$row['UF_XML_ID']] = $row;
        }

        return $vars;
    }

    function GetDirectoryHighLoadElement($IBLOCK_HIGHLOAD_ID=false) {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetDirectoryHighLoadElement_NoCache($IBLOCK_HIGHLOAD_ID);
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetDirectoryHighLoadElement_'.$IBLOCK_HIGHLOAD_ID;
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetDirectoryHighLoadElement|'.$IBLOCK_HIGHLOAD_ID;
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetDirectoryHighLoadElement_NoCache($IBLOCK_HIGHLOAD_ID);


            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetDirectoryHighLoadElement_'.$IBLOCK_HIGHLOAD_ID);
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;

    }
    //END


    //получим свойства для всех инфоблоков с кэшированием
    //START
    function GetAllPropertyIblock_NoCache() {
        $vars = array();
        if(!CModule::IncludeModule("iblock")) {
            return $vars;
        }

        $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array());
        while ($prop_fields = $properties->GetNext())
        {
            $vars[$prop_fields['ID']] = $prop_fields;
        }

        return $vars;
    }

    function GetAllPropertyIblock() {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetAllPropertyIblock_NoCache();
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetAllPropertyIblock';
        // $cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetAllPropertyIblock|';
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetAllPropertyIblock_NoCache();

            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetAllPropertyIblock');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();


            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;
    }
    //END


    //получим значения для списка
    //START
    function GetPropertyValueList_NoCache($FOR_PROPERTY_ID=false) {
        $vars = array();
        if(!CModule::IncludeModule("iblock")) {
            return $vars;
        }

        $property_enums = CIBlockPropertyEnum::GetList(Array(), Array("PROPERTY_ID"=>$FOR_PROPERTY_ID));
        while($enum_fields = $property_enums->GetNext())
        {
            $vars[$enum_fields['VALUE']] = $enum_fields['ID'];
        }

        return $vars;
    }

    function GetPropertyValueList($FOR_PROPERTY_ID=false) {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetPropertyValueList_NoCache($FOR_PROPERTY_ID);
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetPropertyValueList';
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetPropertyValueList|'.$FOR_PROPERTY_ID;
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetPropertyValueList_NoCache($FOR_PROPERTY_ID);

            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetPropertyValueList');
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;
    }
    //END

    //получим все скидки связанные с 1с инструментами
    //START
    function GetAllDiscontTools_NoCache($lid='') {
        $vars = array();
        if(!CModule::IncludeModule("catalog")) {
            return $vars;
        }
        $DISCOUNT_CONDITION_BASKET = COption::GetOptionString('data.tools1c' , "DISCOUNT_CONDITION_BASKET");
        if($DISCOUNT_CONDITION_BASKET == 'Y'){
            $Discontkres = CDataToolsDiscount::GetList(array(), array(), array());
            while($ar_Discontres = $Discontkres->Fetch()) {
                $is = CSaleDiscount::GetByID($ar_Discontres['DISCONT_ID']);
                if($is['SITE_ID'] != $lid) continue;
                $vars['DISCOUNT_ID_SEARCH'][$ar_Discontres['TYPE'].'_'.$ar_Discontres['DISCONT_VALUE']][] = $ar_Discontres['DISCONT_ID'];
                $vars['DISCOUNT_ARRAY'][$ar_Discontres['ID']] = $ar_Discontres;
            }
            return $vars;
        }else{
            $Discontkres = CDataToolsDiscount::GetList(array(), array(), array());
            while($ar_Discontres = $Discontkres->Fetch()) {
                $is = CCatalogDiscount::GetByID($ar_Discontres['DISCONT_ID']);
                if($is['SITE_ID'] != $lid) continue;
                $vars['DISCOUNT_ID_SEARCH'][$ar_Discontres['TYPE'].'_'.$ar_Discontres['DISCONT_VALUE']][] = $ar_Discontres['DISCONT_ID'];
                $vars['DISCOUNT_ARRAY'][$ar_Discontres['ID']] = $ar_Discontres;
            }
            return $vars;
        }
    }

    function GetAllDiscontTools($lid ='') {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetAllDiscontTools_NoCache($lid);
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetAllDiscontTools'.$lid;
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetAllDiscontTools|'.$lid;
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetAllDiscontTools_NoCache($lid);

            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetAllDiscontTools'.$lid);
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;
    }
    //END

    //получим товары у которых есть скидки
    //START
    function GetDiscontProduct_NoCache($id) {
        $vars = array();
        if(!CModule::IncludeModule("catalog")) {
            return $vars;
        }

        //получим скидки связанные с 1с инструментами
        $IdDiscont1cTools = array();
        $resIdDiscont1cTools = CDataToolsDiscount::GetList(array(), array(), array());
        while($ar_IdDiscont1cTools = $resIdDiscont1cTools->Fetch()) {
            $IdDiscont1cTools[] = $ar_IdDiscont1cTools['DISCONT_ID'];
        }


        $type = COption::GetOptionString('data.tools1c' , "DISCOUNT_CONDITION_BASKET");
        //получим информация по скидкам
        if(count($IdDiscont1cTools)>0){
            if($type == 'Y'){
                $dbProductDiscounts = CSaleDiscount::GetList(
                    array(),
                    array("ID" => $IdDiscont1cTools, 'SITE_ID'=>$id),
                    false,
                    false,
                    array("ID","PRODUCT_ID","CONDITIONS", "ACTIONS")
                );
            }else{
                $dbProductDiscounts = CCatalogDiscount::GetList(
                    array(),
                    array("ID" => $IdDiscont1cTools, 'SITE_ID'=>$id),
                    false,
                    false,
                    array("ID","PRODUCT_ID","CONDITIONS")
                );
            }




            while($arProductDiscounts = $dbProductDiscounts->Fetch())
            {
                //если нет привязанных товаров не будет включать в выборку,  так же удалим скидку если назначено в админке
                //START
                $CONDITIONS = array();
                if($type == 'Y'){
                    $CONDITIONS = unserialize($arProductDiscounts["ACTIONS"]);
                }else{
                    $CONDITIONS = unserialize($arProductDiscounts["CONDITIONS"]);
                }


                if(count($CONDITIONS["CHILDREN"]) == 0){
                    unset($arProductDiscounts["PRODUCT_ID"]);
                }
                //END
                if(!is_array($vars["DISCONT_INFO"][$arProductDiscounts["ID"]])){
                    $vars["DISCONT_INFO"][$arProductDiscounts["ID"]] = array();
                }
                if($type == 'Y') {
                    foreach ($CONDITIONS['CHILDREN'] as $item) {
                        foreach ($item["CHILDREN"] as $it) {
                            foreach ($it['DATA']['value'] as $id) {
                                $vars["PRODUCT_INFO"][$id][] = $arProductDiscounts["ID"];
                                $vars["DISCONT_INFO"][$arProductDiscounts["ID"]][] = $id;
                            }
                        }
                    }
                }

                if($arProductDiscounts["PRODUCT_ID"] && $arProductDiscounts["ID"]){
                    $vars["PRODUCT_INFO"][$arProductDiscounts["PRODUCT_ID"]][] = $arProductDiscounts["ID"];
                    $vars["DISCONT_INFO"][$arProductDiscounts["ID"]][] = $arProductDiscounts["PRODUCT_ID"];
                }
            }
        }



        return $vars;
    }

    function GetDiscontProduct($id = '') {

        $CacheConstantCheck = self::CacheConstantCheck();
        if($CacheConstantCheck=='N'){
            $vars = array();
            $vars = self::GetDiscontProduct_NoCache($id);
            return $vars;
        }

        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetDiscontProduct'.$id;
        //$cache_dir = '/'.self::MODULE_ID;
        $cache_id = self::MODULE_ID.'|1|GetDiscontProduct|'.$id;
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
            $vars = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $vars = array();

            $vars = self::GetDiscontProduct_NoCache($id);


            $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetDiscontProduct'.$id);
            $CACHE_MANAGER->RegisterTag(self::MODULE_ID);
            $CACHE_MANAGER->EndTagCache();

            $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.
        }
        return $vars;
    }
    //END


    //возвращает уникальное свойство
    //START
    function GetUniqueProp($code=false) {

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import') {

            $props = CDataToolsEvent::GetAllPropertyIblock();

            $arrPropsCode = array();
            foreach($props as $prop) {
                if(stristr($prop['CODE'], 'CML2_') === FALSE) {
                    $arrPropsCode[] = $prop['CODE'];
                }
            }

            if(in_array($code, $arrPropsCode)){
                $i = 2;
                while ($i <= 1000) {
                    if(!in_array($code.$i, $arrPropsCode)){
                        $code = $code.$i;
                        return $code;
                        break;
                    }
                    $i++;
                }
            }
            return $code;
        }
        return $code;

    }
    //END

    // добавми проверку символьного кода
    // START
    function GetCleanCode($code=false) {

        if(is_numeric($code[0])) {
            $code = 'N'.$code;
        }

        return $code;
    }
    // END

    //для выгрузки в отдельные строки множественных свойств
    //START
    function OffersAttributesSeparate(&$arFields=array() , $type=false , $optionProper=false) {

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], $type)) {

            $PROPS_GET = COption::GetOptionString('data.tools1c' , $optionProper);
            //зададим по умолчанию
            if(empty($PROPS_GET) && $optionProper == 'OFFERS_ATTRIBUTES'){
                $PROPS_GET = 'CML2_ATTRIBUTES';
            }
            if(empty($PROPS_GET) && $optionProper == 'GOODS_TRAITS_STRING'){
                $PROPS_GET = 'CML2_TRAITS';
            }


            $elemProper = CDataToolsEvent::GetPropertyElementIblock($arFields['IBLOCK_ID'], $arFields['ID']);
            if(empty($elemProper)){
                $elemProper = array();
            }
            if(count($elemProper)>0){
                foreach($elemProper as $ob) {
                    if($ob['CODE'] == $PROPS_GET) {
                        //получим свойство куда записать
                        $ob['DESCRIPTION'] = trim($ob['DESCRIPTION']);

                        $properties = CDataToolsEvent::GetPropertyNameIblock($arFields['IBLOCK_ID'], $ob["ID"], 'S');
                        // получим нужное значение свойства
                        foreach($properties as $proper) {
                            if($proper['NAME_1C'] == $ob['DESCRIPTION']) {
                                $propSave_S = $proper["PROPERTY_ID"];
                            }
                        }


                        //если нет свойства создадим его
                        if(empty($propSave_S)) {
                            //получим символьный код
                            $propSave_S = Cutil::translit($ob['DESCRIPTION'],'ru', array('change_case' => 'U'));
                            $propSave_S = CDataToolsEvent::GetCleanCode($propSave_S);
                            $propSave_S = $propSave_S.'_ATTR_S';
                            //создадим свойство
                            $arProprsAdd = Array(
                                "NAME" => $ob['DESCRIPTION'],
                                "ACTIVE" => "Y",
                                "SORT" => "100",
                                "CODE" => $propSave_S,
                                "XML_ID" => $propSave_S,
                                "PROPERTY_TYPE" => "S",
                                "IBLOCK_ID" => $arFields['IBLOCK_ID']
                            );
                            $ibp = new CIBlockProperty;
                            if($newPropId = $ibp->Add($arProprsAdd)) {

                                CDataToolsProperty::Add(array(
                                    'PROPERTY_ID' => $newPropId,
                                    'PROPERTY_IBLOCK' => $arFields['IBLOCK_ID'],
                                    'PROPERTY_TYPE' => 'S',
                                    'FOR_PROPERTY_ID' => $ob["ID"],
                                    'TYPE' => $optionProper,
                                    'NAME_1C' => $ob['DESCRIPTION']
                                ));
                                $propSave_S = $newPropId;
                                global $CACHE_MANAGER;
                                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetPropertyNameIblock');
                                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetAllPropertyIblock');
                            }

                        }
                        if($propSave_S) {

                            CIBlockElement::SetPropertyValues($arFields['ID'], $arFields['IBLOCK_ID'], $ob['VALUE'], $propSave_S);
                            unset($propSave_S);
                            global $CACHE_MANAGER;
                            $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetPropertyElementIblock');
                        }


                    }
                }
            }



        }
        return $arFields;
    }
    //END


    //Создадим свойства типа список для характеристик торговых предложений
    //START
    function OffersAttributesProperList(&$arFields=array() , $type=false , $optionProper=false) {

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], $type)) {

            $OFFERS_ATTRIBUTES = COption::GetOptionString('data.tools1c' , "OFFERS_ATTRIBUTES" , "CML2_ATTRIBUTES");

            $elemProper = CDataToolsEvent::GetPropertyElementIblock($arFields['IBLOCK_ID'], $arFields['ID']);
            if(empty($elemProper)){
                $elemProper = array();
            }
            if(count($elemProper)>0){
                foreach($elemProper as $ob) {
                    if($ob['VALUE'] && $ob['CODE'] == $OFFERS_ATTRIBUTES) {


                        if(in_array($ob['PROPERTY_TYPE'], array('S','N'))) {


                            CDataToolsEvent::AddPropList(
                                array(
                                    'ID' => $arFields['ID'],
                                    'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                                    'NAME_SAVE' => $ob['DESCRIPTION'],
                                    'VALUE_SAVE' => $ob['VALUE'],
                                    'TYPE' => 'OFFERS_ATTRIBUTES',
                                    'FOR_PROPERTY_ID' => $ob['ID'],
                                    'FOR_PROPERTY_CODE' => $ob['CODE']
                                ),
                                $arFields

                            );

                        }
                    }
                }
            }


        }
        return $arFields;
    }
    //END

    //получим значения для разделения множественных свойств
    //START
    function GetMultipleProperElement($SETTINGS=array()) {

        //определим тип выгрузки
        if(strstr($_GET['filename'], 'offers')) {
            $TYPE = 'offers';
        }
        elseif(strstr($_GET['filename'], 'import')) {
            $TYPE = 'import';
        }

        // если нет галочек анулируем функцию
        // START
        $resProper = array();
        if($SETTINGS['STOP_CANCEL'] != 'Y'){

            $CHEXBOX_MULTIPROPER = COption::GetOptionString('data.tools1c' , "CHEXBOX_MULTIPROPER");
            if($TYPE == 'import' && $CHEXBOX_MULTIPROPER != 'Y') {
                return $resProper;
            }
            $CHEXBOX_MULTIPROPER_OFFERS = COption::GetOptionString('data.tools1c' , "CHEXBOX_MULTIPROPER_OFFERS");
            if($TYPE == 'offers' && $CHEXBOX_MULTIPROPER_OFFERS != 'Y') {
                return $resProper;

            }
        }
        //END

        //получим свойства которые надо разделять
        $STRING_MULTIPROPER_ID = COption::GetOptionString('data.tools1c' , "STRING_MULTIPROPER_ID");
        if(!empty($STRING_MULTIPROPER_ID)) {
            $STRING_MULTIPROPER_ID = explode(',' , $STRING_MULTIPROPER_ID);
            if(!is_array($STRING_MULTIPROPER_ID)) {
                $STRING_MULTIPROPER_ID = array($STRING_MULTIPROPER_ID);
            }
        }
        if(is_array($STRING_MULTIPROPER_ID)) {
            foreach($STRING_MULTIPROPER_ID as $k=>$v) {
                $STRING_MULTIPROPER_ID[$k] = trim($v);
            }
        }


        //получим свойства в которых надо игнорировать разделение
        //START
        $STRING_MULTIPROPER_ID_NO = COption::GetOptionString('data.tools1c' , "STRING_MULTIPROPER_ID_NO");
        if(!empty($STRING_MULTIPROPER_ID_NO)) {
            $STRING_MULTIPROPER_ID_NO = explode(',' , $STRING_MULTIPROPER_ID_NO);
            if(!is_array($STRING_MULTIPROPER_ID_NO)) {
                $STRING_MULTIPROPER_ID_NO = array($STRING_MULTIPROPER_ID_NO);
            }
        }
        if(is_array($STRING_MULTIPROPER_ID_NO)) {
            foreach($STRING_MULTIPROPER_ID_NO as $k=>$v) {
                $STRING_MULTIPROPER_ID_NO[$k] = trim($v);
            }
        }
        if(empty($STRING_MULTIPROPER_ID_NO)){
            $STRING_MULTIPROPER_ID_NO = array();
        }
        //END



        $STRING_MULTIPROPER_RAZDEL = COption::GetOptionString('data.tools1c' , "STRING_MULTIPROPER_RAZDEL");
        $OFFERS_ATTRIBUTES = COption::GetOptionString('data.tools1c' , "OFFERS_ATTRIBUTES");



        $STRING_MULTIPROPER_RAZDEL = explode(' ', $STRING_MULTIPROPER_RAZDEL);
        $spacer = array();
        if(is_array($STRING_MULTIPROPER_RAZDEL)){
            foreach($STRING_MULTIPROPER_RAZDEL as $razdel){
                if($razdel){
                    $spacer[] = $razdel;
                }
            }
        }





        $properties = array();

        $elemProper = CDataToolsEvent::GetPropertyElementIblock($SETTINGS['IBLOCK_ID'], $SETTINGS['ID']);
        if(empty($elemProper)){
            $elemProper = array();
        }
        if(count($elemProper)>0){
            foreach($elemProper as $ob) {
                if($SETTINGS['TYPE'] == 'offers' && $ob['CODE'] == $OFFERS_ATTRIBUTES){
                    unset($ob);
                }
                if(in_array($ob['CODE'],$STRING_MULTIPROPER_ID_NO)){
                    unset($ob);
                }

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
        }

        if(count($properties)>0){
            foreach($properties as $keyprops => $prop) {
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
                                $v['VALUE'] = trim($v['VALUE']);
                                $PROPERTY_VALUE = array();
                                $arr_element_prop = array();
                                $arr_element_prop = explode($spacer_value, $v['VALUE']);
                                foreach($arr_element_prop as $key_elem => $element_prop) {
                                    $element_prop = trim($element_prop);
                                    $PROPERTY_VALUE['n'.$key_elem] = array(
                                        'VALUE'=>$element_prop
                                    );
                                    $resProper['val'][$keyprops][] = $element_prop;
                                    if($v['DESCRIPTION']) {
                                        $resProper['val_descr'][$keyprops][$v['DESCRIPTION']][] = $element_prop;
                                    }

                                }

                                //если свойство не множественное сделаем его таковым
                                if($prop[0]['MULTIPLE'] == 'N'){
                                    $ibp = new CIBlockProperty;
                                    $ibp->Update($keyprops, array('MULTIPLE' => 'Y'));
                                    global $CACHE_MANAGER;
                                    $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetAllPropertyIblock');
                                }

                                $resProper['ValuesEx'][$keyprops] = $PROPERTY_VALUE;
                                //CIBlockElement::SetPropertyValuesEx($arFields['ID'], $arFields['IBLOCK_ID'], array($keyprops => $PROPERTY_VALUE));

                            }
                        }
                    }
                }
            }
        }


        return $resProper;


    }
    //END

    //создать свойство справочника
    //START
    function AddPropDirectory($SETTING=array(), $arFields=array()) {

        $SETTING['NAME_SAVE'] = trim($SETTING['NAME_SAVE']);
        $SETTING['VALUE_SAVE'] = trim($SETTING['VALUE_SAVE']);


        if(empty($SETTING['VALUE_SAVE'])){
            return true;
        }
        //получим все свойства
        $allPropsArr = CDataToolsEvent::GetAllPropertyIblock();


        $resProperMultiple = CDataToolsEvent::GetMultipleProperElement(array(
            'ID' => $SETTING['ID'],
            'IBLOCK_ID' => $SETTING['IBLOCK_ID']
        ));


        if(is_array($resProperMultiple['val_descr'][$SETTING['FOR_PROPERTY_ID']][$SETTING['NAME_SAVE']])){
            $SETTING['VALUE_SAVE'] = $resProperMultiple['val_descr'][$SETTING['FOR_PROPERTY_ID']][$SETTING['NAME_SAVE']];
        }
        elseif(is_array($resProperMultiple['val'][$SETTING['FOR_PROPERTY_ID']]) && empty($resProperMultiple['val_descr'][$SETTING['FOR_PROPERTY_ID']])){
            $SETTING['VALUE_SAVE'] = $resProperMultiple['val'][$SETTING['FOR_PROPERTY_ID']];
        }


        //получим свойство куда записать
        //START

        $properties = CDataToolsEvent::GetPropertyNameIblock($SETTING['IBLOCK_ID'], $SETTING['FOR_PROPERTY_ID'], 'E');

        // получим нужное значение свойства
        if(count($properties) == 1 && !in_array($SETTING["FOR_PROPERTY_CODE"],array('CML2_ATTRIBUTES','CML2_TRAITS')) ){
            $propSave_E = $properties[0]["PROPERTY_ID"];
            $propSave_E_MULTIPLE =  $allPropsArr[$properties[0]["PROPERTY_ID"]]["MULTIPLE"];
            //если названия отличаются выравняем с теми что в 1С
            if($properties[0]['NAME_1C'] != $SETTING['NAME_SAVE']){
                CDataToolsProperty::Update($properties[0]['ID'],array('NAME_1C'=>$SETTING['NAME_SAVE']));
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetPropertyNameIblock');
            }

        } else {
            //если свойств много пройдемся по ним
            if(empty($properties)){
                $properties = array();
            }
            if(count($properties)>0){
                foreach($properties as $proper) {
                    if($proper['NAME_1C'] == $SETTING['NAME_SAVE']){
                        $propSave_E = $proper["PROPERTY_ID"];
                        $propSave_E_MULTIPLE =  $allPropsArr[$proper["PROPERTY_ID"]]["MULTIPLE"];
                    }
                }
            }


        }


        //если свойства нет создадим его
        if(empty($propSave_E)){
            //получим символьный код
            $propSave_E = Cutil::translit($SETTING['NAME_SAVE'],'ru', array('change_case' => 'U'));
            $propSave_E = CDataToolsEvent::GetCleanCode($propSave_E);
            $propSave_E = $propSave_E.'_ATTR_E';
            //создадим свойство
            $arProprsAdd = Array(
                "NAME" => $SETTING['NAME_SAVE'],
                "ACTIVE" => "Y",
                "SORT" => "100",
                "CODE" => $propSave_E,
                "XML_ID" => $propSave_E,
                "PROPERTY_TYPE" => "E",
                "IBLOCK_ID" => $SETTING['IBLOCK_ID'],
                ""
            );
            $ibp = new CIBlockProperty;
            if($newPropId = $ibp->Add($arProprsAdd)) {

                CDataToolsProperty::Add(array(
                    'PROPERTY_ID' => $newPropId,
                    'PROPERTY_IBLOCK' => $SETTING['IBLOCK_ID'],
                    'PROPERTY_TYPE' => 'E',
                    'FOR_PROPERTY_ID' => $SETTING['FOR_PROPERTY_ID'],
                    'TYPE' => $SETTING['TYPE'],
                    'NAME_1C' => $SETTING['NAME_SAVE']
                ));
                $propSave_E = $newPropId;
                $propSave_E_MULTIPLE = 'N';
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetPropertyNameIblock');
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetAllPropertyIblock');
            }

        }
        //END

        //если свойство для которого создаем справочник множественное сделаем его таким
        //START
        if(is_array($SETTING['VALUE_SAVE'])){

            if($propSave_E_MULTIPLE == 'N' && $propSave_E){
                $ibp = new CIBlockProperty;
                $ibp->Update($propSave_E, array('MULTIPLE' => 'Y'));
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetAllPropertyIblock');
            }


        }
        //END


        // получим инфоблок для записи
        // START
        $Iblock = CDataToolsEvent::GetIblockDirectoryByProperty($propSave_E);
        if(empty($Iblock)) {

            $siteId = CDataToolsEvent::GetIblockSiteById($SETTING['IBLOCK_ID']);

            $addIblock = new CIBlock;
            $addIblockArFields = Array(
                "ACTIVE" => 'Y',
                "NAME" => $SETTING['NAME_SAVE'],
                "IBLOCK_TYPE_ID" =>  $SETTING['TYPE_IBLOCK_SAVE'],
                "SITE_ID" => $siteId
            );

            if($Iblock = $addIblock->Add($addIblockArFields)){

                CDataToolsIblock::Add(array(
                    'IBLOCK_ID' => $Iblock,
                    'TYPE' => $SETTING['TYPE'],
                    'FOR_PROPERTY_ID' => $propSave_E,
                ));

                $new_proper_add = new CIBlockProperty;
                $new_proper_add->Update($propSave_E, array("LINK_IBLOCK_ID" => $Iblock));

            }

            global $CACHE_MANAGER;
            $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetIblockDirectoryByProperty');

        }
        //END

        $CHEXBOX_GOODS_PROPERTIES_CODE = COption::GetOptionString('data.tools1c', "CHEXBOX_GOODS_PROPERTIES_CODE");
        if($Iblock && $propSave_E) {
            if($CHEXBOX_GOODS_PROPERTIES_CODE == 'Y'){
                $arrElementDirectory = CDataToolsEvent::GetDirElement($Iblock, $SETTING['XML_ID']);
            }else{
                $arrElementDirectory = CDataToolsEvent::GetDirectoryElement($Iblock);
            }

            if(!is_array($SETTING['VALUE_SAVE'])){
                $SETTING['VALUE_SAVE'] = array($SETTING['VALUE_SAVE']);
            }
            if(empty($SETTING['VALUE_SAVE'])){
                $SETTING['VALUE_SAVE'] = array();
            }
            if(count($SETTING['VALUE_SAVE'])>0){
                foreach($SETTING['VALUE_SAVE'] as $value_save) {
                    if(empty($arrElementDirectory[$value_save])){
                        $value_save = htmlspecialcharsBack($value_save);
                        $el = new CIBlockElement;
                        $arLoadProductArray = Array(
                            "IBLOCK_ID"      => $Iblock,
                            "NAME"           => $value_save,
                            "ACTIVE"         => "Y",
                            "CODE"  => Cutil::translit($value_save,'ru', array('change_case' => false)),
                            'XML_ID'=> is_null($SETTING['XML_ID']) ? $arFields['ID'] : $SETTING['XML_ID']
                        );
                        $resArr['ID'][] = $el->Add($arLoadProductArray);
                        global $CACHE_MANAGER;
                        $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetDirectoryElement_'.$Iblock);
                    } else {
                        $resArr['ID'][] =  $arrElementDirectory[$value_save];
                    }

                }
            }


            if($propSave_E) {
                CIBlockElement::SetPropertyValues($SETTING['ID'], $SETTING['IBLOCK_ID'], $resArr['ID'], $propSave_E);
                unset($propSave_E);
                unset($propSave_E_MULTIPLE);
                unset($resArr['ID']);
            }

        }



    }
    //END

    //вернет массив со значениями свойств, которые не нужно создавать в зависимости от инфоблока
    function GetArrayPropsValueNoAdd($iblockId=false)
    {
        $arrProps = array();
        if($iblockId)
        {
            $mxResult = CCatalogSKU::GetInfoByOfferIBlock($iblockId);

            if (is_array($mxResult))  //если инфоблок является инфоблоком торогых
            {
                $str = COption::GetOptionString('data.tools1c' , "OFFERS_PROPERTIES_HIGHLOAD_NOT_VALUE_PROPS");
            }
            else   //если инфоблок не является инфоблоком торогых
            {
                $str = COption::GetOptionString('data.tools1c' , "GOODS_PROPERTIES_HIGHLOAD_MUST_VALUE_NOT");
            }
            $str = trim($str);
            if(strlen($str)>0)
            {
                $arr = explode(",", $str);
                if(count($arr) > 0)
                {
                    foreach($arr as $id=>&$val)
                    {
                        $val = trim($val);
                        if(strlen($val) == 0) unset($arr[$id]);
                    }

                    if(count($arr) > 0) $arrProps = $arr;
                }
            }
        }
        return  $arrProps;

    }
    //создать свойство и справочник HighLoad инфоблока
    //START
    function AddPropDirectoryHighLoad($SETTING=array(), $arFields=array()) {

        if(!CModule::IncludeModule('highloadblock')){
            return true;
        }



        $SETTING['NAME_SAVE'] = trim($SETTING['NAME_SAVE']);
        $SETTING['VALUE_SAVE'] = trim($SETTING['VALUE_SAVE']);

        if(empty($SETTING['VALUE_SAVE'])){
            return true;
        }

        //получим все свойства
        $allPropsArr = CDataToolsEvent::GetAllPropertyIblock();

        //получим множественность свойства
        $resProperMultiple = CDataToolsEvent::GetMultipleProperElement(array(
            'ID' => $SETTING['ID'],
            'IBLOCK_ID' => $SETTING['IBLOCK_ID']
        ));


        if(is_array($resProperMultiple['val_descr'][$SETTING['FOR_PROPERTY_ID']][$SETTING['NAME_SAVE']])){
            $SETTING['VALUE_SAVE'] = $resProperMultiple['val_descr'][$SETTING['FOR_PROPERTY_ID']][$SETTING['NAME_SAVE']];
        }
        elseif(is_array($resProperMultiple['val'][$SETTING['FOR_PROPERTY_ID']]) && empty($resProperMultiple['val_descr'][$SETTING['FOR_PROPERTY_ID']])){
            $SETTING['VALUE_SAVE'] = $resProperMultiple['val'][$SETTING['FOR_PROPERTY_ID']];
        }


        //получим свойство куда записать
        //START
        $properties = CDataToolsEvent::GetPropertyNameIblock($SETTING['IBLOCK_ID'], $SETTING['FOR_PROPERTY_ID'], 'S:directory');


        // получим нужное значение свойства
        if(count($properties) == 1 && !in_array($SETTING["FOR_PROPERTY_CODE"],array('CML2_ATTRIBUTES','CML2_TRAITS')) ){
            $propSave_S_DIRECTORY = $properties[0]["PROPERTY_ID"];
            $propSave_S_DIRECTORY_MULTIPLE =  $allPropsArr[$properties[0]["PROPERTY_ID"]]["MULTIPLE"];
            //если названия отличаются выравняем с теми что в 1С
            if($properties[0]['NAME_1C'] != $SETTING['NAME_SAVE']){
                CDataToolsProperty::Update($properties[0]['ID'],array('NAME_1C'=>$SETTING['NAME_SAVE']));
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetPropertyNameIblock');
            }

        } else {
            //если свойств много пройдемся по ним
            if(empty($properties)){
                $properties = array();
            }
            if(count($properties)>0){
                foreach($properties as $proper) {
                    if($proper['NAME_1C'] == $SETTING['NAME_SAVE']){
                        $propSave_S_DIRECTORY = $proper["PROPERTY_ID"];
                        $propSave_S_DIRECTORY_MULTIPLE =  $allPropsArr[$proper["PROPERTY_ID"]]["MULTIPLE"];
                    }
                }
            }
        }



        //если свойства нет создадим его
        if(empty($propSave_S_DIRECTORY)) {
            //получим символьный код
            $propSave_S_DIRECTORY = Cutil::translit($SETTING['NAME_SAVE'],'ru', array('change_case' => 'U'));
            $propSave_S_DIRECTORY = CDataToolsEvent::GetCleanCode($propSave_S_DIRECTORY);
            $propSave_S_DIRECTORY = $propSave_S_DIRECTORY.'_ATTR_S_DIRECTORY';
            //создадим свойство
            $arProprsAdd = Array(
                "NAME" => $SETTING['NAME_SAVE'],
                "ACTIVE" => "Y",
                "SORT" => "100",
                "CODE" => $propSave_S_DIRECTORY,
                "XML_ID" => $propSave_S_DIRECTORY,
                "PROPERTY_TYPE" => "S",
                "IBLOCK_ID" => $SETTING['IBLOCK_ID'],
                "USER_TYPE" => "directory",
            );
            $ibp = new CIBlockProperty;
            if($newPropId = $ibp->Add($arProprsAdd)) {

                CDataToolsProperty::Add(array(
                    'PROPERTY_ID' => $newPropId,
                    'PROPERTY_IBLOCK' => $SETTING['IBLOCK_ID'],
                    'PROPERTY_TYPE' => 'S:directory',
                    'FOR_PROPERTY_ID' => $SETTING['FOR_PROPERTY_ID'],
                    'TYPE' => $SETTING['TYPE'],
                    'NAME_1C' => $SETTING['NAME_SAVE']
                ));
                $propSave_S_DIRECTORY = $newPropId;
                $propSave_S_DIRECTORY_MULTIPLE = 'N';
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetPropertyNameIblock');
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetAllPropertyIblock');
            }

        }
        //END



        //если свойство для которого создаем справочник множественное сделаем его таким
        //START
        if(is_array($SETTING['VALUE_SAVE'])){

            if($propSave_S_DIRECTORY_MULTIPLE == 'N' && $propSave_S_DIRECTORY){
                $ibp = new CIBlockProperty;
                $ibp->Update($propSave_S_DIRECTORY, array('MULTIPLE' => 'Y'));
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetAllPropertyIblock');
            }

        }
        //END



        // получим инфоблок для записи
        // START
        $Iblock = CDataToolsEvent::GetIblockDirectoryByProperty($propSave_S_DIRECTORY, 'HIGHLOAD');

        //Получим все инфоблоки,  удалим связь если highLoad удалили
        $IblockHL = CDataToolsEvent::GetHighLoadIblock();
        if($Iblock && empty($IblockHL[$Iblock])) {

            CDataToolsIblock::DeleteByIblockId($Iblock);
            global $CACHE_MANAGER;
            $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetHighLoadIblock');
            unset($Iblock);

        }


        if(empty($Iblock)) {

            //название таблицы
            $SETTING_NAME_SAVE = str_replace(' ', '', $SETTING['NAME_SAVE']);
            $highload_name = Cutil::translit($SETTING_NAME_SAVE,'ru', array('change_case' => 'U'));
            $highload_name = CDataToolsEvent::GetCleanCode($highload_name);
            $highload_name = $highload_name.'1C'.$propSave_S_DIRECTORY;
            $highload_name = str_replace('_', '', $highload_name);

            //таблица в базе
            $highload_table = Cutil::translit($SETTING['NAME_SAVE'],'ru', array('change_case' => 'L'));
            $highload_table = CDataToolsEvent::GetCleanCode($highload_table);
            $highload_table = substr($highload_table, 0, 42);
            $highload_table = 'b_datatools1c_hl_'.$highload_table.'_'.$propSave_S_DIRECTORY;


            //создадим hihtload инфоблок
            $data = array(
                'NAME' => $highload_name,
                'TABLE_NAME' => $highload_table,
            );
            $result = Bitrix\Highloadblock\HighloadBlockTable::add($data);
            $Iblock = $result->getId();
            // создадим пользовательские свойства
            $arFieldsName = array(
                'UF_NAME' => array("Y", "string"),
                'UF_XML_ID' => array("Y", "string"),
                'UF_LINK' => array("N", "string"),
                'UF_DESCRIPTION' => array("N", "string"),
                'UF_FULL_DESCRIPTION' => array("N", "string"),
                'UF_SORT' => array("N", "integer"),
                'UF_FILE' => array("N", "file"),
                'UF_DEF' => array("N", "boolean"),
            );
            $obUserField = new CUserTypeEntity();
            $sort = 100;
            foreach($arFieldsName as $fieldName => $fieldValue)
            {
                $arUserField = array(
                    "ENTITY_ID" => "HLBLOCK_".$Iblock,
                    "FIELD_NAME" => $fieldName,
                    "USER_TYPE_ID" => $fieldValue[1],
                    "XML_ID" => "",
                    "SORT" => $sort,
                    "MULTIPLE" => "N",
                    "MANDATORY" => $fieldValue[0],
                    "SHOW_FILTER" => "N",
                    "SHOW_IN_LIST" => "Y",
                    "EDIT_IN_LIST" => "Y",
                    "IS_SEARCHABLE" => "N",
                    "SETTINGS" => array(),
                );
                $obUserField->Add($arUserField);
                $sort += 100;
            }

            if($Iblock){

                CDataToolsIblock::Add(array(
                    'IBLOCK_ID' => $Iblock,
                    'FOR_PROPERTY_ID' => $propSave_S_DIRECTORY,
                    'TYPE' => $SETTING['TYPE'],
                    "IBLOCK_TYPE" => 'HIGHLOAD'
                ));


                CDataToolsProperty::UpdateByPropertyId($propSave_S_DIRECTORY,
                    array(
                        "PROPERTY_HIGHLOAD_TABLE"=>$highload_table
                    )
                );


                $arProperty['USER_TYPE'] = 'directory';
                $arProperty["USER_TYPE_SETTINGS"] = array(
                    "TABLE_NAME" =>  $highload_table,
                );
                $obProperty = new CIBlockProperty;
                $obProperty->Update($propSave_S_DIRECTORY, $arProperty);


            }

            global $CACHE_MANAGER;
            $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetIblockDirectoryByProperty');
            $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetHighLoadIblock');
        }
        //END



        if($Iblock && $propSave_S_DIRECTORY) {


            $arrElementDirectory = CDataToolsEvent::GetDirectoryHighLoadElement($Iblock);
            $IblockHL = CDataToolsEvent::GetHighLoadIblock();


            if(!is_array($SETTING['VALUE_SAVE'])){
                $SETTING['VALUE_SAVE'] = array($SETTING['VALUE_SAVE']);
            }


            if(empty($SETTING['VALUE_SAVE'])){
                $SETTING['VALUE_SAVE'] = array();
            }
            if(count($SETTING['VALUE_SAVE'])>0){

                $PropsNotAdd =  CDataToolsEvent::GetArrayPropsValueNoAdd($SETTING['IBLOCK_ID']);

                foreach($SETTING['VALUE_SAVE'] as $value_save) {
                    if($value_save && !in_array($value_save, $PropsNotAdd)){

                        $element_xml_id = Cutil::translit($value_save,'ru', array('change_case' => 'U'));
                        $element_xml_id = CDataToolsEvent::GetCleanCode($element_xml_id);

                        //если одно из доп свойств будет файл
                        //START
                        if(is_array($SETTING['UF_HIGHLOAD_DOP'])){
                            foreach($SETTING['UF_HIGHLOAD_DOP'] as $key => $value){
                                $fileArray = CFile::MakeFileArray('/upload/1c_catalog/'.$value);
                                if(is_array($fileArray)){
                                    $SETTING['UF_HIGHLOAD_DOP'][$key] = $fileArray;
                                }
                            }
                        }
                        //END



                        if(empty($arrElementDirectory[$element_xml_id])){
                            $value_save = htmlspecialcharsBack($value_save);
                            $arBxData = array(
                                'UF_NAME' => $value_save,
                                'UF_XML_ID' => $element_xml_id,
                                'UF_SORT' => 100,
                            );

                            //если есть дополнительные данные
                            if(is_array($SETTING['UF_HIGHLOAD_DOP'])){


                                $arBxData = array_merge($arBxData, $SETTING['UF_HIGHLOAD_DOP']);
                            }


                            $INFO_entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($IblockHL[$Iblock]);
                            $INFO_entity_data_class = $INFO_entity->getDataClass();
                            $result = $INFO_entity_data_class::add($arBxData);
                            $resArr['ID'][] = $element_xml_id;


                            global $CACHE_MANAGER;
                            $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetDirectoryHighLoadElement_'.$Iblock);
                        } else {

                            //если есть дополнительные данные
                            if(is_array($SETTING['UF_HIGHLOAD_DOP'])){
                                $INFO_entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($IblockHL[$Iblock]);
                                $INFO_entity_data_class = $INFO_entity->getDataClass();
                                if($SETTING['UF_HIGHLOAD_DOP']['UF_FILE'] && $arrElementDirectory[$element_xml_id]['UF_FILE']){
                                    $SETTING['UF_HIGHLOAD_DOP']['UF_FILE']['del'] = 'Y';
                                    $SETTING['UF_HIGHLOAD_DOP']['UF_FILE']['old_file'] = $arrElementDirectory[$element_xml_id]['UF_FILE'];
                                }
                                $result = $INFO_entity_data_class::update($arrElementDirectory[$element_xml_id]['ID'],$SETTING['UF_HIGHLOAD_DOP']);
                            }


                            $resArr['ID'][] =  $element_xml_id;
                        }

                    }
                }
            }



            if($propSave_S_DIRECTORY && $resArr['ID']) {

                CIBlockElement::SetPropertyValues($SETTING['ID'], $SETTING['IBLOCK_ID'], $resArr['ID'], $propSave_S_DIRECTORY);
                unset($propSave_S_DIRECTORY);
                unset($propSave_S_DIRECTORY_MULTIPLE);
                unset($resArr['ID']);
            }



        }


    }
    //END


    //для выгрузки полей типа строка в свойства типа список
    //START
    function AddPropList($SETTING=array(), $arFields=array()) {


        $SETTING['NAME_SAVE'] = trim($SETTING['NAME_SAVE']);
        $SETTING['VALUE_SAVE'] = trim($SETTING['VALUE_SAVE']);

        //получим все свойства
        $allPropsArr = CDataToolsEvent::GetAllPropertyIblock();

        // проверим множественность и разобъем если надо
        // START
        $resProperMultiple = CDataToolsEvent::GetMultipleProperElement(array(
            'ID' => $SETTING['ID'],
            'IBLOCK_ID' => $SETTING['IBLOCK_ID']
        ));


        if(is_array($resProperMultiple['val_descr'][$SETTING['FOR_PROPERTY_ID']][$SETTING['NAME_SAVE']])){
            $SETTING['VALUE_SAVE'] = $resProperMultiple['val_descr'][$SETTING['FOR_PROPERTY_ID']][$SETTING['NAME_SAVE']];
        }
        elseif(is_array($resProperMultiple['val'][$SETTING['FOR_PROPERTY_ID']]) && empty($resProperMultiple['val_descr'][$SETTING['FOR_PROPERTY_ID']])){
            $SETTING['VALUE_SAVE'] = $resProperMultiple['val'][$SETTING['FOR_PROPERTY_ID']];
        }
        // END


        //получим свойство куда записать
        //START
        $properties = CDataToolsEvent::GetPropertyNameIblock($SETTING['IBLOCK_ID'], $SETTING['FOR_PROPERTY_ID'], 'L');

        // получим нужное значение свойства
        if(count($properties) == 1 && !in_array($SETTING["FOR_PROPERTY_CODE"],array('CML2_ATTRIBUTES','CML2_TRAITS')) ){
            $propSave_L = $properties[0]["PROPERTY_ID"];
            $propSave_L_MULTIPLE =  $allPropsArr[$properties[0]["PROPERTY_ID"]]["MULTIPLE"];
            //если названия отличаются выравняем с теми что в 1С
            if($properties[0]['NAME_1C'] != $SETTING['NAME_SAVE']){
                CDataToolsProperty::Update($properties[0]['ID'],array('NAME_1C'=>$SETTING['NAME_SAVE']));
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetPropertyNameIblock');
            }

        } else {
            //если свойств много пройдемся по ним
            if(empty($properties)){
                $properties = array();
            }
            if(count($properties)>0){
                foreach($properties as $proper) {
                    if($proper['NAME_1C'] == $SETTING['NAME_SAVE']){
                        $propSave_L = $proper["PROPERTY_ID"];
                        $propSave_L_MULTIPLE =  $allPropsArr[$proper["PROPERTY_ID"]]["MULTIPLE"];
                    }
                }
            }


        }


        //  printr($properties);

        //если свойства нет создадим его
        if(empty($propSave_L)) {
            //получим символьный код
            $propSave_L = Cutil::translit($SETTING['NAME_SAVE'],'ru', array('change_case' => 'U'));
            $propSave_L = CDataToolsEvent::GetCleanCode($propSave_L);
            $propSave_L = $propSave_L.'_ATTR_L';
            //создадим свойство
            $arProprsAdd = Array(
                "NAME" => $SETTING['NAME_SAVE'] ,
                "ACTIVE" => "Y",
                "SORT" => "100",
                "CODE" => $propSave_L,
                "XML_ID" => $propSave_L,
                "PROPERTY_TYPE" => "L",
                "LIST_TYPE" => "L",
                "IBLOCK_ID" => $SETTING['IBLOCK_ID'],
                ""
            );
            $ibp = new CIBlockProperty;
            if($newPropId = $ibp->Add($arProprsAdd)) {

                CDataToolsProperty::Add(array(
                    'PROPERTY_ID' => $newPropId,
                    'PROPERTY_IBLOCK' => $SETTING['IBLOCK_ID'],
                    'PROPERTY_TYPE' => 'L',
                    'FOR_PROPERTY_ID' => $SETTING['FOR_PROPERTY_ID'],
                    'TYPE' => $SETTING['TYPE'],
                    'NAME_1C' => $SETTING['NAME_SAVE']
                ));
                $propSave_L = $newPropId;
                $propSave_L_MULTIPLE = 'N';
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetPropertyNameIblock');
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetAllPropertyIblock');
            }

        }
        //END

        //если свойство для которого создаем множественное сделаем его таким
        //START
        if(is_array($SETTING['VALUE_SAVE'])){

            if($propSave_L_MULTIPLE == 'N' && $propSave_L){
                $ibp = new CIBlockProperty;
                $ibp->Update($propSave_L, array('MULTIPLE' => 'Y'));
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetAllPropertyIblock');
            }


        }
        //END

        // получим свойства и занесем новые в список
        // START
        if(!is_array($SETTING['VALUE_SAVE'])){
            $SETTING['VALUE_SAVE'] = array($SETTING['VALUE_SAVE']);
        }


        $arrPropertyValueList = CDataToolsEvent::GetPropertyValueList($propSave_L);
        //  printr($SETTING);

        //соберем свойства которые надо сохранить
        $resArr['ID'] = array();
        if(empty($SETTING['VALUE_SAVE'])){
            $SETTING['VALUE_SAVE'] = array();
        }
        if(count($SETTING['VALUE_SAVE'])>0){
            foreach($SETTING['VALUE_SAVE'] as $vsave){


                if($arrPropertyValueList[$vsave]){
                    $resArr['ID'][] = $arrPropertyValueList[$vsave];
                }
                else {
                    $ibpenum = new CIBlockPropertyEnum;
                    if($PropID = $ibpenum->Add(Array('PROPERTY_ID'=>$propSave_L, 'VALUE'=>$vsave))) {
                        $resArr['ID'][] = $PropID;
                        unset($PropID);
                        global $CACHE_MANAGER;
                        $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetPropertyValueList');
                    }
                }

            }
        }





        if($propSave_L) {
            CIBlockElement::SetPropertyValues($SETTING['ID'], $SETTING['IBLOCK_ID'], $resArr['ID'], $propSave_L);
            unset($propSave_L);
            unset($propSave_L_MULTIPLE);
            unset($resArr['ID']);
        }
        // END



    }
    //END

    //для связи справочников с торговыми предложениями
    //START
    function OffersDirectoryLink(&$arFields=array()) {

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) {

            $OFFERS_ATTRIBUTES = COption::GetOptionString('data.tools1c' , "OFFERS_ATTRIBUTES" , "CML2_ATTRIBUTES");

            $elemProper = CDataToolsEvent::GetPropertyElementIblock($arFields['IBLOCK_ID'], $arFields['ID']);

            if(empty($elemProper)){
                $elemProper = array();
            }
            if(count($elemProper)>0){
                foreach($elemProper as $ob) {
                    if($ob['VALUE'] && $ob['CODE'] == $OFFERS_ATTRIBUTES) {
                        $OFFERS_PROPERTIES_IBLOCK = COption::GetOptionString('data.tools1c' , "OFFERS_PROPERTIES_IBLOCK");

                        if(in_array($ob['PROPERTY_TYPE'], array('S','L','N'))) {

                            CDataToolsEvent::AddPropDirectory(
                                array(
                                    'ID' => $arFields['ID'],
                                    'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                                    'TYPE_IBLOCK_SAVE' => $OFFERS_PROPERTIES_IBLOCK,
                                    'NAME_SAVE' => $ob['DESCRIPTION'],
                                    'VALUE_SAVE' => $ob['VALUE'],
                                    'TYPE' => 'OFFERS_ATTRIBUTES',
                                    'FOR_PROPERTY_ID' => $ob['ID'],
                                    'FOR_PROPERTY_CODE' => $ob['CODE']
                                ),
                                $arFields

                            );

                        }
                    }
                }
            }

        }

        return $arFields;
    }
    //END



    //для связи справочников highload с торговыми предложениями
    //START
    function OffersDirectoryHighLoadLink(&$arFields=array()) {

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) {

            $OFFERS_ATTRIBUTES = COption::GetOptionString('data.tools1c' , "OFFERS_ATTRIBUTES" , "CML2_ATTRIBUTES");

            //получим характеристики которые не надо создавать в highload
            $OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE = COption::GetOptionString('data.tools1c' , "OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE");
            if(!empty($OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE)) {
                $OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE = explode(',' , $OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE);
                if(!is_array($OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE)) {
                    $OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE = array($OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE);
                }
            } else {
                $OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE = array();
            }


            $elemProper = CDataToolsEvent::GetPropertyElementIblock($arFields['IBLOCK_ID'], $arFields['ID']);
            if(empty($elemProper)){
                $elemProper = array();
            }
            if(count($elemProper)>0){


                //получим дополнительные данные в инфоблок из характеристик
                //START
                $UF_highload_dobInfo = array();
                $CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD_DOP_INFO = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD_DOP_INFO");
                if($CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD_DOP_INFO=='Y'){
                    foreach($elemProper as $key => $ob) {
                        if($ob['VALUE'] && $ob['CODE'] == $OFFERS_ATTRIBUTES && !in_array($ob["DESCRIPTION"],$OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE)) {
                            if(strpos($ob['DESCRIPTION'],'||')){
                                $arProp = explode('||',$ob['DESCRIPTION']);
                                $UF_highload_dobInfo[$arProp[0]][$arProp[1]] = $ob['VALUE'];
                                unset($elemProper[$key]);
                            }
                        }
                    }
                }
                //END




                foreach($elemProper as $ob) {
                    if($ob['VALUE'] && $ob['CODE'] == $OFFERS_ATTRIBUTES) {
                        $OFFERS_PROPERTIES_IBLOCK = COption::GetOptionString('data.tools1c' , "OFFERS_PROPERTIES_IBLOCK");

                        if(in_array($ob['PROPERTY_TYPE'], array('S','L','N')) && !in_array($ob["DESCRIPTION"],$OFFERS_PROPERTIES_HIGHLOAD_NOT_CREATE)) {

                            $AddPrpoHighload = array(
                                'ID' => $arFields['ID'],
                                'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                                'TYPE_IBLOCK_SAVE' => $OFFERS_PROPERTIES_IBLOCK,
                                'NAME_SAVE' => $ob['DESCRIPTION'],
                                'VALUE_SAVE' => $ob['VALUE'],
                                'TYPE' => 'OFFERS_ATTRIBUTES',
                                'FOR_PROPERTY_ID' => $ob['ID'],
                                'FOR_PROPERTY_CODE' => $ob['CODE']
                            );

                            if($UF_highload_dobInfo[$ob['DESCRIPTION']]){
                                $AddPrpoHighload['UF_HIGHLOAD_DOP'] = $UF_highload_dobInfo[$ob['DESCRIPTION']];
                            }
                            CDataToolsEvent::AddPropDirectoryHighLoad(
                                $AddPrpoHighload,
                                $arFields
                            );
                        }
                    }
                }
            }
        }

        return $arFields;
    }
    //END


    //для связи свойств товара со справочниками
    //START
    function GoodsDirectoryLink(&$arFields=array()) {

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) {

            //получим значения которые надо делать
            //START
            $GOODS_PROPERTIES_ONE_CAN = COption::GetOptionString('data.tools1c' , "GOODS_PROPERTIES_ONE_CAN");
            if(!empty($GOODS_PROPERTIES_ONE_CAN)) {
                $GOODS_PROPERTIES_ONE_CAN = explode(',' , $GOODS_PROPERTIES_ONE_CAN);
                if(!is_array($GOODS_PROPERTIES_ONE_CAN)) {
                    $GOODS_PROPERTIES_ONE_CAN = array($GOODS_PROPERTIES_ONE_CAN);
                }
            }
            if(empty($GOODS_PROPERTIES_ONE_CAN)){
                $GOODS_PROPERTIES_ONE_CAN = array();
            }
            if(count($GOODS_PROPERTIES_ONE_CAN)>0){
                foreach($GOODS_PROPERTIES_ONE_CAN as $k=>$v) {
                    $GOODS_PROPERTIES_ONE_CAN[$k] = trim($v);
                }
            }

            //END

            //которые игнорировать
            //START
            $GOODS_PROPERTIES_MUST_NOT = COption::GetOptionString('data.tools1c' , "GOODS_PROPERTIES_MUST_NOT");
            if(!empty($GOODS_PROPERTIES_MUST_NOT)) {
                $GOODS_PROPERTIES_MUST_NOT = explode(',' , $GOODS_PROPERTIES_MUST_NOT);
                if(!is_array($GOODS_PROPERTIES_MUST_NOT)) {
                    $GOODS_PROPERTIES_MUST_NOT = array($GOODS_PROPERTIES_MUST_NOT);
                }
            }
            if(empty($GOODS_PROPERTIES_MUST_NOT)){
                $GOODS_PROPERTIES_MUST_NOT = array();
            }
            if(count($GOODS_PROPERTIES_MUST_NOT)>0){
                foreach($GOODS_PROPERTIES_MUST_NOT as $k=>$v) {
                    $GOODS_PROPERTIES_MUST_NOT[$k] = trim($v);
                }
            }

            if(empty($GOODS_PROPERTIES_MUST_NOT)){
                $GOODS_PROPERTIES_MUST_NOT = array();
            }
            //END


            $GOODS_PROPERTIES_IBLOCK = COption::GetOptionString('data.tools1c' , "GOODS_PROPERTIES_IBLOCK");

            $elemProper = CDataToolsEvent::GetPropertyElementIblock($arFields['IBLOCK_ID'], $arFields['ID']);

            if(empty($elemProper)){
                $elemProper = array();
            }
            if(count($elemProper)>0){
                foreach($elemProper as $ob) {
                    if(empty($GOODS_PROPERTIES_ONE_CAN) || in_array($ob['CODE'], $GOODS_PROPERTIES_ONE_CAN)) {
                        if(!in_array($ob['CODE'],$GOODS_PROPERTIES_MUST_NOT)){

                            if(in_array($ob['PROPERTY_TYPE'], array('S','L','N'))) {

                                if($ob['PROPERTY_TYPE'] == 'L'){
                                    $ob['VALUE'] = $ob['VALUE_ENUM'];
                                }
                                CDataToolsEvent::AddPropDirectory(
                                    array(
                                        'ID' => $arFields['ID'],
                                        'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                                        'TYPE_IBLOCK_SAVE' => $GOODS_PROPERTIES_IBLOCK,
                                        'NAME_SAVE' => $ob['NAME'],
                                        'VALUE_SAVE' => $ob['VALUE'],
                                        'TYPE' => 'GOODS_DIRECTORY',
                                        'FOR_PROPERTY_ID' => $ob['ID'],
                                        'FOR_PROPERTY_CODE' => $ob['CODE'],
                                        'XML_ID' => isset($ob['~VALUE']) && is_numeric($ob['~VALUE']) ? $ob['~VALUE'] : NULL
                                    ),
                                    $arFields
                                );

                            }

                        }
                    }
                }
            }

        }

        return $arFields;
    }
    //END



    //для связи свойств товара со справочниками  highload
    //START
    function GoodsDirectoryHighLoadLink(&$arFields=array()) {

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) {

            //получим значения которые надо делать
            //START
            $GOODS_PROPERTIES_ONE_CAN = COption::GetOptionString('data.tools1c' , "GOODS_PROPERTIES_HIGHLOAD_ONE_CAN");
            if(!empty($GOODS_PROPERTIES_ONE_CAN)) {
                $GOODS_PROPERTIES_ONE_CAN = explode(',' , $GOODS_PROPERTIES_ONE_CAN);
                if(!is_array($GOODS_PROPERTIES_ONE_CAN)) {
                    $GOODS_PROPERTIES_ONE_CAN = array($GOODS_PROPERTIES_ONE_CAN);
                }
            }
            if(empty($GOODS_PROPERTIES_ONE_CAN)){
                $GOODS_PROPERTIES_ONE_CAN = array();
            }
            if(count($GOODS_PROPERTIES_ONE_CAN)>0){
                foreach($GOODS_PROPERTIES_ONE_CAN as $k=>$v) {
                    $GOODS_PROPERTIES_ONE_CAN[$k] = trim($v);
                }
            }

            //END

            //которые игнорировать
            //START
            $GOODS_PROPERTIES_MUST_NOT = COption::GetOptionString('data.tools1c' , "GOODS_PROPERTIES_HIGHLOAD_MUST_NOT");
            if(!empty($GOODS_PROPERTIES_MUST_NOT)) {
                $GOODS_PROPERTIES_MUST_NOT = explode(',' , $GOODS_PROPERTIES_MUST_NOT);
                if(!is_array($GOODS_PROPERTIES_MUST_NOT)) {
                    $GOODS_PROPERTIES_MUST_NOT = array($GOODS_PROPERTIES_MUST_NOT);
                }
            }
            if(empty($GOODS_PROPERTIES_MUST_NOT)){
                $GOODS_PROPERTIES_MUST_NOT = array();
            }
            if(count($GOODS_PROPERTIES_MUST_NOT)>0){
                foreach($GOODS_PROPERTIES_MUST_NOT as $k=>$v) {
                    $GOODS_PROPERTIES_MUST_NOT[$k] = trim($v);
                }
            }
            if(empty($GOODS_PROPERTIES_MUST_NOT)){
                $GOODS_PROPERTIES_MUST_NOT = array();
            }
            //END



            $elemProper = CDataToolsEvent::GetPropertyElementIblock($arFields['IBLOCK_ID'], $arFields['ID']);

            if(empty($elemProper)){
                $elemProper = array();
            }

            if(count($elemProper)>0){

                foreach($elemProper as $ob) {

                    if(empty($GOODS_PROPERTIES_ONE_CAN) || in_array($ob['CODE'], $GOODS_PROPERTIES_ONE_CAN)) {
                        if(!in_array($ob['CODE'],$GOODS_PROPERTIES_MUST_NOT)){

                            if(in_array($ob['PROPERTY_TYPE'], array('S','L','N'))) {

                                if($ob['PROPERTY_TYPE'] == 'L'){
                                    $ob['VALUE'] = $ob['VALUE_ENUM'];
                                }
                                CDataToolsEvent::AddPropDirectoryHighLoad(
                                    array(
                                        'ID' => $arFields['ID'],
                                        'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                                        'NAME_SAVE' => $ob['NAME'],
                                        'VALUE_SAVE' => $ob['VALUE'],
                                        'TYPE' => 'GOODS_DIRECTORY',
                                        'FOR_PROPERTY_ID' => $ob['ID'],
                                        'FOR_PROPERTY_CODE' => $ob['CODE']
                                    ),
                                    $arFields
                                );

                            }

                        }
                    }

                }

            }

        }

        return $arFields;
    }
    //END



    //для изменения данных о товаре  - количество, налоги,  НДС и т.д.
    //START
    function ProductChangeInfo(&$arFields=array()) {


        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import') {

            $CHEXBOX_QUALITY = COption::GetOptionString('data.tools1c' , "CHEXBOX_QUALITY");
            $INT_QUALITY_DEFAULT = COption::GetOptionString('data.tools1c' , "INT_QUALITY_DEFAULT");
            $CHEXBOX_VAT_INCLUDED = COption::GetOptionString('data.tools1c' , "CHEXBOX_VAT_INCLUDED");
            $SELECT_VAT = COption::GetOptionString('data.tools1c' , "SELECT_VAT");
            $CHEXBOX_ZERO_STOCK = COption::GetOptionString('data.tools1c' , "CHEXBOX_ZERO_STOCK");
            $CHEXBOX_ZERO_STOCK_DEACTIVE = COption::GetOptionString('data.tools1c' , "CHEXBOX_ZERO_STOCK_DEACTIVE");

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
            if($CHEXBOX_ZERO_STOCK == 'Y' && !strstr($_GET['filename'], 'import')) {
                if(!isset($arFields['QUANTITY']) || !$arFields['QUANTITY']) {
                    $arFields['QUANTITY']=0;
                }
            }

            if($CHEXBOX_ZERO_STOCK_DEACTIVE == 'Y' && isset($arFields['QUANTITY']) && $arFields['QUANTITY']==0 && $arFields["ID"] && !strstr($_GET['filename'], 'import')) {
                global $DB;
                $sql = "UPDATE b_iblock_element SET ACTIVE='N' WHERE ID=".$arFields["ID"];
                $DB->Query($sql);
            }



        }




        return $arFields;
    }
    //END






    //для внесения артикула в торговых предложениях
    //START
    function OffersArticleChange(&$arFields=array()) {

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) {
            $OFFERS_ARTICLE = COption::GetOptionString('data.tools1c' , "OFFERS_ARTICLE");

            // определим ID товара предложения
            $arr_xml_id_element = CDataToolsEvent::GetXmlIdElementByOffers($arFields['IBLOCK_ID']);
            $ID_OFFERS_GOODS = $arr_xml_id_element[$arFields['XML_ID_GOODS']];
            //получим артикулы
            $ArrArticle = CDataToolsEvent::GetArticleElementByOffers($arFields['IBLOCK_ID']);

            if($ArrArticle[$ID_OFFERS_GOODS]){
                CIBlockElement::SetPropertyValueCode($arFields['ID'], $OFFERS_ARTICLE, $ArrArticle[$ID_OFFERS_GOODS]);
            }


        }

        return $arFields;
    }
    //END

    //для внесения фотографий в торговые предложения
    //START
    function OffersMorePhotoChange(&$arFields=array()) {

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) {
            $OFFERS_MORE_PHOTO = COption::GetOptionString('data.tools1c' , "OFFERS_MORE_PHOTO");

            // определим ID товара предложения
            $arr_xml_id_element = CDataToolsEvent::GetXmlIdElementByOffers($arFields['IBLOCK_ID']);
            $ID_OFFERS_GOODS = $arr_xml_id_element[$arFields['XML_ID_GOODS']];
            //получим изображения
            $arr_more_photo = CDataToolsEvent::GetMorePhotoElementByOffers($arFields['IBLOCK_ID']);

            //получим характеристики торгового предложения
            //START
            $attrib_offers = array();
            $proper_offers_more_photo = array();
            $OFFERS_ATTRIBUTES = COption::GetOptionString('data.tools1c' , "OFFERS_ATTRIBUTES" , "CML2_ATTRIBUTES");
            $elemProper = CDataToolsEvent::GetPropertyElementIblock($arFields['IBLOCK_ID'], $arFields['ID']);
            if(empty($elemProper)){
                $elemProper = array();
            }
            if(count($elemProper)>0){
                foreach($elemProper as $ob) {
                    if($ob['VALUE'] && $ob['CODE'] == $OFFERS_ATTRIBUTES) {
                        $attrib_offers[$ob['VALUE']] = $ob['VALUE'];
                    }
                    if($ob['VALUE'] && $ob['CODE'] == $OFFERS_MORE_PHOTO) {
                        $proper_offers_more_photo[$ob['VALUE']] = $ob['PROPERTY_VALUE_ID'];
                    }

                }
            }




            //END

            //получим изображения в нужном порядке
            //START
            //соберем массив изображений
            $arr_offers_more_photo = array();
            if ($attrib_offers) {
                $OFFERS_MORE_PHOTO_RAZDEL = COption::GetOptionString('data.tools1c', "OFFERS_MORE_PHOTO_RAZDEL");
                $MORE_ATTRIBUTES = COption::GetOptionString('data.tools1c', "CHEXBOX_OFFERS_MORE_PHOTO_MORE_ATTRIBUTES");
                $MORE_ATTRIBUTES_COUNT = COption::GetOptionString('data.tools1c', "CHEXBOX_OFFERS_MORE_PHOTO_MORE_ATTRIBUTES_COUNT");
                $DUPLICATE_PHOTO_OFFERS = COption::GetOptionString('data.tools1c', "DUPLICATE_PHOTO_OFFERS");
                if (empty($arr_more_photo[$ID_OFFERS_GOODS])) {
                    $arr_more_photo[$ID_OFFERS_GOODS] = array();
                }
                if (count($arr_more_photo[$ID_OFFERS_GOODS]) > 0 && $MORE_ATTRIBUTES == 'N') {
                    foreach ($arr_more_photo[$ID_OFFERS_GOODS] as $idFile => $descrfile) {
                        $arr_descrfile = explode($OFFERS_MORE_PHOTO_RAZDEL, $descrfile);
                        if ($attrib_offers[$arr_descrfile[0]] && $arr_descrfile[1]) {
                            if($DUPLICATE_PHOTO_OFFERS == 'Y'){
                              $idFile = CFile::CopyFile($idFile);
                            }
                            $arr_offers_more_photo[$arr_descrfile[1]] = $idFile;
                        }
                    }
                }
                if(count($arr_more_photo[$ID_OFFERS_GOODS]) > 0 && $MORE_ATTRIBUTES == 'Y' && $MORE_ATTRIBUTES_COUNT != '' && $MORE_ATTRIBUTES_COUNT>0){
                    foreach ($arr_more_photo[$ID_OFFERS_GOODS] as $idFile => $descrfile) {
                        $arr_descrfile = explode($OFFERS_MORE_PHOTO_RAZDEL, $descrfile);
                        $good_count_attributes = 0;
                        for($i=0;$i<count($arr_descrfile);$i++){
                            if(in_array($arr_descrfile[$i], $attrib_offers)){
                                $good_count_attributes++;
                            }
                        }
                        if ($good_count_attributes == $MORE_ATTRIBUTES_COUNT) {
                            if($DUPLICATE_PHOTO_OFFERS == 'Y'){
                                $idFile = CFile::CopyFile($idFile);
                            }
                            $arr_offers_more_photo[$descrfile] = $idFile;
                        }
                    }
                }


            }
            ksort($arr_offers_more_photo);

            //подсчитим изображения которые ушли из обихода
            $arr_offers_more_photo_new = array();
            if(empty($arr_offers_more_photo)){
                $arr_offers_more_photo = array();
            }
            if(count($arr_offers_more_photo)>0){
                foreach($arr_offers_more_photo as $key=>$idFile){

                    if($proper_offers_more_photo[$idFile]){
                        $arr_offers_more_photo_new[$proper_offers_more_photo[$idFile]] = array(
                            'VALUE'=>$idFile,
                            'DESCRIPTION'=> $arr_more_photo[$ID_OFFERS_GOODS][$idFile]
                        );



                        unset($proper_offers_more_photo[$idFile]);
                    }
                    else {
                        $arr_offers_more_photo_new[$key] = array(
                            'VALUE'=>$idFile,
                            'DESCRIPTION'=> $arr_more_photo[$ID_OFFERS_GOODS][$idFile]
                        );
                    }

                }
            }

            if(empty($proper_offers_more_photo)){
                $proper_offers_more_photo = array();
            }
            if(count($proper_offers_more_photo)>0){
                foreach($proper_offers_more_photo as $idFile=>$key){
                    $arr_offers_more_photo_new[$key] = array('del'=>'Y');
                }
            }

            $arr_offers_more_photo = $arr_offers_more_photo_new;
            //END


            if($arr_offers_more_photo && $OFFERS_MORE_PHOTO && $arr_offers_more_photo){
                CIBlockElement::SetPropertyValueCode($arFields['ID'], $OFFERS_MORE_PHOTO, $arr_offers_more_photo);
            }



            //добавим фотогрвфию в элемент если надо
            //START
            $CHEXBOX_OFFERS_MORE_PHOTO_PREVIEW_PICTURE = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_MORE_PHOTO_PREVIEW_PICTURE");
            $CHEXBOX_OFFERS_MORE_PHOTO_DETAIL_PICTURE = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_MORE_PHOTO_DETAIL_PICTURE");
            if($CHEXBOX_OFFERS_MORE_PHOTO_PREVIEW_PICTURE=='Y' || $CHEXBOX_OFFERS_MORE_PHOTO_DETAIL_PICTURE=='Y') {

                $first_more_photo = current($arr_offers_more_photo);

                $PREVIEW_PICTURE = 'null';
                $DETAIL_PICTURE = 'null';


                if($CHEXBOX_OFFERS_MORE_PHOTO_PREVIEW_PICTURE=='Y' && $first_more_photo['VALUE']){
                    $PREVIEW_PICTURE = $first_more_photo['VALUE'];
                }
                if($CHEXBOX_OFFERS_MORE_PHOTO_DETAIL_PICTURE=='Y' && $first_more_photo['VALUE']){
                    $DETAIL_PICTURE = $first_more_photo['VALUE'];
                }
                //приходтся SQL делать
                global $DB;
                $sql = "UPDATE b_iblock_element SET PREVIEW_PICTURE=".$PREVIEW_PICTURE.", DETAIL_PICTURE=".$DETAIL_PICTURE ." WHERE ID=".$arFields['ID'];
                $DB->Query($sql);

            }
            //END



        }

        return $arFields;
    }
    //END

    //для множественных свойств
    //START
    function MultipleProperiesGoods(&$arFields=array(), $type=false) {

        /* if($arFields['ID'] == '16353'){
             printr($resProper);
             die;
         } */

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], $type) ) {

            $resProper = CDataToolsEvent::GetMultipleProperElement(array(
                'ID' => $arFields['ID'],
                'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                'TYPE' => $type
            ));

            CIBlockElement::SetPropertyValuesEx($arFields['ID'], $arFields['IBLOCK_ID'], $resProper['val']);
            
            global $CACHE_MANAGER;
            $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetPropertyElementIblock');

        }
        return $arFields;

    }
    //END


    //Добавим скидку для товара
    //START
    function DiscontAdd($SETTING=array(), $arFields=array()) {

        //определим тип скидки и получим доп параметры
        //START
        if(strpos($SETTING['DISCONT_VALUE'], '%')){
            $SETTING['TYPE'] = 'P';
        }
        else {
            $SETTING['TYPE'] = 'F';
        }
        //$SETTING['DISCONT_VALUE'] = intval($SETTING['DISCONT_VALUE']);
        //END

        $sites = unserialize(COption::GetOptionString(self::MODULE_ID, "DICOUNT_SITE"));


        //получим id скидки если ее нет то создадим
        //START
        $siteId = CDataToolsEvent::GetIblockSiteById($arFields['IBLOCK_ID']);
        foreach ($siteId as $id) {
            if(count($sites) > 0 && !empty($sites)){
                if(!in_array($id,$sites)) continue;
            }
            $discountAll = CDataToolsEvent::GetAllDiscontTools($id);
            $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);

            //сделаем проверку на наличие скидки и привязанных к ней товаров, стоит ограничение в 250 товаров на скидку
            if($SETTING['DISCONT_VALUE'] && $discountAll['DISCOUNT_ID_SEARCH'][$SETTING['TYPE'].'_'.$SETTING['DISCONT_VALUE']]){

                foreach($discountAll['DISCOUNT_ID_SEARCH'][$SETTING['TYPE'].'_'.$SETTING['DISCONT_VALUE']] as $disconyId) {
                    if(count($arrDiscontProduct["DISCONT_INFO"][$disconyId]) < 250){
                        $SETTING['DISCONT_ID'] = $disconyId;
                        break;
                    }
                }

            }

            $is = CCatalogDiscount::GetByID($SETTING['DISCONT_ID']);
            if($is['SITE_ID'] != $id)
                unset($SETTING['DISCONT_ID']);
            if($SETTING['DISCONT_VALUE'] && empty($SETTING['DISCONT_ID'])) {

                $DiscountAdd = array(
                    "SITE_ID" => $id,//$siteId[0],
                    "ACTIVE" => 'Y',
                    "NAME" => GetMessage(self::MODULE_ID.'_DISCONT_NAME'),
                    "VALUE_TYPE" => $SETTING['TYPE'],
                    "VALUE" => $SETTING['DISCONT_VALUE']
                );
                if($SETTING['DISCONT_CURRENCY']){
                    $DiscountAdd['CURRENCY'] = $SETTING['DISCONT_CURRENCY'];
                } else {
                    $DiscountAdd['CURRENCY'] = COption::GetOptionString("sale", "CURRENCY_DEFAULT", "RUB");
                }


                $SELECT_DISCONT_LAST_DISCOUNT = COption::GetOptionString('data.tools1c' , "SELECT_DISCONT_LAST_DISCOUNT");
                if($SELECT_DISCONT_LAST_DISCOUNT){
                    $DiscountAdd['LAST_DISCOUNT'] = $SELECT_DISCONT_LAST_DISCOUNT;
                }


                $SELECT_DISCONT_GROUP_IDS = COption::GetOptionString('data.tools1c' , "SELECT_DISCONT_GROUP_IDS");
                $SELECT_DISCONT_GROUP_IDS = unserialize($SELECT_DISCONT_GROUP_IDS);
                if(count($SELECT_DISCONT_GROUP_IDS) > 0){
                    $DiscountAdd['GROUP_IDS'] = $SELECT_DISCONT_GROUP_IDS;
                }


                $SELECT_DISCONT_CATALOG_GROUP_IDS = COption::GetOptionString('data.tools1c' , "SELECT_DISCONT_CATALOG_GROUP_IDS");
                $SELECT_DISCONT_CATALOG_GROUP_IDS = unserialize($SELECT_DISCONT_CATALOG_GROUP_IDS);
                if(count($SELECT_DISCONT_CATALOG_GROUP_IDS) > 0){
                    $DiscountAdd['CATALOG_GROUP_IDS'] = $SELECT_DISCONT_CATALOG_GROUP_IDS;
                }


                if($SETTING['DISCONT_ID'] = CCatalogDiscount::Add($DiscountAdd)) {
                    CDataToolsDiscount::Add(
                        array(
                            "DISCONT_ID" => $SETTING['DISCONT_ID'],
                            "DISCONT_VALUE" => $SETTING['DISCONT_VALUE'],
                            "TYPE" => $SETTING['TYPE']
                        )
                    );
                }


                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetAllDiscontTools'.$id);


            }
            //END



            //получим товары и скидки, обработаем их
            //START
            $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);

            //если у товара уже нет скидки удалим ее
            if(empty($SETTING['DISCONT_VALUE']) && $arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]] ){

                //пройдемся по скидкам продукта
                if(empty($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]])){
                    $arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]] = array();
                }
                if(count($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]])>0){
                    foreach($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]] as $discontId) {

                        // соберем массив товаров для скидки кроме текущего товара
                        $GoodsArr = array();
                        foreach($arrDiscontProduct["DISCONT_INFO"][$discontId] as $GoodId) {
                            if($GoodId != $arFields["ID"]){
                                $GoodsArr[] = array (
                                    'CLASS_ID' => 'CondIBElement',
                                    'DATA' =>
                                        array (
                                            'logic' => 'Equal',
                                            'value' => $GoodId,
                                        )
                                );
                            }
                        }

                        $DiscountAdd = array(
                            "CONDITIONS" => array(
                                'CLASS_ID' => 'CondGroup',
                                'DATA' => array(
                                    'All' => 'OR',
                                    'True' => 'True'
                                ),
                                'CHILDREN' => $GoodsArr
                            )
                        );



                        CCatalogDiscount::Update($discontId,$DiscountAdd);

                    }
                }


                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetDiscontProduct'.$id);
                $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);
            }



            // если товар не состоит в данной скидке, добавим ее к скидке
            if(empty($arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']])){
                $arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']] = array();
            }
            if(!in_array($arFields["ID"], $arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']])) {

                $allIdElementIblock = CDataToolsEvent::GetAllIdElementIblock();
                // составим массив для добавления скидки
                $GoodsArr = array();
                if(empty($arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']])){
                    $arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']] = array();
                }
                if(count($arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']])>0){
                    foreach($arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']] as $GoodId) {

                        //если элемента уже нет в базе снимем его из скидки
                        if(empty($allIdElementIblock[$GoodId])) {
                            continue;
                        }

                        $GoodsArr[] = array (
                            'CLASS_ID' => 'CondIBElement',
                            'DATA' =>
                                array (
                                    'logic' => 'Equal',
                                    'value' => $GoodId,
                                )
                        );


                    }
                }

                $GoodsArr[] = array (
                    'CLASS_ID' => 'CondIBElement',
                    'DATA' =>
                        array (
                            'logic' => 'Equal',
                            'value' => $arFields["ID"],
                        )
                );
                $DiscountAdd = array(
                    "CONDITIONS" => array(
                        'CLASS_ID' => 'CondGroup',
                        'DATA' => array(
                            'All' => 'OR',
                            'True' => 'True'
                        ),
                        'CHILDREN' => $GoodsArr
                    )
                );
                CCatalogDiscount::Update($SETTING['DISCONT_ID'],$DiscountAdd);
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetDiscontProduct'.$id);
                $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);
            }

            //нужно почистить другие скидки от этого товара
            if(count($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]]) > 1) {
                //пройдемся по скидкам продукта
                if(empty($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]])){
                    $arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]] = array();
                }

                foreach($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]] as $discontId) {
                    //если это не текущая скидка
                    if($discontId != $SETTING['DISCONT_ID']) {
                        // соберем массив товаров для скидки кроме текущего товара
                        $GoodsArr = array();
                        foreach($arrDiscontProduct["DISCONT_INFO"][$discontId] as $GoodId) {
                            if($GoodId != $arFields["ID"]){
                                $GoodsArr[] = array (
                                    'CLASS_ID' => 'CondIBElement',
                                    'DATA' =>
                                        array (
                                            'logic' => 'Equal',
                                            'value' => $GoodId,
                                        )
                                );
                            }
                        }

                        $DiscountAdd = array(
                            "CONDITIONS" => array(
                                'CLASS_ID' => 'CondGroup',
                                'DATA' => array(
                                    'All' => 'OR',
                                    'True' => 'True'
                                ),
                                'CHILDREN' => $GoodsArr
                            )
                        );

                        CCatalogDiscount::Update($discontId,$DiscountAdd);


                    }

                }
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetDiscontProduct'.$id);
                $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);
            }
            //END


            //почистим массив от пустых скидок
            $CHEXBOX_DISCONT_EMPTY_DELETE = COption::GetOptionString('data.tools1c' , "CHEXBOX_DISCONT_EMPTY_DELETE");
            if($CHEXBOX_DISCONT_EMPTY_DELETE == 'Y') {
                $DELETE_DISCONT = 'N';
                if(empty($arrDiscontProduct['DISCONT_INFO'])){
                    $arrDiscontProduct['DISCONT_INFO'] = array();
                }
                if(count($arrDiscontProduct['DISCONT_INFO'])>0){
                    foreach($arrDiscontProduct['DISCONT_INFO'] as $kDiscont => $vDiscont) {
                        if(count($vDiscont) == 0){
                            CCatalogDiscount::Delete($kDiscont);
                            $DELETE_DISCONT = 'Y';
                        }
                    }
                }

                if($DELETE_DISCONT == 'Y') {
                    $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);
                }
            }
       }



    }
    //END

    //Выставим скидки на товар из свойства инфоблока
    //START
    function DiscontGoodsProps(&$arFields=array()) {
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) {
            $DISCONT_CODE = COption::GetOptionString('data.tools1c' , "DISCONT_CODE");
            $DISCOUNT_CONDITION_BASKET = COption::GetOptionString('data.tools1c' , "DISCOUNT_CONDITION_BASKET");
            $elemProper = CDataToolsEvent::GetPropertyElementIblock($arFields['IBLOCK_ID'], $arFields['ID']);
            if(empty($elemProper)){
                $elemProper = array();
            }
            if(count($elemProper)>0){
                    foreach($elemProper as $ob) {
                        if($ob['CODE'] == $DISCONT_CODE) {
                            if($DISCOUNT_CONDITION_BASKET == 'Y'){
                                CDataToolsEvent::DiscontsConditionBasket(
                                    array(
                                        'DISCONT_VALUE' => ($ob['LIST_TYPE'] == 'L' && !is_null($ob['VALUE_ENUM']))?$ob['VALUE_ENUM']:$ob['VALUE'],
                                    ),
                                    $arFields
                                );
                            }else {
                                CDataToolsEvent::DiscontAdd(
                                    array(
                                        'DISCONT_VALUE' => ($ob['LIST_TYPE'] == 'L' && !is_null($ob['VALUE_ENUM']))?$ob['VALUE_ENUM']:$ob['VALUE'],
                                    ),
                                    $arFields
                                );
                            }
                        }
                    }
                }
            }

        return $arFields;
    }
    //END


    // Выставим скидки на товар из разницы в цене
    // START
    function DiscontGoodsPrice(&$arFields=array()) {

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' ) {

            $DISCONT_PRICE_BASE = COption::GetOptionString('data.tools1c' , "DISCONT_PRICE_BASE");
            $DISCONT_PRICE_DISCOUNT = COption::GetOptionString('data.tools1c' , "DISCONT_PRICE_DISCOUNT");
            $DISCOUNT_CONDITION_BASKET = COption::GetOptionString('data.tools1c' , "DISCOUNT_CONDITION_BASKET");


            $ArrPriceGoods = array();
            if(empty($arFields["PRICES"])){
                $arFields["PRICES"] = array();
            }
            if(count($arFields["PRICES"])>0){
                foreach($arFields["PRICES"] as $priceItem){
                    $ArrPriceGoods[$priceItem['PRICE']['ID']]['PRICE'] = $priceItem[GetMessage(self::MODULE_ID.'_PricePerUnit')];
                    $ArrPriceGoods[$priceItem['PRICE']['ID']]['CURRENCY'] = $priceItem['PRICE']['CURRENCY'];
                }
            }


            if(!empty($ArrPriceGoods[$DISCONT_PRICE_BASE]['PRICE']) && !empty($ArrPriceGoods[$DISCONT_PRICE_DISCOUNT]['PRICE']) && $ArrPriceGoods[$DISCONT_PRICE_BASE]['PRICE'] > $ArrPriceGoods[$DISCONT_PRICE_DISCOUNT]['PRICE']) {

                //попробуем получить процент для скидки
                $percent = 0;
                // $percent = 100*$ArrPriceGoods[$DISCONT_PRICE_BASE]/$ArrPriceGoods[$DISCONT_PRICE_DISCOUNT]-100;
                $percent = 100*$ArrPriceGoods[$DISCONT_PRICE_DISCOUNT]['PRICE']/$ArrPriceGoods[$DISCONT_PRICE_BASE]['PRICE'];
                $percent = 100-$percent;
                $percent = strval($percent);


                // если процент целый то зададим процентом скидку
                if($percent == (int)$percent) {
                    $ob['VALUE'] = $percent.'%';
                } else {
                    // если процент не целый зададим фиксированном
                    $ob['VALUE'] = $ArrPriceGoods[$DISCONT_PRICE_BASE]['PRICE']-$ArrPriceGoods[$DISCONT_PRICE_DISCOUNT]['PRICE'];
                }



                if($ob['VALUE']) {
                    $CDataToolsEvent_DiscontAdd =  array(
                        'DISCONT_VALUE' => $ob['VALUE'],
                    );
                    if($ArrPriceGoods[$DISCONT_PRICE_BASE]['CURRENCY']){
                        $CDataToolsEvent_DiscontAdd['DISCONT_CURRENCY'] = $ArrPriceGoods[$DISCONT_PRICE_BASE]['CURRENCY'];
                    }
                    if($DISCOUNT_CONDITION_BASKET == 'Y'){
                        CDataToolsEvent::DiscontsConditionBasket(
                            $CDataToolsEvent_DiscontAdd,
                            $arFields
                        );
                    }else{
                        CDataToolsEvent::DiscontAdd(
                            $CDataToolsEvent_DiscontAdd,
                            $arFields
                        );
                    }


                }


            }


            if((empty($ArrPriceGoods[$DISCONT_PRICE_DISCOUNT]['PRICE']) || $ArrPriceGoods[$DISCONT_PRICE_DISCOUNT]['PRICE'] == $ArrPriceGoods[$DISCONT_PRICE_BASE]['PRICE'])  && $arFields["PRICES"]){
                if($DISCOUNT_CONDITION_BASKET == 'Y'){
                    CDataToolsEvent::DiscontsConditionBasket(
                        array(
                            'DISCONT_VALUE' => 0,
                        ),
                        $arFields
                    );
                }else{
                    CDataToolsEvent::DiscontAdd(
                        array(
                            'DISCONT_VALUE' => 0,
                        ),
                        $arFields
                    );
                }
            }


        }

        return $arFields;
    }


    //END


    // событие добавления товаров каталога
    function OnAfterIBlockElementAdd(&$arFields=array()) {

        if(!self::getDemo()){
            return  $arFields;
        }
        //внесем в сейсию товары которые обновили
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) {
            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['ELEMENT_ADD']['ALL'][] = $arFields['ID'];
            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['ELEMENT_ADD']['OFFERS'][] = $arFields['ID'];

            $arr_xml_id = explode('#',$arFields['XML_ID']);
            $arFields['XML_ID_GOODS'] = $arr_xml_id[0];
        }
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) {
            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['ELEMENT_ADD']['ALL'][] = $arFields['ID'];
            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['ELEMENT_ADD']['IMPORT'][] = $arFields['ID'];
        }

        //заполним артикул в предложениях
        //START
        $CHEXBOX_ARTICLE = COption::GetOptionString('data.tools1c' , "CHEXBOX_ARTICLE");
        if($CHEXBOX_ARTICLE == 'Y') {
            CDataToolsEvent::OffersArticleChange($arFields);
        }
        //END

        // свяжем справочники с торговыми предложениями
        // начало
        $CHEXBOX_OFFERS_PROPERTIES = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_PROPERTIES");
        if($CHEXBOX_OFFERS_PROPERTIES == 'Y') {
            CDataToolsEvent::OffersDirectoryLink($arFields);
        }
        //конец

        // свяжем справочники (highload инфоблоки) с торговыми предложениями
        // начало
        $CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD");
        if($CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD == 'Y') {
            CDataToolsEvent::OffersDirectoryHighLoadLink($arFields);
        }
        //конец

        // выгрузим свойства характеристик в отдельные свойста типа строка
        // начало
        $CHEXBOX_OFFERS_PROPERTIES_STRING = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_PROPERTIES_STRING");
        if($CHEXBOX_OFFERS_PROPERTIES_STRING == 'Y') {
            CDataToolsEvent::OffersAttributesSeparate($arFields, 'offers', 'OFFERS_ATTRIBUTES');
        }
        //конец

        // выгрузим свойства характеристик в отдельные свойста типа список
        // начало
        $CHEXBOX_OFFERS_PROPERTIES_LIST = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_PROPERTIES_LIST");
        if($CHEXBOX_OFFERS_PROPERTIES_LIST == 'Y') {
            CDataToolsEvent::OffersAttributesProperList($arFields, 'offers', 'OFFERS_ATTRIBUTES');
        }
        // конец

        // выгрузим свойства реквизитов в отдельные свойста типа строка
        // начало
        $CHEXBOX_TRAITS_STRING = COption::GetOptionString('data.tools1c' , "CHEXBOX_TRAITS_STRING");
        if($CHEXBOX_TRAITS_STRING == 'Y') {
            CDataToolsEvent::OffersAttributesSeparate($arFields, 'import', 'GOODS_TRAITS_STRING');
        }
        //конец

        // свяжем свойства товара со справочниками
        //START
        $CHEXBOX_GOODS_PROPERTIES = COption::GetOptionString('data.tools1c' , "CHEXBOX_GOODS_PROPERTIES");
        if($CHEXBOX_GOODS_PROPERTIES == 'Y') {
            CDataToolsEvent::GoodsDirectoryLink($arFields);
        }
        //END

        // свяжем свойства товара со справочниками
        //START
        $CHEXBOX_GOODS_PROPERTIES_HIGHLOAD = COption::GetOptionString('data.tools1c' , "CHEXBOX_GOODS_PROPERTIES_HIGHLOAD");
        if($CHEXBOX_GOODS_PROPERTIES_HIGHLOAD == 'Y') {
            CDataToolsEvent::GoodsDirectoryHighLoadLink($arFields);
        }
        //END

        // реализуем множественные свойства
        // начало
        $CHEXBOX_MULTIPROPER = COption::GetOptionString('data.tools1c' , "CHEXBOX_MULTIPROPER");
        if($CHEXBOX_MULTIPROPER == 'Y') {
            CDataToolsEvent::MultipleProperiesGoods($arFields, 'import');
        }
        //конец

        //реализуем множественные свойства торговых предложений
        //начало
        $CHEXBOX_MULTIPROPER_OFFERS = COption::GetOptionString('data.tools1c' , "CHEXBOX_MULTIPROPER_OFFERS");
        if($CHEXBOX_MULTIPROPER_OFFERS == 'Y') {
            CDataToolsEvent::MultipleProperiesGoods($arFields, 'offers');
        }
        //конец


        //заполним фотографии в торговыех предложениях
        //START
        $CHEXBOX_OFFERS_MORE_PHOTO = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_MORE_PHOTO");
        if($CHEXBOX_OFFERS_MORE_PHOTO == 'Y') {
            CDataToolsEvent::OffersMorePhotoChange($arFields);
        }
        //END


        // выставим скидки на товар из 1С с помощью свойств товара
        //START
        $CHEXBOX_DISCONT = COption::GetOptionString('data.tools1c' , "CHEXBOX_DISCONT");
        if($CHEXBOX_DISCONT == 'Y') {
            CDataToolsEvent::DiscontGoodsProps($arFields);
        }
        //END

        /*
        // выставим скидки на товар из 1С из разницы в ценах
        // START
        $CHEXBOX_DISCONT = COption::GetOptionString('data.tools1c' , "CHEXBOX_DISCONT_PRICE");
        if($CHEXBOX_DISCONT == 'Y') {
             CDataToolsEvent::DiscontGoodsPrice($arFields);
        }
        //END
        */

        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetAllIdElementIblock');


        //обновим фасетный индекс, в множественности использовалась функция CIBlockElement::SetPropertyValuesEx в ней нет фасеты
        $UPDATE_FASET_FILLTER = COption::GetOptionString('data.tools1c' , "UPDATE_FASET_FILLTER");
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && $UPDATE_FASET_FILLTER == 'Y') {
            if($CHEXBOX_MULTIPROPER == 'Y') {
                \Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex($arFields['IBLOCK_ID'], $arFields['ID']);
            }
        }


    }

    //событие обновления товаров каталога
    function OnAfterIBlockElementUpdate(&$arFields=array()){


        if(!self::getDemo()){
            return  $arFields;
        }

        //внесем в сейсию товары которые обновили
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'offers')) {
            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['ELEMENT_UPDATE']['ALL'][] = $arFields['ID'];
            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['ELEMENT_UPDATE']['OFFERS'][] = $arFields['ID'];

            $arr_xml_id = explode('#',$arFields['XML_ID']);
            $arFields['XML_ID_GOODS'] = $arr_xml_id[0];
        }
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) {

            /*printr($arFields);
              die; */

            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['ELEMENT_UPDATE']['ALL'][] = $arFields['ID'];
            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['ELEMENT_UPDATE']['IMPORT'][] = $arFields['ID'];
        }


        //заполним артикул в предложениях
        // начало
        $CHEXBOX_ARTICLE = COption::GetOptionString('data.tools1c' , "CHEXBOX_ARTICLE");
        if($CHEXBOX_ARTICLE == 'Y') {
            CDataToolsEvent::OffersArticleChange($arFields);
        }
        //конец

        // свяжем справочники с торговыми предложениями
        // начало
        $CHEXBOX_OFFERS_PROPERTIES = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_PROPERTIES");
        if($CHEXBOX_OFFERS_PROPERTIES == 'Y') {
            CDataToolsEvent::OffersDirectoryLink($arFields);
        }
        //конец

        // свяжем справочники (highload инфоблоки) с торговыми предложениями
        // начало
        $CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD");
        if($CHEXBOX_OFFERS_PROPERTIES_HIGHLOAD == 'Y') {
            CDataToolsEvent::OffersDirectoryHighLoadLink($arFields);
        }
        //конец

        // выгрузим свойства характеристик в отдельные свойста типа стройка
        // начало
        $CHEXBOX_OFFERS_PROPERTIES_STRING = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_PROPERTIES_STRING");
        if($CHEXBOX_OFFERS_PROPERTIES_STRING == 'Y') {
            CDataToolsEvent::OffersAttributesSeparate($arFields, 'offers', 'OFFERS_ATTRIBUTES');
        }
        //конец

        // выгрузим свойства характеристик в отдельные свойста типа список
        // начало
        $CHEXBOX_OFFERS_PROPERTIES_LIST = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_PROPERTIES_LIST");
        if($CHEXBOX_OFFERS_PROPERTIES_LIST == 'Y') {
            CDataToolsEvent::OffersAttributesProperList($arFields, 'offers', 'OFFERS_ATTRIBUTES');
        }
        // конец

        // выгрузим свойства реквизитов в отдельные свойста типа стройка
        // начало
        $CHEXBOX_TRAITS_STRING = COption::GetOptionString('data.tools1c' , "CHEXBOX_TRAITS_STRING");
        if($CHEXBOX_TRAITS_STRING == 'Y') {
            CDataToolsEvent::OffersAttributesSeparate($arFields, 'import', 'GOODS_TRAITS_STRING');
        }
        //конец

        // свяжем свойства товара со справочниками
        //START
        $CHEXBOX_GOODS_PROPERTIES = COption::GetOptionString('data.tools1c' , "CHEXBOX_GOODS_PROPERTIES");
        if($CHEXBOX_GOODS_PROPERTIES == 'Y') {
            CDataToolsEvent::GoodsDirectoryLink($arFields);
        }
        //END

        // свяжем свойства товара со справочниками
        //START
        $CHEXBOX_GOODS_PROPERTIES_HIGHLOAD = COption::GetOptionString('data.tools1c' , "CHEXBOX_GOODS_PROPERTIES_HIGHLOAD");
        if($CHEXBOX_GOODS_PROPERTIES_HIGHLOAD == 'Y') {
            CDataToolsEvent::GoodsDirectoryHighLoadLink($arFields);
        }
        //END

        // реализуем множественные свойства
        // начало
        $CHEXBOX_MULTIPROPER = COption::GetOptionString('data.tools1c' , "CHEXBOX_MULTIPROPER");
        if($CHEXBOX_MULTIPROPER == 'Y') {
            CDataToolsEvent::MultipleProperiesGoods($arFields, 'import');
        }
        //конец


        //реализуем множественные свойства торговых предложений
        //начало
        $CHEXBOX_MULTIPROPER_OFFERS = COption::GetOptionString('data.tools1c' , "CHEXBOX_MULTIPROPER_OFFERS");
        if($CHEXBOX_MULTIPROPER_OFFERS == 'Y') {
            CDataToolsEvent::MultipleProperiesGoods($arFields, 'offers');
        }
        //конец


        //заполним фотографии в торговых предложениях
        //START
        $CHEXBOX_OFFERS_MORE_PHOTO = COption::GetOptionString('data.tools1c' , "CHEXBOX_OFFERS_MORE_PHOTO");
        if($CHEXBOX_OFFERS_MORE_PHOTO == 'Y') {
            CDataToolsEvent::OffersMorePhotoChange($arFields);
        }
        //END


        // выставим скидки на товар из 1С с помощью свойств товара
        //START
        $CHEXBOX_DISCONT = COption::GetOptionString('data.tools1c' , "CHEXBOX_DISCONT");
        if($CHEXBOX_DISCONT == 'Y') {
            CDataToolsEvent::DiscontGoodsProps($arFields);
        }
        //END

        /*
        // выставим скидки на товар из 1С из разницы в ценах
        // START
        $CHEXBOX_DISCONT = COption::GetOptionString('data.tools1c' , "CHEXBOX_DISCONT_PRICE");
        if($CHEXBOX_DISCONT == 'Y') {
             CDataToolsEvent::DiscontGoodsPrice($arFields);
        }
        //END
        */
        $ELEMENT_AVAILABLE = COption::GetOptionString('data.tools1c', "ELEMENT_AVAILABLE");
        if ($ELEMENT_AVAILABLE == 'Y') {
            CDataToolsEvent::ElementAvailable($arFields);
        }

        $deactivation = COption::GetOptionString('data.tools1c', "DEACTIVATION");
        if($deactivation == 'Y'){
            CDataToolsEvent::DeactivationOnProperty($arFields);
        }


        //обновим фасетный индекс, в множественности использовалась функция CIBlockElement::SetPropertyValuesEx в ней нет фасеты
        $UPDATE_FASET_FILLTER = COption::GetOptionString('data.tools1c' , "UPDATE_FASET_FILLTER");
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && $UPDATE_FASET_FILLTER == 'Y') {
            if($CHEXBOX_MULTIPROPER == 'Y') {
                \Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex($arFields['IBLOCK_ID'], $arFields['ID']);
            }
        }



    }


    function OnBeforeIBlockElementAdd(&$arFields=array()) {

        // не заносить поля при выгрузке из 1С
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) {

            $SELECT_NONE_ADD = unserialize(COption::GetOptionString('data.tools1c' , "SELECT_NONE_ADD"));
            if(empty($SELECT_NONE_ADD)){
                $SELECT_NONE_ADD = array();
            }
            if(in_array('PREVIEW_TEXT',$SELECT_NONE_ADD)) {
                $SELECT_NONE_ADD[] = 'PREVIEW_TEXT_TYPE';
            }
            if(in_array('DETAIL_TEXT',$SELECT_NONE_ADD)) {
                $SELECT_NONE_ADD[] = 'DETAIL_TEXT_TYPE';
            }

            if(count($SELECT_NONE_ADD)>0){
                foreach($SELECT_NONE_ADD as $selVal) {
                    $selVal = trim($selVal);
                    unset($arFields[$selVal]);
                }
            }

        }

    }


    function OnBeforeIBlockElementUpdate(&$arFields=array()) {

        // не обновлять элементы при выгрузке из 1С

        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) {

            $SELECT_NONE_UPDATE = unserialize(COption::GetOptionString('data.tools1c' , "SELECT_NONE_UPDATE"));
            if(empty($SELECT_NONE_UPDATE)){
                $SELECT_NONE_UPDATE = array();
            }
            if(in_array('PREVIEW_TEXT',$SELECT_NONE_UPDATE)) {
                $SELECT_NONE_UPDATE[] = 'PREVIEW_TEXT_TYPE';
            }
            if(in_array('DETAIL_TEXT',$SELECT_NONE_UPDATE)) {
                $SELECT_NONE_UPDATE[] = 'DETAIL_TEXT_TYPE';
            }

            if(count($SELECT_NONE_UPDATE)>0){
                foreach($SELECT_NONE_UPDATE as $selVal) {
                    $selVal = trim($selVal);
                    unset($arFields[$selVal]);
                }
            }
        }
    }




    function OnBeforeProductAdd(&$arFields=array()) {
        CDataToolsEvent::ProductChangeInfo($arFields);

    }

    function OnBeforeProductUpdate($ID=false, &$arFields=array()){
        $arFields['ID'] = $ID;
        CDataToolsEvent::ProductChangeInfo($arFields);
    }



    function OnPriceAdd($ID=false, $arFields=array()){


        //если новый обмен то соберем цены для скидок по разнице цен
        $CHEXBOX_DISCONT = COption::GetOptionString('data.tools1c' , "CHEXBOX_DISCONT_PRICE");
        if($CHEXBOX_DISCONT == 'Y' && $_GET['type'] == 'catalog' && $_GET['mode'] == 'import'/* && strstr($_GET['filename'], 'prices')*/) {

            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['PRICES'][$arFields['PRODUCT_ID']]['ID'] = $arFields['PRODUCT_ID'];
            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['PRICES'][$arFields['PRODUCT_ID']]['IBLOCK_ID'] =  $_SESSION['BX_CML2_IMPORT']['NS']['IBLOCK_ID'];

            //сформируем цену
            $price = array();
            $price['PRICE']['ID'] = $arFields['CATALOG_GROUP_ID'];
            $price['PRICE']['CURRENCY'] = $arFields['CURRENCY'];
            $price[GetMessage(self::MODULE_ID.'_PricePerUnit')] = $arFields['PRICE'];
            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['PRICES'][$arFields['PRODUCT_ID']]['PRICES'][] = $price;

        }


    }
    function OnPriceUpdate($ID=false, $arFields=array()){


        //если новый обмен то соберем цены для скидок по разнице цен
        $CHEXBOX_DISCONT = COption::GetOptionString('data.tools1c' , "CHEXBOX_DISCONT_PRICE");
        if($CHEXBOX_DISCONT == 'Y' && $_GET['type'] == 'catalog' && $_GET['mode'] == 'import'/* && strstr($_GET['filename'], 'prices')*/) {

            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['PRICES'][$arFields['PRODUCT_ID']]['ID'] = $arFields['PRODUCT_ID'];
            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['PRICES'][$arFields['PRODUCT_ID']]['IBLOCK_ID'] =  $_SESSION['BX_CML2_IMPORT']['NS']['IBLOCK_ID'];


            //сформируем цену
            $price = array();
            $price['PRICE']['ID'] = $arFields['CATALOG_GROUP_ID'];
            $price['PRICE']['CURRENCY'] = $arFields['CURRENCY'];
            $price[GetMessage(self::MODULE_ID.'_PricePerUnit')] = $arFields['PRICE'];
            $_SESSION['BX_CML2_IMPORT']['TOOLS1C']['PRICES'][$arFields['PRODUCT_ID']]['PRICES'][] = $price;

        }


    }


    //события по работе с заказами
    //игнорировать отмену при обмене с 1С если заказ уже был отменен в битриксе
    //START
    function OnSaleBeforeCancelOrder($ID=false, $val=false){
        $NOT_CHANGE_CANCEL_ORDER = COption::GetOptionString('data.tools1c' , "NOT_CHANGE_CANCEL_ORDER");
        if($_GET['type'] == 'sale' && $NOT_CHANGE_CANCEL_ORDER == 'Y'){
            $db_sales = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), array("ID" => $ID), false, false, array('CANCELED'));
            while ($ar_sales = $db_sales->Fetch())
            {
                if($ar_sales['CANCELED'] == 'Y') {
                    return false;
                }
            }
        }
    }
    //END


    //START
    //свойства
    function OnBeforeIBlockPropertyAdd(&$arFields=array()) {

        $CHEXBOX_IBLOCK_UNIQUE = COption::GetOptionString('data.tools1c' , "CHEXBOX_IBLOCK_UNIQUE");
        if($CHEXBOX_IBLOCK_UNIQUE == 'Y') {
            $code = CDataToolsEvent::GetUniqueProp($arFields['CODE']);


            $arFields['CODE'] = $code;
        }

    }
    //END

    //событие после отгрузки товаров
    function OnSuccessCatalogImport1C() {
        if($_SESSION['BX_CML2_IMPORT']['TOOLS1C']['PRICES']){
            foreach($_SESSION['BX_CML2_IMPORT']['TOOLS1C']['PRICES'] as $key=>$element){
                CDataToolsEvent::DiscontGoodsPrice($element);
            }
        }

        unset($_SESSION['BX_CML2_IMPORT']['TOOLS1C']);
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag(self::MODULE_ID);

    }

    // при удалении свойств
    //START
    function OnIBlockPropertyDelete($ID) {
        CDataToolsProperty::DeleteByPropertyId($ID);
        CDataToolsIblock::DeleteByForPropertyId($ID);
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag(self::MODULE_ID);
    }
    //END

    //При изменение свойств
    //START
    function OnBeforeIBlockPropertyUpdate(&$arFields=array()) {

        //Для запрета изменения типа свойства пользователями  если свойства от модулями
        $props1ctools = CDataToolsProperty::GetByPropertyId($arFields['ID']);
        if(strstr($props1ctools['PROPERTY_TYPE'], ':')) {
            $arr_props1ctools = explode(':' , $props1ctools['PROPERTY_TYPE']);


            if($arr_props1ctools[1] == 'directory'){
                $arFields["USER_TYPE"] = 'directory';
                if($props1ctools['PROPERTY_HIGHLOAD_TABLE']!=$arFields["USER_TYPE_SETTINGS"]["TABLE_NAME"]) {
                    // получим справочник
                    // START
                    if(CModule::IncludeModule("highloadblock")){
                        $allHighLoadiblock = array();
                        $allHighLoadiblockTable = array();
                        $resHighLoadiblock = Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('TABLE_NAME'=>$arFields["USER_TYPE_SETTINGS"]["TABLE_NAME"])));
                        while($ar_resHighLoadiblock = $resHighLoadiblock->fetch())
                        {
                            $IblockHighloadNew = $ar_resHighLoadiblock['ID'];
                        }
                    }
                    // END

                    // получим таблицу с инфоблоком
                    // START
                    $dbiblo = CDataToolsIblock::GetList(array(), array('FOR_PROPERTY_ID'=>$arFields['ID']));
                    $resiblo = $dbiblo->NavNext();
                    // END

                    // START
                    CDataToolsIblock::Update($resiblo['ID'],array('IBLOCK_ID'=>$IblockHighloadNew));
                    CDataToolsProperty::Update($props1ctools['ID'],array('PROPERTY_HIGHLOAD_TABLE'=>$arFields["USER_TYPE_SETTINGS"]["TABLE_NAME"]));
                    // END
                }

            }

            $props1ctools['PROPERTY_TYPE'] = $arr_props1ctools[0];
        }





        if($props1ctools && $arFields['PROPERTY_TYPE']) {
            //если изменили свойства
            if($props1ctools['PROPERTY_TYPE'] != $arFields['PROPERTY_TYPE']){
                global $APPLICATION;
                $APPLICATION->throwException(GetMessage(self::MODULE_ID.'_ERROR_PROPER'), 'DATAIT_TOOLS1_ERROR_PROPER');
                $arFields['PROPERTY_TYPE'] = $props1ctools['PROPERTY_TYPE'];

                return false;
            }
        }
        return $arFields;

    }
    //END


    //при удалеии инфоблока
    //START
    function OnIBlockDelete($ID=false) {
        CDataToolsIblock::DeleteByIblockId($ID);
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag(self::MODULE_ID);
    }
    //END


    //при удаление скидки
    //START
    function OnDiscountDelete($ID=false) {
        CDataToolsDiscount::DeleteByDiscontId($ID);
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag(self::MODULE_ID);
    }
    //END

    //при изменение скидки запретим изменять тип и значение скидки
    //START
    function OnBeforeDiscountUpdate($ID=false, &$arFields=array()) {

        if($_GET['type'] != 'catalog' && $_GET['mode'] != 'import') {

            $arrToolsDiscont = CDataToolsDiscount::GetByDiscontId($ID);
            if($arrToolsDiscont){

                if($arFields['VALUE_TYPE'] != $arrToolsDiscont['TYPE'] || $arFields['VALUE'] != $arrToolsDiscont["DISCONT_VALUE"]){
                    global $APPLICATION;
                    $APPLICATION->throwException(GetMessage(self::MODULE_ID.'_ERROR_DISCONT'), 'DATAIT_TOOLS1_ERROR_DISCONT');
                    return false;
                }
                $arFields['VALUE_TYPE'] = $arrToolsDiscont['TYPE'];
                $arFields['VALUE'] = $arrToolsDiscont["DISCONT_VALUE"];


            }

            global $CACHE_MANAGER;
            $CACHE_MANAGER->ClearByTag(self::MODULE_ID);

        }



        return $arFields;
    }
    //END

    //функция-обработчик события, вызываемого в случае успешного изменения параметров скидки
    function OnDiscountUpdate($ID, $arFields){}


    function OnBeforeIBlockSectionAdd(&$arFields){
        // не заносить поля при выгрузке из 1С
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) {

            $SELECT_NONE_ADD_FOR_SECTION = unserialize(COption::GetOptionString('data.tools1c' , "SELECT_NONE_ADD_FOR_SECTION"));
            if(empty($SELECT_NONE_ADD_FOR_SECTION)){
                $SELECT_NONE_ADD_FOR_SECTION = array();
            }
            if(in_array('DESCRIPTION',$SELECT_NONE_ADD_FOR_SECTION)) {
                $SELECT_NONE_ADD_FOR_SECTION[] = 'DESCRIPTION_TYPE';
            }

            if(count($SELECT_NONE_ADD_FOR_SECTION)>0){
                foreach($SELECT_NONE_ADD_FOR_SECTION as $selVal) {
                    $selVal = trim($selVal);
                    unset($arFields[$selVal]);
                }
            }

        }
    }
    function OnBeforeIBlockSectionUpdate(&$arFields){
        // не обновлять элементы при выгрузке из 1С
        if($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'import')) {

            $SELECT_NONE_UPDATE_FOR_SECTION = unserialize(COption::GetOptionString('data.tools1c' , "SELECT_NONE_UPDATE_FOR_SECTION"));
            if(empty($SELECT_NONE_UPDATE_FOR_SECTION)){
                $SELECT_NONE_UPDATE_FOR_SECTION = array();
            }
            if(in_array('NAME',$SELECT_NONE_UPDATE_FOR_SECTION)) {
                $SELECT_NONE_UPDATE_FOR_SECTION[] = 'SEARCHABLE_CONTENT';
            }
            if(in_array('DESCRIPTION',$SELECT_NONE_UPDATE_FOR_SECTION)) {
                $SELECT_NONE_UPDATE_FOR_SECTION[] = 'DESCRIPTION_TYPE';
            }
            if(count($SELECT_NONE_UPDATE_FOR_SECTION)>0){
                foreach($SELECT_NONE_UPDATE_FOR_SECTION as $selVal) {
                    $selVal = trim($selVal);
                    unset($arFields[$selVal]);
                }
            }
        }
    }
    function notDuplicateDocumentsForOrder($event)
    {
        /** @var Order $order */
        if ($_GET['type'] == 'sale' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'Payment')) {
            $NOT_DUPLICATE_PAYMENT = COption::GetOptionString('data.tools1c', "NOT_DUPLICATE_PAYMENT");
            if ($NOT_DUPLICATE_PAYMENT == 'Y') {
                if(method_exists($event, 'getParameter')){
                    $order = $event->getParameter("ENTITY");
                }else{
                    $order = $event;
                }
                $paymentCollection = $order->getPaymentCollection();
                if (count($paymentCollection) > 1) {
                    $summa = 0;
                    foreach ($paymentCollection as $payment) {
                        if ($payment->isPaid()) $summa += $payment->getSum();
                        foreach ($paymentCollection as $payments) {
                            if (
                                $payment->getPaymentSystemId() == $payments->getPaymentSystemId()
                                && $payment->getSum() == $payments->getSum()
                                && $payment->getPaymentSystemName() == $payments->getPaymentSystemName()
                                && $payment->getId() != $payments->getId()
                                && !$payment->isPaid()
                            ) {
                                $pay = $paymentCollection->getItemById($payment->getId());
                                $delResult = $pay->delete();
                                if ($delResult->isSuccess()) {
                                    $order->save();
                                }
                            }
                        }
                    }
                    if ($summa == $order->getPrice()) {
                        global $DB;
                        $sql = "DELETE FROM `b_sale_order_payment` WHERE `PAID`='N' AND `SUM`='" . $order->getPrice() . "' AND `ORDER_ID`='" . $order->getId() . "'";
                        $DB->Query($sql);
                    }
                }
            }
        }
        if ($_GET['type'] == 'sale' && $_GET['mode'] == 'import' && strstr($_GET['filename'], 'Shipping')) {
            $NOT_DUPLICATE_SHIPMENT = COption::GetOptionString('data.tools1c' , "NOT_DUPLICATE_SHIPMENT");
            if($NOT_DUPLICATE_SHIPMENT == 'Y') {
                $shipmentCollection = $order->getShipmentCollection();
                if (count($shipmentCollection) > 2) {
                    $deactivate = false;
                    $shipment_deactivate = array();
                    foreach ($shipmentCollection as $ship) {
                        $deducted = $ship->getFields()->getValues();
                        $deducted = $deducted['DEDUCTED'];
                        $system = $ship->getFields()->getValues();
                        $system = $system['SYSTEM'];
                        if (!$ship->isAllowDelivery() && $deducted == 'N' && $system == 'N') $shipment_deactivate[] = $ship->getFields();
                        if ($ship->isAllowDelivery() && $deducted == 'Y') $deactivate = true;
                    }
                    if ($deactivate) {
                        foreach ($shipmentCollection as $ship) {
                            $ship->delete();
                            $order->save();
                        }
                    }

                }
            }
        }
    }
    function KartyLoyalnostiOnBeforeUpdate($event)
    {
        $fields = $event->getParameters();
        $fields = $fields['fields'];
        $addGroup = COption::GetOptionString('data.tools1c', "ALLOW_CARD_ADD_GROUP_11_2");
        $addUserForGroup = COption::GetOptionString('data.tools1c', "ALLOW_CARD_ADD_ON_USER_11_2");
        $active = COption::GetOptionString('data.tools1c', "FIELDS_ACTIVE_CARD_11_2");
        $vladelec = COption::GetOptionString('data.tools1c', "FIELDS_VLADELETS_CARD_11_2");
        $ev = COption::GetOptionString(self::MODULE_ID, "EVENTS");
        if ($fields[$active] != 1 && $addGroup == "Y" && $ev == 'N') {
            $rsGroups = CGroup::GetList($by = "c_sort", $order = "asc", Array());
            $fields[$vladelec] = trim($fields[$vladelec]);
            $isIssetGroup = false;
            while ($groups = $rsGroups->Fetch()) {
                if (in_array($fields[$vladelec], $groups)) {
                    $isIssetGroup = true;
                    $GROUP_ID = $groups['ID'];
                }
            }
            if (!$isIssetGroup) {
                $arParams = array("replace_space" => "-", "replace_other" => "-");
                $trans = Cutil::translit($fields[$vladelec], "ru", $arParams);
                $group = new CGroup;
                $arFields = Array(
                    "ACTIVE" => "Y",
                    "C_SORT" => 100,
                    "NAME" => $fields[$vladelec],
                    "DESCRIPTION" => "",
                    "USER_ID" => array(),
                    "STRING_ID" => $trans
                );
                $GROUP_ID = $group->Add($arFields);
            }
            $OnUser = COption::GetOptionString('data.tools1c', "FIELDS_PARTNER_CARD_11_2");
            if ($fields[$OnUser] != '' && $addUserForGroup == 'Y') {
                $user = explode(" ", trim($fields[$OnUser]));
                $filter = Array
                (
                    "ACTIVE" => "Y",
                    "NAME" => isset($user['1']) ? $user['1'] : '',
                    "LAST_NAME" => isset($user['0']) ? $user['0'] : '',
                    "SECOND_NAME" => isset($user['2']) ? $user['2'] : ''
                );
                $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $filter);
                $user_id = 0;
                while ($arItem = $rsUsers->GetNext()) {
                    $user_id = $arItem['ID'];
                }
                if ($user_id != 0) {
                    CUser::SetUserGroup($user_id, array(5, $GROUP_ID));
                }
            }
            $import_code_user = COption::GetOptionString('data.tools1c', "IMPORT_DISCOUNT_CODE_ON_USER");
            if ($import_code_user == 'Y') {
                $code = COption::GetOptionString('data.tools1c', "ADD_USER_GROUP_ON_REG_CODE");
                $add_null_card = COption::GetOptionString('data.tools1c', "ADD_USER_GROUP_ON_REG");
                if($add_null_card == 'Y'){
                    $user_null_card = COption::GetOptionString('data.tools1c', "ADD_USER_GROUP_ON_REG_DISCOUNT");
                    $user_null_card = str_replace("%", "", $user_null_card);
                    if(preg_match('/(.*?)'.trim($fields[$OnUser]).'(.*?)/',$user_null_card)){
                        return false;
                    }
                }
                $user = explode(" ", trim($fields[$OnUser]));
                $filter = Array
                (
                    "ACTIVE" => "Y",
                    "NAME" => isset($user['1']) ? $user['1'] : '',
                    "LAST_NAME" => isset($user['0']) ? $user['0'] : '',
                    "SECOND_NAME" => isset($user['2']) ? $user['2'] : '',
                );
                $select = array('UF_*', 'ID');
                $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $filter, $select);
                $user_id = 0;
                $user_code = '';
                $create_field_code =false;
                while ($arItem = $rsUsers->GetNext()) {
                    $user_id = $arItem['ID'];
                    $us_code = CUser::GetByID($arItem['ID'])->fetch();
                    if(isset($us_code['UF_CODE'])){
                        $user_code = $us_code['UF_CODE'];
                    }else{
                        $create_field_code = true;
                    }
                }
                if($create_field_code){
                    $fields_add = Array(
                        "ENTITY_ID" => "USER",
                        "FIELD_NAME" => "UF_CODE",
                        "USER_TYPE_ID" => "string",
                        "EDIT_FORM_LABEL" => Array("ru"=>"code", "en"=>"code")
                    );
                    $obUserField  = new CUserTypeEntity;
                    $obUserField->Add($fields_add);
                }
                if ($user_id != 0 && $user_code == '') {
                    $user_update = new CUser();
                    $fields = Array(
                        'MANDATORY' => 'Y',
                        "UF_CODE" => $fields[$code]
                    );
                    $user_update->update($user_id, $fields);
                }

            }

        }
    }

    function addDiscountOn1c($event)
    {
        $active = COption::GetOptionString('data.tools1c', "ALLOW_DISCOUNT_11_2");
        $active_item = COption::GetOptionString('data.tools1c', "FIELDS_ACTIVE_DISCOUNT_CARD_11_2");
        $ev = COption::GetOptionString(self::MODULE_ID, "EVENTS");
        $fields = $event->getParameters();
        $fields = $fields['fields'];
        if ($active == 'Y' && $ev == 'N' && $fields[$active_item] != 1) {
            $rsSites = CSite::GetList($by = "sort", $order = "desc", Array('DEFAULT' => 'Y'));
            if (!$Site = $rsSites->Fetch()) {
                return;
            }

            $roditel_skidki = COption::GetOptionString('data.tools1c', "FIELDS_GROUP_DISCOUNT_CARD_11_2");
            $roditel_usloviya = COption::GetOptionString('data.tools1c', "FIELDS_GROUP_CONDITION_DISCOUNT_CARD_11_2");
            $name_skidki = COption::GetOptionString('data.tools1c', "FIELDS_NAME_DISCOUNT_CARD_11_2");
            $value_skidki = COption::GetOptionString('data.tools1c', "FIELDS_VALUE_DISCOUNT_CARD_11_2");
            $condition_hl = COption::GetOptionString('data.tools1c', "CONDITION_DISCOUNT_CARD_11_2");
            $skidki_hl = COption::GetOptionString('data.tools1c', "DISCOUNT_CARD_11_2");
            $name_condition = COption::GetOptionString('data.tools1c', "FIELDS_NAME_CONDITION_DISCOUNT_CARD_11_2");
            $cur = COption::GetOptionString('data.tools1c', "FIELDS_CURRENCY_CONDITION_DISCOUNT_CARD_11_2");
            $value_cond = COption::GetOptionString('data.tools1c', "FIELDS_VALUE_CONDITION_DISCOUNT_CARD_11_2");
            $fields[$name_skidki] = trim($fields[$name_skidki]);
            $class = $event->getEntity()->getDataClass();
            $table_name = 'tbl_' . $event->getEntity()->GetDBTableName();
            if($condition_hl != $skidki_hl) {

                $arFilter = array($roditel_skidki => $fields[$roditel_skidki]);
                $nacenki = $class::getList(array(
                    "select" => array($name_skidki, $value_skidki),
                    "filter" => $arFilter,
                ));
                $data = new CDBResult($nacenki, $table_name);

                $skidkiinacenki = array();
                while ($item = $data->fetch()) {
                    $skidkiinacenki[] = $item;
                }
                if (count($skidkiinacenki) == 0) return;
                unset($rsSites);
                unset($data);
                unset($nacenki);
                $entity = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
                    "filter" => array(
                        'NAME' => $condition_hl
                    )));
                $id_hl = 0;
                while ($hl = $entity->fetch()) {
                    $id_hl = $hl['ID'];
                }
                unset($entity);

                if ($id_hl != 0) {
                    $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($id_hl)->fetch();
                    $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                    $entity_data_class = $entity->getDataClass();
                    $entity_table_name = $hlblock['TABLE_NAME'];
                    $sTableID = 'tbl_' . $entity_table_name;

                    $arFilter = array($roditel_usloviya => $fields[$roditel_skidki]);
                    $items = $entity_data_class::getList(array(
                        "select" => array('*'),
                        "filter" => $arFilter,
                    ));
                    $data = new CDBResult($items, $sTableID);

                    while ($item = $data->fetch()) {
                        $currency = $item[$cur];
                        foreach ($skidkiinacenki as $skidka) {
                            if ($item[$name_condition] == $skidka[$name_skidki]) {
                                echo $item[$name_condition] .' == '. $skidka[$name_skidki].'<br>';
                                if ($item[$value_cond] > 0 && $skidka[$value_skidki] > 0) {
                                    $discounts[] = array(
                                        'RANGE_FROM' => preg_replace('/[^0-9.,]+/', '', $item[$value_cond]),
                                        'VALUE' => preg_replace('/[^0-9.,]+/', '', $skidka[$value_skidki]),
                                        'TYPE' => 'P'
                                    );
                                }
                            }
                        }
                    }
                    unset($data);
                    unset($item);
                    unset($items);
                    unset($skidkiinacenki);
                } else {
                    return;
                }
                $GROUP_ID = array();
                $rsGroups = CGroup::GetList($by = "c_sort", $order = "asc", Array());
                while ($groups = $rsGroups->Fetch()) {
                    if (in_array($fields[$roditel_skidki], $groups)) {
                        $GROUP_ID[] = $groups['ID'];
                    }
                }
                if(count($GROUP_ID) == 0) return;
                $isTrue = false;
                foreach ($discounts as $disc) {
                    if ($disc['VALUE'] == $fields[$value_skidki]) {
                        $isTrue = true;
                    }
                }
                if (!$isTrue) {
                    $arFilter = array($name_condition => $fields[$name_skidki]);
                    $items = $entity_data_class::getList(array(
                        "select" => array('*'),
                        "filter" => $arFilter,
                    ));
                    $data = new CDBResult($items, $sTableID);
                    if ($res = $data->fetch()) {
                        if ($fields[$value_skidki] > 0 && $res[$value_cond] > 0)
                            $discounts[count($discounts)] = array(
                                'RANGE_FROM' => preg_replace('/[^0-9.,]/', '', $res[$value_cond]),
                                'VALUE' => $fields[$value_skidki],
                                'TYPE' => 'P'
                            );
                    }
                }
                
            }else{
               $check_groups = COption::GetOptionString('data.tools1c', "NO_GROUPS");
                $discounts = array();
                if($check_groups == 'Y'){

                    $id = CCatalogDiscountSave::GetList($arOrder = array(), $arFilter = array('NAME'=>$fields[$name_skidki]), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array());
                    if($id_discount = $id->fetch())
                    {
                        $data = CCatalogDiscountSave::GetRangeByDiscount($arOrder = array(), $arFilter = array('DISCOUNT_ID'=>$id_discount['ID']), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array());
                        while ($condition = $data->fetch()) {
                            if($fields[$value_cond] != $condition['RANGE_FROM'] && $fields[$value_skidki] != $condition['VALUE'] && $fields[$value_cond] != '' && $fields[$value_skidki] != ''){
                                $discounts[] = array(
                                    'RANGE_FROM' => $condition['RANGE_FROM'],
                                    'VALUE' => $condition['VALUE'],
                                    'TYPE' => $condition['TYPE']
                                );
                            }
                        }
                    }
                }

                if(count($discounts) > 0)
                $discounts = array_map("unserialize", array_unique(array_map("serialize", $discounts)));
                $currency = $fields[$cur];
                if ($fields[$value_cond] > 0 && $fields[$value_skidki] > 0 && $fields[$value_cond] != '' && $fields[$value_skidki] != '') {
                    $discounts[] = array(
                        'RANGE_FROM' => preg_replace('/[^0-9.,]+/', '', $fields[$value_cond]),
                        'VALUE' => preg_replace('/[^0-9.,]+/', '', $fields[$value_skidki]),
                        'TYPE' => 'P'
                    );
                }

                $GROUP_ID = array();

                $rsGroups = CGroup::GetList($by = "c_sort", $order = "asc", Array());
                while ($groups = $rsGroups->Fetch()) {
                    if (in_array($fields[$roditel_skidki], $groups)) {
                        $GROUP_ID[] = $groups['ID'];
                    }
                }
            }


            if (count($discounts) > 0) {
                $el = array(
                    'SITE_ID' => $Site['ID'],
                    'TYPE' => '1',
                    'NAME' => $fields[$roditel_skidki],
                    'ACTIVE' => $fields[$active_item] == '0' ? 'Y' : 'N',
                    'SORT' => '500',
                    'CURRENCY' => isset($currency) && strlen($currency) > 0 ? $currency : 'RUB',
                    'ACTIVE_FROM' => NULL,
                    'ACTIVE_TO' => NULL,
                    'COUNT_PERIOD' => 'U',
                    'COUNT_SIZE' => '0',
                    'COUNT_TYPE' => 'Y',
                    'COUNT_FROM' => NULL,
                    'COUNT_TO' => NULL,
                    'ACTION_SIZE' => '0',
                    'ACTION_TYPE' => 'Y',
                    'GROUP_IDS' => count($GROUP_ID) == 0 ? array(5) : $GROUP_ID,
                    'RANGES' => $discounts
                );
                $obDiscSave = new CCatalogDiscountSave();
                $dbProductDiscounts = CCatalogDiscountSave::GetList($arOrder = array(), $arFilter = array("NAME" => $fields[$roditel_skidki]), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array("*"));
                if (!$arProductDiscounts = $dbProductDiscounts->Fetch()) {
                    $obDiscSave->Add($el);
                } else {
                    $obDiscSave->Update($arProductDiscounts['ID'], $el);
                }
            }
        }
    }

    //START
    static function OffersAttributesProperListForProduct(&$arFields = array(), $type = false)
    {
        if ($_GET['type'] == 'catalog' && $_GET['mode'] == 'import' && strstr($_GET['filename'], $type)) {
            $add = COption::GetOptionString('data.tools1c', "ATTRIBUTES_FOR_PRODUCT");
            if ($add == 'Y') {
                $product = CCatalogSku::GetProductInfo($arFields['ID']);
                if ($product) {
                    $db_props_offer = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], array("sort" => "asc"), Array("CODE" => "CML2_ATTRIBUTES"));
                    $db_props_product = CIBlockElement::GetProperty($product['IBLOCK_ID'], $product['ID'], array("sort" => "asc"), Array("CODE" => "CML2_ATTRIBUTES"));
                    if (!$ar_props_product = $db_props_product->Fetch()) {
                        $fields = Array(
                            "NAME" => GetMessage("data.tools1c" . '_CML2_ATTRIBUTES'),
                            "ACTIVE" => "Y",
                            "SORT" => "100",
                            "CODE" => "CML2_ATTRIBUTES",
                            "PROPERTY_TYPE" => "S",
                            "IBLOCK_ID" => $product['IBLOCK_ID'],
                            "SEARCHABLE" => "N",
                            "LIST_TYPE" => "L",
                            "FILTRABLE" => "N",
                            "MULTIPLE" => "Y",
                            'WITH_DESCRIPTION' => 'Y'
                        );
                        $ibp = new CIBlockProperty;
                        $ibp->Add($fields);
                    }
                    $ar_prop = array();
                    while ($ar_props_prod = $db_props_product->Fetch()) {
                        $ar_prop[] = Array("VALUE" => $ar_props_prod['VALUE'], 'DESCRIPTION' => $ar_props_prod['DESCRIPTION']);
                    }

                    while ($ar_props_offer = $db_props_offer->Fetch()) {
                        $ar_prop[] = Array("VALUE" => $ar_props_offer['VALUE'], 'DESCRIPTION' => $ar_props_offer['DESCRIPTION']);
                    }
                    $ar_prop = array_map("unserialize", array_unique(array_map("serialize", $ar_prop)));
                    $el = new CIBlockElement();
                    $el->SetPropertyValuesEx($product['ID'], "CML2_ATTRIBUTES", $ar_prop);
                    unset($ar_prop);
                }
            }
        }
    }
    static function eventHl()
    {
//        if($_GET['type'] == 'reference' && $_GET['mode'] == 'import'){
        $ev = COption::GetOptionString(self::MODULE_ID, "EVENTS");
        if ($ev == 'N') {
            $hl = COption::GetOptionString(self::MODULE_ID, "HL_FOR_DISCOUNT_CARD_11_2");
            $hl_skidki = COption::GetOptionString(self::MODULE_ID, "DISCOUNT_CARD_11_2");
            $hl_update = $hl . 'OnUpdate';
            $hl_skidki_update = $hl_skidki . 'OnUpdate';
            $hl_add = $hl . 'OnAdd';
            $hl_skidki_add = $hl_skidki . 'OnAdd';
            global $DB;
            $sql = "SELECT `MESSAGE_ID` FROM `b_module_to_module` WHERE `TO_MODULE_ID`='data.tools1c'";
            $check = $DB->Query($sql);
            $arr_event_module = array();
            while ($event_module = $check->fetch()) {
                $arr_event_module[] = $event_module['MESSAGE_ID'];
            }
            $eventManager = \Bitrix\Main\EventManager::getInstance();
            if (!in_array($hl_update, $arr_event_module) && $hl != '') {
                $eventManager->registerEventHandler("", $hl_update, self::MODULE_ID, "CDataToolsEvent", "KartyLoyalnostiOnBeforeUpdate");
                EventsTable::add(array('EVENTS'=>$hl_update, 'FUNCTIONS'=>'KartyLoyalnostiOnBeforeUpdate'));
            }
            if (!in_array($hl_skidki_update, $arr_event_module) && $hl_skidki != '') {
                $eventManager->registerEventHandler("", $hl_skidki_update, self::MODULE_ID, "CDataToolsEvent", "addDiscountOn1c");
                EventsTable::add(array('EVENTS'=>$hl_skidki_update, 'FUNCTIONS'=>'addDiscountOn1c'));
            }
            if (!in_array($hl_add, $arr_event_module) && $hl != '') {
                $eventManager->registerEventHandler("", $hl_add, self::MODULE_ID, "CDataToolsEvent", "KartyLoyalnostiOnBeforeUpdate");
                EventsTable::add(array('EVENTS'=>$hl_add, 'FUNCTIONS'=>'KartyLoyalnostiOnBeforeUpdate'));
            }
            if (!in_array($hl_skidki_add, $arr_event_module) && $hl_skidki != '') {
                $eventManager->registerEventHandler("", $hl_skidki_add, self::MODULE_ID, "CDataToolsEvent", "addDiscountOn1c");
                EventsTable::add(array('EVENTS'=>$hl_skidki_add, 'FUNCTIONS'=>'addDiscountOn1c'));
            }
            $check = EventsTable::getList(array(), array(), false, array(), array());
            $delete_event_module = array();
            $delete_id = array();
            while ($event_module = $check->fetch()) {
                if ($event_module['EVENTS'] != $hl_update
                    && $event_module['EVENTS'] != $hl_add
                    && $event_module['EVENTS'] != $hl_skidki_update
                    && $event_module['EVENTS'] != $hl_skidki_add
                ) {
                    $delete_event_module[$event_module['EVENTS']] = $event_module['FUNCTIONS'];
                    $delete_id[] = $event_module['ID'];
                }
            }
            foreach ($delete_event_module as $event => $function) {
                $eventManager->unRegisterEventHandler("", $event, 'data.tools1c', "CDataToolsEvent", $function);
            }
            foreach ($delete_id as $id) {
                EventsTable::delete($id);
            }
        }else{
            $hl_cond = COption::GetOptionString(self::MODULE_ID, "CLEAR_HL_DISC_COND");
            $hl_skidki = COption::GetOptionString(self::MODULE_ID, "CLEAR_HL_DISC_SKIDKI");
            $hl = COption::GetOptionString(self::MODULE_ID, "CLEAR_HL_DISC");
            $hl_name = COption::GetOptionString(self::MODULE_ID, "HL_FOR_DISCOUNT_CARD_11_2");
            $hl_skidki_name = COption::GetOptionString(self::MODULE_ID, "DISCOUNT_CARD_11_2");
            $hl_cond_name = COption::GetOptionString(self::MODULE_ID, "CONDITION_DISCOUNT_CARD_11_2");
            if($hl == 'Y' && $hl_name != ''){
                $entity = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
                    "filter" => array(
                        'NAME' => $hl_name
                    )));
                if($hl_id = $entity->fetch()) {
                    $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hl_id['ID'])->fetch();
                    $ent = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                    $entity_data_class = $ent->getDataClass();
                    $entity_table_name = $hlblock['TABLE_NAME'];
                    $sTableID = 'tbl_'.$entity_table_name;
                    $rsData = $entity_data_class::getList(array(
                        "select" => array('ID'),
                    ));
                    $rsData = new CDBResult($rsData, $sTableID);
                    while($arRes = $rsData->Fetch()){
                        $entity_data_class::delete($arRes['ID']);
                    }
                }
            }
            if($hl_skidki == 'Y' && $hl_skidki_name != ''){
                $entity = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
                    "filter" => array(
                        'NAME' => $hl_skidki_name
                    )));
                if($hl_id = $entity->fetch()) {
                    $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hl_id['ID'])->fetch();
                    $ent = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                    $entity_data_class = $ent->getDataClass();
                    $entity_table_name = $hlblock['TABLE_NAME'];
                    $sTableID = 'tbl_'.$entity_table_name;
                    $rsData = $entity_data_class::getList(array(
                        "select" => array('ID'),
                    ));
                    $rsData = new CDBResult($rsData, $sTableID);
                    while($arRes = $rsData->Fetch()){
                        $entity_data_class::delete($arRes['ID']);
                    }
                }
            }
            if($hl_cond == 'Y' && $hl_cond_name != ''){
                $entity = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
                    "filter" => array(
                        'NAME' => $hl_cond_name
                    )));
                if($hl_id = $entity->fetch()) {
                    $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hl_id['ID'])->fetch();
                    $ent = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
                    $entity_data_class = $ent->getDataClass();
                    $entity_table_name = $hlblock['TABLE_NAME'];
                    $sTableID = 'tbl_'.$entity_table_name;
                    $rsData = $entity_data_class::getList(array(
                        "select" => array('ID'),
                    ));
                    $rsData = new CDBResult($rsData, $sTableID);
                    while($arRes = $rsData->Fetch()){
                        $entity_data_class::delete($arRes['ID']);
                    }
                }
            }
        }
    }

    static function UploadChecks()
    {
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload/1c_tools/checks.xml')) return false;
        $data = self::parse(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/upload/1c_tools/checks.xml'));
        $typeIblock = COption::GetOptionString(self::MODULE_ID, "TYPE_IBLOCK_CHECKS_PRODUCT");
        $nameIblock = COption::GetOptionString(self::MODULE_ID, "IBLOCK_CHECKS_NAME");
        $codeIblock = COption::GetOptionString(self::MODULE_ID, "IBLOCK_CHECKS_CODE");
        $iblock = COption::GetOptionString(self::MODULE_ID, "IBLOCK_PRODUCTS");

        $rsSites = CSite::GetList($by = "sort", $order = "desc", Array('DEFAULT' => 'Y'));
        if (!$Site = $rsSites->Fetch()) {
            return;
        }
        $res = CIBlock::GetList(
            Array(),
            Array(
                'TYPE' => $typeIblock,
                "CODE" =>$codeIblock
            ), true
        );
        $id_block = 0;
        while ($result_id_iblock = $res->Fetch()) {
            $id_block = $result_id_iblock['ID'];
        }
        if ($id_block == 0) {

            $ib = new CIBlock;
            $arFields = Array(
                "ACTIVE" => 'Y',
                "NAME" => $nameIblock, //переименовать
                "CODE" => $codeIblock,
                "IBLOCK_TYPE_ID" => $typeIblock,
                "SITE_ID" => Array($Site['ID']),
                "SORT" => '100',
                'IS_CATALOG' => 'Y',
                "GROUP_ID" => Array("2" => "R", "1" => "X")
            );
            $id_block = $ib->Add($arFields);
            CCatalog::Add(array("IBLOCK_ID" => $id_block));
        }
        $meas = CCatalogMeasureClassifier::getMeasureClassifier();
        global $USER;
        foreach ($data['DOCUMENTS'] as $item) {
            if($item['LOYALTYCARDSPARTNER'] == '') continue;
            $is_isset_check = ChecksTable::getList(array('filter'=>array('CHECK_NAME'=>$item['LINK'], 'CHECK_NUMBER'=>$item['NUMBER'])));
            if($is_isset_check->fetch()) continue;
            $user = explode(" ", trim($item['LOYALTYCARDSPARTNER']));
            $currency = GetMessage(self::MODULE_ID.'_CURRENCY') === $item['CURRENCY'] ? 'RUB' : $item['CURRENCY'];
            $filter = Array
            (
                "ACTIVE" => "Y",
                "NAME" => isset($user['1']) ? $user['1'] : '',
                "LAST_NAME" => isset($user['0']) ? $user['0'] : '',
                "SECOND_NAME" => isset($user['2']) ? $user['2'] : ''
            );
            $user_id = 0;
            $user_email = '';
            $user_phone = '';
            $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $filter);
            while ($arItem = $rsUsers->GetNext()) {
                $user_id = $arItem['ID'];
                $user_email = $arItem['EMAIL'];
                $user_phone = $arItem['PERSONAL_PHONE'];
            }
            if ($user_id == 0) continue;
            $arFields = array(
                "LID" => $Site['ID'],
                "PERSON_TYPE_ID" => 1,
                "PAYED" => "N",
                "CANCELED" => "N",
                "STATUS_ID" => "F",
                "PRICE" => preg_replace('/[^0-9.,]/', '', $item['DOCUMENTAMOUNT']),
                "CURRENCY" => $currency,
                "USER_ID" => $user_id,
                "PAY_SYSTEM_ID" => 1,
                "PRICE_DELIVERY" => 0,
                "DELIVERY_ID" => 1,
                "DISCOUNT_VALUE" => 0,
                "TAX_VALUE" => 0.0,
                "USER_DESCRIPTION" => $item['LINK'],
                "COMMENTS" => $item['LINK']
            );
            CModule::IncludeModule('sale');
            $ORDER_ID = CSaleOrder::Add($arFields);
            foreach ($item["GOODS"] as $product) {
                $arSelect = Array("ID");
                $arFilter = Array("IBLOCK_ID" => $iblock, "NAME" => $product['NOMENCLATURE']);
                $prod = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
                if (!$ob = $prod->GetNextElement()) {
                    $arSelect = Array("ID");
                    $arFilter = Array("IBLOCK_ID" => $id_block, "NAME" => $product['NOMENCLATURE']);
                    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
                    if (!$ob = $res->GetNextElement()) {
                        $el = new CIBlockElement;
                        $arLoadProductArray = Array(
                            "MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
                            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
                            "IBLOCK_ID" => $id_block,
                            "NAME" => $product['NOMENCLATURE'],
                            "ACTIVE" => "Y",            // активен
                            "PREVIEW_TEXT" => "",
                            "DETAIL_TEXT" => "",
                        );
                        $PRODUCT_ID = $el->Add($arLoadProductArray);
                        $code = 796;

                        foreach ($meas[0][3] as $mes) {
                            if ($mes['SYMBOL_RUS'] == $product['UNIT']) {
                                $code = $mes['CODE'];
                            }
                        }
                        $MEASURE = CCatalogMeasure::GetList($arOrder = array(), $arFilter = array('CODE' => $code), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array('ID'));
                        $ID_MEASURE = $MEASURE->fetch();
                        $arFields = array('QUANTITY' => $product['COUNT'], 'MEASURE' => $ID_MEASURE['ID'], "ID" => $PRODUCT_ID);
                        CCatalogProduct::Add($arFields);
                        $arFields = Array(
                            "PRODUCT_ID" => $PRODUCT_ID,
                            "CATALOG_GROUP_ID" => 1,
                            "PRICE" => preg_replace('/[^0-9.,]/', '', $product['PRICE']),
                            "CURRENCY" => $currency
                        );
                        CPrice::Add($arFields);
                        unset($arFields);
                    } else {
                        $PRODUCT_ID = $ob->fields['ID'];
                        $ar_res = CCatalogProduct::GetByID($PRODUCT_ID);
                        CCatalogProduct::Update($PRODUCT_ID, array('QUANTITY' => $ar_res['QUANTITY'] + $product['COUNT']));
                    }
                }else{
                    $PRODUCT_ID = $ob->fields['ID'];
                    $ar_res = CCatalogProduct::GetByID($PRODUCT_ID);
                    CCatalogProduct::Update($PRODUCT_ID, array('QUANTITY' => $ar_res['QUANTITY'] + $product['COUNT']));
                }
                CSaleBasket::Add(
                    array(
                        "LID" => $Site['ID'],
                        "PERSON_TYPE_ID" => 1,
                        "PAYED" => "N",
                        "PRODUCT_ID" => $PRODUCT_ID,
                        'NAME' => $product['NOMENCLATURE'],
                        'QUANTITY' => $product['COUNT'],
                        "CANCELED" => "N",
                        "STATUS_ID" => "N",
                        "PRICE" => preg_replace('/[^0-9.,]/', '', $product['PRICE']),
                        "CURRENCY" => $currency,
                        "USER_ID" => $user_id,
                        "PAY_SYSTEM_ID" => 1,
                        "PRICE_DELIVERY" => 1,
                        "DELIVERY_ID" => 1,
                        "DISCOUNT_VALUE" => 0,
                        "TAX_VALUE" => 0.0,
                        "USER_DESCRIPTION" => "",
                        "ORDER_ID" => $ORDER_ID,
                    )
                );
                CSaleBasket::OrderBasket($ORDER_ID, $user_id, $Site['ID']);
            }
            $db_props = CSaleOrderProps::GetList(
                array("SORT" => "ASC"),
                array(
                    "PERSON_TYPE_ID" => 1,
                    "CODE" => array('FIO', 'PHONE', 'EMAIL'),
                ),
                false,
                false,
                array("CODE", 'ID')
            );
            while ($prop = $db_props->fetch()) {
                switch ($prop['CODE']) {
                    case "FIO" :
                        $order_props = array("ORDER_ID" => $ORDER_ID,
                            "ORDER_PROPS_ID" => $prop['ID'],
                            "NAME" => "FIO",
                            "CODE" => "FIO",
                            "VALUE" => $item['LOYALTYCARDSPARTNER']);
                        CSaleOrderPropsValue::Add($order_props);
                        break;
                    case "PHONE" :
                        $order_props = array("ORDER_ID" => $ORDER_ID,
                            "ORDER_PROPS_ID" => $prop['ID'],
                            "NAME" => "PHONE",
                            "CODE" => "PHONE",
                            "VALUE" => $user_phone);
                        CSaleOrderPropsValue::Add($order_props);
                        break;
                    case "EMAIL" :
                        $order_props = array("ORDER_ID" => $ORDER_ID,
                            "ORDER_PROPS_ID" => $prop['ID'],
                            "NAME" => "EMAIL",
                            "CODE" => "EMAIL",
                            "VALUE" => $user_email);
                        CSaleOrderPropsValue::Add($order_props);
                        break;
                }
            }
            CSaleOrder::PayOrder($ORDER_ID, "Y");
            global $DB;
            $site_format = CSite::GetDateFormat("SHORT");
            $php_format = $DB->DateFormatToPHP($site_format);
            CSaleOrder::update($ORDER_ID, array('DATE_UPDATE'=>date($php_format, '946677600')));
            ChecksTable::add(array('ORDER_ID'=>$ORDER_ID, 'CHECK_NUMBER'=>$item['NUMBER'], 'CHECK_NAME'=>$item['LINK']));
        }

    }
    static function order_update_null($ID){
        global $DB;
        $site_format = CSite::GetDateFormat("SHORT");
        $php_format = $DB->DateFormatToPHP($site_format);
        if(is_array($ID) && count($ID) > 0){
            $sql = "UPDATE `b_sale_order` SET `DATE_UPDATE` = '".date($php_format, '946677600')."' WHERE ";
            $i = 0;
            foreach ($ID as $update_id){
                if($i == 0 ) $sql .="(`ID` = '".$update_id."')";
                else $sql .=" OR (`ID` = '".$update_id."')";
                $i++;
            }
            $sql .=';';
        }else{
            $sql = "UPDATE `b_sale_order` SET `DATE_UPDATE` = '".date($php_format, '946677600')."' WHERE `ID` = '".$ID."'";
        }
        $DB->Query($sql);
    }
    static function OnOrder($ID, $arFields){
        $check = ChecksTable::GetList(array('filter'=>array('ORDER_ID'=>$ID)));
        if($check->fetch()){
            static::order_update_null($ID);
        }
        $active = COption::GetOptionString(self::MODULE_ID, "ADD_USER_GROUP_ON_REG");
        if($active == 'N') return;
        $rsUsers = CUser::GetList(($by="ID"), ($order="desc"), array("ID"=>$arFields['USER_ID']),array("SELECT"=>array("UF_*")));
        if(!$user = $rsUsers->fetch()) return;
//        if(!isset($user['UF_CODE']) || $user['UF_CODE'] == '') return false;
        $db_props = CSaleOrderProps::GetList(
            array("SORT" => "ASC"),
            array(
                "CODE" => "1c_tools_code"
            ),
            false,
            false,
            array()
        );
        if (!$props = $db_props->Fetch()){
            $fields = array(
                "PERSON_TYPE_ID" => 1,
                "NAME" => GetMessage(self::MODULE_ID.'_ORDER_CODE'),
                "TYPE" => "STRING",
                "REQUIED" => "N",
                "DEFAULT_VALUE" => "",
                "SORT" => 1000,
                "CODE" => "1c_tools_code",
                "USER_PROPS" => "N",
                "IS_LOCATION" => "N",
                "IS_LOCATION4TAX" => "N",
                "PROPS_GROUP_ID" => 1,
                "SIZE1" => 0,
                "SIZE2" => 0,
                "DESCRIPTION" => "",
                "IS_EMAIL" => "N",
                "IS_PROFILE_NAME" => "N",
                "IS_PAYER" => "N",
                'UTIL'=>'Y'
            );

            $ID_CODE = CSaleOrderProps::Add($fields);
        }else{
            $ID_CODE = $props['ID'];
        }
        $db_vals = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array(
                "ORDER_ID" => $ID,
                "ORDER_PROPS_ID" => $ID_CODE
            )
        );
        if (!$arVals = $db_vals->Fetch()){
            $order_props = array("ORDER_ID" => $ID,
                "ORDER_PROPS_ID" => $ID_CODE,
                "NAME" => "CODE",
                "CODE" => "1c_tools_code",
                "VALUE" => $user['UF_CODE']);
            CSaleOrderPropsValue::Add($order_props);
        }else {
            $order_props = array("ORDER_ID" => $ID,
                "ORDER_PROPS_ID" => $ID_CODE,
                "CODE" => "1c_tools_code",
                "VALUE" => $user['UF_CODE']);
            CSaleOrderPropsValue::Update($arVals['ID'], $order_props);
        }
    }
    static function OnBeforeOrderDelete($ID){
        $check = ChecksTable::GetList(array('filter'=>array('ORDER_ID'=>$ID)));
        if($check->fetch()){
            try
            {
                throw new SystemException('<p style="color: red;">'.GetMessage(self::MODULE_ID.'_ERROR_ORDER').'</p>');
            }
            catch (SystemException $exception)
            {
                echo $exception->getMessage();
            }
            return false;
        }
    }
    static function parse($data){
        $P = new CDataXML();
        $array = array();
        $P->loadstring($data);
        $data = $P->SelectNodes('/Documents/');
        $data = $data->children();
        $cursor = 0;
        foreach ($data as $item) {
            $element = $item->children();
            foreach ($element as $el) {
                $cursor_product = 0;
                $array['DOCUMENTS'][$cursor][mb_strtoupper($el->name)] = $el->content;
                if($products = $el->children()){
                    foreach ($products as $product) {
                        $prod = $product->children();
                        foreach ($prod as $it) {
                            $array['DOCUMENTS'][$cursor][mb_strtoupper($el->name)][$cursor_product][mb_strtoupper($it->name)] = $it->content;
                        }
                        $cursor_product++;
                    }
                }
            }
            $cursor++;
        }
        return $array;
    }
    static function DeactivationOnProperty($arFields){
        $property = COption::GetOptionString(self::MODULE_ID, "DEACTIVATION_PROPERTY");
        $arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_".$property);
        $arFilter = Array("ID"=>$arFields['ID']);
        $res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
        if($ob = $res->fetch()){
            if($ob['PROPERTY_'.$property.'_VALUE'] == GetMessage(self::MODULE_ID.'_Y')){
                global $DB;
                $sql = "UPDATE b_iblock_element SET ACTIVE='N' WHERE ID=".$arFields["ID"];
                $DB->Query($sql);
            }elseif($ob['PROPERTY_'.$property.'_VALUE'] == GetMessage(self::MODULE_ID.'_N')){
                global $DB;
                $sql = "UPDATE b_iblock_element SET ACTIVE='Y' WHERE ID=".$arFields["ID"];
                $DB->Query($sql);
            }

        }
    }

    static function DiscontsConditionBasket($SETTING=array(), $arFields=array())
    {
        $sites = unserialize(COption::GetOptionString(self::MODULE_ID, "DICOUNT_SITE"));
        if (!CModule::IncludeModule('sale')) return false;
        //определим тип скидки и получим доп параметры
        //START
        if (strpos($SETTING['DISCONT_VALUE'], '%')) {
            $SETTING['TYPE'] = 'P';
            $type = 'Perc';
        } else {
            $SETTING['TYPE'] = 'F';
            $type = 'CurEach';
        }
        //$SETTING['DISCONT_VALUE'] = intval($SETTING['DISCONT_VALUE']);
        //END

        //получим id скидки если ее нет то создадим
        //START
        $siteId = CDataToolsEvent::GetIblockSiteById($arFields['IBLOCK_ID']);
        foreach ($siteId as $id) {
            if(count($sites) > 0 && !empty($sites)){
                if(!in_array($id,$sites)) continue;
            }
            $discountAll = CDataToolsEvent::GetAllDiscontTools($id);
            $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);

            //сделаем проверку на наличие скидки и привязанных к ней товаров, стоит ограничение в 250 товаров на скидку
            if ($SETTING['DISCONT_VALUE'] && $discountAll['DISCOUNT_ID_SEARCH'][$SETTING['TYPE'] . '_' . $SETTING['DISCONT_VALUE']]) {

                foreach ($discountAll['DISCOUNT_ID_SEARCH'][$SETTING['TYPE'] . '_' . $SETTING['DISCONT_VALUE']] as $disconyId) {
                    if (count($arrDiscontProduct["DISCONT_INFO"][$disconyId]) < 250) {
                        $SETTING['DISCONT_ID'] = $disconyId;
                        break;
                    }
                }
            }
            if (!isset($SETTING['DISCOUNT_ID'])) $SETTING['DISCOUNT_ID'] = null;
            $is = CSaleDiscount::GetByID($SETTING['DISCONT_ID']);
            if($is['SITE_ID'] != $id) $SETTING['DISCONT_ID'] = null;
            if ($SETTING['DISCONT_VALUE'] && empty($SETTING['DISCONT_ID'])) {
//                $siteId = CDataToolsEvent::GetIblockSiteById($arFields['IBLOCK_ID']);
                $SELECT_DISCONT_LAST_DISCOUNT = COption::GetOptionString('data.tools1c', "SELECT_DISCONT_LAST_DISCOUNT");
                if ($SELECT_DISCONT_LAST_DISCOUNT) {
                    $DiscountAdd['LAST_DISCOUNT'] = $SELECT_DISCONT_LAST_DISCOUNT;
                }


                $SELECT_DISCONT_GROUP_IDS = COption::GetOptionString('data.tools1c', "SELECT_DISCONT_GROUP_IDS");
                $SELECT_DISCONT_GROUP_IDS = unserialize($SELECT_DISCONT_GROUP_IDS);
                if (count($SELECT_DISCONT_GROUP_IDS) > 0) {
                    $DiscountAdd['GROUP_IDS'] = $SELECT_DISCONT_GROUP_IDS;
                } else {
                    return false;
                }

                $DiscountAdd = array(
                    "LID" => $id,
                    "NAME" => GetMessage(self::MODULE_ID . '_DISCONT_NAME'),
                    "ACTIVE_FROM" => "",
                    "ACTIVE_TO" => "",
                    "ACTIVE" => "Y",
                    "SORT" => "100",
                    "PRIORITY" => "1",
                    "LAST_DISCOUNT" => $DiscountAdd['LAST_DISCOUNT'],
                    "LAST_LEVEL_DISCOUNT" => "N",
                    "XML_ID" => "",
                    "CONDITIONS" => array(
                        "CLASS_ID" => "CondGroup",
                        "DATA" => array(
                            "All" => "AND",
                            "True" => "True",
                        ),
                        "CHILDREN" => array()
                    ),
                    "ACTIONS" => array(
                        "CLASS_ID" => "CondGroup",
                        "DATA" => array(
                            "All" => "AND",
                        ),
                        "CHILDREN" => array(
                            0 => array(
                                "CLASS_ID" => "ActSaleBsktGrp",
                                "DATA" => array(
                                    "Type" => "Discount",
                                    "Value" => (int)$SETTING['DISCONT_VALUE'],
                                    "Unit" => $type,
                                    "Max" => 0,
                                    "All" => "OR",
                                    "True" => "True",
                                ),
                                "CHILDREN" => array(
                                    0 => array(
                                        "CLASS_ID" => "CondIBElement",
                                        "DATA" => array(
                                            "logic" => "Equal",
                                            "value" => array(
                                                0 => $arFields['ID']
                                            )
                                        )
                                    ),
                                )
                            )
                        )
                    ),
                    "USER_GROUPS" => $DiscountAdd['GROUP_IDS']);
                if ($SETTING['DISCONT_ID'] = CSaleDiscount::Add($DiscountAdd)) {
                    CDataToolsDiscount::Add(
                        array(
                            "DISCONT_ID" => $SETTING['DISCONT_ID'],
                            "DISCONT_VALUE" => $SETTING['DISCONT_VALUE'],
                            "TYPE" => $SETTING['TYPE']
                        )
                    );
                }
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID . '_GetAllDiscontTools'.$id);
            }
            //END


            //получим товары и скидки, обработаем их
            //START
            $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);
            //если у товара уже нет скидки удалим ее
            if (empty($SETTING['DISCONT_VALUE']) && $arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]]) {
                //пройдемся по скидкам продукта
                if (empty($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]])) {
                    $arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]] = array();
                }

                if (count($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]]) > 0) {
                    foreach ($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]] as $discontId) {
                        // соберем массив товаров для скидки кроме текущего товара
                        $GoodsArr = array();
                        foreach ($arrDiscontProduct["DISCONT_INFO"][$discontId] as $GoodId) {
                            if ($GoodId != $arFields["ID"]) {
                                $GoodsArr[] = array(
                                    'CLASS_ID' => 'CondIBElement',
                                    'DATA' =>
                                        array(
                                            'logic' => 'Equal',
                                            'value' => array(0 => $GoodId)
                                        )
                                );
                            }
                        }
                        $DiscountAdd = array(
                            "CONDITIONS" => array(
                                "CLASS_ID" => "CondGroup",
                                "DATA" => array(
                                    "All" => "AND",
                                    "True" => "True",
                                ),
                                "CHILDREN" => array()
                            ),
                            "ACTIONS" => array(
                                "CLASS_ID" => "CondGroup",
                                "DATA" => array(
                                    "All" => "AND",
                                ),
                                "CHILDREN" => array(
                                    0 => array(
                                        "CLASS_ID" => "ActSaleBsktGrp",
                                        "DATA" => array(
                                            "Type" => "Discount",
                                            "Value" => (int)$SETTING['DISCONT_VALUE'],
                                            "Unit" => $type,
                                            "Max" => 0,
                                            "All" => "OR",
                                            "True" => "True",
                                        ),
                                        "CHILDREN" => $GoodsArr
                                    )
                                )
                            )
                        );
                        $DiscountAdd['PRESET_ID'] = '';
                        $DiscountAdd['PREDICTIONS'] = '';
                        $DiscountAdd['PREDICTIONS_APP'] = '';
                        CSaleDiscount::Update($discontId, $DiscountAdd);

                    }
                }


                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID . '_GetDiscontProduct'.$id);
                $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);
            }


            // если товар не состоит в данной скидке, добавим ее к скидке
            if (empty($arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']])) {
                $arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']] = array();
            }
            if (!in_array($arFields["ID"], $arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']])) {
                $allIdElementIblock = CDataToolsEvent::GetAllIdElementIblock();
                // составим массив для добавления скидки
                $GoodsArr = array();
                if (empty($arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']])) {
                    $arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']] = array();
                }
                if (count($arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']]) > 0) {
                    foreach ($arrDiscontProduct["DISCONT_INFO"][$SETTING['DISCONT_ID']] as $GoodId) {

                        //если элемента уже нет в базе снимем его из скидки
                        if (empty($allIdElementIblock[$GoodId])) {
                            continue;
                        }

                        $GoodsArr[] = array(
                            'CLASS_ID' => 'CondIBElement',
                            'DATA' =>
                                array(
                                    'logic' => 'Equal',
                                    'value' => array(0 => $GoodId)
                                )
                        );


                    }
                }

                $GoodsArr[] = array(
                    'CLASS_ID' => 'CondIBElement',
                    'DATA' =>
                        array(
                            'logic' => 'Equal',
                            'value' => array(0 => $arFields['ID'])
                        )
                );

                $DiscountAdd = array(
                    "NAME" => GetMessage(self::MODULE_ID . '_DISCONT_NAME'),
                    "ACTIVE_FROM" => "",
                    "ACTIVE_TO" => "",
                    "ACTIVE" => "Y",
                    "SORT" => "100",
                    "PRIORITY" => "1",
                    "LAST_LEVEL_DISCOUNT" => "N",
                    "XML_ID" => "",
                    "ACTIONS" => array(
                        "CLASS_ID" => "CondGroup",
                        "DATA" => array(
                            "All" => "AND",
                        ),

                        "CHILDREN" => array(
                            0 => array(
                                "CLASS_ID" => "ActSaleBsktGrp",
                                "DATA" => array(
                                    "Type" => "Discount",
                                    "Value" => (int)$SETTING['DISCONT_VALUE'],
                                    "Unit" => $type,
                                    "Max" => 0,
                                    "All" => "OR",
                                    "True" => "True",
                                ),
                                "CHILDREN" => $GoodsArr
                            )
                        )
                    )
                );

                CSaleDiscount::Update($SETTING['DISCONT_ID'], $DiscountAdd);
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID . '_GetDiscontProduct'.$id);
                $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);
            }

            //нужно почистить другие скидки от этого товара
            if (count($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]]) > 1) {
                //пройдемся по скидкам продукта
                if (empty($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]])) {
                    $arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]] = array();
                }
                foreach ($arrDiscontProduct["PRODUCT_INFO"][$arFields["ID"]] as $discontId) {
                    //если это не текущая скидка
                    if ($discontId != $SETTING['DISCONT_ID']) {
                        $value = CSaleDiscount::GetById($discontId);
                        $value = unserialize($value['ACTIONS']);
                        $value = $value['CHILDREN'][0]['DATA']['Value'];
                        // соберем массив товаров для скидки кроме текущего товара
                        $GoodsArr = array();
                        foreach ($arrDiscontProduct["DISCONT_INFO"][$discontId] as $GoodId) {
                            if ($GoodId != $arFields["ID"]) {
                                $GoodsArr[] = array(
                                    'CLASS_ID' => 'CondIBElement',
                                    'DATA' =>
                                        array(
                                            'logic' => 'Equal',
                                            'value' => $GoodId,
                                        )
                                );
                            }
                        }

                        $DiscountAdd = array(
                            "NAME" => GetMessage(self::MODULE_ID . '_DISCONT_NAME'),
                            "ACTIVE_FROM" => "",
                            "ACTIVE_TO" => "",
                            "ACTIVE" => "Y",
                            "SORT" => "100",
                            "PRIORITY" => "1",
                            "LAST_LEVEL_DISCOUNT" => "N",
                            "XML_ID" => "",
                            "ACTIONS" => array(
                                "CLASS_ID" => "CondGroup",
                                "DATA" => array(
                                    "All" => "AND",
                                ),

                                "CHILDREN" => array(
                                    0 => array(
                                        "CLASS_ID" => "ActSaleBsktGrp",
                                        "DATA" => array(
                                            "Type" => "Discount",
                                            "Value" => $value,
                                            "Unit" => $type,
                                            "Max" => 0,
                                            "All" => "OR",
                                            "True" => "True",
                                        ),
                                        "CHILDREN" => $GoodsArr
                                    )
                                )
                            ));


                        CSaleDiscount::Update($discontId, $DiscountAdd);


                    }

                }
                global $CACHE_MANAGER;
                $CACHE_MANAGER->ClearByTag(self::MODULE_ID . '_GetDiscontProduct'.$id);
                $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);
            }
            //END


            //почистим массив от пустых скидок
            $CHEXBOX_DISCONT_EMPTY_DELETE = COption::GetOptionString('data.tools1c', "CHEXBOX_DISCONT_EMPTY_DELETE");
            if ($CHEXBOX_DISCONT_EMPTY_DELETE == 'Y') {
                $DELETE_DISCONT = 'N';
                if (empty($arrDiscontProduct['DISCONT_INFO'])) {
                    $arrDiscontProduct['DISCONT_INFO'] = array();
                }
                if (count($arrDiscontProduct['DISCONT_INFO']) > 0) {
                    foreach ($arrDiscontProduct['DISCONT_INFO'] as $kDiscont => $vDiscont) {
                        if (count($vDiscont) == 0) {
                            CSaleDiscount::Delete($kDiscont);
                            $DELETE_DISCONT = 'Y';
                        }
                    }
                }

                if ($DELETE_DISCONT == 'Y') {
                    $arrDiscontProduct = CDataToolsEvent::GetDiscontProduct($id);
                }
            }
        }
    }

    static function user_code($arFields){
        $active = COption::GetOptionString(self::MODULE_ID, "ADD_USER_GROUP_ON_REG");
        if($active == 'N') return;
        $rsUsers = CUser::GetList(($by="ID"), ($order="desc"), array("ID"=>$arFields['ID']),array("SELECT"=>array("UF_*")));
        if(!$user = $rsUsers->fetch()) return;
        if(!isset($user['UF_CODE'])){
            $fields = Array(
                "ENTITY_ID" => "USER",
                "FIELD_NAME" => "UF_CODE",
                "USER_TYPE_ID" => "string",
                "EDIT_FORM_LABEL" => Array("ru"=>"code", "en"=>"code")
            );
            $obUserField  = new CUserTypeEntity;
            $obUserField->Add($fields);
        }elseif($user['UF_CODE'] != ''){
            return;
        }
        if($user['EMAIL'] != '' && $user['PERSONAL_PHONE'] != ''){
            $hl = COption::GetOptionString(self::MODULE_ID, "HL_FOR_DISCOUNT_CARD_11_2");
            $entit = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
                "filter" => array(
                    'NAME' => $hl
                )));
            $id = 0;
            while ($hlblock = $entit->fetch()){
                $id = $hlblock['ID'];
            }
            if($id == 0) return;
            $name_field = COption::GetOptionString(self::MODULE_ID, "FIELDS_PARTNER_CARD_11_2");
            $name = COption::GetOptionString(self::MODULE_ID, "ADD_USER_GROUP_ON_REG_DISCOUNT");
            $code = COption::GetOptionString(self::MODULE_ID, "ADD_USER_GROUP_ON_REG_CODE");
            $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($id)->fetch();
            $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $entity_table_name = $hlblock['TABLE_NAME'];
            $sTableID = 'tbl_' . $entity_table_name;
            $arFilter = array($name_field => $name);
            $items = $entity_data_class::getList(array(
                "select" => array('*'),
                "filter" => $arFilter,
                "limit" => '1'
            ));
            $id_card = 0;
            $data = new CDBResult($items, $sTableID);
            $discount_code = '';
            while($it = $data->fetch()){
                $discount_code = $it[$code];
                $id_card = $it['ID'];
            }

            if($discount_code != '' && $id_card !=0){
                $user_code = new CUser();
                $fields = Array(
                    "UF_CODE" => $discount_code
                );
                if($user_code->update($arFields['USER_ID'], $fields)){
                    $entity_data_class::delete($id_card);
                }
            }
        }
    }

    function OnAfterUserAddHandler($arFields){
        static::user_code($arFields);
    }
    
    static function events($filename, $file){
        switch ($filename){
            case 'contragents' : static::parseContragetns($file); break;
            case 'documents' : static::parseDocumments($file); break;
            default : break;
        }
    }

    static function parseUsers($el){
        foreach ($el as $item) {
            $agent = array();
            if ($item->name == GetMessage(self::MODULE_ID . 'CC_BSC1_AGENT')) {
                foreach ($item->children as $child) {

                    if ($child->name == GetMessage(self::MODULE_ID . 'CC_BSC1_ID')) {
                        $agent['ID'] = $child->content;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . 'CC_BSC1_CANCELED')) {
                        $agent['ACTIVE'] = $child->content == "false" ? true : false;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . 'CC_BSC1_NAME')) {
                        $agent['NAME'] = $child->content;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_FULL_NAME')) {
                        $agent['FULL_NAME'] = $child->content;
                        $agent['INDIVIDUAL'] = true;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_OFF_NAME')) {
                        $agent['FULL_NAME'] = $child->content;
                        $agent['INDIVIDUAL'] = false;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_ROLE')) {
                        $agent['ROLE'] = $child->content;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_INN')) {
                        $agent['INN'] = $child->content;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_KPP')) {
                        $agent['KPP'] = $child->content;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_CODE_PO_OKPO')) {
                        $agent['CODE_PO_OKPO'] = $child->content;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_ADDRESS')) {
                        foreach ($child->children as $address) {
                            if ($address->name == GetMessage(self::MODULE_ID . '_PERFORMACE')) {
                                $agent['ADDRESS']['PERFORMACE'] = $address->content;
                            }
                            if ($address->name == GetMessage(self::MODULE_ID . '_ADDRESS_FIELD')) {
                                if ($address->children[0]->content == GetMessage(self::MODULE_ID . '_COUNTRY')) {
                                    $agent['ADDRESS']['COUNTRY'] = $address->children[1]->content;
                                }
                            }
                        }
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_CONTACTS')) {
                        foreach ($child->children as $contacts) {
                            if ($contacts->name == GetMessage(self::MODULE_ID . '_CONTACT')) {
                                if ($contacts->children[0]->content == GetMessage(self::MODULE_ID . '_PHONE')) {
                                    $agent['PHONE'] = $contacts->children[1]->content;
                                }
                            }
                        }
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_CONTACTS')) {
                        foreach ($child->children as $contacts) {
                            if ($contacts->name == GetMessage(self::MODULE_ID . '_CONTACT')) {
                                if ($contacts->children[0]->content == GetMessage(self::MODULE_ID . '_MAIL')) {
                                    $agent['MAIL'] = $contacts->children[1]->content;
                                }
                            }
                        }
                    }
                }
                $agents[] = $agent;
            }
        }
        return $agents;
    }

    static function parseGoods($el)
    {
        $good = array();
        $goods = array();
        foreach ($el as $item) {
            if ($item->name == GetMessage(self::MODULE_ID . '_GOOD')) {
                foreach ($item->children as $child) {
                    if ($child->name == GetMessage(self::MODULE_ID . 'CC_BSC1_ID')) {
                        $good['ID'] = $child->content;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_NAME')) {
                        $good['NAME'] = $child->content;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_TAXES')) {
                        $good['TAXES'] = static::parseTaxes($child);
                    }
                    if($child->name == GetMessage(self::MODULE_ID . '_PROPS_VALUE')){
                        foreach ($child->children as $props) {
                            $good['PROPS'][] = array('NAME'=>$props->children[0]->content, 'VALUE'=>$props->children[1]->content);
                        }
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_COUNT')) {
                        $good['COUNT'] = $child->content;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_PRICE')) {
                        $good['PRICE'] = $child->content;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_SUM')) {
                        $good['SUM'] = $child->content;
                    }
                    if ($child->name == GetMessage(self::MODULE_ID . '_COF')) {
                        $good['COF'] = $child->content;
                    }
                }
                $goods[] = $good;
            }
        }
        return $goods;
    }

    static function parseTaxes($elements){
        $return = array();

        foreach ($elements->children as $taxes){
            if($taxes->name == GetMessage(self::MODULE_ID . '_TAXE')){
                $tax = array();
                foreach ($taxes->children as $taxe) {
                    if($taxe->name == GetMessage(self::MODULE_ID . '_NAME')){
                        $tax['NAME'] = $taxe->content;
                    }
                    if($taxe->name == GetMessage(self::MODULE_ID . '_IN_SUM')){
                        $tax['IN_SUM'] = $taxe->content;
                    }
                    if($taxe->name == GetMessage(self::MODULE_ID . '_SUM')){
                        $tax['SUM'] = $taxe->content;
                    }
                    if($taxe->name == GetMessage(self::MODULE_ID . '_RATE')){
                        $tax['RATE'] = $taxe->content;
                    }
                }
                $return[]=$tax;
            }
        }
        return $return;
    }

    static function parseContragetns($file) {
//        echo '<pre>';
        $documents  = file_get_contents($file);
        $parse = new CDataXML();
        $array = array();
        $parse->loadstring($documents);
        $data = $parse->SelectNodes(GetMessage(self::MODULE_ID . 'CC_BSC1_COM_INFO'));
        foreach ($data->children as $child) {
            foreach ($child->children as $item) {
                $array = static::parseUsers($item->children);
            }
        }
        static::create_profile_agents($array);
    }
    static function parseDocumments($file){
        $documents  = file_get_contents($file);
        $parse = new CDataXML();
        $order = array();
        $payment = array();
        $delivery = array();
        $parse->loadstring($documents);
        $data = $parse->SelectNodes(GetMessage(self::MODULE_ID . 'CC_BSC1_COM_INFO'));
        foreach ($data->children as $child) {
            foreach ($child->children as $item) {
                if($item->name == GetMessage(self::MODULE_ID . 'CC_BSC1_CONTAINER')){
                   foreach ($item->children as $docum){
                      if($docum->name ==  GetMessage(self::MODULE_ID . 'CC_BSC1_DOCUMENT')){
                          $store = array();
                          foreach ($docum->children as $elements) {
                              if($elements->name == GetMessage(self::MODULE_ID . 'CC_BSC1_ID')){
                                $store['ID'] = $elements->content;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . 'CC_BSC1_CANCELED')){
                                $store['ACTIVE'] = $elements->content == "false"?true:false;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . 'CC_BSC1_ID_1C')){
                                $store['NUMBER'] = $elements->content;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . 'CC_BSC1_NUMBER_BASE')){
                                $store['ORDER_XML_ID'] = $elements->content;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . 'CC_BSC1_VERSION_1C')){
                                $store['VERSION'] = $elements->content;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . 'CC_BSC1_1C_DATE')){
                                $store['DATE'] = $elements->content;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . 'CC_BSC1_1C_TIME')){
                                  $store['TIME'] = $elements->content;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . 'CC_BSC1_OPERATION')){
                                  $store['OPERATION'] = $elements->content;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . 'CC_BSC1_AGENTS')){
                                $store['AGENTS'] = static::parseUsers($elements->children);
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . '_STOCKS')){
                                  foreach ($elements->children as $stocks) {
                                      if($stocks->name == GetMessage(self::MODULE_ID . '_STOCK')){
                                          $store['STOCKS'][] = array('ID'=>$stocks->children[0]->content, 'NAME'=>$stocks->children[1]->content);
                                      }
                                  }
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . '_CURRENCY')){
                                  $store['CURRENCY'] = $elements->content;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . '_CUR')){
                                  $store['CURCE'] = $elements->content;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . '_SUM')){
                                  $store['SUM'] = $elements->content;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . '_COMMENT')){
                                  $store['COMMENT'] = $elements->content;
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . '_TAXES')){
                                  $store['TAXES'] = static::parseTaxes($elements);
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . '_PROPS_VALUE')){
                                  foreach ($elements->children as $props) {
                                      $store['PROPS'][] = array('NAME'=>$props->children[0]->content, 'VALUE'=>$props->children[1]->content);
                                  }
                              }
                              if($elements->name == GetMessage(self::MODULE_ID . '_GOODS')){
                                  $store["GOODS"] = static::parseGoods($elements->children);
                              }
                          }

                          switch ($store['OPERATION']){
                              case GetMessage(self::MODULE_ID . 'CC_BSC1_PAYMENT_BC') :
                              case GetMessage(self::MODULE_ID . 'CC_BSC1_PAYMENT_C') :
                              case GetMessage(self::MODULE_ID . 'CC_BSC1_PAYMENT_B') :
                              case GetMessage(self::MODULE_ID . 'CC_BSC1_PAYMENT_A') :
                              case GetMessage(self::MODULE_ID . 'CC_BSC1_PAYMENT_COMMENTS_1C') :
                                  $payment[] = $store; break;
                              case GetMessage(self::MODULE_ID . 'CC_BSC1_ORDER'):  $order[] = $store; break;
                              case GetMessage(self::MODULE_ID . 'CC_BSC1_SHIPMENT'):  $delivery[] = $store; break;
                          }
                      }
                   }
                }
            }
        }
        static::create_profile($order, $payment, $delivery);
    }

    static function create_profile_agents($array){
        CModule::IncludeModule('sale');
        $db_ptype = CSalePersonType::GetList(Array("SORT" => "ASC"), Array());
        $persone = array();
        while ($ptype = $db_ptype->Fetch())
        {
            $persone[$ptype['NAME']] = $ptype['ID'];
        }
        foreach ($array as $in_agent) {
            $filter = Array("EMAIL" => $in_agent['MAIL']);
            $rsUsers = CUser::GetList(($by = "personal_country"), ($order = "desc"), $filter); // выбираем пользователей
            if ($user = $rsUsers->GetNext()) {

                $db_sales = CSaleOrderUserProps::GetList(array(), array("USER_ID" => $user['ID']));

                if ($in_agent['INDIVIDUAL']) {
                    $id = '';
                    while ($ar_sales = $db_sales->Fetch()) {
                        if ($ar_sales['PERSON_TYPE_ID'] == $persone[GetMessage(self::MODULE_ID . '_FIZ')]) {
                            $id = $ar_sales['ID'];
                        }
                    }
                    if ($id == '') {
                        $arFields = array(
                            "NAME" => $user['NAME'] . ' ' . $user['LAST_NAME'],
                            "USER_ID" => $user['ID'],
                            "PERSON_TYPE_ID" => $persone[GetMessage(self::MODULE_ID . '_FIZ')]
                        );
                        $USER_PROPS_ID = CSaleOrderUserProps::Add($arFields);
                    }
                }
                if (!$in_agent['INDIVIDUAL']) {
                    $id = '';
                    while ($ar_sales = $db_sales->Fetch()) {
                        if ($ar_sales['PERSON_TYPE_ID'] == $persone[GetMessage(self::MODULE_ID . '_YOUR')]) {
                            $id = $ar_sales['ID'];
                        }
                    }
                    if ($id == '') {
                        $arFields = array(
                            "NAME" => $user['NAME'] . ' ' . $user['LAST_NAME'],
                            "USER_ID" => $user['ID'],
                            "PERSON_TYPE_ID" => $persone[GetMessage(self::MODULE_ID . '_YOUR')]
                        );
                        $USER_PROPS_ID = CSaleOrderUserProps::Add($arFields);
                    }
                }
            }
        }
    }

    static function create_profile($array, $payment, $delivery){
        CModule::IncludeModule('sale');
        $create_profile = COption::GetOptionString('data.tools1c' , "ON_ADD_USER_CREATE_PROFILE_ORDER");
        $db_ptype = CSalePersonType::GetList(Array("SORT" => "ASC"), Array());
        $persone = array();
        while ($ptype = $db_ptype->Fetch())
        {
            $persone[$ptype['NAME']] = $ptype['ID'];
        }
        $documents_id = array();
        foreach ($array as $key => $document) {
            $documents_id[$key] = $document['ID'];
        }
        $dbOrders = \Bitrix\Sale\Internals\OrderTable::getList(array(
            'filter' => array("ID_1C" => $documents_id),
            'select' => array('*')));
        $order_xml_id = array();
        while($arOrder = $dbOrders->fetch()){
            $order_xml_id[] = $arOrder['ID_1C'];
        }
        foreach ($documents_id as $k => $id){
            if(in_array($id, $order_xml_id)){
                unset($array[$k]);
            }
        }
        foreach ($array as $document) {
            foreach ($document['AGENTS'] as $in_agent) {
                $inn = false;
                if($in_agent['INN'] != '' && $in_agent['INN']){
                    $inn = $in_agent['INN'];
                    $db_vals = CSaleOrderUserPropsValue::GetList(
                        array(),
                        array("NAME" => GetMessage(self::MODULE_ID . '_INN'),
                            "VALUE" => $inn
                        )
                    );
                    if ($arVals = $db_vals->Fetch()){
                        $user_id_props = CSaleOrderUserProps::GetByID($arVals['USER_PROPS_ID']);
                        $filter = Array("ID" => $user_id_props['USER_ID']);
                    }else{
                        $filter = Array("EMAIL" => $in_agent['MAIL']);
                    }
                }
                else{
                    $filter = Array("EMAIL" => $in_agent['MAIL']);
                }
                $rsUsers = CUser::GetList(($by="personal_country"), ($order="desc"), $filter); // выбираем пользователей
                if($user = $rsUsers->GetNext()) {

                    $db_sales = CSaleOrderUserProps::GetList(array(), array("USER_ID" => $user['ID']));
                    if($in_agent['INDIVIDUAL']){
                        $id = '';
                        while ($ar_sales = $db_sales->Fetch())
                        {
                            if($ar_sales['PERSON_TYPE_ID'] == $persone[GetMessage(self::MODULE_ID . '_FIZ')]){
                                $id = $ar_sales['ID'];
                            }
                        }
//
                        if($id == '' && $create_profile == 'Y'){
                            $arFields = array(
                                "NAME" => $user['NAME'].' '.$user['LAST_NAME'],
                                "USER_ID" => $user['ID'],
                                "PERSON_TYPE_ID" => $persone[GetMessage(self::MODULE_ID . '_FIZ')]
                            );
                            $USER_PROPS_ID = CSaleOrderUserProps::Add($arFields);

                            $order_id = static::create_order_on_profile($persone[GetMessage(self::MODULE_ID . '_FIZ')], $user, $document, $payment, $delivery, 'PERSON', $inn, $in_agent);

                            if($inn){
                                ProfileTable::add(array('USER_ID'=>$user['ID'], 'PROFILE_ID'=>$USER_PROPS_ID, 'INN'=>$inn, 'PROFILE_TYPE'=>$persone[GetMessage(self::MODULE_ID . '_FIZ')]));
                            }

                                $db = CSaleOrderPropsValue::GetOrderProps($order_id);
                                while ($arProps = $db->Fetch()) {
                                    $arFields = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "ORDER_PROPS_ID" => $arProps['ORDER_PROPS_ID'],
                                        "NAME" => $arProps['NAME'],
                                        "VALUE" => $arProps['VALUE']
                                    );
                                    CSaleOrderUserPropsValue::Add($arFields);
                                }

                        }else{
                             static::create_order_on_profile($persone[GetMessage(self::MODULE_ID . '_FIZ')], $user, $document, $payment, $delivery, 'PERSON', $inn, $in_agent);
                        }
                    }
                    if(!$in_agent['INDIVIDUAL']){
                        if(strlen($inn) > 10) {
                            $prof = GetMessage(self::MODULE_ID . '_IN_PRE');
                        }else{
                            $prof = GetMessage(self::MODULE_ID . '_YOUR');
                        }
                        $id = '';
                        while ($ar_sales = $db_sales->Fetch())
                        {
                            if($ar_sales['PERSON_TYPE_ID'] == $persone[$prof]){
                                $id = $ar_sales['ID'];
                            }
                        }
                        if($id == '' && $create_profile == 'Y'){
                            $arFields = array(
                                "NAME" => $user['NAME'].' '.$user['LAST_NAME'],
                                "USER_ID" => $user['ID'],
                                "PERSON_TYPE_ID" => $persone[$prof]
                            );
                            $USER_PROPS_ID = CSaleOrderUserProps::Add($arFields);

                            $order_id = static::create_order_on_profile($persone[$prof], $user, $document, $payment, $delivery, 'COMPANY', $inn, $in_agent);

                            if($inn){
                                ProfileTable::add(array('USER_ID'=>$user['ID'], 'PROFILE_ID'=>$USER_PROPS_ID, 'INN'=>$inn, 'PROFILE_TYPE'=>$persone[$prof]));
                            }

                                $db = CSaleOrderPropsValue::GetOrderProps($order_id);
                                while ($arProps = $db->Fetch()) {
                                    $arFields = array(
                                        "USER_PROPS_ID" => $USER_PROPS_ID,
                                        "ORDER_PROPS_ID" => $arProps['ORDER_PROPS_ID'],
                                        "NAME" => $arProps['NAME'],
                                        "VALUE" => $arProps['VALUE']
                                    );
                                    CSaleOrderUserPropsValue::Add($arFields);
                                }

                        }else{
                             static::create_order_on_profile($persone[$prof], $user, $document, $payment, $delivery, 'COMPANY', $inn, $in_agent);
                        }
                    }
                }
            }
        }
}


    static function create_order_on_profile($id, $user, $document, $payments, $deliverys, $prof, $inn, $agent){
        Bitrix\Main\Loader::includeModule("catalog");
        CModule::includeModule('sale');
        $create_order = COption::GetOptionString('data.tools1c' , "CREATE_ORDER");
        if($create_order != 'Y') return;
        //#update#
//        $dbOrders = \Bitrix\Sale\Internals\OrderTable::getList(array(
//            'filter' => array("ID_1C" => $document['ID']),
//            'select' => array('ID')));
//        if ($arOrder = $dbOrders->fetch()) {
//            return;
//        }
        $time = \Bitrix\Main\Type\Date::createFromPhp(new \DateTime($document['DATE']));
        global $DB;
        $prop = $DB->Query("SELECT * FROM `b_sale_bizval` WHERE `PERSON_TYPE_ID`='".$id."'");
        $order_prop = array();
        while($orpro = $prop->fetch()){
            switch ($orpro['CODE_KEY']){
                case 'BUYER_'.$prof.'_INN' : $order_prop[$orpro['PROVIDER_VALUE']] = $inn; break;
                case 'BUYER_'.$prof.'_EMAIL' : $order_prop[$orpro['PROVIDER_VALUE']] = $agent['MAIL']; break;
                case 'BUYER_'.$prof.'_PHONE' : $order_prop[$orpro['PROVIDER_VALUE']] = $agent['PHONE']; break;
                case 'BUYER_'.$prof.'_NAME_AGENT' : $order_prop[$orpro['PROVIDER_VALUE']] = $agent['FULL_NAME']; break;
                case 'BUYER_'.$prof.'_NAME_CONTACT' : $order_prop[$orpro['PROVIDER_VALUE']] = $agent['FULL_NAME']; break;
                case 'BUYER_'.$prof.'_ADDRESS' : $order_prop[$orpro['PROVIDER_VALUE']] = $agent['ADDRESS']['PERFORMACE']; break;
                default: break;
            }
        }
        $params = array();
        $params['TRAITS'] = Array
        (
            'CANCELED' => 'N',
            'DATE_INSERT' => $time,
            'COMMENTS' => '',
            'ID_1C' => $document['ID'],
            'VERSION_1C' => $document['VARSION'],
            'ORDER_PROP' => $order_prop,
            'USER_ID' => $user['ID'],
            'PERSON_TYPE_ID' => $id,
            'DATE_STATUS' => $time,
            'DATE_UPDATE' => $time
        );
        foreach ($document['GOODS'] as $k => $item) {
            $params['ITEMS'][$k][$item['ID']] = array(
                'ID' => $item['ID'],
                'NAME' => $item['NAME'],
                'PRICE' => $item['PRICE'],
                'PRICE_ONE' => $item['PRICE'],
                'QUANTITY' => $item['COUNT'],
                'TYPE' => 'ITEM',
                'MEASURE_CODE' => 796,
                'MEASURE_NAME' => 'Штука',
                'ATTRIBUTES' =>'',
                'TAX' => Array(
                    'VAT_RATE' =>'',
                ),
                'DISCOUNT' => Array('PRICE' => '')
            );
        }
        $params['TAXES'] = Array();

        $add = new Bitrix\Sale\Exchange\Entity\OrderImport();
        $settings = Bitrix\Sale\Exchange\OneC\ImportSettings::getCurrent();
        $add->loadSettings($settings);
       $res = $add->add($params);
        if($res->isSuccess()){
            $add->save();
            OrderTable::add(array('ORDER_ID'=>$add->getId()));
        }
        $pays = array();
        $pays['CASH_BOX_CHECKS'] = Array();

        $payId='';
        $db_ptype = CSalePaySystem::GetList(
            array(),
            array('NAME'=>GetMessage(self::MODULE_ID.'_PAY_SYSTEM')),
            false,
            false,
            array()
        );
        if($ptype = $db_ptype->Fetch())
        {
            $payId = $ptype['ID'];
        }
        if($payId != ''){
            foreach ($payments as $pay) {
                if($pay['ORDER_XML_ID'] == $document['ID']){
                    $paid = 'N';
                    foreach ($pay['PROPS'] as $item) {
                    if($item['NAME'] == GetMessage(self::MODULE_ID .'CC_BSC1_1C_PAYED') && $item['VALUE']){
                        $paid = 'Y';
                    }
                }
                    $pays['TRAITS'] = Array
                    (
                        'VERSION_1C' => $pay['VERSION'],
                        'SUM' => $pay['SUM'],
                        'PAY_VOUCHER_NUM' => $pay['NUMBER'],
                        'PAY_VOUCHER_DATE' => \Bitrix\Main\Type\Date::createFromPhp(new \DateTime($pay['DATE'])),
                        'PAY_SYSTEM_ID' => $payId,//$payId,
                        'PAID' => $paid,
                        'ID_1C' => $pay['ID'],
                        'COMMENTS' => '',
                        'CURRENCY' => $pay['CURRENCY']
                    );
                }
            }
        }

        $ship = array();
        $ship['ITEMS'] = $params['ITEMS'];
        foreach ($deliverys as $deliv) {
            if ($deliv['ORDER_XML_ID'] == $document['ID']) {
                $allow = 'N';
                foreach ($deliv['PROPS'] as $ite) {
                    if($ite['NAME'] == GetMessage(self::MODULE_ID .'CC_BSC1_DEDUCTED') && $ite['VALUE']){
                       $allow = 'Y';
                    }
                }
                $ship['TRAITS'] = Array(
                    'ALLOW_DELIVERY' => $allow,
                    'DEDUCTED' => $allow,
                    'DELIVERY_ID' => Delivery\Services\EmptyDeliveryService::getEmptyDeliveryServiceId(),
                    'DELIVERY_DOC_DATE' => \Bitrix\Main\Type\Date::createFromPhp(new \DateTime($deliv['DATE'])),
                    'COMMENTS' => '',
                    'VERSION_1C' => $deliv['VERSION'],
                    'ID_1C' => $deliv['ID'],
                );
            }
        }
        $shipmentadd = new Bitrix\Sale\Exchange\Entity\ShipmentImport();
        $paymentadd = new Bitrix\Sale\Exchange\Entity\PaymentImport();
        $paymentadd->load(array('ORDER_ID'=>$add->getId()));
        $shipmentadd->load(array('ORDER_ID'=>$add->getId()));
        $isPayment = $paymentadd->add($pays);
        if($isPayment->isSuccess()){
            $paymentadd->save();
        }
        $isShipment = $shipmentadd->add($ship);
        if($isShipment->isSuccess()){
           $shipmentadd->save();
            Bitrix\Sale\Internals\ShipmentTable::update(
                $shipmentadd->getId(),
                 array(
                     'PRICE_DELIVERY'=>NULL,
                       'MARKED'=>'N',
                     'DATE_MARKED'=> NULL,
                     'EMP_MARKED_ID'=>NULL,
                     'DISCOUNT_PRICE'=>NULL,
                     'EXTERNAL_DELIVERY'=>'Y',
                     'UPDATED_1C'=>'N')
                 );
        }
        CSaleOrder::StatusOrder($add->getId(), "F");
        return $add->getId();

    }

    function checkFileRequest(){
        CModule::includeModule('sale');
        $creat_profile = COption::GetOptionString('data.tools1c' , "ON_ADD_USER_CREATE_PROFILE");
        if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/upload/1c_tools/')) {
            if (!mkdir($_SERVER["DOCUMENT_ROOT"].'/upload/1c_tools/')) {
                die("Error creating directory");
            }
        }

        if($_GET['type'] == 'sale' && $_GET['mode'] == 'query'){
            $orders = OrderTable::GetList();
            $ids = array();
            while($order = $orders->fetch()){
                $ids[] = $order['ORDER_ID'];
            }
            static::order_update_null($ids);

        }
        if ($_REQUEST['type'] == 'sale' && $_REQUEST['mode'] == 'import' && (strstr($_GET['filename'], 'documents') || strstr($_GET['filename'], 'contragents')) ) {
            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/1c_tools/'.$_GET['filename'], file_get_contents($_SERVER['DOCUMENT_ROOT'].'/upload/1c_exchange/'.$_GET['filename']));
        }
//        global $APPLICATION;
//        $is_update_profile = COption::GetOptionString('data.tools1c' , "IS_UPDATE_PROFILE");
        //#update#  $APPLICATION->GetCurPage(false) == '/personal/order/make/'
        if(isset($_GET['ORDER_ID']) && !empty($_GET['ORDER_ID']) /*&& $is_update_profile == 'Y'*/){
            $inn_id = self::getInnId();
            $id = (int)$_GET['ORDER_ID'];
            $inn = '';
            $order = \Bitrix\Sale\Order::load($id);
            if($order){
                $propertyCollection = $order->getPropertyCollection();
                $prop = $propertyCollection->getArray();
                $value = '';
                foreach ($prop['properties'] as $item) {
                    foreach ($inn_id['COMPANY_VALUE'] as $inns){
                        if($item['ID'] == $inns){
                            $value = $item['VALUE'][0];
                            $inn = $inns;
                        }
                    }

                }
                if($value != '' && $inn != ''){
                    $is = ProfileTable::GetList(array('filter'=>array('INN'=>$value)));
                    if(!$i = $is->fetch()){
                        $db_vals = CSaleOrderUserPropsValue::GetList(array(), array("ORDER_PROPS_ID" => $inn, "VALUE"=>$value));
                        while($arVals = $db_vals->Fetch()){
                            ProfileTable::add(array('USER_ID'=>$order->getField('USER_ID'), 'PROFILE_ID'=> $arVals['USER_PROPS_ID'], 'INN'=>$value, 'PROFILE_TYPE'=>$arVals['PROP_PERSON_TYPE_ID']));
                        }
                    }
                }
            }
        }
        if ($_REQUEST['type'] == 'catalog' && $_REQUEST['mode'] == 'file' && strstr($_GET['filename'], 'Reports')) {
            $path = $_SERVER['DOCUMENT_ROOT'].'/upload/1c_tools/';
            $orders = OrderTable::GetList();
            while($order = $orders->fetch()){
                $order = \Bitrix\Sale\Order::load($order['ORDER_ID']);
                if($order){
                    if($order->getField('MARKED') == 'Y'){
                        Bitrix\Sale\Internals\OrderTable::update(
                            $order->getId(),
                            array(
                                'MARKED'=>'N'
                            )
                        );
                        $ship = $order->getShipmentCollection();
                        foreach ($ship as $item) {
                            Bitrix\Sale\Internals\ShipmentTable::update(
                                $item->getId(),
                                array(
                                    'PRICE_DELIVERY'=>NULL,
                                    'MARKED'=>'N',
                                    'DATE_MARKED'=> NULL,
                                    'EMP_MARKED_ID'=>NULL,
                                    'DISCOUNT_PRICE'=>NULL,
                                    'EXTERNAL_DELIVERY'=>'Y',
                                    'UPDATED_1C'=>'N')
                            );
                        }
                    }
                }
            }

            $files = scandir($_SERVER['DOCUMENT_ROOT'].'/upload/1c_tools');
            foreach ($files as $file) {
                if($file != '.' && $file !='..'){
                    if(strstr($file, 'documents')){
                        static::events('documents', $path.$file);
                    }
                    if(strstr($file, 'contragents') && $creat_profile == 'Y'){
                        static::events('contragents', $path.$file);
                    }
                    $delete = $path.$file;
                unlink($delete);
                }
            }
        }
    }
    static function update_table_profile($agent = false){
        CModule::includeModule('sale');
        $inn_id = self::getInnId();
        $db_vals = CSaleOrderUserPropsValue::GetList(array(), array("ORDER_PROPS_ID" => $inn_id['COMPANY_VALUE']));
            while($arVals = $db_vals->Fetch()){
                $USER_PROPS_ID = $arVals['USER_PROPS_ID'];
                $INN = $arVals['VALUE'];
                if($USER_PROPS_ID != ''){
                   $_user =  CSaleOrderUserProps::GetList(array(), array("ID" => $USER_PROPS_ID,));
                    while($user_id = $_user->fetch()){
                        if($INN != ''){
                            $is_isset = ProfileTable::GetList(array('filter'=>array('INN'=>$INN)));
                            if(!$is_isset->fetch()){
                                ProfileTable::add(array('USER_ID'=>$user_id['USER_ID'], 'PROFILE_ID'=>$USER_PROPS_ID, 'INN'=>$INN, 'PROFILE_TYPE'=>$arVals['PROP_PERSON_TYPE_ID']));
                            }else{
                                $error_message = str_replace("#INN#", $INN, GetMessage(self::MODULE_ID.'_ISSET_INN_ERROR'));
                                $error_message = str_replace("#ID#", $user_id['USER_ID'], $error_message);
                                LogHandler::logger($error_message);
                            }
                        }
                    }
                }
            }

        $user_props = CSaleOrderUserProps::GetList(array(), array(), false, false, array('ID'));
        $props_id = array();
        while ($use_pro = $user_props->fetch()){
            $props_id[] = $use_pro['ID'];
        }
        $table_props = ProfileTable::GetList(array('select'=>array('PROFILE_ID', 'ID')));
        while( $table = $table_props->fetch()){
            if(!in_array($table['PROFILE_ID'], $props_id)){
                ProfileTable::delete($table['ID']);
            }
        }
        
            if($agent) return 'CDataToolsEvent::update_table_profile(true);';
    }
    static function getInnId(){
        global $DB;
        $prop = $DB->Query("SELECT * FROM `b_sale_bizval` WHERE `CODE_KEY`='BUYER_PERSON_INN' OR `CODE_KEY`='BUYER_COMPANY_INN'");
        $id = array();
        while($user_prop = $prop->fetch()){
            if($user_prop['CONSUMER_KEY'] == '1C' && $user_prop['PROVIDER_VALUE'] != '' && $user_prop['CODE_KEY'] == 'BUYER_COMPANY_INN'){
                  $id['COMPANY_VALUE'][] = $user_prop['PROVIDER_VALUE'];
            }
            if($user_prop['CONSUMER_KEY'] == '1C' && $user_prop['PROVIDER_VALUE'] != '' && $user_prop['CODE_KEY'] == 'BUYER_PERSON_INN'){
                $id['PERSON_VALUE'][] = $user_prop['PROVIDER_VALUE'];
            }
        }

        return $id;
    }
    static function check_unic_inn_on_order(&$arFields)
    {
        $unic = COption::GetOptionString('data.tools1c', "UNIC_INN");
        if ($unic == 'Y' && !isset($_REQUEST['mode']) && !isset($_REQUEST['type'])) {
            CModule::includeModule('sale');
            $inn_id = self::getInnId();
            foreach ($inn_id['COMPANY_VALUE'] as $item) {
                if(isset($arFields['ORDER_PROP'][$item]) && $arFields['ORDER_PROP'][$item] != ''){
                    $inn = $arFields['ORDER_PROP'][$item];
                    $is_isset = ProfileTable::GetList(array('filter' => array('INN' => $inn)));
                    while ($user_info = $is_isset->fetch()) {
                        if ($user_info['USER_ID'] != $arFields['USER_ID']) {
                            $error_message = str_replace("#INN#", $inn, GetMessage(self::MODULE_ID.'_ISSET_INN_ERROR'));
                            $error_message = str_replace("#ID#", $arFields['USER_ID'], $error_message);
                            LogHandler::logger($error_message);
                            $message = str_replace("#INN#", $inn, GetMessage(self::MODULE_ID.'_ISSET_INN'));
                            echo "<script type=\"text/javascript\">alert(\"".$message."\");</script>";
                            die;
                        }elseif($user_info['USER_ID'] == $arFields['USER_ID']){
                            $person_check = false;
                                if($user_info['PROFILE_TYPE'] != $arFields['PERSON_TYPE_ID']){
                                    $person_check = true;
                                }
                            }
                            if($person_check){
                                $error_message = str_replace("#INN#", $inn, GetMessage(self::MODULE_ID.'_ISSET_INN_ERROR_TYPE'));
                                $error_message = str_replace("#ID#", $arFields['USER_ID'], $error_message);
                                LogHandler::logger($error_message);
                                $message = str_replace("#INN#", $inn, GetMessage(self::MODULE_ID.'_ISSET_INN_TYPE'));
                                echo "<script type=\"text/javascript\">alert(\"".$message."\");</script>";
                                die;
                            }
                    }
                }
            }
        }
    }
//    static function offDublicateInn(&$arUserResult, $request, $arParams){
//        $unic = COption::GetOptionString('data.tools1c', "UNIC_INN");
//        if ($unic == 'Y') {
////            if (isset($_REQUEST['PERSON_TYPE_OLD']) && $arUserResult['PERSON_TYPE_OLD'] == $_REQUEST['PERSON_TYPE_OLD'])
////                $arUserResult['PERSON_TYPE_OLD'] = 0;
//        }
//    }
}

?>