<?php
/**
 * Kunena Component
 * @package     Kunena.Site
 * @subpackage  Layout.Category.Item
 *
 * @copyright   (C) 2008 - 2014 Kunena Team. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link        http://www.kunena.org
 **/
defined('_JEXEC') or die;

/**
 * KunenaLayoutCategoryItem
 *
 * @since  3.1
 *
 */
class KunenaLayoutCategoryItem extends KunenaLayout
{
	/**
	 * Method to display categories Index sublayout
	 *
	 * @return void
	 */
	public function displayCategories()
	{
		if ($this->sections)
		{
			$this->subcategories = true;
			echo $this->subLayout('Category/Index')->setProperties($this->getProperties())->setLayout('subcategories');
		}
	}

	/**
	 * Method to display category action sublayout
	 *
	 * @return void
	 */
	public function displayCategoryActions()
	{
		if (!$this->category->isSection())
		{
			echo $this->subLayout('Category/Item/Actions')->setProperties($this->getProperties());
		}
	}

	/**
	 * Method to return array of actions sublayout
	 *
	 * @return array
	 */
	public function getCategoryActions()
	{
		$category = $this->category;
		$token = '&' . JSession::getFormToken() . '=1';
		$actions = array();

		// Is user allowed to post new topic?
		$url = $category->getNewTopicUrl();

		if ($url)
		{
			$actions['create'] = $this->subLayout('Widget/Button')
				->setProperties(array('url' => $url, 'name' => 'create', 'scope' => 'topic', 'type' => 'communication', 'primary' => true));
		}

		// Is user allowed to mark forums as read?
		$url = $category->getMarkReadUrl();

		if ($url)
		{
			$actions['markread'] = $this->subLayout('Widget/Button')
				->setProperties(array('url' => $url, 'name' => 'markread', 'scope' => 'category', 'type' => 'user'));
		}

		// Is user allowed to subscribe category?
		if ($category->isAuthorised('subscribe'))
		{
			$subscribed = $category->getSubscribed($this->me->userid);

			if (!$subscribed)
			{
				$url = "index.php?option=com_kunena&view=category&task=subscribe&catid={$category->id}{$token}";
				$actions['subscribe'] = $this->subLayout('Widget/Button')
					->setProperties(array('url' => $url, 'name' => 'subscribe', 'scope' => 'category', 'type' => 'user'));
			}
			else
			{
				$url = "index.php?option=com_kunena&view=category&task=unsubscribe&catid={$category->id}{$token}";
				$actions['unsubscribe'] = $this->subLayout('Widget/Button')
					->setProperties(array('url' => $url, 'name' => 'unsubscribe', 'scope' => 'category', 'type' => 'user'));
			}
		}

		return $actions;
	}

	/**
	 * Method to get the last post link
	 *
	 * @param   KunenaForumCategory  $category  The KunenaCategory object
	 * @param   string               $content   The content of last topic subject
	 * @param   string               $title     The title of the link
	 * @param   string               $class     The class attribute of the link
	 *
	 * @see KunenaLayout::getLastPostLink()
	 *
	 * @return string
	 */
	public function getLastPostLink($category, $content = null, $title = null, $class = null)
	{
		$lastTopic = $category->getLastTopic();
		$channels = $category->getChannels();

		if (!isset($channels[$lastTopic->category_id]))
		{
			$category = $lastTopic->getCategory();
		}

		$uri = $lastTopic->getUri($category, 'last');

		if (!$content)
		{
			$content = KunenaHtmlParser::parseText($category->getLastTopic()->subject, 20);
		}

		if ($title === null)
		{
			$title = JText::sprintf('COM_KUNENA_TOPIC_LAST_LINK_TITLE', $this->escape($category->getLastTopic()->subject));
		}

		return JHtml::_('kunenaforum.link', $uri, $content, $title, $class, 'nofollow');
	}

	/**
	 * Return the links of pagination item
	 *
	 * @param   int  $maxpages  The maximum number of pages
	 *
	 * @return string
	 */
	public function getPagination($maxpages)
	{
		$pagination = new KunenaPagination($this->total, $this->state->get('list.start'), $this->state->get('list.limit'));
		$pagination->setDisplayedPages($maxpages);

		return $pagination->getPagesLinks();
	}
}
