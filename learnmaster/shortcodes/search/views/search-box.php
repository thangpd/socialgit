<div class="lema-autocomplete-search btn-icon <?php $context->defineShortcodeBlock()?>">
	<div class="lema-search-icon-mobile">
		<i class="fa fa-search"></i>
	</div>
	<form action="<?php echo $searchUrl?>" method="get" class="lema-search-box-form" >
	    <div class="lema-search-container">
	        <input data-action="lema-search-term" type="text" class="input-search lema-searchbox-input" name="q" value="<?php echo $q?>" placeholder="<?php echo __('Search your course...', 'lema')?>" autocomplete="off">
            <button class="lema-searchbox-submit" type="submit"><i class="fa fa-search"></i> </button>
	    </div>
    </form>
</div>