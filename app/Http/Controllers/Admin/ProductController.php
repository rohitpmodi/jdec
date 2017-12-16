<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Repositories\Product\ProductInterface;
use App\Repositories\Product\ProductRepository as Product;
use App\Services\Pagination;
use Flash;
use Input;
use View;
use Redirect;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductController.
 *

 */
class ProductController extends Controller {

    protected $product;
    protected $perPage;

    public function __construct(ProductInterface $product) {
        $this->product = $product;
        View::share('active', 'blog');
        $this->perPage = 50;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {
        //$pagiData = $this->product->paginate(Input::get('page', 1), $this->perPage, true);
        //$product = Pagination::makeLengthAware($pagiData->items, $pagiData->totalItems, $this->perPage);
        $product = \App\Models\Product::orderBy('id', 'desc');
        $company_id = '';
        if ($request->company_id) {
            $company_id = $request->company_id;
            $product = $product->where('company_id', '=', $company_id);
        }
        $standard_id = '';
        if ($request->standard_id) {
            $standard_id = $request->standard_id;
            $product = $product->where('standard_id', '=', $standard_id);
        }
        $school_id = '';
        if ($request->school_id) {
            $school_id = $request->school_id;
            $product = $product->where('school_id', '=', $school_id);
        }
        $is_taxable = '';
        //dd($request->is_taxable);
        if (($request->is_taxable)) {
            $is_taxabl = ($request->is_taxable == 2) ? 1 : 0;
            $is_taxable = $request->is_taxable;
            $product = $product->where('is_taxable', '=', $is_taxabl);
        }
        $status = '';

        if (($request->status)) {
            $statu = ($request->status == 2) ? 1 : 0;
            $status = $request->status;
            $product = $product->where('status', '=', $statu);
        }

        $title = '';
        if ($request->title) {
            $title = $request->title;
            $product->where('title', 'like', '%' . $title . '%');
        }
        if ($request->delete && $request->delete_product) {
            $this->delete($product, $request->delete_product);
            Flash::message('Product successfully deleted');
            return Redirect::route('admin.product');
        }
        $product = $product->paginate($this->perPage);
        if ($company_id) {
            $product = $product->appends(['company_id' => $company_id]);
        }
        if ($standard_id) {
            $product = $product->appends(['standard_id' => $standard_id]);
        }
        if ($school_id) {
            $product = $product->appends(['school_id' => $school_id]);
        }
        if ($title) {
            $product = $product->appends(['title' => $title]);
        }
        if ($is_taxable) {
            $product = $product->appends(['is_taxable' => $is_taxable]);
        }
        if ($status) {
            $product = $product->appends(['status' => $status]);
        }
        $standard = \App\Models\Standard::orderBy('position', 'asc')->orderBy('id', 'asc')->lists('name', 'id')->toArray();
        $standard = [null => 'Please Select'] + $standard;

        $school = \App\Models\School::lists('name', 'id')->toArray();
        $school = [null => 'Please Select'] + $school;

        $company = \App\Models\Company::orderBy('name', 'asc')->lists('name', 'id')->toArray();
        $company = [null => 'Please Select'] + $company;
        return view('backend.product.index', compact('product', 'title', 'is_taxable', 'status', 'company', 'school', 'school_id', 'standard', 'company_id', 'standard_id'));
    }

    public function book($id) {
        $product = $this->product->find($id);
        $book = \App\Models\Book::select('id',DB::raw("CONCAT(book_code, ' / ',name, ' / ', price_after_tax) as item_name"))->where(['company_id' => $product['company_id'],'is_taxable' => $product['is_taxable']])->active()->lists('item_name', 'id')->toArray();
        $book = [null => 'Please Select'] + $book;
//        /dd($product->books);
        return view('backend.product.book', compact('product', 'book'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $standard = \App\Models\Standard::orderBy('position', 'asc')->orderBy('id', 'asc')->lists('name', 'id')->toArray();
        $standard = [null => 'Please Select'] + $standard;

        $school = \App\Models\School::lists('name', 'id')->toArray();
        $school = [null => 'Please Select'] + $school;

        $company = \App\Models\Company::orderBy('name', 'asc')->lists('name', 'id')->toArray();
        $company = [null => 'Please Select'] + $company;
        return view('backend.product.create', compact('company', 'standard', 'school'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        try {
            $data = Input::all();
            $data['added_by'] = Sentinel::getUser()->id;
            $product = $this->product->create($data);

            Flash::message('Product was successfully added');
            return Redirect::route('admin.product.book', ['id' => $product->id]);
        } catch (ValidationException $e) {
            return Redirect::route('admin.product.create')->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id) {
        $product = $this->product->find($id);

        return view('backend.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id) {
        $product = $this->product->find($id);
        $standard = \App\Models\Standard::orderBy('position', 'asc')->orderBy('id', 'asc')->lists('name', 'id')->toArray();
        $standard = [null => 'Please Select'] + $standard;

        $school = \App\Models\School::lists('name', 'id')->toArray();
        $school = [null => 'Please Select'] + $school;

        $company = \App\Models\Company::orderBy('name', 'asc')->lists('name', 'id')->toArray();
        $company = [null => 'Please Select'] + $company;
        return view('backend.product.edit', compact('product', 'standard', 'company', 'school'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id) {
        try {
            $data = Input::all();
            $data['updated_by'] = Sentinel::getUser()->id;
            $this->product->update($id, $data);
            Flash::message('Product was successfully updated');

            return Redirect::route('admin.product.book', ['id' => $id]);
        } catch (ValidationException $e) {
            return Redirect::route('admin.product.edit', ['id' => $id])->withInput()->withErrors($e->getErrors());
        }
    }

    public function book_update($id) {
        try {
            $data = Input::all();
            $books = \App\Models\ProductBooks::where(['product_id' => $id]);
            $books->delete();
            $array = array();
            foreach ($data['book_id'] as $i => $bid) {
                if (!in_array($bid, $array)) {
                    $array[] = $bid;
                    \App\Models\ProductBooks::create(['book_id' => $bid, 'quantity' => $data['quantity'][$i], 'product_id' => $id]);
                }
            }
            Flash::message('Product was successfully updated');

            return Redirect::route('admin.product');
        } catch (ValidationException $e) {
            return Redirect::route('admin.product.book', ['id' => $id])->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id) {
        $product = $this->product->find($id);
        if ($product->order()->count() > 0) {
            die("Action not allowed");
        }
        $this->product->delete($id);
        Flash::message('Product was successfully deleted');

        return Redirect::route('admin.product');
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function confirmDestroy($id) {
        $product = $this->product->find($id);

        return view('backend.product.confirm-destroy', compact('product'));
    }

    public function copy($id) {


        $product = $this->product->find($id);
        $newProduct = $product->replicate();
        $newProduct->save();
        $newProduct->title = 'Copy of ' . $product->title;
        $newProduct->save();

        $productb = \App\Models\ProductBooks::where(['product_id' => $id])->get();
        $array = array();
        foreach ($productb as $i => $pb) {
            if (!in_array($pb->book_id, $array)) {
                $array[] = $pb->book_id;
                \App\Models\ProductBooks::create(['book_id' => $pb->book_id, 'quantity' => $pb->quantity, 'product_id' => $newProduct->id]);
            }
        }
        Flash::message('Clone successfully generated');

        return Redirect::route('admin.product');
    }

    public function switchstatus(Request $request) {
        $status = ($request->prop == 'true') ? 1 : 0;
        \App\Models\Product::where(['id' => $request->product_id])->update(['status' => $status]);
        //$this->product->update(, ['status'=>$status]);
    }
     private function delete($products, $ids) {
        if ($ids) {
            $products = $products->whereIn('id', explode(',', $ids));
            $products->delete();
        }
    }
}
