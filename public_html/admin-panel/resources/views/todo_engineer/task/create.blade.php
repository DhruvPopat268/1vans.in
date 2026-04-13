{{ Form::open(['route' => 'todo.task.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">

        {{-- Hidden Category ID --}}
        {{ Form::hidden('to_do_engineer_id', $todoeng->id) }}

        {{-- Task Title --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('task_title', __('Task Title'), ['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('task_title', null, ['class' => 'form-control', 'placeholder' => __('Enter Task Title'), 'required']) }}
            </div>
        </div>

        {{-- Description --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class'=>'form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'rows' => 3]) }}
            </div>
        </div>

        {{-- Due Date --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('due_date', __('Due Date'), ['class'=>'form-label']) }}
                {{ Form::date('due_date', null, ['class' => 'form-control']) }}
            </div>
        </div>

        {{-- File Upload (Multiple) --}}
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                {{ Form::label('files[]', __('Attach Files'), ['class'=>'form-label']) }}
                {{ Form::file('files[]', ['class' => 'form-control', 'multiple' => true]) }}
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary">
</div>
{{ Form::close() }}
