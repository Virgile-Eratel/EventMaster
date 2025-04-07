<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Paiement pour l'événement : {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-4">Récapitulatif de votre inscription</h3>
                    <div class="mb-4">
                        <p class="mb-2"><span class="font-bold">Événement :</span> {{ $event->title }}</p>
                        <p class="mb-2"><span class="font-bold">Date :</span> {{ $event->event_date->format('d/m/Y H:i') }}</p>
                        <p class="mb-2"><span class="font-bold">Prix :</span> {{ number_format($event->price, 2, ',', ' ') }} €</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-4">Paiement sécurisé par Stripe</h3>
                    <div id="payment-button-container" class="mt-4">
                        <button id="checkout-button" class="font-bold py-2 px-4 rounded" style="background-color: #3b82f6; color: white;">
                            Procéder au paiement
                        </button>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('event.show', $event) }}" class="text-blue-500 hover:underline">
                        Retour à l'événement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stripe = Stripe('{{ $stripeKey }}');
            const checkoutButton = document.getElementById('checkout-button');

            checkoutButton.addEventListener('click', function() {
                // Rediriger vers Checkout
                stripe.redirectToCheckout({
                    sessionId: '{{ $sessionId }}'
                }).then(function (result) {
                    if (result.error) {
                        // Si la redirection échoue, afficher un message d'erreur
                        alert(result.error.message);
                    }
                });
            });
        });
    </script>
</x-app-layout>
