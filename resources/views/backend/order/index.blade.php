@extends('backend/layout/clip')

@section('topscripts-off')
<script type="text/javascript">
    (function ($) {
    });
</script>
@endsection

@section('pagetitle')
<div class="row">
    <div class="col-sm-12">

        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li><a href="{!! url(getLang() . '/admin') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>

            <li class="active">Orders</li>
        </ol>
        <div class="page-header">
            <h1>  Orders </h1>
        </div>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="clip-stats"></i>
                <div class="panel-tools">
                    <a class="btn btn-xs btn-link panel-collapse collapses" href="#"> </a>
                </div>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12">

                        <div class="clearfix"></div>
                        @include('flash::message')
                        <div class="clearfix"></div>

                        <div class="space12">
                            <div class="btn-group btn-group-lg">
                            </div>
                        </div>
                        <form role="form" method="GET" class="order_form">
                        <div class="widget search col-md-12">
                                <div class="form-group col-md-3">
                                    <label class="control-label" for="title">Order ID</label>
                                    <div class="controls">
                                        <input placeholder="Order ID" type="text" name="order_id" value="{{@$order_id}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="control-label" for="charge_status">Product</label>
                                    <div class="controls">
                                        {!! Form::select('product_id', $product, $product_id, array('class' => 'form-control', 'value'=>Input::old('product_id'))) !!}
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="control-label" for="title">Search</label>
                                    <div class="controls">
                                        <input placeholder="Search" type="text" name="search" value="{{@$search}}" class="form-control">
                                    </div>
                                </div>
                                 <div class="form-group col-md-3">
                                        <label class="control-label" for="status_filter">Status</label>
                                        <div class="controls">
                                            {!! Form::select('status', [null => 'ALL'] + $statuss, @$status, array('class' => 'form-control', 'value'=>Input::old('status'))) !!}
                                        </div>
                                    </div>
                                 <div class="form-group col-md-3">
                                    <label class="control-label" for="title">From Date</label>

                                    <div class="controls">
                                        <input type="date" name="from" value="{{@$from}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="control-label" for="title">To Date</label>

                                    <div class="controls">
                                        <input type="date" name="to" value="{{@$to}}" class="form-control">
                                    </div>
                                </div>
                                 <div class="form-group col-md-3">
                                    <label class="control-label" for="title">From Price</label>

                                    <div class="controls">
                                        <input type="text" name="min" value="{{@$min}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="control-label" for="title">To Price</label>

                                    <div class="controls">
                                        <input type="text" name="max" value="{{@$max}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="controls">
                                        <button class="btn btn-info" type="submit">FILTER</button>
                                        <a class="btn btn-info" href="{{url(getLang().'/admin/order')}}">CLEAR</a>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-12">
                            <button formtarget="_blank" disabled="" name="export" value="1" class="btn btn-success export-btn" type="submit">PRINT SELECTED ORDERS</button>
                            <div class="btn-group">
                                <a href="javascript:;" data-toggle="dropdown" class="btn btn-primary dropdown-toggle disabled status_btn">
                                    CHANGE STATUS <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu status_dropdown" role="menu">
                                    @foreach($statuss as $k => $v)
                                    <li role="presentation">
                                        <a href="javascript:;" tabindex="-1" role="menuitem" data-id={!!$k!!}>
                                            {!!$v!!}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
							<input type="hidden" value="" name="change_status" id="change_status">
                            <input type="hidden" value="{{@$offset}}" name="offset">
                            <input type="hidden" value="" class="export-order" name="export_order">
                        </div>
                        </form>
                        <div class="col-md-12">
                            @include('backend.order.table')
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
<script>
    $('.check-all').click(function(){
        if ($('.check-all').prop('checked')){
            $('.export-btn').removeAttr('disabled');
            $('.order-check').prop('checked', true);
			$('.status_btn').removeClass('disabled');
        }else{
            $('.export-btn').attr('disabled','');
			$('.order-check').prop('checked', false);
			$('.status_btn').removeClass('disabled').addClass('disabled');
        }
         var a = ''
        $('.order-check').each(function(){
            if(this.checked) {
                a += $(this).attr('data')+',';
            }
         });
        $('.export-order').val(a.replace(/^,|,$/g,''));
    });
    $('.order-check').change(function(){
        var a = ''
        $('.order-check').each(function(){
            if(this.checked) {
                a += $(this).attr('data')+',';
            }
         });
         if(a){
             $('.export-btn').removeAttr('disabled');
			 $('.status_btn').removeClass('disabled');
         }else{
             
             $('.export-btn').attr('disabled','');
			 $('.status_btn').removeClass('disabled').addClass('disabled');
         }
        $('.export-order').val(a.replace(/^,|,$/g,''));
    });
	$( ".status_dropdown li a" ).on( "click", function() {
	    var status_id = $(this).attr('data-id');
		var status_name = $(this).text().trim();
		var conf = confirm('Are you sure want to change status of selected orders to '+status_name);
		if(conf == true){
			$('#change_status').val(status_id);
			$('.order_form').submit();
		}
	});
</script>
@endsection
@section('clipinline-off')
@endsection