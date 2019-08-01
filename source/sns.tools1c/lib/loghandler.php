<?

namespace Sns\Tools1c;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages ( __FILE__ );
class LogHandler
{
    static function logger($message){
        \Bitrix\Main\Loader::includeModule("main");
        \CEventLog::Add(array(
            "SEVERITY" => "SECURITY",
            "AUDIT_TYPE_ID" => "1c_tools",
            "MODULE_ID" => "sns.tools1c",
            "ITEM_ID" => 1,
            "DESCRIPTION" => $message,
        ));
    }
}
