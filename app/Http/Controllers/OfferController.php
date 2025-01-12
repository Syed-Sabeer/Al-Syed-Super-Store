<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Page;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Offers';
        $offers = Offer::running()->get();

        $sections = Page::where('tempname', activeTemplate())->where('slug', 'offers')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;

        return view('Template::offers', compact('pageTitle', 'offers', 'sections', 'seoContents', 'seoImage'));
    }

    public function offerProducts($id)
    {
        try {
            $id = decrypt($id);
            $offer = Offer::running()->with('products')->findOrFail($id);
            $pageTitle =   $offer->name . ' - Products';
            return view('Template::offer_products', compact('pageTitle', 'offer'));
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
