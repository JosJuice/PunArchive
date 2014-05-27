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


define('PUN_ROOT', './');
require PUN_ROOT.'include/common.php';


// Load the misc.php language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/misc.php';

$action = isset($_GET['action']) ? $_GET['action'] : null;


if ($action == 'rules')
{
	// Load the register.php language file
	require PUN_ROOT.'lang/'.$pun_user['language'].'/register.php';

	$page_title = pun_htmlspecialchars($pun_config['o_board_title']).' / '.$lang_register['Forum rules'];
	require PUN_ROOT.'header.php';

?>
<div class="block">
	<h2><span><?php echo $lang_register['Forum rules'] ?></span></h2>
	<div class="box">
		<div class="inbox">
			<p><?php echo $pun_config['o_rules_message'] ?></p>
		</div>
	</div>
</div>
<?php

	require PUN_ROOT.'footer.php';
}

else if ($action == 'markread')
{
	message($lang_common['No permission']);
}

else if ($action == 'markforumread')
{
	message($lang_common['No permission']);
}

else if (isset($_GET['email']))
{
	message($lang_common['No permission']);
}

else if (isset($_GET['report']))
{
	message($lang_common['No permission']);
}

else if (isset($_GET['subscribe']))
{
	message($lang_common['No permission']);
}

else if (isset($_GET['unsubscribe']))
{
	message($lang_common['No permission']);
}

else
	message($lang_common['Bad request']);
