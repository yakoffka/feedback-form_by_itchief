<?php
//==================================================================================================
// подключаем файл настроек
// require_once dirname(__FILE__).'/process_settings.php';
require_once('../config.php');
require_once('../lib.php');
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


// var_log($rel_path_feedback,"rel_path_feedback",__line__,$_SERVER['PHP_SELF']);
// var_log("пример сообщения","mess",__line__,$_SERVER['PHP_SELF']);






// валидация формы
//--------------------------------------------------------------------------------------------------
// валидация поля name
// 		-------
if(isset($_POST['name'])){
	$name=filter_var($_POST['name'], FILTER_SANITIZE_STRING); // защита от XSS
	if(!checkTextLength($name, 2, 30)){ // проверка на количество символов в тексте
		$data['name']='Поле <b>Имя</b> содержит недопустимое количество символов(допустимо от 2 до 30 символов)';
		$data['result']='error';
	}
}else{
	$data['name']='Поле <b>Имя</b> не заполнено';
	$data['result']='error';
}
var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);


// 		-------
// валидация поля phone
if(!empty($_POST['phone'])){
	var_log($_POST['phone'],"_POST['phone']",__line__,$_SERVER['PHP_SELF']);
	$phone=preg_replace('/\D/', '', $_POST['phone']); //получить номер телефона(цифры) из строки
	var_log($phone,"phone",__line__,$_SERVER['PHP_SELF']);
	if(!preg_match('/^(8|7)(\d{10})$/', $phone)){
		$data['phone']='Поле Телефон содержит некорректный номер!';
		$data['result']='error';
		str_log(__line__,$_SERVER['PHP_SELF']);
	}
}else{
	str_log(__line__,$_SERVER['PHP_SELF']);
	$data['phone']='Поле <b>телефон</b> не заполнено';
	$data['result']='error';
}
var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);


// 		-------
// валидация поля email
if(isset($_POST['email'])){
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ // защита от XSS
		$data['email']='Укажите корректный адрес электронной почты';
		$data['result']='error';
	}else{
		$email=$_POST['email'];
	}
}else{
	$data['email']='Поле <b>Email</b> не заполнено';
	$data['result']='error';
}
var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);


// 		-------
// валидация поля car_brand
if(isset($_POST['car_brand'])){
	if(!filter_var($_POST['car_brand'], FILTER_SANITIZE_STRING)){ // защита от XSS
		$data['car_brand']='Поле <b>марка автомобиля</b> заполнено некорректно';
		$data['result']='error';
	}else{
		$car_brand=$_POST['car_brand'];
	}
}else{
	$data['car_brand']='Поле <b>марка автомобиля</b> не заполнено';
	$data['result']='error';
}
var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);


// 		-------
// валидация поля VIN
if(isset($_POST['vehicle_identification_number'])){
	$vehicle_identification_number=filter_var($_POST['vehicle_identification_number'], FILTER_SANITIZE_STRING); // защита от XSS
	if(!checkTextLength($vehicle_identification_number, 17, 17)){ // проверка на количество символов в тексте
		$data['vehicle_identification_number']='Поле <b>VIN</b> содержит недопустимое количество символов(необходимо ввести 17 символов)';
		$data['result']='error';
	}
}else{
	$data['vehicle_identification_number']='Поле <b>VIN</b> не заполнено';
	$data['result']='error';
}
var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);


//невалидация поля message
// if(isset($_POST['message'])){
	// $message=filter_var($_POST['message'], FILTER_SANITIZE_STRING); // защита от XSS
	// if(!checkTextLength($message, 20, 500)){ // проверка на количество символов в тексте
		// $data['message']='Поле <b>Сообщение</b> содержит недопустимое количество символов';
		// $data['result']='error';
	// }
// }else{
	// $data['message']='Поле <b>Сообщение</b> не заполнено';
	// $data['result']='error';
// }else{$message='';}


// 		-------
// валидация поля Описание запчасти
if(isset($_POST['description_goods'])){
	$description_goods=filter_var($_POST['description_goods'], FILTER_SANITIZE_STRING); // защита от XSS
	if(!checkTextLength($description_goods, 1, 500)){ // проверка на количество символов в тексте
		$data['description_goods']='Поле <b>Описание запчасти</b> содержит недопустимое количество символов';
		$data['result']='error';
	}
}else{
	$data['description_goods']='Поле <b>Описание запчасти</b> не заполнено';
	$data['result']='error';
}
var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);


