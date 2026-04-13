{{ Form::open(['url' => 'users', 'method' => 'post', 'class'=>'needs-validation', 'novalidate' ,'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        @if (\Auth::user()->type == 'super admin')
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Admin Name'), 'required' => 'required']) }}
                    @error('name')
                        <small class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Admin Email'), 'required' => 'required']) }}
                    @error('email')
                        <small class="invalid-email" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
    </div>

    {{-- ✅ Profile Image --}}
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('profile_image', __('Profile Image'), ['class' => 'form-label']) }}
            {{ Form::file('profile_image', ['class' => 'form-control', 'accept' => 'image/*']) }}
            @error('profile_image')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    {{-- ✅ Company Name --}}
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('company_name', __('Company Name'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('company_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Company Name'), 'required' => 'required']) }}
            @error('company_name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    {{-- ✅ Address --}}
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter Company Address'), 'required' => 'required']) }}
            @error('address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    {{-- ✅ GST No --}}
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('gst_no', __('GST No'), ['class' => 'form-label']) }}
            {{ Form::text('gst_no', null, ['class' => 'form-control', 'placeholder' => __('Enter GST Number')]) }}
            @error('gst_no')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    {{-- ✅ Contact No --}}
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('contact_no', __('Contact Number'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('contact_no', null, ['class' => 'form-control', 'placeholder' => __('Enter Contact Number'), 'required' => 'required']) }}
            @error('contact_no')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    {{-- ✅ Access Section (Engineer Access for Company Admins) --}}
    <div class="form-group col-md-12 mt-3">
{{ Form::label('access', __('App Access Permissions'), ['class' => 'form-label fw-bold fs-5 text-dark']) }}
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
        @endphp

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="select_all_superadmin_access">
            <label class="form-check-label" for="select_all_superadmin_access">
                {{ __('Select All Access') }}
            </label>
        </div>

        <div class="access-checkboxes mt-4">
            @foreach ($accessOptions as $index => $option)
                @if ($index % 2 == 0)
                    <div class="row mb-2">
                @endif
                <div class="col-md-6">
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="access[]" value="{{ $option }}" class="form-check-input superadmin-access-checkbox" id="superadmin_access_{{ $index }}">
                        <label class="form-check-label" for="superadmin_access_{{ $index }}">{{ $option }}</label>
                    </div>
                </div>
                @if ($index % 2 == 1 || $loop->last)
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ✅ Web Access Permissions --}}
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
                'manage types of material' => 'Material Units',
                'manage material units' => 'Types Of Material',
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
    @endphp

    {{-- Select All Web Access --}}
    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="select_all_web_access">
        <label class="form-check-label" for="select_all_web_access">
            {{ __('Select All Web Access') }}
        </label>
    </div>

    {{-- Render grouped web access options --}}
    @foreach ($webAccessOptions as $group => $permissions)
        <div class="mt-3 mb-2">
            <strong>{{ $group }}</strong>
        </div>
        <div class="row">
            @foreach ($permissions as $key => $label)
                <div class="col-md-6">
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="web_access[]" value="{{ $key }}" class="form-check-input web-access-checkbox" id="web_access_{{ Str::slug($key, '_') }}">
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


            {!! Form::hidden('role', 'company', null, ['class' => 'form-control select2', 'required' => 'required']) !!}
            <div class="col-md-6 mb-3 form-group mt-4">
                <label for="password_switch">{{ __('Login is enable') }}</label>
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="password_switch" class="form-check-input input-primary pointer" value="on" id="password_switch">
                    <label class="form-check-label" for="password_switch"></label>
                </div>
            </div>
            <div class="col-md-6 ps_div d-none">
                <div class="form-group">
                    {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Admin Password'), 'minlength' => '6']) }}
                    @error('password')
                        <small class="invalid-password" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
        @else
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Engineer Name'), 'required' => 'required']) }}
                    @error('name')
                        <small class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Engineer Email'), 'required' => 'required']) }}
                    @error('email')
                        <small class="invalid-email" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
            {{--  <div class="form-group col-md-6">
                {{ Form::label('role', __('User Role'), ['class' => 'form-label']) }}<x-required></x-required>
                {!! Form::select('role', $roles, null, ['class' => 'form-control select', 'required' => 'required']) !!}
                @error('role')
                    <small class="invalid-role" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                @enderror
            </div>  --}}
<div class="form-group col-md-12">
    {{ Form::label('access', __('Engineer Access'), ['class' => 'form-label']) }}<x-required></x-required>

    @php
        // Get admin/engineer access options from database or config
        // Example: $adminAccessOptions is stored in DB for super admin
        $adminAccessOptions = is_array($user->admin_engineer_access) ? $user->admin_engineer_access : json_decode($user->admin_engineer_access, true);
@endphp

    @if(!empty($adminAccessOptions))
<div id="select_all_engineers_container">
                <!-- Select All Checkbox -->
            <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="select_all_engineers">
                    <label class="form-check-label" for="select_all_engineers">
                        {{ __('Select All Engineers') }}
                    </label>
                </div>
</div>

            <div class="access-checkboxes">
            @foreach ($adminAccessOptions as $index => $option)
            @if ($index % 2 == 0)
                <div class="row mb-2">
            @endif

            <div class="col-md-6">
                <div class="form-check form-check-inline">
                            <input type="checkbox" name="access[]" value="{{ $option }}"
                                class="form-check-input access-checkbox" id="access_{{ $index }}">
                    <label class="form-check-label" for="access_{{ $index }}">{{ $option }}</label>
                </div>
            </div>

            @if ($index % 2 == 1 || $loop->last)
                </div>
            @endif
        @endforeach
    </div>
    @else
        <p>{{ __('No access options available for engineers.') }}</p>
    @endif

    @error('access')
        <small class="invalid-access" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </small>
    @enderror
</div>



    <div class="form-group col-md-12 mt-3">
        {{ Form::label('project_id', __('Project Access'), ['class' => 'form-label']) }}<x-required></x-required>

        @php
            $projects = \App\Models\Project::where('created_by', \Auth::user()->id)->pluck('project_name', 'id');
        @endphp

<div class="form-check">
    @php $count = 0; @endphp
    @foreach ($projects as $id => $name)
        @if ($count % 3 == 0)
            <div class="row mb-2">
        @endif

        <div class="col-md-4">
            <div class="form-check form-check-inline">
                <input type="checkbox" name="project_id[]" value="{{ $id }}" class="form-check-input" id="project_{{ $id }}">
                <label class="form-check-label" for="project_{{ $id }}">{{ $name }}</label>
            </div>
        </div>

        @php $count++; @endphp

        @if ($count % 3 == 0 || $loop->last)
            </div>
        @endif
    @endforeach
</div>

        @error('project_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    
            {{-- ✅ Client Visibility Switch --}}
<div class="col-md-6 mb-3 form-group mt-3">
    <label for="client_visibility">{{ __('Client Visibility') }}</label>
    <div class="form-check form-switch custom-switch-v1 float-end" style="margin-right: 55%;">
        <input type="checkbox" name="client_visibility"
               class="form-check-input input-primary pointer"
               value="1" id="client_visibility">
        <label class="form-check-label" for="client_visibility"></label>
    </div>

</div>

            <div class="col-md-6">
 <div class="form-group position-relative">
             {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}<x-required></x-required>
              <div class="input-group">
        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Engineer Password'), 'required' => 'required', 'minlength' => '6']) }}
        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                <i class="fa fa-eye" id="togglePasswordIcon"></i>
            </span>
            </div>
        @error('password')
            <small class="invalid-password" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

        @endif
        @if (!$customFields->isEmpty())
            {{-- <div class="col-md-6"> --}}
                {{-- <div class="tab-pane fade show" id="tab-2" role="tabpanel"> --}}
                    @include('customFields.formBuilder')
                {{-- </div> --}}
            {{-- </div> --}}
        @endif
    </div>

</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>

{{ Form::close() }}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Delegated event listener to support dynamic content (like in modals)
        document.body.addEventListener('change', function (event) {
            if (event.target && event.target.id === 'select_all_access') {
                const isChecked = event.target.checked;

                // Only checkboxes with access-checkbox class (not project checkboxes)
                const accessCheckboxes = document.querySelectorAll('.access-checkbox');
                accessCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = isChecked;
            });
            }
        });
    });

     $('#select_all_engineers').change(function() {
        let isChecked = $(this).is(':checked');
        $('.access-checkbox').prop('checked', isChecked);
    });

    $('#select_all_superadmin_access').change(function() {
    let isChecked = $(this).is(':checked');
    $('.superadmin-access-checkbox').prop('checked', isChecked);

});
    // ✅ Select All Web Access
$('#select_all_web_access').change(function() {
    let isChecked = $(this).is(':checked');
    $('.web-access-checkbox').prop('checked', isChecked);
    });
</script>
<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordField = document.getElementById('password');
    const icon = document.getElementById('togglePasswordIcon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>


