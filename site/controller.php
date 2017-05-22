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
        // Получаем пользователя
        $user = JFactory::getUser();

        // Получаем представление
        $model = $this->getModel();
        $view = $this->getView();

        $session = &JFactory::getSession();

        // Если пользователь залогинился - сохраняем uid в сессию (чтобы переход из меню работал)
        /*
        if ( !$user->guest and !$session->get('uid') )
        {
            // Пытаемся получить инфу специалиста по пользователю
            $spec = $model->getOperatorMiniCardByUserID( $user->id );

            // Сажаем специалиста в сессию
            if ($spec) { $session->set('uid', $spec->id); }
        }
        */

        // Устанавливаем категорию поиска по умолчанию
        $model->category = 'search';

        // Определяем город из сессии или из запроса

        if ( JRequest::getVar('city') ) {
            $model->city = JRequest::getVar('city'); }
        elseif ( $session->get('city') ) {
          $model->city = $session->get('city');  }
        else {
          $model->city = JText::_( 'AD_MOSCOW' ); }

        $session->set('city', $model->city);

        // Устанавливаем модель по умолчанию для представления
        $view->setModel($model, true);

        # Отображаем view с шаблоном по умолчанию
        $view->display();

    }

    // Отображение списка стран и городов
    function cities()
    {
        // Получаем представление
        $model = $this->getModel();
        $view = $this->getView();

        // Устанавливаем модель по умолчанию для представления
        $view->setModel($model, true);

        $session = &JFactory::getSession();
        $model->city = $session->get('city');
        
        # Отображаем view с шаблоном
        $view->display('cities');

    }


    // Обработка события поиска специалиста
    function search()
    {
        // Получаем представление и модель
        $model = $this->getModel();
        $view = $this->getView();

        // Подгатавливаем входящие данные
        $model->category = JRequest::getVar('category');

        $model->sirname = trim( JRequest::getVar('sirname') );

        $model->price = JRequest::getInt('price') == null ? JRequest::getVar('price') : JRequest::getInt('price');
        $model->price = $model->price == null ? JText::_( 'AD_SEARCH_NULL' ) : $model->price;

        $model->gender = JRequest::getVar('gender');

        //echo JRequest::getVar('city');

        // Сохраняем параметры поиска в сессию
        $session = &JFactory::getSession();

        $session->set('category', $model->category);

        $session->set('sirname', $model->sirname);
        $session->set('price', $model->price);

        // Определяем город из сессии или из запроса
        if ( JRequest::getVar('city') ) {
            $model->city = JRequest::getVar('city'); }
        elseif ( $session->get('city') ) {
          $model->city = $session->get('city');  }
        else {
          $model->city = JText::_( 'AD_MOSCOW' ); }

        $session->set('city', $model->city);

        $session->set('gender', $model->gender);

        // Устанавливаем модель по умолчанию для представления
        $view->setModel($model, true);

        # Отображаем view с шаблоном результата поиска
        $view->display('result');
    }

    // Обработка события поиска специалиста
    function search_last()
    {
        // Получаем представление и модель
        $model = $this->getModel();
        $view = $this->getView();

        $session = &JFactory::getSession();

        // Вытаскиваем параметры поиска из сессии
        $model->sirname = $session->get('sirname');
        $model->price = $session->get('price');
        $model->city = $session->get('city');
        $model->gender = $session->get('gender');

        // Устанавливаем модель по умолчанию для представления
        $view->setModel($model, true);

        # Отображаем view с шаблоном результата поиска
        $view->display('result');
    }

    // Обработка события просмотра карточки специалиста/пациента, добавление специалиста для пользователя
    function view()
    {
        // Получаем представление
        $view = $this->getView();
        $model = $this->getModel();

        $session = &JFactory::getSession();

        $user = JFactory::getUser();

        // Устанавливаем модель по умолчанию для представления
        $view->setModel($model, true);

        // Проверяем, что нам известен id оператора для просмотра
        if ( !$this->input->getInt('uid') and !$session->get('uid') )
        {
          // Если uid нет ни в запросе, ни в сессии, то пытаемся взять из вошедшего пользователя
          if ( !$user->guest )
          {
              // Пытаемся получить инфу специалиста по пользователю
              $spec = $model->getOperatorMiniCardByUserID( $user->id );

              // Сажаем специалиста в сессию, если нашли
              if ($spec)
              {
                $session->set('uid', $spec->id);
              }
              else
              {
                // Если не нашли
                // Вставляем нового оператора по данным пользователя
                /*
                   if ( $uid = $model->operatorInsertByUser( $user ) )
                   {
                        $session->set('uid', $uid);
                   }
                   else
                   {
                      JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_UID' ), 'error' );
                      return;
                   }
                */

              }
          }

        }
        elseif ( !$model->getOperatorMiniCardByID() )
        {
          // Проверяем наличие оператора по UID (uid определяется в процедуре модели)
          JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_UID' ), 'error' );
          return;
        }

        # Массовое добавление адресов. Включаем в случае необходимости
        //$model->addressSetBatch();

        # Отображаем view с шаблоном карточки оператора
        $view->display('operator');
    }

    // Обработка события редактирования специалиста
    function edit()
    {
        // Получаем представление
        $view = $this->getView();
        $model = $this->getModel();

        // Проверяем возможность редактирования
        $user = JFactory::getUser();

        if ( !$model->checkOperatorEdit() )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( 'AD_MSG_ACCESS_DENIED' ), 'error' ); return;
        }

        // Проверяем существование оператора по UID
        if ( !$model->getOperatorMiniCardByID() )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_UID' ), 'error' );
            return;
        }

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

        // Проверяем, что вошедший пользователь имел право на редактирование....
        $user = JFactory::getUser();

        if ( !$model->checkOperatorEdit() )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( 'AD_MSG_ACCESS_DENIED' ), 'error' ); return;
        }

        // Проверяем существование оператора по UID

        # Выбираем текущие данные клиента
        $currentData = $model->getOperatorMiniCardByID();

        if ( !$currentData )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_UID' ), 'error' ); return;
        }

        // Контейнер для параметров апдейта
            $object = new stdClass();
            $address = new stdClass();

        /*
            Проверяем корректность новых данных и заполняем массив для UPDATE
        */

            $object->id = $this->input->getInt('uid'); // uid уже был проверен ранее

        // Данные клиента
            if ( JRequest::getString('sirname') )
            {
                $object->sirname = trim(JRequest::getString('sirname'));
            }
            if ( JRequest::getString('name') ) { $object->name = trim(JRequest::getString('name')); }
            if ( JRequest::getString('patronymic') ) { $object->patronymic = trim(JRequest::getString('patronymic')); }

            if ( JRequest::getString('gender') ) { $object->gender = trim(JRequest::getString('gender')); }

            // Дата рождения
            if ( $date = trim(JRequest::getString('birth_date')) )
            {
                if ($model->checkDate($date)) {

                    $object->birth_date = $model->checkDate($date);
                }
                else {
                    JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_BIRTHDATE' ).': '.$date, 'error' );
                }
            }

            if ( JRequest::getInt('price') ) { $object->price = JRequest::getInt('price');  }
            if ( JRequest::getString('description') ) { $object->description = substr(trim(JRequest::getString('description')), 0, 254); }

            // Контакты
            if ( JRequest::getString('phone') )
            {
                // Удаляем лишние символы из номера телефона
                $vowels = array("(", ")", " ", "-");
                $object->phone = str_replace($vowels, "", JRequest::getString('phone'));

                // Проверяем длину и +
                # ...
            }

            if ( JRequest::getString('email') ) { $object->email = JRequest::getString('email'); }

            // Обрабатываем адрес

              if ( JRequest::getString('country') ) { $address->country = trim(JRequest::getString('country'));  }
              if ( JRequest::getString('region') ) { $address->region = trim(JRequest::getString('region'));  }
              if ( JRequest::getString('city') ) { $address->city = trim(JRequest::getString('city')); }
              if ( JRequest::getString('address') ) { $address->address = trim(JRequest::getString('address'));  }
              if ( JRequest::getString('gps') ) { $address->gps = trim(JRequest::getString('gps'));  }

              $address->client = $this->input->getInt('uid');

            // --------------------

            // Описания
            if ( JRequest::getString('descf') ) { $object->desc_full = JRequest::getString('descf'); }
            if ( JRequest::getString('descc') ) { $object->desc_consult = JRequest::getString('descc'); }

            // Опыт работы
            if ( $date = trim(JRequest::getString('exp')) )
            {
                if ($model->checkDate($date)) {

                    $object->exp = $model->checkDate($date);
                }
                else {
                    JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_EXP' ).': '.$date, 'error' );
                }
            }

            if ( JRequest::getString('education') ) { $object->education = JRequest::getString('education'); }

            // Ресстр по умолчанию. Пишу, то что получил не проверяя
            if ( JRequest::getString('list') ) { $object->list = JRequest::getString('list'); }

            // ------- -----------------------------

            // Получаем фотографию из запроса. Запрос нужно отправлять обязательно в POST (через GET файл не загружается)
            $file = $this->input->files->get('photo', 'Нет файла', 'array');
            # альтернативный синтаксис: JRequest::getVar( 'photo', null, 'files', 'array' ); // $this->input->files->get('photo', null, 'array')

            # ТЕСТ Параметры загруженного файла
            #foreach ($file as $key => $value) { echo $key.':'.$value.'<br>'; }

            // Если файл был передан
            if ( $file['name'] != null )
            {
              if ( $file['error'] == '0' ) {

                if ( $file['size'] < JText::_( 'AD_OPERATOR_PHOTO_SIZE' ) ) {
                    // Обрабатываем файл и пишем имя файла в базу
                    $object->photo = $model->operatorPhoto($file);
                }
                else
                {
                    JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_IMAGE' ).' Ваш файл: '.$file['size'], 'warning' );
                }
              }
              else
              {
                JFactory::getApplication()->enqueueMessage( JText::_( 'AD_FILE_DONT_UPLOADED').' : '.$file['name'], 'warning' );
              }
            }

        // Обновляем данные оператора, если все проверки прошли
        if ( $model->operatorUpdate( $object ) )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( 'AD_MSG_DATA_UPDATED' ), 'notice' );
        }

        // Если у клиента еще нет адреса - добавляем
        if ( !$model->addressGet($object->id) )
        {
            $model->addressInsert( $address );
        }
        else // Если есть - обновляем
        {
            $model->addressUpdate( $address );
        }

        // Считаем полноту заполнения данных и записываем в оператора
        $object->completeness = $model->getOperatorCompleteness( $model->getOperatorMiniCardByID( $object->id ) );
        $model->operatorUpdate( $object );

        // Пишем запись в историю, если менялась Фамилия или Имя (пока для теста)
        if ($currentData->sirname != $object->sirname or $currentData->name != $object->name)
        {
        $history = new stdClass();

        $history->event = JText::_( 'AD_EVENT_CLIENT_SAVE' );
        $history->entity = JText::_( 'AD_ENTITY_CLIENT' );
        $history->entity_id = $object->id;
        $history->value = $object->sirname.' '.$object->name;
        $history->uid = $object->id;
        $history->event_text = 'Оператор сменил фамилию имя с '.$currentData->sirname.' '.$currentData->name.' на '.$object->sirname.' '.$object->name;

        $model->historyInsert( $history );
        }

        # Отображаем view с шаблоном карточки оператора
        $view->display('operator');
    }

    // Сохранение комментария
    function comment()
    {
        // Получаем представление
        $view = $this->getView();
        $model = $this->getModel();

        // Устанавливаем модель по умолчанию для представления
        $view->setModel($model, true);

        // Контейнер для параметров апдейта
            $comment = new stdClass();

        // Проверяем существование оператора по UID - uid из контекста
        if ( !$model->getOperatorMiniCardByID() )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_UID' ), 'error' );
            return;
        }

            $comment->uid = $this->input->getInt('uid');

            # Имя комментатора
            if ( $this->input->getString('name_from') )
            {
              $comment->name_from = $this->input->getString('name_from');
            }
            else
            {
              //JError::raiseError( 4711, JText::_( 'AD_INCORRECT_NAME_FROM' ) );
              JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_NAME_FROM' ), 'error' );
              $this->view();
              return;
            }

            # Текст комментария
            if ( $this->input->getString('text') )
            {
              $comment->text = $this->input->getString('text');
            }
            else
            {
              JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_COMMENT_TEXT' ), 'error' );
              $this->view();
              return;
            }

            # Тип комментария
            if ( $this->input->getString('commtype') )
            {
              $comment->commtype = $this->input->getString('commtype');
            }
            else { $comment->commtype = JText::_( 'AD_COMMENT_TYPE_NORM' ); }

            # Статус комментария
            $comment->status = JText::_( 'AD_COMMENT_STATUS_NEW' );

            # Контакт комментатора
            $comment->contact = $this->input->getString('contact');

            # Если есть пользователь - пишем его как комментатора
            $user = JFactory::getUser();

            if ( !$user->guest )
            {
              # Сохраняем uid связанного с пользовтаелем оператора
              $spec = $model->getOperatorMiniCardByUserID($user->id);
              $comment->uid_from = $spec->id;
              # Имя комментатора меняем на имя пользователя
              $comment->name_from = $spec->sirname.' '.$spec->name;
            }


        // Вставляем комментарий в БД
        if ( !$model->commentInsert( $comment ) )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_COMMENT' ), 'error' );
        }

        # Отображаем view с шаблоном карточки оператора
        $view->display('operator');
    }


    // Обработка ситуации совпадения пользователя по ФИ с ранее загруженным специалистом из реестра.
    /*
        В случае совпадения пользователя по Ф и И с операторами без пользователей (т.е. загруженных из реестра),
        пользователь может выбрать для одного из найденных операторов:
            - «Это я» – блокируем выбранного UID, пишем в историю. Необходимость объединения данных операторов рассматриваю индивидуально.
            - «Это НЕ я» – пишем выбор в историю
        При наличии факта сделанного выбора в истории - больше не показываем это сообщение.
     */

    // Выбор "Это я"/ "Это не я" - обрабатывается параметром MODE
    function block()
    {
        // Получаем представление
        $view = $this->getView();
        $model = $this->getModel();

        // Устанавливаем модель по умолчанию для представления
        $view->setModel($model, true);

        // Текущий пользователь
        $user = JFactory::getUser();

        // Оператор с которым выполняется действие
        $uidto = $this->input->getInt('uidto');

        $mode = $this->input->getString('mode');

        // Проверяем, что передан корректный MODE

        if ( !$mode )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( 'AD_MSG_ACCESS_DENIED' ), 'error' );
            return;
        }

        // Проверяем существование специалиста для связки по UIDTO - строго из URL
        if ( !$uidto )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_UID' ), 'error' );
            return;
        }

        if ( !$model->getOperatorMiniCardByID( $uidto ) )
        {
            JFactory::getApplication()->enqueueMessage( JText::_( 'AD_INCORRECT_UID' ), 'error' );
            return;
        }

        // Проверяем право пользователя на выполнение действия

          # Не залогинился
          if ( $user->guest )
          {
              JFactory::getApplication()->enqueueMessage( JText::_( 'AD_MSG_ACCESS_DENIED' ), 'error' ); return;
          }

          # Получаем инфу текущего специалиста
          $spec = $model->getOperatorMiniCardByUserID($user->id);

          # Выбираем похожих
          $similars = $model->getOperatorSimilars( $spec->id );

          # Не имеет права связаться с этим специалистом, если переданный Uid не присутствует в выборке похожих
          $grant = false;

          foreach ($similars as $similar)
          {
            if ( $similar->id == $uidto ) { $grant = true; }
          }

          if ( $grant != true )
          {
              JFactory::getApplication()->enqueueMessage( JText::_( 'AD_MSG_ACCESS_DENIED' ), 'error' ); return;
          }

        /* Выполнение действий */

        # Нет, это НЕ я!
        if ( $mode == '2' ) {

          // Пишем запись в историю
          $history = new stdClass();

          $history->event = JText::_( 'AD_EVENT_CLIENT_SKIP' );
          $history->entity = JText::_( 'AD_ENTITY_CLIENT' );
          $history->entity_id = $uidto;
          $history->uid = $spec->id;

          $model->historyInsert( $history );

        }
        # Да, это я!
        elseif ( $mode == '1' ) {

          // Блокируем переданного оператора
          $object = new stdClass();

          $object->id = $uidto;
          $object->blocked = 1;

          $model->operatorUpdate( $object );

          // Пишем запись в историю
          $history = new stdClass();

          $history->event = JText::_( 'AD_EVENT_CLIENT_BLOCK' );
          $history->entity = JText::_( 'AD_ENTITY_CLIENT' );
          $history->entity_id = $object->id;
          $history->value = $object->blocked;
          $history->uid = $spec->id;

          $model->historyInsert( $history );

        }
        else { JFactory::getApplication()->enqueueMessage( JText::_( 'AD_MSG_ACCESS_DENIED' ), 'error' ); return; }

        # Отображаем view с шаблоном карточки оператора
        $view->display('edit');
    }

}
?>