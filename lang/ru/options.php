<?
/**
 * Copyright (c) 1/8/2019 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

$MESS["tools.1c_edit1"] = "Общие настройки модуля";
$MESS["tools.1c_edit20"] = "Информация о товаре";
$MESS["tools.1c_edit30"] = "Торговые предложения";
$MESS["tools.1c_OPTION_10"] = "Свойства товара";
$MESS["tools.1c_OPTION_20"] = "Артикул торговых предложений";
$MESS["tools.1c_OPTION_30"] = "Характеристики торговых предложений";
$MESS["tools.1c_OPTION_40"] = "Множественные свойства";
$MESS["tools.1c_OPTION_50"] = "Обновление элементов из 1С";
$MESS["tools.1c_NONE"] = "Не выбрано";

$MESS["tools.1c_CHEXBOX_QUALITY_TITLE"] = "Уменьшать количество при заказе";
$MESS["tools.1c_CHEXBOX_QUALITY_DESCR"] = 'Если включена опция то для всех товаров автоматически будет включена опция "Уменьшать количество при заказе"';
$MESS["tools.1c_INT_QUALITY_DEFAULT_TITLE"] = "Количество товара по умолчанию";
$MESS["tools.1c_INT_QUALITY_DEFAULT_DESCR"] = 'Если поле заполнено то количество будет сохранятся из этого поля, затирая вес по умолчанию';
$MESS["tools.1c_SELECT_VAT_TITLE"] = "Ставка налогов";
$MESS["tools.1c_SELECT_VAT_DESCR"] = 'При выборе налоговай ставки она будет ставится у товаров, если не выбрана то будет ставка по умолчанию';
$MESS["tools.1c_CHEXBOX_VAT_INCLUDED_TITLE"] = "НДС включен в цену";
$MESS["tools.1c_CHEXBOX_VAT_INCLUDED_DESCR"] = 'Если включена опция то для всех товаров автоматически будет включена опция "НДС включен в цену"';


$MESS["tools.1c_CHEXBOX_ARTICLE_TITLE"] = "Артикул в торговые предложения";
$MESS["tools.1c_CHEXBOX_ARTICLE_DESCR"] = 'Если включена опция то артикул из карточки товара будет сохранятся в торговое предложение, удобно для поиска товаров в списке';
$MESS["tools.1c_OFFERS_ARTICLE_TITLE"] = "Символьный код артикула торгового предложения";
$MESS["tools.1c_OFFERS_ARTICLE_DESCR"] = '';
$MESS["tools.1c_CATALOG_ARTICLE_TITLE"] = "Символьный код артикула товара каталога";
$MESS["tools.1c_CATALOG_ARTICLE_DESCR"] = '';
$MESS["tools.1c_OFFERS_LINK_TITLE"] = "Символьный код привязки к элементу каталога";
$MESS["tools.1c_OFFERS_LINK_DESCR"] = '';


$MESS["tools.1c_CHEXBOX_OFFERS_PROPERTIES_TITLE"] = "Включить связь свойств предложений со справочниками";
$MESS["tools.1c_CHEXBOX_OFFERS_PROPERTIES_DESCR"] = 'Что бы характеристики начали связыватся со справочниками, необходимо создать справочники(инфоблоки) с такими же названиями как характеристики и свойства тип "Привязка к элементам" в инфоблоке торгового предложения с таким же названием как характеристика, обязательно задавайте символьный код для свойства';
$MESS["tools.1c_CHEXBOX_OFFERS_PROPERTIES_STRING_TITLE"] = "Выгружать свойства характеристик в отдельные свойства";
$MESS["tools.1c_CHEXBOX_OFFERS_PROPERTIES_STRING_DESCR"] = 'Необходимо просто создать свойство типа "Строка" у торгового предложения с таким же названием как в 1С, при отгрузке значения будут заполняться';
$MESS["tools.1c_OFFERS_ATTRIBUTES_TITLE"] = "Символьный код свойства характеристик";
$MESS["tools.1c_OFFERS_ATTRIBUTES_DESCR"] = '';


$MESS["tools.1c_CHEXBOX_MULTIPROPER_TITLE"] = "Множественные свойства";
$MESS["tools.1c_CHEXBOX_MULTIPROPER_DESCR"] = '';
$MESS["tools.1c_STRING_MULTIPROPER_RAZDEL_TITLE"] = "Разделитель в множественных свойствах";
$MESS["tools.1c_STRING_MULTIPROPER_RAZDEL_DESCR"] = 'Это значение будет разделителем при отгрузке из 1С, если оно будет найдено в свойстве то те значения что будут дальше будут в следующей строке. В 1С необходимо будет создать свойство типа строка и заполнять его так : Черный || Красный || Желтый' ;
$MESS["tools.1c_STRING_MULTIPROPER_ID_TITLE"] = "Символьные код множественного свойства";
$MESS["tools.1c_STRING_MULTIPROPER_ID_DESCR"] = 'Ведите символьные код множественного свойства в котором надо учитывать разделитель, если оно не одно вводите через запятую пример: CML_COLOR,CML_TOOLS . <br />Свойства должны быть множественной строкой. <br />Если нет ни одного значения то будет разделять все свойства.';


$MESS["tools.1c_SELECT_NONE_UPDATE_TITLE"] = "Поля товара (не обновлять)";
$MESS["tools.1c_SELECT_NONE_UPDATE_DESCR"] = "Выберите поля которые не будут обновляться при выгрузке из 1С у существующих товаров";
$MESS["tools.1c_SELECT_NONE_UPDATE_SEL1"] = "Название";
$MESS["tools.1c_SELECT_NONE_UPDATE_SEL2"] = "Картинка для анонса";
$MESS["tools.1c_SELECT_NONE_UPDATE_SEL3"] = "Описание для анонса";
$MESS["tools.1c_SELECT_NONE_UPDATE_SEL4"] = "Детальная картинка";
$MESS["tools.1c_SELECT_NONE_UPDATE_SEL5"] = "Детальное описание";
$MESS["tools.1c_SELECT_NONE_UPDATE_SEL6"] = "Теги";

$MESS["tools.1c_STRING_NONE_UPDATE_PROPER_TITLE"] = "Код свойства товара(не обновлять)";
$MESS["tools.1c_STRING_NONE_UPDATE_PROPER_DESCR"] = 'Ведите код дополнительного свойства элемента который не надо обновлять, если код не один через запятую пример: 2, 34, 44 ';


 
?>