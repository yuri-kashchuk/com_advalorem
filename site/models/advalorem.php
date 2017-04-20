<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку modelitem Joomla.
jimport('joomla.application.component.modelitem');

// Модель по умолчанию. Используется для выборки данных при формировании контента
// Методы работы с сущностями располагаются в соответствующих моделях.

class AdValoremModelAdValorem extends JModelItem
{
    // Контейнеры для проверенных полей запроса
    public $category;  // Категория поиска
    public $sirname;
    public $price;
    public $city;
    public $gender;

    public $limit;      // Лимит для запроса
    public $limitStart; // Стартовая позиция лимита
    public $totalRows; // Общее кол-во записей в запросе для паджинации

    /*
        Ссылки на страницы компонента
    */

    // ССылка на форму редактирования специалиста
    public function getEditLink()
    {
        $session = &JFactory::getSession();

        $uid = JRequest::getInt('uid');
        if (!$uid) { $uid = $session->get('uid'); }

        return JRoute::_( '&task=edit&uid='.$uid );
    }

    // ССылка на форму карточки специалиста
    public function getViewLink($uid = null)
    {
        $session = &JFactory::getSession();

        # Если uid передан в явном виде - используем его, если нет - то из URL, если нет - из сессии
        if (!$uid) { $uid = JRequest::getInt('uid'); }
        if (!$uid) { $uid = $session->get('uid'); }

        return JRoute::_( '&task=view&uid='.$uid );
    }

    // Ссылка на привязку к записи реестра (по сути - ее удаление)
    public function getSignLink($uidto)
    {
        $session = &JFactory::getSession();

        $uid = JRequest::getInt('uid');
        if (!$uid) { $uid = $session->get('uid'); }

        return JRoute::_( '&task=sign&uidto='.$uidto );
    }

    // ССылка на результаты поиска
    public function getSearchLink()
    {
        return JRoute::_( '&task=search_last' );
    }

    // Ссылка на форму выбора города
    public function getCitiesLink()
    {
        return JRoute::_( '&task=cities' );
    }

    // Получаем список тэгов из БД
    public function getTags()
	{
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Select
        $query
            ->select($db->quoteName(array('alias', 'title')))
            ->from($db->quoteName('#__tags'))
            ->where($db->quoteName('level').' > 0')
            ->order('title ASC');

        // Reset the query using our newly populated query object.
        $db->setQuery($query);

        // Load the results
        $results = $db->loadObjectList();

		return $results;
	}

    // Получение ID для нового оператора
    public function getOperatorNewID()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        /*  Выбираем максимальный ID. */
        $query
            ->select('MAX('.$db->quoteName('id').') as id')
            ->from($db->quoteName('#__ad_client', 'c'));

        $db->setQuery($query);

        // Возвращаем одну запись
        $results = $db->loadObject();

        $uid = ++$results->id;

