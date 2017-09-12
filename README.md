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

## Technical and function to-do list
- [ ] Outbid price based on sell instead of only buy
- [ ] Caching on Transactions API call
- [ ] Calculate total costs made on the market
- [ ] Calculate profit from Transactions
- [ ] Add more information about the outbid price (like location and range, or if it 'touches' your location)
- [ ] Add a default filter for Transactions, to show only items that you are trading in
- [ ] User preference/settings screen 
- [ ] Send notifications to email? if you've been outbid on an item. (User preference feature needed)
- [ ] Create tests
- [ ] Check price on any item in The Forge
- [ ] Proper deploy location
- [ ] Show color's if you're being outbid
- [ ] Remember me functionality?
- [ ] Pretty format date in transactions
- [ ] Convert ESI classes into a composer package
- [ ] Move redis caching logic in the Market class to a independent way.
- [x] :white_check_mark: Refactor Curl Client
- [ ] Proper API for transactions, and orders.

And more... if you have any feature requests or idea's, please let me know. (by creating an issue)
