<script>
    <?php if(!isset($_SESSION['darkmode'])):?>
    let on = false;
    <?php else:?>
    let on = !<?php echo $_SESSION['darkmode']?>;
    <?php endif;?>
    let count = 1;
    function darkmode(e) {
        let style = document.documentElement.style;
        on=!on;
        $.ajax({method: 'POST',url: '/darkmode.php',data: { mode: on, count: count },success: (e)=>{console.log(e)}})
        count--
    }
    darkmode(document.querySelector('#darkmode'));
</script>
