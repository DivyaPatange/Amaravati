<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\Service;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('vendor_id', Auth::guard('vendor')->user()->id)->get();
        if(request()->ajax()) {
            return datatables()->of($products)
            ->addColumn('product_img', function($row){    
                if(!empty($row->product_img)){
                    $imageUrl = asset('ProductImg/' . $row->product_img);
                    return '<img src="'.$imageUrl.'" width="50px">';
                }                                                                                                                                                                                                                                                                                      
            })
            ->addColumn('category_id', function($row){    
                $category = Category::where('id', $row->category_id)->first();
                if(!empty($category))
                {
                    return $category->cat_name;
                }                                                                                                                                                                                                                                                                                      
            })
            ->addColumn('service', function($row){    
                $service = Service::where('id', $row->service_id)->first();
                if(!empty($service))
                {
                    return $service->service_name;
                }                                                                                                                                                                                                                                                                                      
            })
            ->addColumn('status', function($row){
                if($row->status == "In-Stock")
                {
                    return '<span class="badge badge-success">'.$row->status.'</span>';
                }  
                else{
                    return '<span class="badge badge-danger">'.$row->status.'</span>';
                }                                                                                                                                                                                                                                                                                    
            })
            ->addColumn('action', 'admin.category.action')
            ->rawColumns(['action', 'product_img', 'status', 'service'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('vendor.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendor = explode(",", Auth::guard('vendor')->user()->services);    
        return view('vendor.product.create', compact('vendor'));
    }

    public function getCategoryList(Request $request)
    {
        $category = Category::where("service_id", $request->service_id)->where('status', 1)
        ->pluck("cat_name","id");
        return response()->json($category);
    }

    public function getParentCategory(Request $request)
    {
        $category = Category::where('parent_id', $request->category_id)->where('status', 1)->get();
        $output = "";
        // dd($subCategory);
        if(count($category) > 0)
        {

            $output.='<div class="form-group">
                <label for="parent_cat">Parent Category</label> <span  style="color:red" id="parent_cat_err"> </span>
                <select class="form-control js-example" name="parent_cat" id="parent_cat">
                    <option value="">Choose</option>';
                    foreach($category as $s){
                    $output.='<option value="'.$s->id.'">'.$s->cat_name.'</option>';
                    }
                $output.='</select>
            </div>';
            return $output;
        }
        else{
            $output = "";

            return $output;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product();
        $product->vendor_id = Auth::guard('vendor')->user()->id;
        $product->service_id = $request->service;
        $product->category_id = $request->category;
        if($request->parent_cat != ""){
        $product->parent_cat_id = $request->parent_cat;
        }
        $product->product_name = $request->product_name;
        $image = $request->file('product_img');
        // dd($request->file('photo'));
        if($image != '')
        {
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('ProductImg'), $image_name);
            $product->product_img =$image_name;
        }
        $product->selling_price = $request->selling_price;
        $product->cost_price = $request->cost_price;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();
        return redirect('/vendors/product')->with('success', 'Product Added Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findorfail($id);
        return view('vendor.product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
