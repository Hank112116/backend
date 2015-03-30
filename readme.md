# 部署環境需求

- php > 5.4, mysql > 5.5, nginx > 1.7
- extentions: php5-mcrypt php5-gd php5-curl php5-mysql
- composer, [how to install](//getcomposer.org/download/)


# 開發環境需求

- composer
- nodejs, npm
- global gulp `npm install -g gulp`
- css : sass > 3.3, compass > 1.0.1 


# 本地開發安裝

    git clone http://172.16.1.82/hwtrek/backendvagrant.git
    cd backendvagrant
    git clone http://172.16.1.82/hwtrek/backend.git apps/backend
    cp apps/backend/.env.dev.example .env
    composer install
    vagrant up

