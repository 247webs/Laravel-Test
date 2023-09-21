@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Purchase') }}</div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Name</th>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $product->description }}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>${{ number_format(($product->unit_amount / 100), 2, '.', ',') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <h2 class="mt-4">
                        {{ __('Card Details') }}
                    </h2>
                    <form class="checkout-form" method="POST" action="{{ route('product.charge') }}">
                        @csrf
                        <input type="hidden" name="payment_method" class="payment-method">
                        <input type="hidden" name="price" value="{{ $product->unit_amount }}" />
                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                        <input type="hidden" name="product_name" value="{{ $product->name }}" />
                        <div class="mb-3">
                            <input type="text" name="card_holder_name" class="form-control" value="{{ old('card_holder_name') }}" placeholder="Card Holder Name">
                        </div>
                        <div class="mb-3">
                            <div id="card-element"></div>
                        </div>
                        <div class="alert"></div>

                        @if (!Auth::user()->isAdmin())
                            <button type="button" class="btn btn-primary btn-lg w-100">Continue to checkout</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    let stripe = Stripe("{{ env('STRIPE_KEY') }}")
    let elements = stripe.elements()
    let style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    }
    let card = elements.create('card', {style: style})
    card.mount('#card-element')

    let submitButton = $('button.btn-primary')
    submitButton.on('click', function (e) {
        submitButton.attr('disabled', true)
        $('div.alert').removeClass('alert-danger')
        $('div.alert').text("")

        let cardHolderName = $('input[name="card_holder_name"]').val()
        if (!cardHolderName) {
            $('div.alert').addClass('alert-danger')
            $('div.alert').text("Please Add Card holder name!")
            submitButton.removeAttr('disabled')
            return true;
        }

        stripe.confirmCardSetup(
            "{{ $intent->client_secret }}",
            {
                payment_method: {
                    card: card,
                    billing_details: {name: cardHolderName}
                }
            }
        ).then(function (result) {
            if (result.error) {
                $('div.alert').addClass('alert-danger')
                $('div.alert').text(result.error.message)
                submitButton.removeAttr('disabled')
            } else {
                paymentMethod = result.setupIntent.payment_method
                console.log(paymentMethod)
                $('.payment-method').val(paymentMethod)
                $('form.checkout-form').submit()
            }
        })

        return false
    })
</script>
@endsection