    <div class="warnIcon"></div>
    <h1>
        This page is available<br>
        <span>
            only for register users
        </span>
    </h1>
    <div class="login" style="display:block;overflow:hidden;">
        <div class="headerContent">
            <?php if ($message = $this->getFlashMessage()): ?>
                <div class="alert alert-warning" style="width: 170px;"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php echo CHtml::beginForm($this->createUrl('user/login')); ?>
                <div class="loginHeader" cellpadding="0" cellspacing="0">
                    <div class="forms">
                        <input class="textInput" placeholder="Login or E-mail" type="text" name="username"/>
                        <input class="textInput" placeholder="Password" type="password" name="password"/>
                    </div>
                </div>
                <div class="loginFooter">
                    <div class="registerButtons">
                        <div class="loginBtn">log in
                            <input type="submit" name="login"/>
                        </div>
                        <a class="registerBtn" href="<?php echo Yii::app()->controller->createUrl('register'); ?>">register</a>
                    </div>
                </div>
            <?php echo CHtml::endForm(); ?>
        </div>
    </div>