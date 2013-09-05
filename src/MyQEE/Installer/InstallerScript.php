<?php
namespace MyQEE\Installer;

use Composer\Script\Event;

class InstallerScript
{
    public static function clean(Event $event)
    {
        $dir = realpath('vendor/') .'/';
        foreach (glob($dir . '*') as $file)
        {
            if ($file[0]=='.')continue;
            $file_name = basename($file);
            if (!in_array($file_name, array('composer', 'myqee', 'autoload.php')))
            {
                return true;
            }
        }

        $st = self::remove_dir();

        echo "clean composer files " . ($st?'success':'fail') ."\n";
    }

    private static function remove_dir($dir)
    {
        if (!is_dir($dir))
        {
            return true;
        }

        $realpath = realpath($dir);

        if (!$realpath)
        {
            return true;
        }

        $handle = opendir($dir);
        while (($file = readdir($handle)) !== false)
        {
            if ($file != '.' && $file != '..')
            {
                $tmp_dir = $dir . '/' . $file;
                is_dir($tmp_dir) ? self::remove_dir($tmp_dir) : @unlink($tmp_dir);
            }
        }

        closedir($handle);

        return @rmdir($dir);
    }
}