<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');


$GLOBALS['TL_LANG']['XPL']['autoLayout'] = array
(
	array('Format', 'Das Layout wird mit normalem HTML definiert. Die Platzhalter werden beim Rendern dann mit den Inhalten der nachfolgenden Inhaltelemente gefüllt. Wurden alle Platzhalter ersetzt, wird die Bearbeitung entweder an dieser Stellen abgebrochen oder es wird von vorn begonnen.'),
	array('Platzhalter', 'Über den Platzhalter {{CE}} wird der Inhalt der Inhaltselemente eingebunden. Als Parameter kann ein Label angegeben werden, mit welchem dann im Backend das Inhaltselement beschriftet wird: {{CE::linke Spalte}}'),
	array('{{ROW}}', 'Die aktuelle Zeilennummer.')
);
