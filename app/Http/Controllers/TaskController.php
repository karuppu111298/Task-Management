<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;

class TaskController extends Controller
{
      public function index(Request $request){

        
        $task_list = DB::table('tasks')
        // ->where('is_deleted', 0)
        ->leftJoin('users', 'tasks.user_id', '=', 'users.id')
        ->select('tasks.*', 'users.name as user_name')
        ->where(function($query) use ($request){
            $term = strtolower($request->term);
            $query->whereRaw('LOWER(title) LIKE ?', ["%{$term}%"])
            ->orWhereRaw('LOWER(description) LIKE ?', ["%{$term}%"])
            ->orWhereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
        })
        ->when(isset($request->status), function ($query) use ($request) {
            $query->where('is_completed', $request->status);
        })
        ->when(!isset($request->status), function ($query) {
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
             $validator = Validator::make($request->all(), [
            'title_name'  => 'required|string|max:255',
            'description' => 'required|string|min:5',
        ], [
            'title_name.required'  => 'Task title is required.',
            'description.required' => 'Please enter description.',
            'description.min'      => 'Description must be at least 5 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 201);
        }

        try {
            DB::beginTransaction();

            DB::table('tasks')->insert([
                'title'       => $request->title_name,
                'description' => $request->description,
                'user_id'     => Auth::id(),
                'is_deleted'  => 0
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Task added successfully ✅'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong ❌: ' . $e->getMessage()
            ], 500);
        }
      
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
