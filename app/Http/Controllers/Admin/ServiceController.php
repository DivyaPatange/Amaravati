<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Service;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use DB;
use App\Models\Admin\Vendor;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vendors = Vendor::orderBy('id', 'DESC')->get();
        $categories = Category::where('status', 'Active')->get();
        if(request()->ajax()) {
            $service1 = DB::table('services');
            if(!empty($request->vendor)){
                $service1 = $service1->where('vendor_id', $request->vendor);
            }
            if(!empty($request->category))
            {
                $service1 = $service1->where('category_id', $request->category);
            }
            $services = $service1->orderBy('id', 'DESC')->get();
                return datatables()->of($services)
                ->addColumn('service_img', function($row){    
                    if(!empty($row->service_img)){
                        $imageUrl = asset('ServiceImg/' . $row->service_img);
                        return '<img src="'.$imageUrl.'" width="50px">';
                    }                                                                                                                                                                                                                                                                                                 
                })
                ->addColumn('vendor_id', function($row){    
                    $vendor = Vendor::where('id', $row->vendor_id)->first();
                    if(!empty($vendor))
                    {
                        return $vendor->business_owner_name;
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
                ->rawColumns(['service_img', 'vendor_id', 'sub_category_id', 'category_id'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.service.index', compact('categories', 'vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $service->service_name = $request->service_name;
        $service->service_type = $request->service_type;
        $service->status = $request->status;
        $service->save();
        return response()->json(['success' => 'Service Added Successfully!']);
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
        //
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

    public function getService(Request $request)
    {
        $service = Service::where('id', $request->bid)->first();
        if (!empty($service)) 
        {
            $data = array('id' =>$service->id,'service_name' =>$service->service_name,'status' =>$service->status, 'service_type' => $service->service_type
            );
        }else{
            $data =0;
        }
        echo json_encode($data);
    }

    public function updateService(Request $request)
    {
        $service = Service::where('id', $request->id)->first();
        $input_data = array (
            'service_name' => $request->service_name,
            'service_type' => $request->service_type,
            'status' => $request->status,
        );

        Service::whereId($service->id)->update($input_data);
        return response()->json(['success' => 'Service Updated Successfully']);
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
        $service->delete();
        return response()->json(['success' => 'Service Deleted Successfully!']);
    }
}