		return $uid;
    }

    // Формируем перечень полей для запроса карточки оператора
    public function getOperatorMiniCardFields()
    {
      $db = JFactory::getDbo();

      $fields = array('c.id', 'c.juser_id', 'c.sirname', 'c.name', 'c.patronymic', 'c.gender', 'c.email', 'c.phone',
                        'c.price', 'c.description', 'c.desc_full', 'c.desc_consult', 'c.photo',
                        'c.exp', 'a.country', 'a.region', 'a.city', 'a.address', 'a.gps');
      $fields[] = 'education';

      // Квотируем элементы массива
      $fields = $db->quoteName($fields);

      // Расчетные значения
      $fields[] = 'TIMESTAMPDIFF( YEAR, '.$db->quoteName('birth_date').', curdate() ) age';
      $fields[] = 'DATE_FORMAT( '.$db->quoteName('birth_date').', \'%d.%m.%Y\') birth_date';
      $fields[] = 'DATE_FORMAT( '.$db->quoteName('c.exp').', \'%d.%m.%Y\') exp';

      return $fields;
    }

    // Базовый запрос для поиска
    function getOperatorMiniCardsQuery()
    {

        // Подключение к БД
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query
            ->select( $this->getOperatorMiniCardFields() )
            ->from( $db->quoteName('#__ad_client', 'c'))
            ->join('LEFT', $db->quoteName('#__ad_address', 'a') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.client') . ')')
            ->where( $db->quoteName('c.profile').' = \''.JText::_( 'AD_OPERATOR' ).'\'' )
            ->where($db->quoteName('c.blocked').' = 0');

        return $query;

    }

    // Получаем данные для мини-карточки оператора на основании данных поиска
    public function getOperatorMiniCardsSearch()
    {
        // Данные из запроса
        $this->limitStart = JRequest::getInt('limitstart', 0);
        $this->limit = JRequest::getInt('limit', JText::_( 'AD_PAGE_LIMIT' ));

        # Обрабатываем значение "Не важно"
        $this->city = $this->city == JText::_( 'AD_SEARCH_NULL' ) ? '%' : $this->city;
        //$this->price = $this->price == JText::_( 'AD_SEARCH_NULL' ) ? 99999 : $this->price;

        # Обрабатываем пустое значение "Не указано"
        $this->city = $this->city == JText::_( 'AD_SEARCH_EMPTY' ) ? '' : $this->city;

        if( !$this->price ) $this->price = JText::_( 'AD_SEARCH_NULL' );

        // Подключение к БД
        $db = JFactory::getDbo();

        //$query = $db->getQuery(true);

        /*  Выбираем данные для вывода мини-карточек в каталоге.
            Отбираем только специалистов
        */

        // Если в параметрах передана категория поиска, то ищем по категрии
        //if ( $this->category == 'random' ) { $results = $this->getOperatorMiniCardsRandom(); return $results; }

        $query = $this->getOperatorMiniCardsQuery();


        // Если категории не передано, то ищем по параметрам
        /*$query
            ->select( $this->getOperatorMiniCardFields() )
            ->from( $db->quoteName('#__ad_client', 'c'))
            ->join('LEFT', $db->quoteName('#__ad_address', 'a') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.client') . ')')
            ->where( $db->quoteName('c.profile').' = \''.JText::_( 'AD_OPERATOR' ).'\'' )
            ->where($db->quoteName('c.blocked').' = 0');
        */

        // Отбор по параметрам поиска:

        if ( $this->sirname ) {
          $query
              ->where($db->quoteName('c.sirname').' like \''.$this->sirname.'%\'');
        }

        if ( !$this->sirname ) {

          $query
              ->where($db->quoteName('a.city').' LIKE \''.$this->city.'\'')
              ->where($db->quoteName('c.gender').' LIKE \''.$this->gender.'%\'');

          // Цена
          # Если выбрано "Не указано" - отбираем только с пустой ценой
          if ( $this->price == JText::_( 'AD_SEARCH_EMPTY' ) ) {
            $query
                ->where( $db->quoteName('c.price').' is null' );
          }
          elseif( $this->price != JText::_( 'AD_SEARCH_NULL' ) )
          {
            $query
                ->where( $db->quoteName('c.price').' <= '.$this->price );
          }
          # Если выбрано "Не важно" - не добавляем фильтр по цене

        }

        // Сортировка
        $sort = JRequest::getString('sort', 'c.completeness desc, c.price desc, c.sirname');

        $query
            ->order( $sort );

        // Выполняем запрос
        $db->setQuery($query, $this->limitStart, $this->limit);

        $results = $db->loadObjectList();

        // Считаем общее кол-во строк для паджинации
        $this->totalRows = $this->_getListCount($query);

		return $results;
    }

    // Получаем данные для мини-карточки оператора по фамилии
    public function getOperatorMiniCardsSirname()
    {
        // Данные из запроса
        $this->limitStart = JRequest::getInt('limitstart', 0);
        $this->limit = JRequest::getInt('limit', JText::_( 'AD_PAGE_LIMIT' ));

        // Подключение к БД
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        /*  Выбираем данные для вывода мини-карточек в каталоге.
            Отбираем только специалистов
        */

        $query
            ->select( $this->getOperatorMiniCardFields() )
            ->from( $db->quoteName('#__ad_client', 'c'))
            ->join('LEFT', $db->quoteName('#__ad_address', 'a') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.client') . ')')
            ->where( $db->quoteName('c.profile').' = \''.JText::_( 'AD_OPERATOR' ).'\'' )
            ->where($db->quoteName('c.blocked').' = 0');

          $query
              ->where($db->quoteName('c.sirname').' like \''.$this->sirname.'%\'');

        // Сортировка
        $sort = JRequest::getString('sort', 'c.completeness desc, c.price desc, c.sirname');

        $query
            ->order( $sort );

        // Выполняем запрос
        $db->setQuery($query, $this->limitStart, $this->limit);

        $results = $db->loadObjectList();

        // Считаем общее кол-во строк для паджинации
        $this->totalRows = $this->_getListCount($query);

		return $results;
    }

    // Получаем данные для мини-карточек операторов при случайном поиске
    public function getOperatorMiniCardsRandom()
    {

        // Подключение к БД
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query
            ->select($this->getOperatorMiniCardFields())
            ->from($db->quoteName('#__ad_client', 'c'))
            ->join('LEFT', $db->quoteName('#__ad_address', 'a') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.client') . ')')
            ->where($db->quoteName('c.profile').' = \''.JText::_( 'AD_OPERATOR' ).'\'' )
            ->where($db->quoteName('c.id').' >= (SELECT FLOOR( MAX( id ) * RAND( ) ) FROM '.$db->quoteName('#__ad_client').' )')
            ->where($db->quoteName('c.blocked').' = 0')
            ->order('id')
            ;

        // Выполняем запрос
        $db->setQuery($query, 0, 2); # Лимит 2 записи

        $results = $db->loadObjectList();

		return $results;
    }

    // Получение карточки специалиста по ID
    public function getOperatorMiniCardByID($uid = null)
    {
        $session = &JFactory::getSession();

        // Если UID не передан - пытаемся его получить из контекста
        if (!$uid) { $uid = JRequest::getInt('uid'); }
        if (!$uid) { $uid = $session->get('uid'); }

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        /*  Выбираем данные для вывода карточки оператора. */
        $query
            ->select($this->getOperatorMiniCardFields())
            ->from($db->quoteName('#__ad_client', 'c'))
            ->join('LEFT', $db->quoteName('#__ad_address', 'a') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.client') . ')')
            ->where($db->quoteName('c.id').' = '.$uid);

        $db->setQuery($query);

        // Возвращаем одну запись
        $result_row = $db->loadObject();

		return $result_row;
    }

    // Получение карточки специалиста по ID пользователя
    public function getOperatorMiniCardByUserID($userid)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        /*  Выбираем данные для вывода карточки оператора. */
        $query
            ->select($this->getOperatorMiniCardFields())
            ->from($db->quoteName('#__ad_client', 'c'))
            ->join('LEFT', $db->quoteName('#__ad_address', 'a') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.client') . ')')
            ->where($db->quoteName('c.juser_id').' = '.$userid);

        $db->setQuery($query);

        // Возвращаем одну запись
        $results = $db->loadObject();

		return $results;
    }


    // Получение списка похожих специалистов по Ф,И для блокировки
    public function getOperatorSimilars($uid = null)
    {
        $session = &JFactory::getSession();

        // Если UID не передан - пытаемся его получить из контекста
        if (!$uid) { $uid = JRequest::getInt('uid'); }
        if (!$uid) { $uid = $session->get('uid'); }

        // Получаем данные текущего специалиста для поиска
        $current = $this->getOperatorMiniCardByID($uid);

        // Инициилизируем запрос
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        /*  Выбираем данные операторов с одинаковым Ф.И. и без связанного пользователя.
            Не блокированных.
            Одинаковость отчества обработаем уже при выводе списка
        */
        $query
            ->select($this->getOperatorMiniCardFields())
            ->from($db->quoteName('#__ad_client', 'c'))
            ->join('LEFT', $db->quoteName('#__ad_address', 'a') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.client') . ')')
            ->where('UPPER('.$db->quoteName('c.sirname').') = UPPER(\''.$current->sirname.'\')')
            ->where('UPPER('.$db->quoteName('c.name').') = UPPER(\''.$current->name.'\')')
            ->where($db->quoteName('c.juser_id').' = 0') # Еще не ассоциирован с пользователем
            ->where($db->quoteName('c.blocked').' = 0')  # Еще не заблокирован
            ->where($db->quoteName('c.id').' <> '.$uid)
            ;

        $db->setQuery($query);

        // Возвращаем записи
        $results = $db->loadObjectList();

		return $results;
    }


    // Получение списка стран и регионов
    public function getCountries()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query
            ->select($db->quoteName('country'))
            ->select('count(1) vol')
            ->from($db->quoteName('#__ad_address'))
            ->group($db->quoteName('country'))
            ->order('count(1) desc');

        $db->setQuery($query);

        $results = $db->loadObjectList();

		return $results;
    }

    // Получение списка городов
    public function getCitiesList()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

