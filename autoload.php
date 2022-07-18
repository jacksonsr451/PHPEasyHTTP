<?php

namespace PhpEasyHttp\Http;

use Composer\Autoload\ClassLoader;

if (class_exists('PhpEasyHttp\Http\Autoload', false) === false) {
    class Autoload
    {

        private static ClassLoader $composerAutoloader = null;

        private static array $loadedClasses = [];

        private static array $loadedFiles = [];

        private static array $searchPaths = [];


        public static function load($class): null|bool
        {
            if (self::$composerAutoloader === null) {
                if (strpos($class, 'Composer\\') === 0) {
                    return;
                }

                if (strpos(__DIR__, 'phar://') !== 0
                    && @file_exists(__DIR__.'/../../autoload.php') === true
                ) {
                    self::$composerAutoloader = include __DIR__.'/../../autoload.php';
                    if (self::$composerAutoloader instanceof ClassLoader) {
                        self::$composerAutoloader->unregister();
                        self::$composerAutoloader->register();
                    } else {
                        self::$composerAutoloader = null;
                    }
                } else {
                    self::$composerAutoloader = null;
                }
            }

            $ds   = DIRECTORY_SEPARATOR;
            $path = false;

            if (substr($class, 0, 16) === 'PhpEasyHttp\\') {
                if (substr($class, 0, 22) === 'PhpEasyHttp\Tests\\') {
                    $isInstalled = !is_dir(__DIR__.$ds.'tests');
                    if ($isInstalled === false) {
                        $path = __DIR__.$ds.'tests';
                    } else {
                        $path = '@test_dir@'.$ds.'PhpEasyHttp'.$ds.'CodeSniffer';
                    }

                    $path .= $ds.substr(str_replace('\\', $ds, $class), 22).'.php';
                } else {
                    $path = __DIR__.$ds.'src'.$ds.substr(str_replace('\\', $ds, $class), 16).'.php';
                }
            }

            if ($path === false && self::$composerAutoloader !== false) {
                $path = self::$composerAutoloader->findFile($class);
            }

            if ($path === false) {
                foreach (self::$searchPaths as $searchPath => $nsPrefix) {
                    $className = $class;
                    if ($nsPrefix !== '' && substr($class, 0, strlen($nsPrefix)) === $nsPrefix) {
                        $className = substr($class, (strlen($nsPrefix) + 1));
                    }

                    $path = $searchPath.$ds.str_replace('\\', $ds, $className).'.php';
                    if (is_file($path) === true) {
                        break;
                    }

                    $path = false;
                }
            }

            if ($path !== false && is_file($path) === true) {
                self::loadFile($path);
                return true;
            }

            return false;

        }


        public static function loadFile($path): string
        {
            if (strpos(__DIR__, 'phar://') !== 0) {
                $path = realpath($path);
                if ($path === false) {
                    return false;
                }
            }

            if (isset(self::$loadedClasses[$path]) === true) {
                return self::$loadedClasses[$path];
            }

            $classesBeforeLoad = [
                'classes'    => get_declared_classes(),
                'interfaces' => get_declared_interfaces(),
                'traits'     => get_declared_traits(),
            ];

            include $path;

            $classesAfterLoad = [
                'classes'    => get_declared_classes(),
                'interfaces' => get_declared_interfaces(),
                'traits'     => get_declared_traits(),
            ];

            $className = self::determineLoadedClass($classesBeforeLoad, $classesAfterLoad);

            self::$loadedClasses[$path]    = $className;
            self::$loadedFiles[$className] = $path;
            return self::$loadedClasses[$path];

        }


        public static function determineLoadedClass(array $classesBeforeLoad, array $classesAfterLoad): null|string
        {
            $className = null;

            $newClasses = array_diff($classesAfterLoad['classes'], $classesBeforeLoad['classes']);
            if (PHP_VERSION_ID < 70400) {
                $newClasses = array_reverse($newClasses);
            }

            $newClasses = array_reduce(
                $newClasses,
                function ($remaining, $current) {
                    return array_diff($remaining, class_parents($current));
                },
                $newClasses
            );

            foreach ($newClasses as $name) {
                if (isset(self::$loadedFiles[$name]) === false) {
                    $className = $name;
                    break;
                }
            }

            if ($className === null) {
                $newClasses = array_reverse(array_diff($classesAfterLoad['interfaces'], $classesBeforeLoad['interfaces']));
                foreach ($newClasses as $name) {
                    if (isset(self::$loadedFiles[$name]) === false) {
                        $className = $name;
                        break;
                    }
                }
            }

            if ($className === null) {
                $newClasses = array_reverse(array_diff($classesAfterLoad['traits'], $classesBeforeLoad['traits']));
                foreach ($newClasses as $name) {
                    if (isset(self::$loadedFiles[$name]) === false) {
                        $className = $name;
                        break;
                    }
                }
            }

            return $className;

        }


        public static function addSearchPath(string $path, string $nsPrefix=''): void
        {
            self::$searchPaths[$path] = rtrim(trim((string) $nsPrefix), '\\');

        }


        public static function getSearchPaths(): array
        {
            return self::$searchPaths;

        }


        public static function getLoadedClassName(string $path): string
        {
            if (isset(self::$loadedClasses[$path]) === false) {
                throw new \Exception("Cannot get class name for $path; file has not been included");
            }

            return self::$loadedClasses[$path];

        }


        public static function getLoadedFileName(string $class): string
        {
            if (isset(self::$loadedFiles[$class]) === false) {
                throw new \Exception("Cannot get file name for $class; class has not been included");
            }

            return self::$loadedFiles[$class];

        }


        public static function getLoadedClasses(): array
        {
            return self::$loadedClasses;

        }


        public static function getLoadedFiles(): array
        {
            return self::$loadedFiles;

        }


    }

    spl_autoload_register(__NAMESPACE__.'\Autoload::load', true, true);
}
