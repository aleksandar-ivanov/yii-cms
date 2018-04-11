<?php


namespace app\extentions\components\module;


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


    public function install(Module $module)
    {

        /*// List of items with dependencies.  Order is not important
        $tables = [
            'reports' => ['posts', 'products'],
            'posts' => [],
            'products' => []
        ];

        $resolved = [];
        $unresolved = [];
        // Resolve dependencies for each table
        foreach (array_keys($tables) as $table) {
            try {
                list ($resolved, $unresolved) = $this->dep_resolve($table, $tables, $resolved, $unresolved);
            } catch (\Exception $e) {
                die("Oops! " . $e->getMessage());
            }
        }

        // Print out result
        foreach ($resolved as $table) {
            $deps = empty($tables[$table]) ? 'none' : join(',', $tables[$table]);
            print "$table (deps: $deps)\n " . "<br>";
        }*/
        $modulesTree = [];

        $moduleComposerJson = $this->getModuleComposerJson($module);

        $this->getComposerDependencies($module->getUniqueId(), $moduleComposerJson, $modulesTree);

        var_dump($modulesTree);
    }

    public function getModulePath(Module $module)
    {
        return \Yii::$app->basePath . DIRECTORY_SEPARATOR . 'modules' .
            DIRECTORY_SEPARATOR . $module->getUniqueId() . DIRECTORY_SEPARATOR;
    }

    public function getModuleComposerJson(Module $module)
    {
        $composerPath = $this->getModulePath($module) . 'composer.json';

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
        foreach ($composerJson['require'] as $name => $version) {
            $nameParts = explode('/', $name);

            if (!isset($nameParts[1])) {
                continue;
            }

            if ($nameParts[0] !== static::MODULE_ORG_PREFIX) {
                continue;
            }

            $afterSlashParts = explode('-', $nameParts[1]);

            if (!count($afterSlashParts) > 2 || ($afterSlashParts[0] . '-' . $afterSlashParts[1]) !== static::MODULE_PREFIX) {
                continue;
            }

            $moduleName = $afterSlashParts[2];
            if ($this->isModuleInstalled($moduleName)) {
                $dependencies[$modName] = [];
                continue;
            }

            $dependencies[$modName][] = $moduleName;

            $this->getComposerDependencies($moduleName, $this->getModuleComposerJson(new Module($moduleName)), $dependencies);
        }
    }

    private function getModuleNameFromComposerJson($fullName)
    {

    }
}