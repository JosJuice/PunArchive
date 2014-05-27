<?php
/***********************************************************************

  Copyright (C) 2002-2008  PunBB

  This file is part of PunBB.

  PunBB is free software; you can redistribute it and/or modify it
  under the terms of the GNU General Public License as published
  by the Free Software Foundation; either version 2 of the License,
  or (at your option) any later version.

  PunBB is distributed in the hope that it will be useful, but
  WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston,
  MA  02111-1307  USA

************************************************************************/


// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

// Send the Content-type header in case the web server is setup to send something else
header('Content-type: text/html; charset='.$lang_common['lang_encoding']);

// Load the template
if (defined('PUN_HELP'))
{
	if (file_exists(PUN_ROOT.'include/template/'.$pun_user['style'].'/help.tpl'))
		$tpl_main = file_get_contents(PUN_ROOT.'include/template/'.$pun_user['style'].'/help.tpl');
	else
		$tpl_main = file_get_contents(PUN_ROOT.'include/template/help.tpl');
}
else
{
	if (file_exists(PUN_ROOT.'include/template/'.$pun_user['style'].'/main.tpl'))
		$tpl_main = file_get_contents(PUN_ROOT.'include/template/'.$pun_user['style'].'/main.tpl');
	else
		$tpl_main = file_get_contents(PUN_ROOT.'include/template/main.tpl');
}

define('PUNRES_STYLE_COMPATIBLE', 1);


// START SUBST - <pun_include "*">
while (preg_match('#<pun_include "([^/\\\\]*?)\.(php[45]?|inc|html?|txt)">#', $tpl_main, $cur_include))
{
	if (!file_exists(PUN_ROOT.'include/user/'.$cur_include[1].'.'.$cur_include[2]))
		error('Unable to process user include '.htmlspecialchars($cur_include[0]).' from template main.tpl. There is no such file in folder /include/user/');

	ob_start();
	include PUN_ROOT.'include/user/'.$cur_include[1].'.'.$cur_include[2];
	$tpl_temp = ob_get_contents();
	$tpl_main = str_replace($cur_include[0], $tpl_temp, $tpl_main);
	ob_end_clean();
}
// END SUBST - <pun_include "*">


// START SUBST - <pun_content_direction>
$tpl_main = str_replace('<pun_content_direction>', $lang_common['lang_direction'], $tpl_main);
// END SUBST - <pun_content_direction>


// START SUBST - <pun_char_encoding>
$tpl_main = str_replace('<pun_char_encoding>', $lang_common['lang_encoding'], $tpl_main);
// END SUBST - <pun_char_encoding>


// START SUBST - <pun_head>
ob_start();

// Is this a page that we want search index spiders to index?
if (!defined('PUN_ALLOW_INDEX'))
	echo '<meta name="ROBOTS" content="NOINDEX, FOLLOW" />'."\n";

?>
<title><?php echo $page_title ?></title>
<link rel="stylesheet" type="text/css" href="style/<?php echo $pun_user['style'].'.css' ?>" />
<?php

if (isset($required_fields))
{
	// Output JavaScript to validate form (make sure required fields are filled out)

?>
<script type="text/javascript">
<!--
function process_form(the_form)
{
	var element_names = new Object()
<?php

	// Output a JavaScript array with localised field names
	while (list($elem_orig, $elem_trans) = @each($required_fields))
		echo "\t".'element_names["'.$elem_orig.'"] = "'.addslashes(str_replace('&nbsp;', ' ', $elem_trans)).'"'."\n";

?>

	if (document.all || document.getElementById)
	{
		for (var i = 0; i < the_form.length; ++i)
		{
			var elem = the_form.elements[i]
			if (elem.name && elem.name.substring(0, 4) == "req_")
			{
				if (elem.type && (elem.type=="text" || elem.type=="textarea" || elem.type=="password" || elem.type=="file") && elem.value=='')
				{
					alert("\"" + element_names[elem.name] + "\" <?php echo $lang_common['required field'] ?>")
					elem.focus()
					return false
				}
			}
		}
	}

	return true
}
// -->
</script>
<?php

}

$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
if (strpos($user_agent, 'msie') !== false && strpos($user_agent, 'windows') !== false && strpos($user_agent, 'opera') === false)
	echo '<script type="text/javascript" src="style/imports/minmax.js"></script>';

$tpl_temp = trim(ob_get_contents());
$tpl_main = str_replace('<pun_head>', $tpl_temp, $tpl_main);
ob_end_clean();
// END SUBST - <pun_head>


// START SUBST - <body>
if (isset($focus_element))
{
	$tpl_main = str_replace('<body onload="', '<body onload="document.getElementById(\''.$focus_element[0].'\').'.$focus_element[1].'.focus();', $tpl_main);
	$tpl_main = str_replace('<body>', '<body onload="document.getElementById(\''.$focus_element[0].'\').'.$focus_element[1].'.focus()">', $tpl_main);
}
// END SUBST - <body>


// START SUBST - <pun_page>
$tpl_main = str_replace('<pun_page>', htmlspecialchars(basename($_SERVER['PHP_SELF'], '.php')), $tpl_main);
// END SUBST - <pun_page>


// START SUBST - <pun_title>
$tpl_main = str_replace('<pun_title>', '<h1><span>'.pun_htmlspecialchars($pun_config['o_board_title']).'</span></h1>', $tpl_main);
// END SUBST - <pun_title>


// START SUBST - <pun_desc>
$tpl_main = str_replace('<pun_desc>', '<p><span>'.$pun_config['o_board_desc'].'</span></p>', $tpl_main);
// END SUBST - <pun_desc>


// START SUBST - <pun_navlinks>
$tpl_main = str_replace('<pun_navlinks>','<div id="brdmenu" class="inbox">'."\n\t\t\t". generate_navlinks()."\n\t\t".'</div>', $tpl_main);
// END SUBST - <pun_navlinks>


// START SUBST - <pun_status>
if ($pun_config['o_announcement'] == '1')
	$tpl_main = str_replace('<pun_status>', '<div id="brdwelcome" class="inbox">'."\n\t\t\t".'<p>'.$pun_config['o_announcement_message'].'</p>'."\n\t\t".'</div>', $tpl_main);
else
	$tpl_main = str_replace('<pun_status>', '', $tpl_main);
// END SUBST - <pun_status>


// START SUBST - <pun_announcement>
$tpl_main = str_replace('<pun_announcement>', '', $tpl_main);
// END SUBST - <pun_announcement>


// START SUBST - <pun_main>
ob_start();


define('PUN_HEADER', 1);
