<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Оплата пластиковой картой — АВАНТЕРН Безопасность");
$APPLICATION->SetTitle("Оплата пластиковой картой");
?><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pay-form">
	<div class="row">
		<form id="newpay-form" class="newpay-form" action="/bitrix/templates/avantern/ajax/payment.php" method="post">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 questions active">
				<div class="row">
					<div class="pay-container">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="row">
										<div class="input">
											<div class="select">
												<select name="firm" id="firm" required="">
													<option value="2">Сити-Гард</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden-xs">
									<div class="row">
										<table class="comment comm1">
										<tbody>
										<tr>
											<td>
												 Выберите юридическое лицо, <br>
												 с которым у вас заключен договор
											</td>
										</tr>
										</tbody>
										</table>
									</div>
								</div>
								<div class="clearfix">
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="row">
										<div class="input">
 <input class="name" type="text" name="name" id="name" placeholder="ФИО полностью" required>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="row">
										<div class="input last">
											<div class="select">
												<select name="service" id="service" required>
													<option selected="selected" disabled="" value="">Выберите услугу</option>
													<option value="Абонентская плата за мониторинг">Абонентская плата за мониторинг</option>
													<option value="За монтаж">За монтаж</option>
													<option value="За сервисный выезд/Техническое обслуживание">За сервисный выезд/Техническое обслуживание</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix">
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="row">
												<div class="input">
 <input class="name" type="text" name="dogovor" id="dogovor" placeholder="Номер договора" required>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="row">
										<table class="comment comm1">
										<tbody>
										<tr>
											<td>
												 При отсутствии информации о договоре, <br>
												 обращайтесь по телефону поддержки <br>
												 +7 (495) 032-89-85<br>
											</td>
										</tr>
										</tbody>
										</table>
									</div>
								</div>
								<div class="clearfix">
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="row">
										<div class="input">
 <input class="name" type="email" name="email" id="email" placeholder="Введите вашу почту" required>
										</div>
									</div>
								</div>
								<div class="clearfix">
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="row">
										<div class="input">
 <input class="name" type="text" name="phone" id="phone" placeholder="Введите ваш телефон">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="del">
					</div>
					<div class="pay-container">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 small-title">
									<div class="row">
										 Сумма к оплате
									</div>
								</div>
								<div class="clearfix">
								</div>
								<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
									<div class="row">
										<div class="input">
 <input class="name right price" type="text" name="rub" id="rub" placeholder="Рублей" required>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
									<div class="row">
										<div class="input">
 <input class="name right price" type="text" name="kop" id="kop" placeholder="Копеек" required>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="row">
										<div class="input last">
											<div class="load">
 <img src="/bitrix/templates/avantern/images/load.gif" class="img-responsive">
											</div>
      <div id="recaptcha" class="g-recaptcha"
          data-sitekey="6Lev6mcjAAAAABIMpKKD1ScVqbcdkqKhXQWRIkNl"
          data-callback="onSubmit"
          data-size="invisible"></div>
 <input type="submit" class="active button-orange g-recaptcha" name="form-submit" id="form-submit" value="Оплатить">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 comm">
						<div class="row">
							<p>
								 После нажатия на кнопку “Оплатить” вы будете перенаправлены на страницу платежной системы, где сможете осуществить платеж с помощью карты Visa и Mastercard
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 thank">
				<div class="row">
					<div class="pay-container">
						 Сейчас вы будете перенаправлены на страницу оплаты.
					</div>
				</div>
			</div>
		</form>
<script>
		function onSubmit(token) {
			const form = document.querySelector('#newpay-form');
			form.submit();
		}
		function validate(event) {
			event.preventDefault();
			let errCount = -1;
			const form = document.querySelector('#newpay-form');
			const el = [].slice.call(form.elements);
			el.forEach(item => {
				let isValid = true;
				if (item.getAttribute('name') === "email") {
					const EMAIL_REGEXP = /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu;
					isValid = EMAIL_REGEXP.test(item.value);
				}
				if (!item.value || !isValid) {
				item.classList.add('error');
				errCount++;
				} else {
				item.classList.remove('error')
				}
			});
			if (!errCount) {
				grecaptcha.execute();
			}
		}
		function onload() {
			var element = document.getElementById('form-submit');
			element.onclick = validate;
		}
	</script>
<script>onload();</script>
	</div>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>