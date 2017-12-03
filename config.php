<?php
// настройки режима отладки:
//--------------------------------------------------------------------------------------------------
// в режиме отладки генерируется капча "aaaaaa", всем полям присваивается плейсхолдеры (нет необходимости каждый раз заполнять поля вручную), 
	// включение/отключение режима отладки
	// const FORM_DEBUG = TRUE;
	const FORM_DEBUG = FALSE;


// общие настройки:
//--------------------------------------------------------------------------------------------------
	// стартовый путь ('http://mydomain.ru/')
	$startPath = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/';
	// относительный путь к корневой директории от корня сайта (например, 'src/')
	$rel_path_feedback = dirname(dirname(__FILE__)) . '/';
	// директория для хранения загружаемых файлов
	$uploadPath = "$rel_path_feedback/uploads/";
	//yo путь к рандомным директориям от корня сайта ПОПРАВИТЬ!!!
	$relPatchUploads = 'src/feedback/uploads/';
	// email отправителя
	const MAIL_FROM = 'no-reply@yugautotruck.dragoon.pw';
	// имя отправителя
	const MAIL_FROM_NAME = 'ЮгАвтоТрак';
	// тема письма
	const MAIL_SUBJECT = 'Заявка с сайта yugautotruck.dragoon.pw';
	// email адресата
	const MAIL_ADDRESS = 'yakoffka@mail.ru';
	// const MAIL_ADDRESS = 'web-a927k@mail-tester.com';// тестирование почты (Проверка тела письма на спам mail-tester.com)
	
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
