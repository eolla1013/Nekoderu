<?php
/**
 * Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$activationUrl = [
    '_full' => true,
    'plugin' => 'CakeDC/Users',
    'controller' => 'Users',
    'action' => 'resetPassword',
    isset($token) ? $token : ''
];
?>
<?= __d('CakeDC/Users', "こんにちわ {0} さん", isset($first_name)? $first_name : '') ?>,

<?= __d('CakeDC/Users', "次のリンクを開いてパスワードのリセットを行ってください。 {0}", $this->Url->build($activationUrl)) ?>

<?= __d('CakeDC/Users', 'いつもご利用ありがとうございます。') ?>,

