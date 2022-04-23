<?php
namespace Ak86;

use GuzzleHttp\Client;

class WPAutoPost
{
    private $gc;

    private $wp_post_endpoint;
    private $wp_media_endpoint;
    private $wp_auth_username;
    private $wp_auth_password;

    public function __construct($wp_post_endpoint, $wp_media_endpoint, $wp_auth_username, $wp_auth_password)
    {
        $this->wp_post_endpoint = $wp_post_endpoint;
        $this->wp_media_endpoint = $wp_media_endpoint;
        $this->wp_auth_username = $wp_auth_username;
        $this->wp_auth_password = $wp_auth_password;
        
        $this->gc = new Client([
            'verify' => false
        ]);
    }

    /**
     * Function for generating WP REST API Basic Auth String based 
     * on password generated with https://wordpress.org/plugins/application-passwords/ plugin
     * @return base64 encoded string
     */
    private function get_wp_auth()
    {
        return base64_encode($this->wp_auth_username.':'.$this->wp_auth_password);
    }

    /**
     * Function for fetching the remote image and uploading it to the Wordpress via its REST API(Media)
     * @param  [String] $imagePath - URL path to the remote image 
     * @return [Object] Response received from the WP REST API upon uploading the Image
     */
    public function upload_image($imagePath)
    {
        //$guzzle = new Client();

        // get image from the $imagePath
        $imgres = $this->gc->get($imagePath);

        // upload image to wordpress
        $wpreq = $this->gc->request('POST', $this->wp_media_endpoint, [
            'headers' => [
                'Authorization' => 'Basic '.$this->get_wp_auth(),
                'Content-Type' => $imgres->getHeader('Content-Type')[0],
                'Content-Disposition' => 'attachment; filename="'.basename($imagePath).'"'
            ],
            'body' => $imgres->getBody()
        ]);	

        return json_decode($wpreq->getBody());
    }

    /**
     * Function for adding a new post to the Wordpress via its REST API (Posts)
     * @param [array] $post - an array of data to be inserted as a wordpress post
     * @return [object] - Response received from the WP REST API upon creating a new post
     */
    public function add_wp_post($post, $imgid)
    {
        // return false if we haven't got an array
        if(!is_array($post))
        {
            return false;
        }

        $post['featured_media'] = $imgid;		// sets the featured image

        //$guzzle = new GuzzleHttp\Client();

        $wpreq = $this->gc->request('POST', $this->wp_post_endpoint, [
            'headers' => [
                'Authorization' => 'Basic '.$this->get_wp_auth(),
                //'Content-Type' => '',
            ],
            'form_params' => $post
        ]);	

        return json_decode($wpreq->getBody());
    }
}
