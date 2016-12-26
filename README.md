# secretchat.site

[https://secretchat.site](https://secretchat.site)

## Requirements

PHP - for the web server portion

node.js - for the optional socket.io server

## Web Installation

Copy the `www` directory into your public HTML director on your web server.

## Node.js Installation

Run `nodejs index.js` in the `nodejs` directory.

## Code analysis

### index.php

```
UI:
 - collect room name (plaintext password) from user

goroom()
 - validate password length
 - peform SHA1() on password and 302 bounce to /room/{hash}
 - plaintext password never sent to server
```
### room.php

```
UI:
 - collect room name again (plaintext password) from user
 - collect name (handle) from user

onload
 - get room id from URL (the SHA1 hash) using aes-js
 - connect to https://securesocket.io:1443 via socket.io
 - subecribe to room based on SHA1 hash

login() 
 - validate room name (plaintext password) and name (handle)
 - SHA1 the room name and make sure it matches our room ID
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

