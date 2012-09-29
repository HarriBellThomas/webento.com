<?php if(!empty($key)) { $class = "premise-button-{$key}"; } else { $class = 'css3button'; } ?>

body .<?php echo $class; ?> {
	display: inline-block;
	
	text-decoration: none;
	<span class="property">font-family</span>: <span class="c font-family"><?php echo $button['font-family']; ?></span>;
	
	<span class="property">font-size</span>: <span class="c font-size"><?php echo $button['font-size']; ?></span>px;
	
	<span class="property">color</span>: <span class="c font-color"><?php echo $button['font-color']; ?></span>;
	
	<span class="property">padding</span>: <span class="s padding-tb"><?php echo $button['padding-tb']; ?></span>px <span class="s padding-lr"><?php echo $button['padding-lr']; ?></span>px;
	
	<span class="property">background</span>: <span class="c background-color-1"><?php echo $button['background-color-1']; ?></span>;
	
	<span class="property">background</span>: <span class="property">-moz-linear-gradient</span>(
		top,
		<span class="c background-color-1"><?php echo $button['background-color-1']; ?></span> 0%
		<div class="moz-background-color-stops">
		<?php foreach(range(2,4) as $key) { ?>
			<?php if($button['background-color-'.$key.'-enabled'] == 'yes') { ?>
		<div class="background-color-<?php echo $key; ?>-enabled-container">,<span id="background-color-<?php echo $key; ?>-moz-flag"><span><span class="c background-color-<?php echo $key; ?>"><?php echo $button['background-color-'.$key] ?></span> <span class="p background-color-<?php echo $key; ?>-position"><?php echo $button['background-color-'.$key.'-position']; ?></span>%</span></div>
			<?php } ?>
		<?php } ?>
		</div>
		,<span class="c background-color-5"><?php echo $button['background-color-5']; ?></span> 100%);
	</span>
	
	<span class="property">background</span>: <span class="property">-webkit-gradient</span>(
		linear, left top, left bottom,
		from(<span class="c background-color-1"><?php echo $button['background-color-1']; ?></span>)
		<div class="webkit-background-color-stops">
		<?php foreach(range(2,4) as $key) { ?>
			<?php if($button['background-color-'.$key.'-enable'] == 'yes') { ?>
		<div class="background-color-<?php echo $key; ?>-enabled-container">,<span class="background-color-<?php echo $key; ?>-container" id="background-color-<?php echo $key; ?>-webkit-flag"><span>color-stop(<span class="p">0.<span class="background-color-<?php echo $key; ?>-position"><?php echo $button['background-color-'.$key.'-position']; ?></span></span>, <span class="c background-color-<?php echo $key; ?>"><?php echo $button['background-color-'.$key]; ?></span>)</span></span></div>
			<?php } ?>
		<?php } ?>
		</div>
		,to(<span class="c background-color-5"><?php echo $button['background-color-5']; ?></span>));
	
	<span class="property">border-radius</span>: <span class="s border-radius"><?php echo $button['border-radius']; ?></span>px;
	
	<span class="property">-moz-border-radius</span>: <span class="s border-radius"><?php echo $button['border-radius']; ?></span>px;
	<span class="property">-webkit-border-radius</span>: <span class="s border-radius"><?php echo $button['border-radius']; ?></span>px;
	<span class="property">border</span>: <span class="s border-width"><?php echo $button['border-width']; ?></span>px solid <span class="c border-color"><?php echo $button['border-color']; ?></span>;
	
	<span class="property">-moz-box-shadow</span>: 
		<span class="s drop-shadow-x"><?php echo $button['drop-shadow-x']; ?></span>px <span class="s drop-shadow-y"><?php echo $button['drop-shadow-y']; ?></span>px <span class="s drop-shadow-size"><?php echo $button['drop-shadow-size']; ?></span>px rgba( <span class="rgb drop-shadow-color"><?php echo $this->RGB2hex($button['drop-shadow-color']); ?></span>, <span class="a drop-shadow-opacity"><?php echo $button['drop-shadow-opacity']; ?></span>),
		inset <span class="s inset-shadow-x"><?php echo $button['inset-shadow-x']; ?></span>px <span class="s inset-shadow-y"><?php echo $button['inset-shadow-y']; ?></span>px <span class="s inset-shadow-size"><?php echo $button['inset-shadow-size']; ?></span>px rgba( <span class="rgb inset-shadow-color"><?php echo $this->RGB2hex($button['inset-shadow-color']); ?></span>, <span class="a inset-shadow-opacity"><?php echo $button['inset-shadow-opacity']; ?></span>);
	
	<span class="property">-webkit-box-shadow</span>: 
		<span class="s drop-shadow-x"><?php echo $button['drop-shadow-x']; ?></span>px <span class="s drop-shadow-y"><?php echo $button['drop-shadow-y']; ?></span>px <span class="s drop-shadow-size"><?php echo $button['drop-shadow-size']; ?></span>px rgba(<span class="rgb drop-shadow-color"><?php echo $this->RGB2hex($button['drop-shadow-color']); ?></span>, <span class="a drop-shadow-opacity"><?php echo $button['drop-shadow-opacity']; ?></span>),
		inset <span class="s inset-shadow-x"><?php echo $button['inset-shadow-x']; ?></span>px <span class="s inset-shadow-y"><?php echo $button['inset-shadow-y']; ?></span>px <span class="s inset-shadow-size"><?php echo $button['inset-shadow-size']; ?></span>px rgba(<span class="rgb inset-shadow-color"><?php echo $this->RGB2hex($button['inset-shadow-color']); ?></span>, <span class="a inset-shadow-opacity"><?php echo $button['inset-shadow-opacity']; ?></span>);
	
	<span class="property">text-shadow</span>: 
		<span class="s text-shadow-1-x"><?php echo $button['text-shadow-1-x']; ?></span>px <span class="s text-shadow-1-y"><?php echo $button['text-shadow-1-y']; ?></span>px <span class="s text-shadow-1-size"><?php echo $button['text-shadow-1-size']; ?></span>px rgba(<span class="rgb text-shadow-1-color"><?php echo $this->RGB2hex($button['text-shadow-1-color']); ?></span>, <span class="a text-shadow-1-opacity"><?php echo $button['text-shadow-1-opacity']; ?></span>), 
		<span class="s text-shadow-2-x"><?php echo $button['text-shadow-2-x']; ?></span>px <span class="s text-shadow-2-y"><?php echo $button['text-shadow-2-y']; ?></span>px <span class="s text-shadow-2-size"><?php echo $button['text-shadow-2-size']; ?></span>px rgba(<span class="rgb text-shadow-2-color"><?php echo $this->RGB2hex($button['text-shadow-2-color']); ?></span>, <span class="a text-shadow-2-opacity"><?php echo $button['text-shadow-2-opacity']; ?></span>);
}

body .<?php echo $class; ?>:hover {
	<span class="property">color</span>: <span class="c font-color"><?php echo $button['font-color']; ?></span>;
	
	<span class="property">background</span>: <span class="c background-color-hover-1"><?php echo $button['background-color-hover-1']; ?></span>;
	
	<span class="property">background</span>: <span class="property">-moz-linear-gradient</span>(
		top,
		<span class="c background-color-hover-1"><?php echo $button['background-color-hover-1']; ?></span> 0%
		<div class="moz-background-color-hover-stops">
		<?php foreach(range(2,4) as $key) { ?>
			<?php if($button['background-color-hover-'.$key.'-enabled'] == 'yes') { ?>
		<div class="background-color-hover-<?php echo $key; ?>-enabled-container">,<span id="background-color-hover-<?php echo $key; ?>-moz-flag"><span><span class="c background-color-hover-<?php echo $key; ?>"><?php echo $button['background-color-hover-'.$key] ?></span> <span class="p background-color-hover-<?php echo $key; ?>-position"><?php echo $button['background-color-hover-'.$key.'-position']; ?></span>%</span></div>
			<?php } ?>
		<?php } ?>
		</div>
		,<span class="c background-color-hover-5"><?php echo $button['background-color-hover-5']; ?></span> 100%);
	</span>
	
	<span class="property">background</span>: <span class="property">-webkit-gradient</span>(
		linear, left top, left bottom,
		from(<span class="c background-color-hover-1"><?php echo $button['background-color-hover-1']; ?></span>)
		<div class="webkit-background-color-hover-stops">
		<?php foreach(range(2,4) as $key) { ?>
			<?php if($button['background-color-hover-'.$key.'-enable'] == 'yes') { ?>
		<div class="background-color-hover-<?php echo $key; ?>-enabled-container">,<span class="background-color-hover-<?php echo $key; ?>-container" id="background-color-hover-<?php echo $key; ?>-webkit-flag"><span>color-stop(<span class="p">0.<span class="background-color-hover-<?php echo $key; ?>-position"><?php echo $button['background-color-hover-'.$key.'-position']; ?></span></span>, <span class="c background-color-hover-<?php echo $key; ?>"><?php echo $button['background-color-hover-'.$key]; ?></span>)</span></span></div>
			<?php } ?>
		<?php } ?>
		</div>
		,to(<span class="c background-color-hover-5"><?php echo $button['background-color-hover-5']; ?></span>));
}


body .<?php echo $class; ?>:focus {
	<span class="property">color</span>: <span class="c font-color"><?php echo $button['font-color']; ?></span>;
}