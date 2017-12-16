<?php

namespace App\Http\Controllers;

use App\Repositories\Store\StoreInterface;
use App\Repositories\Store\StoreRepository as Store;
use App\Services\Pagination;
use Ecommerce\helperFunctions;
use Illuminate\Http\Request;
use View;
use App\Models\State;
use Session;
use Mail;
use Sentinel;
use Illuminate\Support\Facades\Redirect;

/**
 * Class StoreController.
 *
 */
class StoreController extends Controller {

    public function __construct() {
        View::share('active', 'cart');
    }

    public function index(Request $request) {
        return view('frontend.store.store')->with('cart', 'total');
    }

    public function selectSchool(Request $request) {
        $state = State::orderBy('name', 'asc')->get();

        return view('frontend.store.index', compact('state'))->with('cart', 'total');
    }

    public function selectSchoolPost(Request $request) {
        if ($request->state) {
            Session::put('state', $request->state);
        }
        if ($request->school) {
            Session::put('school', $request->school);
        }
        if ($request->standard) {
            Session::put('standard', $request->standard);
        }
        $messages = [
            'state.required' => 'Please select the State',
            'school.required' => 'Please select the School',
            'standard.required' => 'Please select the Standard',
        ];
        $this->validate($request, [
            'state' => 'required',
            'school' => 'required',
            'standard' => 'required',
                ], $messages);

        if (Sentinel::getUser()) {
            return Redirect::route('store.selectProduct');
        } else {
            return Redirect('/signin');
        }
    }

    public function selectProduct(Request $request) {
        $state = Session::get('state');
        $school = Session::get('school');
        $standard = Session::get('standard');
        if ($state && $school && $standard) {
            $product = \App\Models\Product::where(['school_id' => $school, 'standard_id' => $standard])->active()->get();
            $school = \App\Models\School::find($school);
            $standard = \App\Models\Standard::find($standard);
            return view('frontend.store.selectproduct', compact('school', 'standard', 'product'))->with('cart', 'total');
        } else {
            return Redirect::route('store.selectSchool');
        }
    }

    public function confirm(Request $request) {
        if (!Sentinel::check()) {
            return Redirect::route('signin');
        }
        if ($request->action == 'confirm') {
            if ($request->product) {
                foreach ($request->product as $product_id) {
                    addProductToCart($product_id);
                }
                return Redirect::route('store.confirm');
            } else {
                return Redirect::route('store.selectProduct')->with('error', 'Please select at least one product to proceed.');
            }
        }
        if (getCartCount() < 1) {
            return Redirect::route('store.selectSchool');
        }
        $school = Session::get('school');
        $standard = Session::get('standard');
        $cart_data = \App\Models\Cart::where(['user_id' => Sentinel::getuser()->id])->get();
        $school = \App\Models\School::find($school);
        $standard = \App\Models\Standard::find($standard);
        $shipping_address = getUserAddress('shipping');
        return view('frontend.store.confirm', compact('cart_data', 'school', 'standard', 'shipping_address'))->with('cart', 'total');
    }

    public function cart(Request $request) {
        if (!Sentinel::check()) {
            return Redirect::route('signin');
        }

        if ($request->address1) {
            $data = array(
                'address_type' => 'shipping',
                'address1' => $request->address1,
                'address2' => $request->address2,
                'area' => $request->area,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'added_by' => Sentinel::getuser()->id,
            );
            $user = \App\Models\User::find(Sentinel::getuser()->id);
            if ($user->address()->where('address_type', 'shipping')->count() > 0) {
                $sid = $user->address()->where('address_type', 'shipping')->get();
                $sid = $sid[0]['id'];
                $address = \App\Models\Address::where('id', $sid)->update($data);
            } else {
                $address = \App\Models\Address::create($data);
                $address->users()->attach($user);
            }
            $preferred_delivery_date = parseIndianDate($_POST['preferred_delivery_date']);
            updateCartPreferredDeliveryDate($preferred_delivery_date);
            return Redirect::route('store.cart');
        }

        $cart_data = \App\Models\Cart::where(['user_id' => Sentinel::getuser()->id])->get();
        $orders = \App\Models\Order::where(['user_id' => Sentinel::getuser()->id])->whereRaw('DATE(NOW()) = DATE(order_date)')->get();

        $total_products = count($cart_data) + count($orders);

        return view('frontend.store.cart', compact('cart_data', 'orders', 'total_products'));
    }

