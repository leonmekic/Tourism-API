@php
$edit = !is_null($dataTypeContent->getKey());
$add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->display_name_singular)

@section('page_header')
<h1 class="page-title">
    <i class="{{ $dataType->icon }}"></i>
    {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->display_name_singular }}
</h1>
@include('voyager::multilingual.language-selector')
@stop

@section('content')
<div class="page-content edit-add container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-bordered">
                <!-- form start -->
                <form role="form"
                      class="form-edit-add"
                      action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                      method="POST" enctype="multipart/form-data">
                    <!-- PUT Method if we are editing -->
                    @if($edit)
                    {{ method_field("PUT") }}
                    @endif

                    <!-- CSRF TOKEN -->
                    {{ csrf_field() }}

                    <div class="panel-body">

                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Adding / Editing -->
                        @php
                        $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                        @endphp

                        @foreach($dataTypeRows as $row)
                        <!-- GET THE DISPLAY OPTIONS -->
                        @php
                        if ($row->field == 'app_id')
                        continue;
                        @endphp
                        @php
                        $display_options = $row->details->display ?? NULL;
                        if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                        }
                        @endphp
                        @if (isset($row->details->legend) && isset($row->details->legend->text))
                        <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                        @endif

                        <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                            {{ $row->slugify }}
                            <label class="control-label" for="name">{{ $row->display_name }}</label>
                            @include('voyager::multilingual.input-hidden-bread-edit-add')
                            @if (isset($row->details->view))
                            @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add')])
                            @elseif ($row->type == 'relationship')
                            @include('voyager::formfields.relationship', ['options' => $row->details])
                            @else
                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                            @endif

                            @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                            {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                            @endforeach
                            @if ($errors->has($row->field))
                            @foreach ($errors->get($row->field) as $error)
                            <span class="help-block">{{ $error }}</span>
                            @endforeach
                            @endif
                        </div>
                        @endforeach

                        <h5>General Information</h5>
                        <div class="form-group  col-md-12 ">

                            <label class="control-label" for="name">Address</label>
                            <input type="text" class="form-control" name="address" placeholder="Address" value="">


                        </div>
                        <!-- GET THE DISPLAY OPTIONS -->

                        <div class="form-group  col-md-12 ">

                            <label class="control-label" for="name">Phone Number</label>
                            <input type="text" class="form-control" name="phone_number" placeholder="Phone Number" value="">


                        </div>
                        <!-- GET THE DISPLAY OPTIONS -->

                        <div class="form-group  col-md-12 ">

                            <label class="control-label" for="name">Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Email" value="">


                        </div>

                        <h5>Working Hours</h5>
                        <div class="input-group control-group after-add-more">
                            <div class="form-group  col-md-12 ">

                                <label class="control-label" for="name">Day</label>
                                <select class="form-control select2" name="day" data-select2-id="1" tabindex="0" aria-hidden="false">
                                    <option disabled selected value selected="&quot;selected&quot;"> -- select an option -- </option>
                                    <option value="All week">All week</option>
                                    <option value="Business Days">Business Days (Monday through Friday)</option>
                                    <option value="Weekend">Weekend</option>
                                </select>


                            </div>
                            <!-- GET THE DISPLAY OPTIONS -->

                            <div class="form-group  col-md-12 ">

                                <label class="control-label" for="name">Opens At</label>
                                <input required="" type="text" class="form-control" name="opens_at" placeholder="Opens At" value="">


                            </div>
                            <!-- GET THE DISPLAY OPTIONS -->

                            <div class="form-group  col-md-12 ">

                                <label class="control-label" for="name">Closes At</label>
                                <input required="" type="text" class="form-control" name="closes_at" placeholder="Closes At" value="">


                            </div>

                            @if(auth()->id() == User::SuperAdminId)
                            <div style="margin-bottom: 15px">
                                <label class="control-label" for="name">Application</label>
                                <select class="form-control select2" name="app_id" data-select2-id="1" tabindex="0" aria-hidden="false">
                                    <option disabled selected value selected="&quot;selected&quot;"> -- select an option -- </option>
                                    @foreach($apps as $app)
                                    <option value="{{ $app->id }}">{{ $app->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                        </div>


                    </div><!-- panel-body -->

                    <div class="panel-footer">
                        @section('submit-buttons')
                        <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                        @stop
                        @yield('submit-buttons')
                    </div>
                </form>

                <iframe id="form_target" name="form_target" style="display:none"></iframe>
                <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                      enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                    <input name="image" id="upload_file" type="file"
                           onchange="$('#my_form').submit();this.value='';">
                    <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                    {{ csrf_field() }}
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-danger" id="confirm_delete_modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
            </div>

            <div class="modal-body">
                <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Delete File Modal -->
@stop

@section('javascript')
<script>
    var params = {};
    var $file;

    $(document).ready(function() {


        $(".add-more").click(function(){
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });


        $("body").on("click",".remove",function(){
            $(this).parents(".control-group").remove();
        });


    });

    function deleteHandler(tag, isMulti) {
        return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
        };
    }

    $('document').ready(function () {
        $('.toggleswitch').bootstrapToggle();

        //Init datepicker for date fields if data-datepicker attribute defined
        //or if browser does not handle date inputs
        $('.form-group input[type=date]').each(function (idx, elt) {
            if (elt.type != 'date' || elt.hasAttribute('data-datepicker')) {
                elt.type = 'text';
                $(elt).datetimepicker($(elt).data('datepicker'));
            }
        });

    @if ($isModelTranslatable)
        $('.side-body').multilingual({"editing": true});
    @endif

        $('.side-body input[data-slug-origin]').each(function(i, el) {
            $(el).slugify();
        });

        $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
        $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
        $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
        $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

        $('#confirm_delete').on('click', function(){
            $.post('{{ route('voyager.media.remove') }}', params, function (response) {
                if ( response
                    && response.data
                    && response.data.status
                    && response.data.status == 200 ) {

                    toastr.success(response.data.message);
                    $file.parent().fadeOut(300, function() { $(this).remove(); })
                } else {
                    toastr.error("Error removing file.");
                }
            });

            $('#confirm_delete_modal').modal('hide');
        });
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@stop
