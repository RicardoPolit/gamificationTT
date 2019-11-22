<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *   SUBJECT:     _
 *   PROFESSOR:   _
 *
 * DESARROLLADORES: Daniel Ortega
 *
 * PRACTICE _:  TITULO DE LA PRACTICA
 *               - DESCRIPCION
 *
*/

/* GENERAL STRINGS */
$string['pluginname'] = 'Nivel Gamedle';
$string['gmxp'] = $string['pluginname'];
$string['gmxp:addinstance']   = "Agrega un nuevo bloque {$string['pluginname']}";
$string['gmxp:myaddinstance'] = "Agrega un nuevo bloque  {$string['pluginname']} a tu página de Moodle";

/**
 * Global strings variables for the text and help displayed in
 * the different settings, those strings are grouped according
 * to the settings pages.
 */

// ==== SETTINGS MENU =======================================
$string['SETTINGS_CATEGORY'] = "Gamedle: Módulo de Experiencia";
$string['SETTINGS_GENERAL'] = "Configuraciones Generales";
$string['SETTINGS_VISUAL'] = "Configuraciones Visuales";
$string['SETTINGS_SCHEME']  = "Configuraciones de Experiencia";
$string['SETTINGS_EVENTS']  = "Configuraciones de Eventos";

// ==== GENERAL SETTINGS ====================================
$string['SETTINGS_GENERAL_HEADER'] = "Escoje las principales características";
$string['SETTINGS_GENERAL_HEADER_DESC'] =
    "Puede impedir que los eventos a continuación brinden puntos de
     experiencia y/o deshabilitar el sistema de experiencia" ;

$string['SETTINGS_GENERAL_ENABLED'] = "Habilita el sistema de experiencia";
$string['SETTINGS_GENERAL_ENABLED_DESC'] =
    "Puedes impedir que los eventos brinden puntos de experiencia";

$string['SETTINGS_GENERAL_EVENTS_ENABLED'] =
    "Habilitar que los siguientes eventos otorgen puntos de experiencia";

$string['SETTINGS_GENERAL_EVENTS_ENABLED_DESC'] =
    "Establece los valores por defecto o establece los puntos de
     experiencia que los eventos soportados";

// ==== VISUAL SETTINGS =====================================
$string['SETTINGS_VISUAL_HEADER'] = "Personaliza la forma en que lucen los niveles";
$string['SETTINGS_VISUAL_DESC'] =
    "Puedes especificar el ítulo, descripción y mensaje de
     agradecimiento de los niveles, de la misma forma puedes cambiar
     la imagen y los colores para los niveles";

$string['VISUAL_SETTING_TEXT_TITLE'] = "Título de nivel";
$string['VISUAL_SETTING_HELP_TITLE'] = "el {$string['VISUAL_SETTING_TEXT_TITLE']}";
$string['VISUAL_SETTING_HELP_TITLE_help'] =
    "Especifica el título del nivel mostrado cuando se despliega la
    notificación de que un usuario ha alcanzado un nuevo nivel.";

$string['VISUAL_SETTING_TEXT_MESSAGE'] = "Mensaje de felicitación";
$string['VISUAL_SETTING_HELP_MESSAGE'] = "el {$string['VISUAL_SETTING_TEXT_MESSAGE']}";
$string['VISUAL_SETTING_HELP_MESSAGE_help'] =
    "Especifica el mensaje de felicitación mostrado en la notificación
    cuando un usuario alcanza un nuevo nivel";

$string['VISUAL_SETTING_TEXT_DESCRIPTION'] = "Descripción de nivel";
$string['VISUAL_SETTING_HELP_DESCRIPTION'] = "la {$string['VISUAL_SETTING_TEXT_DESCRIPTION']}";
$string['VISUAL_SETTING_HELP_DESCRIPTION_help'] =
    "Especifica la descripción del nivel desplegada en el mensaje
    de notificación cuando un usuario alcanza un nuevo nivel";

