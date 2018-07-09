@extends('Admin::layouts.master')

@section('content')

    <div class="row">
        <div class="col-sm-10 col-sm-offset-2">
            <h1>{{ trans('Admin::admin.users-create-create_user') }}</h1>

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

    {!! Form::open(['route' => 'users.store', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('first_name', trans('Admin::admin.users-create-first-name'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('first_name', old('first_name'), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.users-create-first-name_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('email', trans('Admin::admin.users-create-email'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::email('email', old('email'), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.users-create-email_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('password', trans('Admin::admin.users-create-password'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.users-create-password_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('role_id', trans('Admin::admin.users-create-role'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('role_id', $roles, old('role_id'), ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit(trans('Admin::admin.users-create-btncreate'), ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection


