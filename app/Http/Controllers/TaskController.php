<?php

namespace App\Http\Controllers;
use App\Models\Task;
use File;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {

    try{
    $task = Task::all();

    if($task){
    return response()->json([

        "tasks"=>$task
    ]);
    }
    return response()->json([
    'item'=>"empty"
    ],404);

    } 
    catch(\Exception $e){
    return response()->json([
        'message'=>'internal error'
    ],500);

}
    }

public function store(Request $request)
    {
        //
       // dd($request->image);

        $task = new Task();
        $task->fill($request->all());//because we used fillable

     
        if($image=$request->file('image'))
        {
          $image=$request->image;
            $image->store('public/images/');
            $task->image = $image->hashName();
        }
        if($task->save()){ //returns a boolean
            return response()->json([
                'data'=> $task
            ],200);
        }
        else
        {
            return response()->json([
                'task'=>'task could not be added' 
            ],500);
        }
    }
    
    
    public function show( $id)
    {
        //
        $task = Task::find($id);
        if($task)
        {
            return response()->json([
                'data'=> $task
            ],200);
        }
        return response()->json([
            'task'=>'task could not be found' 
        ],500);
    }





public function destroy($id)
    {
        $item = Task::find($id);
        if($item->delete()){ //returns a boolean
            if($this->imageDelete($item->image,$item->category_id)){
                var_dump('got deleted');
            }
            else
            {
                var_dump('didnt delete');
            }
            return response()->json([
                'item'=> "good for you"
            ],200);
        }
        else
        {
            return response()->json([
                'item'=>'item could not be deleted' 
            ],500);
        }
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if($task){
            $task->update($request->all());//because we used fillable

            //dd($image=$request->file('image'));
            if($image=$request->file('image'))
            {
               // dd($this->imageDelete($task->image));
                if($this->imageDelete($task->image)){
                    
                    $image=$request->image;
                    $image->store('public/images/');                    
                    $task->image = $image->hashName();
                }else{


                    $image=$request->image;
                    $image->store('public/images/');                    
                    $task->image = $image->hashName();
                }
              
                       
            }
            if($task->save()){ //returns a boolean
                return response()->json([
                    'data'=> $task
                ],200);
            }
            else
            {
                return response()->json([
                    'item'=>'item could not be updated' 
                ],500);
            }
        }
        return response()->json([
            'item'=>'item could not be found' 
        ],500);
    }




    public function imageDelete($oldImage)
    {

      //  dd(File::exists(public_path('storage/images/'. $oldImage)));
        
        if(File::exists(public_path('storage/images/'. $oldImage)))
        {
            File::delete(public_path('storage/images/'. $oldImage));
            return true;
        }
        else
        {
            return false;
        }
    }



}