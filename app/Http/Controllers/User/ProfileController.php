<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller {
    public function profile() {
        $pageTitle = "Profile Setting";
        $user = auth()->user();
        return view('Template::user.profile_setting', compact('pageTitle', 'user'));
    }

    public function submitProfile(Request $request) {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'image' => ['nullable', new FileTypeValidate(['png', 'jpg', 'jpeg'])],
        ]);

        $user = auth()->user();

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;

        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;

        if ($request->hasFile('image')) {
            $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), @$user->image);
        }

        $user->save();

        $notify[] = ['success', 'Profile updated successfully'];
        return back()->withNotify($notify);
    }

    public function changePassword() {
        $pageTitle = 'Change Password';
        return view('Template::user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request) {

        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', $passwordValidation],
        ]);

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = ['success', 'Password changed successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }
    }

    public function shippingAddress() {
        $pageTitle = 'Shipping Address';
        $shippingAddresses = ShippingAddress::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(getPaginate());
        $countries = getCountries();
        return view('Template::user.shipping_address', compact('pageTitle', 'shippingAddresses', 'countries'));
    }

    public function saveShippingAddress(Request $request, $id = 0) {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'mobile' => 'required|string',
            'email' => 'required|email',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
            'country' => 'required|string',
            'address' => 'required|string',
            'label' => 'required|string',
        ]);

        if ($id) {
            $shippingAddress = ShippingAddress::where('user_id', auth()->id())->findOrFail($id);
            $notification = 'updated';
        } else {
            $shippingAddress = new ShippingAddress();
            $shippingAddress->user_id = auth()->id();
            $notification = 'added';
        }

        $shippingAddress->label = $request->label;
        $shippingAddress->firstname = $request->firstname;
        $shippingAddress->lastname = $request->lastname;
        $shippingAddress->mobile = $request->mobile;
        $shippingAddress->email = $request->email;
        $shippingAddress->city = $request->city;
        $shippingAddress->state = $request->state;
        $shippingAddress->zip = $request->zip;
        $shippingAddress->country = $request->country;
        $shippingAddress->address = $request->address;
        $shippingAddress->save();

        $notify[] = ['success', "Shipping address $notification successfully"];
        return back()->withNotify($notify);
    }

    public function deleteShippingAddress($id) {
        $address = ShippingAddress::where('user_id', auth()->id())->where('id', $id)->delete();

        if (!$address) {
            abort(404);
        }

        $notify[] = ['success', 'Shipping address deleted successfully'];
        return back()->withNotify($notify);
    }
}
