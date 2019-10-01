# Gamedle Project

Este repositorio contiene todo el trabajo desarrollado durante el desarrollo del trabajo terminal 2018-B029 "Gamificación en una Plataforma Web de Aprendizaje"

## Creando el Entorno de Desarrollo

### Primeros pasos

Es importante leer la documentación y familizarse con la misma, para trabajar en este repositorio se debe de leer como mínimo:

1. [Moodle Arquitecture](https://docs.moodle.org/dev/Moodle_architecture) Documentación acerca de como está organizado Moodle
2. [Comunication Between Components](https://docs.moodle.org/dev/Communication_Between_Components) Comunicación entre componentes
2. [Plugin Files](https://docs.moodle.org/dev/Plugin_files#db.2Finstall.xml) Documentación de los archivos que conforma un plugin
3. [Plugin Dev Tutorial](https://docs.moodle.org/dev/Tutorial) Tutorias para el Desarrollo de Plugins


### Anadir repositorio a la carpeta de instalacion de Moodle

Elimina el directorio **.git** y los archivos **.gitignore** y **.gitattributes**

    rm -r .git .gitignore .gitattributes
    
Crea un repositorio local

    git init
    touch .gitignore
    git add .gitignore
    git commit -m "Initial Import"

Añade el repositorio remoto

    git remote add origin git@github.com:RicardoPolit/gamificationTT.git
    git push -u origin master

Empieza a desarrollar !!!

    git pull 
    git add 
    git commit
    git push


### Añade avances y plugins a Git

Abre el archivo oculto *.gitignore*

    nano .gitignore
    
Ingresa las dos lineas correspondientes al directorio del plugin

    !/path-to-plugin/pluginType_pluginName/
    !/path-to-plugin/PluginType_pluginName/**/*

## Usar XMLDB Editor

Se necesita que el plugin tenga la carpeta db con todos los permisos.

    mkdir db
    chmod 777 -R db/


### Recomendaciones

Si se va a desarrollar se recomienda editar la siguiente linea en el archivo config.php de la carpeta moodle

    $CFG->wwwroot   = 'http://'.gethostbyname(gethostname())."/moodle";

