<?php
// настройки режима отладки:
//--------------------------------------------------------------------------------------------------
// в режиме отладки генерируется капча "aaaaaa", всем полям присваивается плейсхолдеры (нет необходимости каждый раз заполнять поля вручную), 
	// включение/отключение режима отладки
	const FORM_DEBUG=TRUE;
	// const FORM_DEBUG=FALSE;

	// настройка режима капчи:
	const CAPCHA_MODE='soft';// только строчные латинские буквы и цифры
	// const CAPCHA_MODE='hard';// ПРОПИСНЫЕ и строчные латинские буквы и цифры. есть шанс спутать 'l' с 'I', '0' с 'O'


// общие настройки:
//--------------------------------------------------------------------------------------------------
	// стартовый путь ('http://mydomain.ru/')
	$startPath='http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/';
	// относительный путь к корневой директории от корня сайта (например, 'src/')
	$rel_path_feedback=dirname(dirname(__FILE__));
	// директория для хранения загружаемых файлов
	$uploadPath=dirname(__FILE__)."/uploads/";
	//yo путь к рандомным директориям от корня сайта ПОПРАВИТЬ!!!
	$relPatchUploads='src/feedback/uploads/';
	// директория для хранения логов
	$log_Path=dirname(__FILE__)."/logs/";
	$log_file_name="$log_Path".date('Y_m_d__H_i_s');
	// путь к файлу с нумерацией
	$numfile_path=dirname(__FILE__)."/num.tmp";

	// email отправителя
	define("MAIL_FROM",'no-reply@'.$_SERVER['HTTP_HOST']);
	// имя отправителя
	const MAIL_FROM_NAME='ЮгАвтоТрак';
	// тема письма
	const COMPANY='ООО ЮгАвтоТрак';
	// тема письма
	const PHONE='+7(863)000-00-00';
	// тема письма
	$mail_subject="Поступил заказ №_replace_mess_numb от _replace_date";// тема письма
	$mail_subject_client="Заказ №_replace_mess_numb от _replace_date принят.";// тема отчета о доставке сообщения
	// конвертация punicode
	$domainname_utf8=(substr($_SERVER['HTTP_HOST'],0,4)=='xn--')?idn_to_utf8($_SERVER['HTTP_HOST']):$_SERVER['HTTP_HOST'];

	// email адресата
	//const MAIL_ADDRESS='yugautotruck@ya.ru';
	const MAIL_ADDRESS='yakoffka@mail.ru';
				// const MAIL_ADDRESS='web-on3wr@mail-tester.com';//https://www.mail-tester.com/web-mfs3g 10 из 10!!!
				// const MAIL_ADDRESS='web-bwj58@mail-tester.com';// тестирование почты (Проверка тела письма на спам mail-tester.com)
				
				// проверка на письмо и отчет. и там и там 10!!
				// https://www.mail-tester.com/web-prg9y	
				// https://www.mail-tester.com/web-on3wr


