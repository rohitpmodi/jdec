<tr>
    <th class="name">Name</th>
    <th class="quantity text-center">Quantity</th>
    <th class="cost text-center">Cost Per Item</th>
    <th class="subtotal text-center">Subtotal</th>
    <th class="gst text-center">GST</th>
    <th class="mrp text-center">MRP</th>
</tr>
<?php $subtotal = 0; ?>
<?php $totaltax = 0; ?>
<?php $totalmrp = 0; ?>
<?php $books = \App\Models\OrderProductBook::where(['order_product_id' => $ps->pivot->id])->get(); ?>
@foreach($books as $book)
<tr>
    <td class="name">{{$book->name}}</td>
    <td class="quantity text-center">{{$book->quantity}}</td>
    <td class="cost text-center">INR {{$book->price}}</td>
    <td class="subtotal text-center">INR {{$book->price}}</td>
    <td class="gst text-center">{{$book->tax}}%</td>
    <td class="mrp text-center">INR {{$book->price_after_tax}}</td>
</tr>
<?php $subtotal += $book->price; ?>
<?php $totalmrp += $book->price_after_tax; ?>
<?php $totaltax += $book->price_after_tax - $book->price; ?>
@endforeach
<tr>
    <td class="name text-right">Total Without Tax</td>
    <td class="quantity nobg"></td>
    <td class="cost nobg"></td>
    <td class="subtotal text-center">INR {{numberWithDecimal($subtotal)}}</td>
    <td class="gst nobg"></td>
    <td class="mrp nobg"></td>
</tr>
@if($order->user_bill_state == 'in_state')
<tr>
    <td class="name text-right">SGST in INR</td>
    <td class="quantity nobg"></td>
    <td class="cost nobg"></td>
    <td class="subtotal nobg"></td>
    <td class="gst text-center">INR {{numberWithDecimal($order->sgst_tax)}}</td>
    <td class="mrp nobg"></td>
</tr>
<tr>
    <td class="name text-right">CGST in INR</td>
    <td class="quantity nobg"></td>
    <td class="cost nobg"></td>
    <td class="subtotal nobg"></td>
    <td class="gst text-center">INR {{numberWithDecimal($order->cgst_tax)}}</td>
    <td class="mrp nobg"></td>
</tr>
@endif
@if($order->user_bill_state == 'out_state')
<tr>
    <td class="name text-right">IGST in INR</td>
    <td class="quantity nobg"></td>
    <td class="cost nobg"></td>
    <td class="subtotal nobg"></td>
    <td class="gst text-center">INR {{numberWithDecimal($order->igst_tax)}}</td>
    <td class="mrp nobg"></td>
</tr>
@endif
<tr>
    <td class="name text-right greybg">Total MRP (A)</td>
    <td class="quantity nobg"></td>
    <td class="cost nobg"></td>
    <td class="subtotal nobg"></td>
    <td class="gst nobg"></td>
    <td class="mrp greybg text-center">INR {{numberWithDecimal($totalmrp)}}</td>
</tr>
<tr>
    <td class="name text-right">Shipping Costs</td>
    <td class="quantity nobg"></td>
    <td class="cost nobg"></td>
    <td class="subtotal nobg"></td>
    <td class="gst nobg"></td>
    <td class="mrp text-center">INR {{numberWithDecimal($order->shipping_charges)}}</td>
</tr>
@if($order->user_bill_state == 'in_state')
<tr>
    <td class="name text-right">SGST on Shipping Costs in INR</td>
    <td class="quantity nobg"></td>
    <td class="cost nobg"></td>
    <td class="subtotal nobg"></td>
    <td class="gst nobg"></td>
    <td class="mrp text-center">INR {{numberWithDecimal($order->sgst_shipping)}}</td>
</tr>
<tr>
    <td class="name text-right">CGST on Shipping Costs in INR</td>
    <td class="quantity nobg"></td>
    <td class="cost nobg"></td>
    <td class="subtotal nobg"></td>
    <td class="gst nobg"></td>
    <td class="mrp text-center">INR {{numberWithDecimal($order->cgst_shipping)}}</td>
</tr>
@endif
@if($order->user_bill_state == 'out_state')
<tr>
    <td class="name text-right">IGST on Shipping Costs in INR</td>
    <td class="quantity nobg"></td>
    <td class="cost nobg"></td>
    <td class="subtotal nobg"></td>
    <td class="gst nobg"></td>
    <td class="mrp text-center">INR {{numberWithDecimal($order->igst_shipping)}}</td>
</tr>
@endif
<tr>
    <td class="name text-right greybg">Total Shipping Costs (B)</td>
    <td class="quantity nobg"></td>
    <td class="cost nobg"></td>
    <td class="subtotal nobg"></td>
    <td class="gst nobg"></td>
    <td class="mrp greybg text-center">INR {{numberWithDecimal($order->shipping)}}</td>
</tr>
<tr>
    <td class="name text-right greybg">Total Payable (A+B)</td>
    <td class="quantity nobg"></td>
    <td class="cost nobg"></td>
    <td class="subtotal nobg"></td>
    <td class="gst nobg"></td>
    <td class="mrp greybg text-center">INR {{numberWithDecimal($order->total_amount)}}</td>
</tr>
