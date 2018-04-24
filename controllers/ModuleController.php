<?php

namespace app\controllers;

use app\extentions\components\module\ModuleInstaller;
use app\models\Module;
use yii\web\Response;

class ModuleController extends \yii\web\Controller
{
    public function actionIndex()
    {

    }

    public function actionInstall($id)
    {
        $module = Module::findOne(['id' => $id]);
        $module->installed = true;
        $module->enabled = true;
        $module->update();

        return $this->redirect('/settings');
    }

    public function actionInstallmany()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $ids = \Yii::$app->request->get('ids');

        Module::updateAll([
            'installed' => true,
            'enabled' => true
        ], ['id' => $ids, 'installed' => false]);

        return 'Installed modules';
    }

    public function actionEnable($id)
    {
        $module = Module::findOne(['id' => $id]);
        $module->enabled = true;
        $module->update();

        return $this->redirect('/settings');
    }

    public function actionUninstall($id)
    {
        $module = Module::findOne(['id' => $id]);
        $yiiModule = \Yii::$app->getModule($module->name);
        $moduleInstaller = new ModuleInstaller();

        if ($dependencies = $moduleInstaller->getModuleDependencies($yiiModule)) {
            return $this->redirect('/settings');
        }

        $moduleInstaller->uninstall();

        return $this->redirect('/settings');
    }

    public function actionDisable($id)
    {
        $module = Module::findOne(['id' => $id]);
        $module->enabled = false;
        $module->update();

        return $this->redirect('/settings');
    }
}
