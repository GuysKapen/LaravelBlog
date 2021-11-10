<?php /** @noinspection PhpUndefinedMethodInspection */

/** @noinspection PhpUndefinedFieldInspection */

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubscriberController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            "email" => "required|email|unique:subscribers"
        ]);

        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        if ($subscriber->save()) {
            Toastr::success("Subscribe successfully!", "Succeed");
            return redirect()->back();
        }

        Toastr::error("Failed to subscribe!", "Error");
        return redirect()->back();

    }
}
