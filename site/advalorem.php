<?php

// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем логирование.
JLog::addLogger(
	array('text_file' => 'com_advalorem.php'),
	JLog::ALL,
	array('com_advalorem')
);

// Устанавливаем обработку ошибок в режим использования Exception.
JError::$legacy = false;

// Подключаем библиотеку контроллера Joomla.
jimport('joomla.application.component.controller');

// Получаем экземпляр контроллера с префиксом HelloWorld.
$controller = JControllerLegacy::getInstance('AdValorem');

// Исполняем задачу task из Запроса.
$input = JFactory::getApplication()->input;

$controller->execute($input->getCmd('task', 'display'));

// Перенаправляем, если перенаправление установлено в контроллере.
$controller->redirect();

?>