<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\User;
use App\Models\Task;

class TaskController extends Controller
{
      public function index(Request $request){

        
        $task_list = DB::table('tasks')
        // ->where('is_deleted', 0)
        ->where(function($query) use ($request){
            $query->where('title', 'LIKE', '%' . $request->term . '%')
                ->orWhere('description', 'LIKE', '%' . $request->term . '%');
        })
        ->when($request->status, function ($query) use ($request) {
            $query->where('is_completed', $request->status);
        })
        ->when(!$request->status, function ($query) {
            $query->where('is_deleted', 0);
        })
        ->orderBy('position', 'asc')
        ->get();


        if($request->pagination == 1){
          return view('tasks.task_list',['task_list'=>$task_list]);
        }else{
           // dd('index');
          return view('tasks.index',['task_list'=>$task_list]);
        }
    }

    public function add(Request $request){
        if($_POST){
            DB::table('tasks')->insert([
                'title' => $request->title_name,
                'description' => $request->description,
                'user_id' => Auth::user()->id,
                'is_deleted'=>0
            ]);
      
        }
        return view('tasks.add_edit_task');
    }
    public function edit(Request $request,$id=null){

        if($_POST){
            DB::table('tasks')->where('id',$request->id)->update([
                'title' => $request->title_name,
                'description' => $request->description,
                'user_id' => Auth::user()->id,
            ]);
      
        }

        $task_edit_rec = DB::table('tasks')->where('id',$id)->first();

        return view('tasks.add_edit_task',['task_edit_rec'=>$task_edit_rec]);
    }

    public function delete(Request $request){

     DB::table('tasks')->where('id',$request->id)->update([
        'is_deleted' => 1
     ]);


    }
    public function task_completion(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:tasks,id',
            'is_complete' => 'required',
        ]);

         DB::table('tasks')->where('id',$request->id)->update([
            'is_completed' => $request->is_complete
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Task completion status updated successfully.'
        ]);
    }
    public function reorder(Request $request)
    {
        $order = $request->order;

        foreach ($order as $index => $id) {
           DB::table('tasks')->where('id', $id)->update(['position' => $index + 1]);
        }

        return response()->json(['status' => 'success']);
    }

}
