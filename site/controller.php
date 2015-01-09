<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку контроллера Joomla.
jimport('joomla.application.component.controller');

// Подключаем обработчик файлов JFile
jimport('joomla.filesystem.file');

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
          $this->setRedirect( JRoute::_(''), JText::_( 'AD_INCORRECT_UID' ) ); return;
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
          $this->setRedirect( JRoute::_(''), JText::_( 'AD_INCORRECT_UID' ) ); return;
        }

        // Контейнер для параметров апдейта
            $object = new stdClass();

        // Проверяем корректность новых данных и заполняем массив для UPDATE

            $object->id = $this->input->getInt('uid'); // uid уже был проверен ранее

            if ( JRequest::getString('sirname') )
            {
                $object->sirname = JRequest::getString('sirname');
            }

            if ( JRequest::getString('name') ) { $object->name = JRequest::getString('name'); }
            if ( JRequest::getString('patronymic') ) { $object->patronymic = JRequest::getString('patronymic'); }
            if ( JRequest::getString('gender') ) { $object->gender = JRequest::getString('gender'); }
            if ( JRequest::getString('birth_date') ) { $object->birth_date = JRequest::getString('birth_date'); }

            if ( JRequest::getString('city') ) { $object->city = JRequest::getString('city');  }
            if ( JRequest::getInt('price') ) { $object->price = JRequest::getInt('price');  }
            if ( JRequest::getString('description') ) { $object->description = JRequest::getString('description'); }

            if ( JRequest::getString('phone') ) { $object->phone = JRequest::getString('phone'); }
            if ( JRequest::getString('email') ) { $object->email = JRequest::getString('email'); }


            // Получаем фотографию из запроса. Запрос нужно отправлять обязательно в POST (через GET файл не загружается)
            $file = $this->input->files->get('photo', null, 'array'); // альтернативный синтаксис: JRequest::getVar( 'photo', null, 'files', 'array' );

            // ТЕСТ Параметры загруженного файла
            # foreach ($file as $key => $value) { echo $key.':'.$value.'<br>'; }

            // Если файл был передан
            if ( $file['name'] != null )
            {
                // Обезопашиваем название файла
                $file['name'] = JFile::makeSafe($file['name']);

                if ( $imgSize = getimagesize( $file['tmp_name'] ) )
                {
                    $filepath = JPATH_COMPONENT.'/images/'.strtolower( 'photo_'.$this->input->getInt('uid').'.'.JFile::getExt( $file['name'] ) );
                    // if ( strtolower(JFile::getExt($filename) ) == 'jpg') - проверка на расширение файла. Вдруг пригодится

                    // Записываем файл в каталог компонента
                    JFile::upload( $file['tmp_name'], $filepath );
                }
                else
                {
                    $this->setRedirect( $view->get('EditLink'), JText::_( 'AD_INCORRECT_IMAGE' ) ); return;
                }

            }

        // Обновляем данные оператора, если все проверки прошли
        if ( $model->operatorUpdate( $object ) )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( 'AD_MSG_DATA_UPDATED' ) );
        }

        # Отображаем view с шаблоном карточки оператора
        $view->display('operator');
    }


}
?>