<style>
    td.nobg {
    border-right: none!important;
    border-left: none!important;
}
.greybg{
        font-weight: bold;
}
</style>
<div class="container-fluid add-product show-order">
    <div class="row">
        <div class="col-md-12">	
            <table class="table table-bordered ">
                
                <tr>
                    <td colspan="6"><b>Order ID :</b> #{{ $order->order_no }}
                        <span style='float:right'>
                            <label style="margin-right: 20px;"><b>Order Date</b> : {{ $order->order_date_formatted }} </label>
                            <label><b>Preferred Delivery Date</b> : {{ $order->preferred_delivery_date_formatted }}</label>
                        </span></td>
                </tr>
                
                <?php
                $sub_total = 0;
                ?>
                @foreach($order->product as $item)
                <tr>
                    <th colspan="6">Product : {{$item->pivot->title}}</th>
                </tr>
                <?php
                $ps=$item;
                ?>
                @include('common/orderproductdetail')
                @endforeach
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <table class="table table-bordered">
                <tr>
                    <td colspan="2"><h4>Customer Details : </h4></td>
                </tr>
                <tr>
                    <td>Parent / Guardian Name</td>
                    <td>{{ $order->user->parent_name }}</td>
                </tr>
                <tr>
                    <td>Child Name</td>
                    <td>{{ $order->user->child_name }}</td>
                </tr>
                <tr>
                    <td>Email ID</td>
                    <td><a href="mailto:{{ $order->user->email }}">{{ $order->user->email }}</a></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>{{ $order->user->mobile }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-4 billing-info">
            <table class="table table-bordered">
                <tr>
                    <td colspan="2"><h4>Billing Information : </h4></td>
                </tr>
                <tr>
                    <td>Address1</td>
                    <td>{{ $order->billing_address1 }}</td>
                </tr>
                <tr>
                    <td>Address2</td>
                    <td>{{ $order->billing_address2 }}</td>
                </tr>
                <tr>
                    <td>Area</td>
                    <td>{{ $order->billing_area }}</td>
                </tr>
                <tr>
                    <td>City</td>
                    <td>{{ $order->billing_city }}</td>
                </tr>
                <tr>
                    <td>State</td>
                    <td>{{ $order->billing_state }}</td>
                </tr>


                <tr>
                    <td>Zipcode</td>
                    <td>{{ $order->billing_zip }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-4 shipping-info">
            {!! Form::model($order, ['route' => ['admin.order.update', $order->id], 'method' => 'patch', 'files'=>true]) !!}
            <table class="table table-bordered">
                <tr>
                    <td colspan="2"><h4>Shipping Information : </h4></td>
                </tr>
                <tr>
                    <td>Address1</td>
                    <td>{{ $order->shipping_address1 }}</td>
                </tr>
                <tr>
                    <td>Address2</td>
                    <td>{{ $order->shipping_address2 }}</td>
                </tr>
                <tr>
                    <td>Area</td>
                    <td>{{ $order->shipping_area }}</td>
                </tr>
                <tr>
                    <td>City</td>
                    <td>{{ $order->shipping_city }}</td>
                </tr>
                <tr>
                    <td>State</td>
                    <td>{{ $order->shipping_state }}</td>
                </tr>


                <tr>
                    <td>Zipcode</td>
                    <td>{{ $order->shipping_zip }}</td>
                </tr>
                <tr>
                    <td>Status : </td>
                    <td>
                        {!! Form::select('status_id', $status, $order->status_id, ['class' => 'form-control']) !!}
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <input type="submit" class="btn btn-primary" value="Update Order Status">
                    </td>
                </tr>
            </table>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">	
            <table class="table table-bordered ">
                <tr>
                    <td colspan="4"><h4>Items to Dispatch</h4></td>
                </tr>
                <tr>
                    <th width="55%">Item</th>
                    <th class="text-center" width="15%">Item Code</th>
                    <th class="text-center" width="15%">HSN Code</th>
                    <th class="text-center" width="15%">Quantity</th>
                </tr>
                <?php 
                $books = \App\Models\OrderProductBook::where(['order_product_id' => $ps->pivot->id])->get(); 
                ?>
                @foreach($books as $bk)

                <tr>
                    <td>{{ $bk->name }}</td>
                    <td class="text-center">{{ $bk->book_code }}</td>
                    <td class="text-center">{{ $bk->hsn_code }}</td>
                    <td class="text-center">1</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>    
</div>
