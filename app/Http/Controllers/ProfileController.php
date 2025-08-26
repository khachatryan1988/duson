<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Webs\Transaction\Models\Transaction;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the user's orders history.
     */
    public function orders(Request $request): View
    {
        return view('profile.orders', [
            'user' => $request->user()
        ]);
    }

    /**
     * Display the user's order details.
     */
    public function order(Request $request, Order $order): View
    {
        return view('profile.order', [
            'user' => $request->user(),
            'order' => $order
        ]);
    }

    public function payOrder(Request $request, Order $order) {

        if($order->transaction->status != 'success'){
            $transaction = Transaction::create([
                'transaction_id' => strtotime('now'),
                'amount' => $order->total,
                'payment_id' => strtotime('now'),
                'payment_type' => $order->transaction->payment_type,
                'status' => $order->transaction->status
            ]);

            $order->update([
                'transaction_id' => $transaction->id
            ]);

            $getaway = $transaction->pay();

            if($getaway->isSuccessful()){
                $getaway->redirect();
            }
        }

        return redirect()->back();
    }
}
