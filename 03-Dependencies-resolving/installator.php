<?php
    namespace Installator;

    require_once('./exceptions/NotExistingFile.php');
    require_once('./exceptions/NotValidJsonRepresentation.php');
    require_once('./exceptions/NotExistingProperty.php');

    use Exceptions\NotExistingFile;
    use Exceptions\NotValidJsonRepresentation;
    use Exceptions\NotExistingProperty;

    class Installator {
        private $installedModulesFolder;
        private $allPackages;
        private $dependencies;

        public function isInstalled($moduleName) {
            $installedModulesFolder = $this->installedModulesFolder;

            if (file_exists($installedModulesFolder . '/' . $moduleName))
                return true;
            return false;
        }

        public function printMessageForInstalledModule($moduleName) {
            printf("%s is already installed. ", ucwords($moduleName));
        }

        public function printAlreadyInstalledModules($modules) {
            $modulesToInstall = [];

            foreach ($modules as $moduleName)
                if ($this->isInstalled($moduleName))
                    $this->printMessageForInstalledModule($moduleName);
                else
                    $modulesToInstall[] = $moduleName;
            
            return $modulesToInstall;
        }

        public function install($moduleName) {
            printf("Installing %s.\n", $moduleName);

            if (property_exists($this->allPackages, $moduleName) && !empty($requiredPackages = $this->allPackages->{$moduleName})) {
            
                $requiredPackagesList = implode(' and ', $requiredPackages);
                printf("In order to install %s, we need to install %s. ", $moduleName, $requiredPackagesList);
                
                $packagesToInstall = $this->printAlreadyInstalledModules($requiredPackages);
                printf("\n");

                foreach ($packagesToInstall as $packageName)
                    $this->install($packageName);
            }
            
            mkdir($this->installedModulesFolder . '/' . $moduleName, 0766, true);
        }

        public function run() {
            $subDirectories = [
                'dependencies' => 'dependencies.json',
                'allPackages' => 'all_packages.json',
                'installedModules' => 'installed_modules'
            ];

            $currentDirectory = realpath('.');

            try {
                foreach ($subDirectories as $key => $value) {
                    $subDirectory = $currentDirectory . '/' . $value;
                    if (! file_exists($subDirectory)) {
                        if ($key === 'installedModules')
                            mkdir($subDirectory, 0766, true);
                        else /* Throws exception */
                            throw new NotExistingFile($value);
                    }
                }
            }

            catch (NotExistingFile $e) {
                printf("%s", $e->getErrors());
                exit(0);
            }

            try {
                $allPackages = file_get_contents($currentDirectory . '/' . $subDirectories['allPackages']);
                $this->allPackages = json_decode($allPackages);

                /* Throws exception */
                if (empty($this->allPackages))
                    throw new NotValidJsonRepresentation($subDirectories['allPackages']);

                $dependencies = file_get_contents($currentDirectory . '/' . $subDirectories['dependencies']);
                $this->dependencies = json_decode($dependencies);

                /* Throws exception */
                if (empty($this->dependencies))
                    throw new NotValidJsonRepresentation($subDirectories['dependencies']);
            }

            catch (NotValidJsonRepresentation $e) {
                printf("%s", $e->getErrors());
                exit(0);
            }
 
            try {
                if (property_exists($this->dependencies, 'dependencies'))
                    $modulesToInstall = $this->dependencies->{'dependencies'};
                else
                    throw new NotExistingProperty('dependencies in dependecies.json');
            }
            
            catch (NotExistingProperty $e) {
                printf("%s", $e->getErrors());
                exit(0);
            }

            $this->installedModulesFolder = $currentDirectory . '/' . $subDirectories['installedModules'];

            foreach ($modulesToInstall as $moduleName)
                if ($this->isInstalled($moduleName)) {
                    $this->printMessageForInstalledModule($moduleName);
                    printf("\n");
                }
                else
                    $this->install($moduleName);
            
            printf("All done.\n");
        }
    }
?>