# !/bin/bash

rm -r output
mkdir output
mkdir output/analisis
mkdir output/investigacion
mkdir output/modulos
mkdir output/modulos/concentrado
mkdir output/conclusiones
mkdir output/metodologia
mkdir output/trabajofuturo
mkdir output/glosario
./compile.sh -f main \makeglossaries
./compile.sh -f main
