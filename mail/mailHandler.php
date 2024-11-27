<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	echo "<script>console.log('123');</script>";
	$secret = '6Leim_UpAAAAAH1rKA5DLLOyqFwTUmqA-5CwCzC2';
	$name = $_POST['Name'];
	$tel = $_POST['Phone'];
	$email = $_POST['Email'];
	$honeypot = $_POST['honeypot'];
	$recaptchaResponse = $_POST['g-recaptcha-response'];

	// Запрос к API reCAPTCHA для проверки токена
	$verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}");
	$responseData = json_decode($verifyResponse);

	$headers = 'From: ' . 'alelytovc@yandex.ru' . "\r\n" .
		'Reply-To: ' . 'alelytovc@yandex.ru' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	// Проверка ответа от reCAPTCHA
	// if ($responseData->success && $responseData->score >= 0.5) {
	if ((!$name && !$tel && !$email) || $honeypot) return;
	if (mail("katowicelyt@yandex.ru", "Заявка с главной страницы сайта", "Имя: " . $name . "\r\n" . "Телефон: " . $tel . "\r\n" . "Почта: " . "$email", $headers)) {
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
