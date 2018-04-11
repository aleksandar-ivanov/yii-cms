<?php

namespace app\controllers;

use app\models\Module;

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
        $module->installed = false;
        $module->enabled = false;
        $module->update();

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
