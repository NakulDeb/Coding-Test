<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $idarray = Array();
        $searching_or_not = false;
        $products = Product::with('variants');

        $title = request()->query('title');
        if($title){
            $products = $products->where('title','LIKE', "%{$title}%");
            $searching_or_not = true;
        }

        $variant = request()->query('variant');
        if($variant){
            $products = $products
                ->join('product_variants','products.id', '=', 'product_variants.id')
                ->select('products.*','product_variants.variant')
                ->where('product_variants.variant', $variant);
                // ->get();
                
            
            $searching_or_not = true;
        }

        $price_from = request()->query('price_from');
        $price_to = request()->query('price_to');

        if($price_from && $price_to){
            
            $products = $products
                ->join('product_variant_prices','products.id','=','product_variant_prices.product_id')
                ->select('products.*','product_variant_prices.price')
                ->where('product_variant_prices.price','>=', $price_from)
                ->where('product_variant_prices.price','<=', $price_to)
                ->distinct();
            $searching_or_not = true;

    
        }

        $date = request()->query('date');
        if($date){
            $products = $products->where('products.created_at','LIKE', "%{$date}%");
            $searching_or_not = true;
        }

        if($searching_or_not === true){
            $products = $products->paginate(3);
            return view('products.index')->withProducts($products);
        }
        else{
            $products = Product::with('variants')->paginate(3);
            return view('products.index')->withProducts($products);
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // $product->name
        dd($request->all());
        return response()->json(['success'=>'Successfully product created']);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
