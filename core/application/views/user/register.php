    <?php if ($message = $this->getFlashMessage()): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php else : ?>
        <div class="warnIcon"></div>
        <h1>
            Registration page
        </h1>

        <div class="registration" style="display:block;overflow:hidden;">
            <div class="headerContent">
                <?php $form = $this->beginWidget('CActiveForm', array(
                    'id'=>'user-form',
                    'enableAjaxValidation'=>false,
                )); ?>

                <input type="hidden" name="redirect" value="<?php echo Yii::app()->request->getRequestUri(); ?>">
                <div class="registerHeader" cellpadding="0" cellspacing="0">
                    <div class="forms">
                        <p class="note">Fields with  <span class="required">*</span> are mandatory</p>
                        </br>
                        <?php echo $form->label($model, 'username'); ?>
                        </br>
                        <?php echo $form->textField($model, 'username' )?>
                        <?php echo $form->error($model,'username'); ?>
                        </br></br>
                        <?php echo $form->label($model, 'password'); ?>
                        </br>
                        <?php echo $form->passwordField($model, 'password') ?>
                        <?php echo $form->error($model,'password'); ?>
                        </br></br>
                        <?php echo $form->label($model, 'password_repeat'); ?>
                        </br>
                        <?php echo $form->passwordField($model, 'password_repeat') ?>
                        <?php echo $form->error($model,'password_repeat'); ?>
                    </div>
                </div>

                <div class="registerFooter">
                    <div class="registerButtons">
                        <div class="registerBtn">REGISTER
                            <input type="submit" name="register"/>
                        </div>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    <?php endif; ?>