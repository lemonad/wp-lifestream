<?php
class Lifestream_IdenticaFeed extends Lifestream_Feed
{
	const ID		= 'identica';
	const NAME		= 'Identi.ca';
	const URL		= 'http://www.identi.ca/';
	const LABEL		= 'Lifestream_MessageLabel';
	const CAN_GROUP	= false;

	
	function get_options()
	{		
		return array(
			'username' => array($this->lifestream->__('Username:'), true, '', ''),
		);
	}
	
	function get_user_url($user)
	{
		return 'http://www.identi.ca/'.$user;
	}

	function get_user_link($user)
	{
		return $this->lifestream->get_anchor_html('@'.htmlspecialchars($user), $this->get_user_url($user), array('class'=>'user'));
	}
	
	function parse_users($text)
	{
		return preg_replace_callback('/([^\w]*)@([a-z0-9_\-\/]+)\b/i', array($this, 'get_user_link'), $text);
	}

	function render_item($row, $item)
	{
		return $this->parse_users($this->parse_urls(htmlspecialchars($item['title']))) . ' ['.$this->lifestream->get_anchor_html(htmlspecialchars($this->options['username']), $item['link']).']';
	}

	function get_url()
	{
		return 'http://identi.ca/'.$this->options['username'].'/rss';
	}
	
	function yield($row, $url, $key)
	{
		$string = $this->options['username'] . ': ';
		$title = $this->lifestream->html_entity_decode($row->get_title());
		if (lifestream_str_startswith($title, $string))
		{
			$title = substr($title, strlen($string));
		}
		return array(
			'guid'	  =>  $row->get_id(),
			'date'	  =>  $row->get_date('U'),
			'link'	  =>  $this->lifestream->html_entity_decode($row->get_link()),
			'title'	 =>  $title,
		);
	}
}
$lifestream->register_feed('Lifestream_IdenticaFeed');
?>