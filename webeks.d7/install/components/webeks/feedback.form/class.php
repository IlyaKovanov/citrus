<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class FeedbackFormComponent extends CBitrixComponent implements Controllerable
{
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        $this->getResult();
        $this->includeComponentTemplate();
    }

    protected function checkModules()
    {
        if (!Loader::includeModule('iblock'))
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    }

    public function getPost(){
        $request = Application::getInstance()->getContext()->getRequest();
        $post = $request->getPostList()->toArray();
        return $post;
    }

    private function getOption(){

        $op = \Bitrix\Main\Config\Option::get("webeks.d7", "webeks_url", "", false);

        return $op;
    }

    /**
     * @return array
     */
    public function getResult(){
        
    }

    
    public function configureActions()
    {
        return [
            'send' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST]),
                ],
                '-prefilters' => [
                    ActionFilter\Authentication::class,
                ],
                'postfilters' => []
            ],
        ];
    }

    protected function crmLeadAdd($fields){
        // определяем URL 
        $Url = $this->getOption();
        if($Url){
            // описываем параметры  лида 
            $ParamLid = http_build_query(array(
                'fields' => array(
                    'TITLE' => 'Заявка с формы '.$fields['FORM_NAME'],// НАЗВАНИЕ
                    'NAME' => $fields['NAME'],// ИМЯ
                    'COMMENTS' => $fields['MESSAGE'],// ТЕКСТ СООБЩЕНИЯ
                    'PHONE' => Array(
                        "n0" => Array(
                            "VALUE" => $fields['PHONE'],
                            "VALUE_TYPE" => "WORK",
                        )), // РАБОЧИЙ ТЕЛЕФОН в массиве
                    'OPENED' => 'Y', // Доступно для всех
                    'SOURCE_ID' => "WEB", //Источник вебсайт
                    'SOURCE_DESCRIPTION' => 'адрес страницы отправки '.$fields['PAGE_URL'],// доп описание источника
                    'EMAIL' => Array(
                            "n0" => Array(
                                "VALUE" => $fields['EMAIL'],
                                "VALUE_TYPE" => "WORK",
                            ),
                        ), // Рабочая эл. почта
                    // 'UTM_SOURCE' =>  'utm test',// UTM метка
                    'ASSIGNED_BY_ID' => 1, // Ид ответственного
                ),
                'params' => array("REGISTER_SONET_EVENT" => "Y")
            ));
            // обращаемся к сформированному URL при помощи функции curl_exec для создания лида
            $ch = curl_init();
            curl_setopt_array($ch, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $Url,
            CURLOPT_POSTFIELDS => $ParamLid,
            ));
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $result = ['STATUS'=>'error', 'MESSAGE'=>'Не указан url интеграции с битрикс24 в настройках модуля интеграции'];
        }
		
		return $result;
	}

    public function sendAction(){
        $fields = $this->getPost();
        $res = json_decode($this->crmLeadAdd($fields), true);
        
        if(key_exists('error', $res)){
            $result = array("STATUS"=>"error", "MESSAGE"=>$res['error_description']);
            
        } else {
            $result = array("STATUS" => 'success',  "MESSAGE"=>"Сообщение успешно отправлено"); 
        }

        return $result;
    }

}