// 		-------
// валидация капчи
if(isset($_POST['captcha']) && isset($_SESSION['captcha'])){
	$captcha=filter_var($_POST['captcha'],FILTER_SANITIZE_STRING); // защита от XSS
	if($_SESSION['captcha']!=$captcha){// проверка капчи
		$data['captcha']='Вы неправильно ввели код с картинки';
		$data['result']='error';
	}
}else{
	$data['captcha']='Произошла ошибка при проверке проверочного кода';
	$data['result']='error';
}
var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);


// 		-------
// валидация файлов
var_log($_FILES,"_FILES",__line__,$_SERVER['PHP_SELF']);
if(isset($_FILES['attachment'])){
	var_log($_FILES,"_FILES",__line__,$_SERVER['PHP_SELF']);
	// перебор массива $_FILES['attachment']
	foreach($_FILES['attachment']['error'] as $key => $error){
		// если файл был успешно загружен на сервер(ошибок не возникло), то...
		if($error==UPLOAD_ERR_OK){
			// получаем имя файла
			$fileName=$_FILES['attachment']['name'][$key];
			// получаем расширение файла в нижнем регистре
			$fileExtension=mb_strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			// получаем размер файла
			$fileSize=$_FILES['attachment']['size'][$key];
			// результат проверки расширения файла
			$resultCheckExtension=true;
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
	var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);
	

	// если ошибок валидации не возникло, то...
	if($data['result']=='success'){
		var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);
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
			// $name_rand_dir=uniqid('dir_', true);
			// $name_rand_dir=substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'),0,10);
			$name_rand_dir='';
			$i=0;
			while($i<CAPCHA_NUM){$name_rand_dir=$name_rand_dir.substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'),0,1);$i++;}
			unset($i,$string);
			$path_rand_dir=$patch_uploads_dir.$name_rand_dir;
			var_log($path_rand_dir,"path_rand_dir",__line__,$_SERVER['PHP_SELF']);

			// создаем директорию с уникальным именем
			if(!mkdir($path_rand_dir, 0700)){
				$data['info'][]='Ошибка '.__line__.' при загрузке файлов';
				$data['info'][]="директория $path_rand_dir не создана";
				$data['result']='error';
			}else{
				var_log("директория \$path_rand_dir создана","mess",__line__,$_SERVER['PHP_SELF']);
			}
			
			// перемещаем файл в созданную директорию
			if(!move_uploaded_file($fileTmp, "$path_rand_dir/$fileName")){
				// ошибка при перемещении файла
				$data['info'][]='Ошибка '.__line__.' при загрузке файлов';
				$data['result']='error';
			}else{
				// $attachments[]=$patch_uploads_dir.$fileNewName;
				$arr_attach_file['name_attfile']=$fileName;// имя прикрепляемого файла
				$arr_attach_file['full_patch_attfile']="$path_rand_dir/$fileName";// полный путь к файлу от корня сервера
				$arr_attach_file['rel_patch_attfile']="$rel_patch_uploads_dir$name_rand_dir/$fileName";// относительный путь к файлу от корня сайта
				$attachments[]=$arr_attach_file;
			}
		}
	}else{
		var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);
	}
	var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);
}



