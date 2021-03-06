<!DOCTYPE html>
<?php /*оригинал: https://itchief.ru/lessons/php/feedback-form-for-website*/
	// session_start();// открываем сессию. открывается в ../process/process.php
	// $_SESSION['date']=date('l \t\h\e jS');
	$name_script=$_SERVER['PHP_SELF'];// получим имя скрипта для использовании в логировании
	// подключаем конфигурационный файл:
	$file_templ_conf="config.php.template";
	$file_conf="config.php";
	$file_lib="lib.php";
	
	if(file_exists($file_conf)){
		require_once($file_conf);
	}elseif(file_exists($file_templ_conf)){
		echo "<h3 class='warning_feedback'>пожалуйста переименуйте файл '$file_templ_conf' в '$file_conf' и произведите в нем необходимые настройки.</h2>";
	}else{echo "<h3 class='alert_feedback'>не найден конфигурационный файл. пожалуйста перезалейте скрипт.</h2>";}
	require_once($file_lib);
	var_log("\n","NEL",__line__,$_SERVER['PHP_SELF']);
	var_log("\n","NEL",__line__,$_SERVER['PHP_SELF']);
	var_log("------------------------------------------------------------","mess",__line__,$_SERVER['PHP_SELF']);
	var_log("загрузка index.php","mess",__line__,$_SERVER['PHP_SELF']);
	// var_log($file_conf,"file_conf",__line__,$_SERVER['PHP_SELF']);

/* 	// если от предыдущей сессии остался временный файл, то удалим его.
	if(file_exists(CAPTCHA_TMP)){
		unlink(CAPTCHA_TMP);
	}
 */	

?>

<html lang='ru'>
<head>
	<meta charset='utf-8'>
	<title><?php echo $page_title;?></title>
	<link rel='stylesheet' href='vendors/bootstrap/css/bootstrap.min.css'>
	<link rel='stylesheet' href='vendors/jgrowl/jquery.jgrowl.min.css'>
	<link rel='stylesheet' href='vendors/dropzone/dropzone.css'>
	<link rel='stylesheet' href='css/main.css'>
  <style>
  </style>
	
	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>
