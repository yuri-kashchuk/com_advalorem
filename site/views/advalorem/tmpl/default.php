<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;

  $model = $this->getModel();

?>
<!-- Шаблон для вывода главной страницы компонента. -->
  <section class="">

    <div class="container">

        <h1 class="page-header text-uppercased">Найдите остеопата</h1>
        <!-- Строка с поисками -->
        <div class="row">

          <div class="col-md-6">
            <h3><a href="<?= JRoute::_( '&task=search&city='.$model->city ) ?>">В Москве</a></h3>
            <h3><a href="<?= $model->getCitiesLink() ?>">В вашем городе</a></h3>
          </div>

          <div class="col-md-6">
            <h3>По фамилии:</h3>

                  <!-- Форма поиска по фамилии  -->
                  <form id="searchBySirname" class="form-inline" action="<?= JRoute::_('') ?>" method="<?= METHOD ?>">

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

          </div>

        </div>
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

    </div>

  </section>

  <hr></hr>

  <section class="">

    <div class="container">
        <h1 class="page-header text-uppercased">О проекте</h1>

        <div class="jumbotron">
          <h2>Единый и бесплатный реестр для всех остеопатов</h2>
          <p>Каждый специалист может зарегистрироваться и разместить информацию о себе.</p>
          <p>Регистрация бесплатная, членских (и вообще любых) взносов нет.</p>
          <p>
          <a href="<?= JRoute::_( 'component/users/?view=registration' ); ?>" class="btn btn-primary btn-lg">
            Регистрация <span class="glyphicon glyphicon-chevron-right"></span>
          </a></p>
        </div>

        <h3>Причины создания</h3>

        <p>На поверку задача найти остеопата - не так тривиальна, как может показаться.</p>
        <p>В сети информация представлена различными конкурирующими реестрами, хотя они и декларируют попытку объединения всех остеопатов.
        На деле за попадание в реестр нужно платить членский внос. Поисковые системы медиков платные априори.
        Поэтому по их данным в Москве насчитывается всего несколько десятков остеопатов.
        Остеопатам без медицинского образования заявить о себе негде в принципе.
        Список найденных мной реестров смотрите <a href="#where">ниже <span class="glyphicon glyphicon-chevron-down"></span></a></p>

        <p>Это мой личный некоммерческий проект. </p>

        <h3 id="where">Где можете еще поискать остеопата</h3>
        <ul style="list-style-type: circle">
          <li>
            <h4>Реестры различных остеопатических ассоциаций</h4>

          </li>
          <li>
            <h4>Реестры остеопатических школ</h4>

          </li>
          <li>
            <h4>Универсальные системы поиска врачей</h4>

          </li>
        </ul>

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
    <h1 class="page-header text-uppercased">Поддержка</h1>

    </div>

  </section>