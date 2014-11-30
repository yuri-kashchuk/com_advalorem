<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку modelitem Joomla.
jimport('joomla.application.component.modelitem');

// Модель по умолчанию. Используется для выборки данных при формировании контента
// Методы работы с сущностями располагаются в соответствующих моделях.

class AdValoremModelAdValorem extends JModelItem
{

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

    // Пормируем перечень полей для запроса карточки оператора
    public function getOperatorMiniCardFields()
    {
        return array('id', 'sirname', 'name', 'patronymic', 'gender', 'phone', 'city', 'price', 'description');
    }

    // Получаем данные для мини-карточки оператора
    public function getOperatorMiniCards()
    {
        // Подготовка данных из запроса
        $sirname = trim( JRequest::getVar('sirname') );

        $price = JRequest::getInt('price') == 0 ? 99999 : JRequest::getInt('price');
        $city = JRequest::getVar('city');
        $gender = JRequest::getVar('gender');


        // Подключение к БД
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        /*  Выбираем данные для вывода мини-карточек в каталоге.
            Отбираем только специалистов
        */

        $query
            ->select($db->quoteName( $this->getOperatorMiniCardFields() ))
            ->from($db->quoteName('#__ad_client'))
            ->where($db->quoteName('profile').' = '.'\'OPERATOR\'');

        if ($sirname) {
        $query
            ->where($db->quoteName('sirname').' like \''.$sirname.'%\'');
        }

        if (!$sirname) {
        $query
            ->where($db->quoteName('city').' = \''.$city.'\'')
            ->where($db->quoteName('gender').' LIKE \''.$gender.'%\'')
            ->where($db->quoteName('price').' < '.$price);
        }

        $query
            ->order('sirname ASC');

        // Выполняем запрос
        $db->setQuery($query);

        $results = $db->loadObjectList();

		return $results;
    }

    // Получение карточки специалиста
    public function getOperatorMiniCard($uid = null)
    {
        if (!$uid) { $uid = JRequest::getInt('uid'); }

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        /*  Выбираем данные для вывода карточки оператора. */
        $query
            ->select($db->quoteName( $this->getOperatorMiniCardFields() ))
            ->from($db->quoteName('#__ad_client'))
            ->where($db->quoteName('id').' = '.$uid);

        $db->setQuery($query);

        // Возвращаем одну запись
        $results = $db->loadObject();

		return $results;
    }

    // Получение списка городов
    public function getCitiesList()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query
            ->select($db->quoteName('city'))
            ->select('count(1) vol')
            ->from($db->quoteName('#__ad_client'))
            ->where($db->quoteName('profile').' LIKE '.'\'OPERATOR\'')
            ->group($db->quoteName('city'))
            ->order('vol DESC, city');

        $db->setQuery($query);

        $results = $db->loadObjectList();

		return $results;
    }


    // Изменение данных оператора
    public function operatorUpdate()
    {
      $object = new stdClass();

      // Значения полей
      $object->id = JRequest::getInt('uid');
      $object->sirname = '';
      $object->name = '';
      $object->patronymic = '';
      $object->gender = '';
      $object->birth_date = '';

      $object->CITY = '';
      $object->PRICE = '';
      $object->DESCRIPTION = '';


      // Обновляем запись
      $result = JFactory::getDbo()->updateObject('#__ad_client', $object, 'id');

      //
      return $result;
    }


    // Генератор случайных чилел. К проекту не имеет отношения.
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

    #--------

}

?>