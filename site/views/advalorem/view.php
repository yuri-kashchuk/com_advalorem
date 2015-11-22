<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Метод обработки формы по умолчанию
define('METHOD', 'GET');

// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');

// HTML представление сообщения компонента
class AdValoremViewAdValorem extends JViewLegacy
{
	// Переопределяем метод display класса JViewLegacy.
	public function display($tpl = null)
	{
	    $model = $this->getModel();

        ?>
        <!-- Включаем tooltip в bootstrap -->
        <script> $(function () { $('[data-toggle="tooltip"]').tooltip() }) </script>
        <?

        // Отображаем представление шаблоном по умолчанию
		parent::display($tpl);
	}

    // Вывод списка категорий быстрого поиска
    public function viewSearchCategories()
    {
        $model = $this->getModel();

        $search = '';
        $random = '';

        switch ( JRequest::getVar('task') )
        {
            case 'search': { $search = 'active'; break; }
            default:       { $random = 'active'; $search ='disabled'; }
        }


        ?>
        <div>
          <!-- Тело формы  -->
          <div class="row">
            <div class="col-md-12">

              <ul class="nav nav-tabs">
                <li class="<?= $random ?>"><a href="<?= JRoute::_('index.php?option=com_advalorem') ?>"><?= JText::_( 'AD_CAT_RANDOM' ); ?></a></li>
                <li class="disabled"><a><?= JText::_( 'AD_CAT_NEW' ); ?></a></li>
                <li class="disabled"><a><?= JText::_( 'AD_CAT_POP' ); ?></a></li>
                <li class="disabled"><a><?= JText::_( 'AD_CAT_ACTIVE' ); ?></a></li>
                <li class="<?= $search ?>"><a><?= JText::_( 'AD_HEAD_SEARCH' ); ?></a></li>
              </ul>

            </div>
          </div>
        </div>
        <?

        return null;
    }

    // Список тэгов
    public function viewTags($br = null)
    {
        // Инициируем локальный буфер под HTML код
        $content = null;

        // Получаем список тэгов
        $this->data = $this->get('Tags');

        // Формируем контент для вывода списка тэгов
        foreach ($this->data as $key => $value) {
          $content .= '<span class="label label-primary">'.$value->title.'</span> '.$br;
          }

        return $content;
    }

