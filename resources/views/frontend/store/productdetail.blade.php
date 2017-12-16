
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
                $books = $ps->books;
                ?>
                @foreach($books as $book)
                <tr>
                    <td class="name">{{$book->name}}</td>
                    <td class="quantity">{{$book->product_qty}}</td>
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
                <?php if (userState('billing') == $ps->company->state) { ?>
                    <tr>
                        <td class="name text-right">SGST in INR</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst">INR {{numberWithDecimal($totaltax/2)}}</td>
                        <td class="mrp nobg"></td>
                    </tr>
                    <tr>
                        <td class="name text-right">CGST in INR</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst">INR {{numberWithDecimal($totaltax/2)}}</td>
                        <td class="mrp nobg"></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td class="name text-right">IGST in INR</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst">INR {{numberWithDecimal($totaltax)}}</td>
                        <td class="mrp nobg"></td>
                    </tr>
                <?php } ?>
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
                    <td class="mrp">INR {{numberWithDecimal($ps->shipping_state)}}</td>
                </tr>
                <?php $shippingtax = (($ps->shipping_state * getProductItemHighestTax($ps->id)) / 100); ?>
                <?php if (userState('billing') == $ps->company->state) { ?>
                    <tr>
                        <td class="name text-right">SGST on shipping costs in inr</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst nobg"></td>
                        <td class="mrp">INR {{numberWithDecimal($shippingtax/2)}}</td>
                    </tr>
                    <tr>
                        <td class="name text-right">CGST on shipping costs in inr</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst nobg"></td>
                        <td class="mrp">INR {{numberWithDecimal($shippingtax/2)}}</td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td class="name text-right">IGST on shipping costs in inr</td>
                        <td class="quantity nobg"></td>
                        <td class="cost nobg"></td>
                        <td class="subtotal nobg"></td>
                        <td class="gst nobg"></td>
                        <td class="mrp">INR {{numberWithDecimal($shippingtax)}}</td>
                    </tr>
                <?php } ?>
            </table>
            <div class="table-hr"></div>
            <table>
                <tr>
                    <td class="name text-right greybg">total shipping costs (b)</td>
                    <td class="quantity nobg"></td>
                    <td class="cost nobg"></td>
                    <td class="subtotal nobg"></td>
                    <td class="gst nobg"></td>
                    <td class="mrp greybg">INR {{numberWithDecimal($shippingtax+$ps->shipping_state)}}</td>
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
                    <td class="mrp greybg">INR {{numberWithDecimal($ps->price)}}</td>
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