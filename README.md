# EVE Trade Helper (ETH)
EVE online, custom trade helper, which uses the ESI (EVE Swagger Interface)
## Running the application
```bash
cp .env.example .env
```
Add your own client id and secret to the .env file
```
EVE_APP_CLIENT_ID=
EVE_APP_CLIENT_SECRET=
```
After just run:
```bash
docker-compose up
```
## Artisan command
```bash
docker-compose exec app {yourcommandhere}
```
Note: Do NOT forget to run `composer install`.
