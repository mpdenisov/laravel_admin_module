<div class=" files">
    @foreach($files as $file)
        @if($file=='.')
            <div class="row">
                <div class="col-md-12"><a href="{{route('folder',$name=$file)}}"><b>Back to general folder</b></a></div>
            </div>
            <br>
        @else
            @if(!is_null($files['backpath'])&&$files['backpath']==$file)
                <div class="col-md-12"><a href="{{route('folder',$name=$file)}}"><b>Back</b></a></div>


            @elseif($files['backpath']!=$file)
                <div class="row"
                     style="border-bottom: 1px solid;border-color: grey; margin-bottom: 5px; padding-bottom: 5px">
                    <div class="col-md-10"> @if(\File::extension($file)=='')<img
                                src="{{url('admin/images/open-file-button.svg')}}">  @endif
                        <a class=""
                           @if(\File::extension($file)=='')href="{{route('folder',$name=$path.'*'.$file)}}"  @endif >
                            <b>{{$file}}</b>
                        </a>
                    </div>
                    <div class="col-md-1"><a href="{{route('file.rename',$name=$path.'*'.$file)}}"><b>Edit</b></a></div>
                    <div class="col-md-1"><a href="{{route('file.delete',$name=$path.'*'.$file)}}"><img
                                    src="{{url('admin/images/icons8-trash-can.svg')}}"></a></div>
                </div>
            @endif
        @endif
    @endforeach
</div>
