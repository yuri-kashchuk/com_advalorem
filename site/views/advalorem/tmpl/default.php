<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;
?>

<!-- Шаблон для вывода компонента -->
  <div class="row">

      <!-- Вывод каталога специалистов-->
      <div class="col-md-9">
            <!-- Вывод заголовка-->
            <h2><?php echo $this->item; ?></h2>
            <!-- Вывод контента-->
            <?php echo $this->contentCatalog; ?>
      </div>

      <!-- Вывод блока с параметрами поиска-->
      <div class="col-md-3">
            <!-- Вывод заголовка-->
            <h2>Тэги</h2>
            <!-- Вывод контента-->
            <?php echo $this->contentTags; ?>
      </div>
  </div>
