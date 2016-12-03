source data/variables.sh;




CONTAINER=$HTTP_NAME

RUNNING=$(docker inspect --format="{{ .State.Running }}" $CONTAINER 2> /dev/null)



if [ $? -eq 1 ]; then
    echo "UNKNOWN - $CONTAINER does not exist."
    docker build -t=$HTTP_NAME --build-arg APP_NAME=$APP_NAME .
    docker run -i -d -itd -p $SOCKET_PORT:8081 -p $MYSQL_PORT:3306 -p $HTTP_PORT:80 --name $HTTP_NAME -v $PWD'../../':/apps/$APP_NAME $HTTP_NAME
fi

if [ "$RUNNING" == "false" ]; then
    echo "CRITICAL - $CONTAINER is not running."
    docker start $HTTP_NAME 
fi

docker exec -it $HTTP_NAME bash -c 'cd /apps/'$APP_NAME'/docker/ && chmod 777 start.sh'
docker exec -it $HTTP_NAME bash -c 'cd /apps/'$APP_NAME'/docker/data/commands/ && chmod 777 install.sh'
docker exec -it $HTTP_NAME bash -c 'cd /apps/'$APP_NAME'/docker/data/commands/ && ./install.sh'
