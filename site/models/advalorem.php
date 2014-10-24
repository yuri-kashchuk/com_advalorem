<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку modelitem Joomla.
jimport('joomla.application.component.modelitem');

/**
 * Модель сообщения компонента HelloWorld.
 */
class AdValoremModelAdValorem extends JModelItem
{
	// Получаем сообщение.

	public function getItem()
	{
		if (!isset($this->_item))
		{
			$this->_item = 'Список тэгов';
		}

		return $this->_item;
	}

    // Получаем список тэгов из БД
    public function getTags()
	{
        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        // Select
        $query
            ->select($db->quoteName(array('code', 'name')))
            ->from($db->quoteName('#__ad_tags'))
            ->order('name ASC');

        // Reset the query using our newly populated query object.
        $db->setQuery($query);

        // Load the results
        $results = $db->loadObjectList();

		return $results;
	}

}
?>