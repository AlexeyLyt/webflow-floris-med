<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$formName = $_POST['form_name'] ?? '';
	echo "<script>console.log('$formName');</script>";

	// reCAPTCHA
	$secret = '6Leim_UpAAAAAH1rKA5DLLOyqFwTUmqA-5CwCzC2';
	$recaptchaResponse = $_POST['g-recaptcha-response'];
	$honeypot = $_POST['honeypot'];

	$name = $_POST['Name'];
	$phone = $_POST['Phone'];
	$email = $_POST['Email'];
	$subject = $_POST['Subject']; // Услуга
	$formHeaderName = null;
	$message = null;
	$isValid = true;

	// Запрос к API reCAPTCHA для проверки токена
	$verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}");
	$responseData = json_decode($verifyResponse);

	// Настройки почты
	$to = "katowicelyt@yandex.ru";
	$headers = "From: alelytovc@yandex.ru\r\n";
	$headers .= "Content-Type: text/plain; charset=utf-8";

	switch ($formName) {
		case 'wf-form-Contact-Form':
			$formHeaderName = 'Заявка с главной формы сайта';
			$name = htmlspecialchars($_POST['Name'] ?? '');
			$phone = htmlspecialchars($_POST['Phone'] ?? '');
			$email = htmlspecialchars($_POST['Email'] ?? '');
			$formMessage = htmlspecialchars($_POST['Message'] ?? '');
			if (!$name && !$phone && !$email && !$formMessage) $isValid = false;
			$message = "Имя: $name \nПочта: $email \nТелефон: $phone \nУслуга: $subject \nСообщение: $formMessage";
			break;

		case 'feedback_form':
			$username = htmlspecialchars($_POST['username'] ?? '');
			$feedback = htmlspecialchars($_POST['feedback'] ?? '');

			$subject = "Отзыв пользователя";
			$message = "Имя пользователя: $username\nОтзыв: $feedback";
			break;

		default:
			echo "Неизвестная форма!";
			exit;
	}

	// Проверка ответа от reCAPTCHA
	// if ($responseData->success && $responseData->score >= 0.5) {
	if (!$isValid || $honeypot) return;
	if (mail($to, $formHeaderName, $message, $headers)) {
		// echo "Сообщение успешно отправлено";
		header('Location: /contact-form-handler.html?status=success');
	} else {
		// echo "При отправке сообщения возникли ошибки";
		header('Location: /contact-form-handler.html?status=error');
	}
	// } else {
	//     // Если проверка не прошла - выводим ошибку (страница с сообщением о Тех работах или подобном + просьба позвонить напрямую по телефону)
	//     echo 'Verification failed. Please try again';
	// }
}
