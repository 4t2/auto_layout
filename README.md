Contao AutoLayout Modul
=======================

Mit dem AutoLayout-Module lassen sich flexible Layout definieren, die dann automatisch auf Frontend-Elemente angewendet werden. Ein Layout lässt sich dabei zweistufig – über ein Template und das Auto-Layout – definieren.

Die Erweiterung eignet sich für einfache bis komplexe Layouts

Template
--------

Das Template `autolayout_xxx` stellt eine Art Wrapper für das Layout und den Inhalt dar. Der Inhalt des Layouts selbst wird dann so ausgegeben:

```php

echo $this->content;

// Alternativ können auch alle Zeilen einzelne ausgegeben werden:

for ($i = 0; $i < count($this->rows); $i++)
{
	echo $this->rows[$i];
}
```

Zusätzlich steht noch mit `$this->elements` ein Array mit allen enthaltenen Elementen zur Verfügung.

Durch das Template können auch eigene Stylesheet oder Script eingebunden werden:

```php
$GLOBALS['TL_JAVASCRIPT'][] = 'styles/script.js';
$GLOBALS['TL_CSS'][] = 'styles/style.css';
```

Layout
------

Das Layout wird mit normalem HTML definiert. Die Platzhalter werden beim Rendern dann mit den Inhalten der nachfolgenden Inhaltelemente gefüllt. Wurden alle Platzhalter ersetzt, wird die Bearbeitung entweder an dieser Stellen abgebrochen oder es wird von vorn begonnen.

Platzhalter
-----------

Über den Platzhalter `{{CE}}` wird der Inhalt der Inhaltselemente eingebunden. Als Parameter kann ein Label angegeben werden, mit welchem dann im Backend das Inhaltselement beschriftet wird: `{{CE::linke Spalte}}`

Mit dem Platzhalter `{{ROW}}` enthält einen Zähler für die aktuelle Zeile.

Beispiele
---------

**Mehrspaltiges Layout mit dem Foundation Framework**

*Template*

```php
<?php
	$GLOBALS['TL_CSS'][] = 'foundation/css/normalize.css';
	$GLOBALS['TL_CSS'][] = 'foundation/css/foundation.css';

	$GLOBALS['TL_JAVASCRIPT'][] = 'foundation/js/vendor/modernizr.js';
	$GLOBALS['TL_JAVASCRIPT'][] = 'foundation/js/vendor/jquery.js';
	$GLOBALS['TL_JAVASCRIPT'][] = 'foundation/js/foundation.min.js';

	$GLOBALS['TL_BODY'][] = '<script>$(document).foundation();</script>';

	echo $this->content;
?>
```

*Layout*

```html
<div class="row">
	<div class="large-4 columns">{{CE::links oben}}</div>
	<div class="large-4 columns">{{CE::mitte oben}}</div>
	<div class="large-4 columns">{{CE::rechts oben}}</div>
</div>
<div class="row">
	<div class="large-6 columns">{{CE::links unten}}</div>
	<div class="large-6 columns">{{CE::rechts unten}}</div>
</div>
```