    // Генерируем форму поиска специалиста
    public function viewSearch()
    {
        $model = $this->getModel();

        // Формируем URL для запуска поиска
        $url = JRoute::_('index.php?option=com_advalorem');

        // В зависимости от параметров предыдущего поиска включаем ту или иную форму и меняем текст ссылки
        if ($model->sirname)
        {
          $collapse['searchBySirname'] = 'collapse in';
          $collapse['searchForm'] = 'collapse';
          $link = JText::_( 'AD_SEARCH_BY_PARAMS' );
        } else
        {
          $collapse['searchBySirname'] = 'collapse';
          $collapse['searchForm'] = 'collapse in';
          $link = JText::_( 'AD_SEARCH_BY_SIRNAME' );
        }

        ?>
        <div>
          <!-- Тело формы  -->
          <div class="row">
            <div class="col-md-12">

            <script>
            function changeLink()
            {
              if ( $('#sirnameLink').text()=='<?= JText::_( 'AD_SEARCH_BY_SIRNAME' ) ?>' )
              {
                $('#sirnameLink').text('<?= JText::_( 'AD_SEARCH_BY_PARAMS' ) ?>');
              }
              else
              {
                $('#sirnameLink').text('<?= JText::_( 'AD_SEARCH_BY_SIRNAME' ) ?>');
              }

            }
            </script>


            <!-- Аккордеон для поиска по фамилии -->
            <a id='sirnameLink'
            onClick="changeLink(); $('#searchBySirname').collapse('toggle'); $('#searchForm').collapse('toggle'); ">
            <?= $link ?>
            </a>

              <!-- Форма поиска по фамилии  -->
              <form id="searchBySirname" class="collapse <?= $collapse['searchBySirname'] ?>" action="<?= $url ?>" method="<?= METHOD ?>">

                    <hr>

                    <div class="input-group">

                      <input name="sirname" type="text" size="40" class="form-control input-sm"
                             value="<?= $model->sirname ?>" placeholder="<?= JText::_( 'AD_SIRNAME' ); ?>">

                      <span class="input-group-btn">
                        <button class="btn btn-primary btn-sm" type="submit">
                          <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </button>
                      </span>

                      <input name="task" type="hidden" value="search">

                    </div>
                </form>

                <hr>

                <!-- Основная форма поиска  -->
                <form id="searchForm" role="form" class=" <?= $collapse['searchForm'] ?>" action="<?= $url ?>" method="<?= METHOD ?>"> <!-- form-horizontal -->

                <!-- Страна и город -->

                <div class="form-group">

                    <label><?= JText::_( 'AD_CITY' ) ?></label>

                    <? $this->viewSearchCountyCity(); ?>

                </div>


                <!-- Категория специалистов  -->

                <div class="form-group">

                    <label><?= JText::_( 'AD_PROFESSION' ) ?></label>

                    <select class="form-control input-sm" disabled>
                        <option><?= JText::_( 'AD_OSTEOPAT' ); ?></option>
                    </select>

                </div>

                <!-- Города  -->

                <!--
                <div class="form-group">

                    <label><?= JText::_( 'AD_CITY' ) ?></label>

                        <select class="form-control input-sm" name="city">

                        -->
                        <!-- onChange="this.form.submit()" - убрал автосабмит -->


                        <?
                        /*

                        // Формируем список городов
                        $cities = $model->getCitiesList();

                        $checked = false;

                        // Выводим пустую строку, выбранную, если ни один из пунктов не был выбран
                        foreach ($cities as $value) {
                            if ( $value->city == $model->city) { $checked = true; } else {$checked = false; }
                        }

                        echo '<option '.($checked == false ? 'selected' : '').' value="'.JText::_( 'AD_SEARCH_NULL' ).'">'.JText::_( 'AD_SEARCH_NULL' ).'</option>';

                        // Выводим остальной список
                        foreach ($cities as $value) {

                          // Обрабатываем значение NULL
                          if ( $value->city == null) { $value->city = JText::_( 'AD_SEARCH_EMPTY' ); }

                          // Устанавливаем значение по умолчанию для списка
                          if ( $value->city == $model->city) { $selected = 'selected'; } else { $selected = ''; }

                          echo '<option '.$selected.' value="'.$value->city.'">'.$value->city.' ['.$value->vol.']</option>';

                        }

                        */
                        ?>
                <!--
                        </select>

                </div>  -->

                <!-- Цена  -->

                <div class="form-group">

                    <label><?= JText::_( 'AD_PRICE' ) ?></label>

                    <div class="form-group">
                      <div class="input-group">

                        <select class="form-control input-sm" name="price">  <!-- onChange="this.form.submit()" - убрал автосабмит -->
                        <?

                        // Формируем список диапазонов цен
                        $prices = $model->getPricesList();

                        // Выводим пустую строку, выбранную, если ни один из пунктов не был выбран
                        foreach ($prices as $value) {
                            if ( $value->price == $model->price) { $checked = true; } else {$checked = false; }
                        }

                        echo '<option '.($checked == false ? 'selected' : '').' value="'.JText::_( 'AD_SEARCH_NULL' ).'">'.JText::_( 'AD_SEARCH_NULL' ).'</option>';

                        foreach ($prices as $value) {

                          // Обрабатываем значение NULL
                          if ( $value->price == null) { $value->price = JText::_( 'AD_SEARCH_EMPTY' ); }

                          // Устанавливаем значение по умолчанию для списка
                          if ( $value->price == $model->price) { $selected = 'selected'; } else { $selected = ''; }

                          // Выводим сам список
                          echo '<option '.$selected.' value="'.$value->price.'">'.$value->price.'</option>';
                        }
                        ?>
                        </select>

                        <span class="input-group-addon"><?= JText::_( 'AD_RUBLES' ) ?></span>

                        <!--<input type="text" name="price" pattern="^[0-9]+$" class="form-control"
                                value="<?= $model->price ?>" placeholder="Стоимость приема от:">-->

                      </div>
                    </div>
                </div>

                <!-- Пол  -->
                <?
                    $active = array('Male'=>null, 'Female'=>null, 'Default'=>null);
                    $checked = array('Male'=>null, 'Female'=>null, 'Default'=>null);

                    switch ( $model->gender ){
                    case 'Мужчина': $active['Male'] = 'active'; $checked['Male'] = 'checked'; break;
                    case 'Женщина': $active['Female'] = 'active'; $checked['Female'] = 'checked'; break;
                    default:        $active['Default'] = 'active'; $checked['Default'] = 'checked';
                    }
                ?>

                <div class="form-group">

                    <label><?= JText::_( 'AD_GENDER' ) ?></label>

                    <div class="form-group">
                    <div class="btn-group" data-toggle="buttons">

                      <label class="btn btn-default btn-sm <? echo $active['Male']; ?>" > <!-- onChange="this.form.submit()" убрал автосабмит -->
                        <input type="radio" name="gender" value="Мужчина" <? echo $checked['Male']; ?>> Мужчина
                      </label>
                      <label class="btn btn-default btn-sm <? echo $active['Female']; ?>" >
                        <input type="radio" name="gender" value="Женщина" autocomplete="off" <? echo $checked['Female']; ?>> Женщина
                      </label>
                      <label class="btn btn-default btn-sm <? echo $active['Default']; ?>" >
                        <input type="radio" name="gender" value="%" autocomplete="off" <? echo $checked['Default']; ?>> Не важно
                      </label>

                    </div>
                    </div>

                </div>

                <hr class = "warning">

                <button type="submit" class="pull-left btn btn-primary"><?= JText::_( 'AD_BUTTON_SEARCH' ); ?></button>
                <button type="reset" class="pull-right btn btn-default"><?= JText::_( 'AD_BUTTON_RESET' ); ?></button>
                <input type="hidden" name="task" value="search" >

              </form>

            </div>
          </div>
        </div>
        <?

        return null;
    }


