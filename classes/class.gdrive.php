<?php
//this class will be used to handle files upload to google drive
class Gdrive {
	public $access_token;
	public function __construct(){
		include_once './gdrive/Google/autoload.php';

		//get google access token from the database
		$sql = "SELECT google_access_token FROM oauth ";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$this->access_token = $row['google_access_token'];
	}

	public function getClient(){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$client = new Google_Client();
        $client->setApplicationName('ELearning');
		
		$client->addScope(Google_Service_Drive::DRIVE);
		$client->setAuthConfig('gdrive/client_secret.json');
		$client->setAccessType('offline');
        
        $client->setIncludeGrantedScopes(true);   // incremental auth
        $client->setApprovalPrompt ("force");

		//check if we need to refresh the token
        
        //get gmail tasks access token
        $google_access_token =  @$_SESSION['google_access_token'];
        if(empty($google_access_token)){
			$sql = "SELECT google_access_token FROM oauth ";
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			$google_access_token = $row['google_access_token'];

		}
		
		if (!empty($google_access_token)) {

			//check if the old access token is expired or not. If it is expired refresh it	
			$token = json_decode($google_access_token);
			$time_created = $token->created;
            $t=time();
			$timediff = $t - $time_created;
			
            if($timediff>3600)
            {
                    $refreshToken= $token->refresh_token;
                    try{
                        $client->refreshToken($refreshToken);
                        $newtoken=$client->getAccessToken();
                        $newtoken = json_decode($newtoken);
                        $token->access_token = $newtoken->access_token;
                        $token->created = $newtoken->created;
                        $google_access_token = json_encode($token);
                        //update the token now
                        //store the data in the database
						$sql = "UPDATE oauth SET google_access_token='$google_access_token' ";
						mysql_query($sql);

                        $_SESSION['google_access_token'] = $google_access_token; 


                    }
                    catch(Exception $e){
						return false;
                    }
            }
			$client->setAccessToken($google_access_token);
			return $client;
        } else {
            return false;
        }
	}
	public function upload($file_url,$file_name,$ext,$pdf_upload_url){
		$client = $this->getClient();
		$service = new Google_Service_Drive($client);

		if($ext=='ppt' || $ext=='pptx'){
			$fileMetadata = new Google_Service_Drive_DriveFile(array(
								'name' => $file_name,
								'mimeType' => 'application/vnd.google-apps.presentation')
			);
		}
		else{
			$fileMetadata = new Google_Service_Drive_DriveFile(array(
								'name' => $file_name,
								'mimeType' => 'application/vnd.google-apps.document')
			);
		}
		
		
		$result = $service->files->create($fileMetadata, array(
			'data' => file_get_contents($file_url),
			'mimeType' => 'application/octet-stream',
			'uploadType' => 'multipart'
		));
		$file_id = $result->getId();

		//now download this as pdf and store somewhere
		try {
			$response = $service->files->export(
												$file_id, 
												'application/pdf', 
												array(
													'alt' => 'media'
												)
											);
			$file_name = $file_name.'.pdf';
			$file_path = $pdf_upload_url.$file_name;
			file_put_contents($file_path, $response);
		} catch (Exception $e) {
			print "An error occurred: " . $e->getMessage();
		}
		
		$_SESSION['gdrive_id'] = $file_id;
		$_SESSION['file_name'] = $file_name;

		//now delete the file
		$this->deleteFile($file_id);
	}

	public function deleteFile($id=NULL){
		$client = $this->getClient();
		$service = new Google_Service_Drive($client);
		try{
			$result = $service->files->delete($id);
		}
		catch(Exception $e){
			// do nothing
		}
	}
	
}



?>