<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use File;
class BrandController extends Controller
{
    // retrieve all brands data 
    public function index(Request $request){
        $data = Brand::all();
        return response()->json(["data" => $data]);
    }

    public function store(Request $request){
         $request->validate([
            "name" => ["required","string","max:50"],
            "slug" => ["required","unique:brands"],
            "description" => ["nullable","max:200"],
            "status" => ["nullable","boolean"],
            "image" => ["nullable","image","mimes:jpg,png,gif"],
        ]);
       
         if($request->hasFile("logo")){
            $filename = time() . "." . $request->logo->getClientOriginalName();
            $request->logo->move(public_path("uploads"),$filename);
            $path = "uploads/" . $filename;
         }

         $brand = new Brand();
         $brand->logo = $path;
         $brand->name = $request->name;
         $brand->slug = $request->slug;
         $brand->description = $request->description;
         $brand->save();
         return response()->json(["message" => "Brand Created Successfully", "data" => $brand ]);
    }

    public function show(Request $request, string $id){
        $brand = Brand::findOrfail($id);
        return response()->json(["data" => $brand]);
    }

    public function update(Request $request, string $id){
        $request->validate([
            "name" => ["required","string","max:50"],
            "slug" => ["required"],
            "description" => ["nullable","max:200"],
            "status" => ["nullable","boolean"],
            "image" => ["nullable","image","mimes:jpg,png,gif"],
        ]);
        $brand = Brand::findOrfail($id);

        if($request->hasFile("logo")){
            if(  $brand->logo &&  file_exists( public_path($brand->logo))){
                 unlink(public_path($brand->logo));
            }
            $filename = time() . "." . $request->logo->getClientOriginalName();
            $request->logo->move(public_path("uploads"),$filename);
            $path = "uploads/" . $filename;
        }else{
             $path = $brand->logo;
        }

        $brand->logo = $path;
        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $brand->description = $request->description;
        $brand->save();
        return response()->json(["message" => "Brand Updated Successfully", "data" => $brand ]);

    }

    public function destroy(Request $request, string $id){
         $brand = Brand::findOrfail($id);
         if($brand->logo && file_exists(public_path($brand->logo))){
              unlink(public_path($brand->logo));
         }
         $brand->delete();
         return response(["message" => "Brand Deleted Successfully", "data" => $brand]);
    }
}
