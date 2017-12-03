<?php
// подключаем файл настроек
// require_once dirname(__FILE__) . '/process_settings.php';
require_once('../config.php');
// открываем сессию
session_start();
// вводим переменную, содержащую основной статус обработки формы
$data['result'] = 'success';

// обрабатывать будем только ajax запросы
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {exit();}
// обрабатывать данные будет только если они посланы методом POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {exit();}

// функция для проверки количества символов в тексте
function checkTextLength($text, $minLength, $maxLength)
{
	$result = false;
	$textLength = mb_strlen($text, 'UTF-8');
	if (($textLength >= $minLength) && ($textLength <= $maxLength)) {
		$result = true;
	}
	return $result;
}





// валидация формы
//--------------------------------------------------------------------------------------------------
// валидация поля name
if (isset($_POST['name'])) {
	$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING); // защита от XSS
	if (!checkTextLength($name, 2, 30)) { // проверка на количество символов в тексте
		$data['name'] = 'Поле <b>Имя</b> содержит недопустимое количество символов (допустимо от 2 до 30 символов)';
		$data['result'] = 'error';
	}
} else {
	$data['name'] = 'Поле <b>Имя</b> не заполнено';
	$data['result'] = 'error';
}

//валидация поля phone
if (!empty($_POST['phone'])) {
    $phone = preg_replace('/\D/', '', $_POST['phone']); //получить номер телефона (цифры) из строки
    if (!preg_match('/^(8|7)(\d{10})$/', $phone)) {
      $data['phone'] = 'Поле Телефон содержит некорректный номер!';
      $data['result'] = 'error';
    }
} else {
	$data['phone'] = 'Поле <b>телефон</b> не заполнено';
	$data['result'] = 'error';
}

//валидация поля email
if (isset($_POST['email'])) {
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { // защита от XSS
		$data['email'] = 'Укажите корректный адрес электронной почты';
		$data['result'] = 'error';
	} else {
		$email = $_POST['email'];
	}
} else {
	$data['email'] = 'Поле <b>Email</b> не заполнено';
	$data['result'] = 'error';
}

//валидация поля car_brand
if (isset($_POST['car_brand'])) {
	if (!filter_var($_POST['car_brand'], FILTER_SANITIZE_STRING)) { // защита от XSS
		$data['car_brand'] = 'Поле <b>марка автомобиля</b> заполнено некорректно';
		$data['result'] = 'error';
	} else {
		$car_brand = $_POST['car_brand'];
	}
} else {
	$data['car_brand'] = 'Поле <b>марка автомобиля</b> не заполнено';
	$data['result'] = 'error';
}

// валидация поля VIN
if (isset($_POST['vehicle_identification_number'])) {
	$vehicle_identification_number = filter_var($_POST['vehicle_identification_number'], FILTER_SANITIZE_STRING); // защита от XSS
	if (!checkTextLength($vehicle_identification_number, 17, 17)) { // проверка на количество символов в тексте
		$data['vehicle_identification_number'] = 'Поле <b>VIN</b> содержит недопустимое количество символов (необходимо ввести 17 символов)';
		$data['result'] = 'error';
	}
} else {
	$data['vehicle_identification_number'] = 'Поле <b>VIN</b> не заполнено';
	$data['result'] = 'error';
}

//невалидация поля message
// if (isset($_POST['message'])) {
	// $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING); // защита от XSS
	// if (!checkTextLength($message, 20, 500)) { // проверка на количество символов в тексте
		// $data['message'] = 'Поле <b>Сообщение</b> содержит недопустимое количество символов';
		// $data['result'] = 'error';
	// }
// } else {
	// $data['message'] = 'Поле <b>Сообщение</b> не заполнено';
	// $data['result'] = 'error';
// }else{$message='';}

