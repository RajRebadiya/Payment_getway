<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <h1>Make a Payment</h1>
    <input type="number" id="amount" placeholder="Enter Amount">
    <button id="payButton">Pay with Razorpay</button>

    <script>
        document.getElementById('payButton').onclick = function () {
            const amount = document.getElementById('amount').value;

            fetch('/payment/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ amount })
            })
            .then(response => response.json())
            .then(data => {
                const options = {
                    "key": data.razorpay_key,
                    "amount": data.amount,
                    "currency": "INR",
                    "order_id": data.order_id,
                    "handler": function (response) {
                        fetch('/payment/verify', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature
                            })
                        })
                        .then(response => response.json())
                        .then(data => alert(data.message));
                    }
                };

                const rzp = new Razorpay(options);
                rzp.open();
            });
        };
    </script>
</body>
</html>
