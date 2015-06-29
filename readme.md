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
    
    cd web-service
    git clone http://gitlab.hwtrek.com/HWTrek/backend.git backend
    cd backend
    sh deployment/build-development.sh
    