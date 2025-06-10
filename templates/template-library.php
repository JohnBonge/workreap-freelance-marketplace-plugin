<?php
defined( 'ABSPATH' ) || exit;
?>
<script type="text/template" id="tmpl-workreapTemplateLibrary__header-logo">
	<span class="workreapTemplateLibrary__logo-title">{{{ title }}}</span>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php esc_html_e( 'Back to Library', 'workreap' ); ?></span>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__header-menu">
	<# _.each( tabs, function( args, tab ) { var activeClass = args.active ? 'elementor-active' : ''; #>
	<div class="elementor-component-tab elementor-template-library-menu-item {{activeClass}}" data-tab="{{{ tab }}}">
        {{{ args.title }}}
	</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__header-menu-responsive">
	<div class="elementor-component-tab workreapTemplateLibrary__responsive-menu-item elementor-active" data-tab="desktop">
		<i class="eicon-device-desktop" aria-hidden="true" title="<?php esc_attr_e( 'Desktop view', 'workreap' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Desktop view', 'workreap' ); ?></span>
	</div>
	<div class="elementor-component-tab workreapTemplateLibrary__responsive-menu-item" data-tab="tab">
		<i class="eicon-device-tablet" aria-hidden="true" title="<?php esc_attr_e( 'Tab view', 'workreap' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Tab view', 'workreap' ); ?></span>
	</div>
	<div class="elementor-component-tab workreapTemplateLibrary__responsive-menu-item" data-tab="mobile">
		<i class="eicon-device-mobile" aria-hidden="true" title="<?php esc_attr_e( 'Mobile view', 'workreap' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Mobile view', 'workreap' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__header-actions">
	<div id="workreapTemplateLibrary__header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'workreap' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Sync Library', 'workreap' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__preview">
	<iframe></iframe>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__header-insert">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ workreap.library.getModal().getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__insert-button">
	<a class="elementor-template-library-template-action elementor-button workreapTemplateLibrary__insert-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'workreap' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__pro-button">
	<a class="elementor-template-library-template-action elementor-button workreapTemplateLibrary__pro-button" href="https://elementor.wpworkreap.com/buy" target="_blank">
		<i class="eicon-external-link-square" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Get Pro', 'workreap' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__loading">
	<div class="elementor-loader-wrapper">
		<div class="elementor-loader">
			<div class="elementor-loader-boxes">
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
			</div>
		</div>
		<div class="elementor-loading-title"><?php esc_html_e( 'Loading', 'workreap' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__templates">
	<div class="workreapTemplateLibrary__templates-window">
		<div id="workreapTemplateLibrary__templates-list"></div>
	</div>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__template">
	<div class="workreapTemplateLibrary__template-body" id="workreapTemplate-{{ template_id }}">
		<div class="workreapTemplateLibrary__template-preview">
			<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
		</div>
		<img class="workreapTemplateLibrary__template-thumbnail" src="{{ thumbnail }}" alt="thumbnail">
	</div>
	<div class="workreapTemplateLibrary__template-footer">
		{{{ workreap.library.getModal().getTemplateActionButton( obj ) }}}
		<a href="#" class="elementor-button workreapTemplateLibrary__preview-button">
			<i class="eicon-device-desktop" aria-hidden="true"></i>
			<?php esc_html_e( 'Preview', 'workreap' ); ?>
		</a>
	</div>
</script>

<script type="text/template" id="tmpl-workreapTemplateLibrary__empty">
	<div class="elementor-template-library-blank-icon">
		<img src="<?php echo esc_url( ELEMENTOR_ASSETS_URL . 'images/no-search-results.svg' ); ?>" class="elementor-template-library-no-results" alt="no-result"/>
	</div>
	<div class="elementor-template-library-blank-title"></div>
	<div class="elementor-template-library-blank-message"></div>
</script>
