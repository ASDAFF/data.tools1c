<?
$iModuleID  = 'data.tools1c'; 
$MESS[$iModuleID."_ERROR_PROPER"] = 'Данное свойство создано модулем "1С инструменты", запрещено изменять тип свойства'; 
$MESS[$iModuleID."_ERROR_DISCONT"] = 'Данная скидка создана модулем "1С инструменты" в ней запрещено менять тип и значение скидки.<br />Для редактирования этих данных удалить связь скидки с модулем "1С инструменты"'; 
$MESS[$iModuleID."_DISCONT_NAME"] = 'Скидка (создано при выгрузке из 1С)';
$MESS[$iModuleID."_AVAILABLE"] = "Товар в наличии";
$MESS[$iModuleID."_CML2_ATTRIBUTES"] = "Характеристики";
$MESS[$iModuleID."_Y"] = "Да";
$MESS[$iModuleID."_N"] = "Нет";
$MESS[$iModuleID."_PricePerUnit"] = 'ЦенаЗаЕдиницу';
$MESS[$iModuleID."_CURRENCY"] = 'руб.';
$MESS[$iModuleID."_ORDER_CODE"] = 'Код карты';
$MESS[$iModuleID."_ERROR_ORDER"] = 'Данный заказ создан модулем "1С инструменты" его запрещено удалять. <br /> Для удаления заказа удалите связь с модулем "1С инструменты" ';
$MESS[$iModuleID."_FULL_NAME"] = 'ПолноеНаименование';
$MESS[$iModuleID."_OFF_NAME"] = 'ОфициальноеНаименование';
$MESS[$iModuleID."_ROLE"] = 'Роль';
$MESS[$iModuleID."_INN"] = 'ИНН';
$MESS[$iModuleID."_KPP"] = 'КПП';
$MESS[$iModuleID."_CODE_PO_OKPO"] = 'КодПоОКПО';
$MESS[$iModuleID."_ADDRESS"] = 'Адрес';
$MESS[$iModuleID."_PERFORMACE"] = 'Представление';
$MESS[$iModuleID."_COUNTRY"] = 'Страна';
$MESS[$iModuleID."_ADDRESS_FIELD"] = 'АдресноеПоле';
$MESS[$iModuleID."_CONTACTS"] = 'Контакты';
$MESS[$iModuleID."_CONTACT"] = 'Контакт';
$MESS[$iModuleID."_PAY_SYSTEM"] = 'Внутренний счет';
$MESS[$iModuleID."_PHONE"] = 'Телефон рабочий';
$MESS[$iModuleID."_MAIL"] = 'Электронная почта';
$MESS[$iModuleID."_STOCKS"] = 'Склады';
$MESS[$iModuleID."_STOCK"] = 'Склад';
$MESS[$iModuleID."_CURRENCY"] = 'Валюта';
$MESS[$iModuleID."_CUR"] = 'Курс';
$MESS[$iModuleID."_SUM"] = 'Сумма';
$MESS[$iModuleID."_COMMENT"] = 'Комментарий';
$MESS[$iModuleID."_TAXES"] = 'Налоги';
$MESS[$iModuleID."_TAXE"] = 'Налог';
$MESS[$iModuleID."_NAME"] = 'Наименование';
$MESS[$iModuleID."_IN_SUM"] = 'УчтеноВСумме';
$MESS[$iModuleID."_PROPS_VALUE"] = 'ЗначенияРеквизитов';
$MESS[$iModuleID."_PROP_VALUE"] = 'ЗначениеРеквизита';
$MESS[$iModuleID."_GOODS"] = 'Товары';
$MESS[$iModuleID."_GOOD"] = 'Товар';
$MESS[$iModuleID."_RATE"] = 'Ставка';
$MESS[$iModuleID."_COUNT"] = 'Количество';
$MESS[$iModuleID."_PRICE"] = 'Цена';
$MESS[$iModuleID."_COF"] = 'Коэффициент';
$MESS[$iModuleID."_YOUR"] = 'Юридическое лицо';
$MESS[$iModuleID."_FIZ"] = 'Физическое лицо';
$MESS[$iModuleID."CC_BSC1_ERROR_AUTHORIZE"] = "Ошибка авторизации. Неверное имя пользователя или пароль.";
$MESS[$iModuleID."CC_BSC1_PERMISSION_DENIED"] = "У Вас нет прав для обмена. Проверьте настройки компонента.";
$MESS[$iModuleID."CC_BSC1_ERROR_MODULE"] = "Модуль Интернет магазина не установлен.";
$MESS[$iModuleID."CC_BSC1_ERROR_HTTP_READ"] = "Ошибка чтения HTTP данных.";
$MESS[$iModuleID."CC_BSC1_ERROR_UNKNOWN_COMMAND"] = "Неизвестная команда.";
$MESS[$iModuleID."CC_BSC1_NO_ORDERS_IN_IMPORT"] = "В CML не найдены заказы.";
$MESS[$iModuleID."CC_BSC1_ERROR_DIRECTORY"] = "Ошибочный параметр - временный каталог.";
$MESS[$iModuleID."CC_BSC1_ERROR_FILE_WRITE"] = "Ошибка записи в файл #FILE_NAME#.";
$MESS[$iModuleID."CC_BSC1_ERROR_FILE_OPEN"] = "Ошибка открытия файла #FILE_NAME# для записи.";
$MESS[$iModuleID."CC_BSC1_ERROR_INIT"] = "Ошибка инициализации временного каталога.";
$MESS[$iModuleID."CC_BSC1_ERROR_EXCHANGE_1C_ORDER_DEDUCTED"] = "При обмене с 1С из документа заказа получен статус полной отгрузки заказа. При этом от 1С не были получены все документы отгрузок в статусе Отгружен. Код ошибки - ";
$MESS[$iModuleID."CC_BSC1_ZIP_ERROR"] = "Ошибка распаковки архива.";
$MESS[$iModuleID."CC_BSC1_EMPTY_CML"] = "Файл для импорта пуст.";
$MESS[$iModuleID."CC_BSC1_PRODUCT_NOT_FOUND"] = "Товар в каталоге сайта не найден для заказа №";
$MESS[$iModuleID."CC_BSC1_UNZIP_ERROR"] = "Распаковка на сайте невозможна. Отправьте не запакованный файл.";
$MESS[$iModuleID."CC_BSC1_FINAL_NOT_EDIT"] = "Заказ №#ID# не может быть изменен (находится в финальном статусе, оплачен или разрешена доставка).";
$MESS[$iModuleID."CC_BSC1_ORDER_NOT_FOUND"] = "Заказ №#ID# на сайте не найден.";
$MESS[$iModuleID."CC_BSC1_ORDER_ERROR_1"] = "Для документа со стороны 1С не передан уникальный идентификатор - 'Ид'. Документ не может быть обработан. ";
$MESS[$iModuleID."CC_BSC1_ORDER_ERROR_2"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Ошибка обработки заказа ";
$MESS[$iModuleID."CC_BSC1_ORDER_ERROR_3"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Произошла ошибка обновления заказа: ";
$MESS[$iModuleID."CC_BSC1_ORDER_ERROR_4"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#.";
$MESS[$iModuleID."CC_BSC1_ORDER_ERROR_5"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Валюта заказа #CURRENCY_FROM# отличается от валюты сайта #CURRENCY_TO#";
$MESS[$iModuleID."CC_BSC1_PAYMENT_ERROR_1"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Оплата №#ID# для заказа №#ORDER_ID# не может быть изменена т.к. документ основание имеет финальный статус";
$MESS[$iModuleID."CC_BSC1_PAYMENT_ERROR_2"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Оплата №#ID# для заказа №#ORDER_ID# на сайте не найдена";
$MESS[$iModuleID."CC_BSC1_PAYMENT_ERROR_3"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Оплата №#ID# не связана с заказом основанием №#ORDER_ID#";
$MESS[$iModuleID."CC_BSC1_PAYMENT_ERROR_4"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Заказ основание №#ORDER_ID# для оплаты №#ID# на сайте не найден";
$MESS[$iModuleID."CC_BSC1_PAYMENT_ERROR_5"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Заказ основание для оплаты на сайте не найден";
$MESS[$iModuleID."CC_BSC1_PAYMENT_ERROR_6"] = "Для документа со стороны 1С не передан уникальный идентификатор - 'Ид'. Документ не может быть обработан. ";
$MESS[$iModuleID."CC_BSC1_PAYMENT_ERROR_7"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Ошибка выбора платежной системы по умолчанию";
$MESS[$iModuleID."CC_BSC1_PAYMENT_ERROR_8"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Ошибка загрузки заказ основания №#ORDER_ID#";
$MESS[$iModuleID."CC_BSC1_PAYMENT_ERROR_9"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Ошибка удаления. Оплата для заказа №#ORDER_ID# на сайте не найдена";
$MESS[$iModuleID."CC_BSC1_PAYMENT_ERROR_10"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Оплата для заказа №#ORDER_ID# не может быть создана/изменена т.к. документ основание имеет финальный статус";
$MESS[$iModuleID."CC_BSC1_PAYMENT_ERROR_11"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Оплата для заказа №#ORDER_ID# не может быть создана/изменена т.к. документ основание отменен";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_1"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Доставка №#ID# для заказа основания №#ORDER_ID# не может быть изменена т.к. документ основание имеет финальный статус";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_2"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Доставка №#ID# для заказа основания №#ORDER_ID# не может быть изменена т.к. документ отгрузки находиться в статусе Отгружен";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_14"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Доставка №#ID# для заказа основания №#ORDER_ID# не может быть изменена т.к. отгрузка в системе имеет признак системной";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_3"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Доставка №#ID# для заказа основания №#ORDER_ID# на сайте не найдена";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_4"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Заказ основание для доставки №#ID# на сайте не найден";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_5"] = "Для документа с Ид - #XML_1C_DOCUMENT_ID# отгрузка не создана. Создание новых отгрузок не разрешено.";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_6"] = "Для документа со стороны 1С не передан уникальный идентификатор - 'Ид'. Документ не может быть обработан.";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_7"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Ошибка создания заказа";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_8"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. В документе №#ID# в отгрузке указано не допустимое количество товара к отгрузке";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_9"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Для продукта изменилась цена. Данный функционал временно не поддерживается";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_10"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. При изменении количества в позиции отгрузки №#ID# произошла ошибка:";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_11"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. В отгрузке №#ID# указано не допустимое количество товара к отгрузке";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_12"] = "При изменении количества в позиции отгрузки №#ID# произошла ошибка: ";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_13"] = "При изменении количества в позиции отгрузки №#ID# произошла ошибка:";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_15"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Ошибка загрузки заказ основания №#ORDER_ID#";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_16"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Ошибка удаления. Отгрузка для заказа №#ORDER_ID# на сайте не найдена";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_17"] = "Для документа с Ид - #XML_1C_DOCUMENT_ID# отгрузка не создана. Для новой отгрузке установлена пометка на удаление";
$MESS[$iModuleID."CC_BSC1_SHIPMENT_ERROR_18"] = "Документ с Ид - #XML_1C_DOCUMENT_ID#. Доставка для заказа основания №#ORDER_ID# не может быть создана/изменена т.к. документ основание имеет финальный статус";
$MESS[$iModuleID."CC_BSC1_PS_ON_STATUS_PAYMENT_ORDER_ERROR"] = "Необходимо выполнить настройку поля 'Платежная система для автоматической оплаты заказа по статусу оплаты заказа от 1С' на странице настройки интеграции";
$MESS[$iModuleID."CC_BSC1_SALE_ALLOW_DEDUCTION_ON_DELIVERY_ERROR"] = "На странице настройки модуля Интернет магазина необходимо отключить автоматизацию процесса - Разрешать отгрузку при разрешении доставки";
$MESS[$iModuleID."CC_BSC1_CONVERT_SALE"] = "Частичные отгрузки доступны только для сконвертированного модуля магазина";
$MESS[$iModuleID."CC_BSC1_USE_STORE_SALE"] = "Необходимо выключить складской учет и резервирование";
$MESS[$iModuleID."CC_BSC1_COM_INFO"] = "КоммерческаяИнформация";
$MESS[$iModuleID."CC_BSC1_COM_INFO_VARSION"] = "ВерсияСхемы";
$MESS[$iModuleID."CC_BSC1_DOCUMENT"] = "Документ";
$MESS[$iModuleID."CC_BSC1_CONTAINER"] = "Контейнер";
$MESS[$iModuleID."CC_BSC1_AGENT"] = "Контрагент";
$MESS[$iModuleID."CC_BSC1_AGENTS"] = "Контрагенты";
$MESS[$iModuleID."CC_BSC1_OPERATION"] = "ХозОперация";
$MESS[$iModuleID."CC_BSC1_ORDER"] = "Заказ товара";
$MESS[$iModuleID."CC_BSC1_PAYMENT_BC"] = "Выплата безналичных денег|Выплата наличных денег";
$MESS[$iModuleID."CC_BSC1_PAYMENT_C"] = "Выплата наличных денег";
$MESS[$iModuleID."CC_BSC1_PAYMENT_B"] = "Выплата безналичных денег";
$MESS[$iModuleID."CC_BSC1_PAYMENT_A"] = "Эквайринговая операция";
$MESS[$iModuleID."CC_BSC1_PAYMENT_COMMENTS_1C"] = "Автоматическая оплата заказа по статусу от 1С";
$MESS[$iModuleID."CC_BSC1_SHIPMENT"] = "Отпуск товара";
$MESS[$iModuleID."CC_BSC1_NUMBER"] = "Номер";
$MESS[$iModuleID."CC_BSC1_NUMBER_BASE"] = "Основание";
$MESS[$iModuleID."CC_BSC1_SUMM"] = "Сумма";
$MESS[$iModuleID."CC_BSC1_COMMENT"] = "Комментарий";
$MESS[$iModuleID."CC_BSC1_REK_VALUES"] = "ЗначенияРеквизитов";
$MESS[$iModuleID."CC_BSC1_REK_VALUE"] = "ЗначениеРеквизита";
$MESS[$iModuleID."CC_BSC1_NAME"] = "Наименование";
$MESS[$iModuleID."CC_BSC1_VALUE"] = "Значение";
$MESS[$iModuleID."CC_BSC1_ITEMS"] = "Товары";
$MESS[$iModuleID."CC_BSC1_ITEM"] = "Товар";
$MESS[$iModuleID."CC_BSC1_PRICE_PER_UNIT"] = "ЦенаЗаЕдиницу";
$MESS[$iModuleID."CC_BSC1_PRICE_ONE"] = "Цена";
$MESS[$iModuleID."CC_BSC1_QUANTITY"] = "Количество";
$MESS[$iModuleID."CC_BSC1_PROPS_ITEMS"] = "ХарактеристикиТовара";
$MESS[$iModuleID."CC_BSC1_PROP_ITEM"] = "ХарактеристикаТовара";
$MESS[$iModuleID."CC_BSC1_PROP_BASKET"] = "СвойствоКорзины";
$MESS[$iModuleID."CC_BSC1_ITEM_TYPE"] = "ТипНоменклатуры";
$MESS[$iModuleID."CC_BSC1_ITEM_UNIT"] = "Единица";
$MESS[$iModuleID."CC_BSC1_ITEM_UNIT_NAME"] = "НаименованиеПолное";
$MESS[$iModuleID."CC_BSC1_ITEM_UNIT_CODE"] = "Код";
$MESS[$iModuleID."CC_BSC1_ID"] = "Ид";
$MESS[$iModuleID."CC_BSC1_TAXES"] = "Налоги";
$MESS[$iModuleID."CC_BSC1_TAX"] = "Налог";
$MESS[$iModuleID."CC_BSC1_TAX_VALUE"] = "Ставка";
$MESS[$iModuleID."CC_BSC1_IN_PRICE"] = "УчтеноВСумме";
$MESS[$iModuleID."CC_BSC1_SERVICE"] = "Услуга";
$MESS[$iModuleID."CC_BSC1_CANCELED"] = "ПометкаУдаления";
$MESS[$iModuleID."CC_BSC1_CANCEL"] = "Отменен";
$MESS[$iModuleID."CC_BSC1_PROPERTY_VALUES"] = "ЗначенияСвойств";
$MESS[$iModuleID."CC_BSC1_PROPERTY_VALUE"] = "ЗначенияСвойства";
$MESS[$iModuleID."CC_BSC1_CASHBOX_CHECKS"] = "ИнформацияОЧеках";
$MESS[$iModuleID."CC_BSC1_CASHBOX_CHECK"] = "ИнформацияОЧеке";
$MESS[$iModuleID."CC_BSC1_CASHBOX_PRINT_CHECK"] = "PRINT_CHECK";
$MESS[$iModuleID."CC_BSC1_CASHBOX_URL"] = "URL";
$MESS[$iModuleID."CC_BSC1_CASHBOX_FISCAL_SIGN"] = "FISCAL_SIGN";
$MESS[$iModuleID."CC_BSC1_CASHBOX_REG_NUMBER_KKT"] = "REG_NUMBER_KKT";
$MESS[$iModuleID."CC_BSC1_1C_PAYED_DATE"] = "Дата оплаты по 1С";
$MESS[$iModuleID."CC_BSC1_1C_PAYED"] = "Оплачен";
$MESS[$iModuleID."CC_BSC1_1C_RETURN"] = "ПризнакВозврата";
$MESS[$iModuleID."CC_BSC1_1C_RETURN_REASON"] = "Причина к Возврату";
$MESS[$iModuleID."CC_BSC1_1C_PAYED_NUM"] = "Номер оплаты по 1С";
$MESS[$iModuleID."CC_BSC1_1C_DELIVERY_DATE"] = "Дата отгрузки по 1С";
$MESS[$iModuleID."CC_BSC1_1C_DELIVERY_NUM"] = "Номер отгрузки по 1С";
$MESS[$iModuleID."CC_BSC1_1C_TRACKING_NUMBER"] = "Идентификатор отправления";
$MESS[$iModuleID."CC_BSC1_PAY_SYSTEM_ID"] = "Метод оплаты ИД";
$MESS[$iModuleID."CC_BSC1_DELIVERY_SYSTEM_ID"] = "Метод доставки ИД";
$MESS[$iModuleID."CC_BSC1_VERSION_1C"] = "НомерВерсии";
$MESS[$iModuleID."CC_BSC1_DEDUCTED"] = "Отгружен";
$MESS[$iModuleID."CC_BSC1_ID_1C"] = "Номер1С";
$MESS[$iModuleID."CC_BSC1_1C_DATE"] = "Дата1С";
$MESS[$iModuleID."CC_BSC1_1C_TIME"] = "Время";
$MESS[$iModuleID."SALE_EXPORT_FORM_SUMM"] = "ФорматСуммы";
$MESS[$iModuleID."SALE_EXPORT_FORM_QUANT"] = "ФорматКоличества";
$MESS[$iModuleID."SALE_EXPORT_FORM_CRD"] = "ЧРД";
$MESS[$iModuleID."CC_BSC1_DISCOUNTS"] = "Скидки";
$MESS[$iModuleID."CC_BSC1_DISCOUNT"] = "Скидка";
$MESS[$iModuleID."CC_BSC1_DISCOUNT_PERCENT"] = "Процент";
$MESS[$iModuleID."CC_BSC1_ZIP_PROGRESS"] = "Идет распаковка архива.";
$MESS[$iModuleID."CC_BSC1_ZIP_DONE"] = "Распаковка архива завершена.";
$MESS[$iModuleID."CC_BSC1_ERROR_SOURCE_CHECK"] = "Ошибка проверки источника запроса. Обновите модуль обмена.";
$MESS[$iModuleID."CC_BSC1_ERROR_SESSION_ID_CHANGE"] = "Включена смена идентификатора сессий. В файле подключения компонента обмена, до подключения пролога определите константу BX_SESSION_ID_CHANGE: define('BX_SESSION_ID_CHANGE', false);";
$MESS[$iModuleID."CC_BSC1_ORDER_NO_AGENT_ID"] = "Данные контрагента для создания заказа №#ID# не найдены. Заказ не будет создан.";
$MESS[$iModuleID."CC_BSC1_ORDER_ADD_PROBLEM"] = "Произошла ошибка при создании заказа №#ID#.";
$MESS[$iModuleID."CC_BSC1_ORDER_USER_PROBLEM"] = "Произошла ошибка регистрации пользователя при создании заказа №#ID#.";
$MESS[$iModuleID."CC_BSC1_ORDER_PERSON_TYPE_PROBLEM"] = "Не смогли определить тип плательщика при создании заказа №#ID#.";
$MESS[$iModuleID."CC_BSC1_ORDER_BASKET_ITEMS_PROBLEM"] = "Табличная часть заказа не содержит позиций заказа с типом Товар";
$MESS[$iModuleID."CC_BSC1_ORDER_BASKET_ITEMS_AMOUNT_NULL_PROBLEM"] = "Табличная часть заказа не содержит позиций заказа с типом Товар или Сумма документа равна 0";
$MESS[$iModuleID."CC_BSC1_AGENT_NO_AGENT_ID"] = "Данные контрагента не найдены.";
$MESS[$iModuleID."CC_BSC1_AGENT_USER_PROBLEM"] = "Произошла ошибка регистрации пользователя при создании контрагента №#ID#.";
$MESS[$iModuleID."CC_BSC1_AGENT_DUPLICATE"] = "Контрагент №#ID# уже существует на сайте.";
$MESS[$iModuleID."CC_BSC1_AGENT_PERSON_TYPE_PROBLEM"] = "Не смогли определить тип плательщика при создании контрагента №#ID#.";
$MESS[$iModuleID."CC_BSC1_DI_GENERAL"] = "Справочник";
$MESS[$iModuleID."CC_BSC1_DI_STATUSES"] = "Cтатусы";
$MESS[$iModuleID."CC_BSC1_DI_PS"] = "ПлатежныеСистемы";
$MESS[$iModuleID."CC_BSC1_DI_DS"] = "СлужбыДоставки";
$MESS[$iModuleID."CC_BSC1_DI_ELEMENT"] = "Элемент";
$MESS[$iModuleID."CC_BSC1_DI_ID"] = "Ид";
$MESS[$iModuleID."CC_BSC1_DI_NAME"] = "Название";
$MESS[$iModuleID."CP_BCI1_CHANGE_STATUS_FROM_1C"] = "Менять статусы заказов по информации из 1С";
$MESS[$iModuleID."CC_BSC1_1C_STATUS_ID"] = "Статуса заказа ИД";
$MESS[$iModuleID."_ISSET_INN"] = "Войдите под пользователем чей инн #INN#";
$MESS[$iModuleID."_ISSET_INN_TYPE"] = "Инн #INN# зарегистрирован на другой тип платильщика";
$MESS[$iModuleID."_ISSET_INN_ERROR"] = "Ошибка при создании заказа. Профиль с инн (#INN#) уже существует. Пользователь с ID #ID#";
$MESS[$iModuleID."_ISSET_INN_ERROR_TYPE"] = "Ошибка при создании заказа. Профиль с инн (#INN#) не подходит по типу платильщика. Пользователь с ID #ID#";

?>