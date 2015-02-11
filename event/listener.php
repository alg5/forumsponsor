<?php
/**
 *
 * @package ForumSponsor
 * @copyright (c) 2014 alg 
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace alg\ForumSponsor\event;

/**
* @ignore
*/

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{

	public function __construct(\phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request_interface $request)
	{
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.display_forums_modify_template_vars'		=> 'display_forums_modify_template_vars',
			//'core.display_forums_modify_category_template_vars'		=> 'display_forums_modify_category_template_vars',
			'core.acp_manage_forums_request_data'			=> 'acp_manage_forums_request_data',
			'core.acp_manage_forums_display_form'			=> 'acp_manage_forums_display_form',
		);
	}
	public function display_forums_modify_template_vars($event)
	{
		$this->user->add_lang_ext('alg/ForumSponsor', 'info_acp_forumsponsor');
		$forum_row = $event['forum_row'];
		$row = $event['row'];
		$forum_row = array_merge($forum_row, array(
				'FORUM_SPONSOR'			=> ($row['forum_sponsor']) ? $row['forum_sponsor'] : '',
				));
		$event['forum_row'] = $forum_row;
	}
	public function display_forums_modify_category_template_vars($event)
	{
		$this->user->add_lang_ext('alg/ForumSponsor', 'info_acp_forumsponsor');
		$cat_row = $event['cat_row'];
		$cat_row = array_merge($cat_row, array(
				'FORUM_SPONSOR'			=> ($row['forum_sponsor']) ? $row['forum_sponsor'] : '',
		));
		$event['cat_row'] = $cat_row;
	}

	#region ACP functions
	public function acp_manage_forums_request_data($event)
	{
		$forum_data = $event['forum_data'];
		$fs = $forum_data['forum_type'] == FORUM_CAT ? $this->request->variable('cat_sponsor', '', true) : $this->request->variable('forum_sponsor', '', true);
		$forum_data += array(
			'forum_sponsor'	=>  htmlspecialchars_decode(utf8_normalize_nfc($fs)),
		);
		$event['forum_data'] = $forum_data;
	}
	public function acp_manage_forums_display_form($event)
	{
		$forum_data = $event['forum_data'];
		$template_data = $event['template_data'];

		$template_data += array(
			'FORUM_SPONSOR'	=> isset($forum_data['forum_sponsor']) ? $forum_data['forum_sponsor'] : '',
		);

		$event['template_data'] = $template_data;
	}
	#endregion
}
