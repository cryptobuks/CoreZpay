CorezPay (Non-RPC Version) - https://github.com/bavamont/corezpay
=======

CorezPay is a PHP class for accepting coreZ (http://corez.site/) payments.

### Installation
Installation on Ubuntu 16.04
Using putty (https://www.putty.org/) log in as root.

sudo apt-get update

sudo apt-get install software-properties-common pwgen nano git unzip

sudo apt-get install build-essential libtool autotools-dev automake pkg-config libssl-dev libevent-dev bsdmainutils

sudo apt-get install libboost-system-dev libboost-filesystem-dev libboost-chrono-dev libboost-program-options-dev libboost-test-dev libboost-thread-dev

sudo add-apt-repository ppa:bitcoin/bitcoin

sudo apt-get update

sudo apt-get install libdb4.8-dev libdb4.8++-dev

git clone https://github.com/corezcrypto/corez

cd corez

./autogen.sh 

./configure

make

make install

Make sure that www-data has permission to execute coreZ on your server or it will not work (you will receive a "Permission denied" error).
You may need to edit/change the $execCommand in src/CorezPay.php

Important: Change $payoutWalletAddress to match your payout wallet address in src/CorezPay.php

### Usage
See /examples
Live demo: http://62.77.157.217/coreZ/examples/index.php

### Update
04/04/2018	Added notice that this is the Non-RPC version
03/31/2018 	Added error codes (from CoreZ Core + custom)
			Minor changes for better class extension

### Donations
Want to help the project? You can donate some CRZ to ZrrEJze7MCA1dQmr8KRfFdugUhxQ28Vrtu