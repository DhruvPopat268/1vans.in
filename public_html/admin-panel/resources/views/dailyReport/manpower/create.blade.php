{{ Form::open(['route' => 'man-power.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">

        {{-- Man Power --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Man Power Name'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Man Power Name'), 'required']) }}
            </div>
        </div>


        {{-- Price --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('price', __('Price'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('price', old('price', 0), ['class' => 'form-control', 'placeholder' => __('Enter Price')]) }}
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}
