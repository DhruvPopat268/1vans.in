{{ Form::open(['url' => 'material-testing-reports', 'method' => 'post','enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        {{-- Project Dropdown --}}
        <!--<div class="col-sm-6 col-md-6">-->
        <!--    <div class="form-group">-->
        <!--        {{ Form::label('project_id', __('Project Name'),['class'=>'form-label']) }}<x-required></x-required>-->
        <!--        {!! Form::select('project_id', ['' => 'Select Project'] + $projects->toArray(), null, ['class' => 'form-control', 'required' => 'required']) !!}-->
        <!--    </div>-->
        <!--</div>-->

        {{-- Drawing Name --}}
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Material Testing Reports Name'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Material Testing Reports Name'), 'required']) }}
            </div>
        </div>

        {{-- Image Upload --}}
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('image', __('Upload Material Testing Reports (Image)'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::file('image', ['class' => 'form-control', 'required']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}
