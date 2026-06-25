<?php
/**
 *
 * Clean Footer extension for the phpBB Forum Software package.
 *
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

declare(strict_types=1);

namespace ighost\cleanfooter\event;

use phpbb\event\data;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var user */
	protected $user;

	public function __construct(user $user)
	{
		$this->user = $user;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents()
	{
		return array(
			'core.page_header'                            => 'disable_online_list_for_guests_and_bots',
			'core.twig_environment_render_template_after' => 'remove_index_quick_login_form',
		);
	}

	/**
	 * Prevent phpBB from querying and generating online list data for
	 * anonymous visitors and bot accounts.
	 *
	 * @param data $event
	 * @return void
	 */
	public function disable_online_list_for_guests_and_bots(data $event)
	{
		if (empty($this->user->data['is_registered']) || !empty($this->user->data['is_bot']))
		{
			$event['display_online_list'] = false;
		}
	}

	/**
	 * Remove prosilver's anonymous quick-login form from the board index.
	 *
	 * phpBB 3.3 has no template event around this form, so use the official
	 * post-render event and limit the change to index_body.html.
	 *
	 * @param data $event
	 * @return void
	 */
	public function remove_index_quick_login_form(data $event)
	{
		if ($event['name'] !== 'index_body.html')
		{
			return;
		}

		$event['output'] = preg_replace(
			'#<form\b(?=[^>]*\bclass=(["\'])[^"\']*\bheaderspace\b[^"\']*\1)[^>]*>.*?<fieldset\b[^>]*\bclass=(["\'])[^"\']*\bquick-login\b[^"\']*\2[^>]*>.*?</fieldset>\s*</form>#is',
			'',
			$event['output'],
			1
		);
	}
}
