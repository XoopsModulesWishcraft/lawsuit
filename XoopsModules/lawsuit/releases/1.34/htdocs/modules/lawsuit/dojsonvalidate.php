<?php
function lawsuit_checkpasskey($key) {

	$minseed = strtotime(date('Y-m-d h:i'));
	$diff = intval((120/2)*60);
	for($step=($minseed-$diff);$step<($minseed+$diff);$step++)
		if ($key==md5(XOOPS_LICENSE_KEY.date('Ymdhi', $step)))
			return true;
	return false;

}

include('../../mainfile.php');
error_reporting(E_ALL);

foreach($_GET as $id => $val)
	${$id} = $val;

include $GLOBALS['xoops']->path('/modules/lawsuit/include/JSON.php');

set_time_limit(120);
	
if (!lawsuit_checkpasskey($passkey)) { 
	$values['innerhtml']['passnotice'] = _LAWSUIT_VALIDATE_PASSKEYFAILED;
	$values['disable']['submit'] = 'true';	
	print $json->encode($values);
	exit(0);
}

$json = new services_JSON();

$validation_handler =& xoops_getmodulehandler('validation', 'lawsuit');
$criteria = new Criteria('`weight`',1, '>=');
$criteria->setSort('`weight`');
$validations = $validation_handler->getObjects($criteria, '*', true);
$pass=false;
$fail=false;
foreach($validations as $rule_id => $validation){
	if ($pass==false)
	switch($validation->getVar('type')) {
	case 'regex':
		if (!preg_match($validation->getVar('action'), $value)) {
			$fail=true;
		} else {
			$pass=true;
		}
	break;
	case 'match':
		if ($validation->getVar('action')!=$value) {
			$fail=true;
		} else {
			$pass=true;
		}
	break;
	case 'sql':
		$sql = htmlspecialchars_decode($validation->getVar('action'));
		$i=strpos($sql, '[');
		while($i != 0) {
			$elements[] = substr($sql, $i+1, strpos($sql, ']', $i+1)-$i-1);	
			$i = strpos($sql, '[', $i+1);
		}
		foreach($elements as $id => $element) {
			if (strpos($element , '|')) {
				$fields = explode('|', $element);
				foreach($fields as $fid => $field) {
					if (isset($_GET[$field])) {
						$sql = str_replace('['.$element.']', $_GET[$field], $sql);
					}
				}
			} elseif (strlen($element)>0) {
				if (isset($_GET[$element])) {
					$sql = str_replace('['.$element.']', $_GET[$element], $sql);
				}		
			}
		}
			
		if ($result=$GLOBALS['xoopsDB']->queryF($sql)) {
				
			list($count) = $GLOBALS['xoopsDB']->fetchRow($result);
			
			if ($count!=0) {
				$pass=true;
			} else {
				$fail=true;			
			}
		}
	break;		
	}
}

if ($pass!=false){
	$values['innerhtml']['passnotice'] = _LAWSUIT_VALIDATE_PASS;
	$values['disable']['submit'] = 'false';	
} else {	
	$values['innerhtml']['passnotice'] = _LAWSUIT_VALIDATE_FAIL;
	$values['disable']['submit'] = 'true';	
}


print $json->encode($values);
?>