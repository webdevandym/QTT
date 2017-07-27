<!-- load and check lib (online|offline)-->
<script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" ></script>
<script>document.write('<!-- my lib --><script  src="<?php echo $basePath; ?>assets/js/min/siteJS-min.js"  ><\/script>');</script>
<script async defer src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script async defer src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
<script>
window.onload = () => {

  (typeof $.fn.popover == 'function') || document.write('<script src="/../assets/js/vendor/bootstrap.min.js" async defer><\/script>') //3.3.7

}
</script>
