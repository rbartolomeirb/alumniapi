<?php
/**
 * Receives messages from the client
 * @author R. Bartolome
 * @version 2015-02-06 First version
 * @return JSON messages with the format:
 * {
 * 	"code": mandatory, string '0' for correct, '1' for error
 * 	"message": empty or string message
 * 	"data": empty or JSON data
 * }
 *
 * This file can be tested from the browser:
 * http://localhost/irbpeople-api/v1/service_test.php
 * 
 * Based on
 * http://www.raywenderlich.com/2941/how-to-write-a-simple-phpmysql-web-service-for-an-ios-app
 */

// API file
require_once 'alumniapi.php';

// Creates a new instance of the irbpeople_api class
$api = new alumniapi();

// message to return
$message = array();

switch($_POST["action"])
{
	case 'save_personal':
		$params = array();
		$params['alumni_personalcode'] = isset($_POST["alumni_personalcode"]) ? $_POST["alumni_personalcode"] : '';
		// mandatory. If not set, it breaks IRBpeople
		if (!empty($_POST["titles"]) && $api->valid_title($_POST["titles"])) {
			$params['titles'] = $_POST["titles"];
		} else {
			$message["code"] = "1";
			$message["message"] = "The title value is not set or is not valid";
			break;
		}
		$params['firstname'] = isset($_POST["firstname"]) ? $_POST["firstname"] : '';
		$params['surname'] = isset($_POST["surname"]) ? $_POST["surname"] : '';
		$params['irb_surname'] = isset($_POST["irb_surname"]) ? $_POST["irb_surname"] : '';
		// mandatory. If not set, it breaks IRBpeople
		if (!empty($_POST["gender"]) && $api->valid_gender($_POST["gender"])) {
			$params['gender'] = $_POST["gender"];
		} else {
			$message["code"] = "1";
			$message["message"] = "The gender value is not set or is not valid";
			break;
		}
		// mandatory. If not set, it breaks IRBpeople
		if (!empty($_POST["nationality"]) && $api->valid_nationality($_POST["nationality"])) {
			$params['nationality'] = $_POST["nationality"];
		} else {
			$message["code"] = "1";
			$message["message"] = "The nationality value is not set or is not valid";
			break;
		}
		$params['nationality_2'] = isset($_POST["nationality_2"]) ? $_POST["nationality_2"] : '';
		$params['birth'] = isset($_POST["birth"]) ? $_POST["birth"] : '';
		$params['email'] = isset($_POST["email"]) ? $_POST["email"] : '';
		$params['url'] = isset($_POST["url"]) ? $_POST["url"] : '';
		$params['facebook'] = isset($_POST["facebook"]) ? $_POST["facebook"] : '';
		$params['linkedin'] = isset($_POST["linkedin"]) ? $_POST["linkedin"] : '';
		$params['twitter'] = isset($_POST["twitter"]) ? $_POST["twitter"] : '';
		$params['keywords'] = isset($_POST["keywords"]) ? $_POST["keywords"] : '';
		$params['biography'] = isset($_POST["biography"]) ? $_POST["biography"] : '';
		$params['awards'] = isset($_POST["awards"]) ? $_POST["awards"] : '';
		$params['ORCIDID'] = isset($_POST["ORCIDID"]) ? $_POST["ORCIDID"] : '';
		$params['researcherid'] = isset($_POST["researcherid"]) ? $_POST["researcherid"] : '';
		$params['pubmedid'] = isset($_POST["pubmedid"]) ? $_POST["pubmedid"] : '';
		$params['show_data'] = isset($_POST["show_data"]) ? $_POST["show_data"] : '';
		$params['external_jobs'] = isset($_POST["external_jobs"]) ? $_POST["external_jobs"] : '';	
			
		if ($alumni_personalcode = $api->save_personal($params)) {
			$message["code"] = "0";
			$message["data"] = $alumni_personalcode;
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on save method";		
		}
		break;
		
	case 'save_external_jobs':
		$params = array();
		if (empty($_POST['alumni_personalcode']))
		{
			$message["code"] = "1";
			$message["message"] = "The alumni personal code is not set";
			break;
		}
		$params['alumni_personalcode'] = $_POST["alumni_personalcode"];
		$params['start_date'] = isset($_POST["start_date"]) ? $_POST["start_date"] : '';
		$params['end_date'] = isset($_POST["end_date"]) ? $_POST["end_date"] : '';
		$params['external_job_positions'] = isset($_POST["external_job_positions"]) ? $_POST["external_job_positions"] : '';
		$params['comments'] = isset($_POST["comments"]) ? $_POST["comments"] : '';		
		$params['external_job_sectors'] = isset($_POST["external_job_sectors"]) ? $_POST["external_job_sectors"] : '';
		$params['institution'] = isset($_POST["institution"]) ? $_POST["institution"] : '';
		$params['address'] = isset($_POST["address"]) ? $_POST["address"] : '';
		$params['postcode'] = isset($_POST["postcode"]) ? $_POST["postcode"] : '';
		$params['city'] = isset($_POST["city"]) ? $_POST["city"] : '';
		// validates data
		if (empty($_POST['country']) || !$api->valid_country($_POST['country']))
		{
			$message["code"] = "1";
			$message["message"] = "The Country code in External Jobs is not set or is not valid";
			break;
		}
		$params['country'] = isset($_POST["country"]) ? $_POST["country"] : '';
		$params['telephone'] = isset($_POST["telephone"]) ? $_POST["telephone"] : '';
		$params['current'] = isset($_POST["current"]) ? $_POST["current"] : '';
				
		if ($api->save_external_jobs($params)) {
			$message["code"] = "0";
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on save method";
		}
		break;
		
	case 'save_communications':
		$params = array();
		if (empty($_POST['alumni_personalcode']))
		{
			$message["code"] = "1";
			$message["message"] = "The alumni personal code is not set";
			break;
		}
		if (empty($_POST['alumni_communicationscode']))
		{
			$message["code"] = "1";
			$message["message"] = "The alumni communications code is not set";
			break;
		}
		$params['alumni_personalcode'] = $_POST["alumni_personalcode"];
		$params['alumni_communicationscode'] = $_POST["alumni_communicationscode"];

		if ($api->save_communications($params)) {
			$message["code"] = "0";
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on save method";
		}
		break;
				
	case 'remove_external_jobs':
		// validates data
		if (empty($_POST['alumni_personalcode']))
		{
			$message["code"] = "1";
			$message["message"] = "The alumni code is empty";
			break;
		}
		
		if ($api->remove_external_jobs($_POST['alumni_personalcode'])) {
			$message["code"] = "0";
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on remove method";
		}		
		break;
		
	case 'remove_communications':
		// validates data
		if (empty($_POST['alumni_personalcode']))
		{
			$message["code"] = "1";
			$message["message"] = "The alumni code is empty";
			break;
		}
		
		if ($api->remove_communications($_POST['alumni_personalcode'])) {
			$message["code"] = "0";
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on remove method";
		}
		break;
		
	case 'get':
		$params = array();
		$params['alumni_personalcode'] = isset($_POST["alumni_personalcode"]) ? $_POST["alumni_personalcode"] : '';		
		if (is_array($data = $api->get($params))) {
			$message["code"] = "0";
			$message["data"] = $data;
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on get method";
		}
		break;
	
	case 'get_titles':
		if ($data = $api->get_titles()) {
			$message["code"] = "0";
			$message["data"] = $data;
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on get_titles method";		
		}
		break;
		
	case 'get_nationalities':
		if ($data = $api->get_nationalities()) {
			$message["code"] = "0";
			$message["data"] = $data;
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on get_nationalities method";
		}
		break;
		
	case 'get_genders':
		if ($data = $api->get_genders()) {
			$message["code"] = "0";
			$message["data"] = $data;
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on get_genders method";
		}
		break;
		
	case 'get_countries':
		if ($data = $api->get_countries()) {
			$message["code"] = "0";
			$message["data"] = $data;
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on get_countries method";
		}
		break;

	case 'get_communications':
		if ($data = $api->get_communications()) {
			$message["code"] = "0";
			$message["data"] = $data;
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on get_communications method";
		}
		break;
				
	case 'get_external_jobs_positions':
		if ($data = $api->get_external_jobs_positions()) {
			$message["code"] = "0";
			$message["data"] = $data;
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on get_external_jobs_positions method";
		}
		break;
				
	case 'get_external_jobs_sectors':
		if ($data = $api->get_external_jobs_sectors()) {
			$message["code"] = "0";
			$message["data"] = $data;
		} else {
			$message["code"] = "1";
			$message["message"] = "Error on get_external_jobs_sectors method";
		}
		break;
	
	default:
		$message["code"] = "1";
		$message["message"] = "Unknown method " . $_POST["action"];
		break;
}

//the JSON message
header('Content-type: application/json; charset=utf-8');
echo json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHED);

?>
