<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Psych_lib {

	public function getPremiumSkills($userID){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		return $CI->psych->getPremiumSkills($userID);
	}

	public function getUserSkills($userID){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		return $CI->psych->getUserSkills($userID);
	}

	public function getOtherSkills($userID){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		return $CI->psych->getOtherSkills($userID);
	}

	public function getNotAddedSkills($userID){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		return $CI->psych->getNotAddedSkills($userID);
	}

	public function getActiveSkills(){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		return $CI->psych->getActiveSkills();
	}

	public function getTestSettings(){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		return $CI->psych->getTestSettings();
	}

	public function checkAnswer($questionID, $answer, $test = 0){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		$correctAnswer = $CI->psych->getAnswer($questionID)[0]['answer'];
		if($answer == $correctAnswer){
			return 1;
		}else{
			return 0;
		}
	}

	public function getScore($actual_ans, $ans_given)
	{
		$CI = &get_instance();
		$score = 0;
		for ($i = 0; $i < count($actual_ans); $i++) {
			if($actual_ans[$i] == ($ans_given[$i]))
				$score++;
		}
		$test_settings = $CI->session->userdata('test_settings');
		$percent = $score/$test_settings[0]['numberQuestions'] * 100;
		return $percent;
	}

	public function addSkill($score, $userID, $skill_id)
	{
		$response = 0;
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
			date_default_timezone_set('Asia/Kolkata');
			$time = time();
			$date = date("d-m-Y", $time);
			$datestamp = strtotime($date);
			if($CI->psych->addSkillToUser($skill_id, $userID, $score)){
				return true;
			}else{
				return false;
			}
	}

	public function testAvailable($skillID){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		if($CI->psych->testAvailable($skillID) == 1){
			return true;
		}else{
			return false;
		}
	}

	public function getSkillData($skill_id){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		return $CI->psych->getSkillData($skill_id);
	}

	public function isInTest()
	{
		$CI = &get_instance();
		$CI->load->library('session');
		return $CI->session->userdata('in_test');
	}


	public function getQuestionDetails(){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		return $CI->psych->getQuestionDetails();
	}

	public function updateResponse($data){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		return $CI->psych->updateResponse($data);
	}

	public function getPsychCategories(){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		return $CI->psych->getPsychCategories();
	}

	public function getResponses($userID){
		$CI = &get_instance();
		$CI->load->model('psych_model','psych');
		return $CI->psych->getResponses($userID);
	}


}
