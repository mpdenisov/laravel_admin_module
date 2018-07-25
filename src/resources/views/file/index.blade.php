@extends('Admin::layouts.master')

@section('content')


    <div class="row">
        <div class="col-xs-12 form-group ">
            <button class="add-folder btn btn-primary ">Add Folder</button>
            <form  style="display: none" action="{{route('folder.create',$folder=$path)}}" class="create-folder" method="GET" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12"><label> name for folder</label></div>
                </div>
                <div class="row">
                    <div class="col-md-12"><input name="name" type="text"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12"> <button  class="btn btn-primary ">Create Folder</button></div>
                </div>
            </form>

        </div>
    </div>

    <br>

    {!! Form::open([ 'route' => [ 'file.upload',$folder=$path ], 'files' => true, 'enctype' => 'multipart/form-data', 'class' => 'dropzone', 'id' => 'myAwesomeDropzone' ]) !!}

    {!! Form::close() !!}
    <br>
    <br>
    <br>
    <div class="directory ">
        @include('Admin::file.directory')
    </div>

@endsection

@section('javascript')
    <script type="text/javascript">

        $(function () {
            Dropzone.options.myAwesomeDropzone = {

                paramName: "file", // The name that will be used to transfer the file
                maxFilesize: 4, // MB

            };
        });
        $(function () {
            $('body').on('click', 'directory', function (e) {
                e.preventDefault();

                var url = $(this).attr('href');
                getArticles(url);
                window.history.pushState("", "", url);
            });

            function getArticles(url) {
                $.ajax({
                    url: url
                }).done(function (data) {
                    $('.files').html(data);
                }).fail(function () {
                    alert('Articles could not be loaded.');
                });
            }
        });
        var create=$(".create-folder");
        $(function () {
            $('body').on('click', '.add-folder', function (e) {

                $(".create-folder").show();
                $(".add-folder").hide();
            });


        });

    </script>

@stop