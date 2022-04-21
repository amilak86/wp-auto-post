# WP Auto Post Client

WP Auto Post Client is a simple API client library written in PHP which allows creating new posts programatically by using Wordpress REST API, without the need to login to the dashboard.

## Requirements

- Nginx/Apache server configured with PHP 7.4 or higher
- Composer package manager
- Working installation of Wordpress 5.6 or later with the support for Application Passwords. Follow [this link](https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/) to learn how to obtain an application password. If you can't see the application passwords section at the bottom of your wordpress user profile, you may have to enable it [manually](https://wordpress.stackexchange.com/questions/383244/application-passwords-not-working-on-localhost). 

## Installation

```
composer require ak86/wp-auto-post

```

## Basic Usage

```
// Require composer autoloader
require_once '/path/to/vendor/autoload.php';

// Import the library
use Ak86\WPAutoPost;

// Set required config vars
$wp_post_endpoint = 'http://yourwordpresssite/wp-json/wp/v2/posts';
$wp_media_endpoint = 'http://yourwordpresssite/wp-json/wp/v2/media';
$wp_auth_username = 'your_wordpress_username';
$wp_auth_password = 'your_wordpress_application_password';

// Initialize the client instance
$wpap_client = new WPAutoPost($wp_post_endpoint, $wp_media_endpoint, $wp_auth_username, $wp_auth_password);

// creating a new post: set post image url 
$imageUrl = 'http://url/to/image.jpg';

// creating a new post: upload the image
$uploaded_image = $wpap_client->upload_image($imageUrl);

// creating a new post: set post data
$postData = array(
    "title" => 'Sample Post Title',
    "content" => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ...',
    "status" => "publish"
);

// creating a new post: submit post data
$wpap_client->add_wp_post($postData, $uploaded_image->id);

```

## Example App

Please checkout below repository for a functional demo project showing how to use this library.

[https://github.com/amilak86/wp-auto-post-demo](https://github.com/amilak86/wp-auto-post-demo)

## License

[MIT](./LICENSE)

## Author

[Amila Kalansooriya](https://www.amilakalansooriya.me)

## References
- [https://developer.wordpress.org/rest-api/reference/](https://developer.wordpress.org/rest-api/reference/)
- [https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/](https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/)
- [https://wordpress.stackexchange.com/questions/383244/application-passwords-not-working-on-localhost](https://wordpress.stackexchange.com/questions/383244/application-passwords-not-working-on-localhost)