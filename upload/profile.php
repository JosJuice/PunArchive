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


$action = isset($_GET['action']) ? $_GET['action'] : null;
$section = isset($_GET['section']) ? $_GET['section'] : null;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id < 2)
	message($lang_common['Bad request']);

if ($pun_user['g_read_board'] == '0' && ($action != 'change_pass' || !isset($_GET['key'])))
	message($lang_common['No view']);

// Load the profile.php/register.php language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/prof_reg.php';

// Load the profile.php language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/profile.php';


if ($action == 'change_pass')
{
	message($lang_common['No permission']);
}

else if ($action == 'change_email')
{
	message($lang_common['No permission']);
}

else if ($action == 'upload_avatar' || $action == 'upload_avatar2')
{
	if ($pun_config['o_avatars'] == '0')
		message($lang_profile['Avatars disabled']);
	else
		message($lang_common['No permission']);
}

else if ($action == 'delete_avatar')
{
	message($lang_common['No permission']);
}

else if (isset($_POST['update_group_membership']))
{
	message($lang_common['No permission']);
}

else if (isset($_POST['update_forums']))
{
	message($lang_common['No permission']);
}

else if (isset($_POST['ban']))
{
	message($lang_common['No permission']);
}

else if (isset($_POST['delete_user']) || isset($_POST['delete_user_comply']))
{
	message($lang_common['No permission']);
}

else if (isset($_POST['form_sent']))
{
	message($lang_common['No permission']);
}

$result = $db->query('SELECT u.username, u.email, u.title, u.realname, u.url, u.jabber, u.icq, u.msn, u.aim, u.yahoo, u.location, u.use_avatar, u.signature, u.disp_topics, u.disp_posts, u.email_setting, u.save_pass, u.notify_with_post, u.show_smilies, u.show_img, u.show_img_sig, u.show_avatars, u.show_sig, u.timezone, u.language, u.style, u.num_posts, u.last_post, u.registered, u.registration_ip, u.admin_note, g.g_id, g.g_user_title FROM '.$db->prefix.'users AS u LEFT JOIN '.$db->prefix.'groups AS g ON g.g_id=u.group_id WHERE u.id='.$id) or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());
if (!$db->num_rows($result))
	message($lang_common['Bad request']);

$user = $db->fetch_assoc($result);

$last_post = format_time($user['last_post']);

if ($user['signature'] != '')
{
	require PUN_ROOT.'include/parser.php';
	$parsed_signature = parse_signature($user['signature']);
}

$user_title_field = get_title($user);

if ($user['url'] != '')
{
	$user['url'] = pun_htmlspecialchars($user['url']);

	if ($pun_config['o_censoring'] == '1')
		$user['url'] = censor_words($user['url']);

	$url = '<a href="'.$user['url'].'">'.$user['url'].'</a>';
}
else
	$url = $lang_profile['Unknown'];

if ($pun_config['o_avatars'] == '1')
{
	if ($user['use_avatar'] == '1')
	{
		if ($img_size = @getimagesize($pun_config['o_avatars_dir'].'/'.$id.'.gif'))
			$avatar_field = '<img src="'.$pun_config['o_avatars_dir'].'/'.$id.'.gif" '.$img_size[3].' alt="" />';
		else if ($img_size = @getimagesize($pun_config['o_avatars_dir'].'/'.$id.'.jpg'))
			$avatar_field = '<img src="'.$pun_config['o_avatars_dir'].'/'.$id.'.jpg" '.$img_size[3].' alt="" />';
		else if ($img_size = @getimagesize($pun_config['o_avatars_dir'].'/'.$id.'.png'))
			$avatar_field = '<img src="'.$pun_config['o_avatars_dir'].'/'.$id.'.png" '.$img_size[3].' alt="" />';
		else
			$avatar_field = $lang_profile['No avatar'];
	}
	else
		$avatar_field = $lang_profile['No avatar'];
}

$posts_field = '';
if ($pun_config['o_show_post_count'] == '1')
	$posts_field = $user['num_posts'];
if ($pun_user['g_search'] == '1')
	$posts_field .= (($posts_field != '') ? ' - ' : '').'<a href="search.php?action=show_user&amp;user_id='.$id.'">'.$lang_profile['Show posts'].'</a>';

$page_title = pun_htmlspecialchars($pun_config['o_board_title']).' / '.$lang_common['Profile'];
define('PUN_ALLOW_INDEX', 1);
require PUN_ROOT.'header.php';

