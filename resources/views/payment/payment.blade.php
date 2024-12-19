<!DOCTYPE html>
<html>
  <head>
    <title>Buy cool new product</title>
    <script src="https://js.stripe.com/v3/"></script>
  </head>
  <body>
    <section>
      <div class="product">
        <div class="description">
          <h3>Stripe Test</h3>
          <h5>$900.00</h5>
        </div>
      </div>
      <form action="{{route('payment.process')}}" method="POST">
        @csrf
        <button type="submit" id="checkout-button">Checkout</button>
      </form>
      {{--  <a href="{{ $charge->url }}" id="checkout-button">Stripe</a>  --}}
    </section>
  </body>
</html>

{{--  stripe 3  --}}

{{--  <!DOCTYPE html>
<html>
<head>
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h1>Stripe Payment</h1>
    <form id="payment-form">
        <div id="card-element">
            <!-- A Stripe Element will be inserted here -->
        </div>
        <button id="submit">Pay</button>
    </form>

    <div id="payment-message" style="display:none;"></div>

    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');

        document.getElementById('submit').addEventListener('click', async (e) => {
            e.preventDefault();

            // Request a PaymentIntent from the server
            const response = await fetch('{{ route('stripe.paymentIntent') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ amount: 20 }), // Replace with dynamic amount
            });
            const { clientSecret } = await response.json();

            // Use Stripe.js to confirm the payment
            const { error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: stripe.elements().create('card').mount('#card-element'),
                },
            });

            const message = document.getElementById('payment-message');
            if (error) {
                message.style.display = 'block';
                message.textContent = error.message;
            } else {
                message.style.display = 'block';
                message.textContent = 'Payment successful!';
            }
        });
    </script>
</body>
</html>  --}}

{{--  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <button id="checkout-button">Pay Now</button>

    <script>
        // Load Stripe with your public key
        const stripe = Stripe("pk_test_51QKDrXGIW7elcKCuWmCyqw2uEy23XFI5J95h5ApZ3Nk0Kmn655FuEx9WbHzNesXB5LlDNLfyHn7miQmaKF5mHLZR00bBsmTIPR");

        const checkoutButton = document.getElementById("checkout-button");

        checkoutButton.addEventListener("click", () => {
            fetch("/payment", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // Include CSRF token for Laravel
                }
            })
            .then(response => response.json())
            .then(data => {
                return stripe.redirectToCheckout({ sessionId: data.id });
            })
            .then(result => {
              console.log(result);
              return;
                if (result.error) {
                    alert(result.error.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    </script>
</body>
</html>
  --}}
