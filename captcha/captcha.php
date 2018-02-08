<?php
session_start();// открываем сессию. открывается в ../process/process.php
require_once("../config.php");// подключаем конфигурационный файл:
require_once("../lib.php");// подключаем конфигурационный файл:
// require_once("../process/process.php");// подключаем конфигурационный файл:


$id="captcha";
if(isset($_GET['id'])){// yo: а это откуда возьмется, я извиняюсь???
	$id=filter_var($_GET['id'],FILTER_SANITIZE_STRING);
}

/*
// присваиваем PHP переменной $string строку символов
	if(FORM_DEBUG===TRUE){// присвоение $string в режиме отладки
		$string="aaaaaa";
	}else{// присвоение $string в боевом режиме
		if(CAPCHA_MODE==="soft"){
			$string="1234567890abcdefghijklmnopqrstuvwxyz";
		}elseif(CAPCHA_MODE==="hard"){
			$string="ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz";
		}
	}
	// var_log($string,"string",__line__,$_SERVER['PHP_SELF']);
*/

// присваиваем переменной $string строку возможных символов
	if(FORM_DEBUG===TRUE){
		$string="a";
	}else{
		if(CAPCHA_MODE==="soft"){
			$string="1234567890abcdefghijklmnopqrstuvwxyz";
		}elseif(CAPCHA_MODE==="hard"){
			$string="ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz";
		}
	}
	// var_log($string,"string",__line__,$_SERVER['PHP_SELF']);



// 		-------
// получаем фон для нашего изображения, вырезав его из исходника
	// нам нужен прямоугольник CAPCHA_WxCAPCHA_H (132х46)
	list($w_src_im,$h_src_im,$type,$attr)=getimagesize(CAPCHA_PATTERN);// получим параметры исходного изображения (фона)

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

/*
	$image=imagecreatefrompng('background.png');// создаем новое изображение из файла */


