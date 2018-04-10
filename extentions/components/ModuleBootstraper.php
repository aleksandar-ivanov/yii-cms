<?php


namespace app\extentions\components;

use app\modules\posts\PostsManegement;
use Yii;
use yii\base\BootstrapInterface;
use app\modules\users\UserManagement;

class ModuleBootstraper implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Yii::$app->setModule('users', [
            'class' => UserManagement::class
        ]);

        Yii::$app->setModule('posts', [
            'class' => PostsManegement::class
        ]);
    }
}