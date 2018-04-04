CorezPay (RPC Version)
=======

CorezPay is a PHP class for accepting coreZ (http://corez.site/) payments.
RPC Version: https://github.com/bavamont/CoreZpay
Non-RPC Version: https://github.com/bavamont/CoreZpayNonRPC

### Installation
Edit your .conf file on the server where the core client is installed to allow access from another (or the same) server.

rpcuser=YOUR_USER

rpcpassword=YOUR_PASSWORD

rpcport=PORT_FOR_RPC

rpcallowip=REMOTE_SERVER_IP

Important: Change $payoutWalletAddress to match your payout wallet address in src/CorezPay.php

### Usage
See /examples
Live demo: http://62.77.157.217/coreZ/examples/index.php

### Update
04/04/2018	Initial release

### Donations
Want to help the project? You can donate some CRZ to ZrrEJze7MCA1dQmr8KRfFdugUhxQ28Vrtu