<?PHP
include('githubClass.php');
include('bitbucketClass.php');
/***** parameters *******/
$userName = @$argv[1];
$password = @$argv[2];
$repository_url = @$argv[3];
$contributorName = @$argv[4];

/******* check condition if username and password is not empty *******/
if(isset($userName) && isset($password)){
	
	/******* check condition if username and password is correct *******/
	if($userName=='admin' && $password=='admin123'){
		
		/******* check condition if repository_url and contributorName is not empty *******/
		if(isset($repository_url) && isset($contributorName)){
			/******* client id and client secret key no need to change i used because we could send request to server unlimited time *******/
			$clientIdAndKey = '?client_id=c52dba045ea9af593ede&client_secret=536949a8eaf69b2594314fbd809d54db55ef1798';
			$checkRepositoryUrl = explode("/",$repository_url);
			
			/******* check condition if request for github *******/
			if($checkRepositoryUrl[2]=="github.com"){
				if($checkRepositoryUrl[0]!='https:' || $checkRepositoryUrl[1]!="" || $checkRepositoryUrl[2]!="github.com" || count($checkRepositoryUrl)!=5){
					echo "\nPlease Enter Valid Githhub API Repository Url like this :- \n\n(https://github.com/:username/:repository)\n";
					die;
				}else{
					/** call curl **/
					$githubApiUrl = 'https://api.github.com/repos/'.$checkRepositoryUrl[3].'/'.$checkRepositoryUrl[4].'/contributors';
					$curlReturnData = $obj->call_curl($githubApiUrl.$clientIdAndKey);
					$contributionsArray= json_decode($curlReturnData);
					if(isset($contributionsArray->message)=='Not Found'){
						$obj->gitHubMessage();
					}else{
						$obj->get_commitCount($contributionsArray,$contributorName);
					}
				}
			}
			/******* check condition if request for bitbucket *******/
			else if($checkRepositoryUrl[2]=="bitbucket.org"){
				if($checkRepositoryUrl[0]!='https:' || $checkRepositoryUrl[1]!="" || $checkRepositoryUrl[2]!="bitbucket.org" || count($checkRepositoryUrl)!=5){
					echo "\nPlease Enter Valid Bitbucket API Repository Url like this :- \n\n(https://bitbucket.org/:username/:repository)\n";
					die;
				}else{
					/** call curl **/
					$bitBucketApiUrl = 'https://api.bitbucket.org/2.0/repositories/'.$checkRepositoryUrl[3].'/'.$checkRepositoryUrl[4];
					$curlReturnData = $bitObj->call_curl($bitBucketApiUrl);
					if(isset(json_decode($curlReturnData)->error)){
						$bitObj->bitBucketMessage();
					}else{
						$curlReturnData = $bitObj->call_curl($bitBucketApiUrl.'/commits');
						/** get commit count**/
						$bitObj->get_commitCount($curlReturnData,$contributorName);
					}
				}
					
			}else{
				echo "\nPlease Enter Valid API Repository Url like For Github \n\n(https://github.com/:username/:repository)\n  \nFor Bitbucket \n\n(https://bitbucket.org/:username/:repository)\n" ;
				die;
			}
		}else{
			echo "\nPlease Enter Repository URL and Contributor Username\n";
		}
	}else{
		echo "\nPlease Enter Correct username and password\n";
	}
}else{
	echo "please Enter First Username and Password and Repository url and Contributor username\nFormat is as follows : \n\n";
	echo "Username Password Repository_URL contributorName\n";
}

?>