<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

/*
This is an upload script for SWFUpload that attempts to properly handle uploaded files
in a secure way.

Notes:
	
	SWFUpload doesn't send a MIME-TYPE. In my opinion this is ok since MIME-TYPE is no better than
	 file extension and is probably worse because it can vary from OS to OS and browser to browser (for the same file).
	 The best thing to do is content sniff the file but this can be resource intensive, is difficult, and can still be fooled or inaccurate.
	 Accepting uploads can never be 100% secure.
	 
	You can't guarantee that SWFUpload is really the source of the upload.  A malicious user
	 will probably be uploading from a tool that sends invalid or false metadata about the file.
	 The script should properly handle this.
	 
	The script should not over-write existing files.
	
	The script should strip away invalid characters from the file name or reject the file.
	
	The script should not allow files to be saved that could then be executed on the webserver (such as .php files).
	 To keep things simple we will use an extension whitelist for allowed file extensions.  Which files should be allowed
	 depends on your server configuration. The extension white-list is _not_ tied your SWFUpload file_types setting
	
	For better security uploaded files should be stored outside the webserver's document root.  Downloaded files
	 should be accessed via a download script that proxies from the file system to the webserver.  This prevents
	 users from executing malicious uploaded files.  It also gives the developer control over the outgoing mime-type,
	 access restrictions, etc.  This, however, is outside the scope of this script.
	
	SWFUpload sends each file as a separate POST rather than several files in a single post. This is a better
	 method in my opinions since it better handles file size limits, e.g., if post_max_size is 100 MB and I post two 60 MB files then
	 the post would fail (2x60MB = 120MB). In SWFupload each 60 MB is posted as separate post and we stay within the limits. This
	 also simplifies the upload script since we only have to handle a single file.
	
	The script should properly handle situations where the post was too large or the posted file is larger than
	 our defined max.  These values are not tied to your SWFUpload file_size_limit setting.
	
*/

class Uploader {
    
    private $_save_path; // папка для сохранения файлов
    
    public function __construct($save_path=NULL) {
        if (!isset($save_path))
        {
            $save_path = JPATH_ROOT.DS.'temp'.DS;// The path were we will save the file
        }
        $this->_save_path = $save_path;
    }
    function upload_file($over_load = FALSE)
    {

        $msg = '';
        // Check post_max_size (http://us3.php.net/manual/en/features.file-upload.php#73762)
                $POST_MAX_SIZE = ini_get('post_max_size');
                $unit = strtoupper(substr($POST_MAX_SIZE, -1));
                $multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

                if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
                        header("HTTP/1.1 500 Internal Server Error"); // This will trigger an uploadError event in SWFUpload
                        $msg = JTEXT::_("POST exceeded maximum allowed size.");
                        return array(0,$msg);
                }

        // Settings
                
                $upload_name = "Filedata";
                $max_file_size_in_bytes = 2147483647;				// 2GB in bytes
                $extension_whitelist = array("jpg", "gif", "png");	// Allowed file extensions
                $valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';// Characters allowed in the file name (in a Regular Expression format)

        // Other variables	
                $MAX_FILENAME_LENGTH = 260;
                $file_name = "";
                $file_extension = "";
                $uploadErrors = array(
                0=>JTEXT::_("There is no error, the file uploaded with success"),
                1=>JTEXT::_("The uploaded file exceeds the upload_max_filesize directive in php.ini"),
                2=>JTEXT::_("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"),
                3=>JTEXT::_("The uploaded file was only partially uploaded"),
                4=>JTEXT::_("No file was uploaded"),
                6=>JTEXT::_("Missing a temporary folder")
                );


        // Validate the upload
                if (!isset($_FILES[$upload_name])) {
                        $msg = JTEXT::_("No upload found in FILES for")." ". $upload_name;
                        return array(0,$msg);
                } else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
                        $msg = $uploadErrors[$_FILES[$upload_name]["error"]];
                        return array(0,$msg);
                } else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
                        $msg = JTEXT::_("Upload failed is_uploaded_file test");
                        return array(0,$msg);
                } else if (!isset($_FILES[$upload_name]['name'])) {
                        $msg = JTEXT::_("File has no name");
                        return array(0,$msg);
                }

        // Validate the file size (Warning: the largest files supported by this code is 2GB)
                $file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
                if (!$file_size || $file_size > $max_file_size_in_bytes) {
                        $msg = JTEXT::_("File exceeds the maximum allowed size");
                        return array(0,$msg);
                }

                if ($file_size <= 0) {
                        $msg = JTEXT::_("File size outside allowed lower bound");
                        return array(0,$msg);
                }


        // Validate file name (for our purposes we'll just remove invalid characters)
                $file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
                if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
                        $msg = JTEXT::_("Invalid file name");
                        return array(0,$msg);
                }


        // Validate that we won't over-write an existing file
                if (file_exists($this->_save_path . $file_name) AND !$over_load) {
                        $msg = JTEXT::_("File with this name already exists");
                        return array(0,$msg);
                }

        // Validate file extension
                $path_info = pathinfo($_FILES[$upload_name]['name']);
                $file_extension = $path_info["extension"];
                $is_valid_extension = false;
                foreach ($extension_whitelist as $extension) {
                        if (strcasecmp($file_extension, $extension) == 0) {
                                $is_valid_extension = true;
                                break;
                        }
                }
                if (!$is_valid_extension) {
                        $msg = JTEXT::_("Invalid file extension");
                        return array(0,$msg);
                }

                if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $this->_save_path.$file_name)) {
                        $msg = JTEXT::_("File could not be saved");
                        $msg .= ': '.$this->_save_path.$file_name;
                        return array(0,$msg);
                }
                $msg = JTEXT::_("There is no error, the file uploaded with success");
                return (array(1,$msg,$file_name));
        
    }

}

?>