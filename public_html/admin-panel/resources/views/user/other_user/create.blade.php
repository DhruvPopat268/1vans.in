{{ Form::open(['url' => 'other-user-store', 'method' => 'post', 'class'=>'needs-validation', 'novalidate', 'id'=>'userForm']) }}
<div class="modal-body">
    <div class="row">
    
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter User name'), 'required' => 'required']) }}
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
                    {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email'), 'required' => 'required']) }}
                    @error('email')
                        <small class="invalid-email" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                    @enderror
                </div>
            </div>
             <div class="form-group col-md-6">
                {{ Form::label('role', __('User Role'), ['class' => 'form-label']) }}<x-required></x-required>
                {!! Form::select('role', $roles, null, ['class' => 'form-control select', 'required' => 'required']) !!}
                @error('role')
                    <small class="invalid-role" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                @enderror
            </div> 
             <div class="col-md-6">
 <div class="form-group position-relative">
        {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}<x-required></x-required>
         <div class="input-group">
        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Password'), 'required' => 'required', 'minlength' => '6']) }}
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
                <small class="text-danger d-none" id="projectError">Please select at least one project.</small>

        @error('project_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

           

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
</script>
<script>
document.getElementById('userForm').addEventListener('submit', function(event) {
    const checkboxes = document.querySelectorAll('input[name="project_id[]"]');
    const checkedOne = Array.from(checkboxes).some(checkbox => checkbox.checked);

    const projectError = document.getElementById('projectError');
    if (!checkedOne) {
        event.preventDefault(); // prevent form submission
        projectError.classList.remove('d-none'); // show error message
    } else {
        projectError.classList.add('d-none'); // hide error message
    }
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

