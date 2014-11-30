<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

define('REDIRECT', 'index.php?option=com_advalorem');

// Подключаем библиотеку контроллера Joomla.
jimport('joomla.application.component.controller');

// Контроллер компонента
class AdValoremController extends JControllerLegacy
{
    // Переопределяем метод display
    function display()
    {
        // Получаем представление
        $view = $this->getView();

        // Устанавливаем модель по умолчанию для представления
        $view->setModel($this->getModel(), true);

        # Отображаем view с шаблоном по умолчанию
        $view->display();

    }

    // Обработка события поиска специалиста
    function search()
    {
        // Получаем представление
        $view = $this->getView();

        // Проверяем входящие данные


        // Устанавливаем модель по умолчанию для представления
        $view->setModel($this->getModel(), true);

        # Отображаем view с шаблоном результата поиска
        $view->display('result');
    }

    // Обработка события просмотра карточки специалиста/пациента
    function view()
    {
        // Получаем представление
        $view = $this->getView();
        $model = $this->getModel();

        // Устанавливаем модель по умолчанию для представления
        $view->setModel($model, true);

        // Проверяем наличие оператора по UID
        if ( !$model->getOperatorMiniCard( $this->input->getInt('uid') ) )
        {
          $this->setRedirect( REDIRECT, JText::_( 'AD_INCORRECT_UID' ) ); return false;
        }

        # Отображаем view с шаблоном карточки оператора
        $view->display('operator');
    }

    // Обработка события редактирования специалиста
    function edit()
    {
        // Получаем представление
        $view = $this->getView();
        $model = $this->getModel();

        // Устанавливаем модель по умолчанию для представления
        $view->setModel($model, true);

        # Отображаем view с шаблоном результата поиска
        $view->display('edit');
    }

    // Сохранение данных оператора
    function save()
    {
        // Получаем представление
        $view = $this->getView();
        $model = $this->getModel();

        // Устанавливаем модель по умолчанию для представления
        $view->setModel($model, true);

        // Проверяем наличие оператора по UID
        if ( !$model->getOperatorMiniCard( $this->input->getInt('uid') ) )
        {
          $this->setRedirect( REDIRECT, JText::_( 'AD_INCORRECT_UID' ) ); return false;
        }

        // Проверяем корректность новых данных


        # Отображаем view с шаблоном карточки оператора
        $view->display('operator');
    }


}
?>