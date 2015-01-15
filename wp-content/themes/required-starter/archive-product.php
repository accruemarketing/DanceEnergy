<?php
/**
 * Template Name: Fullwidth Page Template
 * Description: A Page Template without a sidebar
 *
 * @package required+ Foundation
 * @since required+ Foundation 0.2.0
 */
global $product, $woocommerce_loop;
get_header('shop');
//shop page options
$custom_shop_page_title = get_option("custom_shop_page_title");
$custom_shop_page_sub_title = get_option("custom_shop_page_sub_title");
//sales images
$shop_page_image_one = get_option("shop_page_image_one");
$shop_page_link_one = get_option("shop_page_link_one");
$shop_page_text_one = get_option("shop_page_text_one");

$shop_page_image_two = get_option("shop_page_image_two");
$shop_page_link_two = get_option("shop_page_link_two");
$shop_page_text_two = get_option("shop_page_text_two");
	
$shop_page_image_three = get_option("shop_page_image_three");
$shop_page_link_three = get_option("shop_page_link_three");
$shop_page_text_three = get_option("shop_page_text_three");
	

$shop_page_image_four = get_option("shop_page_image_four");
$shop_page_link_four = get_option("shop_page_link_four");
$shop_page_text_four = get_option("shop_page_text_four");

?>

	<!-- Row for main content area -->
	<div id="content" ng-controller="shoppage" ng-cloak>
		<div class="row">
			<div class="eight columns">
				<h1><?php echo $custom_shop_page_title; ?></h1>
			</div>
			<div class="four columns">

			</div>
		</div>
		<div class="row" id="header_shop">		
			<div class="three columns">
				<div class="row">
					<a href="<?php echo $shop_page_link_one; ?>">
						<span class="four columns">
							<img src="<?php echo $shop_page_image_one; ?>" alt="advert image" />
						</span>
						<span class="eight columns">
							<p class="shopadstext"><?php echo $shop_page_text_one; ?></p>
						</span>
					</a>
				</div>
			</div>
			<div class="three columns">
				<div class="row">
					<a href="<?php echo $shop_page_link_two; ?>">
						<span class="four columns">
							<img src="<?php echo $shop_page_image_two; ?>" alt="advert image" />
						</span>
						<span class="eight columns">
							<p class="shopadstext"><?php echo $shop_page_text_two; ?></p>
						</span>
					</a>
				</div>
			</div>
			<div class="three columns">
				<div class="row">
					<a href="<?php echo $shop_page_link_three; ?>">
						<span class="four columns">
							<img src="<?php echo $shop_page_image_three; ?>" alt="advert image" />
						</span>
						<span class="eight columns">
							<p class="shopadstext"><?php echo $shop_page_text_three; ?></p>
						</span>
					</a>
				</div>
			</div>
			<div class="three columns">
				<div class="row">
					<a href="<?php echo $shop_page_link_four; ?>">
						<span class="four columns">
							<img src="<?php echo $shop_page_image_four; ?>" alt="advert image" />
						</span>
						<span class="eight columns">
							<p class="shopadstext"><?php echo $shop_page_text_four; ?></p>
						</span>
					</a>
				</div>
			</div>						
		</div>
		<div class="row">
			<div class="searchfunctions">
				<div id="supplementary_shop" class="row">
					<div class="four columns">
						<h3 class="widget-title">Find What You Want</h3>			
					</div>
					<div class="textwidget eight column">
						<input type="text" placeholder="Search..." ng-model="search.post_title" id="searchboxshop">
					</div>
				</div>
					<div class="btn-group row" opt-kind="" ok-key="filter">
						<div ng-repeat="cat in categories" class="four columns">
							<button type="button" class="btn btn-default pickerbtn" ok-sel=".{{cat.slug}}" ng-click="parentclicked(cat)">{{cat.name}}</button>
						</div>
					</div>
					<div class="btn-groupchild" opt-kind="" ok-key="filter">
						<div ng-repeat="catchild in items = ( categorieschild | childcatsfilter:childtoshow )" class="childbtnrepeat" repeat-complete="doSomething( $index )" ng-show="childtoshow == catchild.parent">
							<button type="button" class="btn btn-default pickerbtnchild {{parentclass}}" ok-sel=".{{catchild.slug}}" ng-click="childclicked(catchild)">{{catchild.name}}</button>
						</div>
					</div>
					<div class="btn-groupchild" opt-kind="" ok-key="filter">
						<div ng-repeat="catchildchild in categorieschild | childcatsfilter:childchildtoshow " class="childbtnrepeat" repeat-complete="doSomething( $index )">
							<button type="button" class="btn btn-default pickerbtnchild {{parentclass}}" ok-sel=".{{catchildchild.slug}}" ng-click="childchildclicked(catchildchild)">{{catchildchild.name}}</button>
						</div>
					</div>					
			</div>
		</div>
		<div class="row">

			<div id="main" class="twelve columns" role="main">
				<h2 id="shoppagesub"><?php echo $custom_shop_page_sub_title; ?></h2>
				<div class="post-box woocommerce">
					<span us-spinner="{radius:30, width:8, length: 16}" spinner-key="spinner-1"></span>
					<div class="products row" isotope-container>
						<div ng-repeat="product in shoploop | filter:search" isotope-item class="{{product.classes}}">
							<a href="{{product.guid}}">
								<h3 class="title" >{{product.post_title}}</h3>
								<img ng-src="{{product.prod_img}}" alt="{{product.post_title}}">
							</a>
							<!--<div  class="enrollment_shop_wrap" ng-show="product.product_meta.wpcf_enrollment_course == 'yes' ">

											<p class="title_nextycourse">Next Course Starts</p>
											<p class="startdate">{{product.product_meta.wpcf_startdate}}</p>
											<p class="schedule">{{product.product_meta.wpcf_week_day}} {{product.product_meta.wpcf_starttime}}-{{product.product_meta.wpcf_endtime}}</p>
											<p class="course_length">{{product.product_meta.wpcf_course_length}} Classes</p>
											<div ng-bind-html="product.description | unsafe "></div>
							</div>
							<div ng-show="product.product_meta.wpcf_program_scheduletype == 'DropIn' ">	
										<h3>Drop in Class</h3>
										<p>{{product.product_meta.wpcf_week_day}} {{product.product_meta.wpcf_starttime}}-{{product.product_meta.wpcf_endtime}}</p>
										<div ng-bind-html="product.description | unsafe "></div>
							</div>-->
							<a ng-href="{{product.guid}}" class="addcart button">Read More</a>						
						</div>
					</div>

				</div>
			</div>
		</div><!-- /#main -->

	</div><!-- End Content row -->

<?php get_footer(); ?>