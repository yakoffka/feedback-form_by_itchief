<?php
//==================================================================================================
// функция перехвата ошибок и записи их в файл:
//--------------------------------------------------------------------------------------------------
/* set_error_handler('err_handler');
function err_handler($errno,$errmsg,$filename,$linenum){
	$date=date('Y-m-d H:i:s (T)');
	$f=fopen('errors.txt','a');
	if(!empty($f)){
		$filename=str_replace($_SERVER['DOCUMENT_ROOT'],'',$filename);
		$err="$errmsg=$filename=$linenum\r\n";
		fwrite($f,$err);
		fclose($f);
	}
}
 */

// функция для проверки количества символов в тексте
//--------------------------------------------------------------------------------------------------
function checkTextLength($text,$minLength,$maxLength){
	$result=false;
	$textLength=mb_strlen($text,'UTF-8');
	if(($textLength>=$minLength) && ($textLength<=$maxLength)){
		$result=true;
	}
	return $result;
}



//==================================================================================================
// подключаем файл настроек
// require_once dirname(__FILE__).'/process_settings.php';
require_once('config.php');
// открываем сессию
session_start();
// вводим переменную, содержащую основной статус обработки формы
$data['result']='success';
// вводим переменную, сигнализирующую об успешной отправке основного письма
$successful_sending=FALSE;

// обрабатывать будем только ajax запросы
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest'){exit();}
// обрабатывать данные будет только если они посланы методом POST
if($_SERVER['REQUEST_METHOD']!='POST'){exit();}






		if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}




// валидация формы
//--------------------------------------------------------------------------------------------------
// валидация поля name
if(isset($_POST['name'])){
	$name=filter_var($_POST['name'], FILTER_SANITIZE_STRING); // защита от XSS
	if(!checkTextLength($name, 2, 30)){ // проверка на количество символов в тексте
		$data['name']='Поле <b>Имя</b> содержит недопустимое количество символов(допустимо от 2 до 30 символов)';
		$data['result']='error';
	}
} else {
	$data['name']='Поле <b>Имя</b> не заполнено';
	$data['result']='error';
}
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}

//валидация поля phone
if(!empty($_POST['phone'])){
	if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}
	if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$_POST['phone']='".$_POST['phone']."'\r\n", FILE_APPEND);}
	$phone=preg_replace('/\D/', '', $_POST['phone']); //получить номер телефона(цифры) из строки
	if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$phone='$phone'\r\n", FILE_APPEND);}
	if(!preg_match('/^(8|7)(\d{10})$/', $phone)){
		$data['phone']='Поле Телефон содержит некорректный номер!';
		$data['result']='error';
		if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}
	}
} else {
	if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}
	$data['phone']='Поле <b>телефон</b> не заполнено';
	$data['result']='error';
}
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}

//валидация поля email
if(isset($_POST['email'])){
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ // защита от XSS
		$data['email']='Укажите корректный адрес электронной почты';
		$data['result']='error';
	} else {
		$email=$_POST['email'];
	}
} else {
	$data['email']='Поле <b>Email</b> не заполнено';
	$data['result']='error';
}
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}

//валидация поля car_brand
if(isset($_POST['car_brand'])){
	if(!filter_var($_POST['car_brand'], FILTER_SANITIZE_STRING)){ // защита от XSS
		$data['car_brand']='Поле <b>марка автомобиля</b> заполнено некорректно';
		$data['result']='error';
	} else {
		$car_brand=$_POST['car_brand'];
	}
} else {
	$data['car_brand']='Поле <b>марка автомобиля</b> не заполнено';
	$data['result']='error';
}
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}

// валидация поля VIN
if(isset($_POST['vehicle_identification_number'])){
	$vehicle_identification_number=filter_var($_POST['vehicle_identification_number'], FILTER_SANITIZE_STRING); // защита от XSS
	if(!checkTextLength($vehicle_identification_number, 17, 17)){ // проверка на количество символов в тексте
		$data['vehicle_identification_number']='Поле <b>VIN</b> содержит недопустимое количество символов(необходимо ввести 17 символов)';
		$data['result']='error';
	}
} else {
	$data['vehicle_identification_number']='Поле <b>VIN</b> не заполнено';
	$data['result']='error';
}
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}

