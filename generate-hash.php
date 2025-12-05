<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/common/config/main.php';
new yii\console\Application($config);

$password = 'admin123';
$hash = Yii::$app->security->generatePasswordHash($password);

echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
?>