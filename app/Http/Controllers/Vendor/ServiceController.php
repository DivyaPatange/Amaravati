<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Models\Vendor\Service;
use Auth;
use App\Models\Admin\SubCategory;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::where('vendor_id', Auth::guard('vendor')->user()->id)->get();
        if(request()->ajax()) {
            return datatables()->of($services)
            ->addColumn('service_img', function($row){    
                if(!empty($row->service_img)){
                    $imageUrl = asset('ServiceImg/' . $row->service_img);
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
            ->addColumn('sub_category_id', function($row){    
                $subCategory = SubCategory::where('id', $row->sub_category_id)->first();
                if(!empty($subCategory))
                {
                    return $subCategory->sub_category;
                }                                                                                                                                                                                                                                                                                      
            })
            ->addColumn('description', function($row){   
                if($row->description != Null)
                {
                    return $row->description;
                }
                else{
                    return "";
                }
            })
            ->addColumn('action', 'vendor.service.action')
            ->rawColumns(['action', 'service_img', 'category_id', 'sub_category_id'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('vendor.service.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('status', 'Active')->get();
        return view('vendor.service.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service = new Service();
        $service->vendor_id = Auth::guard('vendor')->user()->id;
        $service->category_id = $request->category;
        $service->sub_category_id = $request->sub_category;
        $service->service_name = $request->service_name;
        $image = $request->file('service_img');
        // dd($request->file('photo'));
        if($image != '')
        {
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('ServiceImg'), $image_name);
            $service->service_img =$image_name;
        }
        $service->service_cost = $request->service_price;
        $service->quantity = $request->quantity;
        $service->description = $request->description;
        $service->save();
        return redirect('/vendors/service')->with('success', 'Service Added Successfully!');
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
        $service = Service::findorfail($id);
        $categories = Category::where('status', 'Active')->get();
        $subCategory = SubCategory::where('category_id', $service->category_id)->where('status', 'Active')->get();
        return view('vendor.service.edit', compact('categories', 'service', 'subCategory'));
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
        $service = Service::findorfail($id);
        $image_name = $request->hidden_image;
        $image = $request->file('service_img');
        if($image != '')
        {
            unlink(public_path('ServiceImg/'.$service->service_img));
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('ServiceImg'), $image_name);
        }

        $input_data = array (
            'sub_category_id' => $request->sub_category,
            'category_id' => $request->category,
            'service_name' => $request->service_name,
            'service_img' => $image_name,
            'service_cost' => $request->service_price,
            'quantity' => $request->quantity,
            'description' => $request->description,
        );
        Service::whereId($id)->update($input_data);
        return redirect('/vendors/service')->with('success', 'Service Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findorfail($id);
        unlink(public_path('ServiceImg/'.$service->service_img));
        $service->delete();
        return response()->json(['success' => 'Service Deleted Successfully!']);
    }
}
