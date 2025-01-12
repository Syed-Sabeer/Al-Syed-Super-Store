<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {
    public function index() {
        $pageTitle  = "All Categories";
        $categories = $this->categoryTree();
        return view('admin.category.form', compact('pageTitle', 'categories'));
    }

    public function trashed() {
        $pageTitle  = "Trashed Categories";
        $categories = Category::searchable(['name'])->onlyTrashed();
        $categories = $categories->with('allSubcategories')->orderBy('deleted_at', 'desc')->paginate(getPaginate());
        return view('admin.category.trashed', compact('pageTitle', 'categories'));
    }

    public function store(Request $request, $id = 0) {
        $validator = $this->validation($request, $id);

        if ($validator->fails()) {
            return errorResponse($validator->errors());
        }

        // Check if parent category exists
        if ($request->parent_id) {

            $parentCategory = Category::with('parent')->where('id', '!=', $id)->find($request->parent_id);

            if (!$parentCategory) {
                return errorResponse('Invalid parent category selected');
            }

            if ($this->getDepthToRoot($parentCategory) >= 5) {
                return errorResponse('You have reached the maximum depth from the root category');
            }
        }

        if ($this->categoryExists($request, $id)) {
            return errorResponse('The name has already been taken');
        }

        $position = Category::where('parent_id', $request->parent_id)->count();

        $category = $id ?  Category::findOrFail($id) : new Category();
        $this->setCategoryAttributes($category, $request);
        $category->position = $position;
        $category->save();

        $message       = $id ? 'updated' : 'added';

        return successResponse("Category $message successfully", [
            'categoryId' => $category->id,
            'name' => $category->name,
            'parentId' => $category->parent_id ?? '#',
            'action' => $message
        ]);
    }

    protected function setCategoryAttributes($category, $request) {
        if ($request->hasFile('image')) {
            $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), $category->image);
        }

        if ($request->hasFile('icon')) {
            $category->icon = fileUploader($request->icon, getFilePath('categoryIcon'), getFileSize('categoryIcon'), $category->icon);
        }

        $category->parent_id          = $request->parent_id;
        $category->name               = $request->name;
        $category->slug               = slug($request->slug);
        $category->meta_title         = $request->meta_title;
        $category->meta_description   = $request->meta_description;
        $category->meta_keywords      = $request->meta_keywords;
        $category->is_featured        = $request->is_featured ?? Status::NO;
        $category->feature_in_banner  = $request->feature_in_banner ?? Status::NO;
    }

    protected function categoryExists(Request $request, $id) {
        return Category::where('id', '!=', $id)->where('name', $request->name)->where('parent_id', $request->parent_id)->exists();
    }

    protected function validation($request, $id) {
        $validator = Validator::make($request->all(), [
            'parent_id'             => 'nullable|integer:gte:0',
            'name'                  => 'required|string',
            'slug'                  => 'required|unique:categories,slug,' . $id,
            'meta_title'            => 'nullable|string',
            'meta_description'      => 'nullable|string',
            'meta_keywords'         => 'nullable|array',
            'meta_keywords.array.*' => 'nullable|string',
            'featured_category'     => 'nullable|integer|between:0,1',
            'filter_menu'           => 'nullable|integer|between:0,1',
            'image'                 => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'icon'                  => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
        ]);

        return $validator;
    }

    protected function categoryTree() {
        return Category::isParent()
            ->with('allSubcategories', function ($q) {
                $q->orderBy('position')->orderBy('name');
            })
            ->orderBy('position')
            ->orderBy('name')
            ->get();
    }

    public function delete(Request $request, $id) {
        $category = Category::where('id', $id)->with('subcategories')->withTrashed()->first();


        if ($category->trashed()) {
            $category->restore();
            $notify[] = ['success', 'Category restored successfully'];
        } else {

            if ($category->subcategories->count()) {
                if ($request->delete_child) {
                    $this->deleteSubCategory($category);
                } else {
                    Category::where('parent_id', $category->id)->update(['parent_id' => null]);
                }
            }

            $category->delete();
            $notify[] = ['success', 'Category deleted successfully'];
        }
        return back()->withNotify($notify);
    }

    private function deleteSubCategory($category) {
        $subCategories = Category::where('parent_id', $category->id)->get();
        if ($subCategories->count()) {
            foreach ($subCategories as $subCategory) {
                $subCat = Category::where('parent_id', $subCategory->id)->get();
                if ($subCat->count()) {
                    $this->deleteSubCategory($subCat);
                }
                $subCategory->delete();
            }
        }
    }

    public function updatePosition(Request $request) {
        $validator = Validator::make($request->all(), [
            'category_id'     => 'required|integer:gt:0',
            'parent_id'       => 'nullable|integer:gt:0',
            'position'        => 'required|int',
            'old_position'    => 'required|int',
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors());
        }

        $category = Category::find($request->category_id);

        if (!$category) {
            return errorResponse('Category not found');
        }

        $category->parent_id = $request->parent_id;
        $category->save();

        $categories = Category::where('parent_id', $request->parent_id)->orderBy('position')->get('id')->pluck('id')->toArray();

        moveElement($categories, $request->old_position, $request->position);

        foreach ($categories as $position => $id) {
            Category::where('id', $id)->update(['position' => $position]);
        }

        return successResponse('Updated');
    }

    function categoryById($id) {
        $category = Category::find($id);
        if ($category) {
            $category->image_path = $category->categoryImage();
            $category->icon_path = $category->categoryIcon();
            return response()->json(['category' => $category]);
        }
        return response('Category not found', '404');
    }


    protected function getDepthToRoot(Category $category) {
        $depth = 0;
        while (!blank($category->parent)) {
            $category = $category->parent;
            $depth++;
        }
        return $depth;
    }

    protected function getDepthFromRoot(Category $category) {
        $maxDepth = 0;

        if ($category->allSubcategories->isNotEmpty()) {
            foreach ($category->allSubcategories as $child) {
                $childDepth = $this->getDepthFromRoot($child);
                $maxDepth = max($maxDepth, $childDepth);
            }
        }

        return $maxDepth + 1;
    }

    public function checkSlug(Request $request, $id) {
        $exists = Category::where('id', '!=', $id)->where('slug', $request->slug)->exists();
        return response([
            'status' => $exists,
        ]);
    }

}
