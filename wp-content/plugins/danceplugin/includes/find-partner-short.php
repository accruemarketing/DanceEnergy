<?php 
	/**
	 * 
	 *Find a partner loop
	 *
	 *
	 **/
	$getpagemeta = get_post_meta( );
	
	//initial check if user is logged in
	if(is_user_logged_in()){
		//ok so they are logged in show em the loop
?>
		<div class="findpartner" ng-controller="findpartner"  ng-cloak>
 	<div class="showhidewrap" ng-show="!singlepartner">
 		<div class="row">
 			<div class="six columns">
 				<p class="button fltright" ng-click="searchit()">Search</p>
 			</div>
 			<div class="six columns">
 				<p class="button fltright" ng-click="getsavedonly()" ng-show="showsaved">View Saved Listings</p>
 				<p class="button fltright" ng-click="showallpartner()" ng-show="!showsaved">Show All</p>
 			</div>
 		</div>
 		<div class="row" ng-hide="searchcontent">
 			<div class="twelve columns">
	 			<label for="search">
	 				<input type="text" placeholder="Search..." ng-model="search.user_nicename" id="searchboxshop">
	 			</label>
 			</div>
 		</div>
	 	<div class="row">
	 		<div class="four columns">
	 			<p><span ng-click="checkAll()" class="btnsmall">Select All</span>/<span class="btnsmall" ng-click="uncheckall()">Unselect All</span></p>
	 		</div>
	 	</div>
		<span us-spinner="{radius:30, width:8, length: 16}" spinner-key="spinner-1"></span>
		<p>{{filteredpartners.length}}</p>
	 	<div ng-repeat="partner in filteredpartners = (findpartnerall | array | filter:search)" class="row partnerrow" ng-show="!singlepartner" >
	 		<div class="four columns">
	 			<div class="six columns">
	 				<input type="checkbox" name="checkbox_{{$index}}" id="checkbox_{{$index}}" ng-model="partner.selected" >
	 			</div>
	 			<div class="six columns">
	 				<div ng-bind-html="partner.avatar | unsafe" ></div>
	 			</div>
	 		</div>
	 		<div class="eight columns">
	 			<div class="eight columns">
	 				<p><span>{{partner.user_nicename}}</span> <span ng-show="partner.Location.field_data != undefined">in {{partner.Location.field_data}}</span> </p>
	 				<p ng-bind-html="partner.descclean | unsafe"></p>
	 			</div>
	 			<div class="four columns">
	 				<p class="button" ng-click="loadmore(partner.ID, partner.user_nicename)">Read More</p>
	 			</div>
	 		</div>
	 	</div>
 	</div>
	<div class="row" ng-show="!singlepartner">
		<div class="four columns">
			<p class="button fltright" ng-click="SaveListing()">Save Selected Listings</p>
		</div>
		<div class="four columns">
			<p class="button fltright" ng-click="DeleteSelected()">Delete Selected Listings</p>
		</div>
		<div class="four columns">
	 		<p>&nbsp;</p>
		</div>
	</div> 	
 	<div class="row" ng-show="singlepartner">
 		<div class="eight columns">
 			
 		</div>
 		<div class="four columns">
 			<p class="button" ng-click="back()">Back to results</p>
 		</div>
 		 <div class="four columns">
 			<div ng-bind-html="findpartner.user.avatar | unsafe" ></div>
 		</div>
 		<div class="eight columns">
 			<p>{{findpartner.user.user_nicename}} in {{findpartner.user.Location.field_data}}</p>
 			<p ng-bind-html="findpartner.user.descclean | unsafe"></p>
 		</div>
 	</div>
		</div>
<?php }else{ //User not logged in ?>
		<div class="row">
			<p>Why not Create and account if you want to find a Dance Partner: <br><a href="/register" class="button">Register</a></p>
		</div>
<?php } ?>