
@extends('frontend/layout/blank')

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
Jeevandeep Prakashan Pvt. Ltd.
@endsection

@section('bodyschema')@endsection
@section('bodytag')@endsection

@section('header_styles')@endsection


@section('content')
<div class="select-product-blog cf">
    <div class="product-set cf">
        <h5><i class="fa fa-shopping-bag"></i>{{$ps->title}}</h5>	
        <p class="pb5 italic">{{$ps->description}}</p>
        <div class="cf">
            <table>
                <tr>
                    <th class="name">NAME</th>
                    <th class="quantity">Quantity</th>
                    <th class="cost">Cost per item</th>
                    <th class="subtotal">subtotal</th>
                    <th class="gst">GST</th>
                    <th class="mrp">MRP</th>
                </tr>
                <?php $subtotal = 0; ?>
                <?php $totaltax = 0; ?>
                <?php $totalmrp = 0; ?>
                <?php
                $books = \App\Models\OrderProductBook::where(['order_product_id' => $ps->pivot->id])->get();
                ?>
                @foreach($books as $book)
                <tr>
                    <td class="name">{{$book->name}}</td>
                    <td class="quantity">{{$book->quantity}}</td>
                    <td class="cost">INR {{$book->price}}</td>
                    <td class="subtotal">INR {{$book->price}}</td>
                    <td class="gst">{{$book->tax}}%</td>
                    <td class="mrp">INR {{$book->price_after_tax}}</td>
                </tr>
                <?php $subtotal+=$book->price; ?>
                <?php $totalmrp+=$book->price_after_tax; ?>
                <?php $totaltax+=$book->price_after_tax - $book->price; ?>
                @endforeach
            </table>
            <div class="table-hr"></div>
            <table>
                <tr>
                    <td class="name text-right">Total without tax</td>
                    <td class="quantity nobg"></td>
                    <td class="cost nobg"></td>
                    <td class="subtotal">INR {{numberWithDecimal($subtotal)}}</td>
                    <td class="gst nobg"></td>
                    <td class="mrp nobg"></td>
                </tr>
                    @if($order->user_bill_state == 'in_state')
                    <tr>
                        <td class="name text-right">SGST in INR</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst">INR {{numberWithDecimal($order->sgst_tax)}}</td>
                        <td class="mrp nobg"></td>
                    </tr>
                    <tr>
                        <td class="name text-right">CGST in INR</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst">INR {{numberWithDecimal($order->cgst_tax)}}</td>
                        <td class="mrp nobg"></td>
                    </tr>
                    @endif
                    @if($order->user_bill_state == 'out_state')
                    <tr>
                        <td class="name text-right">IGST in INR</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst">INR {{numberWithDecimal($order->igst_tax)}}</td>
                        <td class="mrp nobg"></td>
                    </tr>
                    @endif
            </table>
            <div class="table-hr"></div>
            <table>
                <tr>
                    <td class="name text-right greybg">total mrp (A)</td>
                    <td class="quantity nobg"></td>
                    <td class="cost nobg"></td>
                    <td class="subtotal nobg"></td>
                    <td class="gst nobg"></td>
                    <td class="mrp greybg">INR {{numberWithDecimal($totalmrp)}}</td>
                </tr>
            </table> 
            <div class="table-hr"></div>
            <table>
                <tr>
                    <td class="name text-right">shipping costs</td>
                    <td class="quantity nobg"></td>
                    <td class="cost nobg"></td>
                    <td class="subtotal nobg"></td>
                    <td class="gst nobg"></td>
                    <td class="mrp">INR {{numberWithDecimal($order->shipping_charges)}}</td>
                </tr>
                    @if($order->user_bill_state == 'in_state')
                    <tr>
                        <td class="name text-right">SGST on shipping costs in inr</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst nobg"></td>
                        <td class="mrp">INR {{numberWithDecimal($order->sgst_shipping)}}</td>
                    </tr>
                    <tr>
                        <td class="name text-right">CGST on shipping costs in inr</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst nobg"></td>
                        <td class="mrp">INR {{numberWithDecimal($order->cgst_shipping)}}</td>
                    </tr>
                    @endif
                    @if($order->user_bill_state == 'out_state')
                    <tr>
                        <td class="name text-right">IGST on shipping costs in inr</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst nobg"></td>
                        <td class="mrp">INR {{numberWithDecimal($order->igst_shipping)}}</td>
                    </tr>
                    @endif
            </table>
            <div class="table-hr"></div>
            <table>
                <tr>
                    <td class="name text-right greybg">total shipping costs (b)</td>
                    <td class="quantity nobg"></td>
                    <td class="cost nobg"></td>
                    <td class="subtotal nobg"></td>
                    <td class="gst nobg"></td>
                    <td class="mrp greybg">INR {{numberWithDecimal($order->shipping)}}</td>
                </tr>
            </table>
            <div class="table-hr"></div>
            <table>
                <tr>
                    <td class="name text-right greybg">total PAYABLE (A+b)</td>
                    <td class="quantity nobg"></td>
                    <td class="cost nobg"></td>
                    <td class="subtotal nobg"></td>
                    <td class="gst nobg"></td>
                    <td class="mrp greybg">INR {{numberWithDecimal($order->total_amount)}}</td>
                </tr>
            </table>
            <div class="table-hr"></div>
        </div>
    </div>
</div>

@endsection

@section('footer_scripts')@endsection
@section('pp_footer_scripts')@endsection
@section('inlinejs')@endsection