    public function pay(Request $request) {
        if (!Sentinel::check()) {
            return Redirect::route('signin');
        }
        if ($request->product_id) {
            $product_id = $request->product_id;
            $ps = \App\Models\Product::find($product_id);
            $key = $ps->company->payment_gateway_key;

            // Merchant key here as provided by Payu

            $MERCHANT_KEY = "zPr3a9nc";
            // Merchant Salt as provided by Payu
            if ($key == $MERCHANT_KEY) {
                $SALT = "QJtl6hKDZG";
            } else {
                // Merchant key here as provided by Payu
                $MERCHANT_KEY = "xoA6hykW";
                // Merchant Salt as provided by Payu
                $SALT = "VutkKcEJrP";
            }

            // End point - change to https://secure.payu.in for LIVE mode
            $PAYU_BASE_URL = "https://secure.payu.in";

            $action = '';
            $formError = 0;


            $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);

            $hash = '';
            // Hash Sequence
            $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";


            $subtotal = 0;
            $totaltax = 0;
            $totalmrp = 0;
            $totalshipping = 0;
            foreach ($ps->books as $book):
                $subtotal += $book->price;
                $totalmrp += $book->price_after_tax;
                $totaltax += $book->price_after_tax - $book->price;
            endforeach;
            $shippingtax = (($ps->shipping_state * getProductItemHighestTax($product_id)) / 100);
            $totalshipping = $shippingtax + $ps->shipping_state;
            $user = \App\Models\User::find(Sentinel::getuser()->id);
            $billing = $user->address()->where('address_type', 'billing')->first();
            $shipping = $user->address()->where('address_type', 'shipping')->first();
            if (!$billing) {
                $billing = $shipping;
            }
            $posted = array('key' => $MERCHANT_KEY,
                'txnid' => $txnid,
                'amount' => $ps->price,
                'firstname' => $user->parent_first_name,
                'lastname' => $user->parent_last_name,
                'address1' => $billing->address1,
                'address2' => $billing->address2,
                'city' => $billing->city,
                'state' => $billing->state,
                'country' => 'IN',
                'zip' => $billing->zip,
                'email' => $user->email,
                'phone' => $user->mobile,
                'productinfo' => $product_id,
                'surl' => url(getLang() . '/store/paysuccess'),
                'furl' => url(getLang() . '/store/payfailure'),
                'service_provider' => 'payu_paisa',
            );
            $hashVarsSeq = explode('|', $hashSequence);
            $hash_string = '';
            foreach ($hashVarsSeq as $hash_var) {
                $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                $hash_string .= '|';
            }

            $hash_string .= $SALT;

            $hash = strtolower(hash('sha512', $hash_string));
            $action = $PAYU_BASE_URL . '/_payment';
            $posted['hash'] = $hash;
            echo '<body onload="closethisasap();">';
            echo '<form name="redirectpost" method="post" action="' . $action . '">';

            if (!is_null($posted)) {
                foreach ($posted as $k => $v) {
                    echo '<input type="hidden" name="' . $k . '" value="' . $v . '"> ';
                }
            }
            echo '<script type="text/javascript">
            function closethisasap() {
                document.forms["redirectpost"].submit();
            }
        </script></body></form>';

//            $ch = curl_init();
//
//            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
//            curl_setopt($ch, CURLOPT_URL, $action);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $posted);
//            curl_setopt($ch, CURLOPT_HEADER, true);
//            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//
//            $result = curl_exec($ch);
//            curl_close($ch);
        }
    }

    public function payfailure(Request $request) {
        $status = $_POST["status"];
        $firstname = $_POST["firstname"];
        $amount = $_POST["amount"];
        $txnid = $_POST["txnid"];
        $posted_hash = $_POST["hash"];
        $key = $_POST["key"];
        $productinfo = $_POST["productinfo"];
        $email = $_POST["email"];
        //$salt = "GQs7yium";
        $MERCHANT_KEY = "zPr3a9nc";
        // Merchant Salt as provided by Payu
        if ($key == $MERCHANT_KEY) {
            $salt = "QJtl6hKDZG";
        } else {
            $salt = "VutkKcEJrP";
        }

        $product_id = $productinfo;
        If (isset($_POST["additionalCharges"])) {
            $additionalCharges = $_POST["additionalCharges"];
            $retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
        } else {

            $retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
        }
        $hash = hash("sha512", $retHashSeq);

        if ($hash != $posted_hash) {
            return Redirect::route('store.cart', ['error' => 'Your transaction was unsuccessful. If your account has been debited, kindly send an email to solutions@jeevandeep.in. Please mention your account email, phone number, and details of the transaction. We  we will look into it immediately.']);
        } else if ($product_id) {
            return Redirect::route('store.cart', ['error' => 'Your transaction was unsuccessful. If your account has been debited, kindly send an email to solutions@jeevandeep.in. Please mention your account email, phone number, and details of the transaction. We  we will look into it immediately.']);
        }
    }

    public function paysuccess(Request $request) {
        $status = $_POST["status"];
        $firstname = $_POST["firstname"];
        $amount = $_POST["amount"];
        $txnid = $_POST["txnid"];
        $posted_hash = $_POST["hash"];
        $key = $_POST["key"];
        $productinfo = $_POST["productinfo"];
        $email = $_POST["email"];
        //$salt = "GQs7yium";
        $MERCHANT_KEY = "zPr3a9nc";
        // Merchant Salt as provided by Payu
        if ($key == $MERCHANT_KEY) {
            $salt = "QJtl6hKDZG";
        } else {
            $salt = "VutkKcEJrP";
        }
        $product_id = $productinfo;
        If (isset($_POST["additionalCharges"])) {
            $additionalCharges = $_POST["additionalCharges"];
            $retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
        } else {
            $retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
        }
        $hash = hash("sha512", $retHashSeq);

        if ($hash != $posted_hash) {
            return Redirect::route('store.cart', ['error' => 'Your transaction was unsuccessful. If your account has been debited, kindly send an email to solutions@jeevandeep.in. Please mention your account email, phone number, and details of the transaction. We  we will look into it immediately.']);
        } else if ($product_id) {
            
            $ps = \App\Models\Product::find($product_id);
            $subtotal = 0;
            $totaltax = 0;
            $totalmrp = 0;
            $totalshipping = 0;
            foreach ($ps->books as $book):
                $subtotal += $book->price;
                $totalmrp += $book->price_after_tax;
                $totaltax += $book->price_after_tax - $book->price;
            endforeach;
            
            $sgst_tax = $cgst_tax = $igst_tax = 0;
            if(userState('billing') == $ps->company->state){
                $sgst_tax = $cgst_tax = $totaltax/2;
                $user_bill_state = 'in_state';
            }else{
                $igst_tax = $totaltax;
                $user_bill_state = 'out_state';
            }
            
            $shippingtax = (($ps->shipping_state * getProductItemHighestTax($product_id)) / 100);
            $sgst_shipping = $cgst_shipping = $igst_shipping = 0;
            if(userState('shipping') == $ps->company->state){
                $sgst_shipping = $cgst_shipping = $shippingtax/2;
                $user_ship_state = 'in_state';
            }else{
                $igst_shipping = $shippingtax;
                $user_ship_state = 'out_state';
            }
            $totalshipping = $shippingtax + $ps->shipping_state;
            $user = \App\Models\User::find(Sentinel::getuser()->id);
            $billing = $user->address()->where('address_type', 'billing')->first();
            $shipping = $user->address()->where('address_type', 'shipping')->first();
            if (!$billing) {
                $billing = $shipping;
            }
            $order = array('user_id' => Sentinel::getuser()->id,
                'amount' => $subtotal,
                'user_bill_state' => $user_bill_state,
                'user_ship_state' => $user_ship_state,
                'sgst_tax' => $sgst_tax,
                'cgst_tax' => $cgst_tax,
                'igst_tax' => $igst_tax,
                'tax' => $totaltax,
                'shipping_charges' => $ps->shipping_state,
                'sgst_shipping' => $sgst_shipping,
                'cgst_shipping' => $cgst_shipping,
                'igst_shipping' => $igst_shipping,
                'shipping' => $totalshipping,
                'total_amount' => $ps->price,
                'status_id' => 1,
                'preferred_delivery_date' => $request->preferred_delivery_date,
                'billing_address1' => $billing->address1,
                'billing_address2' => $billing->address2,
                'billing_area' => $billing->area,
                'billing_city' => $billing->city,
                'billing_state' => $billing->state,
                'billing_zip' => $billing->zip,
                'shipping_address1' => $shipping->address1,
                'shipping_address2' => $shipping->address2,
                'shipping_area' => $shipping->area,
                'shipping_city' => $shipping->city,
                'shipping_state' => $shipping->state,
                'shipping_zip' => $shipping->zip,
            );
            $ord = \App\Models\Order::Create($order);
            $ord_prod = \App\Models\OrderProduct::Create(['order_id' => $ord->id,
                        'product_id' => $ps->id,
                        'qty' => 1,
                        'price' => $ps->price,
                        'school_id'=>$ps->school_id,
                        'standard_id'=>$ps->standard_id,
                        'company_id'=>$ps->company_id,
                        'is_taxable'=>$ps->is_taxable,
                        'title'=>$ps->title,
                        'description'=>$ps->description,
                        'long_description'=>$ps->long_description,
                        'instate_shipping_charges'=>$ps->instate_shipping_charges,
                        'outstate_shipping_charges'=>$ps->outstate_shipping_charges,
                ]);
            
            foreach ($ps->books as $book):
                $ord_prod_book = \App\Models\OrderProductBook::Create(['order_product_id' => $ord_prod->id,
                        'book_id' => $book->id,
                        'medium' => $book->medium,
                        'standard_id'=>$book->standard_id,
                        'company_id'=>$book->company_id,
                        'is_taxable'=>$book->is_taxable,
                        'name'=>$book->name,
                        'description'=>$book->description,
                        'author'=>$book->author,
                        'book_code'=>$book->book_code,
                        'tax'=>$book->tax,
                        'price'=>$book->price,
                        'price_after_tax'=>$book->price_after_tax,
                        'shipping_charges'=>$book->shipping_charges,
                        'quantity'=>$book->product_qty,
                        'weight'=>$book->weight,
                        'hsn_code'=>$book->hsn_code,
                ]);
            endforeach;
            $ord = \App\Models\Order::find($ord->id);

            $template = \App\Models\Email::where(['template' => 'Order'])->get();
            // Send the welcome email
            if ($template) {
                $body = str_replace('<<parent_name>>', $user->parent_first_name . ' ' . $user->parent_last_name, $template[0]->body);

                $body = nl2br($body);
                $body = explode("<<order_details>>", $body);
                $head = explode("\n", $body[0]);
                $foot = explode("\n", $body[1]);
                Mail::send('emails.orders', ['order' => $ord, 'head' => $head, 'foot' => $foot], function ($m) use ($user, $template) {
                    $m->from('noreply@jeevandeep.com', 'Jeevandeep');
                    $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                    $m->subject($template[0]->subject);
                });
            }
            $pdd = getPreferredDeliveryDate($product_id);
            $ord->preferred_delivery_date = $pdd;
            $ord->save();
            $delete_cart = \App\Models\Cart::where('user_id', Sentinel::getuser()->id)->where('product_id', $product_id)->delete();
        }
        return Redirect::route('store.cart', ['success' => '1']);
    }

    public function unavailable_school(Request $request) {
        if ($request->action == 'add') {
            $missing_school = \App\Models\Missingschool::Create(['name' => $request->name,
                        'description' => $request->description]);
            return Redirect::route('store.selectSchool')->with('success', 'Your request to add new school sent successfully.');
        }
        return View('frontend.store.unavailable_school');
    }

    public function unavailable_standard(Request $request) {
        if ($request->action == 'add') {
            $missing_standard = \App\Models\Missingstandard::Create(['name' => $request->name,
                        'description' => $request->description]);
            return Redirect::route('store.selectSchool')->with('success', 'Your request to add new standard sent successfully.');
        }
        return View('frontend.store.unavailable_standard');
    }

    public function cart_delete($id) {
        deleteFromCart($id);
        return \Redirect()->back()->with([
            'success' => 'Product deleted from cart successfully.'
        ]);
    }
    public function product($product_id) {
        $shipping_address = getUserAddress('shipping');
        $ps = \App\Models\Product::find($product_id);
        return view('frontend.store.productdetail', compact('ps','shipping_address'));
    }
    public function orderproduct($order_id) {
        $order = \App\Models\Order::find($order_id);
        $ps = $order->product->first();
        return view('frontend.store.orderproductdetail', compact('ps','order_id','order'));
    }

}
