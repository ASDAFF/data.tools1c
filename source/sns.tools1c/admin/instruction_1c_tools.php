<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

$iModuleID = "sns.tools1c";

IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle(GetMessage('1CTOOLS_INSTRUCTION_TITLE')); 

//Проверка прав
$CONS_RIGHT = $APPLICATION->GetGroupRight($iModuleID);   
if ($CONS_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$iModuleID."/include.php");

$image_path = '/bitrix/images/sns.tools1c/instruction/';
$site = 'http://'.$_SERVER['HTTP_HOST'];
?>
<style>
.tools1c_ins_page {
  max-width: 1000px;
}
.tools1c_ins_page img {
  border: 1px solid #95B3B9;
  max-width: 1000px;
}
</style>
<div class="tools1c_ins_page">
    <?=GetMessage('CONTENT_TEXT', array('#image_path#' => $image_path, '#site#' => $site));?>    
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>