<div class="burger-menu">
        <div class="bar">
          <span class="bar-1"> </span>
          <span class="bar-2"> </span>
          <span class="bar-3"> </span>
        </div>
      </div>
<script>
    jQuery(document).ready(function($) {
        $(".burger-menu ").on("click",".bar",function(){
            $(".menu").slideToggle();
            $(".bar").toggleClass('change');
        });
});
</script>
