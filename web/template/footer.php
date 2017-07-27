<footer class="footer">
  <div class="container">
    <div class="text-muted">
        <span class="pull-left">CS QTT Panel <sup>&copy;</sup> by SAS Group</span>
        <span style="margin-left: 30px; float: left;" id = 'cngLog'><a href = 'https://docs.google.com/document/d/1Rbcx_aNae7Gzc_Y4v0i7TWoCjo4Vy43OvrvlSg7xL-I/edit?usp=sharing' target="_blank">>>> Add func</a></span>
        <span style="margin-left: 30px; float: right;" id = 'cngLog'><a href = 'https://github.com/webdevandym/QTT/blob/master/README.md' target="_blank">>>> Change Log</a></span>
        <span class="pull-right">v 2.2.5 (pre Stable build 35)</span>
    </div>
  </div>
</footer>

<script>
window.onload = () =>{
  loadClock("<?php echo $_SESSION['userName']; ?>")
}
</script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=PT+Sans&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css" >
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" >
<link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>assets/css/mincss/siteStyle-min.css" >
