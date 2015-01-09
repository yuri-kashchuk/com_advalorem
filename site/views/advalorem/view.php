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
    // Данные из модели
    protected $data;

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
        ?>
        <div>
          <!-- Тело формы  -->
          <div class="row">
            <div class="col-md-12">

              <ul class="nav nav-pills nav-stacked">
                <li><a><?= JText::_( 'AD_HEAD_PROMO' ); ?></a></li>
                <li><a><?= JText::_( 'AD_HEAD_RANDOM' ); ?></a></li>
                <li><a><?= JText::_( 'AD_HEAD_NEW' ); ?></a></li>
                <li><a><?= JText::_( 'AD_HEAD_ADV' ); ?></a></li>
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
          $content .= '<span class="label label-primary">'.$value->name.'</span> '.$br;
          }

        return $content;
    }

    // Генерируем форму поиска специалиста
    public function viewSearch()
    {
        $model = $this->getModel();
        $cities = $model->getCitiesList();

        // Формируем URL для запуска поиска
        $url = JRoute::_('index.php?option=com_advalorem');

        // В зависимости от параметров предыдущего поиска включаем ту или иную форму
        if ($sirname = JRequest::getVar('sirname'))
        {
          $collapse['searchBySirname'] = 'collapse in';
          $collapse['searchForm'] = 'collapse';
        } else
        {
          $collapse['searchBySirname'] = 'collapse';
          $collapse['searchForm'] = 'collapse in';
        }

        ?>
        <div>
          <!-- Тело формы  -->
          <div class="row">
            <div class="col-md-12">


            <!-- Аккордеон для поиска по фамилии -->
            <a onClick="$('#searchBySirname').collapse('toggle'); $('#searchForm').collapse('toggle');">
              <?= JText::_( 'AD_SIRNAME_SEARCH' ); ?>
            </a>

              <!-- Форма поиска по фамилии  -->
              <form id="searchBySirname" class="collapse <?= $collapse['searchBySirname'] ?>" action="<?= $url ?>" method="<?= METHOD ?>">

                    <div class="input-group">

                      <input name="sirname" type="text" size="40" class="form-control"
                             value="<?= $sirname ?>" placeholder="<?= JText::_( 'AD_SIRNAME' ); ?>">

                      <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit">
                          <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </button>
                      </span>

                      <input name="task" type="hidden" value="search">

                    </div>
                </form>

                <hr>

                <!-- Основная форма поиска  -->
                <form id="searchForm" role="form" class="form-horizontal <?= $collapse['searchForm'] ?>" action="<?= $url ?>" method="<?= METHOD ?>">

                <!-- Категория специалистов  -->
                <div class="form-group">
                    <div class="col-sm-12">
                        <select class="form-control" disabled>
                            <option><?= JText::_( 'AD_OSTEOPAT' ); ?></option>
                        </select>
                    </div>
                </div>

                <!-- Города  -->
                <div class="form-group">
                    <div class="col-sm-12">
                        <select class="form-control" name="city" onChange="this.form.submit()">
                        <?
                        // Формируем список городов
                        foreach ($cities as $value) {

                          // Устанавливаем значение по умолчанию для списка
                          if ($value->city == JRequest::getVar('city','')) { $selected = 'selected'; } else { $selected = ''; }

                          echo '<option '.$selected.' value="'.$value->city.'">'.$value->city.' ['.$value->vol.']</option>';
                        }
                        ?>
                        </select>
                    </div>
                </div>

                <!-- Пол  -->
                <?
                    $active = array('Male'=>null, 'Female'=>null, 'Default'=>null);
                    $checked = array('Male'=>null, 'Female'=>null, 'Default'=>null);

                    switch (JRequest::getVar('gender')){
                    case 'Мужчина': $active['Male'] = 'active'; $checked['Male'] = 'checked'; break;
                    case 'Женщина': $active['Female'] = 'active'; $checked['Female'] = 'checked'; break;
                    default:        $active['Default'] = 'active'; $checked['Default'] = 'checked';
                    }
                ?>

                <div class="form-group">

                  <div class="col-sm-12">
                    <div class="btn-group" data-toggle="buttons">

                      <label class="btn btn-default <? echo $active['Male']; ?>" onChange="this.form.submit()">
                        <input type="radio" name="gender" value="Мужчина" <? echo $checked['Male']; ?>> Мужчина
                      </label>
                      <label class="btn btn-default <? echo $active['Female']; ?>" onChange="this.form.submit()">
                        <input type="radio" name="gender" value="Женщина" autocomplete="off" <? echo $checked['Female']; ?>> Женщина
                      </label>
                      <label class="btn btn-default <? echo $active['Default']; ?>" onChange="this.form.submit()">
                        <input type="radio" name="gender" value="%" autocomplete="off" <? echo $checked['Default']; ?>> Не важно
                      </label>

                    </div>
                  </div>

                </div>

                <!-- Цена  -->
                <div class="form-group">
                    <div class="col-sm-4">
                      <label class="control-label"><?= JText::_( 'AD_PRICE' ); ?>:</label>
                    </div>
                    <div class="col-sm-8">
                      <div class="input-group">
                        <input type="text" name="price" pattern="^[0-9]+$" class="form-control" value="<?= JRequest::getInt('price') ?>">
                        <span class="input-group-addon"><?= JText::_( 'AD_RUBLES' ); ?></span>
                      </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" class="pull-right btn btn-primary"><?= JText::_( 'AD_BUTTON_SEARCH' ); ?></button>
                        <input type="hidden" name="task" value="search" >
                    </div>
                </div>

              </form>

            </div>
          </div>
        </div>
        <?

        return null;
    }

    // Ссылка на редактирование оператора
    public function linkOperatorUpdate()
    {
        return JRoute::_('index.php?option=com_advalorem&task=edit');
    }


    // Вывод каталога специалистов по результатам поиска
    public function viewOperatorsList()
    {
        // Получаем данные специалистов для мини-карточки
        $this->data = $this->get('OperatorMiniCards');

        // Формируем html списка мини карточек формата 2-6-4
        foreach ($this->data as $value) {

            $this->viewOperatorMiniCard($value, '2-6-4');

        }

        return null;
    }

    // Вывод специалистов ПРОМО
    public function viewOperatorsPromo()
    {
        $model = $this->getModel();

        // Получаем данные специалистов для мини-карточки
        $data = $model->getOperatorMiniCard(100);

        $this->viewOperatorMiniCard($data, '3-9');

        return null;
    }

    // Вывод специалистов СЛУЧАЙНО
    public function viewOperatorsRandom()
    {
        $model = $this->getModel();

        // Получаем данные специалистов для мини-карточки
        $data = $model->getOperatorMiniCard(mt_rand(1, 200));

        $this->viewOperatorMiniCard($data, '3-9');

        return null;
    }

    // Вывод мини-карточки оператора
    public function viewOperatorMiniCard($data, $view)
    {
      // Если данные не переданы: выбираем их сами
      $model = $this->getModel();

      if (!$data) { $data = $model->getOperatorMiniCard(); }


      // Формируем URL на полную карточку оператора
      $url = JRoute::_('?task=view&uid='.$data->id);

      # Готовим куски HTML кода для вставки в шаблоны

      // Фото отператора
      $photo = '<a class="pull-left" href="'.$url.'">';
      $photo .='<img src="'.JRoute::_('images/ico-blank-64x64.png').'" alt="..." class="img-thumbnail">';
      $photo .='</a>';

      // Данные оператора
      $personal = '';
      if (isset($edit)) { $personal = '<span class="pull-right glyphicon glyphicon-pencil"></span>'; }
      $personal .='<span style="font-size: xx-large">'.$data->sirname.'</span><br>';
      $personal .='<p style="font-size: large">'.$data->name.' '.$data->patronymic.'</p>';
      $personal .='<p>'.'возраст | стаж Х лет'.'<span class="btn btn-default pull-right">'.$data->price.' '.JText::_( 'AD_RUBLES' ).'</span></p>';

      // Описание
      if ( strlen($data->description) > 127 )
      {
        $description = '<p>'.substr($data->description, 0, 127).' ...'.'</p>';
        $description .= '<a data-toggle="tooltip" data-placement="bottom" class="pull-right" title="'.$data->description.'">'.JText::_('AD_GET_MORE_INFO').'</a>';
      }
      else
      {
        $description = '<p>'.$data->description.'</p>';
      }

      // Тэги
      $tags = $this->viewTags('');

      //
      switch ($view) {
      case '2-6-4':
      // Формируем HTML код одной мини-карточки в формате 2-6-4 (для 3/4 компонента)
        ?>
        <div class="thumbnail panel-body">

          <!-- Верхняя часть -->
          <div class="row">
            <div class="col-md-2"><?= $photo ?></div>
            <div class="col-md-6"><?= $personal ?></div>
            <div class="col-md-4"><?= $description ?></div>
          </div>
          <hr style="margin-top: 5px; margin-bottom: 5px;">

          <!-- Нижняя часть  -->
          <div class="row">
            <div class="col-md-12">
              <p><?= $this->viewTags() ?></p>
            </div>
          </div>

        </div>
        <?
      break;

      case '3-9':
      // Формируем HTML код одной мини-карточки в формате 3-6 (для 1/2 компонента)
        ?>
        <div class="thumbnail panel-body">

          <!-- Верхняя часть -->
          <div class="row">
            <div class="col-md-3"><?= $photo ?></div>
            <div class="col-md-9"><?= $personal ?></div>
          </div>
          <hr style="margin-top: 5px; margin-bottom: 5px;">

          <!-- Нижняя часть  -->
          <div class="row">
            <div class="col-md-6"><?= $this->viewTags('<br>') ?></div>
            <div class="col-md-6"><?= $description ?></div>
          </div>

        </div>
        <?
      break;
      default: null;
      }

      //
      return null;
    }


    // Форма редактирования оператора
    public function viewOperatorUpdate()
    {
        $model = $this->getModel();

        // Проверяем данные из запроса в зависимости от этого выстраиваем


        // Получаем данные оператора по uid из запроса
        $data = $model->getOperatorMiniCard();

        // Формируем URL для отправки данных формы
        $url = JRoute::_(''); //эквивалентно - index.php?option=com_advalorem

        ?>
          <!-- Тело формы  -->
          <div class="row">
            <div class="col-md-12">

              <!-- Форма изменения данных  -->
              <form id="updateForm" name="updateForm" class="form-horizontal" action="<?= $url ?>" method="POST" enctype="multipart/form-data">

                <!-- Фото -->
                <div class="form-group">
                    <div class="col-sm-3">
                        <img src="<?= JRoute::_('images/ico-blank-64x64.png') ?>" alt="..." class="img-thumbnail">
                    </div>
                    <div class="col-sm-9">
                        <input name="photo" type="file">
                    </div>
                </div>

                <!-- Основные поля -->
                <!-- ----------------------------------------------------------------- -->
                <div class="form-group">
                    <div class="col-sm-12">
                      <input disabled name="sirname" type="text" size="40" class="form-control" value="<?= $data->sirname ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                      <input disabled name="name" type="text" size="40" class="form-control" value="<?= $data->name ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                      <input disabled name="patronymic" type="text" size="40" class="form-control" value="<?= $data->patronymic ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <select disabled class="form-control" name="gender">

                          <option <? if ($data->gender == 'Мужчина') { echo 'selected'; } ?> value="Мужчина">Мужчина</option>
                          <option <? if ($data->gender == 'Женщина') { echo 'selected'; } ?> value="Женщина">Женщина</option>

                        </select>
                    </div>
                </div>
                <!-- Информация выше в норме не радактируется. Возможно будет доступ для определенных профилей -->

                <div class="form-group">
                    <div class="col-sm-12">
                      <input name="birth_date" type="date" class="form-control" value="<?= $data->birth_date ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                      <textarea  name="description" size="255" class="form-control" rows = 3><?= $data->description ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="input-group">
                      <input name="price" type="text" class="form-control" value="<?= $data->price ?>" placeholder="Стоимость приема" pattern="^[0-9]+$">
                      <span class="input-group-addon"><?= JText::_( 'AD_RUBLES' ); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Контактная информация -->
                <div class="form-group">
                    <div class="col-sm-12">
                      <input name="city" type="text" size="40" class="form-control" value="<?= $data->city ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                      <input name="phone" type="tel" class="form-control" value="<?= $data->phone ?>" placeholder="Телефон">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                      <input name="email" type="email" class="form-control" value="<?= $data->email ?>" placeholder="E-mail">
                    </div>
                </div>

                <!-- Кнопка сохранения -->
                <div class="form-group">
                    <div class="col-sm-12">

                        <button class="btn btn-primary pull-right" type="submit">
                          <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                          <?= JText::_( 'AD_SAVE' ); ?>
                        </button>

                        <input name="task" type="hidden" value="save">
                        <input name="uid" type="hidden" value="<?= JRequest::getInt('uid') ?>">
                    </div>
                </div>

                </form>

              </div>
            </div>
          <?
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