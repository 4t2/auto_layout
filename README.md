Contao AutoLayout Modul
=======================

Mit dem AutoLayout-Module lassen sich flexible Layouts definieren, die dann automatisch auf Frontend-Elemente angewendet werden. Ein Layout lässt sich dabei zweistufig – über ein Template und das AutoLayout – definieren.

Die Erweiterung eignet sich für einfache bis komplexe Layouts und kann genutzt werden, um bspw. Strukturen bekannter HTML5-Frameworks wie Bootstrap oder Foundation mit Inhaltselementen abzubilden. So lassen sich damit einfach Spaltenraster definieren oder Inhalte als Tabs ausgeben.

Vor dem eigentlichen Inhalt wird dann im Artikel das Inhaltselement `AutoLayout` eingefügt. Dort wird das Layout ausgewählt und angegeben, ob die nachfolgenden Inhalte wiederholt werden sollen oder nicht. Definiert man bspw. ein dreispaltiges Raster, werden bei der Wiederholung alle nachfolgenden Elemente abwechseln *links – mitte – rechts* angeordnet und beim vierten Element eine neue Zeile gestartet. Bei jedem Inhaltselement gibt es zusätzlich noch eine Option, die *Layoutposition nicht weiterzusetzen*. Damit können mehrere Elemente in einer Stelle im Layout angeordnet werden.

Zusätzlich kann im AutoLayout-Inhaltselement noch angegeben werden, dass versteckte Inhaltselemente das Layout nicht stören. Steht also bspw. ein Element in der mittleren Tabellenspalte und wird versteckt, bleibt die Spalte leer und die nachfolgenden Elemente rücken nicht nach.

Das AutoLayout endet entweder wenn:

* das letze Element im Artikel erreicht ist
* Wiederholungen deaktiviert sind und alle Platzhalter `{{CE}}` ersetzt sind
* das Inhaltselement `AutoLayout Ende` eingebunden ist

Template
--------

Das Template `autolayout_xxx` stellt eine Art Wrapper für das Layout und den Inhalt dar. Der Inhalt des Layouts selbst wird dann so ausgegeben:

```php
echo $this->content;
```

Alternativ können auch alle Zeilen einzelne ausgegeben werden:

```php
foreach ($this->rows as $numRow => $row)
{
	echo $row['content'];
}
```

Die Template-Variable `$this->rows` ist ein Array mit allen Zeilen/Wiederholungen. Der Inhalt steht in `$this->rows[n]['content']` und `$this->rows[n]['elements']` enthält ein Array mit allen enthaltenen Elementen inkl. der ID, Überschrift, CSS-ID und -Klasse.

Durch das Template können auch eigene Stylesheets oder Scripte eingebunden werden:

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

Mit dem Platzhalter `{{ROW}}` enthält einen Zähler für die aktuelle Zeile beginnend mit 1.

Beispiele
---------

**Mehrspaltiges Layout mit dem Foundation Framework**

*Template*

Als Beispiel wird hier gezeigt, wie das Foundation-Framework im Template eingebunden werden kann. Ansonsten wird im Template nur der Inhalt ausgegeben.

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

Im Layout sind zwei Zeilen mit drei und zwei Spalten definiert.

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

**Horizontale Tabs mit dem Foundation Framework**

*Template*

Das Template ist hier etwas umfangreicher und generiert zusätzlich die Tab-Liste über dem Inhalt. Dazu wird das Array `$this->rows` verwendet, welches in `$this->rows[n]['elements']` die Elemente inkl. CSS-Klassen enthält und in `$this->rows[n]['content']` den Inhalt einer Zeile.

```php
<div class="row">

<?php if ($this->headline): ?>
	<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

	<dl class="tabs" data-tab>
	<?php foreach ($this->rows as $numRow => $row): ?>
	  <dd<?php if ($row['elements'][0]->cssClass!='') echo ' class="'.$row['elements'][0]->cssClass.'"'; ?>><a href="#panel-<?php echo $numRow; ?>"><?php echo $row['elements'][0]->headline; ?></a></dd>
	<?php endforeach; ?>
	</dl>

	<div class="tabs-content">
	<?php foreach ($this->rows as $numRow => $row): ?>
		<div class="content <?php echo $row['elements'][0]->cssClass; ?>" id="panel-<?php echo $numRow; ?>">
			<?php echo $row['content']; ?>
		</div>
	<?php endforeach; ?>
	</div>

</div>
```
*Layout*

Das Layout ist hier minimal und besteht nur aus dem Platzhalter für den Inhalt.

```html
{{CE::Tab-Inhalt}}
```
