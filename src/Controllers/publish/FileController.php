<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Controller;
use Redirect;
use Schema;
use App\Models\File;
use App\Http\Requests\CreateFilesRequest;
use App\Http\Requests\UpdateFilesRequest;
use Illuminate\Http\Request;



class FileController extends Controller {

	/**
	 * Display a listing of files
	 *
     * @param Request $request
     *
     * @return \Illuminate\View\View
	 */
	public function index(Request $request)
    {
        $files = File::all();

		return view('Admin::file.index', compact('files'));
	}

	/**
	 * Show the form for creating a new files
	 *
     * @return \Illuminate\View\View
	 */
	public function create()
	{
	    
	    
	    return view('Admin::file.create');
	}

	/**
	 * Store a newly created files in storage.
	 *
     * @param CreateFilesRequest|Request $request
	 */
	public function store(CreateFilesRequest $request)
	{
	    
		File::create($request->all());

		return redirect()->route(config('admin.route').'.file.index');
	}

	/**
	 * Show the form for editing the specified files.
	 *
	 * @param  int  $id
     * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		$files = File::find($id);
	    
	    
		return view('Admin::file.edit', compact('files'));
	}

	/**
	 * Update the specified files in storage.
     * @param UpdateFilesRequest|Request $request
     *
	 * @param  int  $id
	 */
	public function update($id, UpdateFilesRequest $request)
	{
		$files = File::findOrFail($id);

        

		$files->update($request->all());

		return redirect()->route(config('admin.route').'.file.index');
	}

	/**
	 * Remove the specified files from storage.
	 *
	 * @param  int  $id
	 */
	public function destroy($id)
	{
		File::destroy($id);

		return redirect()->route(config('admin.route').'.file.index');
	}

    /**
     * Mass delete function from index page
     * @param Request $request
     *
     * @return mixed
     */
    public function massDelete(Request $request)
    {
        if ($request->get('toDelete') != 'mass') {
            $toDelete = json_decode($request->get('toDelete'));
            File::destroy($toDelete);
        } else {
            File::whereNotNull('id')->delete();
        }

        return redirect()->route(config('admin.route').'.file.index');
    }

}