// настройки текстовых полей:
//--------------------------------------------------------------------------------------------------
	// максимальный размер файла 512Кбайт (512*1024=524288)
	// const MAX_FILE_SIZE=524288;
	$name_block_1="описание узла или агрегата: ";
	$name_fild_1="наименование: ";
	$name_fild_2="прикрепленные файлы: ";

	$name_block_2="информация о ТС: ";
	$name_fild_3="марка автомобиля: ";
	$name_fild_4="VIN автомобиля: ";

	$name_block_3="контактные данные заказчика: ";
	$name_fild_5="имя: ";
	$name_fild_6="телефон заказчика: ";
	$name_fild_7="email заказчика: ";
	
	$start_mess_adm="С сайта $domainname_utf8 поступил заказ. Детали заказа:";
	$start_mess_user="Здравствуйте, _replace_nameuser, Ваш заказ принят на рассмотрение. Спасибо за проявленный интерес к нашей компании, в ближайшее время наши специалисты свяжутся с Вами.";
	$end_mess_user="Данное сообщение отправлено роботом, отвечать на него не нужно. Письма в данном почтовом ящике не отслеживаются.";

	$html_table="
	<table style='width:100%;padding:0 15% 1em;' border='0' cellpadding='0' cellspacing='0'>
		<tbody>
			<tr>
				<td colspan='2' style='background-color:#0f94d7;text-align:left;padding:0 1em;color:#fff;'>$name_block_1</td>
			</tr>
			<tr>
				<td style='margin:0 1em;text-align:left;border-bottom:1px dotted #0f94d7;'>$name_fild_1</td>
				<td style='margin:0 1em;text-align:right;border-bottom:1px dotted #0f94d7;'>_replace_description_goods</td>
			</tr>
			<tr>
				<td style='margin:0 1em;text-align:left;'>$name_fild_2</td>
				<td style='margin:0 1em;text-align:right;'>_replace_is_attach</td>
			</tr>
			<tr>
				<td colspan='2' style='background-color:#0f94d7;text-align:left;padding:0 1em;color:#fff;'>$name_block_2</td>
			</tr>
			<tr>
				<td style='margin:0 1em;text-align:left;border-bottom:1px dotted #0f94d7;'>$name_fild_3</td>
				<td style='margin:0 1em;text-align:right;border-bottom:1px dotted #0f94d7;'>_replace_car_brand</td>
			</tr>
			<tr>
				<td style='margin:0 1em;text-align:left;'>$name_fild_4</td>
				<td style='margin:0 1em;text-align:right;'>_replace_vehicle_identification_number</td>
			</tr>
			<tr>
				<td colspan='2' style='background-color:#0f94d7;text-align:left;padding:0 1em;color:#fff;'>$name_block_3</td>
			</tr>
			<tr>
				<td style='margin:0 1em;text-align:left;border-bottom:1px dotted #0f94d7;'>$name_fild_5</td>
				<td style='margin:0 1em;text-align:right;border-bottom:1px dotted #0f94d7;'>_replace_nameuser</td>
			</tr>
			<tr>
				<td style='margin:0 1em;text-align:left;border-bottom:1px dotted #0f94d7;'>$name_fild_6</td>
				<td style='margin:0 1em;text-align:right;border-bottom:1px dotted #0f94d7;'>_replace_phone</td>
			</tr>
			<tr>
				<td style='margin:0 1em;text-align:left;'>$name_fild_7</td>
				<td style='margin:0 1em;text-align:right;'>_replace_emailuser</td>
			</tr>
			<tr>
				<td colspan='2' style='background-color:#0f94d7;text-align:center;color:#fff;'>№_replace_mess_numb от _replace_date</td>
			</tr>
		</tbody>
	</table>
	<p style='text-align:center;'>_replace_html_copyright</p>";

		
		
		
	$plain_table="\r\n\r\n\t$name_block_1\r\n-------------------------\r\n$name_fild_1: _replace_description_goods\r\n$name_fild_2: _replace_is_attach\r\n\r\n\t$name_block_2\r\n-------------------------\r\n$name_fild_3: _replace_car_brand\r\n$name_fild_4: _replace_vehicle_identification_number\r\n\r\n\t$name_block_3\r\n-------------------------\r\n$name_fild_5: _replace_nameuser\r\n$name_fild_5: _replace_phone\r\n$name_fild_5: _replace_emailuser\r\n\r\n-----\r\n _replace_date\r\n\r\n_replace_plain_copyright";

	// копирайт в теле письма
	$html_copyright="<a style='text-decoration:none!important;' href='$startPath'>_replace_year &copy; ".COMPANY." ".PHONE.".</a>";
	$plain_copyright="<$startPath'>\r\n_replace_year ".COMPANY." ".PHONE;



// настройки формы:
//--------------------------------------------------------------------------------------------------
	// максимальный размер файла 512Кбайт (512*1024=524288)
	const MAX_FILE_SIZE=524288;
	// разрешённые расширения файлов
	$allowedExtensions=array('gif', 'jpg', 'png');
