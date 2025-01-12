<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class PageBuilderController extends Controller
{

    public function managePages()
    {
        $pData = Page::where('tempname',activeTemplate())->get();
        $pageTitle = 'Manage Pages';
        return view('admin.frontend.builder.pages', compact('pageTitle','pData'));
    }

    public function managePagesSave(Request $request){

        $request->validate([
            'name' => 'required|min:3|string|max:40',
            'slug' => 'required|min:3|string|max:40',
        ]);

        $exist = Page::where('tempname', activeTemplate())->where('slug', slug($request->slug))->exists();

        if($exist){
            $notify[] = ['error', 'This page already exists on your current template. Please change the slug.'];
            return back()->withNotify($notify);
        }

        $page = new Page();
        $page->tempname = activeTemplate();
        $page->name = $request->name;
        $page->slug = slug($request->slug);
        $page->save();
        $notify[] = ['success', 'New page added successfully'];
        return back()->withNotify($notify);
    }

    public function managePagesUpdate(Request $request){

        $page = Page::where('id',$request->id)->firstOrFail();
        $request->validate([
            'name' => 'required|min:3|string|max:40',
            'slug' => 'required|min:3|string|max:40'
        ]);

        $slug = slug($request->slug);

        $exist = Page::where('tempname', activeTemplate())->where('slug',$slug)->first();
        if(($exist) && $exist->slug != $page->slug){
            $notify[] = ['error', 'This page already exist on your current template. please change the slug.'];
            return back()->withNotify($notify);
        }

        $page->name = $request->name;
        $page->slug = slug($request->slug);
        $page->save();

        $notify[] = ['success', 'Page updated successfully'];
        return back()->withNotify($notify);

    }

    public function checkSlug($id = null){
        $page = Page::where('tempname', activeTemplate())->where('slug',request()->slug);
        if ($id) {
            $page = $page->where('id','!=',$id);
        }
        $exist = $page->exists();
        return response()->json([
            'exists'=>$exist
        ]);
    }

    public function managePagesDelete($id){
        $page = Page::where('id',$id)->where('is_default',Status::NO)->firstOrFail();
        $page->delete();
        $notify[] = ['success', 'Page deleted successfully'];
        return back()->withNotify($notify);
    }



    public function manageSection($id)
    {
        $pData = Page::findOrFail($id);
        $pageTitle = 'Manage Section of '.$pData->name;
        $sections =  getPageSections(true);
        $sections = collect($sections)->where('no_selection', false)->whereIn('page', [$pData->page_name, '']);
        return view('admin.frontend.builder.index', compact('pageTitle','pData','sections'));
    }



    public function manageSectionUpdate($id, Request $request)
    {
        $request->validate([
            'secs' => 'nullable|array',
        ]);

        $page = Page::findOrFail($id);
        if (!$request->secs) {
            $page->secs = null;
        }else{
            $page->secs = json_encode($request->secs);
        }
        $page->save();
        $notify[] = ['success', 'Page sections updated successfully'];
        return back()->withNotify($notify);
    }


    public function manageSeo($id){
        $page = Page::findOrFail($id);
        $pageTitle = 'SEO Configuration for '.$page->name .' Page';
        return view('admin.frontend.builder.seo', compact('pageTitle','page'));
    }

    public function manageSeoStore(Request $request, $id){
        $request->validate([
            'image'=>['nullable',new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);

        $page = Page::findOrFail($id);
        $image = @$page->seo_content->image;
        if ($request->hasFile('image')) {
            try {
                $path = getFilePath('seo');
                $image = fileUploader($request->image,$path, getFileSize('seo'), @$page->seo_content->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the image'];
                return back()->withNotify($notify);
            }
        }
        $page->seo_content = [
            'image'=>$image,
            'description'=>$request->description,
            'social_title'=>$request->social_title,
            'social_description'=>$request->social_description,
            'keywords'=>$request->keywords ,
        ];
        $page->save();

        $notify[] = ['success', 'SEO content updated successfully'];
        return back()->withNotify($notify);
    }
}