    // Отображение списка стран и городов для выбора
    public function viewCountyCity()
    {
        $model = $this->getModel();

        // Формируем список городов
        $cities = $model->getCitiesList();

        $countries = $model->getCountries();

        ?>
        <div class="row">

        <div class="col-md-4">

          <div class="list-group">
            <p class="list-group-item active"><?= JText::_( 'AD_RUSSIA' ) ?></p>

            <?

            // Выводим список для России
            foreach ($cities as $value) {

              if ( $value->country != JText::_( 'AD_RUSSIA' ) ) continue;
              if ( $value->region == JText::_( 'AD_MO' ) ) continue;
              if ( $value->city == JText::_( 'AD_MOSCOW' ) ) continue;

              if ($value->city == $model->city) { $disabled = 'list-group-item-warning'; } else { $disabled = ''; }

              $link = '';

              echo '<a href="'.JRoute::_( '&task=search&city='.$value->city ).'" class="list-group-item '.$disabled.'">';
              echo ''.$value->city.' ['.$value->vol.']';
              echo '</a>';

            }

            ?>

          </div>

        </div>

        <div class="col-md-4">

          <div class="list-group">
            <p class="list-group-item active">Россия. Москва и область</p>


            <?

            // Выводим список для Москвы и Московской области
            foreach ($cities as $value) {

            // Пропускаем все кроме Росии, МО и Москвы
              if ( $value->country != JText::_( 'AD_RUSSIA' ) ) continue;
              if ( $value->region != JText::_( 'AD_MO' ) and $value->city != JText::_( 'AD_MOSCOW' ) ) continue;

              if ($value->city == $model->city) { $disabled = 'list-group-item-warning'; } else { $disabled = ''; }

              echo '<a href="'.JRoute::_( '&task=search&city='.$value->city ).'" class="list-group-item '.$disabled.'">'.$value->city.' ['.$value->vol.']</a>';


            }

            ?>

          </div>

        </div>

        <div class="col-md-4">

          <div class="list-group">

            <?

            // Выводим список по остальным странам

            # Страна
            foreach ($countries as $country) {

            // Пропускаем Россию
            if ( $country->country == JText::_( 'AD_RUSSIA' ) ) continue;

            echo '<p class="list-group-item active">'.$country->country.'</p>';

            # Список городов
              foreach ($cities as $value) {

                if ( $value->country != $country->country ) continue;

                if ($value->city == $model->city) { $disabled = 'list-group-item-warning'; } else { $disabled = ''; }

                echo '<a href="'.JRoute::_( '&task=search&city='.$value->city ).'" class="list-group-item '.$disabled.'">'.$value->city.' ['.$value->vol.']</a>';

              }

            echo '<br>';

            }

            ?>

          </div>

        </div>

        </div>
        <?

    }

    // Отображение страны и города для выбора в форме поиска
    public function viewSearchCountyCity()
    {
        $model = $this->getModel();

        $cities = $model->getCitiesList();

        foreach ($cities as $city) {
           if ( $city->city == $model->city ) {

            echo '<h5><a href="'.$model->getCitiesLink().'">'.$model->addressGetAsBreadcrumbs($city).'</a></h5>';
            // echo '<hr>';
            break;

           }
        }

        //echo $model->city;

        # Сбрасываем город на Москву, если по городу в сессии нет данных (например, если город поменяли)
        if ( $city->city != $model->city ) {

        $session = &JFactory::getSession();
        $session->set('city', JText::_( 'AD_MOSCOW' ));

        }

    }


    // Ссылка на редактирование оператора
    public function linkOperatorUpdate()
    {
        return JRoute::_('index.php?option=com_advalorem&task=edit');
    }

