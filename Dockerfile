FROM php:8.1-cli

# 必要なパッケージのインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP拡張機能のインストール
RUN docker-php-ext-install sockets && \
    pecl install grpc protobuf && \
    docker-php-ext-enable grpc protobuf sockets

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# アプリケーションディレクトリの作成
WORKDIR /app

# 依存関係のインストール
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# アプリケーションのコピー
COPY . .

# オートローダーの最適化
RUN composer dump-autoload --optimize

# RoadRunner のインストール
RUN curl -L https://github.com/roadrunner-server/roadrunner/releases/download/v2.12.3/roadrunner-2.12.3-linux-amd64.tar.gz > roadrunner.tar.gz \
    && tar -xzf roadrunner.tar.gz \
    && mv roadrunner-2.12.3-linux-amd64/rr /usr/local/bin/ \
    && rm -rf roadrunner.tar.gz roadrunner-2.12.3-linux-amd64
