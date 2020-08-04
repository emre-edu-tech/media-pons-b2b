<?php

namespace App\Http\Controllers\API;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    // added for api security
    // allows only javascript applications with JWT to access api route
    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
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
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return $product = Product::where('slug', $slug)->firstOrFail();
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

    // Custom functions
    public function getProductsByCategory(Request $request) {

        $pagination = 3;
        $products = NULL;
        $categoryName = NULL;

        if($request->category) {
            $slug = $request->category;
            // get category name
            $categoryName = optional(Category::where('slug', $slug)->first())->name;
            if($request->price) {
                if($request->featured) {
                    if($request->price == 'low_high') {
                        $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
                            $query->where('slug', $slug)->where('featured', 1);
                        })->orderBy('regular_price', 'ASC')->paginate($pagination);
                    } else if($request->price == 'high_low') {
                        $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
                            $query->where('slug', $slug)->where('featured', 1);
                        })->orderBy('regular_price', 'DESC')->paginate($pagination);
                    }           
                } else {
                    if($request->price == 'low_high') {
                        $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
                            $query->where('slug', $slug);
                        })->orderBy('regular_price', 'ASC')->paginate($pagination);
                    } else if($request->price == 'high_low') {
                        $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
                            $query->where('slug', $slug);
                        })->orderBy('regular_price', 'DESC')->paginate($pagination);
                    }
                }
            } else {
                if($request->featured) {
                    $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
                        $query->where('slug', $slug)->where('featured', 1);
                    })->paginate($pagination);    
                } else {
                    $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
                        $query->where('slug', $slug);
                    })->paginate($pagination);
                }
            }
        } else if ($request->price) {
            if($request->featured) {
                if($request->price == 'low_high') {
                    $products = Product::where('featured', 1)->orderBy('regular_price', 'ASC')->paginate($pagination);
                } else if($request->price == 'high_low') {
                    $products = Product::where('featured', 1)->orderBy('regular_price', 'DESC')->paginate($pagination);
                }    
            } else {
                if($request->price == 'low_high') {
                    $products = Product::orderBy('regular_price', 'ASC')->paginate($pagination);
                } else if($request->price == 'high_low') {
                    $products = Product::orderBy('regular_price', 'DESC')->paginate($pagination);
                }
            }
        } else {
            if($request->featured) {
                $products = Product::where('featured', 1)->orderBy('name', 'ASC')->paginate($pagination);
                $categoryName = 'Featured';    
            } else {
                $products = Product::orderBy('name', 'ASC')->paginate($pagination);
                $categoryName = 'All Products';
            }
        }

        return [
            'products' => $products,
            'categoryName' => $categoryName ? $categoryName : 'Not a valid category',
        ];
    }

    public function getAllCategories() {
        return Category::where('parent_id', NULL)->get();
    }
}
