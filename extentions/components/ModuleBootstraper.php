<?php


namespace app\extentions\components;

use app\extentions\components\module\ModulesManager;
use app\modules\posts\PostsManegement;
use Yii;
use yii\base\BootstrapInterface;
use app\modules\users\UserManagement;

class ModuleBootstraper implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $moduleManager = Yii::createObject(ModulesManager::class);
        Yii::$container->set('modulesManager', $moduleManager);

        $installedModules = $moduleManager->getInstalledModules();

        foreach ($installedModules as $module) {
            Yii::$app->setModule($module->name, [
                'class' => $module->getEntryClass()
            ]);

            $moduleManager->addRegisteredModule(Yii::$app->getModule($module->name));
        }
    }
}