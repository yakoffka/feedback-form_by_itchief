# feedback-form_by_itchief

<p>Небольшой апгрейд <a href='https://yadi.sk/d/YcF2Qwwf3KxxQy'>Feedback form (последняя версия)</a> <a href='https://itchief.ru/lessons/php/feedback-form-for-website'>формы обратной связи для сайта с отправкой на почту от Александра Мальцева</a></p>
<p>Оригинал здесь: <a href='https://github.com/itchief/feedback-form'>itchief/feedback-form</a></p>

собственно, плюшки:
	<ol>
		<li>отвязка от необходимости копирования папки feedback непосредственно в корневую директорию сайта;
		</li><li>сохранение имени загружаемых файлов неизменным (вместо имени рандомно генерится имя родительской директории) - упрощает общение с пользователем, отправившим файл (получатель видит файлы под тем-же именем, под которым их отправил отправитель).
		</li><li>замена скрипта для создания масок на http://digitalbush.com/projects/masked-input-plugin/
		</li><li>небольшое допиливание html
		</li><li>допиливание полей под себя
		</li><li>вынесение копирайта в настройки (feedback/config.php)
		</li><li>добавление директории 'uploads'
		</li>
	</ol>
	

использование:
	<ol>
		<!--li>настройка параметров в конфигурационных файлах feedback/process/process_settings.php;-->
		<li>заливаем содержимое директории 'feedback-form_by_itchief' на сайт
		</li><li>переименовываем 'config.php.template' в 'config.php'
		</li><li>производим настройку параметров в конфигурационных файлах 'feedback/config.php' и 'js/main.js';
		</li><li>настраиваем логирование ошибок (вручную, к сожалению) в файле .htaccess
		</li>
	</ol>
сделать:
	<ol>
		<li>возможность включения/выключения хранения загружаемых файлов и (или) ведения лога сообщений 'feedback/info/message.txt';
		</li><li>добавить див-обертку с фиксированной шириной для формы;
		</li>сделать создание загрузочной директории для одного письма<li>
		</li><li>убрать отображение ошибок в '.htaccess'
		</li><li>разобраться с правами доступа на '/logs'
		</li><li>поправить разметку bootstrap (для dropzone+capcha etc)
		</li><li>изменить получение $name_rand_dir (точка в имени не нравится..)
		</li><li>в капче понаклонять символы по разному.
		</li>
	</ol>
сделать:
	<ol>
		<li>добавлена возможность настройки режима капчи: CAPCHA_MODE='soft'; - только строчные латинские буквы и цифры; CAPCHA_MODE='hard'; - ПРОПИСНЫЕ и строчные латинские буквы и цифры. есть шанс спутать 'l' с 'I', '0' с 'O'
		</li><li>доделать текстовую версию письма!!! Результат:	9.4/10!!!
		</li><li>http://www.sesmikcms.ru/pages/read/ischerpyvajuschaja-instrukcija-po-php-mailer/
		</li>
	</ol>
  
<!--https://www.youtube.com/watch?v=gd74R-rvfsY-->

<div stile='border-top:1px #555 solid'>* - проектируемые</div>