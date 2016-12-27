# secretchat.site

[https://secretchat.site](https://secretchat.site)

Not ready for production - yet.

## Requirements

PHP - for the web server portion

node.js - for the optional socket.io server

## Web Installation

Copy the `www` directory into your public HTML director on your web server.

## Node.js Installation

Run `nodejs index.js` in the `nodejs` directory.

## Code analysis

### index.php

Pseudocode:

```
UI:
 - collect room name (plaintext password) from user

goroom()
 - validate password length
 - generate 16 char salt
 - peform SHA256() on salt + password and 302 bounce to /room/{salt}/{hash}
 - plaintext password never sent to server
```
### room.php

Note: You can File | Save the output of room.php and use it locally.  It will still be able to encrypt content locally, all javascript served from your hard drive.  It will encrypt locally and send the encrypted bytes to `securesocket.io`.

Pseudocode:

```
UI:
 - collect room name again (plaintext password) from user
 - collect name (handle) from user

onload
 - get salt and room id from URL (the SHA256 hash) using aes-js
 - connect to https://securesocket.io:1443 via socket.io
 - subecribe to room based on SHA256 hash

login() 
 - validate room name (plaintext password) and name (handle)
 - SHA256 the giev salt & room name and make sure it matches our salt + sha hash
 - even if an attacker removes this code, the messages are still encrypted with the plaintext password
 - send message announcing user
 - hide login UI
 
encrypt()
 - validate message length
 - pad password to 32 bytes
 - pad plaintext message to 64 bytes
 - convert text to bytes
 - generate initializing vector
 - encrypt using crypto-js 3.1.2
 - emit message via socket.io

decrypt() 
 - pad password to 32 bytes
 - split message into ivt and encrypted
 - convert strings to bytes
 - decrypt using crypto-js 3.1.
 - add message to UI
```

