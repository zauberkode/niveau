# Niveau

This is the test task solution with the simple API and some tests

The Docker Compose V2 was used, while creating the Makefile, so, if you prefer Docker Compose V1, please update the Makefile accordingly.
(I mean - "docker-compose" vs "docker compose")

## How to install:

1. Clone the repo 
2. Go into the project folder with docker-compose.yml file in it.
3. Run:

```
docker compose up -d
```

4. Wait until composer installs all dependencies, control it with:

```
docker compose logs
```

5. Create databases with

```
make databases
```
or, if you don't have *make* installed - look into the Makefile and run the commands by hands


## How to use:

Type in your browser's address bar:

```
http://localhost:8080/api
```

and play with the Swagger docs.



## How to test:

```
make testing
```















