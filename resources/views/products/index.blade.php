@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{Route('product.index')}}" method="GET" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control" value="{{request()->query('title')}}">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                    <option value="">Select option...</option>
                        <?php
                            $vars = DB::table('variants')->get();
                            foreach($vars as $v){
                                $all_var = DB::table('product_variants')->distinct()
                                ->select('variant')
                                    ->where('variant_id',$v->id)
                                    ->get();
                                    ?>
                                        <option  class="ml-3" disabled> {{$v->title}}</option>
                                    <?php
                                    foreach($all_var as $tt){

                                    ?>
                                        <option @if($tt->variant==request()->query('variant')) selected @endif value="{{$tt->variant}}" class="ml-3">{{$tt->variant}}</option>
                           
                                    <?php
                                }
                            }
                        ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control" value="{{request()->query('price_from')}}">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control" value="{{request()->query('price_to')}}">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control" value="{{request()->query('date')}}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
        @php
            $d = 1;
            if($products->count()==0){
                $d = 0;
            }
            $start = ($products->currentPage()*3-3)+$d;
            $end = $start + $products->count() -$d;
        @endphp

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th >Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($products as $key=>$product)
                        
                   

                    <tr>
                        <td style="width:2%">{{$key+$start}}</td>
                        <td style="width:15%">{{$product->title}} <br> Created at : {{$product->created_at}}</td>
                        <td style="width:35%" >{{$product->description}}</td>
                        <td style="width:45%">
                            <dl class="row mb-0" style="height: 150px; overflow: hidden" id="variant{{$product->id}}">

                                    
                                    <dt class="col-sm-3 pb-0">

                            
                                        @foreach ($product->variants as $variant)
                                            {{-- SM/ Red/ V-Nick --}}
                            
                                             {{$variant->pivot->variant}}{{'/'}}
                                        @endforeach
                                        
                                    </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        @foreach ($product->prices as $price)
                                            
                                            <dt class="col-sm-4 pb-1">Price : {{ number_format($price->price,2) }}</dt>
                                            <dd class="col-sm-8 pb-1">InStock : {{ number_format($price->stock,2) }}</dd>
                                        @endforeach
                                    </dl>
                                </dd>
                            </dl>
                            <button onclick="$('#variant{{$product->id}}').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td style="width:2%" class="mx-0">
                            <div class="btn-group btn-group-sm px-0">
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="">
                <div class="float-left">


                    <p>Showing {{$start}} to {{$end}} out of {{$products->total()}}</p>
                </div>
                <div class="float-right">
                    <?php 
                        $querystringArray = [
                            'title' => request()->query('title'), 
                            'date' => request()->query('date'),
                            'price_from' => request()->query('price_from'),
                            'price_to' => request()->query('price_to'),
                            'variant' => request()->query('variant'),
                            
                        ];
                    ?>
                    {{ $products->appends($querystringArray)->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
