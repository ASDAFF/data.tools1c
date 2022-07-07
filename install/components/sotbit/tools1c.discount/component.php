<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $USER;
$arGroups = CUser::GetUserGroup($USER->GetId());
$arResult['SUMM'] = 0;
$arResult['DISCOUNT'] = '';
$arFilter = Array(
    "USER_ID" => $USER->GetID(),
);

$db_sales = CSaleOrder::GetList(array(), $arFilter);
while ($ar_sales = $db_sales->Fetch())
{
    $arResult['SUM_PAID'] += $ar_sales['SUM_PAID'];
}
$dbProductDiscounts = CCatalogDiscountSave::GetList($arOrder = array(), $arFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array("ID", "NAME", "CURRENCY"));
$code=CUser::GetByID($USER->GetId())->fetch();
$arResult['CODE']=$code['UF_CODE'];
while ($arProductDiscounts = $dbProductDiscounts->GetNext())
{
    foreach ($arGroups as $group) {
        $rsGroup = CGroup::GetByID($group, "Y");
        $arGroup = $rsGroup->Fetch();
        if($arGroup["NAME"] == $arProductDiscounts['NAME']){
            $rsDiscRanges = CCatalogDiscountSave::GetRangeByDiscount(array('RANGE_FROM' => 'ASC'),array('DISCOUNT_ID' => $arProductDiscounts['ID']));
            while ($arDiscRange = $rsDiscRanges->Fetch())
            {
                $arResult['CURRENCY'] = $arProductDiscounts['CURRENCY'];
                $arResult['CONDITION'][$arProductDiscounts['NAME']][] = $arDiscRange;
                if($arResult['SUM_PAID'] > $arDiscRange['RANGE_FROM']) {
                    switch ($arDiscRange['TYPE']) {
                        case 'P':
                            $type = '%';
                            break;
                        case 'F':
                            $type = $arResult['CURRENCY'];
                            break;
                    }
                    $arResult['DISCOUNT'] = $arDiscRange['VALUE'].$type;
                }
            }
            break;
        }
    }
}

if(!isset($arResult['CONDITION'])){
    $arResult['NULL'] = true;
}
$this->IncludeComponentTemplate();

?>