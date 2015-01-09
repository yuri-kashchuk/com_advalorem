<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку modelitem Joomla.
jimport('joomla.application.component.modelitem');

// Модель по работе с данными специалиста

class AdValoremModelClient extends JModelItem
{
	// Получаем сообщение.

	public function getItem()
	{
		if (!isset($this->_item))
		{
			$this->_item = 'Клиенты';
		}

		return $this->_item;
	}
}
?>