<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller {
    public function index() {
        $pageTitle = "All Offers";
        $offers    = Offer::withCount('products as total_products')->searchable(['name'])->latest()->paginate(getPaginate());
        return view('admin.offers.index', compact('pageTitle', 'offers'));
    }

    public function create() {
        $pageTitle = "Create New Offer";
        return view('admin.offers.create', compact('pageTitle'));
    }

    public function save(Request $request, $id) {
        $request->validate([
            "offer_name"     => 'required|string|max:40',
            "discount_type"  => 'required|in:1,2',
            "amount"         => 'required|numeric',
            "starts_from"    => 'required|date|date_format:Y-m-d h:i A',
            "ends_at"        => 'required|date|date_format:Y-m-d h:i A|after:starts_from',
            "products"       => 'nullable|array|min:1',
            "products.*"     => 'required_with:products|exists:products,id',
            'banner'         => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'show_banner'    => 'nullable|in:1',
            'show_countdown' => 'nullable|in:1',
        ]);


        if ($request->discount_type == Status::DISCOUNT_PERCENT && $request->amount > 100) {
            $notify[] = ['error', 'Offer amount percentage can\'t be greater than 100%'];
            return back()->withNotify($notify);
        }

        $productsToRemove = [];

        if ($id == 0) {
            $offer    = new Offer();
            $notify[] = ['success', 'Offer created successfully'];
            $productsToAdd = $request->products ?? [];
        } else {
            $offer    = Offer::findOrFail($id);
            $notify[] = ['success', 'Offer updated successfully'];

            $previousProducts = $offer->products->pluck('id')->toArray();
            $newProducts = $request->products ?? [];

            // Find products to remove from offer
            $productsToRemove = array_diff($previousProducts, $newProducts);

            // Find products to add to offer
            $productsToAdd = array_diff($newProducts, $previousProducts);
        }

        if ($request->hasFile('banner')) {
            $oldImage = $offer->banner;
            $offer->banner = fileUploader($request->banner, getFilePath('offerBanner'), getFileSize('offerBanner'), $oldImage);
        }

        $offer->name           = $request->offer_name;
        $offer->discount_type  = $request->discount_type;
        $offer->amount         = $request->amount;
        $offer->starts_from    = $request->starts_from;
        $offer->ends_at        = $request->ends_at;
        $offer->show_banner    = $request->show_banner ? 1 : 0;
        $offer->show_countdown = $request->show_countdown ? 1 : 0;
        $offer->save();

        if (!$id) {
            $offer->unique_key = 'offer_' . $offer->id;
            $offer->save();
        }

        Product::whereIn('id', $productsToRemove)->update(['offer_id' => 0]);
        Product::whereIn('id', $productsToAdd)->update(['offer_id' => $offer->id]);

        return back()->withNotify($notify);
    }

    public function edit($id) {
        $pageTitle = "Edit Offer";
        $offer     = Offer::with(['products'])->findOrFail($id);
        return view('admin.offers.create', compact('pageTitle', 'offer'));
    }

    public function productsForOffer(Request $request) {

        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $products = Product::searchable(['name'])
            ->with([
                'offer',
                'displayImage',
            ])
            ->where(function ($query) {
                $query->where('regular_price', '>', 0)
                    ->orWhereHas('productVariants', function ($q) {
                        return $q->where('regular_price', '>', 0);
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(30)
            ->withQueryString();


        return response()->json($products);
    }

    public function changeStatus(Request $request) {
        $offer         = Offer::findOrFail($request->id);
        $offer->status = !$offer->status;
        $offer->save();
        return successResponse($offer->status ? 'Offer activated successfully' : 'Offer deactivated successfully');
    }
}
