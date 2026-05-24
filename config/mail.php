<?php
return [
    'driver' => 'smtp', // smtp ou mail
    'host' => getenv('MAIL_HOST') ?? 'smtp.gmail.com',
    'port' => (int)(getenv('MAIL_PORT') ?? 587),
    'username' => getenv('MAIL_USERNAME') ?? 'seu_email@gmail.com',
    'password' => getenv('MAIL_PASSWORD') ?? 'sua_senha_app',
    'encryption' => getenv('MAIL_ENCRYPTION') ?? 'tls', // tls ou ssl
    'from' => [
        'name' => 'Embee Tech',
        'address' => getenv('MAIL_FROM_ADDRESS') ?? 'noreply@embeetech.com',
    ],
];
