<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use App\Helpers\Stripe;
use App\Http\Requests\ChargeProductRequest;

class ProductController extends Controller
{
    /**
     * Show the product page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Stripe::init()->products();
        return view('product.product', compact('products'));
    }

    /**
     * Show the purchase view.
     *
     * @param string $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function purchaseView(Request $request, string $id)
    {
        $product = Stripe::init()->products($id);
        $intent = $request->user()->createSetupIntent();
        return view('product.purchase', compact('product', 'intent'));
    }

    /** 
     * Do the purchase
     * 
     * @param ChargeProductRequest $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function purchase(ChargeProductRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $paymentMethod = $request->input('payment_method');

        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $method = $user->attachPaymentMethod($paymentMethod);
            $response = $user->charge((int)$request->price, $paymentMethod)->toArray();
            $response = array_merge($response, [
                'product_id' => $request->product_id,
                'product_name' => $request->product_name,
                'payment_method' => $method->id
            ]);

            $user->addTransction($response);
            switch ($request->product_id) {
                case Constants::PRODUCT_B2B:
                    $user->addRole(Constants::ROLE_B2B);
                    break;
                case Constants::PRODUCT_B2C:
                    $user->addRole(Constants::ROLE_B2C);
                    break;
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('home')->with('success', "Payment completed!");
    }
}
