import AdyenCheckout from '@adyen/adyen-web';
import '@adyen/adyen-web/dist/adyen.css';

document.addEventListener('DOMContentLoaded', function() {
    fetch('/make-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(sessionData => {
        console.log('Session Data:', sessionData);

        const configuration = {
            environment: 'test', // or 'live' for production
            clientKey:ADYEN_CLIENT_KEY,
            session: sessionData.sessionId,
            onPaymentCompleted: (result, component) => {
                console.log('Payment Completed:', result, component);
                window.location.href = '/payment/return';
            },
            onError: (error, component) => {
                console.error('Error:', error.name, error.message, error.stack, component);
                alert('An error occurred. Please try again later.');
            },
            paymentMethodsConfiguration: {
                gcash: { // Configuration for GCash payment method
                    name: 'gcash',
                    countryCode: 'PH',
                    amount: {
                        value: 1000, // Adjust amount as needed
                        currency: 'PHP'
                    }
                },
                card: {
                    hasHolderName: true,
                    holderNameRequired: true,
                    billingAddressRequired: true
                }
                // Add configurations for other payment methods if needed
            }
        };

        const checkout = new AdyenCheckout(configuration);
        checkout.create('dropin').mount('#payment-container');
    })
    .catch(error => {
        console.error('Error fetching payment session:', error);
        alert('Failed to fetch payment session. Please try again later.');
    });
});
