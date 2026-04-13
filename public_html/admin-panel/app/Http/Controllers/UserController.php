<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use App\Models\Employee;
use App\Models\ExperienceCertificate;
use App\Models\GenerateOfferLetter;
use App\Models\JoiningLetter;
use App\Models\LoginDetail;
use App\Models\NOC;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserToDo;
use App\Models\Utility;
use App\Imports\UserImport;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lab404\Impersonate\Impersonate;
use Spatie\Permission\Models\Role;
use App\Models\ReferralTransaction;
use App\Models\ReferralSetting;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{

    public function index()
    {
        User::defaultEmail();

        $user = \Auth::user();
        if (\Auth::user()->can('manage user')) {
            if (\Auth::user()->type == 'super admin') {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'company')->with(['currentPlan'])->get();
            } else {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', 'app user')->where('type', '!=', 'client')->with(['currentPlan'])->get();
            }

            return view('user.index')->with('users', $users);
        } else {
            return redirect()->back();
        }

    }

    public function create()
    {

        $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();
        $user = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'client')->get()->pluck('name', 'id');
        if (\Auth::user()->can('create user')) {
            return view('user.create', compact('roles', 'customFields','user'));
        } else {
            return redirect()->back();
        }
    }

  public function store(Request $request)
    {

        if (\Auth::user()->can('create user')) {
            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->where('created_by', '=', \Auth::user()->creatorId())->first();
            $objUser = \Auth::user()->creatorId();

            if (\Auth::user()->type == 'super admin') {
                $validator = \Validator::make(
                    $request->all(), [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users',
                         'profile_image' => 'required', // allow image/pdf up to 2MB
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $enableLogin = 0;
                if (!empty($request->password_switch) && $request->password_switch == 'on') {
                    $enableLogin = 1;
                    $validator = \Validator::make(
                        $request->all(), ['password' => 'required|min:6']
                    );

                    if ($validator->fails()) {
                        return redirect()->back()->with('error', $validator->errors()->first());
                    }
                }
                $userpassword = $request->input('password');
                $settings = Utility::settings();

                do {
                    $code = rand(100000, 999999);
                } while (User::where('referral_code', $code)->exists());

                $user = new User();
                $user['name'] = $request->name;
                $user['email'] = $request->email;
                $psw = $request->password;
                $user['password'] = !empty($userpassword)?\Hash::make($userpassword) : null;
                $user['type'] = 'company';
                $user['default_pipeline'] = 1;
                $user['plan'] = 1;
                $user['lang'] = !empty($default_language) ? $default_language->value : 'en';
                $user['referral_code'] = $code;
                $user['created_by'] = \Auth::user()->creatorId();
                $user['plan'] = Plan::first()->id;
                $user['company_name'] = $request->company_name;
                $user['address'] = $request->address;
                $user['gst_no'] = $request->gst_no;
                $user['contact_no'] = $request->contact_no;
                $user['admin_engineer_access'] = json_encode($request->input('access')); // store access checkboxes
                $user['web_access'] = json_encode($request->input('web_access'));


        if ($request->hasFile('profile_image')) {
            $fileName = time() . '_' . $request->file('profile_image')->getClientOriginalName();
            $path = $request->file('profile_image')->storeAs('uploads/avatar', $fileName, 'local'); // Goes to storage/app/public/Working-Drawings
             $user['avatar'] = $fileName;
        }


                if ($settings['email_verification'] == 'on') {

                    $user['email_verified_at'] = null;
                } else {
                    $user['email_verified_at'] = date('Y-m-d H:i:s');
                }
                $user['is_enable_login'] = $enableLogin;

                $user->save();
                $role_r = Role::findByName('company');
                $user->assignRole($role_r);
                //                $user->userDefaultData();
                $user->userDefaultDataRegister($user->id);
                $user->userWarehouseRegister($user->id);

                //default bank account for new company
                $user->userDefaultBankAccount($user->id);

                Utility::chartOfAccountTypeData($user->id);
                // Utility::chartOfAccountData($user);
                // default chart of account for new company
                Utility::chartOfAccountData1($user->id);

                Utility::pipeline_lead_deal_Stage($user->id);
                Utility::project_task_stages($user->id);
                Utility::labels($user->id);
                Utility::sources($user->id);
                Utility::jobStage($user->id);
                GenerateOfferLetter::defaultOfferLetterRegister($user->id);
                ExperienceCertificate::defaultExpCertificatRegister($user->id);
                JoiningLetter::defaultJoiningLetterRegister($user->id);
                NOC::defaultNocCertificateRegister($user->id);
            } else {
                $validator = \Validator::make(
                    $request->all(), [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users',
                        //    'email' => 'required|email|unique:users,email,NULL,id,created_by,' . $objUser,
                        // 'role' => 'required',
                        'access' => 'required|array', // NEW: validate access checkboxes
                        'project_id' => 'required|array',

                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                // $enableLogin = 0;
                // if (!empty($request->password_switch) && $request->password_switch == 'on') {
                //     $enableLogin = 1;
                //     $validator = \Validator::make(
                //         $request->all(), ['password' => 'required|min:6']
                //     );

                //     if ($validator->fails()) {
                //         return redirect()->back()->with('error', $validator->errors()->first());
                //     }
                // }

                $userpassword = $request->input('password');

// Validate password (always required)
                    $validator = \Validator::make(
    $request->all(),
    ['password' => 'required|min:6']
                    );

                    if ($validator->fails()) {
                        return redirect()->back()->with('error', $validator->errors()->first());
                    }

                $objUser = User::find($objUser);
                $user = User::find(\Auth::user()->created_by);
                $total_user = $objUser->countUsers();
                $plan = Plan::find($objUser->plan);
                $userpassword = $request->input('password');

                //  if ($enableLogin == 0) {
                //      $defaultPrefix = substr($request->name, 0, 3);
                //      $userpassword = $defaultPrefix . date('Y');
                //   }

                if ($total_user < $plan->max_users || $plan->max_users == -1) {
                    // $role_r = Role::findById($request->role);
                    $psw = $request->password;
                    $request['password'] = \Hash::make($userpassword); // for normal users

                    $request['type'] = 'app user';
                    $request['lang'] = !empty($default_language) ? $default_language->value : 'en';
                    $request['created_by'] = \Auth::user()->creatorId();
                    $request['email_verified_at'] = date('Y-m-d H:i:s');
                    $request['is_enable_login'] = 1;
                                        $request['user_access'] = json_encode($request->input('access'));
                    $request['project_id'] = json_encode($request->input('project_id'));
                   $request['client_visibility'] = $request->has('client_visibility') ? 1 : 0;

                    $user = User::create($request->all());
                    // $user->assignRole($role_r);
                    if ($request['type'] != 'client') {
                        \App\Models\Utility::employeeDetails($user->id, \Auth::user()->creatorId());
                    }

                } else {
                    return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                }
            }

              // ---------------- Send Email With Laravel Mailable ----------------
                 if (!empty($userpassword) && $user->type == 'app user') {
                  \Mail::to($user->email)->send(new \App\Mail\EngineerCreatedMail($user, $userpassword));
                 }

            // // Send Email
            // $setings = Utility::settings();
            // if ($setings['new_user'] == 1) {

            //     $user->password = $userpassword;
            //     // $user->type = $role_r->name;
            //     $user->userDefaultDataRegister($user->id);

            //     $userArr = [
            //         'email' => $user->email,
            //         'password' => $user->password,
            //     ];
            //     $resp = Utility::sendEmailTemplate('new_user', [$user->id => $user->email], $userArr);

            //     if (\Auth::user()->type == 'super admin') {
            //         return redirect()->route('users.index')->with('success', __('Company successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            //     } else {
            //         return redirect()->route('users.index')->with('success', __('User successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

            //     }
            // }
            if (\Auth::user()->type == 'super admin') {
                return redirect()->route('users.index')->with('success', __('Company successfully created.'));
            } else {
                return redirect()->route('users.index')->with('success', __('User successfully created.'));

            }

        } else {
            return redirect()->back();
        }

    }

        public function importFile()
    {
        if (\Auth::user()->can('create user')) {
            return view('user.import');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

public function import(Request $request)
{
    $rules = [
        'file' => 'required|mimes:xlsx,xls,csv',
    ];

    $validator = \Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return redirect()->back()->with('error', $validator->errors()->first());
    }

    $authUser = \Auth::user();
    $default_language = DB::table('settings')
        ->select('value')
        ->where('name', 'default_language')
        ->where('created_by', '=', $authUser->creatorId())
        ->first();

    $rows = (new UserImport)->toArray($request->file('file'))[0] ?? [];
    $totalRecords = max(0, count($rows) - 1);
    $skippedRows = [];
    $successCount = 0;

    // ✅ Allowed Access from DB (decoded + trimmed)
    $allowedAccessOptions = json_decode(auth()->user()->admin_engineer_access ?? '[]', true);
$allowedAccessOptions = array_map(function($v) {
    return trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $v));
}, $allowedAccessOptions);
$allowedLower = array_map('mb_strtolower', $allowedAccessOptions);


    foreach ($rows as $key => $row) {
        if ($key === 0) continue; // skip header

        $rowNumber = $key + 1;
        $name = trim($row[0] ?? '');
        $email = trim($row[1] ?? '');
        $password = trim($row[2] ?? '');
        $accessRaw = trim($row[3] ?? '');
        $projectNamesRaw = trim($row[4] ?? '');

        $access = array_map('trim', array_filter(explode(',', $accessRaw)));
        $accessLower = array_map('strtolower', $access);

//         \Log::info('Row '.$rowNumber.' Excel Access: ', $access);
// \Log::info('Allowed DB Access: ', $allowedAccessOptions);

        $projectNames = array_map('trim', array_filter(explode(',', $projectNamesRaw)));

        // ------------------ Required field validation ------------------
        if (empty($name) || empty($email) || empty($password)) {
            $skippedRows[] = [
                'row' => $rowNumber,
                'name' => $name,
                'project' => $projectNamesRaw,
                'reason' => 'Missing required fields (name / email / password).'
            ];
            continue;
        }

        // ------------------ Email validation ------------------
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $skippedRows[] = [
                'row' => $rowNumber,
                'name' => $name,
                'project' => $projectNamesRaw,
                'reason' => "Invalid email format ($email)."
            ];
            continue;
        }

        // ------------------ Duplicate email ------------------
        if (User::where('email', $email)->exists()) {
            $skippedRows[] = [
                'row' => $rowNumber,
                'name' => $name,
                'project' => $projectNamesRaw,
                'reason' => "Email already exists ($email)."
            ];
            continue;
        }

        // ------------------ Plan limit check ------------------
        $creator = User::find($authUser->creatorId());
        $plan = $creator ? Plan::find($creator->plan) : null;
        $total_user = $creator ? $creator->countUsers() : 0;

        if ($plan && $plan->max_users != -1 && $total_user >= $plan->max_users) {
            $skippedRows[] = [
                'row' => $rowNumber,
                'name' => $name,
                'project' => $projectNamesRaw,
                'reason' => "User limit exceeded for your plan."
            ];
            continue;
        }

        // ------------------ Access validation ------------------
        $invalidAccess = [];
        foreach ($accessLower as $i => $val) {
            if (!in_array($val, $allowedLower)) {
                $invalidAccess[] = $access[$i]; // show original Excel text
            }
        }

        if (!empty($invalidAccess)) {
            $skippedRows[] = [
                'row' => $rowNumber,
                'name' => $name,
                'project' => $projectNamesRaw,
                'reason' => 'Invalid Admin Access permission(s): ' . implode(', ', $invalidAccess)
            ];
            continue;
        }

        // ------------------ Project validation ------------------
        $projectIDs = [];
        $notFound = [];
        if (!empty($projectNames)) {
            foreach ($projectNames as $pName) {
                $project = \App\Models\Project::whereRaw('LOWER(project_name) = ?', [mb_strtolower($pName)])
                    ->where('created_by', $authUser->creatorId())
                    ->first();

                if ($project) {
                    $projectIDs[] = (string)$project->id; // store as ["6"]
                } else {
                    $notFound[] = $pName;
                }
            }

            if (!empty($notFound)) {
                $skippedRows[] = [
                    'row' => $rowNumber,
                    'name' => $name,
                    'project' => $projectNamesRaw,
                    'reason' => 'Project(s) not found: ' . implode(', ', $notFound)
                ];
                continue;
            }
        }

        // ------------------ Create User ------------------
        try {
            $newUser = new User();
            $newUser->name = $name;
            $newUser->email = $email;
            $newUser->password = \Hash::make($password);
            $newUser->type = 'app user';
            $newUser->lang = !empty($default_language) ? $default_language->value : 'en';
            $newUser->created_by = $authUser->creatorId();
            $newUser->email_verified_at = now();
            $newUser->is_enable_login = 1;
            $newUser->user_access = json_encode($access); // ["Daily Reports", "Project Documents"]
            $newUser->project_id = json_encode($projectIDs); // ["6","9"]
            $newUser->save();

            \App\Models\Utility::employeeDetails($newUser->id, $authUser->creatorId());


            $successCount++;
        } catch (\Exception $e) {
            $skippedRows[] = [
                'row' => $rowNumber,
                'name' => $name,
                'project' => $projectNamesRaw,
                'reason' => 'Import error: ' . $e->getMessage()
            ];
            continue;
        }
    }

    // ------------------ Final Response ------------------
    if (empty($skippedRows)) {
        return redirect()->route('users.index')
            ->with('success', __("All {$successCount} App Users successfully imported."));
    } else {
        $msg = "{$successCount} record(s) imported successfully. " . count($skippedRows) . " failed out of {$totalRecords}.";
        return redirect()->route('users.index')
            ->with('error', $msg)
            ->with('skippedRows', $skippedRows);
    }
}








    public function indexotheruser()
    {
        User::defaultEmail();

        $user = \Auth::user();
        if (\Auth::user()->can('manage user')) {
            if (\Auth::user()->type == 'super admin') {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'company')->with(['currentPlan'])->get();
            } else {
                $users = User::where('created_by', $user->creatorId())
    ->whereNotIn('type', ['client', 'app user'])
    ->with(['currentPlan'])
    ->get();

            }

            return view('user.other_user.index')->with('users', $users);
        } else {
            return redirect()->back();
        }

    }

        public function createotheruser()
    {

        $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();
        $user = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'client')->get()->pluck('name', 'id');
        if (\Auth::user()->can('create user')) {
            return view('user.other_user.create', compact('roles', 'customFields'));
        } else {
            return redirect()->back();
        }
    }

    public function storeotheruser(Request $request)
{
    if (!\Auth::user()->can('create user')) {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    $default_language = DB::table('settings')
        ->select('value')
        ->where('name', 'default_language')
        ->where('created_by', '=', \Auth::user()->creatorId())
        ->first();

    // ✅ Validation
    $validator = \Validator::make(
        $request->all(),
        [
            'name'     => 'required|max:120',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required', // Role is mandatory for staff
             'project_id' => 'required|array',
        ]
    );

    if ($validator->fails()) {
        return redirect()->back()->with('error', $validator->errors()->first());
    }

    // ✅ Create Staff
    $user = new User();
    $user->name              = $request->name;
    $user->email             = $request->email;
    $user->password          = \Hash::make($request->password);
    $role_r = Role::findById($request->role);
    $user->type              =  $role_r->name;
    $user->lang              = !empty($default_language) ? $default_language->value : 'en';
    $user->created_by        = \Auth::user()->id;
    $user->email_verified_at = date('Y-m-d H:i:s');
      $user->project_id  = json_encode($request->input('project_id'));
       $firstProject = collect($request->input('project_id'))->first();
    $user->project_assign_id = $firstProject ?: null;
    $user->is_enable_login   = 1;
    $user->save();

    // ✅ Assign Role
    $role = Role::findById($request->role);
    if ($role) {
        $user->assignRole($role);
    }


    // ✅ Send Welcome Email
    // \Mail::to($user->email)->send(new \App\Mail\EngineerCreatedMail($user, $request->password));

    return redirect()->route('other.user.index')->with('success', __('User successfully created.'));
}



    public function show()
    {
        return redirect()->route('user.index');
    }

    public function edit($id)
    {
        $user = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'client')->get()->pluck('name', 'id');
        if (\Auth::user()->can('edit user')) {
            $user = User::findOrFail($id);
            $user->customField = CustomField::getData($user, 'user');
            $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();

            return view('user.edit', compact('user', 'roles', 'customFields'));
        } else {
            return redirect()->back();
        }

    }

    public function update(Request $request, $id)
    {

        if (\Auth::user()->can('edit user')) {
            if (\Auth::user()->type == 'super admin') {
                $user = User::findOrFail($id);
                $validator = \Validator::make(
                    $request->all(), [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $id,
                        'company_name' => 'nullable',
            'address' => 'nullable',
            'contact_no' => 'nullable',
            'admin_engineer_access' => 'nullable|array',
            'web_access' => 'nullable|array',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
if ($request->hasFile('profile_image')) {
            $fileName = time() . '_' . $request->file('profile_image')->getClientOriginalName();
            $path = $request->file('profile_image')->storeAs('uploads/avatar', $fileName, 'local'); // Goes to storage/app/public/Working-Drawings
             $user['avatar'] = $fileName;
                }

                //                $role = Role::findById($request->role);
                $role = Role::findByName('company');
                $input = $request->all();
                 $input['user_access'] = json_encode($request->input('access'));
                 $input['web_access'] = json_encode($request->input('web_access'));

                $input['type'] = $role->name;

                $user->fill($input)->save();
                CustomField::saveData($user, $request->customField);

                $roles[] = $role->id;
                $user->roles()->sync($roles);

                return redirect()->route('users.index')->with(
                    'success', 'company successfully updated.'
                );
            } else {
                $user = User::findOrFail($id);
                $validator = \Validator::make(
                    $request->all(), [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $id,
                        // 'email' => 'required|email|unique:users,email,' . $id . ',id,created_by,' . \Auth::user()->creatorId(),
                        // 'role' => 'required',
                        'access' => 'required|array',
                        'project_id' => 'required|array',

                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                // $role = Role::findById($request->role);
                $input = $request->all();
                $input['type'] = 'app user';
                                $input['user_access'] = json_encode($request->input('access'));
                                $input['project_id'] = json_encode($request->input('project_id'));
$input['client_visibility'] = $request->has('client_visibility') ? 1 : 0;
                $user->fill($input)->save();
                Utility::employeeDetailsUpdate($user->id, \Auth::user()->creatorId());
                CustomField::saveData($user, $request->customField);

                $roles[] = $request->role;
                // $user->roles()->sync($roles);

                return redirect()->route('users.index')->with(
                    'success', 'User successfully updated.'
                );
            }
        } else {
            return redirect()->back();
        }
    }

        public function editotheruser($id)
    {
        $user = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'client')->get()->pluck('name', 'id');
        if (\Auth::user()->can('edit user')) {
            $user = User::findOrFail($id);
            $user->customField = CustomField::getData($user, 'user');
            $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();

            return view('user.other_user.edit', compact('user', 'roles', 'customFields'));
        } else {
            return redirect()->back();
        }

    }

public function updateotheruser(Request $request, $id)
{
    if (!\Auth::user()->can('edit user')) {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    $user = User::findOrFail($id);

    // ✅ Validation
    $validator = \Validator::make($request->all(), [
        'name'       => 'required|max:120',
        'email'      => 'required|email|unique:users,email,' . $user->id,
        'password'   => 'nullable|min:6',
        'role'       => 'required',
        'project_id' => 'required|array',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->with('error', $validator->errors()->first());
    }

    // ✅ Update user fields
    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = \Hash::make($request->password);
    }

    $role_r = Role::findById($request->role);
    if ($role_r) {
        $user->type = $role_r->name;
    }

    // Update project_id and assign first project to project_assign_id
    $user->project_id = json_encode($request->input('project_id'));
    $firstProject = collect($request->input('project_id'))->first();
    $user->project_assign_id = $firstProject ?: null;

    // Optional: keep login enabled
   $user->is_enable_login = $request->has('is_enable_login') ? 1 : $user->is_enable_login;

    $user->save();

    // ✅ Update role assignment
    if ($role_r) {
        $user->syncRoles([$role_r->name]);
    }

    return redirect()->route('other.user.index')->with('success', __('User successfully updated.'));
}

public function destroyotheruser($id)
{
    // Check permission
    if (!\Auth::user()->can('delete user')) {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    // Find the user
    $user = User::findOrFail($id);

    // Optional: Remove all roles assigned to user
    $user->roles()->detach();

    // Delete the user
    $user->delete();

    return redirect()->route('other.user.index')->with('success', __('User deleted successfully.'));
}

    public function userPasswordotheruser($id)
    {
        $eId = \Crypt::decrypt($id);
        $user = User::find($eId);

        return view('user.other_user.reset', compact('user'));

    }

public function userPasswordResetotheruser(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'password' => 'required|confirmed|same:password_confirmation',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $user = User::where('id', $id)->first();
        $user->forceFill([
            'password' => Hash::make($request->password),
            'is_enable_login' => 1,
        ])->save();


        return redirect()->route('other.user.index')->with(
            'success', 'User Password successfully updated.'
        );

    }





    public function destroy($id)
    {

        if (\Auth::user()->can('delete user')) {
            if ($id == 2) {
                return redirect()->back()->with('error', __('You can not delete By default Company'));
            }

            $user = User::find($id);
            if ($user) {
                if (\Auth::user()->type == 'super admin') {
                    // $referralSetting = ReferralSetting::where('created_by' , 1)->first();
                    // $users = ReferralTransaction::where('company_id' , $id)->first();
                    // $plan = Plan::find($users->plan_id);
                    // Utility::commissionAmount($plan , $referralSetting , $users->referral_code , 'minus');

                    $transaction = ReferralTransaction::where('company_id' , $id)->delete();

                    $users = User::where('created_by', $id)->delete();
                    $employee = Employee::where('created_by', $id)->delete();

                    $user->delete();

                    return redirect()->back()->with('success', __('Company Successfully deleted'));
                }

                if (\Auth::user()->type == 'company') {

                    $delete_user = User::where(['id' => $user->id])->first();
                    if ($delete_user) {
                        $employee = Employee::where(['user_id' => $user->id])->delete();
                        $delete_user->delete();

                        if ($delete_user || $employee) {
                            return redirect()->route('users.index')->with('success', __('User successfully deleted .'));
                        } else {
                            return redirect()->back()->with('error', __('Something is wrong.'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('Something is wrong.'));
                    }
                }
                return redirect()->route('users.index')->with('success', __('User successfully deleted .'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back();
        }
    }

    public function profile()
    {
        $userDetail = \Auth::user();
        $userDetail->customField = CustomField::getData($userDetail, 'user');
        $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();

        return view('user.profile', compact('userDetail', 'customFields'));
    }

    public function editprofile(Request $request)
    {
        $userDetail = \Auth::user();
        $user = User::findOrFail($userDetail['id']);

        $validator = \Validator::make(
            $request->all(), [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        if ($request->hasFile('profile')) {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $settings = Utility::getStorageSetting();
            if ($settings['storage_setting'] == 'local') {
                $dir = 'uploads/avatar/';
            } else {
                $dir = 'uploads/avatar';
            }

            $image_path = $dir . $userDetail['avatar'];

            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $url = '';
            $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);
            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->route('profile', \Auth::user()->id)->with('error', __($path['msg']));
            }
        }

        if ($request->hasFile('pdf_logo')) {
    $pdfLogoFile = $request->file('pdf_logo');
    $originalName = $pdfLogoFile->getClientOriginalName(); // e.g., "company_logo.png"

    $pdfDir = 'uploads/pdf_logo/';

    // Delete old logo if it exists
    $oldPdfLogo = $userDetail['pdf_logo'] ?? '';
    if ($oldPdfLogo && File::exists($pdfDir . $oldPdfLogo)) {
        File::delete($pdfDir . $oldPdfLogo);
    }

    // Save using the original name
    $pdfPath = Utility::upload_file($request, 'pdf_logo', $originalName, $pdfDir, []);
    if ($pdfPath['flag'] == 1) {
        $user['pdf_logo'] = $originalName; // store original file name
    } else {
        return redirect()->route('profile', \Auth::user()->id)->with('error', __($pdfPath['msg']));
    }
}

        if (!empty($request->profile)) {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name'] = $request['name'];
        $user['email'] = $request['email'];
        $user->save();
        CustomField::saveData($user, $request->customField);

        return redirect()->route('profile', $user)->with(
            'success', 'Profile successfully updated.'
        );
    }

    public function updatePassword(Request $request)
    {

        if (Auth::Check()) {

            $validator = \Validator::make(
                $request->all(), [
                    'old_password' => 'required',
                    'password' => 'required|min:6',
                    'password_confirmation' => 'required|same:password',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $objUser = Auth::user();
            $request_data = $request->All();
            $current_password = $objUser->password;
            if (Hash::check($request_data['old_password'], $current_password)) {
                $user_id = Auth::User()->id;
                $obj_user = User::find($user_id);
                $obj_user->password = Hash::make($request_data['password']);
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            } else {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        } else {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }

    public function updateProject(Request $request)
{
    $request->validate([
        'project_assign_id' => 'nullable|exists:projects,id',
    ]);

    $user = auth()->user();

    // Only allow user to assign projects they created
    $project = \App\Models\Project::where('id', $request->project_assign_id)
                                  
                                  ->first();

    if ($project || $request->project_assign_id === null) {
        $user->project_assign_id = $request->project_assign_id;
        $user->save();

        return redirect()->route('project.dashboard')->with('success', 'Project updated successfully.');
    }

    return redirect()->back()->with('error', 'Invalid project selection.');
}


    // User To do module
    public function todo_store(Request $request)
    {
        $request->validate(
            ['title' => 'required|max:120']
        );

        $post = $request->all();
        $post['user_id'] = Auth::user()->id;
        $todo = UserToDo::create($post);

        $todo->updateUrl = route(
            'todo.update', [
                $todo->id,
            ]
        );
        $todo->deleteUrl = route(
            'todo.destroy', [
                $todo->id,
            ]
        );

        return $todo->toJson();
    }

    public function todo_update($todo_id)
    {
        $user_todo = UserToDo::find($todo_id);
        if ($user_todo->is_complete == 0) {
            $user_todo->is_complete = 1;
        } else {
            $user_todo->is_complete = 0;
        }
        $user_todo->save();
        return $user_todo->toJson();
    }

    public function todo_destroy($id)
    {
        $todo = UserToDo::find($id);
        $todo->delete();

        return true;
    }

    // change mode 'dark or light'
    public function changeMode()
    {
        $usr = \Auth::user();
        if ($usr->mode == 'light') {
            $usr->mode = 'dark';
            $usr->dark_mode = 1;
        } else {
            $usr->mode = 'light';
            $usr->dark_mode = 0;
        }
        $usr->save();

        return redirect()->back();
    }

    public function upgradePlan($user_id)
    {
        $user = User::find($user_id);
        $plans = Plan::get();
        $admin_payment_setting = Utility::getAdminPaymentSetting();

        return view('user.plan', compact('user', 'plans', 'admin_payment_setting'));
    }
    public function activePlan($user_id, $plan_id)
    {

        $plan = Plan::find($plan_id);
        if($plan->is_disable == 0)
        {
            return redirect()->back()->with('error', __('You are unable to upgrade this plan because it is disabled.'));
        }

        $user = User::find($user_id);
        $assignPlan = $user->assignPlan($plan_id, $user_id);
        if ($assignPlan['is_success'] == true && !empty($plan)) {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $plan->price,
                    'price_currency' => isset(\Auth::user()->planPrice()['currency'])?\Auth::user()->planPrice()['currency'] : '',
                    'txn_id' => '',
                    'payment_status' => 'success',
                    'receipt' => null,
                    'user_id' => $user->id,
                ]
            );

            return redirect()->back()->with('success', 'Plan successfully upgraded.');
        } else {
            return redirect()->back()->with('error', 'Plan fail to upgrade.');
        }

    }

    public function userPassword($id)
    {
        $eId = \Crypt::decrypt($id);
        $user = User::find($eId);

        return view('user.reset', compact('user'));

    }

    public function userPasswordReset(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(), [
                'password' => 'required|confirmed|same:password_confirmation',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $user = User::where('id', $id)->first();
        $user->forceFill([
            'password' => Hash::make($request->password),
            'is_enable_login' => 1,
        ])->save();

        if(\Auth::user()->type == 'super admin')
        {
        return redirect()->route('users.index')->with(
            'success', 'Company Password successfully updated.'
        );
    }
    else
    {
        return redirect()->route('users.index')->with(
            'success', 'User Password successfully updated.'
        );
    }

    }

    //start for user login details
    public function userLog(Request $request)
    {
        $filteruser = User::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $filteruser->prepend('Select User', '');

        $query = DB::table('login_details')
            ->join('users', 'login_details.user_id', '=', 'users.id')
            ->select(DB::raw('login_details.*, users.id as user_id , users.name as user_name , users.email as user_email ,users.type as user_type'))
            ->where(['login_details.created_by' => \Auth::user()->id]);

        if (!empty($request->month)) {
            $query->whereMonth('date', date('m', strtotime($request->month)));
            $query->whereYear('date', date('Y', strtotime($request->month)));
        } else {
            $query->whereMonth('date', date('m'));
            $query->whereYear('date', date('Y'));
        }

        if (!empty($request->users)) {
            $query->where('user_id', '=', $request->users);
        }
        $userdetails = $query->get();
        $last_login_details = LoginDetail::where('created_by', \Auth::user()->creatorId())->get();

        return view('user.userlog', compact('userdetails', 'last_login_details', 'filteruser'));
    }

    public function userLogView($id)
    {
        $users = LoginDetail::find($id);

        return view('user.userlogview', compact('users'));
    }

    public function userLogDestroy($id)
    {
        $users = LoginDetail::where('user_id', $id)->delete();
        return redirect()->back()->with('success', 'User successfully deleted.');
    }

    public function LoginWithCompany(Request $request, User $user, $id)
    {
        $user = User::find($id);
        if ($user && auth()->check()) {
            Impersonate::take($request->user(), $user);
            return redirect('/project-dashboard');
        }
    }

    public function ExitCompany(Request $request)
    {
        \Auth::user()->leaveImpersonation($request->user());
        return redirect('/dashboard');
    }

    public function companyInfo(Request $request, $id)
    {
        $user = User::find($request->id);
        $status = $user->delete_status;
        $userData = User::where('created_by', $id)->where('type', '!=', 'client')->selectRaw('COUNT(*) as total_users, SUM(CASE WHEN is_disable = 0 THEN 1 ELSE 0 END) as disable_users, SUM(CASE WHEN is_disable = 1 THEN 1 ELSE 0 END) as active_users')->first();

        return view('user.company_info', compact('userData', 'id', 'status'));
    }

    public function userUnable(Request $request)
    {
        User::where('id', $request->id)->update(['is_disable' => $request->is_disable]);
        $userData = User::where('created_by', $request->company_id)->where('type', '!=', 'client')->selectRaw('COUNT(*) as total_users, SUM(CASE WHEN is_disable = 0 THEN 1 ELSE 0 END) as disable_users, SUM(CASE WHEN is_disable = 1 THEN 1 ELSE 0 END) as active_users')->first();

        if ($request->is_disable == 1) {

            return response()->json(['success' => __('User successfully unable.'), 'userData' => $userData]);

        } else {
            return response()->json(['success' => __('User successfully disable.'), 'userData' => $userData]);
        }
    }

    // public function LoginManage($id)
    // {
    //     $eId = \Crypt::decrypt($id);
    //     $user = User::find($eId);
    //     $authUser = \Auth::user();

    //     if ($user->is_enable_login == 1) {
    //         $user->is_enable_login = 0;
    //         $user->save();

    //         if($authUser->type == 'super admin')
    //         {
    //             return redirect()->back()->with('success', __('Company login disable successfully.'));
    //         }
    //         else
    //         {
    //             return redirect()->back()->with('success', __('User login disable successfully.'));
    //         }
    //     } else {
    //         $user->is_enable_login = 1;
    //         $user->save();
    //         if($authUser->type == 'super admin')
    //         {
    //             return redirect()->back()->with('success', __('Company login enable successfully.'));
    //         }
    //         else
    //         {
    //             return redirect()->back()->with('success', __('User login enable successfully.'));
    //         }
    //     }
    // }
    
        public function LoginManage($id)
    {
        $eId = \Crypt::decrypt($id);
        $user = User::find($eId);
        $authUser = \Auth::user();

        if ($user->is_enable_login == 1) {
            $user->is_enable_login = 0;
            $user->save();

            // ================== ONLY ADDITION START ==================

            // super admin disables admin
            if ($authUser->type == 'company' && $user->type == 'company') {
                // (if your super admin also has type=admin)
            }

            if ($authUser->type == 'super admin' && $user->type == 'company') {

                // disable all users created by this admin
                $subUsers = User::where('created_by', $user->id)->get();

                foreach ($subUsers as $subUser) {
                    $subUser->is_enable_login = 0;
                    $subUser->save();

                    // delete active token of each sub user
                    PersonalAccessToken::where('tokenable_id', $subUser->id)
                        ->where('tokenable_type', User::class)
                        ->orderByDesc('last_used_at')
                        ->limit(1)
                        ->delete();
                }
            }

            // disable current user token also
            PersonalAccessToken::where('tokenable_id', $user->id)
                ->where('tokenable_type', User::class)
                ->orderByDesc('last_used_at')
                ->limit(1)
                ->delete();

            // ================== ONLY ADDITION END ==================

            if ($authUser->type == 'super admin') {
                return redirect()->back()->with('success', __('User login disable successfully.'));
            } else {
                return redirect()->back()->with('success', __('User login disable successfully.'));
            }

        } else {

            $user->is_enable_login = 1;
            $user->save();

            return redirect()->back()->with('success', __('User login enable successfully.'));
        }
    }
}