//невалидация поля message
// if(isset($_POST['message'])){
	// $message=filter_var($_POST['message'], FILTER_SANITIZE_STRING); // защита от XSS
	// if(!checkTextLength($message, 20, 500)){ // проверка на количество символов в тексте
		// $data['message']='Поле <b>Сообщение</b> содержит недопустимое количество символов';
		// $data['result']='error';
	// }
// } else {
	// $data['message']='Поле <b>Сообщение</b> не заполнено';
	// $data['result']='error';
// }else{$message='';}

//валидация поля Описание запчасти
if(isset($_POST['description_goods'])){
	$description_goods=filter_var($_POST['description_goods'], FILTER_SANITIZE_STRING); // защита от XSS
	if(!checkTextLength($description_goods, 1, 500)){ // проверка на количество символов в тексте
		$data['description_goods']='Поле <b>Описание запчасти</b> содержит недопустимое количество символов';
		$data['result']='error';
	}
} else {
	$data['description_goods']='Поле <b>Описание запчасти</b> не заполнено';
	$data['result']='error';
}
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}

//валидация капчи
if(isset($_POST['captcha']) && isset($_SESSION['captcha'])){
	$captcha=filter_var($_POST['captcha'], FILTER_SANITIZE_STRING); // защита от XSS
	if($_SESSION['captcha']!=$captcha){ // проверка капчи
		$data['captcha']='Вы неправильно ввели код с картинки';
		$data['result']='error';
	}
} else {
	$data['captcha']='Произошла ошибка при проверке проверочного кода';
	$data['result']='error';
}
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}

// валидация файлов
if(isset($_FILES['attachment'])){
	// $data['info'][]='$_FILES[\'attachment\'] isset';
	// $data['result']='error';
	// перебор массива $_FILES['attachment']
	foreach($_FILES['attachment']['error'] as $key => $error){
		// $data['info'][]="\$error='$error'";
		// $data['result']='error';
		// если файл был успешно загружен на сервер(ошибок не возникло), то...
		if($error==UPLOAD_ERR_OK){
			// $data['info'][]="\$error==UPLOAD_ERR_OK";
			// $data['result']='error';
			// получаем имя файла
			$fileName=$_FILES['attachment']['name'][$key];
			// $data['info'][]="\$fileName=$fileName";
			// $data['result']='error';
			// получаем расширение файла в нижнем регистре
			$fileExtension=mb_strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			// получаем размер файла
			$fileSize=$_FILES['attachment']['size'][$key];
			// результат проверки расширения файла
			$resultCheckExtension=true;
			// $data['info'][]="\$resultCheckExtension=$resultCheckExtension";
			// $data['result']='error';
			// проверяем расширение загруженного файла
			if(!in_array($fileExtension, $allowedExtensions)){
				$resultCheckExtension=false;
				$data['info'][]='Тип файла '.$fileName.' не соответствует разрешенному';
				$data['result']='error';
			}
			// проверяем размер файла
			if($resultCheckExtension &&($fileSize > MAX_FILE_SIZE)){
				$data['info'][]='Размер файла '.$fileName.' превышает 512 Кбайт';
				$data['result']='error';
			}
		}else{
			$data['info'][]='произошла ошибка '.__line__.'. свяжитесь с администратором';
			$data['result']='error';
		}
	}
	if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}
	

	// если ошибок валидации не возникло, то...
	if($data['result']=='success'){
		// $data['info'][]="\$data['result']=".$data['result'];
		// $data['result']='error';
		// присвоим значение переменной, сигнализирующей о наличии прикрепленных файлов
		$is_attach="присутствуют";
		// переменная для хранения имён файлов
		$attachments=array();
		// перемещение файлов в директорию UPLOAD_PATH
		foreach($_FILES['attachment']['name'] as $key => $attachment){
			// получаем имя файла
			$fileName=basename($_FILES['attachment']['name'][$key]);
			// получаем расширение файла в нижнем регистре
			$fileExtension=mb_strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			// временное имя файла на сервере
			$fileTmp=$_FILES['attachment']['tmp_name'][$key];
			
				// создаём уникальное имя
				// $fileNewName=uniqid('upload_', true).'.'.$fileExtension;
			// yo: создаём уникальную директорию, имя оставляем прежним
			$name_rand_dir=uniqid('dir_', true);
			$path_rand_dir=$uploadPath.$name_rand_dir;
				// $data['info'][]="\$name_rand_dir='$name_rand_dir';";
				// $data['info'][]="\$path_rand_dir='$path_rand_dir';";
				// $data['result']='error';


			// создаем директорию с уникальным именем
			if(!mkdir($path_rand_dir, 0700)){
				$data['info'][]='Ошибка '.__line__.' при загрузке файлов';
				$data['info'][]="директория $path_rand_dir не создана";
				$data['result']='error';
			}else{
				// $data['info'][]="директория $path_rand_dir создана";
				// $data['result']='error';
			}
			
			// перемещаем файл в созданную директорию
			if(!move_uploaded_file($fileTmp, "$path_rand_dir/$fileName")){
				// ошибка при перемещении файла
				$data['info'][]='Ошибка '.__line__.' при загрузке файлов';
				$data['result']='error';
			} else {
				// $attachments[]=$uploadPath.$fileNewName;
				$arr_attach_file['name_attfile']=$fileName;// имя прикрепляемого файла
				$arr_attach_file['full_patch_attfile']="$path_rand_dir/$fileName";// полный путь к файлу от корня сервера
				$arr_attach_file['rel_patch_attfile']="$relPatchUploads$name_rand_dir/$fileName";// относительный путь к файлу от корня сайта
				$attachments[]=$arr_attach_file;
				
				// $data['info'][]="\$arr_attach_file['name_attfile']=".$arr_attach_file['name_attfile'];
				// $data['info'][]="\$arr_attach_file['full_patch_attfile']=".$arr_attach_file['full_patch_attfile'];
				// $data['info'][]="\$arr_attach_file['rel_patch_attfile']=".$arr_attach_file['rel_patch_attfile'];
				// $data['result']='error';
			}
		}
	}else{
		// $data['info'][]="\$data['result']=".$data['result'];
		// $data['result']='error';
	}
	// $data['info'][]="\$data['result']=".$data['result'];
	// $data['result']='error';
}
// /валидация формы
//--------------------------------------------------------------------------------------------------
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}





