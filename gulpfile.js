var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir(function(mix) {
	mix.sass('app.scss');
	
    mix.copy('resources/assets/vendor/bootstrap/fonts', 'public/fonts');
    mix.copy('resources/assets/vendor/font-awesome/fonts', 'public/fonts');
    
    mix.styles([
        'resources/assets/vendor/bootstrap/css/bootstrap.css',
        'resources/assets/vendor/animate/animate.css',
        'resources/assets/vendor/select2/css/select2.min.css',
        'resources/assets/vendor/font-awesome/css/font-awesome.css'
    ], 'public/css/vendor.css', './');
    
    mix.scripts([
        'resources/assets/vendor/jquery/jquery-3.1.1.min.js',
        'resources/assets/vendor/jquery/jquery.cookie.js',
        'resources/assets/vendor/bootstrap/js/bootstrap.min.js',
        'resources/assets/vendor/metisMenu/jquery.metisMenu.js',
        'resources/assets/vendor/slimscroll/jquery.slimscroll.min.js',
        'resources/assets/vendor/pace/pace.min.js',
		'resources/assets/vendor/select2/js/select2.min.js',
		'resources/assets/vendor/bootbox/bootbox.min.js',
		'resources/assets/vendor/extra/fingerprint.js',
		'resources/assets/vendor/extra/highcharts.js',
        'resources/assets/js/app.js'
    ], 'public/js/app.js', './');
	
	mix.sass('resources/assets/landing/slick/slick.scss');
	mix.sass('resources/assets/landing/slick/slick-theme.scss');
    mix.copy('resources/assets/fonts', 'public/fonts');
    mix.copy('resources/assets/finished', 'public/finished');
    mix.copy('resources/assets/images', 'public/images');
    mix.copy('resources/assets/landing', 'public/landing');
    mix.copy('resources/assets/training', 'public/training');
	
	mix.styles(['resources/assets/landing/css/styles.css'], 'public/landing/css/styles.css', './');
	mix.scripts(['resources/assets/landing/js/scripts.js'], 'public/landing/js/scripts.js', './');
	
	mix.scripts(['resources/assets/js/admin/members.js'], 'public/js/admin/members.js', './');
	mix.scripts(['resources/assets/js/admin/editmembers.js'], 'public/js/admin/editmembers.js', './');
	mix.scripts(['resources/assets/js/admin/addmember.js'], 'public/js/admin/addmember.js', './');
	mix.scripts(['resources/assets/js/admin/paypalpending.js'], 'public/js/admin/paypalpending.js', './');
	mix.scripts(['resources/assets/js/admin/paykickstarts.js'], 'public/js/admin/paykickstarts.js', './');
	
	mix.scripts(['resources/assets/js/users/dashboard.js'], 'public/js/users/dashboard.js', './');
	mix.scripts(['resources/assets/js/users/graph.js'], 'public/js/users/graph.js', './');
	mix.scripts(['resources/assets/js/users/links.js'], 'public/js/users/links.js', './');
	mix.scripts(['resources/assets/js/users/rotators.js'], 'public/js/users/rotators.js', './');
    mix.scripts(['resources/assets/js/users/popups.js'], 'public/js/users/popups.js', './');
    mix.scripts(['resources/assets/js/users/popbars.js'], 'public/js/users/popbars.js', './');
    mix.scripts(['resources/assets/js/users/timers.js'], 'public/js/users/timers.js', './');
    mix.scripts(['resources/assets/js/users/billingupgrade.js'], 'public/js/users/billingupgrade.js', './');
	mix.scripts(['resources/assets/js/users/conversiontime.js'], 'public/js/users/conversiontime.js', './');
	mix.scripts(['resources/assets/js/users/linkAdd.js'], 'public/js/users/linkAdd.js', './');
	mix.scripts(['resources/assets/js/users/rotatorAdd.js'], 'public/js/users/rotatorAdd.js', './');
	
	mix.scripts(['resources/assets/vendor/extra/daterangepicker.js'], 'public/js/vendor/extra/daterangepicker.js', './');
	mix.scripts(['resources/assets/vendor/extra/moment.min.js'], 'public/js/vendor/extra/moment.min.js', './');
	mix.scripts(['resources/assets/vendor/extra/jquery.validate.min.js'], 'public/js/vendor/extra/jquery.validate.min.js', './');
	
	mix.styles(['resources/assets/vendor/extra/daterangepicker.css'], 'public/js/vendor/extra/daterangepicker.css', './');
	
	mix.copy('resources/assets/vendor/extra/bootstrap-datepicker', 'public/js/vendor/extra/bootstrap-datepicker');
	mix.styles(['resources/assets/vendor/extra/bootstrap-datepicker/css/datepicker3.css'], 'public/js/vendor/extra/bootstrap-datepicker/css/datepicker3.css', './');
    mix.scripts(['resources/assets/vendor/extra/bootstrap-datepicker/js/bootstrap-datepicker.js'], 'public/js/vendor/extra/bootstrap-datepicker/js/bootstrap-datepicker.js', './');
	
	mix.copy('resources/assets/vendor/extra/fancybox', 'public/js/vendor/extra/fancybox');
	mix.scripts(['resources/assets/vendor/extra/fancybox/lib/jquery.mousewheel.pack.js'], 'public/js/vendor/extra/fancybox/lib/jquery.mousewheel.pack.js', './');
	mix.scripts(['resources/assets/vendor/extra/fancybox/source/jquery.fancybox.pack.js'], 'public/js/vendor/extra/fancybox/source/jquery.fancybox.pack.js', './');
	mix.scripts(['resources/assets/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.js'], 'public/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.js', './');
	mix.scripts(['resources/assets/vendor/extra/fancybox/source/helpers/jquery.fancybox-media.js'], 'public/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-media.js', './');
	mix.scripts(['resources/assets/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.js'], 'public/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.js', './');
	
	mix.styles(['resources/assets/vendor/extra/fancybox/source/jquery.fancybox.css'], 'public/js/vendor/extra/fancybox/source/jquery.fancybox.css', './');
	mix.styles(['resources/assets/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.css'], 'public/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.css', './');
	mix.styles(['resources/assets/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.css'], 'public/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.css', './');

    mix.copy('resources/assets/vendor/extra/tinymce', 'public/js/vendor/extra/tinymce');
    mix.scripts(['resources/assets/vendor/extra/tinymce/tinymce.min.js'], 'public/js/vendor/extra/tinymce/tinymce.min.js', './');

    mix.copy('resources/assets/vendor/extra/colorpicker', 'public/js/vendor/extra/colorpicker');
    mix.styles(['resources/assets/vendor/extra/colorpicker/css/colorpicker.css'], 'public/js/vendor/extra/colorpicker/css/colorpicker.css', './');
    mix.styles(['resources/assets/vendor/extra/colorpicker/css/layout.css'], 'public/js/vendor/extra/colorpicker/css/layout.css', './');
    mix.scripts(['resources/assets/vendor/extra/colorpicker/js/colorpicker.js'], 'public/js/vendor/extra/colorpicker/js/colorpicker.js', './');
    mix.scripts(['resources/assets/vendor/extra/colorpicker/js/eye.js'], 'public/js/vendor/extra/colorpicker/js/eye.js', './');
    mix.scripts(['resources/assets/vendor/extra/colorpicker/js/utils.js'], 'public/js/vendor/extra/colorpicker/js/utils.js', './');
    mix.scripts(['resources/assets/vendor/extra/colorpicker/js/layout.js'], 'public/js/vendor/extra/colorpicker/js/layout.js', './');

    mix.copy('resources/assets/vendor/jquery/datetimepicker', 'public/js/vendor/jquery/datetimepicker');
    mix.styles(['resources/assets/vendor/jquery/datetimepicker/jquery.datetimepicker.css'], 'public/js/vendor/jquery/datetimepicker/jquery.datetimepicker.css', './');
    mix.scripts(['resources/assets/vendor/jquery/datetimepicker/jquery.datetimepicker.full.min.js'], 'public/js/vendor/jquery/datetimepicker/jquery.datetimepicker.full.min.js', './');

    mix.copy('resources/assets/vendor/extra/countdown', 'public/js/vendor/extra/countdown');
    mix.styles(['resources/assets/vendor/extra/countdown/jcountdown.css'], 'public/js/vendor/extra/countdown/jcountdown.css', './');
    mix.scripts(['resources/assets/vendor/extra/countdown/jquery.browser.min.js'], 'public/js/vendor/extra/countdown/jquery.browser.min.js', './');
    mix.scripts(['resources/assets/vendor/extra/countdown/jquery.jcountdown.min.js'], 'public/js/vendor/extra/countdown/jquery.jcountdown.min.js', './');
});