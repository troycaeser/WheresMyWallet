<?php
	// include_once("{$_SERVER['DOCUMENT_ROOT']}/myApp/api/functions/getHTMLContents.php");
	// include_once("{$_SERVER['DOCUMENT_ROOT']}/myApp/api/functions/linkProcessing.php");
	// include_once("{$_SERVER['DOCUMENT_ROOT']}/myApp/api/functions/makeTemplateDir.php");
	// include_once("{$_SERVER['DOCUMENT_ROOT']}/myApp/api/functions/resize.php");
	// include_once("{$_SERVER['DOCUMENT_ROOT']}/myApp/api/functions/finaliseMain.php");
	// include_once("{$_SERVER['DOCUMENT_ROOT']}/myApp/api/functions/loadJsonObject.php");
	
	include_once("{$_SERVER['DOCUMENT_ROOT']}/api/functions/getHTMLContents.php");
	include_once("{$_SERVER['DOCUMENT_ROOT']}/api/functions/linkProcessing.php");
	include_once("{$_SERVER['DOCUMENT_ROOT']}/api/functions/makeTemplateDir.php");
	include_once("{$_SERVER['DOCUMENT_ROOT']}/api/functions/resize.php");
	include_once("{$_SERVER['DOCUMENT_ROOT']}/api/functions/finaliseMain.php");

	$app->post('/crawl', function() use ($app){
		//get parameter via json_decode()->name
		$request = $app->request();
		$body = $request->getBody();
		$result = json_decode($body);
		$site;

		$url = $result->url;

		$links = array();
		finaliseLinks($url, $links);

		$propertyInfo = array();
		getHTML($url, $propertyInfo);


		$templateDir = array();
		$templateDirWeb = array();
		makeTemplateDir($propertyInfo, $templateDir, $templateDirWeb);

		$result = array(
			'links' => $links,
			'propertyInfo' => $propertyInfo,
			'templateDir' => $templateDir,
			'templateDirWeb' => $templateDirWeb
		);

		header("Content-Type: application/json");
		echo json_encode($result);
	});

	$app->get('/test', function() use ($app){
		$url = "http://www.realestate.com.au/property-house-vic-mount+waverley-121481678";

		$propertyInfo = array();
		getHTML ($url, $propertyInfo);
		
		$templateDir = array();
		$templateDirWeb = array();
		makeTemplateDir ($propertyInfo, $templateDir, $templateDirWeb);

		echo "<pre>";
			print_r ($propertyInfo);
		echo "</pre>";

		$resizedDir = "{$_SERVER['DOCUMENT_ROOT']}/api/assets/testDraw/";
		$imgUrl = "{$_SERVER['DOCUMENT_ROOT']}/api/assets/testDraw/main.jpg";
		$resizedUrl = "";
		
		$jsonObject = get_json_object ($propertyInfo['agency_localDir']);
		resizeSingleMain ($imgUrl, $jsonObject, $resizedDir, $resizedUrl);

		finaliseMainAdItem ($propertyInfo, $resizedUrl);

		// $imgUrls = array (
		// 	"{$_SERVER['DOCUMENT_ROOT']}/myApp/api/assets/testResize/main-resized.jpg",
		// 	"{$_SERVER['DOCUMENT_ROOT']}/myApp/api/assets/testResize/1-resized.jpg",
		// 	"{$_SERVER['DOCUMENT_ROOT']}/myApp/api/assets/testResize/2-resized.jpg"
		// );

		// allocateLogo ($imgUrls, $jsonObject, $templateDir);

		// $imgs = array();
		// finaliseLinks ($url, $imgs);

		// $imgUrls = array();
	});
// does it matter at all?
?>
