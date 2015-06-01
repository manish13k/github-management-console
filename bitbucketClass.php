<?php
Interface Bitbucket
{ 
	public function call_curl($repository_url);
	public function get_commitCount($commitJson,$contributorName);
	public function bitBucketMessage();
} 

class BitAPI Implements Bitbucket { 
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
	
	/** get total commits by contributor **/
	public function get_commitCount($commitJson,$contributorName){
		if(isset($commitJson) && $commitJson!=""){
			$commits = json_decode($commitJson)->values;
			for($k=0;$k<count($commits);$k++){
				$totalCommitsUsername[] = @$commits[$k]->author->user->username;
			}
			if(in_array($contributorName, $totalCommitsUsername)) {
				for($counter=0;$counter<count($commits);$counter++){
					if(@$commits[$counter]->author->user->username==$contributorName){
						$totalCommitsArray[] = @$commits[$counter]->author->user->username;
					}
				}
				$totalCommits = count($totalCommitsArray);
				$response = array('Total Commits Count For Bitbucket Contributor'=>$totalCommits);
				$totalCommitCount = json_encode($response);
				print_r("\n".$totalCommitCount."\n");
			}else{
				echo "\n Contributor Name Does Not Exist \n";
			}
		}else{
			$totalCommits = 0;
			$response = array('Total Commits Count For Bitbucket Contributor'=>$totalCommits);
			$totalCommitCount = json_encode($response);
			print_r("\n".$totalCommitCount."\n");
		}
	}
	
	/** message **/
	public function bitBucketMessage(){
		print_r("\nRecord Not Found\n");
		die;
	}
	
}
$bitObj = new BitAPI();
?>