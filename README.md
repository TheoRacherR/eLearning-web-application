1. Installation instructions

- <code>git clone </code>
- <code>docker compose build --pull --no-cache
  </code>
- <code>docker compose up -d</code>

2. URLs

- https://localhost/docs => API
- https://localhost:8080 => VueJS

3. Execute commands

- <code>docker exec challenge-s1-php-1 'your command'</code>

Dans Docker > php-1
- <code>php bin/console d:s:u</code>

- <code>php bin/console d:f:l</code>

- <code>php bin/console lexik:jwt:generate-keypair --overwrite</code>