$string['VISUAL_SETTING_TEXT_COLORLVL'] = "Color de nivel";
$string['VISUAL_SETTING_HELP_COLORLVL'] = "el {$string['VISUAL_SETTING_TEXT_COLORLVL']}";
$string['VISUAL_SETTING_HELP_COLORLVL_help'] =
    "Especifica el color mostrado en el bloque {$string['pluginname']}";

$string['VISUAL_SETTING_TEXT_COLORBAR'] = "Color de la barra de progreso";
$string['VISUAL_SETTING_HELP_COLORBAR'] = "el {$string['VISUAL_SETTING_TEXT_COLORBAR']}";
$string['VISUAL_SETTING_HELP_COLORBAR_help'] =
    "Especifica el color mostrado en el bloque {$string['pluginname']}";

$string['VISUAL_SETTING_TEXT_IMAGE'] = "Imagen de nivel";
$string['VISUAL_SETTING_HELP_IMAGE'] = "la {$string['VISUAL_SETTING_TEXT_IMAGE']}";
$string['VISUAL_SETTING_HELP_IMAGE_help'] =
    "Selecciona la imagen mostrada en el bloque 
    {$string['pluginname']}. Si la imagen no es especificada entonces
    la imagen poa/image.jpg en el directorio del plugin sera mostrada";

$string['VISUAL_SETTING_ERROR_IMAGE_NAME'] = "La imagen no puede ser llamada icon.png";

$string['VISUAL_SETTING_ERROR_IMAGE_SAVE'] =
    "Ocurrio un error. La imagen no pudo ser guardada";

$string['VISUAL_SETTING_ERROR_IMAGE_DELETE'] =
    "Ocurrio un error. La imagen anterior no pudo ser eliminada";

$string['VISUAL_SETTING_DEFAULT_TITLE'] = "Niveles Gamedle";
$string['VISUAL_SETTING_DEFAULT_MESSAGE'] = "Felicitaciones";
$string['VISUAL_SETTING_DEFAULT_DESCRIPTION'] = "Nuevo nivel alcanzado";
$string['VISUAL_SETTING_DEFAULT_COLORLVL'] = "#0B619F";
$string['VISUAL_SETTING_DEFAULT_COLORBAR'] = "#0B619F";
$string['VISUAL_SETTING_DEFAULT_IMAGE'] = "image.jpg";

// ==== SCHEME SETTINGS =====================================
$string['SETTINGS_SCHEME_HEADER'] =
    "Personaliza la forma en que se otorgan los puntos experiencia";
$string['SETTINGS_SCHEME_DESC'] =
    "Controla cómo es que la cantidad de experiencia incrementa
    de nivel a nivel, establece los puntos de experiencia que brindan
    los cursos y especifica los puntos otorgados por el primer nivel";

$string['SCHEME_SETTING_TEXT_INCREMENT'] = "Tipo de incremento";
$string['SCHEME_SETTING_HELP_INCREMENT'] = "el {$string['SCHEME_SETTING_TEXT_INCREMENT']}";
$string['SCHEME_SETTING_HELP_INCREMENT_help'] =
    "Especifica el tipo de incremento que el sistema de experiencia
    con el que se debería trabajar. Si se especifica el incremento 
    lineal los niveles, entonces los niveles incrementarán de forma
    lineal. El otro tipo de incremento es porcentual con el cual
    los niveles incrementarán una porción con base en la cantidad de
    experiencia del nivel anterior.";

$string['SCHEME_SETTING_TEXT_LINEAL'] = "Incremento lineal";
$string['SCHEME_SETTING_HELP_LINEAL'] = "el {$string['SCHEME_SETTING_TEXT_LINEAL']}";
$string['SCHEME_SETTING_HELP_LINEAL_help'] =
    "Indica el valor linal con el cual los niveles incrementarán,
     Si el nivel 'i' consiste 'n' puntos de exoeriencia y el valor es
     'y' entonces la cantidad de experiencia del siguiente nivel
     será 'n+y'.";

