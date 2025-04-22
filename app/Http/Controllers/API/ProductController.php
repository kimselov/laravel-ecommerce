<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
     
     
     public function index(Request $request){
        $query = Product::with(['category', 'brand']);

        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
    
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
    
        $products = $query->get();
    
        return response()->json(["data" => $products]);
     }

     public function show(Request $request, string $id){
         $product = Product::with(["category","brand"])->findOrfail($id);
         return response()->json(["data" => $product]);
     }

     public function store(Request $request){
         $request->validate([
            "image" => ["required","image","mimes:jpg,png,gif"],
            "name" => ["required","max:100"],
            "slug" => ["required"],
            "description" => ["nullable","string"],
            "price" => ["decimal:2"],
            "category_id" => ["required"],
            "brand_id" => ["required"],
            "product_type" => ["nullable","string"],
            "quantity" => ["integer"],
         ]);

         if($request->hasFile("image")){
              $filename = time() . "." . $request->image->getClientOriginalName();
              $request->image->move(public_path("uploads"),$filename);
              $path = "uploads/" . $filename;
         }

         $product = new Product();
         $product->image = $path;
         $product->name  = $request->name;
         $product->slug  = $request->slug;
         $product->description = $request->description;
         $product->price = $request->price;
         $product->quantity = $request->quantity;
         $product->category_id = $request->category;
         $product->brand_id = $request->brand_id;
         $product->product_type = $request->product_type;
         $product->save();
         return response(["message" => "Product Created Successfully", "data" => $product]);
     }

     public function update(Request $request, string $id){
        $request->validate([
            "image" => ["image","mimes:jpg,png,gif"],
            "name" => ["required","max:100"],
            "slug" => ["required"],
            "description" => ["nullable","string"],
            "price" => ["decimal:2"],
            "category_id" => ["integer","required"],
            "brand_id" => ["required","integer"],
            "product_type" => ["nullable","string"],
            "quantity" => ["integer"],
         ]);

         $product = Product::findOrfail($id);
         
         if($request->hasFile("image")){
              if($product->image && file_exists(public_path($product->image))){
                  unlink(public_path($product->image));
              }
              $filename = time() . "." . $request->image->getClientOriginalName();
              $request->image->move(public_path("uploads"),$filename);
              $path = "uploads/" . $filename;
         }else{
             $path = $product->image;
         }

         $product->image = $path;
         $product->name  = $request->name;
         $product->slug  = $request->slug;
         $product->description = $request->description;
         $product->price = $request->price;
         $product->quantity = $request->quantity;
         $product->category_id = $request->category_id;
         $product->brand_id = $request->brand_id;
         $product->product_type = $request->product_type;
         $product->save();
         return response(["message" => "Product Updated Successfully", "data" => $product]);

     }

     public function destroy(Request $request, string $id){
         $product = Product::findOrfail($id);
         if($product->image  &&  file_exists($product->image)){
              unlink(public_path($product->image));
         }
         $product->delete();
         return response(["message" => "Product Deleted Successfully","data" => $product]);
     }

}