?>
<div id="viewprofile" class="block">
	<h2><span><?php echo $lang_common['Profile'] ?></span></h2>
	<div class="box">
		<div class="fakeform">
			<div class="inform">
				<fieldset>
				<legend><?php echo $lang_profile['Section personal'] ?></legend>
					<div class="infldset">
						<dl>
							<dt><?php echo $lang_common['Username'] ?>: </dt>
							<dd><?php echo pun_htmlspecialchars($user['username']) ?></dd>
							<dt><?php echo $lang_common['Title'] ?>: </dt>
							<dd><?php echo ($pun_config['o_censoring'] == '1') ? censor_words($user_title_field) : $user_title_field; ?></dd>
							<dt><?php echo $lang_profile['Realname'] ?>: </dt>
							<dd><?php echo ($user['realname'] !='') ? pun_htmlspecialchars(($pun_config['o_censoring'] == '1') ? censor_words($user['realname']) : $user['realname']) : $lang_profile['Unknown']; ?></dd>
							<dt><?php echo $lang_profile['Location'] ?>: </dt>
							<dd><?php echo ($user['location'] !='') ? pun_htmlspecialchars(($pun_config['o_censoring'] == '1') ? censor_words($user['location']) : $user['location']) : $lang_profile['Unknown']; ?></dd>
							<dt><?php echo $lang_profile['Website'] ?>: </dt>
							<dd><?php echo $url ?>&nbsp;</dd>
							<dt><?php echo $lang_common['E-mail'] ?>: </dt>
							<dd><?php echo $lang_profile['Private'] ?></dd>
						</dl>
						<div class="clearer"></div>
					</div>
				</fieldset>
			</div>
			<div class="inform">
				<fieldset>
				<legend><?php echo $lang_profile['Section messaging'] ?></legend>
					<div class="infldset">
						<dl>
							<dt><?php echo $lang_profile['Jabber'] ?>: </dt>
							<dd><?php echo ($user['jabber'] !='') ? pun_htmlspecialchars($user['jabber']) : $lang_profile['Unknown']; ?></dd>
							<dt><?php echo $lang_profile['ICQ'] ?>: </dt>
							<dd><?php echo ($user['icq'] !='') ? $user['icq'] : $lang_profile['Unknown']; ?></dd>
							<dt><?php echo $lang_profile['MSN'] ?>: </dt>
							<dd><?php echo ($user['msn'] !='') ? pun_htmlspecialchars(($pun_config['o_censoring'] == '1') ? censor_words($user['msn']) : $user['msn']) : $lang_profile['Unknown']; ?></dd>
							<dt><?php echo $lang_profile['AOL IM'] ?>: </dt>
							<dd><?php echo ($user['aim'] !='') ? pun_htmlspecialchars(($pun_config['o_censoring'] == '1') ? censor_words($user['aim']) : $user['aim']) : $lang_profile['Unknown']; ?></dd>
							<dt><?php echo $lang_profile['Yahoo'] ?>: </dt>
							<dd><?php echo ($user['yahoo'] !='') ? pun_htmlspecialchars(($pun_config['o_censoring'] == '1') ? censor_words($user['yahoo']) : $user['yahoo']) : $lang_profile['Unknown']; ?></dd>
						</dl>
						<div class="clearer"></div>
					</div>
				</fieldset>
			</div>
			<div class="inform">
				<fieldset>
				<legend><?php echo $lang_profile['Section personality'] ?></legend>
					<div class="infldset">
						<dl>
<?php if ($pun_config['o_avatars'] == '1'): ?>							<dt><?php echo $lang_profile['Avatar'] ?>: </dt>
							<dd><?php echo $avatar_field ?></dd>
<?php endif; ?>							<dt><?php echo $lang_profile['Signature'] ?>: </dt>
							<dd><div><?php echo isset($parsed_signature) ? $parsed_signature : $lang_profile['No sig']; ?></div></dd>
						</dl>
						<div class="clearer"></div>
					</div>
				</fieldset>
			</div>
			<div class="inform">
				<fieldset>
				<legend><?php echo $lang_profile['User activity'] ?></legend>
					<div class="infldset">
						<dl>
<?php if ($posts_field != ''): ?>							<dt><?php echo $lang_common['Posts'] ?>: </dt>
							<dd><?php echo $posts_field ?></dd>
<?php endif; ?>							<dt><?php echo $lang_common['Last post'] ?>: </dt>
							<dd><?php echo $last_post ?></dd>
							<dt><?php echo $lang_common['Registered'] ?>: </dt>
							<dd><?php echo format_time($user['registered'], true) ?></dd>
						</dl>
						<div class="clearer"></div>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</div>

<?php

require PUN_ROOT.'footer.php';
