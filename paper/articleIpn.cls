
\NeedsTeXFormat{LaTeX2e}
\ProvidesClass{articleIpn}[2019/06/30, %
IPN Reporte Tecnico Class, Author Daniel Ortega (https://gitlab.com/DanielOrtegaZ)]

\LoadClass{article}
\DeclareOption*{\PassOptionsToClass{\CurrentOption}{article}}
\ProcessOptions\relax

\RequirePackage[hmargin=2cm,vmargin=3.5cm]{geometry}

%=====================================================
% Escuela. e.g ESCUELA SUPERIOR DE CÓMPUTO
%=====================================================
\gdef\@escuela{ESCUELA SUPERIOR DE CÓMPUTO}
\gdef\escuelaImg{res/escom}
\newcommand{\escuela}[2][res/escom]{\gdef\escuelaImg{#1}\gdef\@escuela{#2}}

%=====================================================
% Instituto. e.g INSTITUTO POLITECNICO NACIONAL
%=====================================================
\gdef\@instituto{INSTITUTO POLITÉCNICO NACIONAL}
\gdef\institutoImg{res/ipn}
\newcommand{\instituto}[2][res/ipn]{\gdef\institutoImg{#1}\gdef\@instituto{#2}}

%=====================================================
% DATOS DE LA TESIS
%=====================================================
\gdef\tituloTT{}
\gdef\numeroTT{}
\newcommand{\trabajoTerminal}[2]{\gdef\numeroTT{#1}\gdef\tituloTT{#2}\title{#2}}

%=====================================================
% CONTACTO
%=====================================================
\gdef\@contacto{email@email.com}
\newcommand{\contacto}[1]{\gdef\@contacto{#1}}

%=====================================================
% TELEFONO
%=====================================================
\gdef\@telefono{155113}
\newcommand{\telefono}[1]{\gdef\@telefono{#1}}

%=====================================================
% DIRECTORES
%=====================================================
\gdef\@author{John Doe}
\gdef\@director{}
\newcommand{\director}[1]{\gdef\@director{#1}}

%===================================================================================
% DEFINICIÓN DE LA PORTADA
%===================================================================================
\makeatletter
\def\maketitle{%

    \begin{center}
        {\huge\bf\tituloTT}\par
        {\large\@author,\ \@director}\par
        {\@escuela.\ \@instituto}\par
        {Tel. \@telefono.\ E-mail: \@contacto}
    \end{center}
}
\thispagestyle{empty}
\makeatother