    // Вывод паджинатора
    public function viewPagination($totalRows, $limitStart, $limit)
    {
        $model = $this->getModel();

        // Расчетные параметры
        $curPage = ($limitStart / $limit); # Текущая страница

        $totalPages = ceil($totalRows / $limit); # Всего страниц - округляем до большего

        # Считаем сколько страниц показывать
        $stepPages = $totalPages - $curPage;
        if ($stepPages > 5) $stepPages = $curPage + 5;

        // Если все записи помещаются на страницу - ничего не выводим
        if ($totalRows <= $limit) return null;

        // Для первой страницы выключаем левую стрелочку
        if ($curPage == 0) { $disabled = 'disabled'; } else { $disabled = null; }

        ?>
          <nav>
            <ul class="pagination">
              <li class="<?= $disabled ?>">
              <?
              if ($curPage == 0) { $backHref = null; }
              else { $backHref = 'href="'.$model->getSearchLink().'&limitstart='.($limit * ($curPage - 1)).'&limit='.$limit.'"'; }
              ?>
              <!-- Ссылка "Назад" -->
                <a <?= $backHref ?> aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
              </li>
              <?
              // Номера страничек и ссылки
                for ($i = $curPage; $i < $stepPages; $i++)
                {
                    # Считаем граничные номера для страниц
                    $ls = $limit * $i;   $l = $ls + $limit - 1;

                    # Отмечаем активную страничку
                    if ($i == $curPage) { $active = 'active'; } else { $active = null; }

                    # Если номер на страничке больше максимального кол-ва записей - уравниваем их
                    if ( ($l + 1) > $totalRows) { $l = $totalRows - 1; }

                    # Выводим странички
                    echo '<li class="'.$active.'">';
                    echo '<a href="'.$model->getSearchLink().'&limitstart='.$ls.'">'.($ls + 1).' - '.($l + 1).'</a>';
                    echo '</li>';
                }
               ?>
               <!-- Ссылка "Вперед" -->
              <li class="disabled">
                <a aria-label="Next">
                  <span aria-hidden="true"><?= $totalRows  ?></span>  <!--&raquo;-->
                </a>
              </li>
            </ul>
          </nav>
        <?

    }


    // Вывод каталога специалистов по результатам поиска
    public function viewOperatorsList($category = 'search', $format = '2-6-4')
    {
        $model = $this->getModel();

        // Получаем данные специалистов для мини-карточки по категориям
        switch( $category ) {
            case 'search': $data = $this->get('OperatorMiniCardsSearch'); break;
            case 'random': $data = $this->get('OperatorMiniCardsRandom'); break;
            default: return null;
        }

        // Формируем html списка мини карточек формата 2-6-4
        foreach ($data as $value) {
            $this->viewOperatorMiniCard($value, $format);
        }

        // Рисуем паджинатор
        $this->viewPagination( $model->totalRows, $model->limitStart, $model->limit);

        // Все
        return null;
    }

    // Вывод каталога специалистов по результатам поиска
    public function viewOperatorSimilarsList()
    {
        $model = $this->getModel();

        // Получаем данные похожих специалистов
        $data = $model->getOperatorSimilars();

        // Если ничего не нашли - ничего не делаем
        if (!$data) { return null; }

        // Выводим сообщение
        #JFactory::getApplication()->enqueueMessage( JText::_( 'AD_SIMILAR_FOUND' ) );

        // Выводим табличку с операторами
          foreach ($data as $similar)
          {

          // Выводим строку таблицы
          ?>
          <div class="alert alert-warning row" role="alert">
              <div class="col-md-1"><a href="<?= $model->getSignLink($similar->id)  ?>">Удалить</a></div>
              <div class="col-md-11"><?= $similar->sirname.' '.$similar->name.' '.$similar->patronymic ?></div>
          </div>
          <?
          }

          // Черточка
          ?>
          <hr></hr>
        <?

        // Все
        return null;
    }

    // Вывод специалистов ПРОМО
    public function viewOperatorsPromo()
    {
        $model = $this->getModel();

        // Получаем данные специалистов для мини-карточки
        $data = $model->getOperatorMiniCardByID(100);

        $this->viewOperatorMiniCard($data, '3-9');

        return null;
    }

    // Вывод специалистов СЛУЧАЙНО
    public function viewOperatorsRandom()
    {
        $model = $this->getModel();

        // Получаем данные специалистов для мини-карточки
        $data = $model->getOperatorMiniCardByID(mt_rand(1, 200));

        $this->viewOperatorMiniCard($data, '3-9');

        return null;
    }

