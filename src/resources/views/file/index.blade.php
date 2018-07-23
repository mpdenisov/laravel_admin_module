@extends('Admin::layouts.master')

@section('content')


    <div class="row">
        <div class="col-xs-12 form-group">
            <a href="{{route('folder.create',$folder=$path)}}" class="btn btn-primary">Create Folder</a>
        </div>
    </div>

    <br>

    {!! Form::open([ 'route' => [ 'file.upload',$folder=$path ], 'files' => true, 'enctype' => 'multipart/form-data', 'class' => 'dropzone', 'id' => 'image-upload' ]) !!}

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
        $(function () {
            $('body').on('click', '.rename', function (e) {

            });


        });

    </script>

@stop