// сбор сообщения
//==================================================================================================
	// получаем номер
	if(is_file($numfile_path)){$mess_numb=file_get_contents($numfile_path); // получаем содержимое email шаблона
	}else{$mess_numb=1;}
	file_put_contents($numfile_path,$mess_numb+1);
	// и дату заказа
	$mess_date=date('d.m.Y');
	$mess_date_time=date('d.m.Y H:i');
	//$value=str_replace('_replace_date', $mess_date, $value);
	
	// производим замены в теме писем
	$mail_subject=str_replace('_replace_date', $mess_date, str_replace('_replace_mess_numb', $mess_numb, $mail_subject));
	$mail_subject_client=str_replace('_replace_date', $mess_date, str_replace('_replace_mess_numb', $mess_numb, $mail_subject_client));
	
	// сбор сообщения в html
	$html_bodyMail="<h2 style='color:#0f94d7;text-align:center;'>_replace_title</h2><p>".$start_mess_adm."</p>".$html_table."<p>".$end_mess_user."</p>";
	// сбор сообщения в текстовом формате
	$plain_bodyMail="_replace_title\r\n\r\n".$start_mess_adm."\r\n\r\n".$plain_table."\r\n\r\n".$end_mess_user;
	// сбор отчета о доставке в html
	$html_delivery_report="<h2 style='color:#0f94d7;text-align:center;'>_replace_title</h2><p>".$start_mess_user."</p>".$html_table."<p>".$end_mess_user."</p>";
	// сбор отчета о доставке в текстовом формате
	$plain_delivery_report="_replace_title\r\n\r\n".$start_mess_user."\r\n\r\n".$plain_table."\r\n\r\n".$end_mess_user;
	// if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$plain_delivery_report='$plain_delivery_report'\r\n", FILE_APPEND);}


	/* // производим добавление файлов в виде ссылок
	foreach(array($html_bodyMail,$html_delivery_report) as &$value){
		if(isset($attachments)){
			$listFiles='<ul>';
			foreach($attachments as $attachment){
				// $fileHref=substr($attachment, strpos($attachment, 'feedback/uploads/'));// перенес в process_settings.php
				// $fileName=basename($fileHref);
				// $listFiles.='<li><a href="'.$startPath.$relPatchUploads.'">'.$fileName.'</a></li>'."<!-- $fileHref -->";
				$listFiles.='<li><a href="'.$startPath.$attachment['rel_patch_attfile'].'">'.$attachment['name_attfile'].'</a></li>';
			}
			$listFiles.='</ul>';
			$value=str_replace('%email.attachments%', $listFiles, $value);
		} else {
			$value=str_replace('%email.attachments%', ' отсутствуют', $value);
		}
	}
	unset($value);// разрываем ссылку на последний элемент */

	// выполняем замену плейсхолдеров реальными значениями
	foreach(array('html_bodyMail'=>$html_bodyMail,'plain_bodyMail'=>$plain_bodyMail,'html_delivery_report'=>$html_delivery_report,'plain_delivery_report'=>$plain_delivery_report) as $key=>$value){
		//if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$value='$value'\r\n", FILE_APPEND);}
		$value=str_replace('_replace_title', $mail_subject_client, $value);
		$value=str_replace('_replace_nameuser', isset($name)?$name:'-', $value);
		$value=str_replace('_replace_phone', isset($phone)?$phone:'не указан.', $value);
		$value=str_replace('_replace_message', isset($message)?$message:'-', $value);
		$value=str_replace('_replace_description_goods', isset($description_goods)?$description_goods:' не заполнено.', $value);
		$value=str_replace('_replace_emailuser', isset($email)?$email:' не указан.', $value);
		$value=str_replace('_replace_car_brand', isset($car_brand)?$car_brand:' не указана.', $value);
		$value=str_replace('_replace_is_attach', isset($is_attach)?$is_attach:' отсутствуют', $value);
		$value=str_replace('_replace_vehicle_identification_number', isset($vehicle_identification_number)?$vehicle_identification_number:' не указан.', $value);
		$value=str_replace('_replace_date', $mess_date_time, $value);
		$value=str_replace('_replace_html_copyright', isset($html_copyright)?$html_copyright:'#', $value);
		$value=str_replace('_replace_plain_copyright', isset($plain_copyright)?$plain_copyright:'#', $value);
		$value=str_replace('_replace_year', date('Y'), $value);
		$value=str_replace('_replace_mess_numb', $mess_numb, $value);
		//if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$value='$value'\r\n", FILE_APPEND);}
		
		if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$key='$key'\r\n", FILE_APPEND);}
		${$key}=$value;
		// if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."$\{\$key\}='".${$key}."'\r\n", FILE_APPEND);}
	}
	unset($value);// разрываем ссылку на последний элемент
	if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}




