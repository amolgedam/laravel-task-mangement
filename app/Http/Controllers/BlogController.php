<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth, Image;
use App\Models\Blog;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add()
    {
        return view('blog-form');
    }

    public function edit(Request $request, $id)
    {
        if(decrypt($id)){
            $id = decrypt($id);
            $blog = Blog::find($id);
            return view('blog-form', compact('blog'));
        }
        else{
            return redirect('/home');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = NULL;
        $success_message = "Blog created successfully!";
        $error_message = "Unable to create Blog!";
        if(isset($request->id)){
            $id = decrypt($request->id);
            $blog = Blog::find($id);
            $image = $blog->image; 
            $success_message = "Blog updated successfully!";
            $error_message = "Unable to update Blog!";
        }
        else{
            $blog = new Blog;
        }

        if($request->file('file')!=NULL){
            $image = $this->_uploadImage($request->file('file'));

            if(isset($request->id)){
                @unlink('storage/blog/'.$blog->image);
                @unlink('storage/blog/thumbnail/small_'.$blog->image);
                @unlink('storage/blog/thumbnail/medium_'.$blog->image);
                @unlink('storage/blog/thumbnail/large_'.$blog->image);
            }
        }

        $blog->user_id = Auth::id();
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->image = $image;
        
        if($blog->save()){
            return response()->json(['status'=>true, 'message'=>$success_message]);
        }
        else{
            return response()->json(['status'=>false, 'message'=>$error_message]);
        }
    }

    public function delete(Request $request, $id)
    {
        if(decrypt($id)){
            $id = decrypt($id);
            $blog = Blog::find($id);

            if(Blog::whereId($id)->delete()){
                @unlink('storage/blog/'.$blog->image);
                @unlink('storage/blog/thumbnail/small_'.$blog->image);
                @unlink('storage/blog/thumbnail/medium_'.$blog->image);
                @unlink('storage/blog/thumbnail/large_'.$blog->image);

                return response()->json(['status'=>false, 'message'=>"Blog deleted successfully!"]);
            }
            else{
                return response()->json(['status'=>false, 'message'=>"Unable to delete Blog!"]);
            }
        }
        else{
            return response()->json(['status'=>false, 'message'=>"Unable to delete Blog!"]);
        }
    }


    private function _uploadImage($file){
        //get filename with extension
        $filenamewithextension = $file->getClientOriginalName();

        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

        //get file extension
        $extension = $file->getClientOriginalExtension();

        $time = time();

        //filename to store
        $filenametostore = $filename.'_'.$time.'.'.$extension;

        //small thumbnail name
        $smallthumbnail = 'small_'.$filename.'_'.$time.'.'.$extension;

        //medium thumbnail name
        $mediumthumbnail = 'medium_'.$filename.'_'.$time.'.'.$extension;

        //large thumbnail name
        $largethumbnail = 'large_'.$filename.'_'.$time.'.'.$extension;

        //Upload File
        $file->storeAs('public/blog', $filenametostore);
        $file->storeAs('public/blog/thumbnail', $smallthumbnail);
        $file->storeAs('public/blog/thumbnail', $mediumthumbnail);
        $file->storeAs('public/blog/thumbnail', $largethumbnail);

        //create small thumbnail
        $smallthumbnailpath = public_path('storage/blog/thumbnail/'.$smallthumbnail);
        $this->_createThumbnail($smallthumbnailpath, 150, 93);

        //create medium thumbnail
        $mediumthumbnailpath = public_path('storage/blog/thumbnail/'.$mediumthumbnail);
        $this->_createThumbnail($mediumthumbnailpath, 300, 185);

        //create large thumbnail
        $largethumbnailpath = public_path('storage/blog/thumbnail/'.$largethumbnail);
        $this->_createThumbnail($largethumbnailpath, 550, 340);

        return $filenametostore ?? NULL;
    }

    private function _createThumbnail($path, $width, $height){
        $img = Image::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
    }
    

    


}
