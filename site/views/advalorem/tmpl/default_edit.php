<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;

  $model = $this->getModel();
?>

<?php
    // Устанавливаем заголовок для страницы
        $operator = $model->getOperatorMiniCardByID();
        $title = $operator->sirname.' '.$operator->name;

        $document =& JFactory::getDocument();
        $document->setTitle($title);
?>

<!-- Шаблон формы редактирования оператора -->
<div class="container">

    <!-- Строка с верхним меню -->
    <div class="row">
        <div class="col-md-6">

            <!-- Ссылка назад на карточку оператора -->
            <a class="btn btn-default" href="<?= $this->get('ViewLink') ?>">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <?= JText::_( 'AD_BACK' ); ?>
            </a>

        </div>

        <div class="col-md-6">
            <!-- Правый верхний блок -->

            <!-- Дублирование кнопки "Сохранить из формы updateForm" -->
            <a class="btn btn-primary pull-right" onclick="document.forms['updateForm'].submit();">
              <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
              <?= JText::_( 'AD_SAVE' ); ?>
            </a>

        </div>

    </div>

    <hr>

    <!--
        Строка со списком похожих операторов без пользователей для блокировки

        По идее он должен быть только один (или ни одного), т.к. при загрузке я должен учитывать дубли.
        Но теоретически может быть и несколько.

        Если они есть - показываем блок со списком. Основную форму не выключаем
        Оставляем решение о блокировке совпаденцев на выбор пользователя
    -->

    <?
        if ( $model->getOperatorSimilars() ) {
    ?>

    <div class="row">

      <div class="col-md-12">

            <!-- Вывод списка -->
            <?= $this->viewOperatorSimilarsList($operator->id) ?>

      </div>

    </div>

    <? } ?>

    <!-- Строка с формой редактирования -->
    <div class="row">

      <div class="col-md-12">

            <!-- Вывод формы редактирования -->
            <?= $this->viewOperatorUpdate() ?>

      </div>

    </div>

     <!-- Строка с кнопкой -->
    <div class="row">
      <div class="col-md-12">

        <!--  -->

      </div>
    </div>

</div>