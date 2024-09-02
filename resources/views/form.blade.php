<!DOCTYPE html>
<html>
<head>
    <title>PayMongo Payment</title>
</head>
<body>
    <form id="payment-form">
        <input type="text" id="payment-method-id" placeholder="Payment Method ID" required>
        <input type="number" id="amount" placeholder="Amount" required>
        <button type="submit">Pay</button>
    </form>

    <script src="https://js.paymongo.com/v1/paymongo.js"></script>
    <script>
        document.getElementById('payment-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const amount = document.getElementById('amount').value;
            const paymentMethodId = document.getElementById('payment-method-id').value;

            const response = await fetch('/payment/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    amount: amount,
                    payment_method_id: paymentMethodId
                }),
            });

            const data = await response.json();
            if (data.status === 'success') {
                console.log(data.client_secret);
            } else {
                console.error(data.error);
            }
        });
    </script>
</body>
</html>