//валидация поля Описание запчасти
if (isset($_POST['additional_field'])) {
	$additional_field = filter_var($_POST['additional_field'], FILTER_SANITIZE_STRING); // защита от XSS
	if (!checkTextLength($additional_field, 1, 500)) { // проверка на количество символов в тексте
		$data['additional_field'] = 'Поле <b>Описание запчасти</b> содержит недопустимое количество символов';
		$data['result'] = 'error';
	}
} else {
	$data['additional_field'] = 'Поле <b>Описание запчасти</b> не заполнено';
	$data['result'] = 'error';
}

//валидация капчи
if (isset($_POST['captcha']) && isset($_SESSION['captcha'])) {
	$captcha = filter_var($_POST['captcha'], FILTER_SANITIZE_STRING); // защита от XSS
	if ($_SESSION['captcha'] != $captcha) { // проверка капчи
		$data['captcha'] = 'Вы неправильно ввели код с картинки';
		$data['result'] = 'error';
	}
} else {
	$data['captcha'] = 'Произошла ошибка при проверке проверочного кода';
	$data['result'] = 'error';
}

// валидация файлов
if (isset($_FILES['attachment'])) {
	// перебор массива $_FILES['attachment']
	foreach ($_FILES['attachment']['error'] as $key => $error) {
		// если файл был успешно загружен на сервер (ошибок не возникло), то...
		if ($error == UPLOAD_ERR_OK) {
			// получаем имя файла
			$fileName = $_FILES['attachment']['name'][$key];
			// получаем расширение файла в нижнем регистре
			$fileExtension = mb_strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			// получаем размер файла
			$fileSize = $_FILES['attachment']['size'][$key];
			// результат проверки расширения файла
			$resultCheckExtension = true;
			// проверяем расширение загруженного файла
			if (!in_array($fileExtension, $allowedExtensions)) {
				$resultCheckExtension = false;
				$data['info'][] = 'Тип файла ' . $fileName . ' не соответствует разрешенному';
				$data['result'] = 'error';
			}
			// проверяем размер файла
			if ($resultCheckExtension && ($fileSize > MAX_FILE_SIZE)) {
				$data['info'][] = 'Размер файла ' . $fileName . ' превышает 512 Кбайт';
				$data['result'] = 'error';
			}
		}else{
			$data['info'][] = 'произошла ошибка '.__line__.'. свяжитесь с администратором';
			$data['result'] = 'error';
		}
	}
	
	// если ошибок валидации не возникло, то...
	if ($data['result'] == 'success') {
		// присвоим значение переменной, сигнализирующей о наличии прикрепленных файлов
		$is_attach="присутствуют";
		// переменная для хранения имён файлов
		$attachments = array();
		// перемещение файлов в директорию UPLOAD_PATH
		foreach ($_FILES['attachment']['name'] as $key => $attachment) {
			// получаем имя файла
			$fileName = basename($_FILES['attachment']['name'][$key]);
			// получаем расширение файла в нижнем регистре
			$fileExtension = mb_strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			// временное имя файла на сервере
			$fileTmp = $_FILES['attachment']['tmp_name'][$key];
			
				// создаём уникальное имя
				// $fileNewName = uniqid('upload_', true) . '.' . $fileExtension;
			// yo: создаём уникальную директорию, имя оставляем прежним
			$name_rand_dir = uniqid('dir_', true);
			$path_rand_dir = $uploadPath.$name_rand_dir;
				// $data['info'][] = "\$name_rand_dir='$name_rand_dir';";
				// $data['info'][] = "\$path_rand_dir='$path_rand_dir';";
				// $data['result'] = 'error';

			// создаем директорию с уникальным именем
			if(!mkdir($path_rand_dir, 0700)){
				$data['info'][] = 'Ошибка '.__line__.' при загрузке файлов';
				$data['result'] = 'error';
			}
			
			// перемещаем файл в созданную директорию
			if (!move_uploaded_file($fileTmp, "$path_rand_dir/$fileName")) {
				// ошибка при перемещении файла
				$data['info'][] = 'Ошибка '.__line__.' при загрузке файлов';
				$data['result'] = 'error';
			} else {
				// $attachments[] = $uploadPath . $fileNewName;
				$arr_attach_file['name_attfile'] = $fileName;// имя прикрепляемого файла
				$arr_attach_file['full_patch_attfile'] = "$path_rand_dir/$fileName";// полный путь к файлу от корня сервера
				$arr_attach_file['rel_patch_attfile'] = "$relPatchUploads$name_rand_dir/$fileName";// относительный путь к файлу от корня сайта
				$attachments[]=$arr_attach_file;
				
				// $data['info'][] = "\$attachments[]['full_patch_attfile']=".$attachments[]['name_attfile'];
				// $data['info'][] = "\$attachments[]['full_patch_attfile']=".$attachments[]['full_patch_attfile'];
				// $data['info'][] = "\$attachments[]['full_patch_attfile']=".$attachments[]['rel_patch_attfile'];
				// $data['result'] = 'error';
			}
		}
	}
}
// /валидация формы
//--------------------------------------------------------------------------------------------------




