<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Спасибо за покупку — АВАНТЕРН");
$APPLICATION->SetTitle("Оплата прошла успешно");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>

<h3 style="margin-top:0">Ваша оплата прошла успешно. <br>Спасибо, что пользуетесь нашими услугами.</h3>

<?global $APPLICATION;
Bitrix\Main\Loader::includeModule('sale');
Bitrix\Main\Loader::includeModule('catalog');
Bitrix\Main\Loader::IncludeModule('iblock');

$cib_id="7"; //задаем ИД информационного блока
//извлекаем логин и пароль из файлf (!важно до символа EOF, т.к. Сбер не игнорирует EOF!)
$sber_user=file_get_contents("/home/bitrix/.metadata/sber", false, null, 0, 15); 
$sber_pass=file_get_contents("/home/bitrix/.metadata/sber", false, null, 16, 8); 
$pay_host = 'https://3dsec.sberbank.ru'; //платежный шлюз (для тестовой и боевой установки они разные!)
//извлекаем в строку все содержимое файла с логином и паролем облачной кассы
$cass_login=file_get_contents('/home/bitrix/.metadata/lifepay', false, null, 0, 11); 
$cass_key=file_get_contents('/home/bitrix/.metadata/lifepay', false, null, 12, 32); //извлекаем в строку все содержимое файла с ключем облачной касс
$site="https://pay.avantern.ru/sberpay"; //сайт на который перенаправляем клиента после оплаты

$el = new CIBlockElement;
$order=$_GET["orderId"]; //извлекаем orderId в системе платежного шлюза из адресной строки

$arResult = Array();

$now = new DateTime();

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields
// фильтр в запросе GetList
$arFilter = Array(
	"IBLOCK_ID"=>$cib_id,
	"PROPERTY_PAY_ID"=>$order
);

$res = CIBlockElement::GetList(Array(), $arFilter, false, array(), $arSelect);
while($ob = $res->GetNextElement())
	{ 
	$arFields = $ob->GetFields();  
	$arProps = $ob->GetProperties();
	if(empty($arProps['UID_NUMBER']['VALUE'])) //дополнительная проверка не печатался ли чек ранее по данному заказу
		{
        $q_Url = $pay_host.'/payment/rest/getOrderStatusExtended.do?';
        $q_Data = array(
            'userName' => $sber_user,
            'password' => $sber_pass,
            'orderId' => $order
        );
                
        $result = file_get_contents($q_Url.http_build_query($q_Data), false, stream_context_create(array(
            'http' => array(
            'method'  => 'GET',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($q_Data)
        )           
    )));
                
    $orderStatus = json_decode($result);
		
    $arResult["status"] = $orderStatus->actionCode; //код ответа о состояние платежа от платежного шлюза
    $arResult["stat_desc"] = $orderStatus->errorMessage; //сообщение об ошибке или "успешно" в случае actionCode=0
    $arResult["email"] = $orderStatus->payerData->email; //email, который ввел пользователь на странице платежного шлюза (по умолчанию совпадает с email из формы оплаты)
    $arResult["order_id"] = $orderStatus->orderNumber; //id заказа в системе сайта
    $arResult["client_ip"]= $orderStatus->ip; //ip клиента
	
    $arResult["amount"] = $arProps['SUMM']['VALUE']; //значение суммы из соответсвующего элемента инфоблока
    $arResult["description"] = $arFields['NAME']; //описание заказа из имени элемента инфоблока

// расскоментировать блок при переходе на боевой режим! --start
    /*
		if($arResult["status"]=="0") //если оплата прошла успешно - формируем чек
    		{
			//выбираем страницу на которую необходимо отправить запрос
			$url1 = 'https://sapi.life-pay.ru/cloud-print/create-receipt';
			//параметры которые необходимо передать
			$params1 = array(
    			'apikey' => $cass_key,
    			'login' => $cass_login,
    			'purchase' =>array(
				'products'=>array([
					'name'=>$arResult["description"],
					'price'=>$arResult["amount"],
					'quantity'=>1,
					'tax'=>'none',
					'unit'=>'piece'
					])),
				'test'=>0,
				'mode'=>'email',
				'customer_email'=>$arResult["email"],
				'type'=>'payment',
				'card_amount'=>'#',
				'ext_id'=>$arResult["order_id"],
				'pos'=>array
					(
					'perform'=>0,
					'slip_count'=>0
					),
				'tax_system'=>'usn15'
				);
			$result1 = file_get_contents($url1, false, stream_context_create(array(
    			'http' => array(
        			'method'  => 'POST',
        			'header'  => 'Content-type: application/json',	
        			'content' => json_encode($params1)
    				)
			)));
 			$result1 = json_decode($result1); 
			$name=time();
			$UID_NUMBER=$result1->data->uuid;
			$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*");
			$arFilter = Array("IBLOCK_ID"=>$cib_id, PROPERTY_PAY_ID"=>$order);
			$res1 = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
			while($ob1 = $res1->GetNextElement())
				{
 				$arFields = $ob1->GetFields();
 				CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $UID_NUMBER, "UID_NUMBER");
				CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["status"], "STATUS");
                CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["stat_desc"], "STAT_DESC");
                CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["client_ip"], "CLIENT_IP");
				// Обновляем статус элемента на Активный
 				$ob11 = new CIBlockElement();
 				$ob11->Update($arFields["ID"], ['ACTIVE' => 'Y']);
				}
			logFile($result1);
			header("Refresh: 1; url=$site"); //перенаправляем клиента обратно на сайт
			}
        */
        // расскоментировать блок при переходе на боевой режим! --end

        // заккоментировать блок при переходе на боевой режим! --start
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields
        $arFilter = Array(
	        "IBLOCK_ID"=>$cib_id,
	        "PROPERTY_PAY_ID"=>$order
        );
        $res1 = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
		while($ob1 = $res1->GetNextElement())
			{
 			$arFields = $ob1->GetFields();
			CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["status"], "STATUS");
            CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["stat_desc"], "STAT_DESC");
            CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["client_ip"], "CLIENT_IP");
			// Обновляем статус элемента на Активный
 			$ob11 = new CIBlockElement();
 			$ob11->Update($arFields["ID"], ['ACTIVE' => 'Y']);
			}
        // заккоментировать блок при переходе на боевой режим! --end
		}
	}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>