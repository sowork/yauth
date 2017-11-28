<?php

namespace Sowork\YAuth\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Sowork\YAuth\Http\Requests\StoreItemPost;
use Sowork\YAuth\Facades\YAuth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('yauth::items/index');
    }

    public function search(){
        $yauth = YAuth::getItems();
        return response()->json($yauth);
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
     * @param  \Sowork\YAuth\Http\Requests\StoreItemPost  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemPost $request)
    {
        //
        if($request->input('item_type') == 2){
            $item = YAuth::createPermission($request->input('item_name'));
        }else{
            $item = YAuth::createRole($request->input('item_name'));
        }
        $item->item_desc = $request->get('item_desc') ?? '';
        try{
            $isError = false;
            $isError = YAuth::add($item);
        }catch (\Exception $e){
        }finally{
            return response()->json([
                'isError' => !$isError,
                'msg' => $isError ? '操作成功' : '操作失败'
            ]);
        }
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
        //
    }
}
