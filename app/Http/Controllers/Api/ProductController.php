<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validations\GenericValidations;
use App\Models\Product;
use App\Http\Resources\ProductResource;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data=Product::orderBy('id','desc')->get();
            $productList=ProductResource::collection($data);
            return sendResponse(200, 'List Fetched ', $productList);
          } catch (\Illuminate\Database\QueryException $ex) {
            $response = sendError(500, $ex->getMessage(), (object)[]);
            return $response;
          }
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
        try {
            $input = $request->all();
            $id =isset($input['id']) ? $input['id'] : '';
            if($id){
                $product = Product::find($input['id']);
                $product->fill($input);
                $product->save();
                return sendResponse(200, 'Record updated Successfully',new ProductResource ($product));
            }
            else{
             $product= Product::create($input);
             return sendResponse(200, 'Record Created Successfully',new ProductResource ($product));
            }
		 }  catch (\Illuminate\Database\QueryException $ex) {
            $response = sendError(500, $ex->getMessage(), (object)[]);
            return $response;
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
        try {
            $product = Product::where(['id'=>$id])->first();
            if($product==null){
              return sendResponse(400, 'Record Not Found',(object)[]);
            }
            return sendResponse(200, 'Record Fetched',new ProductResource ($product));
            
          } catch (\Illuminate\Database\QueryException $ex) {
            $response = sendError(500, $ex->getMessage(), (object)[]);
            return $response;
          }
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
    public function destroy($id, Request $request)
    {
        try {
            $data = Product::find($id);
            if($data==null){
              return sendResponse(400, 'Record Not Found',(object)[]);
            }
            $data->delete();
            return sendResponse(200, 'Record Deleted Successfully',(object)[]);
          } catch (\Illuminate\Database\QueryException $ex) {
            $response = sendError(500, $ex->getMessage(), (object)[]);
            return $response;
          }
    }
}