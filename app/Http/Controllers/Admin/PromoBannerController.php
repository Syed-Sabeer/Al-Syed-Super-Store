<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PromotionalBanner;
use App\Models\PromotionalBannerImage;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class PromoBannerController extends Controller
{
    public function index()
    {
        $pageTitle = 'Promotional Banners';
        $banners  = PromotionalBanner::with('images')->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.promo_banners.list', compact('pageTitle', 'banners'));
    }

    public function add()
    {
        $pageTitle = 'Add Promotional Banner';
        return view('admin.promo_banners.add', compact('pageTitle'));
    }

    public function update($id)
    {
        $banner = PromotionalBanner::with('images')->findOrFail($id);
        $pageTitle = "Update Promotional Banner";
        return view('admin.promo_banners.add', compact('pageTitle', 'banner'));
    }

    public function save(Request $request, $id = 0)
    {
        $imageValidation = $id == 0 ? 'required':'nullable';

        $request->validate([
            'title'          => 'required|string',
            'type'           => 'required|in:1,2,3',
            'images'         => "nullable|array",
            'images.*.image' => [$imageValidation, new FileTypeValidate(['png', 'jpg', 'jpeg', 'gif'])],
            'images.*.link'  => 'nullable|string'
        ]);

        $type = $request->type;

        if (!$id && $request->has('images') && count($request->images) != $type) {
            $notify[] = ['error', "Total image should be $type"];
            return back()->withNotify($notify);
        }

        if ($id) {
            $promoBanner = PromotionalBanner::with('images')->findOrFail($id);
            $message = 'Promotional banner updated successfully';
        } else {
            $promoBanner = new PromotionalBanner();
            $max = (PromotionalBanner::max('id') ?? 0) + 1;
            $promoBanner->unique_key = 'banner_' . $max;

            $message = 'Promotional banner added successfully';
        }

        $promoBanner->title = $request->title;
        $promoBanner->type = $type;
        $promoBanner->save();

        $fileKeyName = $promoBanner->fileKeyName();
        $path = getFilePath($fileKeyName);
        $size = getFileSize($fileKeyName);

        foreach ($request->images ?? [] as $key => $imgRequest) {
            $promoBannerImage = $id ? $promoBanner->images->where('id', $key)->first() : null;

            $promoBannerImage = $promoBannerImage ?? new PromotionalBannerImage();
            $promoBannerImage->promotional_banner_id = $promoBanner->id;
            $promoBannerImage->link = $imgRequest['link'] ?? null;

            if (@$imgRequest['image'] && @$imgRequest['image']->isValid()) {
                try {
                    $promoBannerImage->image = fileUploader($imgRequest['image'], $path, $size, @$promoBannerImage->image);
                } catch (\Exception $e) {
                    $notify[] = ['error', 'Couldn\'t upload banner image'];
                    return back()->withNotify($notify);
                }
            }

            $promoBannerImage->save();
        }

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $banner = PromotionalBanner::with('images')->findOrFail($id);

        $fileKeyName = $banner->fileKeyName();
        $path = getFilePath($fileKeyName);

        foreach ($banner->images as $bannerImage) {
            fileManager()->removeFile($path . '/' . $bannerImage->image);
            $bannerImage->delete();
        }

        $home = Page::where('slug', '/')->first();
        $home->removeSection($banner->unique_key);

        $banner->delete();

        $notify[] = ['success', 'The promotional banner deleted successfully'];
        return back()->withNotify($notify);
    }
}
