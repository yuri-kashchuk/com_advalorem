<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');

// HTML представление сообщения компонента
class AdValoremViewAdValorem extends JViewLegacy
{
	//
	protected $item;
    //
    protected $tags;
    // Контент для вывода в шаблон
    protected $content;

	// Переопределяем метод display класса JViewLegacy.
	public function display($tpl = null)
	{
		// Получаем сообщение из модели.
		$this->item = $this->get('Item');

        // Получаем список тэгов
        $this->tags = $this->get('Tags');

        //Формируем контент для вывода
        foreach ($this->tags as $key => $value) {
          $this->content .= '<span class="label label-warning">'.$key.'</span><span class="label label-primary">'.$value->name.'</span><br>';
          }

        // Отображаем представление.
		parent::display($tpl);
	}
}
?>