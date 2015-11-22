<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;

  $model = $this->getModel();

?>

<!-- Шаблон для вывода главной страницы компонента. -->
  <section class="">

    <div class="container">
        <h1 class="page-header text-uppercased">Найти остеопата</h1>

        <h3>Вы можете найти остеопата <a href="<?= $model->getCitiesLink(); ?>">в вашем городе</a> или по фамилии.</h3>

        <p>
          <a href="<?= JRoute::_( '&task=search' ); ?>" class="btn btn-primary">
          Искать <span class="glyphicon glyphicon-chevron-right"></span>
          </a>
        </p>

        <p>Вы также можете подобрать специалиста по стоимости сеанса, образованию, стажу работы, полу и другим параметрам.</p>

        <p><h4>Сейчас на сайте зарегистрировано 000 остеопатов.</h4></p>

        Если вы попали сюда случайно или просто не очень понимаете кто такие <a href="#osteopaty">остеопаты</a> и что они делают,<br>
        то можете почитать об этом <a href="#osteopaty">тут</a>.

    </div>

  </section>

  <hr>

  <section class="">

    <div class="container">
    <h1 class="page-header text-uppercased">О проекте</h1>

    </div>

  </section>

  <hr>

  <section class="" id="osteopaty">

    <div class="container">
    <h1 class="page-header text-uppercased">Об стеопатии</h1>

    </div>

  </section>

  <hr>

  <section class="">

    <div class="container">
    <h1 class="page-header text-uppercased">Обо мне</h1>

    </div>

  </section>