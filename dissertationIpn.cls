%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
% Author Daniel Ortega (https://gilab.com/DanielOrtegaZ)
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
\NeedsTeXFormat{LaTeX2e}
\ProvidesClass{dissertationIpn}[2019/06/30, IPN Dissertation format, Author Daniel Ortega (https://gilab.com/DanielOrtegaZ)]

\LoadClass{book}
\DeclareOption*{\PassOptionsToClass{\CurrentOption}{book}}
\ProcessOptions\relax
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

\RequirePackage[utf8]{inputenc}                          % Codificación de caracteres en UTF-8.
\RequirePackage[spanish]{babel}                          % Cambia las variables del documento a español.
\RequirePackage{graphicx}                                % Manejo de imágenes.
\RequirePackage[hmargin=2cm,vmargin=3.5cm]{geometry}     % Manejo de márgenes del documento.

%=====================================================
% Instituto. e.g INSTITUTO POLITECNICO NACIONAL
%=====================================================
\gdef\@instituto{INSTITUTO POLITÉCNICO NACIONAL}
\gdef\institutoImg{res/ipn}
\newcommand{\instituto}[2][res/ipn]{\gdef\institutoImg{#1}\gdef\@instituto{#2}}

%=====================================================
% Escuela. e.g ESCUELA SUPERIOR DE CÓMPUTO
%=====================================================
\gdef\@escuela{ESCUELA SUPERIOR DE CÓMPUTO}
\gdef\escuelaImg{res/escom}
\newcommand{\escuela}[2][res/escom]{\gdef\escuelaImg{#1}\gdef\@escuela{#2}}

%=====================================================
% DATOS DEL TÍTULO
%=====================================================
\gdef\@titulo{INGENIERÍA EN SISTEMAS COMPUTACIONALES}
\newcommand{\titulo}[1]{\gdef\@titulo{#1}}

%=====================================================
% DATOS DE LA TESIS
%=====================================================
\gdef\tituloTT{}
\gdef\numeroTT{}
\newcommand{\trabajoTerminal}[2]{\gdef\numeroTT{#1}\gdef\tituloTT{#2}\title{#2}}

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

    \noindent%
    \begin{minipage}[t]{0.35\linewidth}
        \centering
        \vspace{0pt}
        \includegraphics[width=0.45\textwidth]{\institutoImg}
        
        \par\bigskip\bigskip
        \rule{1pt}{12cm}\hspace{0.4cm}
        \rule{2pt}{12cm}\hspace{0.4cm}
        \rule{1pt}{12cm}
        \par\bigskip\bigskip
        
        \includegraphics[width=0.6\textwidth]{\escuelaImg}
        
    \end{minipage}%
    %
    \hfill%
    \begin{minipage}[t]{0.65\linewidth}
        \centering
        \vspace{0.5cm}
        {\fontsize{14pt}{14pt}\bf\sffamily \@instituto}\par\bigskip
        \rule{1.0\textwidth}{2pt}\par
        \rule{0.9\textwidth}{1pt}\par
        
        \null\vfill
        \vspace{1.5cm}
        {\fontsize{14pt}{14pt}\sffamily\bf \@escuela}\par\bigskip
        
        \vspace{1.5cm}
        {\fontsize{14pt}{14pt}\sffamily Trabajo Terminal \numeroTT\par\bf``\tituloTT''}\par\bigskip
        
        \vspace{2cm}
        {\fontsize{14pt}{14pt}\sffamily\bf REPORTE DE TRABAJO TERMINAL}\par
        {\fontsize{12pt}{12pt}\sffamily COMO REQUISITO PARA OBTENER EL TÍTULO:}\par\bigskip
        {\fontsize{14pt}{14pt}\sffamily\bf \@titulo}\par
        
        \vspace{1cm}
        {\fontsize{14pt}{14pt}\sffamily\hfill P\hfill R\hfill E\hfill S\hfill E\hfill N\hfill T\hfill A\hfill}\bigskip
        
        \vspace{1cm}
        {\fontsize{12pt}{12pt}\sffamily \@author}\par
        
        \vspace{1cm}
        {\fontsize{14pt}{14pt}\sffamily\bf DIRECTORES:}\par
        {\fontsize{12pt}{12pt}\sffamily \@director}\par\bigskip\@date
        
    \end{minipage}
}
\thispagestyle{empty}
\makeatother

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
% Author Daniel Ortega (https://gilab.com/DanielOrtegaZ)
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

% GAMIFICATION

\def\principioIx{Epic Meaning \& Calling}
\def\principioIIx{Development \& Accomplishment}
\def\principioIIIx{Empowerment of Creativity \& FeedBack}
\def\principioIVx{OwnerShip \& Posession}
\def\principioVx{Social Influence \& Relateness}
\def\principioVIx{Scarcity \& Impatience}
\def\principioVIIx{Unpredictability \& Curiosity}
\def\principioVIIIx{Loss \& Avoidance}

\def\principioI{Diferenciación y Pertenencia (\principioIx)\ }
\def\principioII{Desarrollo y Recompensa (\principioIIx)\ }
\def\principioIII{Descubrimiento y Retroalimentación (\principioIIIx)\ }
\def\principioIV{Personalización (\principioIVx)\ }
\def\principioV{Motivo e Impulso Social (\principioVx)\ }
\def\principioVI{Codicia (\principioVIx)\ }
\def\principioVII{Impredecibilidad y Curiosidad (\principioVIIx)\ }
\def\principioVIII{Miedo a la Pérdida (\principioVIIIx)\ }
