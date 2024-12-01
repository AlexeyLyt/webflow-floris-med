<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$formName = $_POST['form_name'] ?? '';
	echo "<script>console.log('$formName');</script>";

	// reCAPTCHA
	$secret = '6Leim_UpAAAAAH1rKA5DLLOyqFwTUmqA-5CwCzC2';
	$recaptchaResponse = $_POST['g-recaptcha-response'];
	$honeypot = $_POST['honeypot'];

	$name = htmlspecialchars($_POST['Name'] ?? '');
	$phone = htmlspecialchars($_POST['Phone'] ?? '');
	$email = htmlspecialchars($_POST['Email'] ?? '');
	$subject = htmlspecialchars($_POST['Subject'] ?? ''); // Услуга

	$age = htmlspecialchars($_POST['Age'] ?? ''); // Возраст сиделки
	$nationality = htmlspecialchars($_POST['Nationality'] ?? ''); // Гражданство сиделки
	$schedule = htmlspecialchars($_POST['Schedule'] ?? ''); // Удобный график работы сиделки
	$education = htmlspecialchars($_POST['Education'] ?? ''); // Уровень образования сиделки
	$medEducation = htmlspecialchars($_POST['MedEducation'] ?? ''); // Наличие мед. образования сиделки
	$certificate = htmlspecialchars($_POST['Certificate'] ?? ''); // Наличие санитарной книжки у сиделки

	$formMessage = htmlspecialchars($_POST['Message'] ?? ''); // Комментарий (сообщение)

	$formHeaderName = null;
	$message = null;
	$isValid = true;

	// Запрос к API reCAPTCHA для проверки токена
	$verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}");
	$responseData = json_decode($verifyResponse);

	// Настройки почты
	$to = "katowicelyt@yandex.ru";
	// $headers = "From: alelytovc@yandex.ru\r\n";
	// $headers .= "Content-Type: text/plain; charset=utf-8";

	switch ($formName) {
		case 'wf-form-Contact-Form':
			$formHeaderName = 'Заявка с расширенной формы сайта';
			if (!$name && !$phone && !$email && !$formMessage) $isValid = false;
			$message = "Имя: $name \nПочта: $email \nТелефон: $phone \nУслуга: $subject \nСообщение: $formMessage";
			break;

		case 'wf-form-Contact-Form-Vacancies':
			$formHeaderName = 'Заявка со страницы ВАКАНСИЙ для сиделок';
			if (!$name && !$phone && !$email) $isValid = false;
			$message = "Имя: $name \nПочта: $email \nТелефон: $phone \nВозраст сиделки: $age \nГражданство сиделки: $nationality \nУдобный график работы сиделки: $schedule
			\nУровень образования сиделки: $education \nНаличие мед. образования: $medEducation \nНаличие санитарной книжки: $certificate \nСообщение: $formMessage";
			break;

		case 'wf-form-Popup-1-Form':
			$formHeaderName = 'Заявка c формы обратной связи';
			if (!$name && !$phone) $isValid = false;
			$message = "Имя: $name \nТелефон: $phone";
			break;

		case 'wf-form-CTA-Subscribe':
			$formHeaderName = 'Заявка c баннера на главной странице сайта';
			if (!$email) $isValid = false;
			$message = "Почта или телефон: $email";
			break;

		case 'form-from-services':
			$formHeaderName = 'Заявка c посадочной страницы услуг';
			if (!$name && !$phone && !$email) $isValid = false;
			$message = "Имя: $name \nТелефон: $phone \nПочта: $email";
			break;

		case 'wf-form-multi-step-form':
			$formHeaderName = 'Заявка на подбор специалиста по уходу';
			// Обработка выбранных чекбоксов
			$surName = htmlspecialchars($_POST['Surname'] ?? '');
			$selectedOption = [];
			if (isset($_POST['checkBoxProzhivanie'])) $selectedOptions[] = "Сиделка с проживанием";
			if (isset($_POST['checkBoxPrihodyashaya'])) $selectedOptions[] = "Сиделка приходящая";
			if (isset($_POST['checkBoxSutochnaya'])) $selectedOptions[] = "Сиделка суточная";
			if (isset($_POST['checkBoxBolnitsa'])) $selectedOptions[] = "Сиделка в больницу";
			$selectedOptionsMessage = implode(", ", $selectedOptions); // Объединяем выбранные пункты в строку для отправки на почту

			$wardName = htmlspecialchars($_POST['WardName'] ?? ''); // Имя подопечного
			$wardAge = htmlspecialchars($_POST['WardAge'] ?? ''); // Возраст подопечного
			$wardWeight = htmlspecialchars($_POST['WardWeight'] ?? ''); // Вес подопечного
			$wardDiagnosis = htmlspecialchars($_POST['WardDiagnosis'] ?? ''); // Диагноз подопечного

			if (!$name && !$phone) $isValid = false;
			$message = "Имя: $name $surName \nТелефон: $phone \nПочта: $email \nВыбранная услуги: $selectedOptionsMessage
			\nИмя подопечного: $wardName \nВозраст подопечного: $wardAge \nВес подопечного: $wardWeight \nДиагноз подопечного: $wardDiagnosis";
			break;

		case 'wf-form-Blog-Subscribe':
			$formHeaderName = 'Подписка на нашу рассылку со страницы БЛОГА';
			if (!$email) $isValid = false;
			$message = "Почта: $email";
			break;

		default:
			echo "Неизвестная форма!";
			exit;
	}

	// Проверка ответа от reCAPTCHA
	if ($responseData->success && $responseData->score >= 0.5) {
		if (!$isValid || $honeypot) return;
		if (mail($to, $formHeaderName, $message)) {
		// if (mail($to, $formHeaderName, $message, $headers)) {
			// echo "Сообщение успешно отправлено";
			header('Location: /contact-form-handler.html?status=success');
		} else {
			// echo "При отправке сообщения возникли ошибки";
			header('Location: /contact-form-handler.html?status=error');
		}
	} else {
		// Если проверка не прошла - выводим ошибку (страница с сообщением о Тех работах или подобном + просьба позвонить напрямую по телефону)
		echo 'Verification failed. Please try again';
	}
}
