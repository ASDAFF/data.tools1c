<?

namespace Sns\Tools1c;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;


Loc::loadMessages ( __FILE__ );
class ChecksTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }
    public static function getTableName()
    {
        return 'b_snstools1c_checks';
    }
    public static function getMap()
    {
        return array (
            new Entity\IntegerField ( 'ID', array (
                'primary' => true,
                'autocomplete' => true
            ) ),
            new Entity\IntegerField ( 'ORDER_ID', array (
                'required' => true,
                'title' => Loc::getMessage ( self::getTableName () . '_EVENTS' )
            ) ),
            new Entity\StringField ( 'CHECK_NUMBER', array (
                'required' => true,
                'title' => Loc::getMessage ( self::getTableName () . '_CHECK_NUMBER' )
            ) ),
            new Entity\StringField ( 'CHECK_NAME', array (
                'required' => true,
                'title' => Loc::getMessage ( self::getTableName () . '_CHECK_NAME' )
            ) ),

        );
    }
}
