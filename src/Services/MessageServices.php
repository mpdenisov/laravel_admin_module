<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 07.08.18
 * Time: 13:24
 */

namespace Rhinoda\Admin\Services;


use Illuminate\Support\Facades\Schema;
use Rhinoda\Admin\Models\Menu;

class MessageServices
{
    public static function delete()
    {
        $path = 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Admin';

        unlink(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'message' .DIRECTORY_SEPARATOR. 'create.blade.php'));
        unlink(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'message' .DIRECTORY_SEPARATOR. 'edit.blade.php'));
        unlink(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'message' .DIRECTORY_SEPARATOR. 'index.blade.php'));
        unlink(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'message' .DIRECTORY_SEPARATOR. 'message.blade.php'));
        rmdir(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'message'));
        unlink(app_path('Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR . 'MessageController.php'));
        unlink(app_path($path . DIRECTORY_SEPARATOR . 'Messenger.php'));
        unlink(app_path('Models' . DIRECTORY_SEPARATOR . 'Message.php'));
        Schema::drop('messages');
        $files = scandir(database_path('migrations'));
        foreach ($files as $item) {
            if (preg_match('/create_messages_table.php$/',$item)){
                unlink(database_path('migrations' . DIRECTORY_SEPARATOR . $item));
            }
        }

        Menu::where('singular_name','message')->delete();
    }
}