@extends('Admin::layouts.master')
@section('content')
    {!! Form::open(['class' => 'form-horizontal']) !!}

    <h3>{{ trans('Admin::qa.menus-createCrud-add_fields') }}</h3>

    <table class="table">
        <tbody id="generator">
        <tr>
            <td>{{ trans('Admin::qa.menus-createCrud-show_in_list') }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @if(old('f_type'))
            @foreach(old('f_type') as $index => $fieldName)
                @include('Admin::templates.menu_field_line', ['index' => $index])
            @endforeach
        @else
            @include('Admin::templates.menu_field_line', ['index' => ''])
        @endif
        </tbody>
    </table>

    <div class="form-group">
        <div class="col-md-12">
            <button type="button" id="addField" class="btn btn-success"><i
                        class="fa fa-plus"></i> {{ trans('Admin::qa.menus-createCrud-add_field') }}
            </button>
        </div>
    </div>

    <hr/>

    <div class="form-group">
        <div class="col-md-12">
            {!! Form::submit(trans('Admin::qa.menus-createCrud-create_crud'), ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
    {!! Form::close() !!}
    <div style="display: none;">
        <table>
            <tbody id="line">
            @include('Admin::templates.menu_field_line', ['index' => ''])
            </tbody>
        </table>
@endsection

@section('javascript')

    <script type="text/javascript">
        function typeChange(e) {
            var val = $(e).val();
            // Hide all possible outputs
            $(e).parent().parent().find('.value').hide();
            $(e).parent().parent().find('.default_c').hide();
            $(e).parent().parent().find('.relationship').hide();
            $(e).parent().parent().find('.title').show().val('');
            $(e).parent().parent().find('.texteditor').hide();
            $(e).parent().parent().find('.size').hide();
            $(e).parent().parent().find('.dimensions').hide();
            $(e).parent().parent().find('.enum').hide();

            // Show a checbox which enables/disables showing in list
            $(e).parent().parent().parent().find('.show2').show();
            $(e).parent().parent().parent().find('.show_hid').val(1);
            switch (val) {
                case 'radio':
                    $(e).parent().parent().find('.value').show();
                    break;
                case 'checkbox':
                    $(e).parent().parent().find('.default_c').show();
                    break;
                case 'relationship':
                    $(e).parent().parent().find('.relationship').show();
                    $(e).parent().parent().find('.title').hide().val('-');
                    break;
                case 'textarea':
                    $(e).parent().parent().find('.show2').hide();
                    $(e).parent().parent().find('.show_hid').val(0);
                    $(e).parent().parent().find('.texteditor').show();
                    break;
                case 'file':
                    $(e).parent().parent().find('.size').show();
                    break;
                case 'enum':
                    $(e).parent().parent().find('.enum').show();
                    break;
                case 'photo':
                    $(e).parent().parent().find('.size').show();
                    $(e).parent().parent().find('.dimensions').show();
                    break;
            }
        }

        function relationshipChange(e) {
            var val = $(e).val();
            $(e).parent().parent().find('.relationship-field').remove();
            var select = $('.rf-' + val).clone();
            $(e).parent().parent().find('.relationship-holder').html(select);
        }

        $(document).ready(function () {
            $('.type').each(function () {
                typeChange($(this))
            });
            $('.relationship').each(function () {
                relationshipChange($(this))
            });

            $('.show2').change(function () {
                var checked = $(this).is(":checked");
                if (checked) {
                    $(this).parent().find('.show_hid').val(1);
                } else {
                    $(this).parent().find('.show_hid').val(0);
                }
            });

            // Add new row to the table of fields
            $('#addField').click(function () {
                var line = $('#line').html();
                var table = $('#generator');
                table.append(line);
            });

            // Remove row from the table of fields
            $(document).on('click', '.rem', function () {
                $(this).parent().parent().remove();
            });

            $(document).on('change', '.type', function () {
                typeChange($(this))
            });
            $(document).on('change', '.relationship', function () {
                relationshipChange($(this))
            });
        });

    </script>
@stop