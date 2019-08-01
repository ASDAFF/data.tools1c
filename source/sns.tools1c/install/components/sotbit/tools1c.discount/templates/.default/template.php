<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(isset($arResult['NULL']) && $arResult['NULL']){
    echo GetMessage('NULL');
}else {
    if ($arResult['CODE'])
        echo GetMessage('CODE') . $arResult['CODE'] . '<br>';
    foreach ($arResult['CONDITION'] as $name => $item) {
        echo GetMessage('GROUP'). $name ;
        echo GetMessage('SUM_PAID'). $arResult["SUM_PAID"].' '.$arResult['CURRENCY'];
        echo GetMessage('DISCOUNT_NOW'). $arResult["DISCOUNT"] . '<br>';
        echo GetMessage('CONDITION').'<br>';
        foreach ($item as $condition) {
            switch ($condition['TYPE']) {
                case 'P':
                    $type = '%';
                    break;
                case 'F':
                    $type = $arResult['CURRENCY'];
                    break;
            }
            echo GetMessage('SUM') . $condition['RANGE_FROM'] . GetMessage('DISCOUNT') . $condition['VALUE'] . '' . $type . '<br>';
        }
    }
}
?>