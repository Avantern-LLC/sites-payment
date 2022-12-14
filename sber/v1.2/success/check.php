<? 
$_SERVER["DOCUMENT_ROOT"]="/home/bitrix/www";
ini_set("max_execution_time",2400);
ini_set('memory_limit','100M');
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
Bitrix\Main\Loader::includeModule('sale');
Bitrix\Main\Loader::includeModule('catalog');
Bitrix\Main\Loader::IncludeModule('iblock');
$el = new CIBlockElement;
$cib_id="7"; //задаем ИД информационного блока
//извлекаем логин и пароль из файлf (!важно до символа EOF, т.к. Сбер не игнорирует EOF!)
$sber_user=file_get_contents("/home/bitrix/.metadata/sber", false, null, 0, 15); 
$sber_pass=file_get_contents("/home/bitrix/.metadata/sber", false, null, 16, 8); 
$pay_host = 'https://3dsec.sberbank.ru'; //платежный шлюз (для тестовой и боевой установки они разные!)
//извлекаем в строку все содержимое файла с логином и паролем облачной кассы
$cass_login=file_get_contents('/home/bitrix/.metadata/lifepay', false, null, 0, 11); 
$cass_key=file_get_contents('/home/bitrix/.metadata/lifepay', false, null, 12, 32); 
?>
<?
$arResult = Array(); //массив, в котором сохраняем результаты
//получаем текущую дату и время
$now = new DateTime();
//формируем запрос на формирование массива элементов инфоблока
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields
//выбираем из инфоблока с только неактивные элементы с датой изменения не старше указанного количества дней
$arFilter = Array("IBLOCK_ID"=>$cib_id, ">TIMESTAMP_X"=>$now->modify('-10 day')->format('d.m.Y H:i:s'), "ACTIVE"=>"N");
$res = CIBlockElement::GetList(Array(), $arFilter, false, array(), $arSelect);
	while($ob = $res->GetNextElement())
	{ 
		$arFields = $ob->GetFields();  
		$arProps = $ob->GetProperties();

		if((empty($arProps['UID_NUMBER']['VALUE']))) //если свойство UID_NUMBER (ид чека) не содержит значения - запрашивает статус платежа по ИД-заказа у платежной системы
		{
            $q_Url = $pay_host.'/payment/rest/getOrderStatusExtended.do?';
            $q_Data = array(
                'userName' => $sber_user,
                'password' => $sber_pass,
                'orderNumber' => $arProps['ORDER_ID']['VALUE'],
                'orderId' => $arProps['PAY_ID']['VALUE'] //этот параметр имеет приоритет над orderNumber, если указаны оба
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
            $arResult["stat_desc"] = $orderStatus->actionCodeDescription; //расшифровка кода состояния платежа (пусто, если actionCode=0)
            $arResult["email"] = $orderStatus->payerData->email; //email, который ввел пользователь на странице платежного шлюза (по умолчанию совпадает с email из формы оплаты)
            $arResult["order_id"] = $orderStatus->orderNumber; //id заказа в системе сайта
            $arResult["summ"] = $orderStatus->amount; //оплаченная сумма в копейках
            $arResult["amount"] = $arResult["summ"]/100; //сумма в рублях
            $arResult["client_ip"]= $orderStatus->ip; //ip клиента
		    $arResult["description"] = $arFields['NAME'];

			if($arResult["status"]=="0") //если оплата прошла успешно - формируем чек и записываем его номер в св-во инфоблока
			{
                // расскомментировать этот блок при переводе в боевой режим --start
                /*
				$url1 = 'https://sapi.life-pay.ru/cloud-print/create-receipt';
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
							])
						),
						'test'=>0,
						'mode'=>'email',
						'customer_email'=>$arResult["email"],
						'type'=>'payment',
						'card_amount'=>'#',
						'ext_id'=>$arResult["order_id"],
						'pos'=>array(
							'perform'=>0,
							'slip_count'=>0
							),
						'tax_system'=>'usn15'
					);

				$result = file_get_contents($url1, false, stream_context_create(array(
					'http' => array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/json',	
					'content' => json_encode($params1)
					)
				)));
				$result1 = json_decode($result); 
				$UID_NUMBER=$result1->data->uuid;
                */
                // расскомментироать этот блок при переводе в боевой режим --end
				$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*");
				$arFilter = Array("IBLOCK_ID"=>$cib_id, "PROPERTY_ORDER_ID"=> $arResult["order_id"]);
				$res1 = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
				while($ob1 = $res1->GetNextElement())
				{
					$arFields = $ob1->GetFields();
					CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["status"], "STATUS"); //записываем полученный статус оплаты в свойство элемента инфоблока
                    CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["stat_desc"], "STAT_DESC"); //описание
                    CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["client_ip"], "CLIENT_IP"); //ip устройства клиента
                    // расскрментировать при переводе в боевой режим --start
					//CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $UID_NUMBER, "UID_NUMBER"); //записываем номер сформированного чека
                    // расскомментировать при переводе в боевой режим --end
					// Обновляем статус элемента на Активный
 					$ob11 = new CIBlockElement();
 					$ob11->Update($arFields["ID"], ['ACTIVE' => 'Y']);
				}							
			} elseif($arResult["status"]=="-2007") { //-2007 - истек срок ввода данных карты
				$UID_NUMBER_2="payment_not_found";
                $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*");
				$arFilter = Array("IBLOCK_ID"=>$cib_id, "PROPERTY_ORDER_ID"=> $arResult["order_id"]);
				$res2 = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
				while($ob2 = $res2->GetNextElement())
				{
					$arFields = $ob2->GetFields();
					CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $UID_NUMBER_2, "UID_NUMBER");
                    CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["status"], "STATUS"); //записываем полученный статус оплаты в свойство элемента инфоблока
                    CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["stat_desc"], "STAT_DESC"); //описание
					$ob22 = new CIBlockElement();
 					$ob22->Update($arFields["ID"], ['ACTIVE' => 'Y']);
				}
			} else { // во всех остальных случаях - записываем статус, активность элемента не меняем, чтобы он попал в запрос при следующем запуске скрипта
                $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*");
				$arFilter = Array("IBLOCK_ID"=>$cib_id, "PROPERTY_ORDER_ID"=> $arResult["order_id"]);
				$res3 = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
				while($ob3 = $res3->GetNextElement())
				{
					$arFields = $ob3->GetFields();
					CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["status"], "STATUS"); //записываем полученный статус оплаты в свойство элемента инфоблока
                    CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["stat_desc"], "STAT_DESC"); //описание
                    CIBlockElement::SetPropertyValues($arFields["ID"], $cib_id, $arResult["client_ip"], "CLIENT_IP"); //ip устройства клиента
				}
			}
		}
	}
?>