<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку modelitem Joomla.
jimport('joomla.application.component.modelitem');

// Модель по умолчанию. Используется для выборки данных при формировании контента
// Методы работы с сущностями располагаются в соответствующих моделях.

class AdValoremModelAdValorem extends JModelItem
{
	// Получаем сообщение.

	public function getItem()
	{
		if (!isset($this->_item))
		{
			$this->_item = 'Список специалистов';
		}

		return $this->_item;
	}

    // Получаем список тэгов из БД

    public function getTags()
	{
        $db = JFactory::getDbo();
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

    // Получаем данные о специалистах для вывода

    public function getSpecCatalog()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        /*  Выбираем данные для вывода мини-карточек в каталоге.
            Отбираем только специалистов, имя и отчество объединяем, дату выводим в нормальном формате
        */
        $query
            ->select($db->quoteName(array('sirname', 'name', 'patronymic', 'gender', 'phone', 'city')))
            ->from($db->quoteName('#__ad_client'))
            ->where($db->quoteName('profile').' LIKE '.'\'O\'')
            ->order('sirname ASC'.' '.'LIMIT 0, 3');

        $db->setQuery($query);

        $results = $db->loadObjectList();

		return $results;
    }

}
?>