@extends('Admin::layouts.master')

@section('content')

    <div class="row">
        <div class="col-sm-10 col-sm-offset-2">
            <h1>{{ trans('Admin::admin.users-edit-edit_user') }}</h1>

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

    {!! Form::open(['route' => ['users.update', $user->id], 'class' => 'form-horizontal', 'method' => 'PATCH']) !!}

    <div class="form-group">
        {!! Form::label('first_name', trans('Admin::admin.users-create-first-name'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('first_name', old('first_name', $user->first_name), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.users-create-first-name_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('email', trans('Admin::admin.users-edit-email'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::email('email', old('email', $user->email), ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.users-edit-email_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('password', trans('Admin::admin.users-edit-password'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=> trans('Admin::admin.users-edit-password_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('role_id', trans('Admin::admin.users-edit-role'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('role_id', $roles, old('role_id', $user->roles()->pluck('id')->first()), ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit(trans('Admin::admin.users-edit-btnupdate'), ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection


