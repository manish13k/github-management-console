<?php
Interface GitHubAPI
{ 
	public function call_curl($repository_url);
	public function get_repos($reposJson);
	public function get_commitCount($contributionsArray,$contributorName);
	public function gitHubMessage();
} 

class API Implements GitHubAPI { 
	/* call curl */
	public function call_curl($repository_url){
		$handle=curl_init($repository_url);
		curl_setopt($handle, CURLOPT_VERBOSE, true);
		curl_setopt($handle, CURLOPT_USERAGENT, "Dark Secret Ninja/1.0");
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($handle);
		return $content;
	}
	
	/** get repository list and their contributions url**/
	public function get_repos($reposJson){
		$reposArray = json_decode($reposJson);
		$reposCount = count($reposArray);
		if(isset($reposArray->message)=='Not Found'){
			echo "\nRecord Not Found\n";
			die;
		}else{
			if(!empty($reposArray->contributors_url)){
			for($counter=0;$counter<$reposCount;$counter++){
				$contributorsUrlArray[] = $reposArray->contributors_url;
				$contributorsUrl = json_encode($contributorsUrlArray);
			}
			}else{
					$contributorsUrl = 0;
			}
			return $contributorsUrl;
		}
	}
	
	/** get total commits by contributor **/
	public function get_commitCount($contributionsArray,$contributorName){
		$totalCommit = 0;
		for($k=0;$k<count($contributionsArray);$k++){
			$loginNames[] = $contributionsArray[$k]->login;
		}
		if(in_array($contributorName, $loginNames)) {
			for($counter=0;$counter<count($contributionsArray);$counter++){
				$loginName = $contributionsArray[$counter]->login;
				if($loginName==$contributorName){
					$totalCommit = $contributionsArray[$counter]->contributions;
					break;
				}
			}
			$response = array('Total Commits Count For GithHub Contributor'=> $totalCommit );
			$totalCommitCount = json_encode($response);
			echo "\n".$totalCommitCount."\n";
		}else{
			echo "\n Contributor Name Does Not Exist \n";
		}
	}
	
	/** message **/
	public function gitHubMessage(){
		print_r("\nRecord Not Found\n");
		die;
	}
}
$obj = new API();
?>