## Authentication 

### JWT 

When you start using it, follow the instructions [here](https://github.com/lexik/LexikJWTAuthenticationBundle). 

#### Unable to create a request from the given configuration

Happens when : We try to instanciate a request like a login attempt

Create your keys pair : `php bin/console lexik:jwt:generate-keypair`

#### No Such Process 

Happens when : `php bin/console lexik:jwt:generate-keypair`

Error : `error:02001003:system library:fopen:No such process`

Solution : [Install Openssl](https://kb.firedaemon.com/support/solutions/articles/4000121705)

Download the compressed file, and set the **Environment Variable** `OPEN_SSL` equal to the path to the `.cnf` file.

