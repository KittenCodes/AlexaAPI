# AlexaAPI

## Import domains as WordPress posts, then process the posts to determine if the site is running WordPress and return their site ranking with the Alexa API.

- Import your domains with [WP All Import](http://www.wpallimport.com) and create a new post with each domain title
- Add the alexapi.php file to your site root
- Update the $accessKeyId and $secretAccessKey with the API info from your Amazon AWIS account
- Add the get_site_info.php file to your site root
- Update the wp_redirect URL
- Change the "network.http.redirection-limit" in Firefox to 250 by going to "about:config"
- Run http://example.com/get_site_info.php?my_page_number=0
