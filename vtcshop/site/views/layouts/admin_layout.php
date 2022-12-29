<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "icon" href = "<?php echo _WEB_ROOT; ?>/public/assets/styles/IMG/logo.jpg" type = "image/x-icon">
    <title><?php echo $this->data['subcontent']['title']; ?></title>
    <link rel="stylesheet" href="<?php echo _WEB_ROOT; ?>/public/assets/styles/Font-icon/css/all.min.css" />
    <link rel="stylesheet" href="<?php echo _WEB_ROOT; ?>/public/assets/styles/css/admMain.css" />
    <link rel="stylesheet" href="<?php echo _WEB_ROOT; ?>/public/assets/styles/css/fontMIcon.css" />
    <script src="<?php echo _WEB_ROOT; ?>/public/assets/JS/jquery.min.js"></script>
</head>
<body>
    <?php $this->render('blocks/admin/header'); ?>
    <?php $this->render($content,$subcontent); ?>
    <?php $this->render('blocks/admin/footer'); ?>
</body>
</html>