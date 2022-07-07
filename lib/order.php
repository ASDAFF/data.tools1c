<?

namespace Data\Tools1c;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;


Loc::loadMessages ( __FILE__ );
class OrderTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }
    public static function getTableName()
    {
        return 'b_datatools1c_order_id_not_export';
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
                'title' => Loc::getMessage ( self::getTableName () . '_USER_ID' )
            ) ),

        );
    }
}
