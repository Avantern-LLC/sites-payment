# 🧾 Что делать на сайте, где что находится

---

1. [*index.php*](https://github.com/Avantern-LLC/sites-payment/blob/sber/develop/sber/v1.2/index.php) кладем в директорию страницы оплаты. На [pay.avantern.ru](https://pay.avantern.ru) \- это корень, на [*avtt.ru*](https://avtt.ru) \- это /payment.
2. [*/success/index.php*](https://github.com/Avantern-LLC/sites-payment/blob/sber/develop/sber/v1.2/success/index.php), [/success/check.php](https://github.com/Avantern-LLC/sites-payment/blob/sber/develop/sber/v1.2/success/check.php) \- соответственно в /success.
3. [*sberpayment.php*](https://github.com/Avantern-LLC/sites-payment/blob/sber/develop/sber/v1.2/bitrix/templates/avantern/ajax/sberpayment.php) - по тому же пути (от корня сайта), как он лежит здесь.
4. Гугловая recapcha объявлена в *header.php*, вызывается в *index.php* формы.
5. [/success/check.php](https://github.com/Avantern-LLC/sites-payment/blob/sber/develop/sber/v1.2/success/check.php) исполнять **cron**, но не слишком часто. Рекомендуется каждый час, код:
            `0 */1 * * *     bitrix /usr/bin/php -f /home/bitrix/www/success/check.php`

---

### Связь с автором:

📧 cio@avanertn.ru
📧 s.ershov.shoko@gmail.com