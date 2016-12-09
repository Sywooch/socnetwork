source data/variables.sh;


docker start $HTTP_NAME
docker exec -it $HTTP_NAME bash -c 'service apache2 start'
docker exec -it $HTTP_NAME bash -c 'service mysql start'
docker exec -it $HTTP_NAME bash -c 'service cron start'
docker exec -it $HTTP_NAME /bin/bash