@extends('Admin::layouts.master')

@section('content')

    <div class="row">
        <div class="col-sm-10 col-sm-offset-2">
            <h1>{{ trans('Admin::qa.menus-edit-edit_menu_information') }}</h1>

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

    {!! Form::open(['class' => 'form-horizontal']) !!}

    @if($menu->menu_type != 2)
        <div class="form-group">
            {!! Form::label('parent_id', trans('Admin::qa.menus-edit-parent'), ['class'=>'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::select('parent_id', $parentsSelect, old('parent_id', $menu->parent_id), ['class'=>'form-control']) !!}
            </div>
        </div>
    @endif

    <div class="form-group">
        {!! Form::label('title', trans('Admin::qa.menus-edit-title'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('title', old('title',$menu->title), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-edit-title_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('roles', trans('Admin::qa.menus-edit-roles'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            @foreach($roles as $role)
                <div>
                    <label>
                        {!! Form::checkbox('roles['.$role->id.']',$role->id,old('roles.'.$role->id, $menu->roles()->where('role_id', $role->id)->pluck('id')->first())) !!}
                        {!! $role->display_name !!}
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('icon', trans('Admin::qa.menus-edit-icon'), ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('icon', old('icon',$menu->icon), ['class'=>'form-control', 'placeholder'=> trans('Admin::qa.menus-edit-icon_placeholder')]) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            {!! Form::submit( trans('Admin::qa.menus-edit-update'), ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection