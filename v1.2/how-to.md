# 🧾 Что делать на сайте, где что находится

---

1. [*index.php*](https://github.com/Avantern-LLC/sites-payment/blob/release/v1.2/index.php) кладем в директорию страницы оплаты. На [pay.avantern.ru](https://pay.avantern.ru) \- это корень, на [*avtt.ru*](https://avtt.ru) \- это /payment.
2. [*/success/index.php*](https://github.com/Avantern-LLC/sites-payment/blob/release/v1.2/success/index.php), [/success/check.php](https://github.com/Avantern-LLC/sites-payment/blob/release/v1.2/success/check.php) \- соответственно в /success.
3. [*sberpayment.php*](https://github.com/Avantern-LLC/sites-payment/blob/release/v1.2/bitrix/templates/avantern/ajax/sberpayment.php) - по тому же пути (от корня сайта), как он лежит здесь.
4. Гугловая recapcha объявлена в *header.php*, вызывается в *index.php* формы.
5. [/success/check.php](https://github.com/Avantern-LLC/sites-payment/blob/release/v1.2/success/check.php) исполнять **cron**, но не слишком часто. Рекомендуется каждый час, код:  
            `0 */1 * * *     bitrix /usr/bin/php -f /home/bitrix/www/success/check.php`
  
Все данные об оплатах хранятся в элементах инфоблока
  
## 🗃️ Структура инфоблока:
  
| **Свойство** | **Тип** | **Код** | **Описание** |
| ------------ | ------- | ------- | ------------ |
| ФИО | Строка | FIO | ФИО клиента, введенное им в форме оплаты |
| Услуга | Строка | SERVICE| Услуга, которую клиент выбрал в форме оплаты |
| Номер договора | Строка | DOGOVOR | Информация по договору клиента, введенная им в форме оплаты |
| Почта | Строка | EMAIL | Адрес электронной почты клиента, введенный им в форме оплаты. Этот же адрес передается платежному шлюзу |
| Сумма | Строка | SUM | Сумма к оплате клиентом, введенная в форме оплаты |
| ИД заказа | Строка | ORDER_ID | ИД заказа в системе сайта |
| Статус платежа | Строка | STATUS | Статус оплаты, полученный от платежного шлюза |
| Статус детально | Строка | STAT_DESC | Расшифровка статуса оплаты. При успешной оплате отсутсвует |
| UID номер принтера | Строка | UID_NUMBER | Внутренний id чека, полученный от облачной кассы |
| ID в платежном шлюзе | Строка | PAY_ID | Внутренний ID заказа клиента в платежном шлюзе |
| IP устройства клиента | Строка | CLIENT_IP | IP устройства клиента, с которого он совершал оплату на сайте |


---

### Связь с автором:

📧 cio@avanertn.ru  
📧 s.ershov.shoko@gmail.com