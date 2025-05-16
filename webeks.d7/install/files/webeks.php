<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
// собираем зарегистрированные через RegisterModuleDependences и AddEventHandler обработчики события OnSomeEvent
$rsHandlers = GetModuleEvents("webeks.d7", "OnSomeEvent");
// перебираем зарегистрированные в системы события
while ($arHandler = $rsHandlers->Fetch()) {
    // выполняем каждое зарегистрированное событие по одному
    ExecuteModuleEventEx($arHandler, array(/* параметры которые нужно передать в модуль */));
}
