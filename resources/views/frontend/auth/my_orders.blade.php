@extends('frontend/layout/layout')

@section('htmlschema')
itemscope itemtype="http://schema.org/LocalBusiness
@endsection

@section('seo')
@endsection

@section('json-ld')
@endsection

@section('goodrelations-off')
@endsection

@section('title')
My Orders | Jeevandeep Prakashan Pvt. Ltd.
@endsection

@section('bodyschema')@endsection
@section('bodytag')@endsection

@section('header_styles')@endsection


@section('content')
<!-- Start Wrapper -->
<div class="wrapper content create-profile my-account cf">
    @include('frontend.layout.jeevandeep.header')
    <div class="select-div"><strong>My Account</strong></div>
    <div class="please-select">Welcome <span>{{Sentinel::getUser()->parent_first_name.' '.Sentinel::getUser()->parent_last_name}}</span> to your Jeevandeep Online Store account. You can view or edit your profile, view your orders and their status as well as download invoices for your completed orders from this page.</div>
    @if(Session::has('error'))
    <div class="flash-message alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    @if(Session::has('success'))
    <div class="flash-message alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <!-- Start Lets Connect -->
    <div class="cf">
        @include('frontend.layout.jeevandeep.account-left')
        <div class="account-right">
            @if(count($orders) > 0)
            <div class="my-order">
                <table>
                    <thead>
                        <tr>
                            <th class="my-col-3">ORDER ID</th>
                            <th class="my-col-1">PRODUCT TITLE</th>
                            <th class="my-col-2">DATE OF PURCHASE</th>
                            <th class="my-col-3">ORDER TOTAL</th>
                            <th class="my-col-4">ORDER STATUS</th>
                            <th class="my-col-5">DOWNLOAD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $om)
                        @foreach($om->product as $op)
                        <tr>
                            <td class="my-col-3"><div>{{$om->order_no}}</div></td>
                            <td class="my-col-1"><div><a rel="group" class="thickbox underline" href="{!! route('store.orderproduct', [$om->id]) !!}?width=905&height=550" title="{!! $op->pivot->title !!}">{{$op->title}}</a></div></td>
                            <td class="my-col-2"><div>{{$om->order_date_formatted_short}}</div></td>
                            <td class="my-col-3"><div>INR {{$om->total_amount}}</div></td>
                            <td class="my-col-4"><div>{{$om->order_status_text}}</div></td>
                            <td class="my-col-5">
                                <div>
                                    @if($om->order_status_text != 'Cancelled')
                                    <a href="{!! route('invoice', [$om->id]) !!}" target="_blank"><i class="fa fa-download"></i><span>Download</span></a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                No orders found.
            </div>
            @endif
        </div>
    </div>
    <div class="any-que">If you have any questions regarding an order, please contact Jeevandeep Customer Support at enquiries@jeevandeep.in.</div>
</div>
<!-- End Wrapper -->
@endsection

@section('footer_scripts')@endsection
@section('pp_footer_scripts')@endsection
@section('inlinejs')@endsection