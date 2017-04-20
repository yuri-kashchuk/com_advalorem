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


<!-- Шаблон для вывода карточки оператора -->
<div class="container">

    <!-- Вывод блока с управляющими кнопками -->
    <div class="row">

        <div class="col-md-6">
        <!-- Кнопка возврата назад к списку -->
            <a class="btn btn-default" href="<?= $this->get('SearchLink') ?>">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <?= JText::_( 'AD_BACK_TO_SEARCH' ); ?>
            </a>

            <a class="btn btn-default" href="<?= $this->get('ViewLink') ?>#comments"><?= JText::_( 'AD_HEAD_COMMENTS' ); ?></a>

        </div>
        <div class="col-md-6">

        <?
            /*
            Проверяем что есть доступ к редактированию. В принципе кнопку можно и включить.
            Защита основная на уровне контроллера
            Так что это просто для красоты.
            */
            if ( $model->checkOperatorEdit() ) {
        ?>

        <!-- Кнопка редактирования -->
            <a class="btn btn-primary pull-right" href="<?= $this->get('EditLink') ?>">
              <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
              <span class="text-capitalize"><?= JText::_( 'AD_EDIT' ); ?></span>
            </a>

        <?
            }
        ?>

        </div>

    </div>

    <hr>

    <!-- Вывод блока с мини-карточкой и контактами -->
    <div class="row">

      <div class="col-md-6">

        <!-- Вывод мини карточки -->
        <?php echo $this->viewOperatorMiniCard(null, '3-9'); ?>

      </div>

      <div class="col-md-6">

        <h3><?= JText::_( 'AD_HEAD_CONTACTS' ); ?></h3>
        <!-- Вывод контактов -->
        <?php echo $this->viewOperatorContacts(); ?>

      </div>

    </div>

    <!-- Вывод блока с общей инфо и образованием -->
    <div class="row">

      <div class="col-md-6">

        <h3><?= JText::_( 'AD_HEAD_FULLINFO' ); ?></h3>
        <!-- Вывод общей информации -->
        <?php echo $data = $this->viewOperatorFullInfo(); ?>

      </div>

      <div class="col-md-6">

        <h3><?= JText::_( 'AD_HEAD_EDUCATION' ); ?></h3>
        <!-- Вывод образования -->
        <?php echo $this->viewOperatorEducation(); ?>

      </div>

    </div>

    <!-- Вывод блока с отзывами -->
    <div class="row">

      <div class="col-md-12">

        <h3 id="comments"><?= JText::_( 'AD_HEAD_COMMENTS' ); ?>
          <a class="btn btn-default" role="button" data-toggle="collapse" href="#comment" aria-expanded="false" aria-controls="comment">
          <?= JText::_( 'AD_COMMENT' ); ?>
          </a>
        </h3>

      </div>

    </div>

    <div class="row">

      <div class="col-md-12">

        <!-- Показываем форму добавления комментария под ссылочкой -->

        <?php echo $this->viewOperatorCommentAdd(); ?>

        <!-- Вывод комментариев -->
        <?php echo $this->viewOperatorComments(); ?>

      </div>

    </div>
</div>