<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;
?>

<!-- Шаблон для вывода компонента -->
  <div class="row">

      <div class="col-md-8">
            <h3><?= JText::_( 'AD_HEAD_RESULT' ); ?></h3>
            <hr>
            <!-- Вывод результата поиска -->
            <?php echo $this->viewOperatorsList(); ?>

      </div>

      <div class="col-md-4">
            <h3><span class="text-uppercase"><?= JText::_( 'AD_HEAD_SEARCH' ); ?></span></h3>
            <hr>
            <!-- Вывод формы поиска-->
            <?php echo $this->viewSearch(); ?>
      </div>

  </div>
