<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\EmptyResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public static function getSlug($slug)
    {
        $index = 1;
        $new_slug = $slug;
        if (Category::where('slug', $slug)->count()) {
            while (Category::where('slug', $new_slug)->count()) {
                $new_slug = $slug . '-' . $index;
                $index++;
            }
        }
        return $new_slug;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        return CategoryResource::collection(
            Category::where('category_name', 'LIKE', '%' . $request->search . '%')
                ->get()
        );
    }
    public function getRandomly()
    {
        // return CategoryResource::collection(Category::limit(6)->get());
        return CategoryResource::collection(Category::inRandomOrder()->limit(6)->get());
    }

    public function getDetails($slug)
    {
        $cat[] = Category::where('slug', $slug)->first();
        return new CategoryResource($cat);
    }
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->image;
            $imageName = $image->getClientOriginalName();
            $imageName = time() . '_' . $imageName;
            $image->move(public_path('/images/categories_images'), $imageName);
        } else {
            $imageName = 'default.jpg';
        }
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->category_image = $imageName;
        $category->slug =
            self::getSlug(Str::slug($request->category_name, '-'));

        $category->save();

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        Log::info($request->all());
        $path = public_path() . '/images/categories_images/';
        if ($request->new_image != 'null' && $request->new_image != 'default.jpg') { //code for remove old image
            $file_old = $path . $request->category_image;
            unlink($file_old);

            //code for add new image
            $image = $request->new_image;
            $imageName = $image->getClientOriginalName();
            $imageName = time() . '_' . $imageName;
            $image->move(public_path('/images/categories_images/'), $imageName);
        } else {
            $imageName = $request->category_image;
        }
        $category->update([
            'category_name' => $request->category_name,
            'category_image' => $imageName,
            'slug' =>  self::getSlug(
                Str::slug($request->category_name, '-')
            )
        ]);

        return new EmptyResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->noContent();
    }
}
