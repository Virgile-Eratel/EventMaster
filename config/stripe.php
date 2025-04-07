<?php

return [
    'key' => env('STRIPE_KEY', 'pk_test_51RBBasQ4ySMgsFxSGlMVQeVpwxywUYkNiSZknfqKOoDaalchKEu7AGrwPXmRX96rTSVJwxBjqrnWv9mpADZnX8k200YV0c0k31'),
    'secret' => env('STRIPE_SECRET', 'sk_test_51RBBasQ4ySMgsFxSKw7mhgOGuME46BFgpEBuS5Ek2f1rKKT6ByGKg6ZhszZ74tvQA0u7xAKyUJKkka38d9AfKb3A00vNBlR2Di'),
    'webhook' => [
        'secret' => env('STRIPE_WEBHOOK_SECRET', ''),
        'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
    ],
];
