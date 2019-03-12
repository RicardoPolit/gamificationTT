# Gamification TT

## Gamedle

### Instalation Guide

1. Delete the ".git" directory, ".gitignore" and ".gitattributes" files
2. Create your local repository
    git init
    touch .gitignore
    git add .gitignore
    git commit -m "Initial Import"
3. Add the remote repository to the project
    git remote add origin git@github.com:RicardoPolit/gamificationTT.git
    git push -u origin master
4. Work as always
    ...
    git push
    ...

### Must Read Docs

Estos debe de leer antes de empezar a tirar código

- **[Moodle Arquitecture](https://docs.moodle.org/dev/Moodle_architecture)**
- **[Plugin Files](https://docs.moodle.org/dev/Plugin_files#db.2Finstall.xml)**
- **[Plugin Dev Tutorial](https://docs.moodle.org/dev/Tutorial)**

### Add Development to Git

1. Abre el archivo oculto .gitignore
2. Ingresa la linea correspondiente al directorio de tu plugin
    !/path-to-your/pluginType_pluginName/*
3. Verifica con git status que la ruta especificada este añadida

## Pruebas de Concepto

### Lista de Plugins
- [ ] Activity Modules **Dan**
- [ ] Antivirus plugins
- [ ] Assignment submission plugins
- [ ] Assignment feedback plugins
- [ ] Book tools (no Docs)
- [ ] Database Fields **David**
- [ ] Database Presets
- [ ] LTI sources
- [ ] File Converters
- [ ] LTI services (no Docs)
- [ ] Machine Learning Backends
- [ ] Quiz Reports
- [ ] Quiz Access Rules
- [ ] SCORM Reports
- [ ] Workshop Grading Strategies
- [ ] Workshop Allocations Methods
- [ ] Workshop Evaluaction Methods
- [x] Blocks
- [ ] Questions Types **Richard**
- [ ] Question Behaviours
- [ ] Questions Import/Export Formats
- [ ] Text Filters
- [ ] Editors
- [ ] Atto Editor Plugins
- [ ] TinyMCE editor Plugins
- [ ] Enrolment Plugins
- [ ] Authentication Plugins
- [ ] Admin Tools
- [ ] Log Stores
- [ ] Availability Conditions
- [ ] Calendar Types
- [ ] Messaging Consumers
- [ ] Course Formats
- [ ] Data Formats
- [x] User Profile Fields
- [ ] Reports
- [ ] Course Reports
- [ ] Gradebook export
- [ ] Gradebook import
- [ ] Gradebook reports
- [ ] Advanced Grading Methods
- [ ] MNET Services
- [ ] Web Service Protocols
- [ ] Repository Plugins
- [ ] Portfolio plugins
- [ ] Search Engines
- [ ] Media Players
- [ ] Plagiarism Plugins
- [ ] Cache Store (no Docs)
- [ ] Cache Locks (no Docs)
- [ ] Themes
- [ ] Local Plugins
- [ ] Legacy Assignment Types (no Docs)
- [ ] Ledacy Admin Reports
