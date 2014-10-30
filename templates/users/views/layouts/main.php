<!DOCTYPE html>
<html lang="en-gb" xml:lang="en-gb" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $this->getPageTitle(); ?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>/static/reset.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>/static/style.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL; ?>/bootstrap/css/bootstrap.css" />

</head>

<body>
<div id="app" class="restrictPage">
        <?php if(!Yii::app()->user->isGuest):?>
            <div style="padding:0 30px 20px 30px;">
                <a href="<?php echo Yii::app()->createUrl('user/logout')?>" class="btn btn-info">Logout</a>
            </div>
        <?php endif;?>
        <div id="appContent">
            <?php echo $content; ?>
        </div>
</div>
<?php if(!Yii::app()->user->isGuest):?>
<script data-main="<?php echo PUBLIC_URL; ?>/js/index"  src="<?php echo PUBLIC_URL; ?>/js/lib/require.js"></script>
<?php endif;?>
</body>
</html>