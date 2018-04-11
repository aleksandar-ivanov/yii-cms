<?php

namespace app\extentions\components\module;

use yii\base\Module;

class ModulesManager
{
    protected $allModules = [];

    protected $installedModules = [];

    protected $registeredModules = [];

    /**
     * ModulesManager constructor.
     */
    public function __construct()
    {
        $this->allModules = \app\models\Module::findBySql('select * from modules')->all();
    }


    public function getAllModules()
    {
        return $this->allModules;
    }

    public function getInstalledModules()
    {
        if (!$this->installedModules) {
            $installedModules = array_filter($this->allModules, function ($module) {
                return $module->installed;
            });

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

    public function getEnabledModules()
    {
        $installedMap = [];

        foreach ($this->installedModules as $installedModule) {
            $installedMap[$installedModule->name] = $installedModule;
        }

        return array_filter($this->registeredModules, function ($module) use ($installedMap) {
            return $installedMap[$module->getUniqueId()]->enabled;
        });
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