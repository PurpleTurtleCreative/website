<?php
// Do not allow the file to be called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php _e( 'Access Denied', 'patchstack' ); ?></title>
	<meta name="robots" content="noindex, nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<style type="text/css">
		.grey{background-color:#9e9e9e!important}.grey.lighten-4{background-color:#f5f5f5!important}html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}a:active,a:hover{outline:0}html{-webkit-box-sizing:border-box;box-sizing:border-box}*,:after,:before{-webkit-box-sizing:inherit;box-sizing:inherit}a{color:#039be5;text-decoration:none;-webkit-tap-highlight-color:transparent}.card{-webkit-box-shadow:0 2px 2px 0 rgba(0,0,0,.14),0 1px 5px 0 rgba(0,0,0,.12),0 3px 1px -2px rgba(0,0,0,.2);box-shadow:0 2px 2px 0 rgba(0,0,0,.14),0 1px 5px 0 rgba(0,0,0,.12),0 3px 1px -2px rgba(0,0,0,.2)}.container{margin:0 auto;max-width:1280px;width:90%}@media only screen and (min-width:601px){.container{width:85%}}@media only screen and (min-width:993px){.container{width:70%}}.container .row{margin-left:-.75rem;margin-right:-.75rem}.row{margin-left:auto;margin-right:auto;margin-bottom:20px}.row:after{content:"";display:table;clear:both}.row .col{float:left;-webkit-box-sizing:border-box;box-sizing:border-box;padding:0 .75rem;min-height:1px}.row .col.s12{width:100%;margin-left:auto;left:auto;right:auto}a{text-decoration:none}html{line-height:1.5;font-family:sans-serif;font-weight:400;color:rgba(0,0,0,.87)}@media only screen and (min-width:0){html{font-size:14px}}@media only screen and (min-width:992px){html{font-size:14.5px}}@media only screen and (min-width:1200px){html{font-size:15px}}h4{font-weight:400;line-height:1.1}h4{font-size:2.28rem;line-height:110%;margin:1.14rem 0 .912rem 0}.card{position:relative;margin:.5rem 0 1rem 0;background-color:#fff;-webkit-transition:-webkit-box-shadow .25s;transition:-webkit-box-shadow .25s;transition:box-shadow .25s;transition:box-shadow .25s,-webkit-box-shadow .25s;border-radius:2px}.card .card-content{padding:24px;border-radius:0 0 2px 2px}.card .card-content p{margin:0;color:inherit}::-webkit-input-placeholder{color:#d1d1d1}::-moz-placeholder{color:#d1d1d1}:-ms-input-placeholder{color:#d1d1d1}::placeholder{color:#d1d1d1}.container{margin-top:50px;width:768px}.grey>div{font-size:18px;font-weight:400;text-shadow:0 1px 1px #fff;color:#726f6f}h4{font-size:20px}.card-content>a:last-child{margin-top:20px;display:inline-block;font-size:12px}.patchstack-logo{background-image:url("data:image/svg+xml,%3Csvg width='50' height='36' viewBox='0 0 50 36' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M0 36H12.4997V27H0V36Z' fill='%23AFE614'/%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M12.502 36H25.0017V27H12.502V36Z' fill='%236EBE00'/%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M25 26.9999H49.9995V0H25V26.9999Z' fill='%23AFE614'/%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M0 18.0002H24.9995V0.000244141H0V18.0002Z' fill='%236EBE00'/%3E%3C/svg%3E");display:block;width:50px;height:36px;position:absolute;top:17px;right:10px}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col s12">
				<div class="card">
					<div class="card-content grey lighten-4">
						<div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; padding-right:30px;"><?php _e( 'Access Denied', 'patchstack' ); ?> - <?php echo esc_url( 'http' . ( ( $_SERVER['SERVER_PORT'] == 443 ) ? 's://' : '://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); ?>
							<div>
								<a href="https://patchstack.com" class="patchstack-logo" target="_blank"></a>
							</div>
						</div>
					</div>
					<div class="card-content">
						<h4><?php _e( 'Login Page Access Denied', 'patchstack' ); ?></h4>
						<p><?php echo sprintf( __( 'This request has been blocked by <a href="%s" target="_blank">Patchstack</a> because you are not authorized to access this page.', 'patchstack' ), 'https://patchstack.com' ); ?></p>
						<p><?php _e( 'If you are a legitimate user, contact the administrator of the site if this message persists.', 'patchstack' ); ?></p>
						<a href="<?php echo esc_url( get_site_url() ); ?>"><?php _e( 'Return To Homepage', 'patchstack' ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
