<?php
namespace MyQEE\Installer;


use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{
    protected $locations = array
    (
        'myqee-core'    => 'core/',
        'myqee-library' => 'libraries/{$vendor}/{$name}/',
    );

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $packageType = $this->package->getType();

        if (!isset($this->locations[$packageType]))
        {
            throw new \InvalidArgumentException(sprintf('Package type "%s" is not supported', $packageType));
        }

        $availableVars = $this->inflectPackageVars(compact('name', 'vendor', 'type'));

        $extra = $package->getExtra();
        if (!empty($extra['installer-name']))
        {
            $availableVars['name'] = $extra['installer-name'];
        }

        $path = $this->locations[$packageType];
        foreach (array('name', 'vendor', 'type') as $item)
        {
            $path = str_replace('{$'.$item.'}', $availableVars[$item], $page);
        }

        return $path;
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
