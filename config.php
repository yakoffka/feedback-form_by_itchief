<?php
// настройки режима отладки:
//--------------------------------------------------------------------------------------------------
// в режиме отладки генерируется капча "aaaaaa", всем полям присваивается плейсхолдеры (нет необходимости каждый раз заполнять поля вручную), 
	// включение/отключение режима отладки
	const FORM_DEBUG = TRUE;
	// const FORM_DEBUG = FALSE;

	// настройка режима капчи:
	const CAPCHA_MODE = 'soft';// только строчные латинские буквы и цифры
	// const CAPCHA_MODE = 'hard';// ПРОПИСНЫЕ и строчные латинские буквы и цифры. есть шанс спутать 'l' с 'I', '0' с 'O'


// общие настройки:
//--------------------------------------------------------------------------------------------------
	// стартовый путь ('http://mydomain.ru/')
	$startPath = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/';
	// относительный путь к корневой директории от корня сайта (например, 'src/')
	$rel_path_feedback = dirname(dirname(__FILE__));
	// директория для хранения загружаемых файлов
	$uploadPath = dirname(__FILE__)."/uploads/";
	//yo путь к рандомным директориям от корня сайта ПОПРАВИТЬ!!!
	$relPatchUploads = 'src/feedback/uploads/';
	// email отправителя
	// const MAIL_FROM = 'no-reply@кириллица.рф.dragoon.pw';
	// const MAIL_FROM = 'no-reply@'.$_SERVER['HTTP_HOST'];
	define("MAIL_FROM",'no-reply@'.$_SERVER['HTTP_HOST']);
	// имя отправителя
	const MAIL_FROM_NAME = 'ЮгАвтоТрак';
	// тема письма
	const COMPANY = 'ООО ЮгАвтоТрак';
	// тема письма
	const PHONE = '+7(863)000-00-00';
	// тема письма
	// const MAIL_SUBJECT = 'Заявка с сайта '.$_SERVER['HTTP_HOST'];
	define("MAIL_SUBJECT",'Заявка с сайта '.$_SERVER['HTTP_HOST']);
	// email адресата
	//const MAIL_ADDRESS = 'yugautotruck@ya.ru';
	//const MAIL_ADDRESS = 'yakoffka@mail.ru';
	const MAIL_ADDRESS = 'web-bwj58@mail-tester.com';// тестирование почты (Проверка тела письма на спам mail-tester.com)
	$copyright = "<a style='text-decoration:none!important;' href='$startPath'>%email.year% &copy; ".COMPANY." ".PHONE.".</a>
	";
	// копирайт в теле письма
	
	const MAIL_SUBJECT_CLIENT = 'Ваше сообщение доставлено';// тема письма, отправляемого пользователю для информирования его о доставке сообщения



// настройки текстовых полей:
//--------------------------------------------------------------------------------------------------
	// максимальный размер файла 512Кбайт (512*1024=524288)
	// const MAX_FILE_SIZE = 524288;



// настройки формы:
//--------------------------------------------------------------------------------------------------
	// максимальный размер файла 512Кбайт (512*1024=524288)
	const MAX_FILE_SIZE = 524288;
	// разрешённые расширения файлов
	$allowedExtensions = array('gif', 'jpg', 'png');
