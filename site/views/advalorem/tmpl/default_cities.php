<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;

  $model = $this->getModel();

?>

<!-- Шаблон вывода списка городов для выбора -->
<div class="container">

    <div class="row">

        <div class="col-md-6">

            <a class="btn btn-default" href="<?= $this->get('SearchLink') ?>">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <?= JText::_( 'AD_BACK_TO_SEARCH' ); ?>
            </a>

        </div>
        <div class="col-md-6">

        </div>

    </div>

    <hr>

    <div class="row">

            <div class="col-md-12">
                <?php echo $this->viewCountyCity(); ?>

                <?
                /* Загрузка реестра остеорег
                ТЕСТ

                $model->downloadOsteoreg();
                */ 
                ?>

            </div>

    </div>
</div>
