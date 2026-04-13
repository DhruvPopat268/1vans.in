{{ Form::open(['url' => 'equipment', 'method' => 'post','enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">

        {{-- Drawing Name --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Equipment Name'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Equipment Name'), 'required']) }}
            </div>
        </div>

        {{-- Image Upload --}}
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('rate', __('Rate (Per Hours)'), ['class'=>'form-label']) }}
        {{ Form::number('rate', old('rate', 0), ['class' => 'form-control', 'placeholder' => __('Enter Rate')]) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}
