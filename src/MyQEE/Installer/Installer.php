<?php
namespace MyQEE\Installer;

use Composer\Installers\BaseInstaller;

class Installer extends BaseInstaller
{
    protected $locations = array
    (
        'core'    => 'core/',
        'library' => 'libraries/{$name}/',
    );
}
