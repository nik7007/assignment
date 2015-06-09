<footer>
    <p>Questo footer contiene la dichiarazione position:relative; per fornire hasLayout a Internet Explorer 6 per il footer e determinarne il clearing corretto. Se non dovete supportare IE6, potete rimuoverlo.</p>
    <address>
      Contenuto indirizzo
    </address>
  </footer>

<script>

    resizePage();

    $(window).resize(function(){
        resizePage();
    });

</script>