// отправка формы (данных на почту)
//--------------------------------------------------------------------------------------------------
if ($data['result'] == 'success') {
	// подключаем файл PHPMailerAutoload.php
	require_once('../phpmailer/PHPMailerAutoload.php');
	// формируем тело письма
	$bodyMail = file_get_contents('email.tpl'); // получаем содержимое email шаблона

	// производим добавление файлов в виде ссылок
	if (isset($attachments)) {
		$listFiles = '<ul>';
		foreach ($attachments as $attachment) {
			// $fileHref = substr($attachment, strpos($attachment, 'feedback/uploads/'));// перенес в process_settings.php
			// $fileName = basename($fileHref);
			// $listFiles .= '<li><a href="' . $startPath . $relPatchUploads . '">' . $fileName . '</a></li>'."<!-- $fileHref -->";
			$listFiles .= '<li><a href="' . $startPath . $attachment['rel_patch_attfile'] . '">' . $attachment['name_attfile'] . '</a></li>';
		}
		$listFiles .= '</ul>';
		$bodyMail = str_replace('%email.attachments%', $listFiles, $bodyMail);
	} else {
		$bodyMail = str_replace('%email.attachments%', ' отсутствуют', $bodyMail);
	}

	// выполняем замену плейсхолдеров реальными значениями
	$bodyMail = str_replace('%email.title%', MAIL_SUBJECT, $bodyMail);
	$bodyMail = str_replace('%email.nameuser%', isset($name) ? $name : '-', $bodyMail);
	$bodyMail = str_replace('%email.phone%', isset($phone) ? $phone : 'не указан.', $bodyMail);
	$bodyMail = str_replace('%email.message%', isset($message) ? $message : '-', $bodyMail);
	$bodyMail = str_replace('%email.additional_field%', isset($additional_field) ? $additional_field : ' не заполнено.', $bodyMail);
	$bodyMail = str_replace('%email.emailuser%', isset($email) ? $email : ' не указан.', $bodyMail);
	$bodyMail = str_replace('%email.car_brand%', isset($car_brand) ? $car_brand : ' не указана.', $bodyMail);
	$bodyMail = str_replace('%email.is_attach%', isset($is_attach) ? $is_attach : ' отсутствуют', $bodyMail);
	$bodyMail = str_replace('%email.vehicle_identification_number%', isset($vehicle_identification_number) ? $vehicle_identification_number : ' не указан.', $bodyMail);
	$bodyMail = str_replace('%email.date%', date('d.m.Y H:i'), $bodyMail);
	$bodyMail = str_replace('%email.year%', date('Y'), $bodyMail);

	
	// отправляем письмо адресату, указанному в /feedback/process/process_settings.php
	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->IsHTML(true);  // формат HTML
	$fromName = '=?UTF-8?B?'.base64_encode(MAIL_FROM_NAME).'?=';
	$mail->setFrom(MAIL_FROM, $fromName);
	$mail->Subject = '=?UTF-8?B?'.base64_encode(MAIL_SUBJECT).'?=';
	$mail->Body = $bodyMail;
	$mail->addAddress(MAIL_ADDRESS);
	// прикрепление файлов к письму
	if (isset($attachments)) {
		foreach ($attachments as $attachment) {
			$mail->addAttachment($attachment['full_patch_attfile']);
		}
	}
 	// отправляем письмо
	if (!$mail->send()) {
			$data['info'][] = "не удалось отправить письмо. ошибка ".__line__;// 
			$data['result'] = 'error';
	}


	// отправляем письмо адресату, указанному пользователем (информируем пользователя о доставке)
	if (isset($email)) {
		// очистка всех адресов и прикреплёных файлов
		$mail->clearAllRecipients();
		$mail->clearAttachments();
		//формируем тело письма
		$bodyMail = file_get_contents('email_client.tpl'); // получаем содержимое email шаблона
		// выполняем замену плейсхолдеров реальными значениями
		$bodyMail = str_replace('%email.title%', MAIL_SUBJECT_CLIENT, $bodyMail);
		$bodyMail = str_replace('%email.nameuser%', isset($name) ? $name : '-', $bodyMail);
		$bodyMail = str_replace('%email.phone%', isset($phone) ? $phone : 'не указан.', $bodyMail);
		$bodyMail = str_replace('%email.message%', isset($message) ? $message : '-', $bodyMail);
		$bodyMail = str_replace('%email.additional_field%', isset($additional_field) ? $additional_field : ' не заполнено.', $bodyMail);
		$bodyMail = str_replace('%email.emailuser%', isset($email) ? $email : ' не указан.', $bodyMail);
		$bodyMail = str_replace('%email.car_brand%', isset($car_brand) ? $car_brand : ' не указана.', $bodyMail);
		$bodyMail = str_replace('%email.is_attach%', isset($is_attach) ? $is_attach : ' отсутствуют', $bodyMail);
		$bodyMail = str_replace('%email.vehicle_identification_number%', isset($vehicle_identification_number) ? $vehicle_identification_number : ' не указан.', $bodyMail);
		$bodyMail = str_replace('%email.date%', date('d.m.Y H:i'), $bodyMail);
		$bodyMail = str_replace('%email.year%', date('Y'), $bodyMail);
		$mail->Subject = MAIL_SUBJECT_CLIENT;
		$mail->Body = $bodyMail;
		$mail->addAddress($email);
		// прикрепление файлов к письму
		if (isset($attachments)) {
			foreach ($attachments as $attachment) {
				$mail->addAttachment($attachment['full_patch_attfile']);
			}
		}
		$mail->send();
	}
}




