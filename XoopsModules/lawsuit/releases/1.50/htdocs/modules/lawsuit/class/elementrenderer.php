<?php
// $Id$



class LawsuitElementRenderer{
	var $_ele;

	function LawsuitElementRenderer(&$element){
		$this->_ele =& $element;
	}

	function constructElement($form_ele_id, $admin=false){
		global $xoopsUser, $form;
		$myts =& MyTextSanitizer::getInstance();
		$ele_caption = $this->_ele->getVar('ele_caption');
		$ele_value = $this->_ele->getVar('ele_value');
		$e = $this->_ele->getVar('ele_type');
		$delimiter = $form->getVar('form_delimiter');
		switch($e){
			case 'text':
				if( !is_object($xoopsUser) ){
					$ele_value[2] = str_replace('{UNAME}', '', $ele_value[2]);
					$ele_value[2] = str_replace('{EMAIL}', '', $ele_value[2]);
				}elseif( !$admin ){
					$ele_value[2] = str_replace('{UNAME}', $xoopsUser->getVar('uname', 'e'), $ele_value[2]);
					$ele_value[2] = str_replace('{EMAIL}', $xoopsUser->getVar('email', 'e'), $ele_value[2]);
				}
				
				
				$form_ele = new XoopsFormText(
					$ele_caption,
					$form_ele_id,
					$ele_value[0],	//	box width
					$ele_value[1],	//	max width
					$myts->htmlspecialchars($myts->stripSlashesGPC($ele_value[2]))	//	default value
				);
			break;
			case 'validate':
			case 'validation':
			
				$GLOBALS['LawsuitHasValidation'] = true;
				$form_ele = new XoopsFormElementTray($ele_caption, '');
				$form_ele->addElement(new XoopsFormText(
					$ele_caption,
					$form_ele_id,
					$ele_value[0],	//	box width
					$ele_value[1],	//	max width
					'')	//	default value
				);
				$val_button = new XoopsFormButton('', 'validate', _LAWSUIT_VALIDATE);
				$val_button->setExtra('onclick="javascript:ValidateLawsuitForm(\''.$form_ele_id.'\');"');
				$form_ele->addElement($val_button);
				$form_ele->addElement(new XoopsFormLabel('', '<div id="passnotice">&nbsp;</div>'));
				
				if (strpos($ele_value[2], '|')>0) {
					foreach(explode('|', $ele_value[2]) as $id => $field) {
						$scrincld .= '&'.$field.'=" + $(\'#'.$field.'\').val() + "';
					}
				} elseif (strlen($ele_value[2])>0) {
					$scrincld .= '&'.$ele_value[2].'=" + $(\'#'.$ele_value[2].'\').val() + "';
				}
				$GLOBALS['xoTheme']->addScript(XOOPS_URL.'/browse.php?Frameworks/jquery/jquery.js');
				$GLOBALS['xoTheme']->addScript(XOOPS_URL.'/modules/lawsuit/js/jquery.json.validation.js');
				$GLOBALS['xoTheme']->addScript( null, array( 'type' => 'text/javascript' ), 'function ValidateLawsuitForm(element) {
	var params = new Array();
	$.getJSON("'.XOOPS_URL.'/modules/lawsuit/dojsonvalidate.php?passkey=' . md5(XOOPS_LICENSE_KEY.date('Ymdhi')) . '&value=" + $(\'#\' + element).val() + "&ele_id=' . $this->_ele->getVar('ele_id') . $scrincld . '", params, refreshformdesc);
}' );

			break;
			case 'textarea':
				$form_ele = new XoopsFormTextArea(
					$ele_caption,
					$form_ele_id,
					$myts->stripSlashesGPC($ele_value[0]),	//	default value
					$ele_value[1],	//	rows
					$ele_value[2]	//	cols
				);
			break;
			
			case 'html':
				global $check_req;
				if( !$admin ){
					$form_ele = new XoopsFormLabel(
						$ele_caption,
						$myts->displayTarea($myts->stripSlashesGPC($ele_value[0]), 1)
					);
				}else{
					$form_ele = new XoopsFormDhtmlTextArea(
						$ele_caption,
						$form_ele_id,
						$myts->stripSlashesGPC($ele_value[0]),	//	default value
						$ele_value[1],	//	rows
						$ele_value[2]	//	cols
					);
					$check_req->setExtra('disabled="disabled"');
				}
			break;

			case 'select':
				$selected = array();
				$options = array();
				$opt_count = 1;
				while( $i = each($ele_value[2]) ){
					$options[$opt_count] = $myts->stripSlashesGPC($i['key']);
					if( $i['value'] > 0 ){
						$selected[] = $opt_count;
					}
				$opt_count++;
				}
				$form_ele = new XoopsFormSelect(
					$ele_caption,
					$form_ele_id,
					$selected,
					$ele_value[0],	//	size
					$ele_value[1]	//	multiple
				);
				$form_ele->addOptionArray($options);
			break;
			
			case 'checkbox':
				$selected = array();
				$options = array();
				$opt_count = 1;
				while( $i = each($ele_value) ){
					$options[$opt_count] = $i['key'];
					if( $i['value'] > 0 ){
						$selected[] = $opt_count;
					}
					$opt_count++;
				}
				
				$form_ele = new XoopsFormElementTray($ele_caption, $delimiter == 'b' ? '<br />' : ' ');
				$j = 0;
				while( $o = each($options) ){
					$t =& new XoopsFormCheckBox(
						'',
						$form_ele_id.'[]',
						$selected
					);
					$other = $this->optOther($o['value'], $form_ele_id);
					if( $other != false && !$admin ){
						$t->addOption($o['key'], _LAWSUIT_OPT_OTHER.$other);
					}else{
						$t->addOption($o['key'], $myts->stripSlashesGPC($o['value']));
					}
					$form_ele->addElement($t);
					unset($t);
					$j++;
				}
			break;
			
			case 'radio':
			case 'yn':
				$selected = '';
				$options = array();
				$opt_count = 1;
				while( $i = each($ele_value) ){
					switch($e){
						case 'radio':
							$options[$opt_count] = $i['key'];
						break;
						case 'yn':
							$options[$opt_count] = constant($i['key']);
						break;
					}
					if( $i['value'] > 0 ){
						$selected = $opt_count;
					}
					$opt_count++;
				}
				switch($delimiter){
					case 'b':
						$form_ele = new XoopsFormElementTray($ele_caption, '<br />');
						$j = 0;
						while( $o = each($options) ){
							$t =& new XoopsFormRadio(
								'',
								$form_ele_id,
								$selected
							);
							$other = $this->optOther($o['value'], $form_ele_id);
							if( $other != false && !$admin ){
								$t->addOption($o['key'], _LAWSUIT_OPT_OTHER.$other);
							}else{
								$t->addOption($o['key'], $myts->stripSlashesGPC($o['value']));
							}
							$form_ele->addElement($t);
							$j++;
							unset($t);
						}
					break;
					case 's':
					default:
						$form_ele = new XoopsFormRadio(
							$ele_caption,
							$form_ele_id,
							$selected
						);
						while( $o = each($options) ){
							$other = $this->optOther($o['value'], $form_ele_id);
							if( $other != false && !$admin ){
								$form_ele->addOption($o['key'], _LAWSUIT_OPT_OTHER.$other);
							}else{
								$form_ele->addOption($o['key'], $myts->stripSlashesGPC($o['value']));
							}
						}
					break;
				}
			break;
			
			case 'upload':
			case 'uploadimg':
				if( $admin ){
					$form_ele = new XoopsFormElementTray('', '<br />');
					$form_ele->addElement(new XoopsFormText(_AM_ELE_UPLOAD_MAXSIZE, $form_ele_id.'[0]', 10, 20, $ele_value[0]));
					if( $e == 'uploadimg' ){
						$form_ele->addElement(new XoopsFormText(_AM_ELE_UPLOADIMG_MAXWIDTH, $form_ele_id.'[4]', 10, 20, $ele_value[4]));
						$form_ele->addElement(new XoopsFormText(_AM_ELE_UPLOADIMG_MAXHEIGHT, $form_ele_id.'[5]', 10, 20, $ele_value[5]));
					}
				}else{
					global $form_output;
					$form_output->setExtra('enctype="multipart/form-data"');
					$form_ele = new XoopsFormFile($ele_caption, $form_ele_id, $ele_value[0]);
				}
			break;
			
			default:
				return false;
			break;
		}
		return $form_ele;
	}

	function optOther($s='', $id){
		global $xoopsModuleConfig;
		if( !preg_match('/\{OTHER\|+[0-9]+\}/', $s) ){
			return false;
		}
		$s = explode('|', preg_replace('/[\{\}]/', '', $s));
		$len = !empty($s[1]) ? $s[1] : $xoopsModuleConfig['t_width'];
		$box = new XoopsFormText('', 'other['.$id.']', $len, 255, '', 'other_'.$id);
		return $box->render();
	}

}
?>