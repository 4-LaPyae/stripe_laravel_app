<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/stripe_checkout.js') }}" defer></script>
<link href="{{ asset('css/stripe_checkout.css') }}" rel="stylesheet" />
<div class="stripe-container">
  {{ csrf_field() }}
  <form id="payment-form">
  <div id="payment-element">
  <!-- Stripe.js injects the Payment Element here-->
  </div>
  <button id="submit">
  <div class="spinner hidden" id="spinner"></div>
  <span id="button-text">Pay £9.99</span>
  </button>
  <div id="payment-message" class="hidden"></div>
  </form>
</div>