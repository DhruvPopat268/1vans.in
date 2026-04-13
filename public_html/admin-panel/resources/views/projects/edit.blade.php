{{ Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp

    {{-- end for ai module--}}
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('project_name', __('Project Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('project_name', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('company_name', __('Company Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('company_name', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('project_number', __('Project Number'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('project_number', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                {{ Form::date('start_date', null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                {{ Form::date('end_date', null, ['class' => 'form-control']) }}
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('client', __('Client'),['class'=>'form-label']) }}<x-required></x-required>
                {!! Form::select('client', $clients, $project->client_id,array('class' => 'form-control select2','id'=>'choices-multiple1','required'=>'required')) !!}
            </div>
        </div>
                <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('budget', __('Budget'), ['class' => 'form-label']) }}
                {{ Form::number('budget', null, ['class' => 'form-control']) }}
            </div>
        </div>

    </div>
    <div class="row">

        {{--  <div class="col-6 col-md-6">
            <div class="form-group">
                {{ Form::label('estimated_hrs', __('Estimated Hours'),['class' => 'form-label']) }}
                {{ Form::number('estimated_hrs', null, ['class' => 'form-control','min'=>'0','maxlength' => '8']) }}
            </div>
        </div>  --}}
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('site_address', __('Site Address'), ['class' => 'form-label']) }}
                {{ Form::textarea('site_address', null, ['class' => 'form-control', 'rows' => '4', 'cols' => '50']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('latitude', __('Latitude'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('latitude', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('longitude', __('Longitude'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('longitude', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>

    </div>
    <div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            {{ Form::label('crite_area', __('Crite Area (meter)'), ['class' => 'form-label']) }}
            {{ Form::text('crite_area', $project->crite_area ?? null, ['class' => 'form-control', 'placeholder' => __('Enter Crite Area')]) }}
        </div>
    </div>
</div>
<div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('attendance_start_time', __('Attendance Start Time'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::time('attendance_start_time', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('attendance_end_time', __('Attendance End Time'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::time('attendance_end_time', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '4', 'cols' => '50']) }}
            </div>
        </div>
    </div>
    {{--  <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('tag', __('Tag'), ['class' => 'form-label']) }}
                {{ Form::text('tag', isset($project->tags) ? $project->tags: '', ['class' => 'form-control', 'data-toggle' => 'tags']) }}
            </div>
        </div>
    </div>  --}}
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                <select name="status" id="status" class="form-control main-element select2" >
                    @foreach(\App\Models\Project::$project_status as $k => $v)
                        <option value="{{$k}}" {{ ($project->status == $k) ? 'selected' : ''}}>{{__($v)}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            {{ Form::label('project_image', __('Project Image'), ['class' => 'form-label']) }}
            <div class="form-file mb-3">
                <input type="file" class="form-control file-validate" name="project_image" >
                <p id="" class="file-error text-danger"></p>
            </div>
            <img {{$project->img_image}} class="avatar avatar-xl" alt="project-image">
        </div>

    </div>
    <div class="row">
    <div class="col-sm-12 col-md-12">
        {{ Form::label('pdf_logo', __('PDF Logo'), ['class' => 'form-label']) }}
        <div class="form-file mb-3">
            <input type="file" class="form-control file-validate" name="pdf_logo">
            <p class="file-error text-danger"></p>
        </div>

        @if (!empty($project->pdf_logo))
            <div class="mt-2">
                <strong>{{ __('Current PDF Logo:') }}</strong><br>
                <img src="{{ asset('storage/uploads/pdf_logo/' . $project->pdf_logo) }}" alt="PDF Logo" class="avatar avatar-xl">
            </div>
        @endif
    </div>
</div>

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}

