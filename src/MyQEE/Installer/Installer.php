<?php
namespace MyQEE\Installer;

use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{
    protected $locations = array
    (
        'database' => '{$vendor}/database/src/{$extra.dir}',
        'cache'    => '{$vendor}/cache/src/{$extra.dir}',
        'storage'  => '{$vendor}/storage/src/{$extra.dir}',
        'session'  => '{$vendor}/storage/src/{$extra.dir}',
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
        $extra = $package->getExtra();

        $path = str_replace(['{$vendor}', '{$name}', '{$extra.dir}'], [$vendor, $name, $extra['dir']], $this->locations[$packageType]);


        return $this->vendorDir .'/'. $path;
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

        while (true) 
        {
            if (is_dir($downloadPath)) 
            {
                if (!glob($downloadPath.'/*')) 
                {
                    if (false === @rmdir($downloadPath))
                    {
                        break;
                    }
                } 
                else 
                {
                    break;
                }
            } 
            else 
            {
                $downloadPath = dirname($downloadPath);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return isset($this->locations[$packageType]);
    }
}
