<?php

namespace EcomailDeps\Composer;

use EcomailDeps\Composer\Semver\VersionParser;
class InstalledVersions
{
    private static $installed = array('root' => array('pretty_version' => '1.0.0+no-version-set', 'version' => '1.0.0.0', 'aliases' => array(), 'reference' => NULL, 'name' => '__root__'), 'versions' => array('__root__' => array('pretty_version' => '1.0.0+no-version-set', 'version' => '1.0.0.0', 'aliases' => array(), 'reference' => NULL), 'doctrine/collections' => array('pretty_version' => '1.6.7', 'version' => '1.6.7.0', 'aliases' => array(), 'reference' => '55f8b799269a1a472457bd1a41b4f379d4cfba4a'), 'ecomailcz/ecomail' => array('pretty_version' => 'dev-add-event', 'version' => 'dev-add-event', 'aliases' => array(), 'reference' => '27af61dac680cdd543f7c2ba0524ae34f6cd233b'), 'monolog/monolog' => array('pretty_version' => '2.2.0', 'version' => '2.2.0.0', 'aliases' => array(), 'reference' => '1cb1cde8e8dd0f70cc0fe51354a59acad9302084'), 'opis/closure' => array('pretty_version' => '3.6.2', 'version' => '3.6.2.0', 'aliases' => array(), 'reference' => '06e2ebd25f2869e54a306dda991f7db58066f7f6'), 'php-di/invoker' => array('pretty_version' => '2.3.0', 'version' => '2.3.0.0', 'aliases' => array(), 'reference' => '992fec6c56f2d1ad1ad5fee28267867c85bfb8f9'), 'php-di/php-di' => array('pretty_version' => '6.3.3', 'version' => '6.3.3.0', 'aliases' => array(), 'reference' => 'da8e476cafc8011477e2ec9fd2e4706947758af2'), 'php-di/phpdoc-reader' => array('pretty_version' => '2.2.1', 'version' => '2.2.1.0', 'aliases' => array(), 'reference' => '66daff34cbd2627740ffec9469ffbac9f8c8185c'), 'psr/container' => array('pretty_version' => '1.1.1', 'version' => '1.1.1.0', 'aliases' => array(), 'reference' => '8622567409010282b7aeebe4bb841fe98b58dcaf'), 'psr/container-implementation' => array('provided' => array(0 => '^1.0')), 'psr/log' => array('pretty_version' => '1.1.4', 'version' => '1.1.4.0', 'aliases' => array(), 'reference' => 'd49695b909c3b7628b6289db5479a1c204601f11'), 'psr/log-implementation' => array('provided' => array(0 => '1.0.0')), 'wpify/core' => array('pretty_version' => '5.0.x-dev', 'version' => '5.0.9999999.9999999-dev', 'aliases' => array(), 'reference' => '2e9a247de0295641918d096d78b40835e9a97b4c'), 'wpify/custom-fields' => array('pretty_version' => '1.4.3', 'version' => '1.4.3.0', 'aliases' => array(), 'reference' => '79b4dc7ff0a94daf987d7a775ebc36a00730887f')));
    public static function getInstalledPackages()
    {
        return \array_keys(self::$installed['versions']);
    }
    public static function isInstalled($packageName)
    {
        return isset(self::$installed['versions'][$packageName]);
    }
    public static function satisfies(\EcomailDeps\Composer\Semver\VersionParser $parser, $packageName, $constraint)
    {
        $constraint = $parser->parseConstraints($constraint);
        $provided = $parser->parseConstraints(self::getVersionRanges($packageName));
        return $provided->matches($constraint);
    }
    public static function getVersionRanges($packageName)
    {
        if (!isset(self::$installed['versions'][$packageName])) {
            throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
        }
        $ranges = array();
        if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
            $ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
        }
        if (\array_key_exists('aliases', self::$installed['versions'][$packageName])) {
            $ranges = \array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
        }
        if (\array_key_exists('replaced', self::$installed['versions'][$packageName])) {
            $ranges = \array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
        }
        if (\array_key_exists('provided', self::$installed['versions'][$packageName])) {
            $ranges = \array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
        }
        return \implode(' || ', $ranges);
    }
    public static function getVersion($packageName)
    {
        if (!isset(self::$installed['versions'][$packageName])) {
            throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
        }
        if (!isset(self::$installed['versions'][$packageName]['version'])) {
            return null;
        }
        return self::$installed['versions'][$packageName]['version'];
    }
    public static function getPrettyVersion($packageName)
    {
        if (!isset(self::$installed['versions'][$packageName])) {
            throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
        }
        if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
            return null;
        }
        return self::$installed['versions'][$packageName]['pretty_version'];
    }
    public static function getReference($packageName)
    {
        if (!isset(self::$installed['versions'][$packageName])) {
            throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
        }
        if (!isset(self::$installed['versions'][$packageName]['reference'])) {
            return null;
        }
        return self::$installed['versions'][$packageName]['reference'];
    }
    public static function getRootPackage()
    {
        return self::$installed['root'];
    }
    public static function getRawData()
    {
        return self::$installed;
    }
    public static function reload($data)
    {
        self::$installed = $data;
    }
}
