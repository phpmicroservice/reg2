#

FROM php:7.4-cli

MAINTAINER Dongasai 1514582970@qq.com

ENV REFRESH_VERSION=1

RUN apt-get update

RUN apt-get install -y vim wget zip zlib1g-dev autoconf automake libtool libzip4 libzip-dev git

# 扩展安装 开始

# 安装 oniguruma
ENV ORIGURUMA_VERSION=6.9.7

RUN wget https://github.com/kkos/oniguruma/archive/v${ORIGURUMA_VERSION}.tar.gz -O oniguruma-${ORIGURUMA_VERSION}.tar.gz \
    && tar -zxvf oniguruma-${ORIGURUMA_VERSION}.tar.gz \
    && cd oniguruma-${ORIGURUMA_VERSION} \
    && ./autogen.sh \
    && ./configure \
    && make \
    && make install
# 安装 常用扩展
RUN docker-php-ext-install bcmath mbstring pdo pdo_mysql zip sockets \
    && docker-php-ext-enable pdo pdo_mysql sockets;
# 安装 Redis
RUN pecl install redis \
    && docker-php-ext-enable redis
# 安装 memcached
RUN apt-get install -y libmemcached-dev zlib1g-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached
# 安装 gd
RUN apt-get update && apt-get install -y wget unzip \
        libfreetype6-dev \
        libmcrypt-dev \
        libjpeg-dev \
        libpng-dev \
&& docker-php-ext-configure gd \
&& docker-php-ext-install gd



# 安装swoole
RUN pecl install swoole  && docker-php-ext-enable swoole

# 安装 protobuf
ENV PROTOBUF_VERSION=3.19.1
RUN wget "https://github.com/protocolbuffers/protobuf/releases/download/v${PROTOBUF_VERSION}/protobuf-php-${PROTOBUF_VERSION}.zip"
RUN unzip protobuf-php-${PROTOBUF_VERSION}.zip  && ls
RUN cd protobuf-${PROTOBUF_VERSION} \
    && ./configure && make && make check && make install
# pecl protobuf
RUN pecl install protobuf  && docker-php-ext-enable protobuf
# 追加环境变量
RUN echo "export LD_LIBRARY_PATH=/usr/local/lib" >> /root/.bashrc

COPY php7.ini  /usr/local/etc/php/conf.d/


# 扩展安装 完成
WORKDIR /var/www/html

# 安装composer
RUN wget https://mirrors.aliyun.com/composer/composer.phar \
	&& mv composer.phar /usr/local/bin/composer \
	&& chmod +x /usr/local/bin/composer

WORKDIR /var/www/html

