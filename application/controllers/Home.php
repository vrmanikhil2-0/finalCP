<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library(array('session', 'function_lib', 'skill_lib'));
		$this->load->helper(array('url'));
		$this->data = array();

		$this->data['headerFiles'] =  $this->load->view('commonCode/headerFiles',$this->data,true);
		$this->data['footerFiles'] =  $this->load->view('commonCode/footerFiles',$this->data,true);
		$this->data['nav'] =  $this->load->view('commonCode/nav',$this->data,true);
		$this->data['footer'] =  $this->load->view('commonCode/footer',$this->data,true);

		$this->data['message'] = ($v = $this->session->flashdata('message'))?$v:array('content'=>'','color'=>'');

		// $this->data['csrf_token_name'] = $this->security->get_csrf_token_name();
	}

	public function pageNotFound(){
		$this->load->view('404', $this->data);
	}

	public function index(){
		if($this->function_lib->auth()){
			redirect(base_url('general-details'));
		}
		else{
			$this->data['pageTitle'] = "CampusPuppy";
			$this->load->view('home', $this->data);
		}
	}

	public function dextryx(){
		if($this->function_lib->auth()){
			redirect(base_url('general-details'));
		}
		else{
			$this->data['pageTitle'] = "CampusPuppy";
			$this->load->view('dextryx', $this->data);
		}
	}

	public function emailer(){
			$this->load->view('emailers/offers', $this->data);
	}


	public function verifyContactDetails(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				redirect(base_url('general-details'));
			}
			else{
				$this->data['pageTitle'] = "Verify Contact Details";
				$this->data['activePage'] = "0";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->generateVerificationCode(1);
				$this->generateVerificationCode(2);
				$this->load->view('verifyContactDetails', $this->data);
			}
		}
		else{
			redirect(base_url());
		}

	}

	public function generalDetails(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "General Details";
				$this->data['activePage'] = "2";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->data['generalData'] = $this->function_lib->getUserData($_SESSION['user_data']['email']);
				$this->data['generalData'] = $this->data['generalData'][0];
				if($_SESSION['user_data']['accountType'] == 2)
					$this->data['companyData'] = $this->function_lib->getCompanyData($this->data['generalData']['userID']);
				$this->data['locations'] = $this->function_lib->getAllLocations();
				$this->data['preferredLocation'] = $this->function_lib->getPreferredLocations($_SESSION['user_data']['userID']);
				$this->load->view('generalDetails', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}

	}


	public function skills(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Skills";
				$this->data['activePage'] = "3";
				if(!empty($x = $this->skill_lib->getPremiumSkills($_SESSION['user_data']['userID'])))
					$this->data['premiumSkills'] = $x;
				else
					$this->data['premiumSkills'] = null;
				if(!empty($x = $this->skill_lib->getOtherSkills($_SESSION['user_data']['userID'])))
					$this->data['otherSkills'] = $x;
				else
					$this->data['otherSkills'] = null;
				// var_dump($x);die;
				if(!empty($x = $this->skill_lib->getNotAddedSkills($_SESSION['user_data']['userID'])))
					$this->data['skills'] = $x;
				else
					$this->data['skills'] = null;
				// var_dump($x);die;
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->load->view('skills', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function skillTest(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				if($_SESSION['userData']['intest']){
				// if(false){
					$_SESSION['questionData'] = NULL;
					$skill_id = $_SESSION['userData']['currentSkill'];
					$_SESSION['userData']['currentSkill'] = NULL;
					$_SESSION['userData']['currentSkillName'] = NULL;
					$_SESSION['userData'][$skill_id]['totalScore'] = NULL;
					$_SESSION['userData'][$skill_id]['skips'] = NULL;
					$_SESSION['userData'][$skill_id]['skipStatus'] = NULL;
					$_SESSION['userData'][$skill_id]['totalTime'] = NULL;
					$_SESSION['userData'][$skill_id]['responses'] = NULL;
					$_SESSION['userData']['intest'] = false;
					$this->session->set_flashdata('message', array('content'=>'Page Reload Not allowed During test.','color'=>'red'));
					redirect(base_url('skills'));
				}
				$_SESSION['userData']['intest'] = true;
				$this->data['skillData']['skillID'] = $_SESSION['userData']['currentSkill'];
				$this->data['skillData']['skillName'] = $_SESSION['userData']['currentSkillName'];
				$this->data['questionData'] = $_SESSION['questionData'];
				$totalTime = $_SESSION['userData'][$_SESSION['userData']['currentSkill']]['totalTime'];
				$this->data['totalTime'] = $totalTime;
				$this->data['skips'] = $_SESSION['userData'][$_SESSION['userData']['currentSkill']]['skips'];
				$this->data['pageTitle'] = "Skill Test";
				$this->data['activePage'] = "3";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->load->view('skillTest', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function skillTestGuidelines(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Skill Test Guidelines";
				$this->data['activePage'] = "3";
				$this->data['skill'] = $_SESSION['userData']['currentSkill'];
				$this->data['settings'] = $this->skill_lib->getTestSettings($_SESSION['userData']['currentSkill'])[0];
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->load->view('skillTestGuidelines', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function educationalDetails(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Educational Details";
				$this->data['activePage'] = "4";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->data['educations'] = $this->function_lib->getUserEducationalDetails($_SESSION['user_data']['userID']);
				$this->data['colleges'] = $this->function_lib->getColleges();
				$this->data['courses'] = $this->function_lib->getCourses();
				$this->load->view('educationalDetails', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function workExperience(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Work Experience";
				$this->data['activePage'] = "5";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->data['workExperience'] = $this->function_lib->getUserWorkExperience($_SESSION['user_data']['userID']);
				$this->load->view('workExperience', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function resume(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Resume";
				$this->data['activePage'] = "6";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->data['resumeReferenceNumber'] = $this->function_lib->getUserData($_SESSION['user_data']['email']);
				$this->data['resumeReferenceNumber'] = $this->data['resumeReferenceNumber'][0]['resumeReferenceNumber'];
				$this->load->view('resume', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function changePassword(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Change Password";
				$this->data['activePage'] = "7";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->load->view('changePassword', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function addNewOffer(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Add New Offer";
				$this->data['activePage'] = "9";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->data['skills'] = $this->function_lib->getSkills();
				$this->data['locations'] = $this->function_lib->getAllLocations();
				if(isset($_SESSION['redirect']))
					$this->data['redirect'] = $_SESSION['redirect'];
				unset($_SESSION['redirect']);
				$this->load->view('addNewOffer', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function editOffer($offerID){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Edit Offer";
				$this->data['activePage'] = "9";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->data['skills'] = $this->function_lib->getSkills();
				$this->data['locations'] = $this->function_lib->getAllLocations();
				if(!empty($this->function_lib->getOfferDetails($offerID))){
					$this->data['redirect'] = $this->function_lib->getOfferDetails($offerID)[0];
					if(isset($this->data['redirect']['compensation'])){
						$this->data['redirect']['compensationType'] = 1;
					}else if(isset($this->data['redirect']['minCompensation']) || isset($this->data['redirect']['maxCompensation'])){
						$this->data['redirect']['compensationType'] = 2;
					}else{
						$this->data['redirect']['compensationType'] = 3;
					}
					$this->data['redirect']['workHome'] = $this->data['redirect']['workFromHome'];
					$this->data['redirect']['applicantType'] = $this->data['redirect']['skillRequired'];
					if($offerSkills = $this->function_lib->getOfferSkills($offerID))
						$this->data['redirect']['selectedSkills'] = json_encode($offerSkills);
					else
						$this->data['redirect']['selectedSkills'] = array();

					if($offerLocations = $this->function_lib->getOfferLocations($offerID)){
						$i=0; 
						foreach ($offerLocations as $key => $locations) {
							$offers[$i]['locationID'] = $locations['cityID'];
							$offers[$i]['location_name'] = $locations['city'].', '.$locations['state'];
							$i++; 
						}
						$offerLocations = $offers;
						$this->data['redirect']['location'] = json_encode($offerLocations);
					}else{
						$this->data['redirect']['location'] = array();
					}
				$this->data['edit'] = 1;
				}else{
					redirect(base_url('add-new-offer'));
				}
				$this->load->view('addNewOffer', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function myAddedOffers(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "My Added Offers";
				$this->data['activePage'] = "8";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$offers = $this->function_lib->getAddedOffers($_SESSION['user_data']['userID'],0,10);
				$this->data['hasMore'] = $this->function_lib->hasMoreOffers($_SESSION['user_data']['userID'], 10,10);
				$this->data['offers'] = $offers;
				if(!empty($offers)){
				foreach ($offers as $key => $offer) {
					if($offerSkills = $this->function_lib->getOfferSkills($offer['offerID'])){
						$this->data['offerSkills'][$offer['offerID']] = $offerSkills;

					}
					else{
						$this->data['offerSkills'][$offer['offerID']] = array();
					}

					if($offerLocations = $this->function_lib->getOfferLocations($offer['offerID'])){
						$this->data['offerLocations'][$offer['offerID']] = $offerLocations;

					}
					else{
						$this->data['offerLocations'][$offer['offerID']] = array();
					}
				}
				}
				$this->load->view('myAddedOffers', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function applicants(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Applicants";
				$this->data['activePage'] = "8";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->load->view('applicants', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function compareApplicants(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Compare Applicants";
				$this->data['activePage'] = "8";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				$this->load->view('compareApplicants', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function appliedOffers(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Applied Offers";
				$this->data['activePage'] = "10";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				if(isset($_SESSION['filter']) && $_SESSION['filter'] == 1){
					$this->data['offers'] = $_SESSION['data']['offers'];
					$this->data['hasMore'] = $_SESSION['data']['hasMore'];
					if(!empty($_SESSION['data']['offers'])){
						$this->data['offerSkills'] = $_SESSION['data']['offerSkills'];
						$this->data['offerLocations'] = $_SESSION['data']['offerLocations'];
					}
					$this->data['status'] = $_SESSION['data']['status'];
					
				}else{
					unset($_SESSION['filter']);
					unset($_SESSION['data']);
				$offers = $this->function_lib->getAppliedOffers($_SESSION['user_data']['userID'],0,10,0);
				$this->data['hasMore'] = $this->function_lib->hasMoreAppliedOffers($_SESSION['user_data']['userID'],10,10,0);
				$this->data['offers'] = $offers;
				if(!empty($offers)){
				foreach ($offers as $key => $offer) {
					if($offerSkills = $this->function_lib->getOfferSkills($offer['offerID'])){
						$this->data['offerSkills'][$offer['offerID']] = $offerSkills;
					}
					else{
						$this->data['offerSkills'][$offer['offerID']] = array();
					}
					if($offerLocations = $this->function_lib->getOfferLocations($offer['offerID'])){
						$this->data['offerLocations'][$offer['offerID']] = $offerLocations;
					}
					else{
						$this->data['offerLocations'][$offer['offerID']] = array();
					}
				}
				}
			}
				$this->load->view('appliedOffers', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function availableOffers(){
		if($this->function_lib->auth()){
			if($_SESSION['user_data']['emailVerified'] == '1' && $_SESSION['user_data']['mobileVerified'] == '1'){
				$this->data['pageTitle'] = "Available Offers";
				$this->data['activePage'] = "20";
				$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
				if(isset($_SESSION['filter']) && $_SESSION['filter'] == 1){
					$this->data['offers'] = $_SESSION['data']['offers'];
					$this->data['hasMore'] = $_SESSION['data']['hasMore'];
					if(!empty($_SESSION['data']['offers'])){
						$this->data['offerSkills'] = $_SESSION['data']['offerSkills'];
						$this->data['offerLocations'] = $_SESSION['data']['offerLocations'];
					}
					$this->data['allOfferLocations'] = $this->function_lib->getAllOfferLocations();
					$this->data['allOfferSkills'] = $this->function_lib->getAllOfferSkills();
					if(isset($_SESSION['data']['status'])){
						$this->data['status'] = $_SESSION['data']['status'];
						$this->data['userSkills'] = $_SESSION['data']['userSkills'];
					}
					if(isset($_SESSION['appliedFilters'])){
						$this->data['appliedFilters'] = $_SESSION['appliedFilters'];
					}
				}else{
					unset($_SESSION['appliedFilters']);
					unset($_SESSION['filter']);
					unset($_SESSION['data']);
				$offers = $this->function_lib->getAllOffers(0,10);
				$this->data['hasMore'] = $this->function_lib->hasMoreUserOffers(10,10);
				$this->data['offers'] = $offers;
				$this->data['allOfferLocations'] = $this->function_lib->getAllOfferLocations();
				$this->data['allOfferSkills'] = $this->function_lib->getAllOfferSkills();
				if(!empty($offers)){
				foreach ($offers as $key => $offer) {
					if($offerSkills = $this->function_lib->getOfferSkills($offer['offerID'])){
						$this->data['offerSkills'][$offer['offerID']] = $offerSkills;
					}
					else{
						$this->data['offerSkills'][$offer['offerID']] = array();
					}
					if($offerLocations = $this->function_lib->getOfferLocations($offer['offerID'])){
						$this->data['offerLocations'][$offer['offerID']] = $offerLocations;
					}
					else{
						$this->data['offerLocations'][$offer['offerID']] = array();
					}
				}
				}
			}
				$this->load->view('availableOffers', $this->data);
			}
			else{
				redirect(base_url('verify-contact-details'));
			}
		}
		else{
			redirect(base_url());
		}
	}

	public function offer($offerID){
		$this->data['pageTitle'] = "Offer";
		$this->data['activePage'] = "0";
		$this->data['offerDetails'] = $this->function_lib->getOfferDetails($offerID);
		$this->data['employerDetails'] = $this->function_lib->getCompanyData($this->data['offerDetails'][0]['addedBy']);
		if(empty($this->function_lib->getOfferDetails($offerID))){
			redirect(base_url('xyz'));
		}
		if($offerSkills = $this->function_lib->getOfferSkills($offerID))
			$this->data['offerSkills'] = $offerSkills;
		else
			$this->data['offerSkills'] = array();

		if($offerLocations = $this->function_lib->getOfferLocations($offerID))
			$this->data['offerLocations'] = $offerLocations;
		else{
			$this->data['offerLocations'] = array();
		}

		$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
		$this->load->view('offer', $this->data);
	}

	public function aboutUs(){
		$this->data['pageTitle'] = "About Us";
		$this->data['activePage'] = "0";
		$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
		$this->load->view('aboutUs', $this->data);
	}

	public function termsAndConditions(){
		$this->data['pageTitle'] = "Terms and Conditions";
		$this->data['activePage'] = "0";
		$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
		$this->load->view('termsAndConditions', $this->data);
	}

	public function privacyPolicy(){
		$this->data['pageTitle'] = "Privacy Policy";
		$this->data['activePage'] = "0";
		$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
		$this->load->view('privacyPolicy', $this->data);
	}

	public function contactUs(){
		$this->data['pageTitle'] = "Contact Us";
		$this->data['activePage'] = "0";
		$this->data['sidebar'] =  $this->load->view('commonCode/sidebar',$this->data,true);
		$this->load->view('contactUs', $this->data);
	}


	public function employer(){
		$this->data['pageTitle'] = "Employer";

		$this->load->view('employer', $this->data);
	}

	public function report($userID){
		$this->data['generalData'] = $this->function_lib->getUserGeneralData($userID)[0];
		$this->data['educationalDetails'] = $this->function_lib->getUserEducationalDetails($userID);
		$this->data['workExperience'] = $this->function_lib->getUserWorkExperience($userID);
		$this->data['skills'] = $this->skill_lib->getUserSkills($userID);
		$premiumSkills = $this->skill_lib->getPremiumSkills($userID);
		$this->data['premiumSkills'] = $premiumSkills;
		foreach ($premiumSkills as $key => $value) {
			$this->data['skillResponse'][$value['skillID']] = $this->skill_lib->getResponses($userID, $value['skillID']);
			$this->data['skillCorrect'][$value['skillID']] = $this->skill_lib->getCorrectResponses($userID, $value['skillID']);
			$this->data['skillIncorrect'][$value['skillID']] = $this->skill_lib->getIncorrectResponses($userID, $value['skillID']);
			$this->data['skillMax'][$value['skillID']] = $this->skill_lib->getSkillMax($value['skillID']);
		}

		$this->load->view('report', $this->data);
	}

	private function sendEMail($email){
		$this->load->helper('mail_helper');
		$message =  $this->load->view('emailers/offers', $this->data, true);
		$data = array(
				'sendToEmail' => $email,
				'fromName' => 'Campus Puppy Private Limited',
				'fromEmail' => 'no-reply@campuspuppy.com',
				'subject' => 'Offers|Campus Puppy Private Limited',
				'message' => $message,
				'using' =>'pepipost'
				);
		sendEmail($data);
	}

	private function sendSMS($mobile, $msg){
		$authKey = "163538ADD0UybtU59590664";
		$mobileNumber = $mobile;
		$senderId = "CPUPPY";
		$message = urlencode($msg);
		$route = "4";
		$postData = array(
		    'authkey' => $authKey,
		    'mobiles' => $mobileNumber,
		    'message' => $message,
		    'sender' => $senderId,
		    'route' => $route
		);
		$url="http://api.msg91.com/api/sendhttp.php";
		$ch = curl_init();
		curl_setopt_array($ch, array(
		    CURLOPT_URL => $url,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_POST => true,
		    CURLOPT_POSTFIELDS => $postData
		    //,CURLOPT_FOLLOWLOCATION => true
		));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$output = curl_exec($ch);
		if(curl_errno($ch)){
	  // echo 'error:' . curl_error($ch);
		}
		curl_close($ch);
		// echo $output;
	}

	public function generateVerificationCode($type){
		$this->load->library(array('contact_lib'));
		$mobile = "";
		$email = "";

		if($type=="1"){
			date_default_timezone_set("Asia/Kolkata");
			$mobile = $this->function_lib->getUserData($_SESSION['user_data']['email'])[0]['mobile'];
			$checkCode = $this->contact_lib->checkVerificationCode($mobile, $email, $type);
			$currentTime = strtotime(date("d M Y H:i:s"));
			if($checkCode){
				$expiry = $checkCode[0]['expiry'];
				$timeDifference = $expiry-$currentTime;
				if($timeDifference>0 && $timeDifference<7200){
					$msg = "Your Mobile Number Verification Token is: ".$checkCode[0]['code'].". The token is valid for only next 2 hours.";
					// echo $msg."1";
					$this->sendSMS($mobile, $msg);
				}
				else{
					$code = rand(1000,9999);
					$expiry = $currentTime + 7200;
					$codeData = array(
						'code' => $code,
						'mobile' => $mobile,
						'generatedAt' => $currentTime,
						'expiry' => $expiry,
						'codeType' => '1',
						'userID' => $_SESSION['user_data']['userID'],
						'active' => '1'
					);
					$this->contact_lib->insertVerificationCode($codeData);
					$msg =  "Your Mobile Number Verification Token is: ".$code.". The token is valid for only next 2 hours.";
					$this->sendSMS($mobile, $msg);
				}
			}
			else {
				$code = rand(1000,9999);
				$expiry = $currentTime + 7200;
				$codeData = array(
					'code' => $code,
					'mobile' => $mobile,
					'generatedAt' => $currentTime,
					'expiry' => $expiry,
					'codeType' => '1',
					'userID' => $_SESSION['user_data']['userID'],
					'active' => '1'
				);
				$this->contact_lib->insertVerificationCode($codeData);
				$msg =  "Your Mobile Number Verification Token is: ".$code.". The token is valid for only next 2 hours.";
				$this->sendSMS($mobile, $msg);
			}
		}

		if($type=="2"){
			date_default_timezone_set("Asia/Kolkata");
			$email = $_SESSION['user_data']['email'];
			$checkCode = $this->contact_lib->checkVerificationCode($mobile, $email, $type);
			$currentTime = strtotime(date("d M Y H:i:s"));
			if($checkCode){
				$expiry = $checkCode[0]['expiry'];
				$timeDifference = $expiry-$currentTime;
				if($timeDifference>0 && $timeDifference<7200){
					$msg = "Your E-Mail Verification Token is: ".$checkCode[0]['code'].". The token is valid for only next 2 hours.";
					$this->sendEMail($email);
				}
				else{
					$code = rand(1000,9999);
					$expiry = $currentTime + 7200;
					$codeData = array(
						'code' => $code,
						'email' => $email,
						'generatedAt' => $currentTime,
						'expiry' => $expiry,
						'codeType' => '2',
						'userID' => $_SESSION['user_data']['userID'],
						'active' => '1'
					);
					$this->contact_lib->insertVerificationCode($codeData);
					$msg =  "Your E-Mail Verification Token is: ".$code.". The token is valid for only next 2 hours.";
					$this->sendEMail($email);
				}
			}
			else {
				$code = rand(1000,9999);
				$expiry = $currentTime + 7200;
				$codeData = array(
					'code' => $code,
					'email' => $email,
					'generatedAt' => $currentTime,
					'expiry' => $expiry,
					'codeType' => '2',
					'userID' => $_SESSION['user_data']['userID'],
					'active' => '1'
				);
				$this->contact_lib->insertVerificationCode($codeData);
				$msg =  "Your E-Mail Verification Token is: ".$code.". The token is valid for only next 2 hours.";
				$this->sendEMail($email);
			}
		}

	}

}