    // Вывод мини-карточки оператора
    public function viewOperatorMiniCard($data, $view)
    {
      // Если данные не переданы: выбираем их сами
      $model = $this->getModel();

      if (!$data) { $data = $model->getOperatorMiniCardByID(); }

      if (!$data->age) {$data->age = 'нет';}

      // Формируем URL на полную карточку оператора
      $url = JRoute::_('?task=view&uid='.$data->id);

      # Готовим куски HTML кода для вставки в шаблоны

      // Фото отператора
      $photo = '<a class="pull-left" href="'.$url.'">';

      if ($data->photo) {
        $photo .='<img src="'.JRoute::_('components/com_advalorem/images/preview/'.$data->photo).'?'.time().'" alt="..." class="img-thumbnail">';
        # Конструкция: '?'.time() добавлена, чтобы картинка не бралась из кэша и не залипала после обновления. Т.н. "соль"
      }
      else {$photo .='<span class="pull-right glyphicon glyphicon-camera img-thumbnail"></span>';}

      $photo .='</a>';

      // Данные оператора
      $personal = '';

      if (isset($edit)) { $personal .= '<span class="pull-right glyphicon glyphicon-pencil"></span>'; }

      if ( mb_strlen($data->sirname, 'utf-8') > 17 ) {  //mb_strlen($str, utf-8) Просто strlen в кодировке utf8 для кириллицы удваивает результат
        $personal .='<span style="font-size: large">'.$data->sirname.'</span><br>';
      }
      else
      { $personal .='<span style="font-size: x-large">'.$data->sirname.'</span><br>'; }


      $personal .='<p style="font-size: large">'.$data->name.' '.$data->patronymic.'</p>';
      $personal .='<p>';

      $personal .='<span class="btn btn-default pull-left">'.JText::_( 'AD_AGE' ).': '.$data->age.'</span>';

      if ( isset($data->exp) ) {

        $date1 = new DateTime($data->exp);
        $date2 = new DateTime("now");
        $interval = $date1->diff($date2);
        $years = $interval->format('%y');
        $months = $interval->format('%m');

        $personal .= '<span class="btn btn-default pull-left">'.'Cтаж: '.$years.' '.$model->YearTextArg($years).'</span>';
      }

      if ($data->price) {
        $personal .='<span class="btn btn-default pull-right">'.'Оплата: '.$data->price.' '.JText::_( 'AD_RUBLES' ).'</span>';
      }

      $personal .='<p>';

      // Описание
      if ( strlen($data->description) > 255 )
      {
        $description = '<p>'.substr($data->description, 0, 255).' ';
        $description .= '<a data-toggle="tooltip" data-placement="bottom" title="'.$data->description.'">'.JText::_('AD_GET_MORE_INFO').'</a>';
        $description .= '</p>';
      }
      else
      {
        $description = '<p>'.$data->description.'</p>';
      }

      // Тэги
      #$tags = $this->viewTags('');
      $tags = null;

      //
      switch ($view) {
      case '2-6-4':
      // Формируем HTML код одной мини-карточки в формате 2-6-4 (для 3/4 компонента)
        ?>
        <div class="panel-body"> <!--thumbnail-->

          <!-- Верхняя часть -->
          <div class="row">
            <div class="col-md-2 col-sm-3 col-xs-12"><?= $photo ?></div>
            <div class="col-md-6 col-sm-9 col-xs-12"><?= $personal ?></div>
            <div class="col-md-4 col-sm-6 col-xs-12"><?= $description ?></div>
          </div>

          <!--<hr style="margin-top: 5px; margin-bottom: 5px;">-->

          <!-- Нижняя часть  -->
          <div class="row">
            <div class="col-md-12">
              <p><?= $tags ?></p>
            </div>
          </div>

          <hr>

        </div>
        <?
      break;

      case '3-9':
      // Формируем HTML код одной мини-карточки в формате 3-6 (для 1/2 компонента)
        ?>
        <div class="panel-body"> <!-- thumbnail -->

          <!-- Верхняя часть -->
          <div class="row">
            <div class="col-md-3"><?= $photo ?></div>
            <div class="col-md-9"><?= $personal ?></div>
          </div>
          <hr style="margin-top: 5px; margin-bottom: 5px;">

          <!-- Нижняя часть  -->
          <div class="row">
            <div class="col-md-4"><?= $tags ?></div>
            <div class="col-md-8"><?= $description ?></div>
          </div>

        </div>
        <?
      break;
      default: null;
      }

      //
      return null;
    }

    // Отображение контактов
    public function viewOperatorContacts()
    {
        $model = $this->getModel();

        // Получаем данные оператора по uid из запроса
        $data = $model->getOperatorMiniCardByID();

        ?>
          <table class="table">
          <tr><td>
            <div class="row">
              <div class="col-md-1"><span class="glyphicon glyphicon-earphone"></span></div>
              <div class="col-md-11"><?= $data->phone /*$this->phone_format( $data->phone )*/ ?></div>
            </div>
          </td></tr>
          <tr><td>
            <div class="row">
              <div class="col-md-1"><span class="glyphicon glyphicon-envelope"></span></div>
              <div class="col-md-11"><a href="mailto:<?= $data->email ?>"><?= $data->email ?></a></div>
            </div>
          </td></tr>
          <tr><td>
            <div class="row">
              <div class="col-md-1"><span class="glyphicon glyphicon-home"></span></div>
              <div class="col-md-11"><?= $model->addressGetAsString($data) ?></div>
            </div>
          </td></tr>
          </table>
        <?
    }

    // Отображение общей информации
    public function viewOperatorFullInfo()
    {
        $model = $this->getModel();

        // Получаем данные оператора по uid из запроса
        $data = $model->getOperatorMiniCardByID();

        ?>
        <ul class="list-group">
          <li class="list-group-item">
            <div class="row">
              <div class="col-md-12"><?= $data->desc_full ?></div>
            </div>
          </li>

          <? if ($data->desc_consult) { ?>

          <li class="list-group-item">
            <div class="row">
              <div class="col-md-12">
                <h4><?= JText::_( 'AD_DESCC' ); ?></h4>
                <?= $data->desc_consult ?>
              </div>
            </div>
          </li>

          <? } ?>

        </ul>

        <?

    }

