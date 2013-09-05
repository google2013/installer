<?php
namespace MyQEE\Installer;


use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{
    protected $locations = array
    (
        'myqee-core'    => 'core',
        'myqee-library' => 'libraries/{$vendor}/{$name}',
        'myqee-module'  => 'modules/{$name}',
        'myqee-project' => 'projects/{$name}',
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

        list($vendor, $name) = explode('/', strtolower($package->getName()), 2);

        if (substr($name, 0, 8)=='project-')
        {
            $name = substr($name, 8);
        }
        elseif (($packageType=='myqee-library' || $packageType=='myqee-module') && preg_match('#[^a-z0-9]|^[^a-z]#', $name))
        {
            throw new \InvalidArgumentException(sprintf('Package name "%s" is not supported', $name));
        }


        return realpath('./') . '/' . str_replace(array('{$vendor}', '{$name}'), array($vendor, $name), $this->locations[$packageType]);
    }

    protected function removeCode(PackageInterface $package)
    {
        $downloadPath = $this->getInstallPath($package);
        $this->downloadManager->remove($package, $downloadPath);
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::uninstall($repo, $package);

        $downloadPath = $this->getInstallPath($package);
        if (is_dir($downloadPath) && !glob($downloadPath.'/*')) {
            @rmdir($downloadPath);
        }
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
