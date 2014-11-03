<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');

// HTML представление сообщения компонента
class AdValoremViewAdValorem extends JViewLegacy
{
	// Dummy
	protected $item;
    //
    protected $tags, $specMini;
    // Контент для вывода в шаблон
    protected $data, $contentCatalog, $contentTags;

	// Переопределяем метод display класса JViewLegacy.
	public function display($tpl = null)
	{
		// Получаем сообщение из модели.
		$this->item = $this->get('Item', 'advalorem');

        // Получаем список тэгов
        $this->tags = $this->get('Tags');

        // Формируем контент для вывода списка тэгов
        foreach ($this->tags as $key => $value) {
          $this->contentTags .= '<span class="label label-primary">'.$value->name.'</span><br>';
          }

        // Формируем html для мини-карточки специалистов
        $this->contentCatalog = $this->viewSpecCatalog();

        // Отображаем представление.
		parent::display($tpl);
	}

    // Подготовка HTML кода для мини карточки специалиста из каталога
    public function viewSpecCatalog()
    {
        $content = null;

        // Получаем данные специалистов для мини-карточки
        $this->data = $this->get('SpecCatalog');

        // Формируем html каталога специалистов
        foreach ($this->data as $value) {

          // Формируем HTML код одной карточки:
          $content .= '<div class="media">';
          $content .= ' <a class="pull-left" href="'.JRoute::_('').'"><img src="'.JRoute::_('images/ico-blank-64x64.png').'" alt="..." class="img-thumbnail"></a>';
          $content .= ' <div class="media-body">';
          $content .= '     <h4 class="media-heading"><a href="">'.$value->sirname.' '.'<small>'.$value->name.' '.$value->patronymic.'</small></a>';
          $content .= '     <span class="pull-right glyphicon glyphicon-ok"></span></h4>';
          $content .= ' </div>';
          $content .= '</div>';
          $content .= '<hr></hr>';
          //JRoute::_('index.php?option=com_helloworld') - формирование ссылки
        }

        $content .= '<a href="'.JRoute::_('').'">следующие <span class="badge">'.count($this->data).'</span></a>';

        return $content;
    }
}
?>