<?php

namespace App\Http\Controllers;

use App\CheckList;
use App\ChecklistFile;
use App\Http\Resources\ChecklistResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ChecklistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(ChecklistResource::collection(CheckList::all()));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checklist = CheckList::create($request->except(['check_list','files']));
        return response()->json(new ChecklistResource($checklist));
    }

    //Add Checklist File
    public function addFile(Request $request)
    {
        $image = Input::file('checklist_file');
        $filename = $image->getClientOriginalName();
        $name = time() . '' . $filename;

        Storage::disk('public')->putFileAs(
            'files',
            $image,
            $name
        );
        $checklist = ChecklistFile::create(['filename' => $filename,
            'check_list_id' => CheckList::count() + 1,
            'mime' => $image->getClientMimeType(),
            'size' => $image->getClientSize(),
            'path' => $name
        ]);
        return response()->json($checklist);

    }
    //Update Checklist File
    public function updateFile($id)
    {
        $image = Input::file('checklist_file');
        $filename = $image->getClientOriginalName();
        $name = time() . '' . $filename;

        Storage::disk('public')->putFileAs(
            'files',
            $image,
            $name
        );
        $checklist = ChecklistFile::create(['filename' => $filename,
            'check_list_id' => $id,
            'mime' => $image->getClientMimeType(),
            'size' => $image->getClientSize(),
            'path' => $name
        ]);
       return response()->json($checklist);
    }
    //Remove Checklist File
    public function removeFile(Request $request)
    {
       $file = ChecklistFile::find($request->id);
        Storage::delete($file->path);
        $file->delete();
       return response()->json($file);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        CheckList::find($id)->update($request->except(['expiry_type','check_list','duration_type','files']));
        return response()->json(new ChecklistResource(CheckList::find($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        CheckList::destroy($id);
        return response()->json($id);
    }
}