// отправка формы (данных на почту)
//==================================================================================================
if($data['result']=='success'){
	if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}
	// подключаем файл PHPMailerAutoload.php
	require_once('phpmailer/PHPMailerAutoload.php');

				// // формируем тело основного письма
				//-//------------------------------------------------------------------------------------------------
				// // формируем html-тело основного письма
				// $bodyMail="<h2 style='color:#0f94d7;text-align:center;'>_replace_title</h2><p>".$start_mess_user."</p>".$html_table."<p>".$end_mess_user."</p>";

				//	//	формируем plain-тело основного письма
				// $bodyMail="_replace_title\r\n\r\n".$start_mess_user."\r\n\r\n".$html_table."<p>".$end_mess_user."</p>";
				// $bodyMail=file_get_contents('email.tpl'); // получаем содержимое email шаблона
				// производим добавление файлов в виде ссылок
				/* if(isset($attachments)){
					$listFiles='<ul>';
					foreach($attachments as $attachment){
						// $fileHref=substr($attachment, strpos($attachment, 'feedback/uploads/'));// перенес в process_settings.php
						// $fileName=basename($fileHref);
						// $listFiles.='<li><a href="'.$startPath.$relPatchUploads.'">'.$fileName.'</a></li>'."<!-- $fileHref -->";
						$listFiles.='<li><a href="'.$startPath.$attachment['rel_patch_attfile'].'">'.$attachment['name_attfile'].'</a></li>';
					}
					$listFiles.='</ul>';
					$bodyMail=str_replace('%email.attachments%', $listFiles, $bodyMail);
				} else {
					$bodyMail=str_replace('%email.attachments%', ' отсутствуют', $bodyMail);
				} */

	// выполняем замену плейсхолдеров реальными значениями
	// $bodyMail=str_replace('%email.title%', MAIL_SUBJECT, $bodyMail);
	// $bodyMail=str_replace('%email.nameuser%', isset($name)?$name:'-', $bodyMail);
	// $bodyMail=str_replace('%email.phone%', isset($phone)?$phone:'не указан.', $bodyMail);
	// $bodyMail=str_replace('%email.message%', isset($message)?$message:'-', $bodyMail);
	// $bodyMail=str_replace('%email.description_goods%', isset($description_goods)?$description_goods:' не заполнено.', $bodyMail);
	// $bodyMail=str_replace('%email.emailuser%', isset($email)?$email:' не указан.', $bodyMail);
	// $bodyMail=str_replace('%email.car_brand%', isset($car_brand)?$car_brand:' не указана.', $bodyMail);
	// $bodyMail=str_replace('%email.is_attach%', isset($is_attach)?$is_attach:' отсутствуют', $bodyMail);
	// $bodyMail=str_replace('%email.vehicle_identification_number%', isset($vehicle_identification_number)?$vehicle_identification_number:' не указан.', $bodyMail);
	// $bodyMail=str_replace('%email.date%', date('d.m.Y H:i'), $bodyMail);
	// $bodyMail=str_replace('%email.copyright%', isset($copyright)?$copyright:'#', $bodyMail);
	// $bodyMail=str_replace('%email.year%', date('Y'), $bodyMail);

	
	// отправляем письмо адресату, указанному в /feedback/process/process_settings.php
	$mail=new PHPMailer;
	$mail->CharSet='UTF-8';
	$mail->IsHTML(true);  // формат HTML
	
	
				////Вот это да! Идеально, Вы можете отправлять
				////Результат :
				////9.4/10
				////$textBody='добавим текстовую версию вашего сообщения..';// добавим текстовую версию вашего сообщения
				//$mail->AltBody=$textBody;// добавим текстовую версию вашего сообщения
	
	$fromName='=?UTF-8?B?'.base64_encode(MAIL_FROM_NAME).'?=';
	$mail->setFrom(MAIL_FROM, $fromName);
	$mail->Subject='=?UTF-8?B?'.base64_encode($mail_subject).'?=';
	$mail->Body=$html_bodyMail;
	$mail->AltBody=$plain_bodyMail;// добавим текстовую версию вашего сообщения
	$mail->addAddress(MAIL_ADDRESS);
	// прикрепление файлов к письму
	if(isset($attachments)){
		if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}
		foreach($attachments as $attachment){
			if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}
			$mail->addAttachment($attachment['full_patch_attfile']);
		}
	}
 	// отправляем письмо
	if(!$mail->send()){
		if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}
		$data['info'][]="не удалось отправить письмо. ошибка ".__line__;// 
		$data['result']='error';
	}else{
		if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}
		$successful_sending=TRUE;
	}
	if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}


	// отправляем письмо адресату, указанному пользователем (отчет о доставке)
	//------------------------------------------------------------------------------------------------
	if(isset($email)){// если пользователь указал свой email и основное письмо отправлено 
		if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}
		if($successful_sending){// если пользователь указал свой email и основное письмо отправлено 
			if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}
			// очистка всех адресов и прикреплёных файлов
			$mail->clearAllRecipients();
			$mail->clearAttachments();
							// // формируем тело письма
							// $bodyMail=file_get_contents('email_client.tpl'); // получаем содержимое email шаблона
							// // выполняем замену плейсхолдеров реальными значениями
							// $bodyMail=str_replace('%email.title%', MAIL_SUBJECT_CLIENT, $bodyMail);
							// $bodyMail=str_replace('%email.nameuser%', isset($name)?$name:'-', $bodyMail);
							// $bodyMail=str_replace('%email.phone%', isset($phone)?$phone:'не указан.', $bodyMail);
							// $bodyMail=str_replace('%email.message%', isset($message)?$message:'-', $bodyMail);
							// $bodyMail=str_replace('%email.description_goods%', isset($description_goods)?$description_goods:' не заполнено.', $bodyMail);
							// $bodyMail=str_replace('%email.emailuser%', isset($email)?$email:' не указан.', $bodyMail);
							// $bodyMail=str_replace('%email.car_brand%', isset($car_brand)?$car_brand:' не указана.', $bodyMail);
							// $bodyMail=str_replace('%email.is_attach%', isset($is_attach)?$is_attach:' отсутствуют', $bodyMail);
							// $bodyMail=str_replace('%email.vehicle_identification_number%', isset($vehicle_identification_number)?$vehicle_identification_number:' не указан.', $bodyMail);
							// $bodyMail=str_replace('%email.date%', date('d.m.Y H:i'), $bodyMail);
							// $bodyMail=str_replace('%email.copyright%', isset($copyright)?$copyright:'#', $bodyMail);
							// $bodyMail=str_replace('%email.year%', date('Y'), $bodyMail);
							// $mail->Subject=MAIL_SUBJECT_CLIENT;
							// $mail->Body=$bodyMail;
							// $mail->addAddress($email);

			$mail->Subject=$mail_subject_client;
			$mail->Body=$html_delivery_report;
			$mail->AltBody=$plain_delivery_report;// добавим текстовую версию вашего сообщения
			$mail->addAddress($email);
			// прикрепление файлов к письму.. нужно ли?
			if(isset($attachments)){
				foreach($attachments as $attachment){
					$mail->addAttachment($attachment['full_patch_attfile']);
				}
			}
			$mail->send();
		}else{
			// пользователь не указал email
		}
	}else{
		// основное письмо не отправлено
	}
}
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\r\n", FILE_APPEND);}




