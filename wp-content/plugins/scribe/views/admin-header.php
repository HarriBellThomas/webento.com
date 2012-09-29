<?php
$dependency = $this->getEcordiaDependency();
$settings = $this->getSettings();
?>
<!-- Start Ecordia Output -->
<script type="text/javascript">
var ecordia_dependency = '<?php echo $dependency; ?>';
var ecordia_element_title = '';
var ecordia_element_description = '';
<?php if($dependency == 'user-defined') { ?>
ecordia_element_title = '<?php echo $settings['seo-tool']['title']; ?>';
ecordia_element_description = '<?php echo $settings['seo-tool']['description']; ?>';
<?php } ?>
var ecordia = new ecordiaObject(ecordia_dependency, ecordia_element_title, ecordia_element_description);
function ecordia_addTinyMCEEvent(ed) {
	ed.onChange.add(function(ed, e) { ecordia.blurEvent(); } );
}
</script>
<!-- End Ecordia Output -->