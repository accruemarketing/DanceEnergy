<?php
require_once("../includes/classService.php");
//require_once("../includes/clientService.php");
$sourcename = "AccrueMarketing";
$password = "VvcW7fbmwIQSavb3uPJME2SFUsM=";
$siteID = "4639";
// initialize default credentials
$creds = new SourceCredentials($sourcename, $password, array($siteID));

$classService = new MBClassService();
$classService->SetDefaultCredentials($creds);

$result = $classService->GetClassDescriptions(array(), array(), array(), null, null, 150, 0);

$resultClass = $classService->GetClasses(array(), array(), array(), null, null, null, 4639, 150 );

$jsonit = json_encode($result);

$jsonitfull = json_encode($resultClass);
echo '<pre>';
//var_dump($resultClass);
var_dump($result);
echo "</pre>";
?>
<!DOCTYPE> 
<html>
	<head>
		<title>List Class Descriptions Demo</title>
		<link rel="stylesheet" type="text/css" href="../styles/site.css" />
		<link rel="stylesheet" id="foundation-css-css" href="http://dancenergy.zenutech.com/wp-content/themes/required-foundation/stylesheets/foundation.min.css?ver=3.2.5" type="text/css" media="all">
		<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.12/angular.min.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.12/angular-animate.js"></script>
		<script type="text/javascript">
			angular.module('ngAppDemo', ['ngAnimate']).controller('ngAppDemoController', function($scope) {
			  $scope.array = <?php  echo $jsonit; ?>;'\n'
			  $scope.arrayfull = <?php echo $jsonitfull; ?>;'\n'

			});
		</script>
		<style type="text/css">
  .example-animate-container {
    background:white;
    border:1px solid black;
    list-style:none;
    margin:0;
    padding:0 10px;
  }
 
  .animate-repeat {
    list-style:none;
    box-sizing:border-box;
  }
 
  .animate-repeat.ng-move,
  .animate-repeat.ng-enter,
  .animate-repeat.ng-leave {
    -webkit-transition:all linear 0.5s;
    transition:all linear 0.5s;
  }
 
  .animate-repeat.ng-leave.ng-leave-active,
  .animate-repeat.ng-move,
  .animate-repeat.ng-enter {
    opacity:0;
  }
 
  .animate-repeat.ng-leave,
  .animate-repeat.ng-move.ng-move-active,
  .animate-repeat.ng-enter.ng-enter-active {
    opacity:1;
  }
		</style>
	</head>
	<body ng-app="ngAppDemo" class="row">
<script type="text/javascript">
</script>
<div ng-controller="ngAppDemoController">
	<br>
	<br><br>

	<div class="row">
		<h1>search for your Class</h1>
		<h3><input class="twelve columns" ng-model="searchText" placeholder="SEARCH QUERY HERE"></h3>
			<br>
	<br><br>
</div>
	  <div class="animate-repeat panel" ng-repeat="items in arrayfull.GetClassesResult.Classes.Class | filter:searchText">
	        <div class="row">
		        <div class="four columns">
		        	<h3>Class Name:</h3>
		        	<p>{{items.ClassDescription.Name}}</p>
			 	</div>
		       	<div class="four columns">
		         	<h3>Class Description:</h3> 
		         	<p>{{items.ClassDescription.Description}}</p> 
		      	</div>
				<div class="four columns">
	         		<h3>Level:</h3> 
	         		<p>{{items.ClassDescription.Level.Name}}</p>
				</div>
			</div>
			<div class="row">
		        <div class="four columns">
		        	<h3>Is avaiable:</h3>
					<p>{{items.IsAvailable}}</p>
			 	</div>
		       	<div class="four columns">
		         	<h3>Contact Details:</h3> 
					<h5>Teacher:</h5>
					<p>{{items.Staff.FirstName}} {{items.Staff.LastName}}</p>
		         	<p>Phone: {{items.Staff.MobilePhone}}</p>
		         	<p>{{items.Staff.State}}</p>
		      	</div>
				<div class="four columns">
	         		<h3>Start time:</h3> 
	         		<p>{{items.StartDateTime}}</p>
				</div>			
			</div>
</div>	

<?php
//var_dump($resultClass);


$cdsHtml = '<table><tr><td>ID</td><td>Name</td></tr>';
$cds = toArray($result->GetClassDescriptionsResult->ClassDescriptions->ClassDescription);
foreach ($cds as $cd) {
	$cdsHtml .= sprintf('<tr><td>%d</td><td>%s</td></tr>', $cd->ID, $cd->Name);
}
$cdsHtml .= '</table>';
	
//echo($cdsHtml); 
?>
	</body>
</html>