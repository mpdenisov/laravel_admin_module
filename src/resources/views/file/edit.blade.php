@extends('Admin::layouts.master')

@section('content')


    <form action="{{ route('file.rename',$folder=$name)}}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row"><div class="col-md-12"><label>New name for {{$item}} </label></div></div>
        <div class="row"><div class="col-md-12"><input name="newname" type="text"></div></div>
        <br>
        <div class="row">
            <div class="col-md-12"><input class="" type="submit" value=" Update "/></div>
        </div>
    </form>
    <form action="{{ route('file.move',$folder=$name)}}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row"><div class="col-md-12"><label>New path for {{$item}} </label></div></div>
        <div class="row"><div class="col-md-12"><input name="newpath" type="text" value="admin/"></div></div>
        <br>
        <div class="row">
            <div class="col-md-12"><input class="" type="submit" value=" Update "/></div>
        </div>
    </form>
@endsection