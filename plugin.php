<?php
/**
 * @package zaiad
 * @version 1.0.0
 */
/*
Plugin Name:zaiad
Plugin URI: http://wordpress.org/plugins/wan-pliggin/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: zaiad es-sahel
Version: 1.0.0
Author URI: http://ma.tt/
*/
function formShape() {
    ?>
   <form action=" <?= esc_url( $_SERVER['REQUEST_URI'] )?> " method="post">
    <label for=""> Name</label>
    <input type="text" name="name" pattern="[a-zA-Z0-9 ]+" value=" <?= isset( $_POST["name"] ) ? esc_attr( $_POST["name"] ) : '' ?>" size="40" />
     <label for="">Email</label>
    <input type="email" name="email" value="<?= isset( $_POST["email"] ) ? esc_attr( $_POST["email"] ) : ''?>" size="40" />
     <label for="">Subject</label>
    <input type="text" name="subject" pattern="[a-zA-Z ]+" value="<?= isset( $_POST["subject"] ) ? esc_attr( $_POST["subject"] ) : '' ?> " size="40" />
     <label for="">Message</label>
     <input type="submit" name="send" value="envoyer"/>
    </form>
    <?php
}
function secureSubmit() {

    if ( isset( $_POST['send'] ) ) {
        $name    =  $_POST["name"] ;
        $email   =$_POST["email"] ;
        $subject = $_POST["subject"] ;
        $message = $_POST["message"] ;
        save($email,$name,$message,$subject);?>
        <div class="alert alert-success" style="font-weight:bold;border:sloid black 1px;border-radius: 5px">
            message sent to admins!
        </div>
<?php
    }
}
function cf_shortcode() {
    ob_start();
    secureSubmit();
    formShape();
    return ob_get_clean();
}
function save($email,$name,$message,$subject){
    global $wpdb;
    $sr=$qr=$wpdb->query("INSERT INTO `wp_contact_plugin_test` (`id`, `email`, `name`, `subject`, `message`) VALUES (NULL, '{$email}', '{$name}', '{$subject}', '{$subject}');");
}
function createTable(){
    global $wpdb;
    $qr=$wpdb->query("CREATE TABLE IF NOT EXISTS `wordpress`.`wp_contact_plugin_test` ( `id` INT NOT NULL AUTO_INCREMENT , `email` TEXT NOT NULL , `name` TEXT NOT NULL , `subject` TEXT NOT NULL , `message` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
}
function deleteTable(){
    global $wpdb;
    $qr=$wpdb->query("DROP TABLE IF EXISTS `wordpress`.`wp_contact_plugin_test`;");
}
add_shortcode( 'sitepoint_contact_form', 'cf_shortcode' );
add_action('activate_contact-us-test/main.php',function(){
    createTable();
});

add_action('deactivate_contact-us-test/main.php',function(){
    deleteTable();
});
add_action('admin_menu', 'contact_form_add_menu_fun');
function contact_form_add_menu_fun() {

    add_menu_page(
        'List of received messages',
        'My contact form',
        'edit_posts',
        'menu_slug',
        'list_received_emails'
        ,
        'dashicons-media-spreadsheet'

    );
}
function list_received_emails(){
    global $wpdb;
    $results=$qr=$wpdb->get_results("SELECT * FROM `wordpress` ;");
    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">


    <h5>Contact emails</h5>
        <table class="table">
            <?php if(count($results)<1){?>
                <div class="alert alert-danger">
                    you do not have any incoming messages yet!
                </div>
            <?php }else{?>
            <tr>
                <th>#</th>
                <th>Email</th>
                <th>Name</th>
                <th>Sublect</th>
                <th>Message</th>
            </tr>
            <?php }  foreach($results as $entry){?>
            <tr>
                <td><?= $entry->id ?></td>
                <td><?= $entry->email?></td>
                <td><?= $entry->name ?></td>
                <td><?= $entry->subject ?></td>
                <td><?= $entry->message ?></td>
            </tr>
            <?php }?>

        </table>
    <?php
}
?>