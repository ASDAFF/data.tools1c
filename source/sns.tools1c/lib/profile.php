<?

namespace Sns\Tools1c;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;


Loc::loadMessages ( __FILE__ );
class ProfileTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }
    public static function getTableName()
    {
        return 'b_snstools1c_profile';
    }
    public static function getMap()
    {
        return array (
            new Entity\IntegerField ( 'ID', array (
                'primary' => true,
                'autocomplete' => true
            ) ),
            new Entity\StringField ( 'USER_ID', array (
                'required' => true,
                'title' => Loc::getMessage ( self::getTableName () . '_USER_ID' )
            ) ),
            new Entity\StringField ( 'PROFILE_ID', array (
                'required' => true,
                'title' => Loc::getMessage ( self::getTableName () . '_PROFILE_ID' )
            ) ),
            new Entity\StringField ( 'INN', array (
                'required' => true,
                'title' => Loc::getMessage ( self::getTableName () . '_INN' )
            ) ),
            new Entity\StringField ( 'PROFILE_TYPE', array (
                'required' => true,
                'title' => Loc::getMessage ( self::getTableName () . '_PROFILE_TYPE' )
            ) ),
        );
    }
}