    // Отображение образования и реестров
    public function viewOperatorEducation()
    {
        $model = $this->getModel();

        // Получаем данные оператора по uid из запроса
        $data = $model->getOperatorMiniCardByID();

        ?>
          <table class="table">
          <tr><td><?= $data->education ?></td></tr>
          </table>
        <?
    }

    // Отображение отзывов
    public function viewOperatorComments()
    {
        $model = $this->getModel();

        // Получаем комментарии оператора по uid из контекста
        $comments = $model->commentsGet();

        if (!$comments) { return null; }

        // Выводим табличку с комментами
        ?>
          <table class="table">
          <?
          // Набиваем таблицу строками
          foreach ($comments as $comment)
          {

          // Выбираем значек типа комментария
          switch ($comment->commtype) {
            case 'GOOD' : $glyphicon = 'glyphicon glyphicon-ok text-success'; $tdclass = '';
            break;
            case 'BAD' : $glyphicon = 'glyphicon glyphicon-remove text-danger'; $tdclass = '';
            break;
            default:      $glyphicon = 'glyphicon glyphicon-adjust'; $tdclass = '';
          }

          // Выводим строку таблицы
          ?>
          <tr><td class="<?= $tdclass ?> strong">
            <div class="row">
              <div class="col-md-1"><span class="<?= $glyphicon ?>"></span></div>
              <div class="col-md-6"><strong><?= $comment->name_from; ?></strong></div>
              <div class="col-md-5"><span class="pull-right"><?= $comment->date; ?></span></div>
            </div>
            <div class="row">
              <div class="col-md-12"><?= $comment->text; ?></div>
            </div>
          </td></tr>
          <?
          }

          ?>
          </table>
        <?

    }

    // Форма добавления отзыва
    public function viewOperatorCommentAdd()
    {
        $model = $this->getModel();

        // Формируем URL для отправки данных формы
        $url = JRoute::_(''); //эквивалентно - index.php?option=com_advalorem

        ?>

          <!-- Тело формы -->
          <div class="collapse" id="comment">
          <a id="comments"></a>
          <div class="row">

            <!--

                Тело формы

            -->
            <div class="col-md-12">

            <!-- Форма изменения данных  -->
            <form id="commentForm" name="commentForm" class="form-horizontal" action="<?= $url ?>" method="POST" enctype="multipart/form-data">

                <!-- Основные поля -->
                <!-- ----------------------------------------------------------------- -->
                <div class="form-group">
                    <div class="col-sm-12">
                      <input name="name_from" type="text" size="40" class="form-control" placeholder="Ваше имя *" value="">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">

                    <div class="btn-group" data-toggle="buttons">

                      <label class="btn btn-default btn-sm " > <!-- onChange="this.form.submit()" убрал автосабмит -->
                        <input type="radio" name="commtype" value="GOOD" ><span class="glyphicon glyphicon-ok"></span>
                      </label>
                      <label class="btn btn-default btn-sm " >
                        <input type="radio" name="commtype" value="BAD" ><span class="glyphicon glyphicon-remove"></span>
                      </label>

                    </div>

                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-12">
                      <textarea  data-toggle="tooltip" data-placement="top" title="<?= JText::_( 'AD_HINT_COMMENT_TEXT' ) ?>"
                                name="text" size="3000" class="form-control" rows = 2 placeholder="Текст отзыва *"></textarea>
                    </div>
                </div>

                <!-- Кнопка сохранения -->
                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" class="pull-left btn btn-primary"><?= JText::_( 'AD_COMMENT_SAVE' ); ?></button>
                        <input name="task" type="hidden" value="comment">
                        <input name="uid" type="hidden" value="<?= JRequest::getInt('uid') ?>">
                    </div>
                </div>

            </form>
            </div>
          </div>

          </div>
          <?

          return false;
    }

