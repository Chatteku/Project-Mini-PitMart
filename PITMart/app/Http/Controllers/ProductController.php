<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::all();
        return response()->json([
            'messages' => 'Data YG Tersedia',
            'Data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'category_id' => 'required|max:191',
            'name' => 'required|max:191',
            'price' => 'required|max:191',
            'description' => 'required|max:191',
            'qty' => 'required|max:191',
            'brand' => 'required|max:191',
            'category' => 'required|max:191',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 401,
                'messages' => $validator->messages(),
            ]);
        }
        else
        {
            $data = new Product;
            $data -> category_id = $request -> input('category_id');
            $data -> name = $request -> input('name');
            $data -> price = $request -> input('price');
            $data -> description =$request->input('description');
            $data -> qty = $request->input('qty');
            $data -> category = $request->input('category');
            $data -> brand =$request->input('brand');

            if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() .'.'.$extension;
                $file->move('uploads/product/' , $filename);
                $data->image = 'uploads/product/' . $filename;
            }

            $data->save();
            return response()->json([
                'status' => 200,
                'messages' => 'Data Berhasil Ditambahkan'
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all() , [
            'category_id' => 'required|max:191',
            'name' => 'required|max:191',
            'price' => 'required|max:191',
            'description' => 'required|max:191',
            'qty' => 'required|max:191',
            'brand' => 'required|max:191',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => 404,
                'errors' => $validator->messages()
            ]);
        }
        else
        {
            $product = Product::find($id);
            if($product)
            {
                $product -> category_id = $request->input('category_id');
                $product -> name = $request->input('name');
                $product -> price = $request->input('price');
                $product -> description = $request->input('description');
                $product -> qty = $request->input('qty');
                $product -> brand = $request->input('brand');
                $product -> image = $request->file('image');
                 $result = CloudinaryStorage::upload($product->getRealPath() , $product->getClientOriginalName());
                 Image::create([
                    'name' => $request->name,
                    'image' => $result,
                 ]);
            }
            // if($request->hasFile('image'))
            // {
            //     $file = $request->file('image');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename = time() .'.'.$extension;
            //     $file->move('uploads/product/' , $filename);
            //     $product->image = 'uploads/product/' . $filename;
            // }

            $product->save();
            return response()->json([
                'status' => 200,
                'messages' => 'Data Berhasil Di Ubah'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Product::find($id);
        if($data)
        {
            $data->delete();
            return response()->json([
                'status' => 200,
                'messages' => 'data dgn id tersebut sudah di hapus',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => 'data gagal terhapus'
            ]);
        }
    }

    public function findproduct($id)
    {
        $data = Product::find($id);
        return response()->json([
            'data' => $data
        ]);
    }
}
