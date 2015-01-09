<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;
?>

<!-- Шаблон для вывода карточки оператора -->
    <div class="row">
        <div class="col-md-6">

            <a class="btn btn-default" href="<?= $this->get('SearchLink') ?>">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <?= JText::_( 'AD_BACK_TO_SEARCH' ); ?>
            </a>

        </div>
        <div class="col-md-6">

            <a class="btn btn-primary pull-right" href="<?= $this->get('EditLink') ?>">
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
              <span class="text-capitalize"><?= JText::_( 'AD_EDIT' ); ?></span>
            </a>

        </div>
    </div>

    <hr>

    <div class="row">

      <div class="col-md-6">
            <!-- Вывод мини карточки -->
            <?php echo $this->viewOperatorMiniCard(null, '3-9'); ?>

      </div>

      <!-- Вывод блока с ... -->
      <div class="col-md-6">

      </div>

    </div>
