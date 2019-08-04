# !/bin/bash

# Setting Internal Field Separator (IFS) to empty in order to scape linebreaks
IFS=

# Function to express Usage of the bash
function Usage {
	echo ""
	echo "  The principal LaTeX file have not been specified"
	echo "    Usage: ./compile.sh [-f|--Full] {TeX File}"
	echo "  Example"
	echo "    ./compile.sh example"
	echo "    ./compile.sh -f example"
	echo "  Note: You must have installed 'ack' and 'grep' commands"
    echo "  The Tex File should be specified without .tex"
	echo ""
}

# Command to compile LaTex
latexCmd="pdflatex --interaction=nonstopmode --output-directory=output --halt-on-error"
glossariesCmd="makeglossaries -d output/ -q"

# Commands for colorized Output
ackGreen=" ack --filter --passthru --color --color-match=green  \"Output written on|This is pdfTeX|Info\""
ackYellow="ack --filter --passthru --color --color-match=yellow \"Warning|warning\""
ackRed="   ack --filter --passthru --color --color-match=red    \"Error|error|Undefined control sequence\""
ackBlue="  ack --filter --passthru --color --color-match=blue   \"Overfull|Underfull\""

# Commands for filtering output
grepAll="grep -E \"Output|This is pdfTeX|Info |arning|rror|Undefined|->|l\.[0-9]* ...\""
#grepAll="grep -E \"Output|This is pdfTeX|Info |arning|rror|erfull|Undefined|->|l\.[0-9]* ...\""

# Start of Main Script process
if [ -z "$1" ]; then
	Usage
	
elif [ -z "$2" ]; then
    echo ""
    mkdir -p output
	echo $(eval "${latexCmd} ${1}.tex | ${grepAll} | ${ackGreen} | ${ackYellow} | ${ackRed} | ${ackBlue}")
    echo $(eval "${glossariesCmd} ${1}")
	mv output/*.pdf ./
    
elif [ $1 == "--Full" ] || [ $1 == "-f" ]; then
    echo ""
    mkdir -p output
    echo $(eval "${latexCmd} ${2}.tex | ${ackGreen} | ${ackYellow} | ${ackRed} | ${ackBlue}")
    echo $(eval "${glossariesCmd} ${2}")
    mv output/*.pdf ./
    
else
	Usage
fi

