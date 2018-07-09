@extends('Admin::layouts.master')

@push('styles')
<link href="{{ asset('css/chat.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="container">
        <div class="col-md-5">
            <div class="panel-body">
                @if ($messages->count())
                    <ul class="chat" id="messages">
                        @foreach($messages as $message)
                            @if ($message->user_id == $match->user_from_id)
                                @php
                                    $position = 'right';
                                @endphp
                            @else
                                @php
                                    $position = 'left';
                                @endphp
                            @endif
                            <li class="{{ $position }} clearfix">
                                <span class="chat-img pull-{{ $position }}">
                                    @if ($message->user->avatar == null)
                                        <img width="50" class="img-circle" src="{{ asset('images/no-avatar.png') }}" alt="User avatar">
                                    @else
                                        <img width="50" class="img-circle" src="{{ $message->user->avatar->file_url }}" alt="User avatar">
                                    @endif
                                </span>
                                <div class="chat-body clearfix">
                                    <div class="header">
                                        <strong class="primary-font @if ($position == 'right') pull-right @endif">
                                            {{ $message->user->fullname }}
                                        </strong>
                                        <small class="text-muted @if ($position == 'left') pull-right @endif">
                                            <span class="glyphicon glyphicon-time"></span>{{ $message->timeAgo }}
                                        </small>
                                    </div>
                                    <p>{{ $message->message }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    {{ trans('Admin::templates.templates-view_index-no_entries_found') }}
                @endif
            </div>
            <div id="typing" style="display: none; font-size: 12px;"><span id="typing-text"></span><img class="img-circle" src="{{ asset('images/typing.gif') }}" alt="User avatar"></div>

            {!! Form::open(array('id' => 'message-form', 'class' => 'form-horizontal')) !!}

            <div class="panel-footer">
                <div class="input-group">
                    {!! Form::textarea('message', old('message_text'), array('class'=>'form-control custom-control', 'id'=>'message', 'rows'=>3, 'style'=>'resize:none')) !!}
                    <span id="send-message" class="input-group-addon btn btn-primary">Send</span>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

@endsection