<?php
namespace MyQEE\Installer;


use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{
    protected $locations = array
    (
        'myqee-core'    => 'core/',
        'myqee-library' => 'libraries/{$name}/',
    );

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $packageType = $package->getType();

        if (!isset($this->locations[$packageType]))
        {
            throw new \InvalidArgumentException(sprintf('Package type "%s" is not supported', $packageType));
        }

        return str_replace('{$name}', strtolower($package->getName()), $this->locations[$packageType]);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return isset($this->locations[$packageType]);
    }

    /**
     * For an installer to override to modify the vars per installer.
     *
     * @param  array $vars
     * @return array
     */
    public function inflectPackageVars($vars)
    {
        return $vars;
    }
}