</head>
<body>
	<!--div class='container'-->
	<div class='center'>
		<!--div class='row'-->
		<div class=''>
			<!--div class='col-sm-6 col-sm-offset-3'-->
			<div class='col-sm-9'>
				<h1 class='text-center page-header'><?php echo $page_title;?></h1>
				<div class='panel panel-success'>
					<div class='panel-heading'>
						<h2 class='h3 panel-title'><?php echo $panel_title;?></h2>
					</div>
					<div class='panel-body'>

						<!-- Форма обратной связи -->
						<form id='feedbackForm' action='process/process.php' enctype='multipart/form-data' novalidate>
							<div class='row'>

								<div class='col-sm-4'>
									<!-- Имя пользователя -->
									<div class='form-group has-feedback'>
										<label for='name' class='control-label'>Имя *</label>
										<input id='name' type='text' name='name' class='form-control' value='<?php echo "$val_name";?>' placeholder='Имя' minlength='2' required='required'>
										<span class='glyphicon form-control-feedback'></span>
									</div>
								</div>

								<div class='col-sm-4'>
									<!-- Телефон пользователя -->
									<div class='form-group has-feedback'>
										<label for='phone' class='control-label'>Телефон *</label>
										<input type='text' name='phone' id='yo_phone' class='form-control yo_phone' value='<?php echo "$val_phone";?>' maxlength='16' placeholder='+7(863)000-00-00'>
										<span class='glyphicon form-control-feedback'></span>
									</div>
								</div>

								<div class='col-sm-4'>
									<!-- email пользователя -->
									<div class='form-group has-feedback'>
										<label for='email' class='control-label'>email *</label>
										<input id='email' type='email' name='email' required='required'
											class='form-control'
											value='<?php echo "$val_email";?>' placeholder='adress@mail.ru'>
										<span class='glyphicon form-control-feedback'></span>
									</div>
								</div>

								<div class='col-sm-6'>
									<!-- марка автомобиля -->
									<div class='form-group has-feedback'>
										<label for='car_brand' class='control-label'>марка автомобиля *</label>
										<input id='car_brand' type='text' name='car_brand' class='form-control'
											value='<?php echo "$val_car_brand";?>' placeholder='марка автомобиля' minlength='10' required='required'>
										<span class='glyphicon form-control-feedback'></span>
									</div>
								</div>

								<div class='col-sm-6'>
									<!-- VIN -->
									<div class='form-group has-feedback'>
										<label for='vehicle_identification_number' class='control-label'>VIN (17 символов)*</label>
										<input type='text' name='vehicle_identification_number' id='yo_vin' class='form-control'
											value='<?php echo "$val_vin";?>' placeholder='1ZVHT82H485113456' minlength='17' required='required'>
										<span class='glyphicon form-control-feedback'></span>
									</div>
								</div>

							</div>

							<!-- Описание запчасти -->
							<div class='form-group has-feedback'>
								<label for='description_goods' class='control-label'>Описание запчасти *</label>
								<textarea id='description_goods' name='description_goods' class='form-control'
									rows='3' value='' placeholder='Описание запчасти' minlength='20'
									maxlength='500' required='required'><?php echo "$val_description_goods";?></textarea>
							</div>

							<!-- Сообщение пользователя -->
							<!--div class='form-group has-feedback'>
								<label for='message' class='control-label'>Сообщение</label>
								<textarea id='message' name='message' class='form-control'
									rows='3' placeholder='Сообщение (не менее 20 символов)' minlength='20'
									maxlength='500' required='required'></textarea>
							</div-->

							<!-- Файлы, для прикрепления к форме -->
							<?php
							if($use_dropzone){
								echo "
									<div class='form-group' id='mydropzone'>
										<!--input type='file' name='images[]' style='display: none !important;'-->
										<!--input type='file' name='img_dropzone[]' style='display: none !important;'-->
										<input type='file' name='attachment[]' style='display: none !important;'>
									</div>
								";
							}else{
								echo "
									<div class='form-group'>
										<p style='font-weight: 700; margin-bottom: 0;'>Прикрепить к сообщению файлы (до <span
											class='countFiles'></span>):</p>
										<p class='small success'>jpg, jpeg, bmp, gif, png (до 512 Кбайт)</p>
										<div class='attachments'>
											<input type='file' name='attachment[]'>
											<p style='margin-top: 3px; margin-bottom: 3px; color: #ff0000;'></p>
										</div>
									</div>
								";
							}

							?>

							<!-- Капча -->
							<div class='captcha'>
								<img class='img-captcha' src='captcha/captcha.php' data-src='captcha/captcha.php'>
								<div class='btn btn-default refresh-captcha'><i class='glyphicon glyphicon-refresh'></i>
									Обновить
								</div>
								<div class='form-group has-feedback' style='margin-top: 10px;'>
									<label for='captcha' class='control-label'>Код, показанный на изображении</label>
									<input type='text' name='captcha' maxlength='6' required='required' id='captcha'
										class='form-control captcha' placeholder='<?php
										// var_log("рисуем placeholder_captcha","mess",__line__,$_SERVER['PHP_SELF']);
										$placeholder_captcha="";
										$i=0;
										while($i<CAPCHA_NUM){
											$placeholder_captcha=$placeholder_captcha.'*';// набор необходимого количества символов
											$i++;
										}
										echo "$placeholder_captcha";
										unset($i,$placeholder_captcha);
									?>' autocomplete='off' value='<?php
										if(FORM_DEBUG===TRUE){echo $val_captcha;};
									?>'>
									<span class='glyphicon form-control-feedback'></span>
								</div>
							</div>

							
							<!-- Пользовательское солашение -->
							<div class='checkbox'>
								<label>
									<input type='checkbox' name='agree'>Нажимая кнопку, я принимаю условия
									<a href='#'>Пользовательского соглашения</a> и даю своё согласие на обработку моих
									персональных данных, в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О
									персональных
									данных».
								</label>
							</div>

							<!-- Кнопка для отправки формы -->
							<button type='submit' class='btn btn-success pull-right' disabled='disabled'>Отправить сообщение</button>

							<!-- Индикация загрузки данных формы на сервер -->
							<div class='clearfix'></div>
							<div class='progress' style='display:none; margin-top: 20px;'>
								<div class='progress-bar progress-bar-success progress-bar-striped' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 0'>
									<span class='sr-only'>0%</span>
								</div>
							</div>
							<div class='clearfix'></div>

						</form>

						<!-- Сообщение об успешной отправки формы -->
						<div class='alert alert-warning success-message hidden'>
							Форма успешно отправлена. Нажмите на <a class='show-form' href='#'>ссылку</a>, чтобы отправить ещё одно сообщение.
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<!--script src='vendors/jquery/jquery-3.2.1.min.js'></script-->
	<script src='vendors/bootstrap/js/bootstrap.min.js'></script>
  <script src='vendors/dropzone/dropzone.js'></script>
  <!--script src='js/mydropzone.js'></script-->
	<script src='vendors/jgrowl/jquery.jgrowl.min.js'></script>
	<script src='js/main.js'></script>
	<!--script src='vendors/mask/jquery.mask.min.js\'></script-->
	<script src='vendors/mask/jquery.maskedinput.min.js'></script><?php /* эта маска круче! http://digitalbush.com/projects/masked-input-plugin/ */?>
 
</body>
</html>