/* вставка части скрипта для валидации и прикрепления файлов через dropzone

//обработаем файлы, загруженные пользователем посредством элементов с name="images[]"
// если ассоциатианый массив $_FILES["images"] существует, то
if(isset($_FILES['images'])){
	if(FORM_DEBUG){file_put_contents($patch_log_file,$_SERVER['PHP_SELF'].": ".__line__." \$_FILES['images'] isset.\r\n", FILE_APPEND);}
	// var_log($_FILES['images'],"_FILES['images']",__line__,$_SERVER['PHP_SELF']);
	var_log($_FILES,"_FILES",__line__,$_SERVER['PHP_SELF']);

	// переберём все файлы (изображения)
	$files=array();
	foreach ($_FILES["images"]["error"] as $key=>$error){
		// если ошибок не возникло, т.е. файл был успешно загружен на сервер, то...
		if($error==UPLOAD_ERR_OK){
			// имя файла на устройстве пользователя
			$nameFile=$_FILES['images']['name'][$key];
			// расширение загруженного пользователем файла в нижнем регистре
			$extFile=mb_strtolower(pathinfo($nameFile, PATHINFO_EXTENSION));
			// размер файла
			$sizefile=$_FILES['images']['size'][$key];
			//myme-тип файла
			$filetype=$_FILES['images']['type'][$key]; 
			// проверить расширение файла, размер файла и mime-тип
			if(!array_key_exists($extFile, $allowedExtension)){
				$data['files']='Ошибка при загрузке файлов (неверное расширение).';
				$data['result']='error';
			}elseif($sizefile > $maxSizeFile){
				$data['files']='Ошибка при загрузке файлов (размер превышает 512Кбайт).';
				$data['result']='error';
			}elseif(!in_array($filetype, $allowedExtension)){
				$data['files']='Ошибка при загрузке файлов (неверный тип файла).';
				$data['result']='error';
			}else{
				//ошибок не возникло, продолжаем...
				// временное имя, с которым принятый файл был сохранён на сервере
				$tmpFile=$_FILES['images']['tmp_name'][$key];
				// уникальное имя файла
				$newFileName=uniqid('img_', true).'.'.$extFile;
				
				$path_rand_dir=$patch_uploads_dir.$name_rand_dir;
				// полное имя файла
				// $newFullFileName=$pathToFile.$newFileName;
				$newFullFileName=$path_rand_dir.$newFileName;
				// перемещаем файл в директорию
				if(!move_uploaded_file($tmpFile, $newFullFileName)){
					// ошибка при перемещении файла
					// $data['files']='Ошибка при загрузке файлов.';
					$data['files']=$data['files']."Ошибка при загрузке файлов.<br>\n\$extFile='$extFile';<br>\n\$allowedExtension='$allowedExtension';<br>\n\$sizefile='$sizefile';<br>\n\$maxSizeFile='$maxSizeFile';<br>\n\$filetype='$filetype';<br>\n\$tmpFile='$tmpFile';<br>\n\$newFullFileName='$newFullFileName.'";
					// yo
						//$data['files']=$data['files']."<pre>".print_r($_FILES)."</pre>";
						$patch_tmp_dir="/tmp/333";
						if(is_dir($patch_tmp_dir)){
							$data['files']="директория '$patch_tmp_dir' существует";
						}else{
							if(mkdir($patch_tmp_dir, 0700)){
								$data['files']="директория '$patch_tmp_dir' создана";
							}else{
								$data['files']="директория '$patch_tmp_dir' не создана";
							}
						}
					// yo
					$data['result']='error';
				}else{
					$files[]=$newFullFileName;
				}
				/* yo пытался проверить.. // не!! перемещаем файл в директорию
				if(is_uploaded_file($tmpFile)){
					$data['files']=$data['files']."<br>\n<br>\nфайл не был загружен при помощи HTTP POST.<br>\n<br>\n";
					$data['result']='error';
				}else{
					$data['files']=$data['files']."<br>\n<br>\nфайл был загружен при помощи HTTP POST.<br>\n<br>\n";
					$data['result']='error';
				}*//*
			}
		}else{
			//ошибка при загрузке файл на сервер
			$data['result']='error';
		}
	}
} */
// /валидация формы
//--------------------------------------------------------------------------------------------------
var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);





