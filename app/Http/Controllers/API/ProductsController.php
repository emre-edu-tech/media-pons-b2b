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

        if($request->category) {
            $slug = $request->category;
            if($request->price) {
                if($request->price == 'low_high') {
                    return $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
                        $query->where('slug', $slug);
                    })->orderBy('regular_price', 'ASC')->paginate(3);
                } else if($request->price == 'high_low') {
                    return $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
                        $query->where('slug', $slug);
                    })->orderBy('regular_price', 'DESC')->paginate(3);
                }
            } else {
                return $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
                    $query->where('slug', $slug);
                })->paginate(3);
            }
        } else if ($request->price) {
            if($request->price == 'low_high') {
                return Product::orderBy('regular_price', 'ASC')->paginate(3);
            } else if($request->price == 'high_low') {
                return Product::orderBy('regular_price', 'DESC')->paginate(3);
            }
        } else {
            return Product::orderBy('name', 'ASC')->paginate(3);
        }

        // if(!$slug) {
        //     // sort the products according to sort query string
        //     if($request->sort == 'low_high') {
        //         return Product::orderBy('regular_price', 'ASC')->paginate(3);
        //     } else if($request->sort == 'high_low') {
        //         return Product::orderBy('regular_price', 'DESC')->paginate(3);
        //     } else {
        //         return Product::orderBy('name', 'ASC')->paginate(3);
        //     }
        // } else {
        //     // sort the products according to sort query string
        //     if($request->sort == 'low_high') {
        //         $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
        //             $query->where('slug', $slug);
        //         })->orderBy('regular_price', 'ASC')->paginate(3);
        //     } else if($request->sort == 'high_low') {
        //         $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
        //             $query->where('slug', $slug);
        //         })->orderBy('regular_price', 'DESC')->paginate(3);
        //     } else {
        //         return ['message' => 'here'];
        //         // Get the products according to categories (slug)
        //         $products = Product::with('categories')->whereHas('categories', function($query) use ($slug) {
        //             $query->where('slug', $slug);
        //         })->paginate(3);
        //     }
        // }

        // get category name
        // $categoryName = Category::where('slug', $slug)->firstOrFail()->name;

        // return [
        //     'products' => $products,
        //     'categoryName' => $categoryName ? $categoryName : '',
        // ];
    }

    public function getAllCategories() {
        return Category::where('parent_id', NULL)->get();
    }
}
