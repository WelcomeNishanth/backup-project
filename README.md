# gateway-portal-ui
Repository for Gateway Portal UI

### Deployment steps (local)

This is a single image docker container.

* Pre-requisites:

  + git  
  + [docker](https://www.docker.com)


1. clone the repo:

  > git clone https://github.com/BuildDirect/gateway-portal-ui.git

  > cd gateway-portal-ui

2. build and run the container eg.

  > docker build -t gateway-portal-ui .

  > docker run -d --env ENVIRONMENT=local/dev/production --env  AWS_ACCESS_KEY=### --env AWS_SECRET_KEY=### -p 8099:80 -t -i gateway-portal-ui
  
  Visit http://localhost:8099 in your browser.

3. to stop the container 
  > docker stop (container_id)

4. to restart the container 
  > docker restart (container_id)

5. to login/ssh to the container scope
  > docker exec -t -i (id/name) /bin/sh

6. to list images 
  > docker images

7. to list docker containers with their status
  > docker ps -a

7. to delete the docker image 
  > docker rmi -f (image_name/image_id)

