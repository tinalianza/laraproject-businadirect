import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

import AdyenCheckout from '@adyen/adyen-web';
import '@adyen/adyen-web/dist/adyen.css';

window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();
document.addEventListener('DOMContentLoaded', async () => {
    // Fetch session data from your backend
    const sessionResponse = await fetch('/payment/session');
    const sessionData = await sessionResponse.json();

    const configuration = {
        environment: 'test', // Change to 'live' for the live environment
        clientKey: 'test_SW4ZAO5MH5GI7ELK7C4GVGOZT4GLPWUS', // Replace with your client key
        session: {
            id: sessionData.id,
            sessionData: sessionData.sessionData
        },
        paymentMethodsResponse: {
            paymentMethods: [
                {
                    type: 'gcash',
                    name: 'GCash'
                },
                // Add other payment methods as needed
            ]
        },
        onSubmit: (state, component) => {
            handleSubmission(state);
        },
        onAdditionalDetails: (state, component) => {
            handleAdditionalDetails(state);
        }
    };

    const checkout = new AdyenCheckout(configuration);
    const dropin = checkout.create('dropin', {
        paymentMethodsConfiguration: {
            card: { // Example for card payment method configuration
                hasHolderName: true,
                holderNameRequired: true,
                enableStoreDetails: true,
                hideCVC: false,
                name: 'Credit or debit card'
            },
            gcash: { // Example for GCash payment method configuration
                buttonColor: 'orange' // <-- Removed extra comma here
                // Add specific configuration options for GCash if needed
            }
        }
    });

    dropin.mount('#dropin-container');

    document.getElementById('pay-button').addEventListener('click', () => {
        dropin.submit();
    });
});

function handleSubmission(state) {
    fetch('/payments', {
        method: 'POST',
        body: JSON.stringify(state.data),
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
      .then(data => {
          // Handle server response
      }).catch(error => {
          console.error('Error:', error);
      });
}

function handleAdditionalDetails(state) {
    fetch('/payments/details', {
        method: 'POST',
        body: JSON.stringify(state.data),
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
      .then(data => {
          // Handle server response
      }).catch(error => {
          console.error('Error:', error);
      });
}
