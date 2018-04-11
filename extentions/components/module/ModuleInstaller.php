<?php


namespace app\extentions\components\module;


use yii\base\Module;

class ModuleInstaller
{
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
        $moduleComposerJson = $this->getModuleComposerJson($module);
        foreach ($moduleComposerJson['require'] as $requiredModule) {

        }
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
}