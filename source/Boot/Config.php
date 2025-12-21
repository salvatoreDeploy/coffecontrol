<?php
/**
 * DATABASE
 */
define("CONF_DB_HOST", "localhost");
define("CONF_DB_USER", "root");
define("CONF_DB_PASS", "");
define("CONF_DB_NAME", "coffecontrol");

/**
 * PROJECT URLs
 */
define("CONF_URL_BASE", "https://www.developerservice.com.br");
define("CONF_URL_ADMIN", CONF_URL_BASE . "/admin");

/**
 * PROJECT DEV URLs
 */
define("CONF_URL_DEV", "https://www.localhost/coffecontrol");

/**
 * SITE
 */
define("CONF_SITE_NAME", "CoffeControl");
define("CONF_SITE_TITLE", "Controle suas contas degustando o melhor café");
define("CONF_SITE_DESC", "O CoffeControl é um gerenciador de contas simples, poderoso e gratuito. O prazer de tomar um café e ter o controle total de suas contas.");
define("CONF_SITE_LANG", "pt_BR");
define("CONF_SITE_DOMAIN", "developerservice.shop");
define("CONF_SITE_ADDR_STREET", "Rua Jose Luiz Regal");
define("CONF_SITE_ADDR_NUMBER", "105");
define("CONF_SITE_ADDR_COMPLEMENT", "");
define("CONF_SITE_ADDR_CITY", "Sorocaba");
define("CONF_SITE_ADDR_STATE", "SP");
define("CONF_SITE_ADDR_ZIPCODE", "18074-135");

/**
 * SOCIAL
 */
define("CONF_SOCIAL_TWITTER_CREATOR", "@robsonvleite");
define("CONF_SOCIAL_TWITTER_PUBLISHER", "@robsonvleite");

define("CONF_SOCIAL_FACEBOOK_APP", "626590460695980");
define("CONF_SOCIAL_FACEBOOK_PAGE", "upinside");
define("CONF_SOCIAL_FACEBOOK_AUTHOR", "robsonvleiteoficial");

define("CONF_SOCIAL_INSTAGRAM_PAGE", "robsonvleiteoficial");

define("CONF_SOCIAL_YOUTUBE_PAGE", "robsonvleiteoficial");

define("CONF_SOCIAL_GOOGLE_PAGE", "107305124528362639842");
define("CONF_SOCIAL_GOOGLE_AUTHOR", "103958419096641225872");

/**
 * DATES
 */
define("CONF_DATE_BR", "d/m/Y H:i:s");
define("CONF_DATE_APP", "Y-m-d H:i:s");

/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);

/**
 * MESSAGE
 */
define("CONF_MESSAGE_CLASS", "message");
define("CONF_MESSAGE_INFO", "info icon-info");
define("CONF_MESSAGE_SUCCESS", "success icon-check-square-o");
define("CONF_MESSAGE_WARNING", "warning icon-warning");
define("CONF_MESSAGE_ERROR", "error icon-warning");

/**
 * VIEW
 */
define("CONF_VIEW_PATH", __DIR__ . "/../../shared/views");
define("CONF_VIEW_EXT", "php");
define("CONF_VIEW_THEME", "cafecontrolweb");
define("CONF_VIEW_APP", "cafecontrolapp");

/**
 * UPLOAD
 */
define("CONF_UPLOAD_DIR", "storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_FILE_DIR", "files");
define("CONF_UPLOAD_MEDIA_DIR", "medias");

/**
 * IMAGES
 */
define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
define("CONF_IMAGE_SIZE", 2000);
define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

/**
 * MAIL
 */
define("CONF_MAIL_HOST", "sandbox.smtp.mailtrap.io");
define("CONF_MAIL_PORT", "2525");
define("CONF_MAIL_USER", "ef3c2c7383bf81");
define("CONF_MAIL_PASS", "770a244ad46e23");
define("CONF_MAIL_SENDER", ["name" => "Henrique Araujo", "address" => "liderhenrique@gmail.com"]);
define("CONF_MAIL_SUPPORT", "support@email.com.br");
define("CONF_MAIL_OPTION_LANG", "br");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_AUTH", true);
define("CONF_MAIL_OPTION_SECURE", "tls");
define("CONF_MAIL_OPTION_CHARSET", "utf-8");