// сохранение данных, введенных пользователем в файл
//--------------------------------------------------------------------------------------------------
// добавить условие, при соблюдении которого вести лог.. при отладке???
if ($data['result'] == 'success') {
	$name = isset($name) ? $name : '-';
	$phone = isset($phone) ? $phone : '-';
	$email = isset($email) ? $email : '-';
	$car_brand = isset($car_brand) ? $car_brand : '-';
	$vehicle_identification_number = isset($vehicle_identification_number) ? $vehicle_identification_number : '-';
	$message = isset($message) ? $message : '-';
	$additional_field = isset($additional_field) ? $additional_field : '-';
	$output = "---------------------------------" . "\n";
	$output .= date("d-m-Y H:i:s") . "\n";
	$output .= "Имя пользователя: " . $name . "\n";
	$output .= "Телефон: " . $phone . "\n";
	$output .= "Адрес email: " . $email . "\n";
	$output .= "Сообщение: " . $message . "\n";
	$output .= "Марка автомобиля: " . $car_brand . "\n";
	// добавление ссылок на прикрепленные файлы
	if (isset($attachments)) {
		$output .= "Файлы: " . "\n";
		foreach ($attachments as $attachment) {
			$output .= $attachment['full_patch_attfile'] . "\n";
		}
	}
	if (!file_put_contents(dirname(dirname(__FILE__)) . '/info/message.txt', $output, FILE_APPEND | LOCK_EX)) {
		$data['result'] = 'error';
	}
}

// сообщаем результат клиенту
echo json_encode($data);