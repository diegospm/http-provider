<?php

$payment = new PaymentExample();

$data = []; // body for api service
$params['headers'] = [
    'Content-Type'  => 'application/json',
    'Authorization' => 'Bearer ' . '<Access-Token>' // autorizathion for api service
];

// try with PayPal
$response = $payment->createOrder('paypal-api', $params, $data);

if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
    // oh no, the PayPal provider attempt failed

    // try with Stripe/other provider
    $response = $payment->createOrder('stripe-api', $params, $data);

    if ($response->getStatusCode() === 200 || $response->getStatusCode() === 201) {
        // \o/, now it worked

        // retrieving response as object
        $result = $response->toObject();

        echo 'The payment id for your order is: '. $result->id;

        exit;
    }

    // oh no, the other provider also failed
    exit('Order processing failed');
}

// \o/, worked with PayPal

// retrieving response as array
$result = $response->toArray();

echo 'The payment id for your order is: '. $result['id'];

exit;
