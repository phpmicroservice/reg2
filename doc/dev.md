# 开发相关


## Docker 
```bash
sudo docker build -t ms/reg:dev .

sudo docker run -d --name regdev -it reg:dev

sudo docker run -d -v $PWD:/var/www/html --name msregdev -it ms/reg:dev


sudo docker exec -it msregdev  bash

```