<?php
// $Id: directory.php 5204 2010-09-06 20:10:52Z mageg $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: XOOPS Foundation                                                  //
// URL: http://www.xoops.org/                                                //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

	include ('admin_header.php');
	xoops_loadLanguage('admin', 'lawsuit');
	
	xoops_cp_header();
	lawsuit_adminMenu('dashboard.php', 2);
	
	$indexAdmin = new ModuleAdmin();
	$category_handler = xoops_getmodulehandler('category', 'lawsuit');
    $elements_handler = xoops_getmodulehandler('elements', 'lawsuit');
    $forms_handler = xoops_getmodulehandler('forms', 'lawsuit');
    $pages_handler = xoops_getmodulehandler('pages', 'lawsuit');
    $responses_handler = xoops_getmodulehandler('response', 'lawsuit');
    
    foreach($forms_handler->getObjects(NULL, true) as $form_id => $form) {
 		$indexAdmin = new ModuleAdmin();	
    	$indexAdmin->addInfoBox(sprintf(_AM_LAWSUIT_FORM_COUNTS, $form->getVar('form_title')));
    	$indexAdmin->addInfoBoxLine(sprintf(_AM_LAWSUIT_FORM_COUNTS, $form->getVar('form_title')), "<label>"._AM_LAWSUIT_FORMS_THEREARE_ELEMENTS."</label>", $elements_handler->getCount(new Criteria('`form_id`', $form_id, '=')), 'Green');
    	$indexAdmin->addInfoBoxLine(sprintf(_AM_LAWSUIT_FORM_COUNTS, $form->getVar('form_title')), "<label>"._AM_LAWSUIT_FORMS_THEREARE_PAGES."</label>", $pages_handler->getCount(new Criteria('`form_id`', $form_id, '=')), 'Purple');
    	$indexAdmin->addInfoBoxLine(sprintf(_AM_LAWSUIT_FORM_COUNTS, $form->getVar('form_title')), "<label>"._AM_LAWSUIT_FORMS_THEREARE_RESPONSE."</label>", $responses_handler->getCount(new Criteria('`form_id`', $form_id, '=')), 'Orange');
	    $criteria = new CriteriaCompo(new Criteria('`form_id`', $form_id, '='));
	    $criteria->add(new Criteria('`time_response`', '0', '>'));
	    $criteria->setSort('`time_response`');
	    $criteria->setOrder('DESC');
	    $criteria->setLimit(1);
	    $responses = $responses_handler->getObjects($criteria, false);
	    if (is_object($responses[0]))
			$indexAdmin->addInfoBoxLine(sprintf(_AM_LAWSUIT_FORM_COUNTS, $form->getVar('form_title')), "<label>"._AM_LAWSUIT_FORMS_THEREARE_RESPONSELAST."</label>", date(_DATESTRING, $responses[0]->getVar('time_response')), 'Blue');
    }    

    echo $indexAdmin->renderIndex();
	
    include ('footer.php');
	xoops_cp_footer();	

?>