# Personal Finance Tracker
A simple web application to track income, expenses, and savings with basic reporting and user authentication.

## Installation
```shell
docker-compose up -d --build
```

### DB initialization
```shell
docker exec -i postgres_db psql -U finance_user -d finance_db < database/schema.sql
```

## Useful Docker Commands
```shell
# Restart containers
docker-compose restart

# Stop containers
docker-compose down

# View logs
docker-compose logs -f
```
