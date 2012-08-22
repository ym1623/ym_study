<?php

//EXAMPLES 
$htmlPage = 'tab.html';
$htmlPage = file_get_contents($htmlPage);
$htmlTitle = TextPatternParser::parseBetweenText($htmlPage, '<span class="switchBtn">', '</span>');
print_r($htmlTitle);

$arrayOfImagesOnpage = TextPatternParser::parseBetweenText($htmlPage, 'class="miniature"><img src="', '" onMouseOver="startThumbSlide(');

class TextPatternParser {

	//$text = the string to parse values from
	//$beginText = the begining value to search for, parseBetweenText looks between $beginText and $endText
	//$endText = end value to look for
	//$removeSpace - perform trim on value
	//$removeHtmlTags - removes any html from end result array values
	//$firstResultOnlyNoArray - only will return one result - the first one instead of an array

	public static function parseBetweenText(
		$text, 
		$beginText, 
		$endText, 
		$removeSpace=true, 
		$removeHtmlTags=true,
		$firstResultOnlyNoArray=false) {
		$results = array();
		$endPos = 0;
		while(true) {
			$beginPos = stripos($text, $beginText, $endPos);
			if($beginPos===false) break;
			$beginPos = $beginPos+strlen($beginText);
			$endPos = stripos($text, $endText, $beginPos);
			if($endPos===false) break;
			$result = substr($text, $beginPos, $endPos-$beginPos);
			if($removeSpace){
				$result = str_replace("\t","",$result);
				$result = str_replace("\n","",$result);
				$result = preg_replace("/  /"," ",$result);
				$result = preg_replace("~[\s]{2}?[\t]?~i"," ",$result);
				$result = str_replace("  "," ",$result);
				$result = trim($result);
			}
			if($removeHtmlTags){
				$result = strip_tags($result);
			}
			if($firstResultOnlyNoArray) return $result;
			if($result != '') $results[] = $result;
		}
		return ($firstResultOnlyNoArray && empty($results) ? '' : $results) ;
	}

}
