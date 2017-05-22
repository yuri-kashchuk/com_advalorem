<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;

  $model = $this->getModel();

  // Получаем из модели объект определения устройства
  $mobile = $model->getMobile();

  $user = JFactory::getUser();

  $document =& JFactory::getDocument();
  $document->setTitle('look4health | Главная');

?>
<!-- Шаблон для вывода главной страницы компонента. -->
  <section>

  <!--style="background-image: url(/images/headers/alma-ata.jpg);"-->

    <center>

    <?php if (!$mobile->isMobile()) : ?>

        <!--<img src="/images/headers/alma-ata.jpg" alt="">-->
        <img src="/images/headers/rainbow.jpg" alt="">

    <? endif; ?>

    </center>

    <blockquote>
      <p>&laquo;To find health should be the object of the doctor. Anyone can find disease.&raquo;</p>
      <footer><a href="https://en.wikipedia.org/wiki/Andrew_Taylor_Still">Andrew Taylor Still</a>, 1874</footer>
    </blockquote>

  </section>

  <section class="">

    <div class="container">

    <div class="row">
    <div class="col-md-12">

        <h3 class="page-header">Свободный список остеопатов</h3>

        <p>
        Создан для свободной регистрации всех специалистов в области оздоровления, работающих в соответствии с принципами
        <a href="https://ru.wikipedia.org/wiki/%D0%9E%D1%81%D1%82%D0%B5%D0%BE%D0%BF%D0%B0%D1%82%D0%B8%D1%8F">остеопатии</a>.
        </p>
        <p>
        <em>
        Система находится в режиме
        <a href="https://ru.wikipedia.org/wiki/%D0%91%D0%B5%D1%82%D0%B0-%D1%82%D0%B5%D1%81%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5">бета-тестирования</a>.
        </em>
        </p>

    </div>
    </div>

    <div class="row">

    <div class="col-md-8">

        <h3 class="page-header">Найдите остеопата</h3>
        <!-- Строка с поисками -->
        <div class="row">

          <div class="col-md-6">
            <h4><a href="<?= JRoute::_( '?task=search&city='.JText::_( 'AD_MOSCOW' ) ) ?>">Москва</a> или <a href="<?= $model->getCitiesLink() ?>">другой город</a></h4>

                  <!-- Форма поиска по городу  -->
                  <form id="searchByCity" class="form-inline" action="<?= JRoute::_('') ?>" method="<?= METHOD ?>">

                        <div class="form-group">

                        <select class="form-control input-sm" name="city">

                        <option><?= JText::_( 'AD_SEARCH_BY_CITY' ) ?></option>

                        <?

                        // Формируем список городов
                        $cities = $model->getCitiesList();

                        foreach ($cities as $value) {

                          // Выводим сам список
                          echo '<option value="'.$value->city.'">'.$value->city.' ['.$value->vol.']'.'</option>';
                        }
                        ?>
                        </select>

                        </div>

                            <button class="btn btn-primary btn-sm" type="submit">
                              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </button>

                          <input name="task" type="hidden" value="search">

                    </form>

          </div>

        </div>

        <div class="row">

          <div class="col-md-6">
            <h4>По фамилии:</h4>

                  <!-- Форма поиска по фамилии  -->
                  <form id="searchBySirname" class="form-inline" action="<?= JRoute::_('') ?>" method="<?= METHOD ?>">

                    <div class="form-group">

                          <input name="sirname" type="text" size="40" class="form-control input-sm"
                                 value="<?= $model->sirname ?>" placeholder="<?= JText::_( 'AD_SIRNAME' ); ?>">

                     </div>

                            <button class="btn btn-primary btn-sm" type="submit">
                              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </button>

                          <input name="task" type="hidden" value="search">

                    </form>

          </div>

        </div>

    </div>

    <div class="col-md-4">

    <? if ($user->guest) { ?>

        <h3 class="page-header text-uppercased">Вы остеопат?</h3>

            <p>Регистрация бесплатная для всех</p>
            <p>
            <a href="<?= JRoute::_( 'component/users/?view=registration' ); ?>" class="btn btn-primary btn-md">
              Регистрируйтесь <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
            </p>
            или
            <p>
            <a href="<?= JRoute::_( 'component/users/?view=login' ); ?>" class="btn btn-default btn-md">
              Войдите <span class="glyphicon glyphicon-chevron-right"></span>
            </a>

            </p>

    <? } else { ?>

        <h3 class="page-header text-uppercased"><?= $user->name ?></h3>

            <a href="<?= JRoute::_( '?task=view' ); ?>" class="btn btn-primary btn-md">
              Моя страница <span class="glyphicon glyphicon-chevron-right"></span>
            </a>

    <? } ?>

    </div>

    </div>

    <hr></hr>

    <!-- "Поделиться" https://tech.yandex.ru/share/  -->
    <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
    <script src="//yastatic.net/share2/share.js"></script>
    <div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,gplus,twitter"></div>

    </div>

  </section>

  <hr>

  <section class="">

    <div class="container">
    <!--
        <hr></hr>
        <p>Что такое <a href="#osteopaty">остеопатия</a> и кто такие <a href="#osteopaty">остеопаты</a>
        можете почитать <a href="#osteopaty">тут</a>.</p>
        <h3 class="">Не забывайте оставлять ваши отзывы на странице специалиста</h3>
        <p>Эта информация очень полезна для других пациентов при выборе,</p>
        <p>да и самому врачу будет интересно оценить результаты своей работы</p>
        <h3>Порядок сортировки при поиске</h3>
        Сейчас специалисты в списке сортируются по рейтингу, который расчитывается как сумма баллов выданных:
        <ol>
          <li>За каждое заполненное поле анкеты</li>
          <li>За каждый авторизованный положительный отзыв (авторизованные отрицательные - вычитаются)
          неавторизованные отзывы в рейтинге не учитываются</li>
        </ol>
    -->
    <h3 class="page-header text-uppercased">Реестры остеопатов</h3>

    <div class="panel panel-default">

      <div class="panel-heading">
        <span>Перечень официальных реестров остеопатов, которые удалось найти на просторах рунетов.</span>
      </div>

      <?= $this->viewRegLists() ?>


    </div>

    <?php

    /*

      // Создаем поток
      $opts = array(
        'http'=>array(
          'method'=>"GET",
          'header'=>"Accept-language: en\r\n" .
                    "Cookie: foo=bar\r\n"
        )
      );

      $context = stream_context_create($opts);

      // Открываем файл с помощью установленных выше HTTP-заголовков
      $url = 'http://www.enro.ru/enromember.php?id=558';

      $file = file_get_contents($url, false, $context);

      $file = iconv("KOI8-U", "UTF-8", $file);

      echo strip_tags($file);
    */

    ?>

    </div>

  </section>

  <!--

  <hr>

  <section class="" id="osteopaty">

    <div class="container">
    <h1 class="page-header text-uppercased">Об стеопатии</h1>

    </div>

  </section>
    -->
