<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        $pageTitle  = "All Attributes";
        $attributes = Attribute::searchable(['name'])->withCount('attributeValues')->latest()->paginate(getPaginate());
        return view('admin.attribute.index', compact('pageTitle', 'attributes'));
    }

    public function values($id)
    {
        $attribute  = Attribute::findOrFail($id);
        $pageTitle  = "Values of " . $attribute->name;
        $attributeValues = $attribute->attributeValues()->searchable(['name', 'value'])->latest()->paginate(getPaginate());
        return view('admin.attribute.values', compact('pageTitle', 'attribute', 'attributeValues'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'          => 'required|string',
            'type'          => 'required|in:1,2,3',
        ]);

        if ($id == 0) {
            $attributeType = new Attribute();
            $notification  = 'Attribute type created successfully';
        } else {
            $attributeType = Attribute::findOrFail($id);
            $notification  = 'Attribute type updated successfully';
        }

        $attributeType->name          = $request->name;
        $attributeType->type          = $request->type;
        $attributeType->save();
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }


    function storeValues(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);
        $request->validate([
            "value_id" => "nullable|exists:attribute_values,id",
            'name'  => 'required|string',
            'value' => $this->valueValidationRule($attribute->type),
        ]);

        $attributeValue =  $request->value_id ? AttributeValue::findOrFail($request->value_id) : new AttributeValue();

        if ($attribute->type == Status::ATTRIBUTE_TYPE_IMAGE) {
            $attributeValue->value = $this->uploadAttributeImage($request, $attributeValue->value ?? null);
        } else {
            $attributeValue->value = $request->value;
        }

        $attributeValue->attribute_id = $id;
        $attributeValue->name         = $request->name;
        $attributeValue->save();
        $notify[] = ['success', 'New attribute value added successfully'];
        return back()->withNotify($notify);
    }

    private function valueValidationRule($type)
    {
        if ($type == Status::ATTRIBUTE_TYPE_IMAGE) {
            return ['nullable', 'required_if:value_id,null', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])];
        } elseif ($type == Status::ATTRIBUTE_TYPE_COLOR) {
            return 'required|regex:/^[a-f0-9]{6}$/i';
        } else {
            return 'required|string';
        }
    }

    private function uploadAttributeImage($request, $oldValue)
    {
        if (is_file($request->value)) {
            return fileUploader($request->value, getFilePath('attribute'), getFileSize('attribute'), $oldValue);
        }
        return $oldValue;
    }

    public function status($id)
    {
        return Attribute::changeStatus($id);
    }
}
