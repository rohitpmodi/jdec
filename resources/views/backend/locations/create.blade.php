@extends('backend/layout/clip')
@section('topscripts')
<link href="{!! asset('/clip/bower_components/select2/dist/css/select2.min.css') !!}" rel="stylesheet" />
@endsection
@section('topscripts-off')
<script type="text/javascript">
    (function($){});
</script>
@endsection

@section('pagetitle')
    <div class="row">
        <div class="col-sm-12">

            <!-- start: PAGE TITLE & BREADCRUMB -->
            <ol class="breadcrumb">
            <li><a href="{!! url(getLang() . '/admin') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">New Location</li>
            </ol>
            <div class="page-header">
                <h1> Locations <small> | New Location</small> </h1>
            </div>
            <!-- end: PAGE TITLE & BREADCRUMB -->

                        <div class="space12">
                            <div class="btn-group btn-group-lg">
                                <a class="btn btn-default" href="{!! url(getLang() . '/admin/locations') !!}"> Locations </a>
                                <a class="btn btn-default hidden-xs" href="{!! url(getLang() . '/admin/locations/create') !!}"> <i class="fa fa-plus"></i> Add Location </a>
                            </div>
                        </div>
        </div>
    </div>
@endsection

@section('content')
{{-- <div class="container-fluid"> --}}
<div class="row">
    {{-- <div class="col-sm-2"></div> --}}
    <div class="col-sm-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="clip-stats"></i>
                <div class="panel-tools">
                    <a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
                    <a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal"> <i class="fa fa-wrench"></i> </a>
                    <a class="btn btn-xs btn-link panel-refresh" href="#"> <i class="fa fa-refresh"></i> </a>
                    <a class="btn btn-xs btn-link panel-close" href="#"> <i class="fa fa-times"></i> </a>
                </div>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12">

                        <div class="clearfix"></div>
                       @include('flash::message') 
                     {{--    @include('adminlte-templates::common.errors') --}}
                        <div class="clearfix"></div>


                        <div class="col-md-10">
                    {!! Form::open(['route' => 'admin.locations.store']) !!}

                        @include('backend.locations.fields')

                    {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- </div> --}}
@endsection

@section('bottomscripts')
        <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
         <script src="{!! asset('/clip/bower_components/select2/dist/js/select2.min.js') !!}"></script>
         <script type="text/javascript">
    jQuery(document).ready(function() {
        $('#dealer_id').select2();
    });
</script>

        <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
@endsection

@section('clipinline-off')

@endsection