<?php
session_start();// открываем сессию
require_once('../config.php');// подключаем конфигурационный файл:


$id='captcha';
if(isset($_GET['id'])){// yo: а это откуда возьмется, я извиняюсь???
	$id=filter_var($_GET['id'],FILTER_SANITIZE_STRING);
}

// присваиваем PHP переменной $string строку символов
	if(FORM_DEBUG===TRUE){// присвоение $string в режиме отладки
		$string='aaaaaa';
	}else{// присвоение $string в боевом режиме
		if(CAPCHA_MODE==='soft'){
			$string='1234567890abcdefghijklmnopqrstuvwxyz';
		}elseif(CAPCHA_MODE==='hard'){
			$string='ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
		}
	}


// 		-------
// получаем фон для нашего изображения, вырезав его из исходника
	// нам нужен прямоугольник CAPCHA_WxCAPCHA_H (132х46)
	list($w_src_im,$h_src_im,$type,$attr)=getimagesize("school_pattern_02-280x235.jpg");// получим параметры исходного изображения (фона)

	$src_im=imagecreatefromjpeg(CAPCHA_PATTERN);
	$dst_im=imagecreatetruecolor(CAPCHA_W,CAPCHA_H);// создаем новое пустое полноцветное изображение
	
	// копируем на пустое изображение часть исходника
	// imagecopy($dst_im,$src_im,$dst_x,$dst_y,$src_x,$src_y,$src_w,$src_h);
		// dst_im - Ресурс целевого изображения.
		// src_im - Ресурс исходного изображения.
		// dst_x - x-координата результирующего изображения.
		// dst_y - y-координата результирующего изображения.
		// src_x - x-координата исходного изображения.
		// src_y - y-координата исходного изображения.
		// src_w - Ширина исходного изображения.
		// src_h - Высота исходного изображения.
		$src_x=rand(0,($w_src_im-CAPCHA_W));
		$src_y=rand(0,($h_src_im-CAPCHA_H));
	imagecopy($dst_im,$src_im,0,0,$src_x,$src_y,CAPCHA_W,CAPCHA_H);


// 		-------
// рисуем поверх фона капчу

// получаем первые 6 символов после их перемешивания с помощью функции str_shuffle
	// $captchastring=substr(str_shuffle($string),0,6);
// получаем 6 символов после их перемешивания с помощью функции str_shuffle
	// $captchastring=substr(str_shuffle($string),0,6);

	/* $captchastring="";
	$i=0;while($i<CAPCHA_NUM){$captchastring=$captchastring.substr(str_shuffle($string),0,1);$i++;}// набор необходимого количества символов
	unset($i,$string);
// инициализируем переменной сессии с помощью сгенерированной подстроки $captchastring, содержащей 6 символов
	$_SESSION[$id]=$captchastring; */

/* // генерируем изображение captcha/captcha.php
	$image=imagecreatefrompng('background.png');// создаем новое изображение из файла */
	$size=CAPCHA_SIZE;// размер шрифта
	$x=-1*CAPCHA_SPACING/2;// начальная координата x
	$y_=32;// ордината оси y???
	$color=imagecolorallocate($dst_im,CAPCHA_R,CAPCHA_G,CAPCHA_B);// цвет текста (rgb)
	// $fontfile=dirname(__FILE__).'/oswald.ttf';// путь к шрифту TrueType
	$fontfile=dirname(__FILE__).'/'.CAPCHA_FONTFILE;// путь к шрифту TrueType

	$captchastring="";$i=0;
	while($i<CAPCHA_NUM){// набор необходимого количества символов
		$angle=rand(-10, 10);// случайное число между -10 и 10 градусов для поворота текста
		$x=$x+CAPCHA_SPACING;// начальная координата x
		$y=$y_+rand(-$size/4,$size/4);// начальная координата y
		$captcha_letter=substr(str_shuffle($string),0,1);
		$captchastring=$captchastring.$captcha_letter;
		imagettftext($dst_im,$size,$angle,$x,$y,$color,$fontfile,$captcha_letter);
		$i++;
	}
	unset($i,$string);

	
// инициализируем переменной сессии с помощью сгенерированной подстроки $captchastring, содержащей 6 символов
	$_SESSION[$id]=$captchastring;

// выводим изображение
	header('Content-type: image/png');
	imagepng($dst_im);

// освобождение памяти
imagedestroy($dst_im);
imagedestroy($src_im);
