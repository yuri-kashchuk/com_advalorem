<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;

  /*  Отображение результатов поиска по параметрам */
?>

<!-- Шаблон для вывода результатов поиска -->
<div class="container">

  <h3 class="page-header">Результаты поиска</h3>

  <div class="row">

      <div class="col-md-9">
            <!--<h3><?= JText::_( 'AD_HEAD_RESULT' ); ?></h3>-->
            <!--<hr>-->
            <!-- Категории поиска -->
            <?php /*echo $this->viewSearchCategories();*/ ?>
            <!-- Вывод результата поиска -->
            <?php echo $this->viewOperatorsList(); ?>

      </div>

      <div class="col-md-3">
            <!--<h3><span class="text-uppercase"><?= JText::_( 'AD_HEAD_SEARCH' ); ?></span></h3>-->
            <!--<hr>-->
            <!-- Вывод формы поиска-->
            <?php echo $this->viewSearch(); ?>

      </div>

  </div>

</div>