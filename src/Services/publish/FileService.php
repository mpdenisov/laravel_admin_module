<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 19.07.18
 * Time: 14:10
 */

namespace App\Modules\Admin\Services;


class FileService
{
    public static function pathcreator($dir)
    {

        $path = '';
        foreach ($dir as $item) {
            if ($item == 'general_folder') {
            } else {
                $path .= '/' . $item;
            }
        }
        return $path;
    }

    public static function parentroutecreator($dir)
    {


        $path = '';


        for ($i = 0; $i < count($dir); $i++) {
            if ($dir[$i]=='admin'){
                $dir[$i]='general_folder';
            }
            if (($i == count($dir) - 1) && ($dir[$i] != '')) {
                $path .= $dir[$i];
            } elseif ($dir[$i] == '') {

            } elseif ($i != count($dir) - 1 && $dir[$i + 1] == '') {
                $path .= $dir[$i];
            } else {
                $path .= $dir[$i] . '*';
            }
        }


        return $path;
    }

    public static function deleteitem($path, $name)
    {
        if (is_dir(public_path('/admin' . $path . '/' . $name))) {

            rmdir((public_path('/admin' . $path . '/' . $name)));
        } else {
            unlink((public_path('/admin' . $path . '/' . $name)));
        }
    }

    public static function namecreator($dir)
    {
        if (count($dir) > 1) {
            $name = end($dir);
        } else {
            $name = '';
        }
        return $name;
    }

    public static function backpath($dir)
    {
        $path = '';


        for ($i = 0; $i < count($dir); $i++) {
            if (($i == count($dir) - 1) && ($dir[$i] != '')) {
                $path .= $dir[$i];
            } elseif ($dir[$i] == '') {

            } elseif ($i != count($dir) - 1 && $dir[$i + 1] == '') {
                $path .= $dir[$i];
            } else {
                $path .= $dir[$i] . '*';
            }
        }


        return $path;
    }
}