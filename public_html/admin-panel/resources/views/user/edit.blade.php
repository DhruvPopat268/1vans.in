{{Form::model($user,array('route' => array('users.update', $user->id), 'method' => 'PUT', 'class'=>'needs-validation','novalidate','enctype' => 'multipart/form-data')) }}
@php
    use App\Models\Project;

    $projects = Project::where('created_by', \Auth::user()->id)->pluck('project_name', 'id');
    $userProjects = is_array($user->project_access) ? $user->project_access : json_decode($user->project_access, true);


        // Get logged-in user's allowed engineer access options
        $adminAccessOptions = \Auth::user()->admin_engineer_access;

        if (is_string($adminAccessOptions)) {
            $adminAccessOptions = json_decode($adminAccessOptions, true);
        }

        if (!is_array($adminAccessOptions)) {
            $adminAccessOptions = [];
        }

        // Get the edited user's existing access values
        $userAccess = is_array($user->user_access)
            ? $user->user_access
            : json_decode($user->user_access, true);

        // Projects for access
        $projects = Project::where('created_by', \Auth::user()->creatorId())->pluck('project_name', 'id');
        $userProjects = is_array($user->project_id)
            ? $user->project_id
            : json_decode($user->project_id, true);
@endphp


<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group ">
                {{Form::label('name',__('Name'),['class'=>'form-label']) }}<x-required></x-required>
                {{Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>__('Enter User Name'), 'required' => 'required'))}}
                @error('name')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('email',__('Email'),['class'=>'form-label'])}}<x-required></x-required>
                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email'), 'required' => 'required'))}}
                @error('email')
                <small class="invalid-email" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>
        </div>
        @if(\Auth::user()->type == 'super admin')
            <div class="col-md-6">
               <div class="form-group">
                {{ Form::label('profile_image', __('Profile Image'), ['class'=>'form-label']) }}
                {{ Form::file('profile_image', ['class'=>'form-control','accept'=>'image/*']) }}
                @if($user->avatar)
    <img src="{{ asset('storage/uploads/avatar/' . $user->avatar) }}" alt="Profile" style="height:80px; margin-top:5px;">