/* 
 // генерируем изображение текста captcha/captcha.php
	$size=CAPCHA_SIZE;// размер шрифта
	$x=-1*CAPCHA_SPACING/2;// начальная координата x
	$y_=CAPCHA_H/2+CAPCHA_SIZE/2;// 32;// ордината базовой линии текста: высота изображения CAPCHA_H/2 + высота шрифта CAPCHA_SIZE/2
	$color_shadow=imagecolorallocate($dst_im,CAPCHA_S_R,CAPCHA_S_G,CAPCHA_S_B);// цвет текста (rgb)
	$color=imagecolorallocate($dst_im,CAPCHA_R,CAPCHA_G,CAPCHA_B);// цвет текста (rgb)
	$fontfile=dirname(__FILE__).'/'.CAPCHA_FONTFILE;// путь к шрифту TrueType

	$captchastring=""; $i=0;
	while($i<CAPCHA_NUM){// набор необходимого количества символов
		var_log("","NEL",__line__,$_SERVER['PHP_SELF']);
		$angle=rand(-CAPCHA_ANGLE,CAPCHA_ANGLE);// случайное число между -CAPCHA_ANGLE и CAPCHA_ANGLE градусов для поворота текста
		
		// сделать проверку на заглавную букву (вернее на широкую)
		$x=$x+CAPCHA_SPACING-CAPCHA_S_X;// начальная координата x
		$y=$y_+rand(-$size/5,$size/5)-CAPCHA_S_Y;// начальная координата y
		$captcha_letter=substr(str_shuffle($string),0,1);
		$captchastring=$captchastring.$captcha_letter;
		imagettftext($dst_im,$size,$angle,$x,$y,$color_shadow,$fontfile,$captcha_letter);// отрисовываем тень
		// if($bbox){
			// var_log($bbox,"bbox",__line__,$_SERVER['PHP_SELF']);
		// }else{
			// var_log("imagettfbbox вернул FALSE!!!","alert",__line__,$_SERVER['PHP_SELF']);
		// }
		$x=$x+CAPCHA_S_X;// начальная координата x
		$y=$y+CAPCHA_S_Y;// начальная координата y
		imagettftext($dst_im,$size,$angle,$x,$y,$color,$fontfile,$captcha_letter);// отрисовываем символ
			var_log("captcha_letter='$captcha_letter'","mess",__line__,$_SERVER['PHP_SELF']);
			var_log("coord='$x,$y'","mess",__line__,$_SERVER['PHP_SELF']);
			var_log("angle='$angle'","mess",__line__,$_SERVER['PHP_SELF']);
		// imagettfbbox($size,$angle,$fontfile,$text) — Получение параметров рамки, обрамляющей текст написанный TrueType шрифтом
		// возвращает массив из 8 элементов представляющих координаты четырех точек - вершин рамки вокруг текста. В случае ошибки функция вернет FALSE.
				// ключ 	содержимое
				// 0 	нижний левый угол, X координата
				// 1 	нижний левый угол, Y координата
				// 2 	нижний правый угол, X координата
				// 3 	нижний правый угол, Y координата
				// 4 	верхний правый угол, X координата
				// 5 	верхний правый угол, Y координата
				// 6 	верхний левый угол, X координата
				// 7 	верхний левый угол, Y координата
		$bbox=imagettfbbox($size,$angle,$fontfile,$captcha_letter);
		var_log("A='$bbox[0],$bbox[1]'; C='$bbox[4],$bbox[5]';","mess",__line__,$_SERVER['PHP_SELF']);
 */



	// генерируем изображение текста captcha/captcha.php
	$color_shadow=imagecolorallocate($dst_im,CAPCHA_S_R,CAPCHA_S_G,CAPCHA_S_B);// цвет тени (rgb)
	$color=imagecolorallocate($dst_im,CAPCHA_R,CAPCHA_G,CAPCHA_B);// цвет текста (rgb)
	$fontfile=dirname(__FILE__).'/'.CAPCHA_FONTFILE;// путь к шрифту TrueType
	
	// переменные для первого символа
	$y_bl=CAPCHA_H/2+CAPCHA_SIZE/2;// ордината базовой линии текста
	$size=CAPCHA_SIZE;// размер шрифта
	$x=CAPCHA_SIZE/30*rand(10,25);// начальная координата x первого символа: три-пять пробелов
	$y=$y_bl+rand(-$size/5,$size/5);// начальная координата y первого символа: ордината базовой линии текста +- 1/5 высоты шрифта
	$x_=$x+CAPCHA_S_X;// начальная координата x тени
	$y_=$y+CAPCHA_S_Y;// начальная координата y тени
	$angle=rand(-CAPCHA_ANGLE,CAPCHA_ANGLE);// случайное число между -CAPCHA_ANGLE и CAPCHA_ANGLE градусов для поворота текста
	$d_angle=3*CAPCHA_ANGLE/CAPCHA_NUM;// изменение угла поворота текста
	
	$rand_direct=rand(-1,1);// выбираем направление изменения угла; 
	if($rand_direct>0){$angle_direct=1;// направление изменения угла: +1: увеличение;
	}else{$angle_direct=-1;}// направление изменения угла: -1: уменьшение;
	 

	$captchastring=""; $i=0;
	while($i<CAPCHA_NUM){// набор необходимого количества символов

		$captcha_letter=substr(str_shuffle($string),0,1);
		$captchastring=$captchastring.$captcha_letter;
			// var_log("","NEL",__line__,$_SERVER['PHP_SELF']);
			// var_log("координаты символа №$i $captcha_letter($x,$y)","mess",__line__,$_SERVER['PHP_SELF']);

		imagettftext($dst_im,$size,$angle,$x_,$y_,$color_shadow,$fontfile,$captcha_letter);// отрисовываем тень
		imagettftext($dst_im,$size,$angle,$x,$y,$color,$fontfile,$captcha_letter);// отрисовываем символ
			// var_log("captcha_letter='$captcha_letter'","mess",__line__,$_SERVER['PHP_SELF']);
			// var_log("coord='$x,$y'","mess",__line__,$_SERVER['PHP_SELF']);
			// var_log("angle='$angle'","mess",__line__,$_SERVER['PHP_SELF']);

		// получаем координаты следующего символа
		$bbox=imagettfbbox($size,$angle,$fontfile,$captcha_letter);
			// var_log($bbox,"bbox",__line__,$_SERVER['PHP_SELF']);
	
		// angle>0 против часовой
		if($angle>0){// если угол предыдущего символа > 0, то абсцисса нижнего левого угла больше абсциссы верхнего угла
			$x=$x+$bbox[2]+CAPCHA_SPACING;// ориентируемся на абсциссу нижнего левого угла
			$y=$y_bl+$bbox[3];
			if($angle>CAPCHA_ANGLE-$d_angle){$angle_direct=-1;}// если угол предыдущего символа приближается к максимальному
		}else{
			$x=$x+$bbox[4]+CAPCHA_SPACING;// ориентируемся на абсциссу верхнего левого угла
			$y=$y_bl+$bbox[3];
			if($angle<-CAPCHA_ANGLE+$d_angle){$angle_direct=1;}// если угол предыдущего символа приближается к минимальному
		}
		$angle=$angle+$angle_direct*$d_angle;

		

/* 		if($angle>CAPCHA_ANGLE-CAPCHA_ANGLE/CAPCHA_NUM){// если угол предыдущего символа приближается к максимальному
			$y=$y_bl+$bbox[3];
			$angle=$angle-CAPCHA_ANGLE/CAPCHA_NUM/2;
		}else{
			$y=$y_bl+$bbox[3];
			$angle=$angle+CAPCHA_ANGLE/CAPCHA_NUM/2;
		}
 */		
		$x_=$x+CAPCHA_S_X;// начальная координата x тени
		$y_=$y+CAPCHA_S_Y;// начальная координата y тени

		// imagettfbbox($size,$angle,$fontfile,$text) — Получение параметров рамки, обрамляющей текст написанный TrueType шрифтом
		// возвращает массив из 8 элементов представляющих координаты четырех точек - вершин рамки вокруг текста. В случае ошибки функция вернет FALSE.
				// ключ 	содержимое
				// 0 	нижний левый угол, X координата
				// 1 	нижний левый угол, Y координата
				// 2 	нижний правый угол, X координата
				// 3 	нижний правый угол, Y координата
				// 4 	верхний правый угол, X координата
				// 5 	верхний правый угол, Y координата
				// 6 	верхний левый угол, X координата
				// 7 	верхний левый угол, Y координата
		$i++;
	}
	unset($i,$string);

	
// инициализируем переменную сессии с помощью сгенерированной подстроки $captchastring, содержащей 6 символов
$_SESSION[$id]=$captchastring;
var_log("captchastring='$captchastring'","mess",__line__,$_SERVER['PHP_SELF']);
	

// выводим изображение
	header('Content-type: image/png');
	imagepng($dst_im);

// освобождение памяти
imagedestroy($dst_im);
imagedestroy($src_im);