// сохранение данных, введенных пользователем в файл
//--------------------------------------------------------------------------------------------------
// добавить условие, при соблюдении которого вести лог.. при отладке???
if($data['result']=='success'){
	if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}
	$name=isset($name)?$name:'-';
	$phone=isset($phone)?$phone:'-';
	$email=isset($email)?$email:'-';
	$car_brand=isset($car_brand)?$car_brand:'-';
	$vehicle_identification_number=isset($vehicle_identification_number)?$vehicle_identification_number:'-';
	$message=isset($message)?$message:'-';
	$description_goods=isset($description_goods)?$description_goods:'-';
	$output="---------------------------------"."\n";
	$output.=date("d-m-Y H:i:s")."\n";
	$output.="Имя пользователя: ".$name."\n";
	$output.="Телефон: ".$phone."\n";
	$output.="Адрес email: ".$email."\n";
	$output.="Сообщение: ".$message."\n";
	$output.="Марка автомобиля: ".$car_brand."\n";
	// добавление ссылок на прикрепленные файлы
	if(isset($attachments)){
		$output.="Файлы: "."\n";
		foreach($attachments as $attachment){
			$output.=$attachment['full_patch_attfile']."\n";
		}
	}
	//if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}
	if(!file_put_contents(dirname(dirname(__FILE__)).'/info/message.txt', $output, FILE_APPEND | LOCK_EX)){
		$data['result']='error';
		if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}
	}
}
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."\$data['result']=".$data['result']."\r\n", FILE_APPEND);}
// if(FORM_DEBUG){
	// foreach($data as $key=>$val){
		// file_put_contents($log_file_name,"\t'$key'=>'$val'\r\n", FILE_APPEND);
	// }
// }

// выводим результат отправки клиенту
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."json_encode(\$data)=".json_encode($data)."\r\n", FILE_APPEND);}
//echo json_encode($data);
$arr_true=array('result'=>'success');
if(FORM_DEBUG){file_put_contents($log_file_name,$_SERVER['PHP_SELF'].": ".__line__."json_encode(\$arr_true)=".json_encode($arr_true)."\r\n", FILE_APPEND);}
echo json_encode($arr_true);