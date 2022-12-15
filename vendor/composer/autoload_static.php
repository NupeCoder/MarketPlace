<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitedd6f2d8be4097cc9aff718eebf1fafa
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Sebbmyr\\Teams\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Sebbmyr\\Teams\\' => 
        array (
            0 => __DIR__ . '/..' . '/sebbmeyer/php-microsoft-teams-connector/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitedd6f2d8be4097cc9aff718eebf1fafa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitedd6f2d8be4097cc9aff718eebf1fafa::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitedd6f2d8be4097cc9aff718eebf1fafa::$classMap;

        }, null, ClassLoader::class);
    }
}