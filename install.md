# Instalar PHP 7.x < 7.3

    DEBIAN 10
    wget https://packages.sury.org/php/apt.gpg
    apt-key add apt.gpg
    apt-get update
    apt-get install php7.0 php7.0-zip php7.0-curl php7.0-mysql php7.0-xml php7.0-gd php7.0-intl php7.0-mbstring php7.0-xmlrpc php-opcache php-soap

# Instalar MariaDB

    DEBIAN 10
    apt-get install mariadb-server
    mysql_secure_installation

## Crear la base de datos

    CREATE DATABASE moodle DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,CREATE TEMPORARY TABLES,DROP,INDEX,ALTER ON moodle.* TO 'moodle'@'localhost' IDENTIFIED BY 'moodle';

# OBTAINING MOODLE

    DEBIAN 10
    git clone --depth 1 -b MOODLE_35_STABLE https://github.com/moodle/moodle.git gamedle

# PROCESO DE INSTALACION

Directorio de datos

    mkdir moodledata

    cd gamedle
    php -S 0.0.0.0:8000

FULL UNICODE SUPPORT IF REQUIERED

    vim /etc/my.cnf
    https://docs.moodle.org/all/es/MySQL_soporte_unicode_completo

FOR DEVELOPERS

    cd ../
    rm -rf gamedle/.git
    git clone git@gitlab.com:DanielOrtegaZ/gamedle.git temp

copiamos todo el contenido de temp (con archivos ocultos) dentro de gamedle-

    rm -rf temp
    cd gamedle
    git status # confirmar que todo está bien


