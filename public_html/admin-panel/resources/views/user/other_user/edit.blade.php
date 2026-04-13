{{Form::model($user,array('route' => array('other.user.update', $user->id), 'method' => 'PUT', 'class'=>'needs-validation','novalidate', 'id'=>'userForm')) }}
@php
    use App\Models\Project;

    // Get all projects created by current logged-in user
    $projects = Project::where('created_by', \Auth::user()->id)->pluck('project_name', 'id');

    // Decode user's assigned project IDs from JSON
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
@if(\Auth::user()->type != 'super admin')
            <div class="form-group col-md-12">
                {{ Form::label('role', __('User Role'),['class'=>'form-label']) }}<x-required></x-required>
                {!! Form::select('role', $roles, $user->roles,array('class' => 'form-control select','required'=>'required')) !!}
                @error('role')
                <small class="invalid-role" role="alert">
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
                <small class="text-danger d-none" id="projectError">Please select at least one project.</small>

                @error('project_access')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
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
