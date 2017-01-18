# 部署環境需求

- php > 7.0, mysql > 5.6, nginx > 1.10
- extentions: php-mcrypt php-gd php-curl php-mysql
- composer, [how to install](//getcomposer.org/download/)
- redis-server > 2.8


# 開發環境需求

- composer
- nodejs, npm
- global gulp `npm install -g gulp`
- css : sass > 3.3, compass > 1.0.1 


# 本地開發安裝
    
    cd web-service
    git clone http://gitlab.hwtrek.com/HWTrek/backend.git backend
    cd backend
    sh deployment/build-development.sh
    