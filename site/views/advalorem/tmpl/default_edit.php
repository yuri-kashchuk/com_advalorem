<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;
?>

<!-- Шаблон формы редактирвоания оператора -->

    <!-- Строка с верхним меню -->
    <div class="row">
        <div class="col-md-6">

            <!-- Ссылка назад на карточку оператора -->
            <a class="btn btn-default" href="<?= $this->get('ViewLink') ?>">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <?= JText::_( 'AD_CANCEL' ); ?>
            </a>

        </div>

        <div class="col-md-6">
            <!-- Дублирование кнопки "Сохранить из формы updateForm" -->
            <a class="btn btn-primary pull-right" onclick="document.forms['updateForm'].submit();">
              <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
              <?= JText::_( 'AD_SAVE' ); ?>
            </a>



        </div>

    </div>

    <hr>

    <!-- Строка с формой -->
    <div class="row">

      <div class="col-md-6">

            <!-- Вывод мини карточки -->
            <?php echo $this->viewOperatorMiniCard(null, '3-9'); ?>

      </div>

      <div class="col-md-6">

            <!-- Вывод формы редактирования -->
            <?= $this->viewOperatorUpdate() ?>

      </div>

    </div>
