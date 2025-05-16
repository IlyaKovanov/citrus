<?
// пространство имен для подключений ланговых файлов
use Bitrix\Main\Localization\Loc;
// пространство имен для управления (регистрации/удалении) модуля в системе/базе
use Bitrix\Main\ModuleManager;
// пространство имен для работы с параметрами модулей хранимых в базе данных
use Bitrix\Main\Config\Option;
// пространство имен с абстрактным классом для любых приложений, любой конкретный класс приложения является наследником этого абстрактного класса
use Bitrix\Main\Application;
// пространство имен для работы c ORM
use \Bitrix\Main\Entity\Base;
// пространство имен для автозагрузки модулей
use \Bitrix\Main\Loader;
// пространство имен для событий
use \Bitrix\Main\EventManager;

// подключение ланговых файлов
Loc::loadMessages(__FILE__);

class Webeks_D7 extends CModule
{
    // переменные модуля
    public  $MODULE_ID;
    public  $MODULE_VERSION;
    public  $MODULE_VERSION_DATE;
    public  $MODULE_NAME;
    public  $MODULE_DESCRIPTION;
    public  $PARTNER_NAME;
    public  $PARTNER_URI;
    public  $SHOW_SUPER_ADMIN_GROUP_RIGHTS;
    public  $MODULE_GROUP_RIGHTS;
    public  $errors;

    // конструктор класса, вызывается автоматически при обращение к классу
    function __construct()
    {
        // создаем пустой массив для файла version.php
        $arModuleVersion = array();
        // подключаем файл version.php
        include_once(__DIR__ . '/version.php');

        // версия модуля
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        // дата релиза версии модуля
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        // id модуля
        $this->MODULE_ID = "webeks.d7";
        // название модуля
        $this->MODULE_NAME = "Модуль интеграции формы обратной связи с Bitrix24";
        // описание модуля
        $this->MODULE_DESCRIPTION = "Модуль интеграции формы обратной связи с Bitrix24";
        // имя партнера выпустившего модуль
        $this->PARTNER_NAME = "Webeks";
        // ссылка на рисурс партнера выпустившего модуль
        $this->PARTNER_URI = "https://example.com";
        // если указано, то на странице прав доступа будут показаны администраторы и группы
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        // если указано, то на странице редактирования групп будет отображаться этот модуль
        $this->MODULE_GROUP_RIGHTS = 'Y';
    }

    // метод отрабатывает при установке модуля
    function DoInstall()
    {
        // глобальная переменная с обстрактным классом
        global $APPLICATION;
        // регистрируем модуль в системе
        ModuleManager::RegisterModule("webeks.d7");
        // копируем файлы, необходимые для работы модуля
        $this->InstallFiles();
        // подключаем скрипт с административным прологом и эпилогом
        $APPLICATION->includeAdminFile(
            Loc::getMessage('INSTALL_TITLE'),
            __DIR__ . '/instalInfo.php'
        );

        // для успешного завершения, метод должен вернуть true
        return true;
    }

    // метод отрабатывает при удалении модуля
    function DoUninstall()
    {

        // глобальная переменная с обстрактным классом
        global $APPLICATION;
        // удаляем файлы, необходимые для работы модуля
        $this->UnInstallFiles();
        // удаляем регистрацию модуля в системе
        ModuleManager::UnRegisterModule("webeks.d7");
        // подключаем скрипт с административным прологом и эпилогом
        $APPLICATION->includeAdminFile(
            Loc::getMessage('DEINSTALL_TITLE'),
            __DIR__ . '/deInstalInfo.php'
        );

        // для успешного завершения, метод должен вернуть true
        return true;
    }

    // метод для копирования файлов модуля при установке
    function InstallFiles()
    {
        // скопируем файлы на страницы админки из папки в битрикс, копирует одноименные файлы из одной директории в другую директорию
        CopyDirFiles(
            __DIR__ . "/admin",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin",
            true, // перезаписывает файлы
            true  // копирует рекурсивно
        );

        // скопируем компоненты из папки в битрикс, копирует одноименные файлы из одной директории в другую директорию
        CopyDirFiles(
            __DIR__ . "/components",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components",
            true, // перезаписывает файлы
            true  // копирует рекурсивно
        );

        // копируем файлы страниц, копирует одноименные файлы из одной директории в другую директорию
        CopyDirFiles(
            __DIR__ . '/files',
            $_SERVER["DOCUMENT_ROOT"] . '/',
            true, // перезаписывает файлы
            true  // копирует рекурсивно
        );

        // для успешного завершения, метод должен вернуть true
        return true;
    }

    // метод для удаления файлов модуля при удалении
    function UnInstallFiles()
    {
        // удалим файлы из папки в битрикс на страницы админки, удаляет одноименные файлы из одной директории, которые были найдены в другой директории, функция не работает рекурсивно
        DeleteDirFiles(
            __DIR__ . "/admin",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin"
        );

        // удалим компонент из папки в битрикс 
        if (is_dir($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/" . $this->MODULE_ID)) {
            // удаляет папка из указанной директории, функция работает рекурсивно
            DeleteDirFilesEx(
                "/bitrix/components/" . $this->MODULE_ID
            );
        }

        // удалим файлы страниц, удаляет одноименные файлы из одной директории, которые были найдены в другой директории, функция не работает рекурсивно
        DeleteDirFiles(
            __DIR__ . "/files",
            $_SERVER["DOCUMENT_ROOT"] . "/"
        );

        // для успешного завершения, метод должен вернуть true
        return true;
    }
}
