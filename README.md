# Техническая реализация эквайринга на сайтах

---

### Описание логики работы

   1. Клиент заполняет форму оплаты на сайте и нажимает **оплатить**
   
   2. На сайте создается неактивный инфоблок. **ID** инфоблока на сайте *https://pay.avantern.ru* имеет номер **6**, на сайте *https://avtt.ru* - **15**
   
   3. Клиент перенаправляется на платежный шлюз, где вносит и подтверждает данные карты
   
   4. Платежный шлюз отправляет данные в банк, проводит 3DS при необходимости и возвращает статус платежа
   
       > - если платеж прошел успешно, клиент переходит на шаг **4**
    
       > - если в платеже отказано - платежный шлюз сообщает об этом клиенту, а также выводит причину отказа. Редиректов со страницы оплаты ***не происходит***
  
   1. Платежный шлюз редиректит клиента обратно на сайт
   
   2. На сайте запускается скрипт [index.php](https://github.com/Avantern-LLC/sites-payment/blob/release/pay.avantern/index.php)
   
   3. В скрипт передается **id заказа**, полученный из адресной строки (id заказа формируется автоматически функцией *time()*)
   
   4. Скрипт находит элемент инфоблока с данным id и посредством запроса *GetAdvancedStatus* получает подтверждение оплаты
   
   5.  Скрипт отправляет запрос облачной кассе на формирование чека, в ответ получает *UUID принтера*
   
   6.  Статус платежа записывается в свойство элемента инфоблока *STATUS*, UUID принтера - в *UID_NUMBER*
   
   7.  Инфоблок становится активным


<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAIAAAGuqymWAAAACXBIWXMAAA7DAAAOwwHHb6hkAAACiElEQVR4nGVTzUsbQRQf4nrwAxEVlFYi6qEF2wq1JmmsDShIW4u2KFZCCxLFixdp794UD2LwT/Ai9NBDA6lJC8b6tSY1W9etm01c89WQNLHBbIRCiyZ90xnWmA5vhje/935v3scuOjs72+3pyWazCHbG6fRqm7D2wW5XFAVrwZ0d5NPp9xBCgiCwLItBAHIXF3AGHA4EIcDKlZfDSQNxCwveoSF8Ye8bwS08OQknjiPe7QA3vrERbc7OggbylWFQMplcd7mcq6uyLOMAsPxdD8BfFe7ZcxwPHlOh35FI4NFjonvutGOeUFZGgkrd3UQB8ZrN2JZKpWxTU3xpKX22quqQ42iVEPa7JH15+cptMh3MzBCEFrPx+k1hIn694SQeBzP6EQpR1GDI5/PU3N6OedtjYypDtYFg2+feXnI50DadKwpOp6REvNeZisXQ1vy8mndofFzVaS58RQW579fXE2VnZITajo+OtmtrackajXNwMJFI4DzxFINBt9v90eGAwbrW1nieBxvl0aUoHquVe2gKGLsKy5X0Bp/ewE1P//xHIAuR7riXloqmFOh/GnjS7ysA/Tq9d2KC0mCzi4vyVQ7IeSYDTTp+MXoF79TxFgstYKOvr4gD8ksQcrncQXMzf+260NIqdnQQ3NvSgnsJ1M3h4cNbt6G3327c3K+r29NocF8tlvjcnNpaKgyzrdVmyecQl+X1hoYij/TKyp+TkyKQr6wUbTaaJO5iJuMwm9Ux/S/7DLNlNCaj0ctOnp6eRqNRnyh6PB7b8vLb0VF7W5uzpuZTdbWjtfX9wMA7q3WXZeH3C4fD6XT6cm6EDB92LBY7lmW/3+/zQRxRkiS4RiIR+K/AQXX+C0EGOyw44LvrAAAAAElFTkSuQmCC"/> 

#### **ВАЖНО**:<br>

Если клиент после успешной оплаты закрывает страницу платежного шлюза, не дождавшись перехода обртано на сайт, получает отказ от банка в платежном шлюзе или просто закрывает сессию браузера - скрипт [index.php](https://github.com/Avantern-LLC/sites-payment/blob/release/pay.avantern/index.php) не отрабатывает.<br>

В таком случае по крону запускается скрипт [check.php](https://github.com/Avantern-LLC/sites-payment/blob/release/pay.avantern/check.php)

---

### Описание логики работы [check.php](https://github.com/Avantern-LLC/sites-payment/blob/release/pay.avantern/check.php)

   1. Из инфоблока выбираются все **неактивные** элементы, **изменные** в течении последних нескольких дней (задается в *$arFilter*) и формируются в массив
   
   2. В цикле по каждому элементу, у которого значение свойства *UID_NUMBER* отсутсвует (т.е. чек не был сформирован), в платежный шлюз отправляется запрос *GetAdvancedStatus* с **id заказа**, полученного из значения свойства элемента инфоблока *NUMBER_PAY*
   
   3. На основании полученного от платежного шлюза статуса выполняется следующее:
   
       > * Статус платежа - успешно (**charged**)

       >>  1. Отправляется запрос на формирование чека в облачную кассу, в ответ получает *UUID принтера*

       >>  2. Статус платежа записывается в свойство элемента инфоблока *STATUS*, UUID принтера - в *UID_NUMBER*

       >>  3. Инфоблок становится **активным**

       > * Статус платежа - отклонено (**Rejected**)

       >>  1. Статус платежа записывается в свойство элемента инфоблока *STATUS*, а в *UID_NUMBER* записывается **payment_rejected**

       >>  2. Инфоблок становится **активным**

       > * Платежный шлюз не вернул статус (платеж с таким *order_id* не найден)

       >>  1. В *UID_NUMBER* записывается **payment_not_found**

       >>  2. Инфоблок становится **активным**

       > * Платежный шлюз вернул любой другой статус (не **charged** и не **Rejected**)

       >>  1. Статус платежа записывается в свойство элемента инфоблока *STATUS*

       >>  2. Инфоблок остается **деактивированным**, чтобы попасть в слудующий запуск крона (особенно полезно при статусе платежа **pending**)
   
### *Notice*

  > - Скрипт [check.php](https://github.com/Avantern-LLC/sites-payment/blob/release/pay.avantern/check.php) также полезен в том случае, если в момент совершения оплаты не удалось выполнить фискализацию, чек не был сформирован (например, если ФН не был доступен)

  > - Если по какой-либо причине требуется повторно обработать какой-либо старый элемент - необходимо его деактивировать и очистить значение свойства *UID_NUMBER*

---