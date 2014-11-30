<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;
?>

<!-- Шаблон по умолчанию для вывода компонента. -->
  <div class="row">

          <div class="col-md-8">
              <h3><?= JText::_( 'AD_HEAD_CATALOG' ); ?></h3>
              <hr>
              <!-- Вывод категорий быстрого поиска-->
              <?php echo $this->viewSearchCategories(); ?>
          </div>

          <div class="col-md-4">
              <h3><?= JText::_( 'AD_HEAD_SEARCH' ); ?></h3>
              <hr>
              <!-- Вывод блока с параметрами поиска-->
              <?php echo $this->viewSearch(); ?>
          </div>

  </div>

  <div class="row">

      <!-- Вывод карточки специалиста ПРОМО -->
      <div class="col-md-6">
            <!-- Вывод заголовка-->
            <h3><?= JText::_( 'AD_HEAD_PROMO' ); ?></h3>
            <!-- Вывод мини карточки -->
            <?php echo $this->viewOperatorsPromo(null, '3-9'); ?>
      </div>

      <!-- Вывод карточки специалиста СЛУЧАЙНО -->
      <div class="col-md-6">
            <!-- Вывод заголовка-->
            <h3><?= JText::_( 'AD_HEAD_RANDOM' ); ?></h3>
            <!-- Вывод мини карточки -->
            <?php echo $this->viewOperatorsRandom(null, '3-9'); ?>

      </div>

  </div>