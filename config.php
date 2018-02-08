<?php
// выбор механизма прикрепления файлов
	$use_dropzone=TRUE;
	// $use_dropzone=FALSE;


//==================================================================================================
// общие настройки:
//==================================================================================================
	// стартовый путь ('http://mydomain.ru/')
	$startPath='http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/';
	// полный путь от корня файловой системы к корневой директории скрипта
	$rel_path_feedback=dirname(dirname(__FILE__));
	// полный путь от корня файловой системы к директории для хранения загружаемых файлов
	$patch_uploads_dir=dirname(__FILE__)."/uploads/";
	// относительный путь к рандомным директориям от корневой директории скрипта
	$rel_patch_uploads_dir="src/feedback/uploads/";
	// путь к временной директории
	$patch_tmp_dir=dirname(__FILE__)."/tmp/";
	// директория для хранения логов
	$patch_log_dir=dirname(__FILE__)."/logs/";
	// полный путь от корня файловой системы к лог-файлу
	// $patch_log_file="$patch_log_dir".date('Y_m_d__H_i_s');
	define("PATCH_LOG_FILE","$patch_log_dir".date('Y_m_d'));
	// путь к файлу с нумерацией
	$numfile_path=dirname(__FILE__)."/num.tmp";

	// email отправителя
	define("MAIL_FROM","no-reply@".$_SERVER['HTTP_HOST']);
	// имя отправителя
	define("MAIL_FROM_NAME","ЮгАвтоТрак");
	// тема письма
	define("COMPANY","ООО ЮгАвтоТрак");
	// тема письма
	define("PHONE","+7(863)000-00-00");
	// тема письма
	$mail_subject="Поступил заказ №_replace_mess_numb от _replace_date";// тема письма
	$mail_subject_client="Заказ №_replace_mess_numb от _replace_date принят.";// тема отчета о доставке сообщения
	// конвертация punicode
	$domainname_utf8=(substr($_SERVER['HTTP_HOST'],0,4)=='xn--')?idn_to_utf8($_SERVER['HTTP_HOST']):$_SERVER['HTTP_HOST'];

	// email адресата
	//define("MAIL_ADDRESS","yugautotruck@ya.ru");
	define("MAIL_ADDRESS","yakoffka@mail.ru");
				// define("MAIL_ADDRESS","web-on3wr@mail-tester.com");//https://www.mail-tester.com/web-mfs3g 10 из 10!!!
				// define("MAIL_ADDRESS","web-bwj58@mail-tester.com");// тестирование почты (Проверка тела письма на спам mail-tester.com)
				
				// проверка на письмо и отчет. и там и там 10!!
				// https://www.mail-tester.com/web-prg9y	
				// https://www.mail-tester.com/web-on3wr


// настройки текстовых полей:  (вынести в общий массив с названиями полей, валидацией и прочим!!!)
//--------------------------------------------------------------------------------------------------
	$page_title="Форма обратной связи";
	$panel_title="Запрос детали по VIN";

	// максимальный размер файла 512Кбайт (512*1024=524288)
	// define("MAX_FILE_SIZE=524288;
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
	define("MAX_FILE_SIZE","524288");
	// разрешённые расширения файлов
	$allowedExtensions=array('gif', 'jpg', 'png');







//==================================================================================================
// настройка капчи:
//==================================================================================================
	// режим
	// define("CAPCHA_MODE","soft");// только строчные латинские буквы и цифры
	define("CAPCHA_MODE","hard");// ПРОПИСНЫЕ и строчные латинские буквы и цифры. есть шанс спутать 'l' с 'I', '0' с 'O'
	
	// путь к фоновому изображению
	// define("CAPCHA_PATTERN","pattern-simple-numbers-on-blackboard-background.jpg");
	define("CAPCHA_PATTERN","school_pattern_02-280x235.jpg");
	// количество символов в капче и имени рандомной директории
	define("CAPCHA_NUM","6");//6
	// необходимые ширина и высота получаемого изображения
	define("CAPCHA_SIZE","22");// размер шрифта
	// путь к шрифту TrueType
	// define("CAPCHA_FONTFILE","Arbat.ttf");
	// define("CAPCHA_FONTFILE","monotipe_corsiva.ttf");
	define("CAPCHA_FONTFILE","georgia.ttf");
	// define("CAPCHA_FONTFILE","oswald.ttf");
	// define("CAPCHA_FONTFILE","Copyist.ttf");
	// define("CAPCHA_FONTFILE","Harrington.ttf");
	// необходимые ширина и высота получаемого изображения
	define("CAPCHA_W",160);//160
	define("CAPCHA_H",50);
	// цвет текста
	define("CAPCHA_R",0);
	define("CAPCHA_G",56);
	define("CAPCHA_B",6);
	// максимальный угол наклона текста, градусов
	define("CAPCHA_ANGLE",10);
	// интервал между символами, в размере шрифта
	// define("CAPCHA_SPACING",CAPCHA_SIZE*1/9);
	define("CAPCHA_SPACING",0);

	// цвет тени
	define("CAPCHA_S_R",255);
	define("CAPCHA_S_G",255);
	define("CAPCHA_S_B",255);
	// смещение тени
	// define("CAPCHA_S_X",CAPCHA_SIZE/20);
	// define("CAPCHA_S_Y",CAPCHA_SIZE/20);
	define("CAPCHA_S_X",1);
	define("CAPCHA_S_Y",1);






//==================================================================================================
// настройки режима отладки:
//==================================================================================================
// в режиме отладки генерируется капча "aaaaaa", всем полям присваивается плейсхолдеры (нет необходимости каждый раз заполнять поля вручную), 
	define("FORM_DEBUG",TRUE);
	// define("FORM_DEBUG",FALSE);

// присвоение плейсхолдеров при включенном режим отладки (вынести в общий массив с названиями полей, валидацией и прочим!!!)
if(FORM_DEBUG===TRUE){
	define("CAPTCHA_TMP",$patch_tmp_dir."val_captcha.php");// иначе не получается вызвать актуальное значение $captchastring ()
	
	$val_name="Яков";
	$val_phone="+7(928)000-00-00";
	$val_email="yakoffka@mail.ru";
	$val_car_brand="ВАЗ";
	$val_vin="1ZVHT82H485113456";
	$val_description_goods="Реле стартера втягивающее WA66-113-SL или аналог";
	$val_captcha="";$i=0;while($i<CAPCHA_NUM){$val_captcha=$val_captcha.'a';$i++;}// набор необходимого количества символов
}else{
	$val_name="";
	$val_phone="";
	$val_email="";
	$val_car_brand="";
	$val_vin="";
	$val_description_goods="";
}
