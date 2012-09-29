	<p style="text-align: center;">
		<?php _e('Evaluations left: '); ?>
		<strong> 
			<span id="evaluations-left-number">
				<?php echo $this->getNumberEvaluationsRemaining(); ?>
			</span> 
			<?php printf(__(' for %s'), date('F Y')); ?>
		</strong>
	</p>
