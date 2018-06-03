<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitaae97774b5db8ac286e1301dede91e80
{
    public static $classMap = array (
        'AppConfig' => __DIR__ . '/../..' . '/AppConfig/AppConfig.php',
        'BusinessBase' => __DIR__ . '/../..' . '/Framework/BusinessBase.php',
        'Common' => __DIR__ . '/../..' . '/Framework/Common.php',
        'ControllerBase' => __DIR__ . '/../..' . '/Framework/ControllerBase.php',
        'Database' => __DIR__ . '/../..' . '/Framework/Database/Database.php',
        'Filter' => __DIR__ . '/../..' . '/Framework/Filters.php',
        'Firebase\\JWT\\BeforeValidException' => __DIR__ . '/../..' . '/Framework/Libraries/JWT/BeforeValidException.php',
        'Firebase\\JWT\\ExpiredException' => __DIR__ . '/../..' . '/Framework/Libraries/JWT/ExpiredException.php',
        'Firebase\\JWT\\JWT' => __DIR__ . '/../..' . '/Framework/Libraries/JWT/JWT.php',
        'Firebase\\JWT\\SignatureInvalidException' => __DIR__ . '/../..' . '/Framework/Libraries/JWT/SignatureInvalidException.php',
        'Identity' => __DIR__ . '/../..' . '/Framework/Indentity.php',
        'LookupType' => __DIR__ . '/../..' . '/AppCode/LookupType.php',
        'User' => __DIR__ . '/../..' . '/Business/User.php',
        'WebSocketServer' => __DIR__ . '/../..' . '/Framework/WebSocket/Websockets.php',
        'WebSocketUser' => __DIR__ . '/../..' . '/Framework/WebSocket/WebSocketUser.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitaae97774b5db8ac286e1301dede91e80::$classMap;

        }, null, ClassLoader::class);
    }
}