    // Форма редактирования оператора
    public function viewOperatorUpdate()
    {
        $model = $this->getModel();

        // Данные запроса на корректность проверяются контроллером

        // Получаем данные оператора по uid из запроса
        $data = $model->getOperatorMiniCardByID();

        // Формируем URL для отправки данных формы
        $url = JRoute::_(''); //эквивалентно - index.php?option=com_advalorem

        ?>
          <!-- Тело формы -->
          <div class="row">

            <!-- Форма изменения данных  -->
            <form id="updateForm" name="updateForm" class="form-horizontal" action="<?= $url ?>" method="POST" enctype="multipart/form-data">

            <!--

                Тело формы левая колонка

            -->
            <div class="col-md-6">

                <!--<h3><?= $data->sirname.' '.$data->name.' '.$data->patronymic ?></h3>-->

                <!-- Фото -->
                <div class="form-group">
                    <div class="col-sm-3">
                        <img src="<?= JRoute::_('components/com_advalorem/images/preview/'.$data->photo).'?'.time() ?>" alt="..." class="img-thumbnail">
                    </div>
                    <div class="col-sm-9">
                        <input data-toggle="tooltip" data-placement="right" title="<?= JText::_( 'AD_HINT_PHOTO' ) ?>"
                            name="photo" type="file" value = "" class="btn btn-primary">
                    </div>
                </div>

                <!-- Основные поля -->
                <!-- ----------------------------------------------------------------- -->
                <div class="form-group">
                    <div class="col-sm-12">
                      <input name="sirname" type="text" size="40" class="form-control" value="<?= $data->sirname ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                      <input name="name" type="text" size="40" class="form-control" value="<?= $data->name ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                      <input name="patronymic" type="text" size="40" class="form-control" value="<?= $data->patronymic ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <select class="form-control" name="gender">

                          <option <? if ($data->gender == 'Мужчина') { echo 'selected'; } ?> value="Мужчина">Мужчина</option>
                          <option <? if ($data->gender == 'Женщина') { echo 'selected'; } ?> value="Женщина">Женщина</option>

                        </select>
                    </div>
                </div>
                <!-- Информация выше в норме не радактируется для тех, кто залит из реестров. Возможно будет доступ для определенных профилей -->

                <!-- Дата рождения -->
                <div class="form-group ">
                    <div class="col-sm-12">
                      <input data-toggle="tooltip" data-placement="top" title="<?= JText::_( 'AD_HINT_BIRTH_DATE' ) ?>"
                             name="birth_date" type="text" class="form-control" value="<?= $data->birth_date ?>" placeholder="Дата рождения" data-provide="datepicker">
                    </div>
                </div>

                <!-- Краткое описание -->
                <div class="form-group">
                    <div class="col-sm-12">
                      <textarea  data-toggle="tooltip" data-placement="top" title="<?= JText::_( 'AD_HINT_DESCRIPTION' ) ?>"
                                name="description" size="255" class="form-control" rows = 3 placeholder="Краткое описание"><?= $data->description ?></textarea>
                    </div>
                </div>

                <!-- Стоимость сеанса -->
                <div class="form-group <? if (!$data->price) echo 'has-error' ?>">
                    <div class="col-sm-12">
                        <div class="input-group">
                      <input data-toggle="tooltip" data-placement="top" title="<?= JText::_( 'AD_HINT_SALARY' ) ?>"
                        name="price" type="text" class="form-control" value="<?= $data->price ?>" placeholder="Стоимость приема" pattern="^[0-9]+$">
                      <span class="input-group-addon"><?= JText::_( 'AD_RUBLES' ); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Контактная информация -->

                <!-- Телефон -->
                <div class="form-group <? if (!$data->phone) echo 'has-error' ?> ">
                    <div class="col-sm-12">
                      <input data-toggle="tooltip" data-placement="top" title="<?= JText::_( 'AD_HINT_PHONE' ) ?>"
                            name="phone" type="tel" class="form-control" value="<?= $data->phone ?>" placeholder="Телефон">
                    </div>
                </div>

                <!-- E-mail -->
                <div class="form-group">
                    <div class="col-sm-12">
                      <input data-toggle="tooltip" data-placement="top" title="<?= JText::_( 'AD_HINT_EMAIL' ) ?>"
                            name="email" type="email" class="form-control" value="<?= $data->email ?>" placeholder="E-mail">
                    </div>
                </div>

                <!-- Адрес -->
                <h4><?= JText::_( 'AD_ADDRESS' ); ?></h4>

                <div class="form-group <? if (!$data->country) echo 'has-error' ?> ">
                    <div class="col-sm-12">
                      <input name="country" type="text" size="40" class="form-control" value="<?= $data->country ?>" placeholder="Страна">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                      <input name="region" type="text" size="40" class="form-control" value="<?= $data->region ?>" placeholder="Регион">
                    </div>
                </div>

                <div class="form-group <? if (!$data->city) echo 'has-error' ?>">
                    <div class="col-sm-12">
                      <input name="city" type="text" size="40" class="form-control" value="<?= $data->city ?>" placeholder="Город">
                    </div>
                </div>

                <div class="form-group <? if (!$data->address) echo 'has-error' ?>">
                    <div class="col-sm-12">
                      <input name="address" type="text" size="40" class="form-control" value="<?= $data->address ?>" placeholder="Адрес">
                    </div>
                </div>

                <div class="form-group <? if (!$data->gps) echo 'has-error' ?>">
                    <div class="col-sm-12">
                      <input name="gps" type="text" size="40" class="form-control" value="<?= $data->gps ?>" placeholder="GPS координаты">
                    </div>
                </div>

                <!-- Кнопка сохранения выводится в шаблоне. Тут только скрытые -->
                <div class="form-group">
                    <div class="col-sm-12">
                        <input name="task" type="hidden" value="save">
                        <input name="uid" type="hidden" value="<?= JRequest::getInt('uid') ?>">
                    </div>
                </div>

            </div>

            <!--

                Тело формы правая колонка колонка

            -->
            <div class="col-md-6">

                <!-- Подробное описание -->
                <div class="form-group">
                    <div class="col-sm-12">
                      <label><?= JText::_( 'AD_HEAD_FULLINFO' ) ?></label>
                      <textarea  data-toggle="tooltip" data-placement="top" title="<?= JText::_( 'AD_HINT_DESCF' ) ?>"
                                name="descf" size="3000" class="form-control" rows = 5><?= $data->desc_full ?></textarea>
                    </div>
                </div>

                <!-- Специфика приема -->
                <div class="form-group">
                    <div class="col-sm-12">
                      <label><?= JText::_( 'AD_DESCC' ) ?></label>
                      <textarea  data-toggle="tooltip" data-placement="top" title="<?= JText::_( 'AD_HINT_DESCC' ) ?>"
                                name="descc" size="3000" class="form-control" rows = 5><?= $data->desc_consult ?></textarea>
                    </div>
                </div>

                <h3><?= JText::_( 'AD_HEAD_EDUCATION' ); ?></h3>

                <!-- Образование. Пока в виде одного поля. Табличку и связь с реестрами сделаю потом -->
                <div class="form-group">
                    <div class="col-sm-12">
                      <textarea  data-toggle="tooltip" data-placement="top" title="<?= JText::_( 'AD_HINT_EDUCATION' ) ?>"
                                name="education" size="3000" class="form-control" rows = 2><?= $data->education ?></textarea>
                    </div>
                </div>

                <hr>

                <!-- Стаж работы -->
                <div class="form-group">
                    <div class="col-sm-12">
                      <div class="input-group">

                      <span class="input-group-addon"><?= JText::_( 'AD_EXP' ); ?></span>

                      <input data-toggle="tooltip" data-placement="top" title="<?= JText::_( 'AD_HINT_EXP' ) ?>"
                        name="exp" type="date" class="form-control" value="<?= $data->exp ?>" pattern="^[0-9]+$">

                      </div>
                    </div>
                </div>

            </div>

            </form>

          </div>
          <?
    }

