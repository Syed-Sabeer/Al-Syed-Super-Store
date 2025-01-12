<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\ProductCollection;
use App\Models\Page;
use App\Models\Product;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductCollectionController extends Controller
{
    public function index()
    {
        $pageTitle = 'Product Collections';
        $collections = ProductCollection::orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.collection.list', compact('pageTitle', 'collections'));
    }

    public function create()
    {
        $pageTitle = 'Create Product Collection';
        return view('admin.collection.create', compact('pageTitle'));
    }

    public function update($id)
    {
        $collection = ProductCollection::findOrFail($id);
        $pageTitle = 'Update Product Collection';
        return view('admin.collection.create', compact('pageTitle', 'collection'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'title' => 'required',
            'products' => 'nullable|array|min:1',
            'banner_position' => 'required|in:left,right',
            'products.*' => ['required', Rule::exists('products', 'id')->where('is_published', Status::YES)],
            'banner' => ['nullable', new FileTypeValidate(['png', 'jpg', 'jpeg'])]
        ]);

        if ($id) {
            $collection = ProductCollection::findOrFail($id);
            $message    = 'The collection updated successfully';
        } else {
            $collection = new ProductCollection();
            $collection->unique_key = 'collection_' . (ProductCollection::max('id') ?? 0) + 1;

            $message = 'Collection created successfully';
        }

        $collection->title = $request->title;
        $collection->product_ids = $request->products??[];
        $collection->banner_position = $request->banner_position;

        if ($request->hasFile('banner')) {
            try {
                $collection->banner = fileUploader($request->banner, getFilePath('collection'), getFileSize('collection'), @$collection->banner);
            } catch (\Exception $e) {
                $notify[] = ['error', 'Couldn\'t upload collection image'];
                return back()->withNotify($notify);
            }
        }

        $collection->save();

        $notify[] = ['success', $message];
        return to_route('admin.collection.update', $collection->id)->withNotify($notify);
    }

    public function delete($id)
    {
        $collection = ProductCollection::findOrFail($id);
        $home = Page::where('slug', '/')->first();
        $home->removeSection($collection->unique_key);

        if ($collection->banner) {
            fileManager()->removeFile(getFilePath('collection') . '/' . $collection->banner);
        }

        $collection->delete();

        $notify[] = ['success', 'The collection deleted successfully'];
        return back()->withNotify($notify);
    }

    public function deleteBanner($id)
    {
        $collection = ProductCollection::findOrFail($id);

        if ($collection->banner) {
            fileManager()->removeFile(getFilePath('collection') . '/' . $collection->banner);
        }

        $collection->banner = null;
        $collection->save();

        $notify[] = ['success', 'Banner deleted successfully'];
        return back()->withNotify($notify);
    }


    public function products(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

       $products  = Product::searchable(['name'])
            ->select(['id', 'brand_id', 'name', 'regular_price', 'sale_price', 'offer_id', 'sale_starts_from', 'sale_ends_at', 'main_image_id', 'in_stock'])
            ->published()
            ->filter(['brand_id', 'is_showable', 'product_type'])
            ->orderBy('id', 'desc')
            ->with([
                'displayImage',
                'categories:id,parent_id,name',
                'brand:id,name',
                'productVariants:id,product_id,regular_price,sale_price',
            ])
            ->paginate(30)
            ->withQueryString();

        return response()->json($products);
    }
}
