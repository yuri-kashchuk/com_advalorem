<?php

  // Запрет прямого доступа.
  defined('_JEXEC') or die;

  $model = $this->getModel();

?>

<!-- Шаблон для вывода карточки оператора -->
<div class="container">

    <div class="row">

        <div class="col-md-6">
        <!-- Кнопка возврата назад к списку -->
            <a class="btn btn-default" href="<?= $this->get('SearchLink') ?>">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <?= JText::_( 'AD_BACK_TO_SEARCH' ); ?>
            </a>

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

    <div class="row">

      <div class="col-md-6">

        <!-- Вывод мини карточки -->
        <?php echo $this->viewOperatorMiniCard(null, '3-9'); ?>

      </div>

      <!-- Вывод блока с ... -->
      <div class="col-md-6">

        <h3><?= JText::_( 'AD_HEAD_CONTACTS' ); ?></h3>
        <!-- Вывод контактов -->
        <?php echo $this->viewOperatorContacts(); ?>

      </div>

    </div>

    <div class="row">

      <div class="col-md-6">

        <h3><?= JText::_( 'AD_HEAD_FULLINFO' ); ?></h3>
        <!-- Вывод общей информации -->
        <?php echo $data = $this->viewOperatorFullInfo(); ?>

      </div>

      <!-- Вывод блока с ... -->
      <div class="col-md-6">

        <h3><?= JText::_( 'AD_HEAD_EDUCATION' ); ?></h3>
        <!-- Вывод образования -->
        <?php echo $this->viewOperatorEducation(); ?>

      </div>

    </div>

    <div class="row">

      <div class="col-md-6">

        <h3 id="comments"><?= JText::_( 'AD_HEAD_COMMENTS' ); ?>
        <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#comment" aria-expanded="false" aria-controls="comment">
        <?= JText::_( 'AD_COMMENT' ); ?>
        </a>
        </h3>

      </div>

      <div class="col-md-6">

        <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#comment" aria-expanded="false" aria-controls="comment">
        <?= JText::_( 'AD_COMMENT' ); ?>
        </a>

      </div>

    </div>

    <div class="row">

      <div class="col-md-12">

        <!--Показываем форму добавления комментария - потом спрячу под ссылочку-->

        <?php echo $this->viewOperatorCommentAdd(); ?>

        <!-- Вывод комментариев -->
        <?php echo $this->viewOperatorComments(); ?>

      </div>

    </div>
</div>