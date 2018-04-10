<?php

namespace app\extentions\components\module;

use yii\base\Module;

class ModulesManager
{
    protected $installedModules = [];

    protected $registeredModules = [];

    public function getInstalledModules()
    {
        if (!$this->installedModules) {
            $installedModules = \app\models\Module::findAll([
                'installed' => true
            ]);

            $this->installedModules = array_filter($installedModules, function ($module) {
                $moduleEntryName = ucfirst($module->name);

                $pathParts = [
                    \Yii::$app->basePath, DIRECTORY_SEPARATOR,
                    'modules', DIRECTORY_SEPARATOR, $module->name,
                    DIRECTORY_SEPARATOR, $moduleEntryName,
                    'Management.php'
                ];

                if (!file_exists(implode('', $pathParts))) {
                    return false;
                }

                $classPath = "app\modules\\$module->name\\{$moduleEntryName}Management";
                if (!class_exists($classPath)) {
                    return false;
                }

                $module->setEntryClass($classPath);

                return true;
            });
        }

        return $this->installedModules;
    }

    public function addRegisteredModule(Module $module)
    {
        $this->registeredModules[$module->getUniqueId()] = $module;
    }

    public function getRegisteredModules()
    {
        return $this->registeredModules;
    }
}