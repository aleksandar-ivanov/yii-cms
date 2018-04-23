<?php


namespace app\extentions\components\module;


use Symfony\Component\Process\Process;
use yii\base\Module;

class ModuleInstaller
{
    const INSTALLER_MODULE = 'aleksandar-ivanov/yii-module-installer';

    const MODULE_ORG_PREFIX = 'aleksandar-ivanov';

    const MODULE_PREFIX = 'aivanov-module';

    protected $installedModules = [];

    /**
     * ModuleInstaller constructor.
     * @param array $installedModules
     */
    public function __construct()
    {
        $this->installedModules = \Yii::$container->get('modulesManager')->getInstalledModules();
    }

    /**
     * @param Module $module
     * @throws \Exception
     */
    public function install(Module $module)
    {

        $modulesTree = [];

        $moduleComposerJson = $this->getModuleComposerJson($module);

        $this->getComposerDependencies($module->getUniqueId(), $moduleComposerJson, $modulesTree);

        $resolved = [];
        $unresolved = [];
        // Resolve dependencies for each table
        foreach (array_keys($modulesTree) as $module) {
            try {
                list ($resolved, $unresolved) = $this->dep_resolve($module, $modulesTree, $resolved, $unresolved);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }


        foreach ($resolved as $module) {
            if ($this->isModuleInstalled($module)) {
                continue;
            }
            $this->installProcedure($module);
        }
    }

    public function getModulePath($moduleName)
    {
        return \Yii::$app->basePath . DIRECTORY_SEPARATOR . 'modules' .
            DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR;
    }

    public function getModuleComposerJson(Module $module)
    {
        $composerPath = $this->getModulePath($module->getUniqueId()) . 'composer.json';

        if (!file_exists($composerPath)) {
            throw new \InvalidArgumentException("The given module does not have a composer.json file. Path : {$composerPath}");
        }

        return json_decode(file_get_contents($composerPath), true);
    }

    /**
     * Recursive dependency resolution
     *
     * @param string $item Item to resolve dependencies for
     * @param array $items List of all items with dependencies
     * @param array $resolved List of resolved items
     * @param array $unresolved List of unresolved items
     * @return array
     */
    protected function dep_resolve($item, array $items, array $resolved, array $unresolved) {
        array_push($unresolved, $item);
        foreach ($items[$item] as $dep) {
            if (!in_array($dep, $resolved)) {
                if (!in_array($dep, $unresolved)) {
                    array_push($unresolved, $dep);
                    list($resolved, $unresolved) = $this->dep_resolve($dep, $items, $resolved, $unresolved);
                } else {
                    throw new \RuntimeException("Circular dependency: $item -> $dep");
                }
            }
        }
        // Add $item to $resolved if it's not already there
        if (!in_array($item, $resolved)) {
            array_push($resolved, $item);
        }
        // Remove all occurrences of $item in $unresolved
        while (($index = array_search($item, $unresolved)) !== false) {
            unset($unresolved[$index]);
        }

        return [$resolved, $unresolved];
    }

    protected function isModuleInstalled($name)
    {
        return count(array_filter($this->installedModules, function ($module) use ($name){
            return $module->name === $name;
        })) > 0;
    }

    protected function getComposerDependencies($modName, $composerJson, &$dependencies = [])
    {
        $actualDependencies = array_filter($composerJson['require'], function ($name) {
            $nameParts = explode('/', $name);

            if (!isset($nameParts[1])) {
                return false;
            }

            if ($nameParts[0] !== static::MODULE_ORG_PREFIX) {
                return false;
            }

            $afterSlashParts = explode('-', $nameParts[1]);

            if (!count($afterSlashParts) > 2 || ($afterSlashParts[0] . '-' . $afterSlashParts[1]) !== static::MODULE_PREFIX) {
                return false;
            }

            return true;
        }, ARRAY_FILTER_USE_KEY);

        if (count($actualDependencies) === 0) {
            $dependencies[$modName] = [];
        }

        foreach ($actualDependencies as $name => $version) {
            $nameParts = explode('/', $name);
            $afterSlashParts = explode('-', $nameParts[1]);
            $moduleName = $afterSlashParts[2];

            $dependencies[$modName][] = $moduleName;

            $this->getComposerDependencies($moduleName, $this->getModuleComposerJson(new Module($moduleName)), $dependencies);
        }
    }

    protected function installProcedure($moduleName)
    {
        $modulePath = $this->getModulePath($moduleName);

        $migrationsPath = $modulePath . DIRECTORY_SEPARATOR . 'migrations';

        $migrationCommand = sprintf("php yii migrate --migrationPath=%s --interactive=0", $migrationsPath);
        $process = new Process($migrationCommand, \Yii::$app->basePath);
        $process->run();

        $composerJson = $this->getModuleComposerJson(new Module($moduleName));
        $extraEntries = $composerJson['extra'] ?? [];

        if (isset($extraEntries['install']) && class_exists($extraEntries['install'])) {
            $installScript = new $extraEntries['install'];
            if (is_callable($installScript)) {
                call_user_func($installScript);
            }
        }
    }

}