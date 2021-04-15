<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Service;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service = Service::orderBy('id', 'DESC')->get();
        if(request()->ajax()) {
            return datatables()->of($service)
            ->addColumn('action', 'admin.service.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.service.index');
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
