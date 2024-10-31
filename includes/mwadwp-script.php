<script>
    var acc = document.getElementsByClassName("mwadwp__accordion");
    var sub_acc = document.getElementsByClassName("mwadwp__accordion");
    var i;
    var l;

    for (i = 0; i < acc.length; i++) {

        acc[i].addEventListener("click", function() {

            for(l = 0; l < sub_acc.length; l++) {
                sub_acc[l].classList.remove("mwadwp__active");
                var sub_panel = sub_acc[l].nextElementSibling;
                sub_panel.style.display = "none";
            }

            this.classList.toggle("mwadwp__active");

            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";

            } else {
                panel.style.display = "block";
            }
        });

    }
</script>