    // Преобразование телефонного номера по формату. Взято отсюда: http://php.ru/forum/viewtopic.php?t=19362
    function phone_format($number, $format = '[1] [(3)] 3-2-2')
    {
        $plus = ($number[0] == '+'); // есть ли +
        $number = preg_replace('/\D/', '', $number); // убираем все знаки кроме цифр

        $len = array_sum(preg_split('/\D/', $format)); // получаем сумму чисел из $format
        $params = array_reverse(str_split($number)); // разбиваем $number на цифры и переворачиваем массив
        $params += array_fill(0, $len, 0); // забиваем пустаты предыдущего массива нулями

        $format = strrev(preg_replace('/(\d)/e', "str_repeat('d%', '\\1')", $format)); // делаем форматированную строку и переворачиваем её
        $format = call_user_func_array('sprintf', array_merge(array($format), $params)); // заполняем строку цирами
        $format = ($plus ? '+' : '').strrev($format); // возвращаем строку в нормальное положение и прилепляем + обратно, если он был

        if (preg_match_all('/\[(.*?)\]/', $format, $match)) // тут чистим от необязательных кусков
        for ($i = 0, $c = count($match[0]); $i < $c; $i++)
        if (!(int)preg_replace('/\D/', '', $match[1][$i]))
        $format = str_replace($match[0][$i], '', $format);

        return strtr(trim($format), array('[' => '', ']' => '')); // вырезаем знаки необязательности
    }

    # ------------------------------------------------------------------------------------
    // Вывод случайных чисел. Вне проекта
    public function viewRand()
    {
        // Инициируем локальный буфер под HTML код
        $content = null;

        $r['max'] = 0; $r['min'] = 100;

        // Получаем данные
        $this->data = $this->get('Rand');

        // Формируем html
        foreach ($this->data as $key => $value) {

        if ( $value > $r['max'] ) { $r['max'] = $value; }

        if ( $value < $r['min'] ) { $r['min'] = $value; }

          // Формируем HTML код
          /*
          $content .= '<table class="table">';
          $content .= ' <tr>';
          $content .= ' <td>'.$key.'</td>';
          $content .= ' <td>'.$value.'</td>';
          $content .= '</tr>';
          $content .= '</table>';
          */
        }

        $r['avg'] = array_sum($this->data)/count($this->data);

        $content .= '<span class="label label-default">MAX: '.$r['max'].'</span>';
        $content .= '<span class="label label-default">MIN: '.$r['min'].'</span>';
        $content .= '<span class="label label-default">AVG: '.$r['avg'].'</span>';

        return $content;
    }

}
?>