$string['SCHEME_SETTING_TEXT_PERCENTUAL'] = "Incremento porcentual";
$string['SCHEME_SETTING_HELP_PERCENTUAL'] = "el {$string['SCHEME_SETTING_TEXT_PERCENTUAL']}";
$string['SCHEME_SETTING_HELP_PERCENTUAL_help'] =
    "Infica el valor del incremento porcentual a partir de 1.0 hasta
    2.0 con el cual los niveles incrementarán, Si el nivel 'i'
    consta de 'n' puntos de experiencia, y el incremento es 'y'
    entonces la experiencia del siguiente nivel estará determinada
    por la siguiente fórmula 'n*(1+y)^i'";

$string['SCHEME_SETTING_TEXT_LEVELXP'] = "Experiencia del primer nivel";
$string['SCHEME_SETTING_HELP_LEVELXP'] = "la {$string['SCHEME_SETTING_TEXT_LEVELXP']}";
$string['SCHEME_SETTING_HELP_LEVELXP_help'] =
    "Especifica la experiencia requerida para superar el primer nivel";

$string['SCHEME_SETTING_TEXT_COURSEXP'] = "Experiencia de los cursos";
$string['SCHEME_SETTING_HELP_COURSEXP'] = "la {$string['SCHEME_SETTING_TEXT_COURSEXP']}";
$string['SCHEME_SETTING_HELP_COURSEXP_help'] =
    "Especifica los puntos de experiencia que los cursos en general
    deberían entregar cuando estos son concluidos";

$string['SCHEME_SETTING_WARNING_MESSAGE'] = "";
// DEscribe the behavement of plugin when changing this settings.

$string['SCHEME_SETTING_DEFAULT_INCREMENT'] = "1"; // 0:LINEAL 1:PERCENTUAL
$string['SCHEME_SETTING_DEFAULT_VALUE'] = "1.3";
$string['SCHEME_SETTING_DEFAULT_LEVELXP'] = "1000";
$string['SCHEME_SETTING_DEFAULT_COURSEXP'] = "1500";

// ==== EVENTS SETTINGS =====================================
$string['SETTINGS_EVENTS_HEADER'] =
    "Establece la experiencia que los niveles deberían entregar";

$string['SETTINGS_EVENTS_DESC'] =
    "De los siguientes niveles habilita o deshabilita cuales eventos
    deberían entregar puntos de experiencia y la cantidad de puntos
    otorgados";

$string['EVENTS_SETTING_TEXT_XP_SUPPORT'] = "Dar experiencia: ";
$string['EVENTS_SETTING_TEXT_XP'] = "Experiencia otorgada";
$string['EVENTS_SETTING_HELP_XP'] = "la {$string['EVENTS_SETTING_TEXT_XP']}";
$string['EVENTS_SETTING_HELP_XP_help'] =
    "Especifica la experiencia otorgada por este evento";

$string['EVENTS_SETTING_TEXT_COMPCPU'] = "activar";
$string['EVENTS_SETTING_HELP_COMPCPU'] = "la activación";
$string['EVENTS_SETTING_HELP_COMPCPU_help'] =
    "Este evento es emitido cuando un usuario gana un competencia
    dentro del módulo de competencia";

$string['EVENTS_SETTING_TEXT_COMPCPU_DISABLED'] =
    "Para brindar soporte a este evento el plugin *mod_gmcompcpu*
    debe estar instalado";

$string['EVENTS_SETTING_TEXT_COMPCPU_GROUP'] = "Competencia vs CPU ganar evento";
$string['EVENTS_SETTING_HELP_COMPCPU_GROUP'] = "la {$string['EVENTS_SETTING_TEXT_COMPCPU_GROUP']}";
$string['EVENTS_SETTING_HELP_COMPCPU_GROUP_help'] = "  {$string['EVENTS_SETTING_HELP_COMPCPU_help']}

