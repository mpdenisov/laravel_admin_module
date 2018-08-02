@extends('Admin::layouts.master')

@section('content')


    <form action="{{ route('file.rename',$folder=$name)}}" method="POST" class="form-group" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row"><div class="col-md-12 control-label "><label class="control-label">New name for: {{$item}} </label></div></div>
        <div class="row"><div class="col-md-3 "><input class="form-control" name="newname" type="text"></div></div>
        <br>
        <div class="row">
            <div class="col-md-12"><input class="btn btn-primary" type="submit" value=" Update "/></div>
        </div>
    </form>

    <form action="{{ route('file.move',$folder=$name)}}" method="POST" class="form-group" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row"><div class="col-md-12 control-label"><label >New path for: {{$item}} </label></div></div>
        <div class="row"><div class="col-md-5"><input  class="form-control" name="newpath" type="text" value="admin/"></div></div>
        <br>
        <div class="row">
            <div class="col-md-12"><input class="btn btn-primary" type="submit" value=" Update "/></div>
        </div>
    </form>
@endsection