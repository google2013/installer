<?php
namespace MyQEE\Installer;

use Composer\Script\Event;

class InstallerScript
{
    public static function postAutoloadDump(Event $event)
    {
        // if vendor only have composer/, myqee/, autoload.php then remove vendor dir,use myqee autoload
        $dir = realpath('vendor/') .'/';
        $f   = $dir.'autoload-for-myqee.php';
        foreach (glob($dir . '*') as $file)
        {
            if ($file[0]=='.')continue;
            $file_name = basename($file);
            if (!in_array($file_name, array('composer', 'myqee', 'autoload.php', 'autoload-for-myqee.php')))
            {
                if (!is_link($f))
                {
                    @symlink('autoload.php', $f);
                }
                return true;
            }
        }

        if (is_link($f))
        {
            unlink($f);
        }
    }
}