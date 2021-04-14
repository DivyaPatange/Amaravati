<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Service;
use App\Models\Admin\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('status', 'Active')->where('parent_id', NULL)->get();
        // dd($categories);
        $allCategory = Category::orderBy('id', 'DESC')->get();
        if(request()->ajax()) {
            return datatables()->of($allCategory)
            ->addColumn('service_name', function($row){    
                $service = Service::where('id', $row->service_id)->first();
                if(!empty($service))
                {
                    return $service->service_name;
                }                                                                                                                                                                                                                                                                                      
            })
            ->addColumn('parent_id', function($row){    
                $category = Category::where('id', $row->parent_id)->first();
                if(!empty($category))
                {
                    return $category->cat_name;
                }                                                                                                                                                                                                                                                                                      
            })
            ->addColumn('action', 'admin.category.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        $services = Service::where('status', 'Active')->get();
        return view('admin.category.index', compact('services', 'categories'));
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
        $category = new Category();
        $category->service_id = $request->service_name;
        $category->cat_name = $request->category;
        $category->status = $request->status;
        if($request->is_parent == 0)
        {
            $category->parent_id = $request->parent_id;
        }
        $category->save();
        return response()->json(['success' => 'Category Added Successfully!']);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findorfail($id);
        $category->delete();
        return response()->json(['success' => 'Category Deleted Successfully!']);
    }

    public function getCategory(Request $request)
    {
        $category = Category::where('id', $request->bid)->first();
        if (!empty($category)) 
        {
            $data = array('id' =>$category->id, 'service_name' => $category->service_id ,'cat_name' =>$category->cat_name,'status' =>$category->status, 'parent_id' => $category->parent_id
            );
        }else{
            $data =0;
        }
        echo json_encode($data);
    }

    public function updateCategory(Request $request)
    {
        $category = Category::where('id', $request->id)->first();
        if($request->is_parent == 0)
        {
            $parent_id = $request->parent_id;
        }
        else{
            $parent_id = null;
        }
        $input_data = array (
            'service_id' => $request->service_name,
            'cat_name' => $request->category,
            'status' => $request->status,
            'parent_id' => $parent_id,
        );

        Category::whereId($category->id)->update($input_data);
        return response()->json(['success' => 'Category Updated Successfully']);
    }
}
