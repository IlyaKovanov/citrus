<?
define('NEED_AUTH', true);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("Контакты");
?><?$APPLICATION->IncludeComponent(
	"webeks:feedback.form",
	"",
Array()
);?><?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>