<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToDoEngineer;

class ToDoEngineerController extends Controller
{
   public function index(Request $request)
{
                     $user = \Auth::user();
        $webAccess = is_array($user->web_access)
        ? $user->web_access
        : json_decode($user->web_access ?? '[]', true);

    // Permission check for both company & other roles
    $hasPermission = (
        ($user->type === 'company' && in_array('manage todo list', $webAccess)) ||
        ($user->type !== 'company' && $user->can('manage todo list'))
    );

    if ($hasPermission) {
        $user = \Auth::user();

        $query = ToDoEngineer::where('project_id', $user->project_assign_id)->with('engineer');

        if ($request->has('engineer_id') && !empty($request->engineer_id)) {
            $query->where('engineer_id', $request->engineer_id);
        }

        $todoeng = $query->get();

        // Load engineers for the filter dropdown
        $engineers = \App\Models\User::where('type', 'app user')
            ->whereJsonContains('project_id', (string)$user->project_assign_id)
            ->pluck('name', 'id');

        return view('todo_engineer.index', compact('todoeng', 'engineers'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

protected $notificationService;

public function __construct(\App\Notifications\EngineerNotificationService $notificationService)
{
    $this->notificationService = $notificationService;
}

protected function sendNotification($title, $message, $engineerIds, $toDoEngineerId = null)
{
    // Retrieve FCM tokens for the specified drivers
    $tokens = \App\Models\User::whereIn('id', $engineerIds)->pluck('engineer_token')->toArray();

    $this->notificationService->send([
        'title' => $title,
        'message' => $message,
        'tokens' => $tokens, // Pass the FCM tokens
        'target' => 'mobile_app', // Customize if needed
    ]);
 // Save notification to the database
    $this->saveNotificationToDatabase($title, $message, $engineerIds, $toDoEngineerId);
}

protected function saveNotificationToDatabase($title, $message, $engineerIds, $toDoEngineerId = null)
{
     // Get project_id from to_do_engineers
    $projectId = null;
    if ($toDoEngineerId) {
        $projectId = \App\Models\ToDoEngineer::find($toDoEngineerId)->project_id ?? null;
    }
foreach ($engineerIds as $engineerId) {
    \App\Models\EngineerNotification::create([
        'engineer_id' => $engineerId,
        'title' => $title,
        'message' => $message,
        'key' => 1,
        'status' => 'Pending',
        'to_do_engineer_id' => $toDoEngineerId,
            'project_id' => $projectId,
    ]);
}
}


        public function create()
    {
        if (\Auth::user()->can('create todo list')) {
            $user = \Auth::user();
            $projects = \App\Models\Project::where('created_by', \Auth::id())->where('id', $user->project_assign_id)->pluck('project_name', 'id');
              // Fetch engineers with type = 'app user'
            $engineers = \App\Models\User::where('type', 'app user')
            ->whereJsonContains('project_id', (string)$user->project_assign_id)
            ->pluck('name', 'id');

        return view('todo_engineer.create', compact('projects', 'engineers'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


public function store(Request $request)
{
    if (\Auth::user()->can('create todo list')) {

        // Validate the request without project_id input
        $request->validate([
            'name' => 'required|string|max:255',
            'engineer_id' => 'required|exists:users,id',
        ]);

        // Get the project_id from the authenticated user's project_assign_id
        $project_id = \Auth::user()->project_assign_id; // assuming the project_assign_id exists on the User model

        if (!$project_id) {
            return redirect()->back()->with('error', __('Please create a project first before adding a ToDo List.'));
        }

        // Create a new drawing record
        $todoeng = new \App\Models\ToDoEngineer();
        $todoeng->project_id = $project_id; // Automatically assign project_id
        $todoeng->name = $request->name;
         $todoeng->engineer_id = $request->engineer_id;
        $todoeng->created_by = \Auth::user()->id;
        $todoeng->created_user = "Admin"; 

        // Save the drawing
        $todoeng->save();

        // Redirect with a success message
        return redirect()->route('to-do-list.index')->with('success', __('ToDo List Created Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

 public function show(ToDoEngineer $todoeng)
    {
        if (\Auth::user()->can('show todo list')) {
          
            return view('todo_engineer.show', compact('todoeng'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function todoTaskindex(Request $request, $id)
{
    if (\Auth::user()->can('show todo list')) {
        $todoengtask = \App\Models\ToDoEngineerTask::where('to_do_engineer_id', $id)->orderBy('created_at', 'desc')->get();

        return view('todo_engineer.task.index', compact('todoengtask', 'id'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function todoTaskcreate($todoengId)
{
    if (\Auth::user()->can('create todo list')) {
        $todoeng = \App\Models\ToDoEngineer::findOrFail($todoengId);

        return view('todo_engineer.task.create', compact('todoeng'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function todoTaskstore(Request $request)
{
    if (\Auth::user()->can('create todo list')) {

        // Validate the request
        $request->validate([
            'to_do_engineer_id' => 'required|exists:to_do_engineers,id',
            'task_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'files.*' => 'nullable|file|max:5120',
        ]);

        // Save task
        $todoengtask = new \App\Models\ToDoEngineerTask();
        $todoengtask->to_do_engineer_id = $request->to_do_engineer_id;
        $todoengtask->task_title = $request->task_title;
        $todoengtask->description = $request->description;
        $todoengtask->due_date = $request->due_date;
        $todoengtask->date = now(); // Save current date
         $todoengtask->created_by = \Auth::user()->id;
        $todoengtask->created_user = "Admin"; 

        // Set status based on due_date
        $todoengtask->status = $request->due_date ? 'Pending' : '-';

        $todoengtask->save();

        // Save files if any
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $filepath = $file->storeAs('todo-tasks', $filename, 'local');

                \App\Models\ToDoEngineerTaskFiles::create([
                    'task_id' => $todoengtask->id,
                    'file_path' => $filename,
                ]);
            }
        }
        
      $engineerId = \App\Models\ToDoEngineer::find($request->to_do_engineer_id)->engineer_id ?? null;

if ($engineerId) {
    $message = "Task- " . $request->task_title . "\n";

    if (!empty($request->description)) {
        $message .= "Description- " . $request->description . "\n";
    }

    if (!empty($request->due_date)) {
        $message .= "Due Date- " . date('d-m-Y', strtotime($request->due_date)) . "\n";
    }

    $this->sendNotification(
        'You have new task Assigned',
        $message,
        [$engineerId],
         $todoengtask->to_do_engineer_id
    );
}



        return redirect()->back()->with('success', __('ToDo Task Created Successfully.'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}

public function todoTaskshow(\App\Models\ToDoEngineerTask $todoengtask)
{
    if (\Auth::user()->can('show todo list')) {
        $files = $todoengtask->attachment ?? collect(); // Ensure it's never null
        return view('todo_engineer.task.show', compact('todoengtask', 'files'));
    } else {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }
}




}