// сбор сообщения
//==================================================================================================
	// получаем номер
	if(is_file($numfile_path)){$mess_numb=file_get_contents($numfile_path); // получаем содержимое email шаблона
	}else{$mess_numb=1;}
	file_put_contents($numfile_path,$mess_numb+1);
	// и дату заказа
	$mess_date=date('d.m.Y');
	$mess_date_time=date('d.m.Y H:i');
	
	// производим замены даты и номера заявки в теме писем
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


	/* // производим добавление файлов в виде ссылок
	foreach(array($html_bodyMail,$html_delivery_report) as &$value){
		if(isset($attachments)){
			$listFiles='<ul>';
			foreach($attachments as $attachment){
				// $fileHref=substr($attachment, strpos($attachment, 'feedback/uploads/'));// перенес в process_settings.php
				// $fileName=basename($fileHref);
				// $listFiles.='<li><a href="'.$startPath.$rel_patch_uploads_dir.'">'.$fileName.'</a></li>'."<!-- $fileHref -->";
				$listFiles.='<li><a href="'.$startPath.$attachment['rel_patch_attfile'].'">'.$attachment['name_attfile'].'</a></li>';
			}
			$listFiles.='</ul>';
			$value=str_replace('%email.attachments%', $listFiles, $value);
		}else{
			$value=str_replace('%email.attachments%', ' отсутствуют', $value);
		}
	}
	unset($value);// разрываем ссылку на последний элемент */

	// выполняем замену плейсхолдеров реальными значениями
	foreach(array('html_bodyMail'=>$html_bodyMail,'plain_bodyMail'=>$plain_bodyMail,'html_delivery_report'=>$html_delivery_report,'plain_delivery_report'=>$plain_delivery_report) as $key=>$value){
		// var_log($key,"key",__line__,$_SERVER['PHP_SELF']);
		// var_log($value,"value",__line__,$_SERVER['PHP_SELF']);
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
		
		${$key}=$value;
	}
	unset($value);// разрываем ссылку на последний элемент
	str_log(__line__,$_SERVER['PHP_SELF']);




// отправка формы (данных на почту)
//==================================================================================================
if($data['result']=='success'){
	str_log(__line__,$_SERVER['PHP_SELF']);
	require_once('../phpmailer/PHPMailerAutoload.php');// подключаем файл PHPMailerAutoload.php

	// отправляем письмо адресату, указанному в /feedback/process/process_settings.php
	$mail=new PHPMailer;
	$mail->CharSet='UTF-8';
	$mail->IsHTML(true);  // формат HTML
	$fromName='=?UTF-8?B?'.base64_encode(MAIL_FROM_NAME).'?=';
	$mail->setFrom(MAIL_FROM, $fromName);
	$mail->Subject='=?UTF-8?B?'.base64_encode($mail_subject).'?=';
	$mail->Body=$html_bodyMail;
	$mail->AltBody=$plain_bodyMail;// добавим текстовую версию вашего сообщения
	$mail->addAddress(MAIL_ADDRESS);
	// прикрепление файлов к письму
	if(isset($attachments)){
		str_log(__line__,$_SERVER['PHP_SELF']);
		foreach($attachments as $attachment){
			str_log(__line__,$_SERVER['PHP_SELF']);
			$mail->addAttachment($attachment['full_patch_attfile']);
		}
	}
 	// отправляем письмо
	if(!$mail->send()){
		str_log(__line__,$_SERVER['PHP_SELF']);
		$data['info'][]="не удалось отправить письмо. ошибка ".__line__;// 
		$data['result']='error';
	}else{
		str_log(__line__,$_SERVER['PHP_SELF']);
		$successful_sending=TRUE;
	}
	str_log(__line__,$_SERVER['PHP_SELF']);


	// отправляем письмо адресату, указанному пользователем (отчет о доставке)
	//------------------------------------------------------------------------------------------------
	if(isset($email)){// если пользователь указал свой email и основное письмо отправлено 
		str_log(__line__,$_SERVER['PHP_SELF']);
		if($successful_sending){// если пользователь указал свой email и основное письмо отправлено 
			str_log(__line__,$_SERVER['PHP_SELF']);
			// очистка всех адресов и прикреплёных файлов
			$mail->clearAllRecipients();
			$mail->clearAttachments();
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
			str_log(__line__,$_SERVER['PHP_SELF']);
		}
	}else{
		// основное письмо не отправлено
		str_log(__line__,$_SERVER['PHP_SELF']);
	}
}
str_log(__line__,$_SERVER['PHP_SELF']);



// сохранение данных, введенных пользователем в файл
//--------------------------------------------------------------------------------------------------
// добавить условие, при соблюдении которого вести лог.. при отладке???
if($data['result']=='success'){
	var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);
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
	if(!file_put_contents(dirname(dirname(__FILE__)).'/info/message.txt', $output, FILE_APPEND | LOCK_EX)){
		$data['result']='error';
		var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);
	}
}
var_log($data['result'],"data['result']",__line__,$_SERVER['PHP_SELF']);

// выводим результат отправки клиенту
var_log(json_encode($data),"json_encode(\$data)",__line__,$_SERVER['PHP_SELF']);
echo json_encode($data);