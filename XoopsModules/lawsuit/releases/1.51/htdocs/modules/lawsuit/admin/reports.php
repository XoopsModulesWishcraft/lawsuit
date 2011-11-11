<?php
// $Id: index.php,v 1.05 2009/06/24 23:45:00 wishcraft Exp $


include 'admin_header.php';
$myts =& MyTextSanitizer::getInstance();
$op = isset($_REQUEST['op']) ? trim($_REQUEST['op']) : 'list';
$fct = isset($_REQUEST['fct']) ? trim($_REQUEST['fct']) : '';
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$num = isset($_REQUEST['num']) ? intval($_REQUEST['num']) : 30;
error_reporting(E_ALL);
switch($op){
	default:
	case 'list':
		adminHtmlHeader();
		lawsuit_adminMenu('reports.php', 7);
		
		$response_handler =& xoops_getmodulehandler('response', 'lawsuit');
		$pages_handler =& xoops_getmodulehandler('pages', 'lawsuit');
		$forms_handler =& xoops_getmodulehandler('forms', 'lawsuit');
		$category_handler =& xoops_getmodulehandler('category', 'lawsuit');
		$ttl = $response_handler->getCount(NULL);
		
		
		$pagenav = new XoopsPageNav($ttl, $num, $start, 'start', 'num='.$num.'&op='.$op);
		echo '<div style="height:45px;"><div style="float:right;">'.$pagenav->renderNav().'</div></div>';

		$criteria = new Criteria(1,1);
		$criteria->setSort('`time_response`');
		$criteria->setOrder('DESC');
		$criteria->setStart($start);
		$criteria->setLimit($num);
		$responses = $response_handler->getObjects($criteria, true);
		$pages = $pages_handler->getObjects(NULL, true);
		$forms = $forms_handler->getObjects(NULL, true);
		$categories = $category_handler->getObjects(NULL, true);
		
		echo '<table class="outer" cellspacing="1" width="100%">
				<tr class="head">
					<th>'._AM_FORM_RESPONSEID.'</th>
					<th>'._AM_FORM_CATEGORY.'</th>
					<th>'._AM_FORM_PAGE.'</th>
					<th>'._AM_FORM_FORM.'</th>
					<th>'._AM_FORM_FINGERPRINT.'</th>
					<th>'._AM_FORM_WHEN.'</th>
					<th>'._AM_FORM_TOP1.'</th>
					<th>'._AM_FORM_TOP2.'</th>
					<th>'._AM_FORM_ACTIONS.'</th>
				</tr>';
		foreach($responses as $responseid => $response){		
			$class=($class!='even')?'even':'odd';
			echo '<tr class="'.$class.'">
					<td align="center">'.$responseid.'</td>
					<td align="center">'.(isset($categories[$response->getVar('cid')])?$categories[$response->getVar('cid')]->getVar('title'):_AM_FORM_UNKNOWN).'</td>
					<td align="center">'.(isset($pages[$response->getVar('pid')])?$pages[$response->getVar('pid')]->getVar('title'):_AM_FORM_UNKNOWN).'</td>
					<td align="center">'.(isset($forms[$response->getVar('form_id')])?$forms[$response->getVar('form_id')]->getVar('title'):_AM_FORM_UNKNOWN).'</td>
					<td align="center">'.$response->getVar('fingerprint').'</td>
					<td align="center">'.date(_DATESTRING, $response->getVar('time_response')).'</td>';
			$i=0;
			foreach($response->getVar('response') as $key => $res) {
				$i++;
				if ($i<=2) {
					if (is_array($res)) {
						echo '<td align="center"><strong>'.$key.':&nbsp;</strong>';
						foreach($res as $keyb => $value) {
							echo '<em>'.$value.($keyb<sizeof($res)-1?', ':'').'</em>';	
						}
						echo '</td>';
					} else {
						echo '<td align="center"><strong>'.$key.':&nbsp;</strong>'.$res.'</td>';
					}
				}
			}
			
			echo '<td align="center"><a href="'.$_SERVER['PHP_SELF'].'?op=view&id='.$responseid.'">'._AM_FORM_VIEW_RESPONSE.'</a></td></tr>';
		}
		echo '</table>';
		break;
	case 'view':
	
		$response_handler =& xoops_getmodulehandler('response', 'lawsuit');
		if( !empty($id) ){
			$response = $response_handler->get($id);
			if (!is_object($response)) {
				redirect_header($_SERVER['PHP_SELF'].'?op=list', 10, _NOPERM);
				exit;
			}
		}else{
			redirect_header($_SERVER['PHP_SELF'].'?op=list', 10, _NOPERM);
			exit;
		}

		adminHtmlHeader();
		lawsuit_adminMenu('reports.php', 7);
		
		$pages = $pages_handler->getObjects(NULL, true);
		$forms = $forms_handler->getObjects(NULL, true);
		$categories = $category_handler->getObjects(NULL, true);
		
		echo '<table class="outer" cellspacing="1" width="100%">
				<tr class="head">
					<th>'._AM_FORM_FIELD.'</th>
					<th>'._AM_FORM_RESPONSE.'</th>
				</tr>';
		
		$class=($class!='even')?'even':'odd';
		echo '<tr class="'.$class.'">
					<td align="right">'._AM_FORM_CATEGORY.':&nsbp;</td>
					<td align="left">'.(isset($categories[$response->getVar('cid')])?$categories[$response->getVar('cid')]->getVar('title'):_AM_FORM_UNKNOWN).'</td>
			 </tr>';
		$class=($class!='even')?'even':'odd';
		echo '<tr class="'.$class.'">
					<td align="right">'._AM_FORM_PAGE.':&nsbp;</td>
					<td align="left">'.(isset($pages[$response->getVar('pid')])?$pages[$response->getVar('pid')]->getVar('title'):_AM_FORM_UNKNOWN).'</td>
			 </tr>';
		$class=($class!='even')?'even':'odd';
		echo '<tr class="'.$class.'">
					<td align="right">'._AM_FORM_FORM.':&nsbp;</td>
					<td align="left">'.(isset($forms[$response->getVar('form_id')])?$forms[$response->getVar('form_id')]->getVar('title'):_AM_FORM_UNKNOWN).'</td>
			 </tr>';
		$class=($class!='even')?'even':'odd';
		echo '<tr class="'.$class.'">
					<td align="right">'._AM_FORM_FINGERPRINT.':&nsbp;</td>
					<td align="left">'.$response->getVar('fingerprint').'</td>
			 </tr>';
		$class=($class!='even')?'even':'odd';
		echo '<tr class="'.$class.'">
					<td align="right">'._AM_FORM_WHEN.':&nsbp;</td>
					<td align="left">'.date(_DATESTRING, $response->getVar('time_response')).'</td>
			 </tr>';
		echo '<tr class="foot">
					<td align="right" colspan="2">&nbsp;</td>
			 </tr>';
		foreach($response->getVar('response') as $key => $res) {
				if (is_array($res)) {
					$i=0;
					foreach($res as $keyb => $value) {
						$i++;
						$class=($class!='even')?'even':'odd';
						echo '<tr class="'.$class.'">
									<td align="right">'.($i<2?$key.':&nsbp;':'&nbsp;').'</td>
									<td align="left"><em>'.$value.'</em></td>
							 </tr>';
					}
				} else {
					$class=($class!='even')?'even':'odd';
					echo '<tr class="'.$class.'">
								<td align="right">'.$key.':&nsbp;</td>
								<td align="left"><em>'.$res.'</em></td>
						 </tr>';
				}
			}
		echo '</table>';
		break;
}

include 'footer.php';
xoops_cp_footer();
?>