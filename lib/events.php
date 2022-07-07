<?

namespace Data\Tools1c;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;


Loc::loadMessages ( __FILE__ );
class EventsTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }
    public static function getTableName()
    {
        return 'b_datatools1c_events_hl';
    }
    public static function getMap()
    {
        return array (
            new Entity\IntegerField ( 'ID', array (
                'primary' => true,
                'autocomplete' => true
            ) ),
            new Entity\StringField ( 'EVENTS', array (
                'required' => true,
                'title' => Loc::getMessage ( self::getTableName () . '_EVENTS' )
            ) ),
            new Entity\StringField ( 'FUNCTIONS', array (
                'required' => true,
                'title' => Loc::getMessage ( self::getTableName () . '_FUNCTIONS' )
            ) ),

        );
    }
}