/*
        $query
            ->select($db->quoteName('city'))
            ->select('count(1) vol')
            ->from($db->quoteName('#__ad_client'))
            ->where($db->quoteName('profile').' = \''.JText::_( 'AD_OPERATOR' ).'\'' )
            ->group($db->quoteName('city'))
            ->order('city');
*/

        $query
            ->select($db->quoteName('city').', '.$db->quoteName('country').', '.$db->quoteName('region').', '.$db->quoteName('address') )
            ->select('count(1) vol')
            ->from($db->quoteName('#__ad_address'));

        $query
            ->group($db->quoteName('city'))
            ->order('city, country, region');

        $db->setQuery($query);

        $results = $db->loadObjectList();

		return $results;
    }

    // Получение списка диапазонов цен
    public function getPricesList()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query
            ->select($db->quoteName('price'))
            ->select('count(1) vol')
            ->from($db->quoteName('#__ad_client'))
            ->where($db->quoteName('profile').' = \''.JText::_( 'AD_OPERATOR' ).'\'' )
            ->group($db->quoteName('price'))
            ->order('price DESC');

        $db->setQuery($query);

        $results = $db->loadObjectList();

		return $results;
    }

    /* Блок методов для работы с оператором */

    // Расчет балла заполненности
    public function getOperatorCompleteness($object) {

        $result = 0;

        if ( isset($object->birth_date) ) {
            if ($object->birth_date != '00.00.0000') { $result = $result + 10; }
        }

        if ( isset($object->gender) ) { $result = $result + 10; }

        if ( isset($object->price) ) {
            if ( $object->price > 0 ) { $result = $result + 30; }
        }

        if ( isset($object->description) ) { $result = $result + 20; }

        if ( isset($object->desc_full) ) { $result = $result + 20; }

        if ( isset($object->desc_consult) ) { $result = $result + 20; }

        if ( isset($object->phone) ) { $result = $result + 30; }

        if ( isset($object->email) ) { $result = $result + 30; }

        if ( isset($object->exp) ) {
            if ($object->exp != '00.00.0000') { $result = $result + 40; }
        }

        if ( isset($object->photo) ) { $result = $result + 50; }

        if ( isset($object->country) ) { $result = $result + 20; }

        if ( isset($object->city) ) { $result = $result + 30; }

        if ( isset($object->address) ) { $result = $result + 50; }

        if ( isset($object->gps) ) { $result = $result + 20; }


        return $result;
    }

    // Пакетный расчет заполненности анкет
    function operatorCompletenessBatch()
    {

    }

    // Вставка нового оператора
    function operatorInsert( $object ) {

        $result = JFactory::getDbo()->insertObject('#__ad_client', $object);

        return $result;
    }

    // Вставка нового оператора по данным пользователя
    function operatorInsertByUser( $user ) {

      $object = new stdClass();

      // Приводим в порядок строку имени пользователя

      $user->name = trim($user->name);

      while ( strpos($user->name,'  ') == true ) { $user->name = str_replace('  ', ' ', $user->name); }

      // Пытаемся получить из имени пользователя ФИО. Не мудурствуя особо.

      $names = explode(" ", $user->name);

      if ( isset($names[0]) ) { $object->sirname = $names[0]; }
      if ( isset($names[1]) ) { $object->name = $names[1]; }
      if ( isset($names[2]) ) { $object->patronymic = $names[2]; }

      $object->email = $user->email;
      $object->juser_id = $user->id;
      $object->profile = JText::_( 'AD_OPERATOR' );

      $object->id = $this->getOperatorNewID();

      // Собственно вставка записи
      if ( $this->operatorInsert( $object ) ) { return $object->id; }

      return false;
    }


    // Изменение данных оператора
    public function operatorUpdate($object = null)
    {
      // Обновляем запись данными объекта
      $result = JFactory::getDbo()->updateObject('#__ad_client', $object, 'id');

      //
      return $result;
    }

    // Обработка фото
    public function operatorPhoto($file)
    {
      // Обезопашиваем название файла
      $file['name'] = JFile::makeSafe($file['name']);

      $filepath = JPATH_COMPONENT.'/images/'.strtolower( 'photo_'.JRequest::getInt('uid').'.'.JFile::getExt( $file['name'] ) );
      // if ( strtolower(JFile::getExt($filename) ) == 'jpg') - проверка на расширение файла. Вдруг пригодится

      // Записываем файл в каталог компонента
      JFile::upload( $file['tmp_name'], $filepath );

      // Формируем имя файла
      $photo_name = strtolower( 'photo_'.JRequest::getInt('uid').'.'.JFile::getExt( $file['name'] ) );

      // Делаем превьюшку фотки
      $filepath_preview = JPATH_COMPONENT.'/images/preview/'.strtolower( 'photo_'.JRequest::getInt('uid').'.'.JFile::getExt( $file['name'] ) );

      $this->img_resize($filepath, $filepath_preview, 96, 96);

      return $photo_name;
    }


    // Обрезка изображения. Взято отсюда: http://www.php5.ru/articles/image
    public function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100)
    {
      if (!file_exists($src)) return false;

      $size = getimagesize($src);

      if ($size === false) return false;

      // Определяем исходный формат по MIME-информации, предоставленной
      // функцией getimagesize, и выбираем соответствующую формату
      // imagecreatefrom-функцию.
      $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
      $icfunc = "imagecreatefrom" . $format;
      if (!function_exists($icfunc)) return false;

      $x_ratio = $width / $size[0];
      $y_ratio = $height / $size[1];

      $ratio       = min($x_ratio, $y_ratio);
      $use_x_ratio = ($x_ratio == $ratio);

      $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
      $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
      $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
      $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

      $isrc = $icfunc($src);
      $idest = imagecreatetruecolor($width, $height);

      imagefill($idest, 0, 0, $rgb);
      imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0,
        $new_width, $new_height, $size[0], $size[1]);

      imagejpeg($idest, $dest, $quality);

      imagedestroy($isrc);
      imagedestroy($idest);

      return true;
    }

    // года/лет в зависимости от числа. Взято отсюда: http://krylov.org.ua/?p=762
    function YearTextArg($year) {
        $year = abs($year);
        $t1 = $year % 10;
        $t2 = $year % 100;
        return ($t1 == 1 && $t2 != 11 ? "год" : ($t1 >= 2 && $t1 <= 4 && ($t2 < 10 || $t2 >= 20) ? "года" : "лет"));
    }

    // Проверяет дату на соответствие формату dd.mm.yyyy
    function checkDate($date) {

        $date_parts = explode(".", $date);

        if ( !array_key_exists(0, $date_parts) ) { return false; }
        if ( !array_key_exists(1, $date_parts) ) { return false; }
        if ( !array_key_exists(2, $date_parts) ) { return false; }

        if ( !checkdate($date_parts[1], $date_parts[0], $date_parts[2]) ) { return false; }

        return $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
    }

    // Проверяет возможность редактирования специалиста
    function checkOperatorEdit() {

        // Определяем uid карточки, которую пытаемся редактировать
        $session = &JFactory::getSession();

        $uid = JRequest::getInt('uid');
        if (!$uid) { $uid = $session->get('uid'); }

        // Если не определили id специалиста, то дальше делать нечего
        if (!$uid) { return false; }

        // Получаем пользователя
        $user = JFactory::getUser();

        // Пытаемся получить инфу специалиста по пользователю
        $spec = $this->getOperatorMiniCardByUserID( $user->id );

        // Если пользователь вошел и есть открыта карточка связанного с ним оператора
        if ($spec) {
            if ( !$user->guest and $spec->id == $uid ) { return true; }
        }

        // Админу можно редактировать всех
        if ( $user->username == 'admin' ) { return true; }

        // По умолчанию редактировать нельзя.
        return false;
    }


    /*
        Блок методов для работы с комментариями
    */

    // Добавление нового комментария
    public function commentInsert( $object )
    {
        $result = JFactory::getDbo()->insertObject('#__ad_comments', $object);

        return $result;
    }

    // Получение списка комментариев по оператору
    public function commentsGet( $uid = null )
    {
        $session = &JFactory::getSession();

        // Если UID не передан - пытаемся его получить из контекста
        if (!$uid) { $uid = JRequest::getInt('uid'); }
        if (!$uid) { $uid = $session->get('uid'); }

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query
            ->select('id, uid, uid_from, name_from, commtype, text, DATE_FORMAT( '.$db->quoteName('date').', \'%d.%m.%Y\') date, status')
            ->from($db->quoteName('#__ad_comments'))
            ->where($db->quoteName('uid').' = '.$uid )
            ->order($db->quoteName('id').' desc');

        $db->setQuery($query);

        $results = $db->loadObjectList();

		return $results;
    }

    /* -------- ---------------------------------------------------------- */


    /*
      Пакет методов работы с адресом
    */

    // Вставка строки адреса
    function addressInsert( $object ) {

        $result = JFactory::getDbo()->insertObject('#__ad_address', $object);

        return $result;
    }

    // Обновляем данные адреса
    function addressUpdate( $object ) {

      // Обновляем запись данными объекта
      $result = JFactory::getDbo()->updateObject('#__ad_address', $object, 'client');

      return $result;
    }

    // Получаем одну запись из таблицы адреса по ID специалиста
    function addressGet($uid = null) {

        $session = &JFactory::getSession();

        // Если UID не передан - пытаемся его получить из контекста
        if (!$uid) { $uid = JRequest::getInt('uid'); }
        if (!$uid) { $uid = $session->get('uid'); }

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query
            ->select('*')
            ->from($db->quoteName('#__ad_address'))
            ->where($db->quoteName('client').' = '.$uid );

        $db->setQuery($query);

        $result = $db->loadObject();

		return $result;
    }

    // Возвращаем адрес одной строкой
    function addressGetAsString($data) {

        $result = $data->address;

        if ($data->city) $result = $data->city.', '.$result;
        if ($data->region and $data->region != $data->city) $result = $data->region.', '.$result;
        if ($data->country) $result = $data->country.', '.$result;

        // Убираем лишнюю запятую в конце
        if (substr($result, -2) == ', ') { $result = substr($result, 0, -2); }

        return $result;
    }

    // Возвращаем адрес до города для ссылки в поиске
    function addressGetAsBreadcrumbs($data) {

        $result = null;

        if ($data->city) $result = $data->city;
        //if ($data->region and $data->region != $data->city) $result = $data->region.' / '.$result;
        if ($data->country) $result = $data->country.' | '.$result;

        // Убираем лишнюю запятую в конце
        if (substr($result, -2) == '| ') { $result = substr($result, 0, -2); }

        return $result;
    }

    // Пакетное добавление адресов по базе операторов тем, у кого их еще нет на основании данных из поля CITY
    // Процедура повторно входимая. Доступ на запуск - только для admin
    function addressSetBatch() {

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        /*  Выбираем данные операторов, у которых нет адресов */
        $query
            ->select('c.id, c.city')
            ->from($db->quoteName('#__ad_client', 'c'))
            ->where(' NOT EXISTS ( select 1 from #__ad_address a where a.client = c.id) ');

        $db->setQuery($query);

        $results = $db->loadObjectList();

        # ТЕСТ
        foreach ($results as $data) { echo $data->id.' | '.$data->city; }

        # Добавляем новые адреса
        $address = new stdClass();

        foreach ($results as $data) {

            $address->country = 'Россия'; # По умолчанию все в Россию
            $address->city = $data->city;
            $address->client = $data->id;

            # Вставляем
            $this->addressInsert($address);

            echo $data->id.' | '.$data->city;
        }

		return $results;
    }

    /*
        Блок методов работы с историей
    */

    /*
        clientCreate - создание клиента (оператора или пациента)
        clientBlock - блокировка клиента


    */

    // Добавление новой записи в историю
    public function historyInsert( $object )
    {
        $result = JFactory::getDbo()->insertObject('#__ad_history', $object);

        return $result;
    }

    /* ------------------------------------- */

    // Генератор случайных чисел. К проекту не имеет отношения.
    /*
    public function getRand($n = 50, $k = 100000)
    {
      $bingo = array();

      for ($j = 1; $j <= $k; $j++)
      {
       $bingo[$j] = 1;

       for ($i = 1; $i <= $n; $i++)
       {
         $randA[$i] = mt_rand(1,3);
         $randB[$i] = mt_rand(1,3);

         if ($randA[$i] == $randB[$i]) { $bingo[$j]++; }
       }
       // Пересчет в %
       $bingo[$j] = $bingo[$j] * 100 / $n;
      }

       return $bingo;
    }
      */
    #--------

}

?>