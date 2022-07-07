<?

namespace Data\Tools1c;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages ( __FILE__ );
class EventHandler
{
   public function OnOrderAddHundlers(&$arOrder){
        \CDataToolsEvent::check_unic_inn_on_order($arOrder);
    }
}
