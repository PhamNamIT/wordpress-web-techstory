<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link    https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package flatsome
 */

//get_header(); ?>
<?php do_action( 'flatsome_before_404' ); ?>
<?php
if ( get_theme_mod( '404_block' ) ) :
	echo do_shortcode( '[block id="' . get_theme_mod( '404_block' ) . '"]' );
else :
?>
    <!-- Mod 1 -->
	<!-- <div id="primary" class="content-area">
		<main id="main" class="site-main container pt" role="main">
			<section class="error-404 not-found mt mb">
				<div class="row">
					<div class="col medium-3"><span class="header-font" style="font-size: 6em; font-weight: bold; opacity: .3">404</span></div>
					<div class="col medium-9">
						<header class="page-title">
							<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'flatsome' ); ?></h1>
						</header>
						<div class="page-content">
							<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'flatsome' ); ?></p>
							<?php get_search_form(); ?>
						</div>
					</div>
				</div>
			</section>
		</main>
	</div> -->
	<!-- Mod 2 -->
	<!-- <body style="margin: 0">
    	<div style="background-image: url('http://techstory.fun/wp-content/uploads/2021/11/fastcomet-wordpress-404-error-661x400.png'); background-size: cover; width: 100%; height: 100%;">
            <button class="back-page" style="position: relative; top: 450px; left: 400px; height: 40px; border-radius: 20px; cursor: pointer;" type="button">Quay lại</button>
    	</div>
	</body> -->
	<!-- Mod 3 -->
	
    <head>
        <meta charset="utf-8" />
        <title>Không thấy trang - TechStory</title>
        <meta name="author" content="Laivanduc" />
        <meta name="keywords" content="keywords của bạn" />
        <meta name="description" content="description của bạn" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <style>
    @import url(http://fonts.googleapis.com/css?family=opensans:500);
    body{
        background: #259af2;
        color:#fff;
        font-family: 'Open Sans', sans-serif;
        max-height:700px;
        overflow: hidden;
    }
    .c{
        text-align: center;
        display: block;
        position: relative;
        width:80%;
        margin:100px auto;
    }
    ._404{
        font-size: 220px;
        position: relative;
        display: inline-block;
        z-index: 2;
        height: 250px;
        letter-spacing: 15px;
    }
    ._1{
        text-align:center;
        display:block;
        position:relative;
        letter-spacing: 12px;
        font-size: 4em;
        line-height: 80%;
        margin: 8px 0;
    }
    ._2{
        text-align:center;
        display:block;
        position: relative;
        font-size: 20px;
        margin: 6px 0;
    }
    .text{
        font-size: 70px;
        text-align: center;
        position: relative;
        display: inline-block;
        margin: 19px 0px 0px 0px;
        /* top: 256.301px; */
        z-index: 3;
        width: 100%;
        line-height: 1.2em;
        display: inline-block;
    }
    .btn_back{
        background-color: rgb( 255, 255, 255 );
        position: relative;
        display: inline-block;
        width: 358px;
        padding: 5px;
        z-index: 5;
        font-size: 25px;
        margin:0 auto;
        color:#33cc99;
        text-decoration: none;
        margin-right: 10px;
        border: none;
		cursor: pointer;
    }
    .right{
        float:right;
        width:60%;
    }         
    hr{
        padding: 0;
        border: none;
        border-top: 5px solid #fff;
        color: #fff;
        text-align: center;
        margin: 0px auto;
        width: 420px;
        height:10px;
        z-index: -10;
    }            
    hr:after {
        content: "\2022";
        display: inline-block;
        position: relative;
        top: -0.75em;
        font-size: 2em;
        padding: 0 0.2em;
        background: #259af2;
    }
    .cloud {
        width: 350px; height: 120px;
        background: #FFF;
        background: linear-gradient(top, #FFF 100%);
        background: -webkit-linear-gradient(top, #FFF 100%);
        background: -moz-linear-gradient(top, #FFF 100%);
        background: -ms-linear-gradient(top, #FFF 100%);
        background: -o-linear-gradient(top, #FFF 100%);
        border-radius: 100px;
        -webkit-border-radius: 100px;
        -moz-border-radius: 100px;
        position: absolute;
        margin: 120px auto 20px;
        z-index:-1;
        transition: ease 1s;
    }
    .cloud:after, .cloud:before {
        content: '';
        position: absolute;
        background: #FFF;
        z-index: -1
    }
    .cloud:after {
        width: 100px; height: 100px;
        top: -50px; left: 50px;
        border-radius: 100px;
        -webkit-border-radius: 100px;
        -moz-border-radius: 100px;
    }
    .cloud:before {
        width: 180px; height: 180px;
        top: -90px; right: 50px;
        border-radius: 200px;
        -webkit-border-radius: 200px;
        -moz-border-radius: 200px;
    }
    .x1 {
        top:-50px;
        left:100px;
        -webkit-transform: scale(0.3);
        -moz-transform: scale(0.3);
        transform: scale(0.3);
        opacity: 0.9;
        -webkit-animation: moveclouds 15s linear infinite;
        -moz-animation: moveclouds 15s linear infinite;
        -o-animation: moveclouds 15s linear infinite;
    }
    .x1_5{
        top:-80px;
        left:250px;
        -webkit-transform: scale(0.3);
        -moz-transform: scale(0.3);
        transform: scale(0.3);
        -webkit-animation: moveclouds 17s linear infinite;
        -moz-animation: moveclouds 17s linear infinite;
        -o-animation: moveclouds 17s linear infinite;
    }
    .x2 {
        left: 250px;
        top:30px;
        -webkit-transform: scale(0.6);
        -moz-transform: scale(0.6);
        transform: scale(0.6);
        opacity: 0.6;
        -webkit-animation: moveclouds 25s linear infinite;
        -moz-animation: moveclouds 25s linear infinite;
        -o-animation: moveclouds 25s linear infinite;
    }
    .x3 {
        left: 250px; bottom: -70px;
        -webkit-transform: scale(0.6);
        -moz-transform: scale(0.6);
        transform: scale(0.6);
        opacity: 0.8;
        -webkit-animation: moveclouds 25s linear infinite;
        -moz-animation: moveclouds 25s linear infinite;
        -o-animation: moveclouds 25s linear infinite;
    }
    .x4 {
        left: 470px; botttom: 20px;
        -webkit-transform: scale(0.75);
        -moz-transform: scale(0.75);
        transform: scale(0.75);
        opacity: 0.75;
        -webkit-animation: moveclouds 18s linear infinite;
        -moz-animation: moveclouds 18s linear infinite;
        -o-animation: moveclouds 18s linear infinite;
    }
    .x5 {
        left: 200px; top: 300px;
        -webkit-transform: scale(0.5);
        -moz-transform: scale(0.5);
        transform: scale(0.5);
        opacity: 0.8;
        -webkit-animation: moveclouds 20s linear infinite;
        -moz-animation: moveclouds 20s linear infinite;
        -o-animation: moveclouds 20s linear infinite;
    }
    @-webkit-keyframes moveclouds {
        0% {margin-left: 1000px;}
        100% {margin-left: -1000px;}
    }
    @-moz-keyframes moveclouds {
        0% {margin-left: 1000px;}
        100% {margin-left: -1000px;}
    }
    @-o-keyframes moveclouds {
        0% {margin-left: 1000px;}
        100% {margin-left: -1000px;}
    }
    </style>
    </head>
    <body>
        <div id="clouds">
            <div class="cloud x1"></div>
            <div class="cloud x1_5"></div>
            <div class="cloud x2"></div>
            <div class="cloud x3"></div>
            <div class="cloud x4"></div>
            <div class="cloud x5"></div>
        </div>
        <div class='c'>
            <div class='_404'>404</div>
            <hr>
            <div class='_1'>TRANG NÀY</div>
            <div class='_2'>KHÔNG TỒN TẠI</div>
            <button class='btn_back' onclick='window.history.back()'>Quay lại</button>
        </div>
    </body>
	
<?php endif; ?>
<?php do_action( 'flatsome_after_404' ); ?>
<?php //get_footer(); ?>