@endif
 </div>
            </div>


           <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('company_name', __('Company Name'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('company_name', null, ['class'=>'form-control','required']) }}
            </div>
    </div>


            <div class="col-md-12">
        <div class="form-group">
                {{ Form::label('address', __('Address'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::textarea('address', null, ['class'=>'form-control','rows'=>2,'required']) }}
           </div>
    </div>

           <div class="col-md-6">
        <div class="form-group">
                {{ Form::label('gst_no', __('GST No'), ['class'=>'form-label']) }}
                {{ Form::text('gst_no', null, ['class'=>'form-control']) }}
            </div>
    </div>

            <div class="col-md-6">
        <div class="form-group">
                {{ Form::label('contact_no', __('Contact Number'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('contact_no', null, ['class'=>'form-control','required']) }}
            </div>
    </div>

            {{-- Access Permissions --}}
           <div class="col-md-12">
        <div class="form-group">
    {{ Form::label('admin_engineer_access', __('App Access Permissions'), ['class'=>'form-label fw-bold fs-5 text-dark']) }}

    {{-- Select All Checkbox --}}
    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="select_all_admin_access">
        <label class="form-check-label" for="select_all_admin_access">{{ __('Select All Access') }}</label>
    </div>

    @php
        $accessOptions = [
              'Daily Reports',
                               'Project Documents',
                                'Working Drawings',
                                'Work Issues',
                                'Testing Reports',
                                'Equipment Log',
                                'BOQ & R.A Bill',
                                'Material Order',
                                'Material Incoming',
                                'Site Gallery',
                                'To-Do List',
                                'AutoCAD Files',
                                'Attendance',
                                'Site Reports'
        ];
        $userAccess = is_array($user->admin_engineer_access) ? $user->admin_engineer_access : json_decode($user->admin_engineer_access, true);
    @endphp

    <div class="row">
        @foreach($accessOptions as $access)
            <div class="col-md-3">
                <div class="form-check">
                    <input type="checkbox" name="admin_engineer_access[]" value="{{ $access }}" class="form-check-input admin-access-checkbox" id="admin_access_{{ Str::slug($access) }}"
                        {{ in_array($access, $userAccess ?? []) ? 'checked' : '' }}>
                    <label class="form-check-label" for="admin_access_{{ Str::slug($access) }}">{{ $access }}</label>
                </div>
            </div>
        @endforeach
    </div>
</div>
    </div>

<div class="form-group col-md-12 mt-3">
    {{ Form::label('web_access', __('Web Access Permissions'), ['class' => 'form-label fw-bold fs-5 text-dark']) }}

    @php
        $webAccessOptions = [
            'Project Documents' => [
                'manage site documents' => 'Site Documents',
                'manage working drawings' => 'Working Drawings',
                'manage bill of quantity' => 'Bill Of Quantity',
                'manage material testing reports' => 'Material Testing Reports',
                'manage autocad files' => 'AutoCAD Files',
            ],
            'Project Management' => [
                'manage types of work' => 'Types Of Works',
                'manage name of works' => 'Name Of Works',
                'manage working area' => 'Working Area',
                'manage types of measurements' => 'Types Of Measurement',
                'manage work reports' => 'Work Reports',
                'manage all reports' => 'All Reports',
                'manage man power' => 'Man Power',
                'manage import data' => 'Import Data',
            ],
            'Equipment Management' => [
                'manage types of equipment' => 'Types Of Equipment',
                'manage equipments summary' => 'Equipments Summary',
                'manage equipments reports' => 'Equipments Report'
            ],
            'Material Reports' => [
                'manage material units' => 'Material Units',
                'manage types of material' => 'Types Of Material',
                
                'manage material record' => 'Material Record'
            ],
            'ToDo List' => [
                'manage todo list' => 'To-Do List'
            ],
            'Work Issue' => [
                'manage work issue' => 'Work Issue'
            ],
            'Site Gallery' => [
                'manage site gallery' => 'Site Gallery'
            ],
            'Attendance' => [
             'manage holiday' => 'Holiday',
                'manage engineer attendance' => 'Attendance'
            ],
            'Site Reports' => [
                 'manage site report' => 'Site Reports'
             ],
        ];

        $userWebAccess = is_array($user->web_access) ? $user->web_access : json_decode($user->web_access, true);
    @endphp

    {{-- Select All Checkbox --}}
    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="select_all_web_access_edit">
        <label class="form-check-label" for="select_all_web_access_edit">
            {{ __('Select All Web Access') }}
        </label>
    </div>

    {{-- Render grouped web access options --}}
    @foreach ($webAccessOptions as $group => $permissions)
        <div class="mt-3 mb-2"><strong>{{ $group }}</strong></div>
        <div class="row">
            @foreach ($permissions as $key => $label)
                <div class="col-md-6">
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="web_access[]" value="{{ $key }}" class="form-check-input web-access-checkbox-edit" id="web_access_{{ Str::slug($key, '_') }}"
                            {{ in_array($key, $userWebAccess ?? []) ? 'checked' : '' }}>
                        <label class="form-check-label" for="web_access_{{ Str::slug($key, '_') }}">{{ $label }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    @error('web_access')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

        @endif

@if(\Auth::user()->type != 'super admin')
            {{--  <div class="form-group col-md-12">
                {{ Form::label('role', __('User Role'),['class'=>'form-label']) }}<x-required></x-required>
                {!! Form::select('role', $roles, $user->roles,array('class' => 'form-control select','required'=>'required')) !!}
                @error('role')
                <small class="invalid-role" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
                @enderror
            </div>  --}}
           <div class="form-group col-md-12">
    {{ Form::label('access', __('Engineer Access'), ['class' => 'form-label']) }}<x-required></x-required>



    @if(!empty($adminAccessOptions))


        <div class="access-checkboxes">
            @foreach ($adminAccessOptions as $index => $option)
                @if ($index % 2 == 0)
                    <div class="row mb-2">
                @endif

                <div class="col-md-6">
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="access[]" value="{{ $option }}"
                            class="form-check-input access-checkbox"
                            id="access_{{ $index }}"
                            {{ in_array($option, $userAccess ?? []) ? 'checked' : '' }}>
                        <label class="form-check-label" for="access_{{ $index }}">{{ $option }}</label>
                    </div>
                </div>

                @if ($index % 2 == 1 || $loop->last)
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <p class="text-muted">{{ __('No access options available for engineers.') }}</p>
    @endif

    @error('access')
        <small class="invalid-access" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </small>
    @enderror
</div>



            <div class="form-group col-md-12 mt-3">
                {{ Form::label('project_id', __('Project Access'), ['class' => 'form-label']) }}<x-required></x-required>


                <div class="row">
                    @foreach ($projects as $id => $name)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="project_id[]" value="{{ $id }}"
                                    class="form-check-input"
                                    id="project_id_{{ $id }}"
                                    {{ in_array($id, $userProjects ?? []) ? 'checked' : '' }}>

                                <label class="form-check-label" for="project_id_{{ $id }}">{{ $name }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('project_access')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
                {{-- ✅ Client Visibility (EDIT) --}}
<div class="col-md-6 mb-3 form-group mt-3">
    <div class="d-flex justify-content-between align-items-center">
        <label for="client_visibility" class="form-label mb-0">
            {{ __('Client Visibility') }}
        </label>

        <div class="form-check form-switch custom-switch-v1 m-0" style="margin-right: 55% !important">
            <input type="checkbox" name="client_visibility"
                   class="form-check-input input-primary pointer"
                   value="1" id="client_visibility"
                   {{ $user->client_visibility ? 'checked' : '' }}>
            <label class="form-check-label" for="client_visibility"></label>
        </div>
    </div>
</div>
        @endif
        @if(!$customFields->isEmpty())
            {{-- <div class="col-md-6"> --}}
                {{-- <div class="tab-pane fade show" id="tab-2" role="tabpanel"> --}}
                    @include('customFields.formBuilder')
                {{-- </div> --}}
            {{-- </div> --}}
        @endif
    </div>

</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-secondary"data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>

{{Form::close()}}

<script>
    $(document).ready(function() {
        const selectAll = $('#select_all_admin_access');
        const checkboxes = $('.admin-access-checkbox');

        // On page load, check if all are checked
        selectAll.prop('checked', checkboxes.length === checkboxes.filter(':checked').length);

        // When Select All is clicked
        selectAll.on('change', function() {
            checkboxes.prop('checked', $(this).is(':checked'));
        });

        // When any checkbox is clicked
        checkboxes.on('change', function() {
            selectAll.prop('checked', checkboxes.length === checkboxes.filter(':checked').length);
        });
    });
    $(document).ready(function() {
    const selectAllWebEdit = $('#select_all_web_access_edit');
    const checkboxesWebEdit = $('.web-access-checkbox-edit');

    // On page load, check if all are checked
    selectAllWebEdit.prop('checked', checkboxesWebEdit.length === checkboxesWebEdit.filter(':checked').length);

    // When Select All is clicked
    selectAllWebEdit.on('change', function() {
        checkboxesWebEdit.prop('checked', $(this).is(':checked'));
    });

    // When any checkbox is clicked
    checkboxesWebEdit.on('change', function() {
        selectAllWebEdit.prop('checked', checkboxesWebEdit.length === checkboxesWebEdit.filter(':checked').length);
    });
});
</script>