{$string['EVENTS_SETTING_HELP_XP_help']}";

$string['EVENTS_SETTING_TEXT_COMPVS'] = "activar";
$string['EVENTS_SETTING_HELP_COMPVS'] = "la activación";
$string['EVENTS_SETTING_HELP_COMPVS_help'] =
    "Este evento es emitido cuando un usuario gana una competencia
    contra la computadora, en el módulo de competencias";

$string['EVENTS_SETTING_TEXT_COMPVS_DISABLED'] =
    "Para brindar soporte a este evento el plugin *mod_gmcompvs*
    denbe estar instalado";

$string['EVENTS_SETTING_TEXT_COMPVS_GROUP'] = "Competencia 1vs1 ganar evento";
$string['EVENTS_SETTING_HELP_COMPVS_GROUP'] = "la {$string['EVENTS_SETTING_TEXT_COMPVS_GROUP']}";
$string['EVENTS_SETTING_HELP_COMPVS_GROUP_help'] = "  {$string['EVENTS_SETTING_HELP_COMPVS_help']}

{$string['EVENTS_SETTING_HELP_XP_help']}";

$string['EVENTS_SETTING_TEXT_PREGDIARIA'] = "activar";
$string['EVENTS_SETTING_HELP_PREGDIARIA'] = "la activación";
$string['EVENTS_SETTING_HELP_PREGDIARIA_help'] =
    "Este evento es emitido cuando un usuario gana una competencia
    en el módulo de competencia";

$string['EVENTS_SETTING_TEXT_PREGDIARIA_DISABLED'] =
    'Para brindar soporte a este evento el plugin *mod_gmpregdiarias* debe estar instalado';

$string['EVENTS_SETTING_TEXT_PREGDIARIA_GROUP'] = "Pregunta diaria correcta evento";
$string['EVENTS_SETTING_HELP_PREGDIARIA_GROUP'] = "la {$string['EVENTS_SETTING_TEXT_PREGDIARIA_GROUP']}";
$string['EVENTS_SETTING_HELP_PREGDIARIA_GROUP_help'] = "  {$string['EVENTS_SETTING_HELP_PREGDIARIA_help']}

{$string['EVENTS_SETTING_HELP_XP_help']}";

$string['EVENTS_SETTING_DEFAULT_COMPCPU'] = "1"; // 0:FALSE 1:TRUE
$string['EVENTS_SETTING_DEFAULT_COMPCPUXP'] = "1000";

$string['EVENTS_SETTING_DEFAULT_COMPVS'] = "1"; // 0:FALSE 1:TRUE
$string['EVENTS_SETTING_DEFAULT_COMPVSXP'] = "1000";

$string['EVENTS_SETTING_DEFAULT_PREGDIARIA'] = "1"; // 0:FALSE 1:TRUE
$string['EVENTS_SETTING_DEFAULT_PREGDIARIAXP'] = "1000";

// ==== FORM MESSAGES =====================================
$string['SETTINGS_EXPERIENCE_DISABLED_REDIRECT'] = "Habilitar módulo";
$string['SETTINGS_EXPERIENCE_DISABLED'] =
    "El módulo de experiencia está desactivado en la página
    *{$string['SETTINGS_GENERAL']}*. Si desea acceder a esta
    configuración debes habilitar el módulo de experiencia.";

$string['SETTINGS_EVENTS_DISABLED_REDIRECT'] = "Habilitar eventos";
$string['SETTINGS_EVENTS_DISABLED'] =
    "Los eventos de experiencia están desactivados en la página
    *{$string['SETTINGS_GENERAL']}*. Si desea acceder a estar
    configuraciones debes habilitar los eventos de experiencia";

$string['maxlength'] = 'La máxima longitud permitida es {$a}';
$string['color'] = 'Valor de color incorrecto';
$string['integer'] = 'El valor de experiencia debe se un entero mayor a 0';
$string['float'] =
    'The value must be a float between 1.0 and 2.0 of maximun 5 floating precision 
    points';

