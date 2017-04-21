<?php
  // Запрет прямого доступа.
  defined('_JEXEC') or die;

?>

<!-- Шаблон по умолчанию для вывода компонента. Пока просто отображает результаты поиска-->
  <div class="row">

      <div class="col-md-9">
            <!--<h3><?= JText::_( 'AD_HEAD_RESULT' ); ?></h3>-->
            <!--<hr>-->
            <!-- Категории поиска -->
            <?php /*echo $this->viewSearchCategories();*/ ?>
            <p></p>
            <!-- Вывод результата поиска -->
            <?php /*echo $this->viewOperatorsList(); */ ?>

      </div>

      <div class="col-md-3">
            <!--<h3><span class="text-uppercase"><?= JText::_( 'AD_HEAD_SEARCH' ); ?></span></h3>-->
            <!--<hr>-->
            <!-- Вывод формы поиска-->
            <?php echo $this->viewSearch(); ?>
            <jdoc:include type="modules" name="login" />
      </div>

  </div>

<?php

/*
echo JURI::current().'<hr>';

echo JURI::root().'<hr>';

echo JPATH_BASE.'<hr>';	//The path to the installed Joomla! site, or JPATH_ROOT/administrator if executed from the backend.
echo JPATH_CACHE.'<hr>';	//The path to the cache folder.
echo JPATH_COMPONENT.'<hr>';	//The path to the current component being executed.
echo JPATH_COMPONENT_ADMINISTRATOR.'<hr>';	//The path to the administration folder of the current component being executed.
echo JPATH_COMPONENT_SITE.'<hr>';	//The path to the site folder of the current component being executed.
echo JPATH_ROOT.'<hr>';	//The path to the installed Joomla! site.
echo JPATH_SITE.'<hr>';	//The path to the installed Joomla! site.
echo JPATH_THEMES.'<hr>';	//The path to the templates folder.
*/


/*
  <div class="row">

      <!-- Вывод карточки специалиста ПРОМО -->

      <div class="col-md-6">
            <h3><?= JText::_( 'AD_HEAD_PROMO' ); ?></h3>
            <?php echo $this->viewOperatorsPromo(null, '3-9'); ?>
      </div>

      <!-- Вывод карточки специалиста СЛУЧАЙНО -->
     <div class="col-md-6">
            <h3><?= JText::_( 'AD_HEAD_RANDOM' ); ?></h3>
            <?php echo $this->viewOperatorsRandom(null, '3-9'); ?>
      </div>

  </div>

*/
  ?>