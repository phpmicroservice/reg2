# 开发相关

```bash
sudo docker build -t reg:dev .

sudo docker run -d --name regdev -it reg:dev
sudo docker run -d -v $PWD:/var/www/html --name regdev -it reg:dev




sudo docker exec -it regdev  bash

```