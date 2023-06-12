<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $index = Category::all();
        return response()->json([
            'messages' => 'This is Category',
            'index' => $index
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function allcategory()
    {
        $index = Category::where('status','0')->get();
        return response()->json([
            'status' => 200,
            'messages' => 'Ini Kategori ',
            'index' => $index
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required|max:191',
            'image' => 'required|image|mimes:png,jpg',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        }
        else
        {
        $category = new Category;
        $category -> name = $request -> input('name');
        $category -> image = $request -> input('image');

            if($request->hasFile('image'))
            {
                $file  = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() .'.'.$extension;
                $file->move('uploads/product/' , $filename);
                $category->image = 'uploads/product/' . $filename;
            }
         $category -> save();


        return response()->json([
            'status' => 200,
            'messages' => 'Category Add Successfully',
            'data' => $category,
        ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required|max:191',
            'image' => 'image|mimes:png,jpg|max:2048',
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
            $category = Category::find($id);
             if($category)
             {
                $category -> name = $request -> input('name');

                if($request->hasFile('image'))
                {
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() .'.'.$extension;
                    $file->move('uploads/product/' , $filename);
                    $category->image = 'uploads/product/' . $filename;
                }
                $category->save();
                return response()->json([
                    'status' => 200,
                    'messages' => 'Category Berhasil Di Update',
                    'data' => $category
                ]);
             }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Category::find($id);
        if($data)
        {
            $data->delete();
            return response()->json([
                'status' => 200,
                'messages' => 'Category Deleted Succsfully'
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'meesages' => 'No category ID Found'
            ]);
        }
    }

    public function findcategory($id)
    {
        $data = Category::find($id);
        $data1 = $data->product;
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }
}