// ===== NOTIFICATION MESSAGES =========================================
$string['messageprovider:expgiven'] = 'Notificacion de experiencia recibida';

$string['expgivensubject'] = '{$a->nombre} has recibido {$a->exp} experiencia!';
$string['expgivenbody'] = 'Hola {$a->nombre}

Recibiste experiencia por haber ganado la competencia {$a->typecomp}

Cantidad de experiencia recibida: {$a->exp}';

$string['expgivensmall'] = 'Recibiste {$a->exp} monedas!';









$string['nameGeneralSettings'] = 'Ajustes Generales';
$string['nameVisualSettings'] = 'Ajustes de vistas de secciones de niveles';
$string['nameVisualSettingsGeneral'] = 'Ajustes de vistas de niveles generales';
/* ADMIN FORM STRINGS */
$string['headerconfigLevels'] = 'Configuración del esquema de experiencia';
$string['descconfigLevels'] = 'Asigna los valores para ir subiendo de nivel';
$string['typeOfIncrement'] = 'Tipo de incremento';
$string['typeOfIncrementA'] = 'Porcentual';
$string['typeOfIncrementB'] = 'Lineal';
$string['typeOfIncrementDesc'] = 'Selecciona el tipo de incremento';
$string['titleIncre'] = 'Valor del incremento';
$string['descIncre'] = 'Ingresa el valor del incremento que se tendrá por nivel, si seleccionaste el tipo de incremento "porcentaje" el valor debe estar en el rango [1.1 - 2.0]';
$string['headerconfigLevelsFormat'] = 'Configuración del formato de los niveles';
$string['headerconfigLevelsFormatDesc'] = 'Aquí podrás configurar el color, la imagen y los textos que aparecen para cada nivel';
$string['titleDefMessage'] = 'Mensaje de felicitaciones';
$string['descDefMessage'] = 'Escribe tu propio menaje de felicitaciones cuando un usuario suba de nivel';
$string['defaultDefMessage'] = 'Felicidades';
$string['defaultLevelsImage'] = 'Imagen por defecto';
$string['defaultLevelsImageDesc'] = 'Sube la imagen que desees para los niveles';
$string['titleDefName'] = 'Nombre de los niveles';
$string['descDefName'] = 'Selecciona un nombre para los niveles';
$string['defaultDefName'] = 'Niveles Casuales';
$string['checkboxisActive'] = 'Componente activado';
$string['checkboxisActivedesc'] = '¿El componente está activado?';
$string['defColorPick'] = 'Color del número de nivel';
$string['defColorPickProgressBar'] = 'Color de la barra de progreso';
$string['defColorPickdesc'] = 'Elige el color de tu agrado';

$string['titleFirstExpRequired'] = 'Exp. primer nivel';
$string['descFirstExpRequired'] = 'Experiencia requerida para completar el nivel 1';
$string['titleExpGiven'] = 'Exp. por curso';
$string['descExpGiven'] = 'Experiencia con la que un curso cuenta para repartirse entre sus secciones';
$string['titleDefDescription'] = 'Descripción de niveles';
$string['descDefDescription'] = 'Agrega un mensaje que motive a los usuarios a obtener más experiencia';
$string['defaultDefDescription'] = 'Estos son niveles increibles!';

/* Visuals settings levels */

$string['nameSecc'] = 'Definición de secciones de niveles';
$string['descSections'] = 'Descripción de la página';
$string['descDescSections'] = 'Aquí se pueden definir secciones de niveles para definir diferentes vistas para cada sección.';
$string['sectionscount'] = 'Número de secciones';
$string['createSections'] = 'Crear secciones';
$string['sectionx'] = 'Sección #{$a}';
$string['levelstart'] = 'Nivel de inicio de la sección';
$string['levelend'] = 'Nivel de fin de la sección';
$string['errorsectionsincorrect'] = 'El número de secciones no puede ser menos de dos';
