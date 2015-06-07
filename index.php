<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <?php require_once("include.php") ?>
</head>

<body>

<div class="container">
    <?php
    require "header.php";
    require "main_menu.php";
    ?>
    <article class="content">
        <h1>Activities</h1>
        <section>
            <h2>Come utilizzare questo documento</h2>

            <p>Tenete presente che il codice CSS di questi layout contiene molti commenti. Se solitamente lavorate nella
                vista Progettazione, visualizzate almeno momentaneamente la vista Codice per consultare i suggerimenti
                sull'uso del codice CSS nei layout fissi. Potete rimuovere questi commenti prima di lanciare il sito.
                Per saperne di più sulle tecniche utilizzate in questi layout CSS, leggete questo articolo nel Centro
                per sviluppatori Adobe - <a href="http://www.adobe.com/go/adc_css_layouts_it">http://www.adobe.com/go/adc_css_layouts_it</a>.
            </p>
        </section>
        <section>
            <h2>Metodo di clearing</h2>

            <p>Poiché tutte le colonne sono con float, questo layout utilizza una dichiarazione clear:both nella regola
                footer. Questa tecnica di clearing obbliga il .container a determinare dove terminano le colonne per
                fare apparire i bordi o i colori di bordo che applicate al .container. Se il vostro design richiede la
                rimozione del footer dal .container, dovete utilizzare un metodo di clearing differente. La tecnica più
                affidabile consiste nell'aggiunta di un &lt;br class=&quot;clearfloat&quot; /&gt; o &lt;div class=&quot;clearfloat&quot;&gt;&lt;/div&gt;
                dopo l'ultima colonna con float (ma prima della chiusura del .container). L'effetto di clearing sarà lo
                stesso. </p>
        </section>
        <section>
            <h2>Sostituzione logo</h2>

            <p>In questo layout è stata utilizzata un'immagine segnaposto nell'intestazione, nel punto in cui
                probabilmente inserirete un logo. Si consiglia di rimuovere il segnaposto e sostituirlo con il vostro
                logo collegato. </p>

            <p>Tenete presente che se utilizzate la finestra di ispezione Proprietà per accedere all'immagine del logo
                utilizzando il campo Orig. (anziché rimuovere e sostituire il segnaposto), dovrete rimuovere le
                proprietà di visualizzazione e sfondo in linea. Questi stili in linea vengono utilizzati solo per fare
                apparire il segnaposto del logo nei browser a scopo di dimostrazione. </p>

            <p>Per rimuovere gli stili, controllate che il pannello Stili CSS sia visualizzato nella versione Corrente.
                Selezionate l'immagine e, nel riquadro Proprietà del pannello Stili CSS, fate clic con il pulsante
                destro ed eliminate le proprietà di visualizzazione e sfondo (display e background). (Naturalmente
                potete sempre accedere direttamente al codice ed eliminare manualmente gli stili in linea dall'immagine
                o dal segnaposto.)</p>
        </section>
        <!-- end .content --></article>
    <?php
    require "footer.php";

    ?>
    <!-- end .container --></div>
</body>
</html>