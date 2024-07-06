## Set up

### Up & Run script
```bash
cd docker
cp .env.example .env # update docker/.env if necessary
docker compose up -d
# expect preparation database
docker compose exec -T php cp .env.example .env # update app/.env if necessary
docker compose exec -T php php artisan.php distribute
```

### Down
```bash
cd docker
docker compose down -v
```