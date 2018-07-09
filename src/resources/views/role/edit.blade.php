@extends('Admin::layouts.master')

@section('content')

    <div class="row">
        <div class="col-sm-10 col-sm-offset-2">
            <h1>{{ trans('Admin::admin.roles-edit-edit_role') }}</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        {!! implode('', $errors->all('
                        <li class="error">:message</li>
                        ')) !!}
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {!! Form::open(['route' => ['roles.update', $role->id], 'class' => 'form-horizontal', 'method' => 'PATCH']) !!}

    <div class="form-group">
        {!! Form::label('name', trans('Admin::admin.roles-edit-name'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', old('name', $role->name), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.roles-edit-name_placeholder')]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('display_name', trans('Admin::admin.roles-edit-display-name'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('display_name', old('display_name', $role->display_name), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.roles-edit-display-name_placeholder')]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('description', trans('Admin::admin.roles-edit-description'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('description', old('description', $role->description), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.roles-edit-description_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit(trans('Admin::admin.roles-edit-btnupdate'), ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection


