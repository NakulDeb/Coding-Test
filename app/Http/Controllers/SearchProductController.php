<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        print("index");
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
   
        $result = DB::table('products');
        if($request->title){
            $result = $result->where('title','LIKE', "%{$request->title}%");
        }
        if($request->price_from && $request->price_to){
            $from = $request->price_from;
            $to = $request->price_to;


            $result = $result
            ->join('product_variant_prices','products.id','=','product_variant_prices.product_id')
            ->select('products.*','product_variant_prices.price')
                ->where('product_variant_prices.price','>', $from)
                ->where('product_variant_prices.price','<', $to);

        }
        if($request->date){
            // dd($request->date);
            $result = $result->where('created_at','>', $request->date);
        }

        $result = $result->paginate(5);
        // dd($result);
        return view('products.index')->withProducts($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $porduct = Product::findOrFail($id);
        dd($id);
        return view('')->withProduct($product);
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
