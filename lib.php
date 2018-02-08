<?php
// ������� ��� �������� ���������� �������� � ������.
//--------------------------------------------------------------------------------------------------
function checkTextLength($text,$minLength,$maxLength){
	$result=false;
	$textLength=mb_strlen($text,'UTF-8');
	if(($textLength>=$minLength) && ($textLength<=$maxLength)){
		$result=true;
	}
	return $result;
}





// ������� ����������� ���������� �������. ������ ������: str_log(__line__,$_SERVER['PHP_SELF'])
//--------------------------------------------------------------------------------------------------
function str_log($line,$name_script){if(FORM_DEBUG){
	file_put_contents(PATCH_LOG_FILE,microtime(true)."\t\t$name_script: $line\r\n",FILE_APPEND);};
}





// ������� ������ ����������������� ���������� ������������� ���������� � ���.
//--------------------------------------------------------------------------------------------------
function var_log($var,$name,$line,$name_script){
	if(FORM_DEBUG){
		if($name=="NEL"){$result="\n";}else{
			$result=microtime(true)."\t\t$name_script $line: var \$$name='".print_r($var,true)."';\n";
		}
		$fp=fopen(PATCH_LOG_FILE,'a');
		fwrite($fp,"$result");
		fclose($fp);
	}
}
