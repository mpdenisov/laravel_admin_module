<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Controller;

use function Couchbase\defaultDecoder;
use Illuminate\Support\Facades\Storage;
use Redirect;
use Rhinoda\Admin\Services\FileService;
use Schema;
use Illuminate\Http\Request;
use function Sodium\add;


class FileController extends Controller
{

    /**
     * Display a listing of files
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $files = scandir(public_path('admin'));
        unset($files[0]);
        unset($files[1]);
        $files['backpath']=null;
        $path = 'general_folder';
        $name = '';

        return view('Admin::file.index', compact('files', 'name', 'path'));
    }

    public function folder(Request $request, $name)
    {

        $pathroute = $name;
        $dir = explode("*", $name);
        $name = FileService::nameCreator($dir);
        unset($dir[count($dir) - 1]);
        $path = FileService::pathCreator($dir);
        $files = scandir(public_path('admin' . $path . '/' . $name));
        $path = $pathroute;
        unset($files[1]);

        if (count($dir) == 0) {

            return redirect(route('files'));
        }
        $files['backpath']=FileService::parentRouteCreator($dir);

        return view('Admin::file.index', compact(['files', 'name', 'path']));

    }

    public function createFolder(Request $request, $name)
    {
        $pathroute = $name;
        $dir = explode("*", $name);
        $name = FileService::nameCreator($dir);
        unset($dir[count($dir) - 1]);
        $path = FileService::pathCreator($dir);
        mkdir(public_path('admin' . $path . '/' . $name . '/'.$request->name), 0700);
        return redirect(route('folder', $folder = $pathroute));
    }

    public function uploadFile(Request $request, $name)
    {
        $file = $request->file('file');
        $dir = explode("*", $name);
        $name = FileService::nameCreator($dir);
        unset($dir[count($dir) - 1]);
        $path = FileService::pathCreator($dir);
        $upload_success = $request->file('file')->move(public_path('/admin/' . $path . '/' . $name), $file->getClientOriginalName());
        if ($upload_success) {
            return response()->json($upload_success, 200);

        } // Else, return error 400
        else {

            return response()->json('error', 400);
        }
    }

    public function deleteFile($name)
    {


        $dir = explode("*", $name);
        $name = FileService::nameCreator($dir);
        unset($dir[count($dir) - 1]);
        $path = FileService::pathCreator($dir);
        $pathroute = FileService::parentRouteCreator($dir);
        FileService::deleteItem($path, $name);

        return redirect(route('folder', $folder = $pathroute));
    }

    public function edit($name)
    {
        if (in_array('*', str_split($name))) {

            $dir = explode("*", $name);
            $item = end($dir);

        }

        return view('Admin::file.edit', compact([$name => 'name', 'item']));
    }

    public function rename($name, Request $request)
    {

        $extention = \File::extension($name);
        $newname = $request->newname;
        $dir = explode("*", $name);
        $name = FileService::nameCreator($dir);
        unset($dir[count($dir) - 1]);
        $path = FileService::pathCreator($dir);
        $parentroute = FileService::parentRouteCreator($dir);
        rename(public_path('/admin' . $path . '/' . $name), public_path('/admin' . $path . '/' . $newname . '.' . $extention));

        return redirect(route('folder', $folder = $parentroute));
    }


    public function move(Request $request, $name)
    {
        $newpath = $request->newpath;
        $dir = explode("*", $name);
        $name = FileService::nameCreator($dir);
        unset($dir[count($dir) - 1]);
        $path = FileService::pathCreator($dir);
        $dir = explode("/", $newpath);
        $parentroute = FileService::parentRouteCreator($dir);

        rename(public_path('/admin' . $path . '/' . $name), public_path($newpath . '/' . $name));
        return redirect(route('folder', $folder = $parentroute));
    }
}
