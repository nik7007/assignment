<footer>
    <p>Spare time activities</p>
    <address>
        Address
    </address>
</footer>

<script>

    sticky_navigation_offset_top = $('.sidebar1').offset().top;
    resizePage();
    sticky_navigation();

    $(window).scroll(function () {
        resizePage();
        sticky_navigation();

    });


    $(window).resize(function () {
        resizePage();
        sticky_navigation();

    });

    //window.onclose = onClosePage();

</script>