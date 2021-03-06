<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7b3928cda2a000c46144f3ea337c0b12
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'Jarouche\\ViaCEP\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Jarouche\\ViaCEP\\' => 
        array (
            0 => __DIR__ . '/..' . '/jarouche/viacep/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7b3928cda2a000c46144f3ea337c0b12::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7b3928cda2a000c46144f3ea337c0b12::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
