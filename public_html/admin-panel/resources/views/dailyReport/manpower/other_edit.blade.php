{{ Form::model($man_power, ['route' => ['manpower.data.update', $man_power->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp

    {{-- end for ai module--}}
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Name')) }}
        {{ Form::text('name',  $man_power->name, ['class' => 'form-control', 'required']) }}
    </div>
        </div>
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                {{ Form::label('price', __('Price')) }}
        {{ Form::text('price',  $man_power->price, ['class' => 'form-control', 'required']) }}
    </div>
        </div>
    </div>

</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{Form::close()}}

