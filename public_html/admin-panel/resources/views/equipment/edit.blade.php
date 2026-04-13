{{ Form::model($equipment, ['route' => ['equipment.update', $equipment->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp

    {{-- end for ai module--}}
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Equipment Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('rate', __('Rate (Per Hours)'), ['class' => 'form-label']) }}
                {{ Form::number('rate', null, ['class' => 'form-control']) }}
            </div>
        </div>

    </div>

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}

