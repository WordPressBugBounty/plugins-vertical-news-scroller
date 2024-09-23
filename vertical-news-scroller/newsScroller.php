<?php
	 /* 
	Plugin Name: Vertical News Scroller
	Plugin URI:https://www.i13websolution.com/product/wordpress-vertical-news-scroller-pro/
	Author URI:http://www.i13websolution.com
	Description: Plugin for scrolling Vertical News on wordpress theme.Admin can add any number of news.
	Author:I Thirteen Web Solution
	Text Domain:vertical-news-scroller
	Version:1.25
	*/

	//add_action( 'admin_init', 'vertical_news_scroller_plugin_admin_init' );
	register_activation_hook(__FILE__, 'vns_install_newsscroller');
	register_deactivation_hook(__FILE__, 'vns_vertical_news_remove_access_capabilities');
	add_shortcode('print_vertical_news_scroll', 'vns_print_verticalScroll_func'); 
	add_action('admin_menu', 'vns_scrollnews_plugin_menu');  
	add_filter('widget_text', 'do_shortcode');
	/* Add our function to the widgets_init hook. */
	add_action('widgets_init', 'vns_verticalScrollSet');

	add_action('plugins_loaded', 'vns_load_lang_for_vertical_news_scroller');
	add_action('wp_enqueue_scripts', 'vns_news_scroller_load_styles_and_js');

	add_action('upgrader_process_complete', 'vns_vertical_news_upgrader_process_complete', 10, 4);
        
        add_filter('widget_text_content', 'vnsp_remove_extra_p_tags', 999);
        
        add_filter('the_content', 'vnsp_remove_extra_p_tags', 999);

        if(!function_exists('vns_load_lang_for_vertical_news_scroller')){
            
            function vns_load_lang_for_vertical_news_scroller() {

                load_plugin_textdomain('vertical-news-scroller', false, basename(dirname(__FILE__)) . '/languages/');
                add_filter('map_meta_cap', 'vns_map_vns_vertical_news_scroller_meta_caps', 10, 4);
                add_filter('user_has_cap', 'vns_vertical_news_admin_cap_list', 10, 4);

            }
            
        }
	
        if(!function_exists('vns_vertical_news_admin_cap_list')){

                function vns_vertical_news_admin_cap_list( $allcaps, $caps, $args, $user) {


                    if (! in_array('administrator', $user->roles) ) {

                            return $allcaps;
                    } else {

                            if (!isset($allcaps['vns_vertical_news_scroller_view_news'])) {

                                    $allcaps['vns_vertical_news_scroller_view_news']=true;
                            }

                            if (!isset($allcaps['vns_vertical_news_scroller_add_news'])) {

                                    $allcaps['vns_vertical_news_scroller_add_news']=true;
                            }

                            if (!isset($allcaps['vns_vertical_news_scroller_edit_news'])) {

                                    $allcaps['vns_vertical_news_scroller_edit_news']=true;
                            }

                            if (!isset($allcaps['vns_vertical_news_scroller_delete_news'])) {

                                    $allcaps['vns_vertical_news_scroller_delete_news']=true;
                            }

                    }

                    return $allcaps;
            }
            
        }
        
        if(!function_exists('vns_map_vns_vertical_news_scroller_meta_caps')){
            
            function vns_map_vns_vertical_news_scroller_meta_caps( array $caps, $cap, $user_id, array $args  ) {


                    if (! in_array(
                            
                        $cap, array( 
                                        'vns_vertical_news_scroller_view_news',
                                        'vns_vertical_news_scroller_add_news',
                                        'vns_vertical_news_scroller_edit_news',
                                        'vns_vertical_news_scroller_delete_news'
                                                  
                                    ), true 
                                            
                            ) 
                    ) 
                    {

                            return $caps;
                    }




                    $caps = array();

                    switch ( $cap ) {


                            case 'vns_vertical_news_scroller_view_news':
                                    $caps[] = 'vns_vertical_news_scroller_view_news';
                                    break;

                            case 'vns_vertical_news_scroller_add_news':
                                    $caps[] = 'vns_vertical_news_scroller_add_news';
                                    break;

                            case 'vns_vertical_news_scroller_edit_news':
                                    $caps[] = 'vns_vertical_news_scroller_edit_news';
                                    break;

                            case 'vns_vertical_news_scroller_delete_news':
                                    $caps[] = 'vns_vertical_news_scroller_delete_news';
                                    break;

                            default:
                                    $caps[] = 'do_not_allow';
                                    break;
                    }


                    return apply_filters('vns_vertical_news_scroller_map_meta_caps', $caps, $cap, $user_id, $args);
            }
        }
	
        if(!function_exists('vns_vertical_news_scroller_add_access_capabilities')){
            
                function vns_vertical_news_scroller_add_access_capabilities() {

                // Capabilities for all roles.
                $roles = array( 'administrator' );
                foreach ( $roles as $role ) {

                                $role = get_role($role);
                        if (empty($role) ) {
                                        continue;
                        }




                        if (!$role->has_cap('vns_vertical_news_scroller_view_news') ) {

                                        $role->add_cap('vns_vertical_news_scroller_view_news');
                        }

                        if (!$role->has_cap('vns_vertical_news_scroller_add_news') ) {

                                        $role->add_cap('vns_vertical_news_scroller_add_news');
                        }

                        if (!$role->has_cap('vns_vertical_news_scroller_edit_news') ) {

                                        $role->add_cap('vns_vertical_news_scroller_edit_news');
                        }

                        if (!$role->has_cap('vns_vertical_news_scroller_delete_news') ) {

                                        $role->add_cap('vns_vertical_news_scroller_delete_news');
                        }



                }

                $user = wp_get_current_user();
                $user->get_role_caps();
        }
	
}
    if(!function_exists('vns_news_scroller_load_styles_and_js')){
        
        function vns_news_scroller_load_styles_and_js() {

                if (!is_admin()) {                                                       

                                wp_register_style('news-style', plugins_url('/css/newsscrollcss.css', __FILE__), array(), '1.19');
                                wp_register_script('newscript', plugins_url('/js/jv.js', __FILE__), array ('jquery'), '2.0');
                                wp_register_script('newscriptv2', plugins_url('/js/i13_newsTicker.js', __FILE__), array ('jquery'), '1.15');

                }  
        }   
        
    }

    if(!function_exists('vns_table_column_exists')){
        
            function vns_table_column_exists( $table_name, $column_name ) {

                    global $wpdb;
                    $column = $wpdb->get_results(
                            $wpdb->prepare(
                                    'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ',
                                    DB_NAME, $table_name, $column_name
                            ) 
                    );
                    if (! empty($column) ) {
                             return true;
                    }
                    return false;

            } 
    }
    
    if(!function_exists('vns_install_newsscroller')){
  
            function vns_install_newsscroller() {

                    global $wpdb;
                    $table_name = $wpdb->prefix . 'scroll_news';
                    $charset_collate = $wpdb->get_charset_collate();

                    $sql = 'CREATE TABLE ' . $table_name . " (
                    id int(10) unsigned NOT NULL auto_increment,
                    title varchar(1000) NOT NULL,
                    content varchar(2000) NOT NULL,
                    createdon datetime NOT NULL,
                    custom_link varchar(1000) default NULL,
                    category_id int(10) unsigned NOT NULL DEFAULT '1',
                    PRIMARY KEY  (id)
                    ) $charset_collate;";
                    include_once ABSPATH . 'wp-admin/includes/upgrade.php';
                    dbDelta($sql);

                    if (vns_table_column_exists($table_name, 'category_id')==false) {


                                    $wpdb->query( 
                                                    $wpdb->prepare( 
                                                            '
                                                   ALTER TABLE ' . $wpdb->prefix . 'scroll_news
                                                    ADD `category_id` int(10) unsigned NOT NULL DEFAULT 1', ''


                                                    )
                                            );


                    }

                      vns_vertical_news_scroller_add_access_capabilities();


            } 

    }
    
    if(!function_exists('vns_vertical_news_upgrader_process_complete')){
        
        function vns_vertical_news_upgrader_process_complete() {

                vns_vertical_news_scroller_add_access_capabilities();
        }
    }
	
    if(!function_exists('vns_vertical_news_remove_access_capabilities')){
        
            function vns_vertical_news_remove_access_capabilities() {

                       global $wp_roles;

                       
                    if (isset($wp_roles) && $wp_roles!=NULL && is_object($wp_roles) && 0>count($wp_roles->roles)) {

                            foreach ( $wp_roles->roles as $role => $details ) {
                                      $role = $wp_roles->get_role($role);
                                    if (empty($role) ) {
                                            continue;
                                    }

                                $role->remove_cap('vns_vertical_news_scroller_view_news');
                                $role->remove_cap('vns_vertical_news_scroller_add_news');
                                $role->remove_cap('vns_vertical_news_scroller_edit_news');
                                $role->remove_cap('vns_vertical_news_scroller_delete_news');

                            }

                    }

                                    // Refresh current set of capabilities of the user, to be able to directly use the new caps.
                                    $user = wp_get_current_user();
                                    $user->get_role_caps();
            }
    }

      if(!function_exists('vns_scrollnews_plugin_menu')){
  
    
            function vns_scrollnews_plugin_menu() {

                    $hook_suffix_v_n=add_menu_page(__('Scroll news', 'vertical-news-scroller'), __('Manage Scrolling News', 'vertical-news-scroller'), 'vns_vertical_news_scroller_view_news', 'Scrollnews-settings', 'vns_managenews');
                    add_action('load-' . $hook_suffix_v_n, 'vertical_news_scroller_plugin_admin_init');
            }
      }

      if(!function_exists('vertical_news_scroller_plugin_admin_init')){
          
            function vertical_news_scroller_plugin_admin_init() {

                    $url = plugin_dir_url(__FILE__);
                    wp_enqueue_script('jquery');
                    wp_enqueue_script('jquery.validate', $url . 'js/jquery.validate.js', array(), '1.16');
                    wp_enqueue_style('admin-css', plugins_url('/css/admin-css.css', __FILE__), array(), '1.16');


            }
      }

      if(!function_exists('vns_verticalScrollSet')){
                    /* Function that registers our widget. */
            function vns_verticalScrollSet() {
                    register_widget('vns_verticalScroll');
            }
      }


      if(!function_exists('vns_managenews')){
          
      
            function vns_managenews() {

                    $action='gridview';
                    global $wpdb;


                    if (isset($_GET['action']) && ''!=$_GET['action']) {


                            $action=trim(sanitize_text_field($_GET['action']));
                    }

                    if (strtolower($action)==strtolower('gridview')) { 

                            if (! current_user_can('vns_vertical_news_scroller_view_news') ) {

                                    wp_die(__('Access Denied', 'vertical-news-scroller'));
                            }


                            ?> 
                            <div id="poststuff">
                                    <table><tr>
                                                    <td>
                                                              <div class="fb-like" data-href="https://www.facebook.com/i13websolution" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                                                              <div id="fb-root"></div>
                                                                    <script>(function(d, s, id) {
                                                                      var js, fjs = d.getElementsByTagName(s)[0];
                                                                      if (d.getElementById(id)) return;
                                                                      js = d.createElement(s); js.id = id;
                                                                      js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=158817690866061&autoLogAppEvents=1';
                                                                      fjs.parentNode.insertBefore(js, fjs);
                                                                    }(document, 'script', 'facebook-jssdk'));</script>
                                                      </td>
                                                    <td>
                                                            <a target="_blank" title="Donate" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=nvgandhi123@gmail.com&amp;item_name=Scroller News&amp;item_number=scroll news support&amp;no_shipping=0&amp;no_note=1&amp;tax=0&amp;currency_code=USD&amp;lc=US&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8">
                                                                    <img id="help us for free plugin" height="30" width="90" src="<?php echo esc_html(plugins_url('images/paypaldonate.jpg', __FILE__)); ?>" border="0" alt="help us for free plugin" title="help us for free plugin">
                                                            </a>
                                                    </td>
                                            </tr>
                                    </table>
                                    <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/wordpress-vertical-news-scroller-pro/"><?php echo __('UPGRADE TO PRO VERSION', 'vertical-news-scroller'); ?></a></h3></span>

                            <?php 

                                    $messages=get_option('scrollnews_messages'); 
                                    $type='';
                                    $message='';
                            if (isset($messages['type']) && ''!=$messages['type']) {

                                    $type=trim($messages['type']);
                                    $message=trim($messages['message']);

                            }  


                            if (trim($type)=='err') {
                                    echo "<div class='notice notice-error is-dismissible'><p>";
                                    echo $message;
                                    echo '</p></div>';
                            } else if (trim($type)=='succ') {
                                    echo "<div class='notice notice-success is-dismissible'><p>";
                                    echo $message;
                                    echo '</p></div>';
                            }


                                    update_option('scrollnews_messages', array());     
                            ?>

                                    <div id="post-body" class="metabox-holder columns-2">  
                                            <div id="post-body-content" >
                                                    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
                                                    <h1><?php echo __('News', 'vertical-news-scroller'); ?>&nbsp;&nbsp;<a class="button add-new-h2" href="admin.php?page=Scrollnews-settings&action=addedit"><?php echo __('Add New', 'vertical-news-scroller'); ?></a> </h1>
                                                    <br/>    

                                                    <form method="POST" action="admin.php?page=Scrollnews-settings&action=deleteselected" id="posts-filter" onkeypress="return event.keyCode != 13;">


                                                            <div class="alignleft actions">
                                                                    <select name="action_upper" id="action_upper">
                                                                            <option selected="selected" value="-1"><?php echo __('Bulk Actions', 'vertical-news-scroller'); ?></option>
                                                                            <option value="delete"><?php echo __('Delete', 'vertical-news-scroller'); ?></option>
                                                                    </select>
                                                                    <input type="submit" value="<?php echo __('Apply', 'vertical-news-scroller'); ?>" class="button-secondary action" id="deleteselected" name="deleteselected" onclick="return confirmDelete_bulk();">
                                                            </div>
                                                            <br/>  
                                                            <br/>  
                                                            <br class="clear">
                                                     <?php
                                                                    $setacrionpage='admin.php?page=Scrollnews-settings';

                                                            if (isset($_GET['order_by']) && ''!=$_GET['order_by']) {
                                                                    $setacrionpage.='&order_by=' . esc_html(sanitize_text_field($_GET['order_by']));   
                                                            }

                                                            if (isset($_GET['order_pos']) && ''!=$_GET['order_pos']) {
                                                                    $setacrionpage.='&order_pos=' .esc_html(sanitize_text_field($_GET['order_pos']));   
                                                            }

                                                                    $seval='';
                                                            if (isset($_GET['search_term']) && ''!= $_GET['search_term']) {
                                                                    $seval=esc_html(sanitize_text_field($_GET['search_term']));   
                                                            }


                                                            ?>
                                                            <?php 

                                                                    $order_by='id';
                                                                    $order_pos='asc';

                                                            if (isset($_GET['order_by']) && sanitize_sql_orderby($_GET['order_by'])!==false) {

                                                                    $order_by=esc_html(sanitize_sql_orderby($_GET['order_by'])); 
                                                            }

                                                            if (isset($_GET['order_pos'])) {

                                                                    $order_pos=esc_html(sanitize_text_field($_GET['order_pos'])); 
                                                            }
                                                                     $search_term='';
                                                            if (isset($_GET['search_term'])) {

                                                                    $search_term= esc_html(sanitize_text_field($_GET['search_term']));
                                                            }

                                                                    $search_term_='';
                                                            if (isset($_GET['search_term'])) {

                                                                    $search_term_='&search_term=' . esc_html(sanitize_text_field($_GET['search_term']));
                                                            }


                                                            $order_by=esc_html(sanitize_text_field($order_by));
                                                            $order_pos=esc_html(sanitize_text_field(sanitize_sql_orderby($order_pos)));


                                                            if (''!=$search_term) {


                                                                    $rowsCount=$wpdb->get_var($wpdb->prepare('SELECT count(*) FROM ' . $wpdb->prefix . 'scroll_news where  ( id like %d or title like %s ) ', '%' . $wpdb->esc_like($search_term) . '%', '%' . $wpdb->esc_like($search_term) . '%'));
                                                            } else {

                                                                    $rowsCount=$wpdb->get_var($wpdb->prepare('SELECT count(*) FROM ' . $wpdb->prefix . 'scroll_news  where 1=%d ', 1));

                                                            }

                                                            ?>
                                                              <div style="padding-top:5px;padding-bottom:5px">
                                                                            <b><?php echo __('Search', 'vertical-news-scroller'); ?> : </b>
                                                                              <input type="text" value="<?php echo esc_html($seval); ?>" id="search_term" name="search_term">&nbsp;
                                                                              <input type='button'  value='<?php echo __('Search', 'vertical-news-scroller'); ?>' name='searchusrsubmit' class='button-primary' id='searchusrsubmit' onclick="SearchredirectTO();" >&nbsp;
                                                                              <input type='button'  value='<?php echo __('Reset Search', 'vertical-news-scroller'); ?>' name='searchreset' class='button-primary' id='searchreset' onclick="ResetSearch();" >
                                                                    </div>  
                                                                    <script type="text/javascript" >
                                                                            jQuery('#search_term').on("keyup", function(e) {
                                                                                       if (e.which == 13) {

                                                                                               SearchredirectTO();
                                                                                       }
                                                                              });   
                                                                     function SearchredirectTO(){
                                                                       var searchval=jQuery('#search_term').val();
                                                                       redirectto=window.location.href+'&search_term='+jQuery.trim(encodeURIComponent(searchval));  
                                                                                                                               window.location.href=redirectto;
                                                                     }
                                                                    function ResetSearch(){

                                                                             var redirectto='<?php echo esc_url($setacrionpage); ?>';
                                                                             window.location.href=redirectto;
                                                                             exit;
                                                                    }
                                                                    </script>
                                                            <div id="no-more-tables">
                                                                    <table cellspacing="0" id="gridTbl" class="table-bordered table-striped table-condensed cf " >
                                                                            <thead>
                                                                                    <tr>
                                                                                            <th class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
                                                                                     <?php if ('title'==$order_by && 'asc'==$order_pos) : ?>
                                                                                                    <th class="alignLeft"><a href="<?php echo esc_url($setacrionpage); ?>&order_by=title&order_pos=desc<?php echo esc_html($search_term_); ?>"><?php echo __('Title', 'vertical-news-scroller'); ?><img style="vertical-align:middle" src="<?php echo esc_url(plugins_url('/images/desc.png', __FILE__)); ?>"/></a></th>
                                                                                            <?php else : ?>
                                                                                                    <?php if ('title'==$order_by) : ?>
                                                                                                            <th class="alignLeft"><a href="<?php echo esc_url($setacrionpage); ?>&order_by=title&order_pos=asc<?php echo esc_html($search_term_); ?>"><?php echo __('Title', 'vertical-news-scroller'); ?><img style="vertical-align:middle" src="<?php echo esc_url(plugins_url('/images/asc.png', __FILE__)); ?>"/></a></th>
                                                                                                    <?php else : ?>
                                                                                                            <th class="alignLeft"><a href="<?php echo esc_url($setacrionpage); ?>&order_by=title&order_pos=asc<?php echo esc_html($search_term_); ?>"><?php echo __('Title', 'vertical-news-scroller'); ?></a></th>
                                                                                                    <?php endif; ?>    
                                                                                            <?php endif; ?> 
                                                                                    <?php if ('createdon'==$order_by && 'asc'==$order_pos) : ?>
                                                                                                    <th><a href="<?php echo esc_url($setacrionpage); ?>&order_by=createdon&order_pos=desc<?php echo esc_html($search_term_); ?>"><?php echo __('Published On', 'vertical-news-scroller'); ?><img style="vertical-align:middle" src="<?php echo esc_url(plugins_url('/images/desc.png', __FILE__)); ?>"/></a></th>
                                                                                            <?php else : ?>
                                                                                                    <?php if ('createdon'==$order_by) : ?>
                                                                                                            <th><a href="<?php echo esc_url($setacrionpage); ?>&order_by=createdon&order_pos=asc<?php echo esc_html($search_term_); ?>"><?php echo __('Published On', 'vertical-news-scroller'); ?><img style="vertical-align:middle" src="<?php echo esc_url(plugins_url('/images/asc.png', __FILE__)); ?>"/></a></th>
                                                                                                    <?php else : ?>
                                                                                                            <th><a href="<?php echo esc_url($setacrionpage); ?>&order_by=createdon&order_pos=asc<?php echo esc_html($search_term_); ?>"><?php echo __('Published On', 'vertical-news-scroller'); ?></a></th>
                                                                                                    <?php endif; ?>    
                                                                                            <?php endif; ?>
                                                                                            <th><span><?php echo __('Edit', 'vertical-news-scroller'); ?></span></th>
                                                                                            <th><span><?php echo __('Delete', 'vertical-news-scroller'); ?></span></th>
                                                                                    </tr> 
                                                                            </thead>

                                                                            <tbody id="the-list">
                                                                            <?php

                                                                            if ($rowsCount>0) {

                                                                                    global $wp_rewrite;
                                                                                    $rows_per_page = 10;

                                                                                    $current = ( isset($_GET['paged']) ) ? intval($_GET['paged']) : 1;
                                                                                    $pagination_args = array(
                                                                                            'base' => @add_query_arg('paged', '%#%'),
                                                                                            'format' => '',
                                                                                            'total' => ceil($rowsCount/$rows_per_page),
                                                                                            'current' => $current,
                                                                                            'show_all' => false,
                                                                                            'type' => 'plain',
                                                                                    );


                                                                                    $offset = ( $current - 1 ) * $rows_per_page;


                                                                                    if (''!=$search_term) {

                                                                                             $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'scroll_news where  ( id like %d or title like %s ) order by ' . esc_sql($order_by) . ' ' . esc_sql($order_pos) . '  limit ' . esc_sql($offset) . ', ' . esc_sql($rows_per_page), '%' . $wpdb->esc_like($search_term) . '%', '%' . $wpdb->esc_like($search_term) . '%'), ARRAY_A);
                                                                                    } else {

                                                                                             $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'scroll_news where  1=%d order by ' . esc_sql($order_by) . ' ' . esc_sql($order_pos) . ' limit ' . esc_sql($offset) . ',' . esc_sql($rows_per_page), 1), ARRAY_A);

                                                                                    }

                                                                                    $delRecNonce=wp_create_nonce('delete_news');
                                                                                    foreach ($rows as $row ) {


                                                                                            $id=$row['id'];
                                                                                            $editlink="admin.php?page=Scrollnews-settings&action=addedit&id=$id";
                                                                                            $deletelink="admin.php?page=Scrollnews-settings&action=delete&id=$id&nonce=$delRecNonce";

                                                                                            ?>
                                                                                                    <tr valign="top" >
                                                                                                            <td class="alignCenter check-column"   data-title="<?php echo __('Select Record', 'vertical-news-scroller'); ?>" ><input type="checkbox" value="<?php echo intval($row['id']); ?>" name="news[]"></td>
                                                                                                            <td class=""   data-title="<?php echo __('Name', 'vertical-news-scroller'); ?>" ><strong><?php echo esc_html(stripslashes_deep($row['title'])); ?></strong></td>  
                                                                                                            <td class="alignCenter"   data-title="<?php echo __('Published On', 'vertical-news-scroller'); ?>"><span><?php echo esc_html(gmdate(get_option('date_format')) . ' ' . gmdate(esc_html(get_option('time_format'), strtotime($row['createdon'])))); ?></span></td>
                                                                                                            <td class="alignCenter"   data-title="<?php echo __('Edit', 'vertical-news-scroller'); ?>"><strong><a href='<?php echo esc_url($editlink); ?>' title="<?php echo __('Edit', 'vertical-news-scroller'); ?>"><?php echo __('Edit', 'vertical-news-scroller'); ?></a></strong></td>  
                                                                                                            <td class="alignCenter"   data-title="<?php echo __('Delete', 'vertical-news-scroller'); ?>"><strong><a href='<?php echo esc_url($deletelink); ?>' onclick="return confirmDelete();"  title="<?php echo __('Delete', 'vertical-news-scroller'); ?>"><?php echo __('Delete', 'vertical-news-scroller'); ?></a> </strong></td>  
                                                                                                    </tr>

                                                                                                    <?php 
                                                                                    } 
                                                                            } else {
                                                                                    ?>

                                                                                            <tr valign="top" class="" id="">
                                                                                                    <td colspan="5" data-title="<?php echo __('No Record', 'vertical-news-scroller'); ?>" align="center"><strong><?php echo __('No News Found', 'vertical-news-scroller'); ?></strong></td>  
                                                                                            </tr>
                                                                                            <?php 
                                                                            } 
                                                                            ?>

                                                                            </tbody>
                                                                    </table>
                                                            </div>
                                                       <?php
                                                            if ($rowsCount>0) {

                                                                    echo "<div class='pagination' style='padding-top:10px'>";
                                                                    echo wp_kses(paginate_links($pagination_args), wp_kses_allowed_html('post'));
                                                                    echo '</div>';
                                                            }
                                                            ?>
                                                            <br/>
                                                            <div class="alignleft actions">
                                                                    <select name="action" id="action_bottom">
                                                                            <option selected="selected" value="-1"><?php echo __('Bulk Actions', 'vertical-news-scroller'); ?></option>
                                                                            <option value="delete"><?php echo __('Delete', 'vertical-news-scroller'); ?></option>
                                                                    </select>
                                                            <?php wp_nonce_field('action_news_mass_delete', 'mass_delete_nonce'); ?>
                                                                    <input type="submit" value="<?php echo __('Apply', 'vertical-news-scroller'); ?>" class="button-secondary action" id="deleteselected" name="deleteselected" onclick="return confirmDelete_bulk();">
                                                            </div>
                                                            <br/>
                                                            <br/>
                                                            <h3><?php echo __('To print this news scroller either you can use theme widget feature or use below shortcode', 'vertical-news-scroller'); ?></h3>
                                                            <h4><?php echo __('JQuery Scroller', 'vertical-news-scroller'); ?></h4>
                                                            <textarea style="text-align:left" cols="80" rows="3" onclick="this.focus(); this.select()">[print_vertical_news_scroll s_type="modern" maxitem="5" padding="10" add_link_to_title="1" show_content="1" modern_scroller_delay="5000" modern_speed="1700" height="200" width="100%" direction="up" lib="v1" ]</textarea>
                                                               <br/>
                                                            <div> <?php echo __('Use', 'vertical-news-scroller'); ?> lib="v2" <?php echo __('If you find problem with v1', 'vertical-news-scroller'); ?></div>

                                                            <br/>
                                                            <h4><?php echo __('Marquee Scroller', 'vertical-news-scroller'); ?></h4>
                                                            <textarea style="text-align:left" cols="80" rows="3" onclick="this.focus(); this.select()">[print_vertical_news_scroll s_type="classic" maxitem="5" padding="10" add_link_to_title="1" show_content="1" delay="60" height="200" width="100%" scrollamount="1" direction="up" ]</textarea>
                                                    </form>
                                                    <script type="text/JavaScript">

                                                            function  confirmDelete(){
                                                                    var agree=confirm("<?php echo __('Are you sure you want to delete this news ?', 'vertical-news-scroller'); ?>");
                                                                    if (agree)
                                                                            return true ;
                                                                    else
                                                                            return false;
                                                            }

                                                            function  confirmDelete_bulk(){
                                                                    var topval=document.getElementById("action_bottom").value;
                                                                    var bottomVal=document.getElementById("action_upper").value;

                                                                    if(topval=='delete' || bottomVal=='delete'){


                                                                            var agree=confirm("<?php echo __('Are you sure you want to delete selected news?', 'vertical-news-scroller'); ?>");
                                                                            if (agree)
                                                                                    return true ;
                                                                            else
                                                                                    return false;
                                                                    }
                                                            }
                                                    </script>


                                                    <br class="clear">
                                            </div>
                                            <div id="postbox-container-1" class="postbox-container"> 


                                                    <div class="postbox"> 
                                                            <h3 class="hndle"><span></span><?php echo __('New AI DIVI Theme', 'vertical-news-scroller'); ?></h3> 
                                                            <div class="inside">
                                                                    <center><a href="https://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715&url=80806" target="_blank"><img border="0" src="<?php echo esc_url(plugins_url('images/divi_300x250.jpg', __FILE__)); ?>" width="250" height="250"></a></center>

                                                                    <div style="margin:10px 5px">

                                                                    </div>
                                                            </div></div>

                                                     <div class="postbox"> 
                                                            <h3 class="hndle"><span></span><?php echo __('Google For Business Coupon', 'vertical-news-scroller'); ?></h3> 
                                                                    <div class="inside">
                                                                            <center><a href="http://i13websolution.com/google-workspace.html" target="_blank">
                                                                                            <img src="<?php echo esc_url(plugins_url('images/g-suite-promo-code-4.png', __FILE__)); ?>" width="250" height="250" border="0">
                                                                                    </a></center>
                                                                            <div style="margin:10px 5px">
                                                                            </div>
                                                                    </div>

                                                            </div>
                                            </div>

                                    </div>  
                            </div>  

                            <?php 
                    } else if (strtolower($action)==strtolower('addedit')) {
                            ?>
                            <br/>

                            <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/product/wordpress-vertical-news-scroller-pro/"><?php echo __('UPGRADE TO PRO VERSION', 'vertical-news-scroller'); ?></a></h3></span>
                            <?php        
                            if (isset($_POST['btnsave'])) {


                                    if (!check_admin_referer('action_news_add_edit', 'add_edit_nonce')) {

                                                      wp_die('Security check fail'); 
                                    }

                                            //edit save
                                    if (isset($_POST['newsid'])) {

                                            //add new

                                            if (! current_user_can('vns_vertical_news_scroller_edit_news') ) {

                                                    $scrollnews_messages=array();
                                                    $scrollnews_messages['type']='err';
                                                    $scrollnews_messages['message']=__('Access Denied. Please contact your administrator', 'vertical-news-scroller');
                                                    update_option('scrollnews_messages', $scrollnews_messages);
                                                    $location='admin.php?page=Scrollnews-settings';
                                                    ?>
                                                    <script type='text/javascript'> location.href='<?php echo esc_url($location); ?>';</script>
                                                    <?php
                                                    exit;


                                            } 

                                            $newsurl='';
                                            if (isset($_POST['newstitle'])) {

                                                    $title=trim(htmlentities(sanitize_text_field($_POST['newstitle']), ENT_QUOTES));
                                            }
                                            if (isset($_POST['newsurl'])) {

                                                    $newsurl=trim(htmlentities(esc_url_raw($_POST['newsurl']), ENT_QUOTES));
                                            }

                                            if (isset($_POST['newscont'])) {
                                                    $contant=trim(strip_tags(wp_kses($_POST['newscont'], wp_kses_allowed_html('post')), '<br><a><b><i><span><h1><h2><h3><h4><h5><h6><hr><p><ul><li>'));
                                            }

                                            if (isset($_POST['newsid'])) {
                                                    $newsId=intval($_POST['newsid']);
                                            }

                                            $location='admin.php?page=Scrollnews-settings';

                                            try {

                                                    $wpdb->update( 
                                                            $wpdb->prefix . 'scroll_news', 
                                                            array( 
                                                                                                    'title' => $title,    
                                                                                                    'content' => $contant
                                                                                    ), 
                                                            array( 'id' => $newsId ), 
                                                            array( 
                                                                                                    '%s',    
                                                                                                    '%s'    
                                                                                    ), 
                                                            array( '%d' ) 
                                                    );


                                                    $scrollnews_messages=array();
                                                    $scrollnews_messages['type']='succ';
                                                    $scrollnews_messages['message']='News updated successfully.';
                                                    update_option('scrollnews_messages', $scrollnews_messages);


                                            } catch (Exception $e) {

                                                    $scrollnews_messages=array();
                                                    $scrollnews_messages['type']='err';
                                                    $scrollnews_messages['message']='Error while updating news.';
                                                    update_option('scrollnews_messages', $scrollnews_messages);
                                            }  

                                            ?>
                                            <script> location.href='<?php echo esc_url($location); ?>';</script>
                                            <?php
                                            exit;
                                            
                                    } else {

                                            //add new

                                            if (! current_user_can('vns_vertical_news_scroller_add_news') ) {


                                                    $scrollnews_messages=array();
                                                    $scrollnews_messages['type']='err';
                                                    $scrollnews_messages['message']=__('Access Denied. Please contact your administrator', 'vertical-news-scroller');
                                                    update_option('scrollnews_messages', $scrollnews_messages);
                                                    $location='admin.php?page=Scrollnews-settings';



                                                    ?>
                                                    <script> location.href='<?php echo esc_url($location); ?>';</script>
                                                       <?php
                                                            exit;
                                            }
                                                        $title=trim(htmlentities(sanitize_text_field($_POST['newstitle']), ENT_QUOTES));
                                                        $newsurl=trim(htmlentities(sanitize_text_field($_POST['newsurl']), ENT_QUOTES));
                                                        $contant=trim(strip_tags(wp_kses($_POST['newscont'], wp_kses_allowed_html('post')), '<br><a><b><i><span><h1><h2><h3><h4><h5><h6><hr><p><ul><li>'));
                                                        /*
                                                        $createdOn=@date( 'Y-m-d H:i:s', current_time( 'mysql' ));
                                                        if(get_option('time_format')=='H:i')
                                                        $createdOn=date('Y-m-d H:i:s',strtotime(current_time('mysql')));
                                                        else   
                                                        $createdOn=date('Y-m-d h:i:s',strtotime(current_time('mysql')));
                                                        * 
                                                        */

                                                        $createdOn=current_time('mysql');


                                                        $location='admin.php?page=Scrollnews-settings';

                                            try {


                                                       $wpdb->insert( 
                                                                       $wpdb->prefix . 'scroll_news', 
                                                                       array( 
                                                                                        'title' => $title, 
                                                                                        'content' => $contant, 
                                                                                        'createdon' => $createdOn, 
                                                                                        'custom_link' => $newsurl, 
                                                                                       ), 
                                                                       array( 
                                                                                            '%s', 
                                                                                            '%s', 
                                                                                            '%s', 
                                                                                            '%s', 
                                                                                       ) 
                                                       );

                                                       $scrollnews_messages=array();
                                                       $scrollnews_messages['type']='succ';
                                                       $scrollnews_messages['message']=__('New news added successfully.', 'vertical-news-scroller');
                                                       update_option('scrollnews_messages', $scrollnews_messages);


                                            } catch (Exception $e) {

                                                       $scrollnews_messages=array();
                                                       $scrollnews_messages['type']='err';
                                                       $scrollnews_messages['message']=__('Error while adding news.', 'vertical-news-scroller');
                                                       update_option('scrollnews_messages', $scrollnews_messages);
                                            }  

                                            ?>
                                            <script> location.href='<?php echo esc_url($location); ?>';</script>
                                            <?php       
                                                 exit;
                                    } 

                            } else { 

                                    ?>
                                    <table><tr>
                                                    <td>
                                                              <div class="fb-like" data-href="https://www.facebook.com/i13websolution" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                                                              <div id="fb-root"></div>
                                                                    <script>(function(d, s, id) {
                                                                      var js, fjs = d.getElementsByTagName(s)[0];
                                                                      if (d.getElementById(id)) return;
                                                                      js = d.createElement(s); js.id = id;
                                                                      js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=158817690866061&autoLogAppEvents=1';
                                                                      fjs.parentNode.insertBefore(js, fjs);
                                                                    }(document, 'script', 'facebook-jssdk'));</script>
                                                      </td>
                                                    <td>
                                                            <a target="_blank" title="Donate" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=nvgandhi123@gmail.com&amp;item_name=Scroller News&amp;item_number=scroll news support&amp;no_shipping=0&amp;no_note=1&amp;tax=0&amp;currency_code=USD&amp;lc=US&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8">
                                                                    <img id="help us for free plugin"  height="30" width="90" src="<?php echo esc_url(plugins_url('images/paypaldonate.jpg', __FILE__)); ?>" border="0" alt="help us for free plugin" title="help us for free plugin">
                                                            </a>

                                                    </td>
                                            </tr></table>
                                    <div id="poststuff">
                                            <div id="post-body" class="metabox-holder columns-2">
                                                    <div id="post-body-content">
                                                            <div class="wrap">
                                                            <?php 
                                                            if (isset($_GET['id']) && intval($_GET['id'])>0) { 

                                                                    if (! current_user_can('vns_vertical_news_scroller_edit_news') ) {

                                                                            $scrollnews_messages=array();
                                                                            $scrollnews_messages['type']='err';
                                                                            $scrollnews_messages['message']=__('Access Denied. Please contact your administrator', 'vertical-news-scroller');
                                                                            update_option('scrollnews_messages', $scrollnews_messages);
                                                                            $location='admin.php?page=Scrollnews-settings';
                                                                            ?>
                                                                            <script> location.href='<?php echo esc_url($location); ?>';</script>
                                                                            <?php   
                                                                            exit;

                                                                    } 

                                                                    $id= intval($_GET['id']);
                                                                    $myrow  = $wpdb->get_row($wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'scroll_news WHERE id=%d', $id));

                                                                    if (is_object($myrow)) {

                                                                            $title=stripslashes_deep($myrow->title);
                                                                            $newsurl=$myrow->custom_link;
                                                                            $contant=stripslashes_deep($myrow->content);

                                                                    }   

                                                                    ?>

                                                                            <h1><?php echo __('Update News', 'vertical-news-scroller'); ?></h1>

                                                                    <?php 
                                                            } else { 


                                                                    if (! current_user_can('vns_vertical_news_scroller_add_news') ) {

                                                                            $scrollnews_messages=array();
                                                                            $scrollnews_messages['type']='err';
                                                                            $scrollnews_messages['message']=__('Access Denied. Please contact your administrator', 'vertical-news-scroller');
                                                                            update_option('scrollnews_messages', $scrollnews_messages);
                                                                            $location='admin.php?page=Scrollnews-settings';
                                                                            ?>
                                                                        <script> location.href='<?php echo esc_url($location); ?>';</script>
                                                                        <?php  
                                                                        exit;
                                                                    } 

                                                                    $title='';
                                                                    $newsurl='';
                                                                    $contant='';

                                                                    ?>
                                                                            <h1><?php echo __('Add News', 'vertical-news-scroller'); ?> </h1>
                                                            <?php } ?>

                                                                    <div id="poststuff">
                                                                            <div id="post-body" class="metabox-holder columns-2">
                                                                                    <div id="post-body-content">
                                                                                            <form method="post" action="" id="addnews" name="addnews">

                                                                                                    <div class="stuffbox" id="namediv" style="width:100%">
                                                                                                            <h3><label for="link_name"><?php echo __('News Title', 'vertical-news-scroller'); ?></label></h3>
                                                                                                            <div class="inside">
                                                                                                                    <input type="text" id="newstitle"  class="required"  size="30" name="newstitle" value="<?php echo esc_html($title); ?>">
                                                                                                                    <div style="clear:both"></div>
                                                                                                                    <div></div>
                                                                                                                    <div style="clear:both"></div>
                                                                                                                    <p><?php echo __('This title will scroll', 'vertical-news-scroller'); ?></p>
                                                                                                            </div>
                                                                                                    </div>
                                                                                                    <div class="stuffbox" id="namediv" style="width:100%">
                                                                                                            <h3><label for="link_name"><?php echo __('News Url', 'vertical-news-scroller'); ?></label></h3>
                                                                                                            <div class="inside">
                                                                                                                    <input type="text" id="newsurl" class="url2"   size="30" name="newsurl" value="<?php echo esc_url($newsurl); ?>">
                                                                                                                    <div style="clear:both"></div>
                                                                                                                    <div></div>
                                                                                                                    <div style="clear:both"></div>
                                                                                                                    <p><?php echo __('On news title click users will redirect to this url.', 'vertical-news-scroller'); ?></p>
                                                                                                            </div>
                                                                                                    </div>
                                                                                                    <div class="stuffbox" id="namediv" style="width:100%">
                                                                                                            <h3><label for="link_name"><?php echo __('News Content', 'vertical-news-scroller'); ?></label></h3>
                                                                                                            <div class="inside">
                                                                                                                    <textarea cols="90" class="required" style="width:100%" rows="6" id="newscont" name="newscont"><?php echo wp_kses($contant, wp_kses_allowed_html('post')); ?></textarea>
                                                                                                                    <div style="clear:both"></div>
                                                                                                                    <div></div>
                                                                                                                    <div style="clear:both"></div>
                                                                                                                    <p><?php echo __('Two three lines summary', 'vertical-news-scroller'); ?></p>
                                                                                                            </div>
                                                                                                    </div>
                                                                                            <?php if (isset($_GET['id']) && intval($_GET['id'])>0) { ?> 
                                                                                                            <input type="hidden" name="newsid" id="newsid" value="<?php echo intval($_GET['id']); ?>">
                                                                                                            <?php
                                                                                            } 
                                                                                            ?>

                                                                                            <?php wp_nonce_field('action_news_add_edit', 'add_edit_nonce'); ?>    
                                                                                                    <input type="submit" name="btnsave" id="btnsave" value="<?php echo __('Save Changes', 'vertical-news-scroller'); ?>" class="button-primary">&nbsp;&nbsp;<input type="button" name="cancle" id="cancle" value="<?php echo __('Cancel', 'vertical-news-scroller'); ?>" class="button-primary" onclick="location.href='admin.php?page=Scrollnews-settings'">

                                                                                            </form> 
                                                                                            <script>
                                                                                                    jQuery(document).ready(function() {  
                                                                                                                    jQuery("#addnews").validate({
                                                                                                                                    errorClass: "news_error",
                                                                                                                                    errorPlacement: function(error, element) {
                                                                                                                                            error.appendTo( element.next().next().next());
                                                                                                                                    }

                                                                                                                    })
                                                                                                    });

                                                                                            </script> 

                                                                                    </div>
                                                                            </div>
                                                                    </div>  
                                                            </div>      
                                                    </div>
                                                    <div id="postbox-container-1" class="postbox-container"> 

                                                            <div class="postbox"> 
                                                                    <h3 class="hndle"><span></span><?php echo __('Access All Themes One price', 'vertical-news-scroller'); ?></h3> 
                                                                    <div class="inside">
                                                                            <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank"><img border="0" src="<?php echo esc_url(plugins_url('images/300x250.gif', __FILE__)); ?>" width="250" height="250"></a></center>

                                                                            <div style="margin:10px 5px">

                                                                            </div>
                                                                    </div></div>

                                                            <div class="postbox"> 
                                                            <h3 class="hndle"><span></span><?php echo __('Google For Business Coupon', 'vertical-news-scroller'); ?></h3> 
                                                                    <div class="inside">
                                                                            <center><a href="http://i13websolution.com/google-workspace.html" target="_blank">
                                                                                            <img src="<?php echo esc_url(plugins_url('images/g-suite-promo-code-4.png', __FILE__)); ?>" width="250" height="250" border="0">
                                                                                    </a></center>
                                                                            <div style="margin:10px 5px">
                                                                            </div>
                                                                    </div>

                                                            </div>


                                                    </div> 

                                            </div>         

                                    </div>
                                    <?php 
                            } 
                    } else if (strtolower($action)==strtolower('delete')) {

                             $retrieved_nonce = '';

                            if (isset($_GET['nonce']) && ''!=trim(sanitize_text_field($_GET['nonce']))) {

                                    $retrieved_nonce=sanitize_text_field($_GET['nonce']);

                            }
                            if (!wp_verify_nonce($retrieved_nonce, 'delete_news') ) {


                                    wp_die('Security check fail'); 
                            }

                            if (! current_user_can('vns_vertical_news_scroller_delete_news') ) {

                                    $scrollnews_messages=array();
                                    $scrollnews_messages['type']='err';
                                    $scrollnews_messages['message']=__('Access Denied. Please contact your administrator', 'vertical-news-scroller');
                                    update_option('scrollnews_messages', $scrollnews_messages);
                                    $location='admin.php?page=Scrollnews-settings';
                                    ?>
                                    <script> location.href='<?php echo esc_url($location); ?>';</script>
                                     <?php
                                      exit;

                            } 

                            $location='admin.php?page=Scrollnews-settings';
                            $deleteId=intval($_GET['id']);

                            try {

                                            $wpdb->query( 
                                                    $wpdb->prepare( 
                                                            '
                                                   DELETE FROM ' . $wpdb->prefix . 'scroll_news
                                                    WHERE id = %d',
                                                            $deleteId 
                                                    )
                                            );



                                    $scrollnews_messages=array();
                                    $scrollnews_messages['type']='succ';
                                    $scrollnews_messages['message']=__('News deleted successfully.', 'vertical-news-scroller');
                                    update_option('scrollnews_messages', $scrollnews_messages);


                            } catch (Exception $e) {

                                    $scrollnews_messages=array();
                                    $scrollnews_messages['type']='err';
                                    $scrollnews_messages['message']=__('Error while deleting news.', 'vertical-news-scroller');
                                    update_option('scrollnews_messages', $scrollnews_messages);
                            }  
                            ?>
                            <script> location.href='<?php echo esc_url($location); ?>';</script>
                            <?php
                            exit;

                    } else if (strtolower($action)==strtolower('deleteselected')) {

                            if (!check_admin_referer('action_news_mass_delete', 'mass_delete_nonce')) {

                                      wp_die('Security check fail'); 
                            }

                            if (! current_user_can('vns_vertical_news_scroller_delete_news') ) {

                                    $scrollnews_messages=array();
                                    $scrollnews_messages['type']='err';
                                    $scrollnews_messages['message']=__('Access Denied. Please contact your administrator', 'vertical-news-scroller');
                                    update_option('scrollnews_messages', $scrollnews_messages);
                                    $location='admin.php?page=Scrollnews-settings';
                                    ?>
                                    <script> location.href='<?php echo esc_url($location); ?>';</script>
                                    <?php
                                    exit;
                            } 

                                    $location='admin.php?page=Scrollnews-settings'; 
                            if (isset($_POST) && isset($_POST['deleteselected']) && isset($_POST['action']) && ( 'delete' ==sanitize_text_field($_POST['action']) || ( isset($_POST['action_upper']) && 'delete'==sanitize_text_field($_POST['action_upper']) ) )) {

                                    if (isset($_POST['news']) && is_array($_POST['news']) && count($_POST['news']) >0) {

                                            $deleteto = array_map( 'sanitize_text_field', wp_unslash( $_POST['news'] ) );

                                            try {

                                                    if (is_array($deleteto)) {

                                                            foreach ($deleteto as $deleteId) {

                                                                    $deleteId=intval($deleteId);

                                                                    $wpdb->query( 
                                                                            $wpdb->prepare( 
                                                                                    '
                                                                    DELETE FROM ' . $wpdb->prefix . 'scroll_news
                                                                     WHERE id = %d',
                                                                                    $deleteId 
                                                                            )
                                                                    );



                                                            }  

                                                    }
                                                    $scrollnews_messages=array();
                                                    $scrollnews_messages['type']='succ';
                                                    $scrollnews_messages['message']=__('selected news deleted successfully.', 'vertical-news-scroller');
                                                    update_option('scrollnews_messages', $scrollnews_messages);


                                            } catch (Exception $e) {

                                                    $scrollnews_messages=array();
                                                    $scrollnews_messages['type']='err';
                                                    $scrollnews_messages['message']=__('Error while deleting news.', 'vertical-news-scroller');
                                                    update_option('scrollnews_messages', $scrollnews_messages);
                                            }  
                                            ?>
                                            <script> location.href='<?php echo esc_url($location); ?>';</script>
                                            <?php
                                            exit;


                                    } else {
                                            ?>
                                            <script> location.href='<?php echo esc_url($location); ?>';</script>
                                            <?php
                                            exit; 
                                    }

                            } else {
                                    ?>
                                    <script> location.href='<?php echo esc_url($location); ?>';</script>
                                    <?php
                                    exit;
                            }

                    }    
            }
    }
    
    
   if(!function_exists('vns_print_verticalScroll_func')){
         
            function vns_print_verticalScroll_func( $atts) {

                    global $wpdb;
                    extract(shortcode_atts(array('maxitem' => 5,), $atts));
                    extract(shortcode_atts(array('padding' => 5,), $atts));
                    extract(shortcode_atts(array('add_link_to_title' => 1,), $atts));
                    extract(shortcode_atts(array('show_content' => 1,), $atts));
                    extract(shortcode_atts(array('delay' => 60,), $atts));
                    extract(shortcode_atts(array('modern_scroller_delay' => 5000,), $atts));
                    extract(shortcode_atts(array('height' => 200,), $atts));
                    extract(shortcode_atts(array('width' => 220,), $atts));
                    extract(shortcode_atts(array('scrollamount' => 1,), $atts));
                    extract(shortcode_atts(array('modern_speed' => 1700,), $atts));
                    extract(shortcode_atts(array('s_type' => 'modern',), $atts));
                    extract(shortcode_atts(array('direction' => 'up',), $atts));
                    extract(shortcode_atts(array('lib' => 'v1',), $atts));

                    $maxitem=intval($maxitem);
                    $padding=intval($padding);
                    $add_link_to_title=intval($add_link_to_title);
                    $show_content=intval($show_content);
                    $delay=intval($delay);
                    $modern_scroller_delay=intval($modern_scroller_delay);
                    $height=intval($height);
                    $width=sanitize_text_field($width);
                    $scrollamount=intval($scrollamount);
                    $modern_speed=intval($modern_speed);
                    $s_type=sanitize_text_field($s_type);
                    $direction=sanitize_text_field($direction);

                    $randomNum=rand(0, 10000);
                    if ('classic'==$s_type) {
                            $news_style='classic';  
                    } else if ('modern'==$s_type) {
                            $news_style='modern';  
                    }
                    global $wpdb;

                            $rows  = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'scroll_news order by createdon DESC limit %d', $maxitem), ARRAY_A);


                    wp_enqueue_style('news-style');
                    wp_enqueue_script('jquery');
                    if ('v2'==$lib) {
                              wp_enqueue_script('newscriptv2');
                    } else {
                            wp_enqueue_script('newscript');

                    }


                            ob_start();
                    ?>
                    <!-- print_verticalScroll_func -->
                      <?php if ('classic'==$news_style) { ?>  
                            <marquee height='<?php echo esc_html($height); ?>' direction="<?php echo esc_html(strtolower($direction)); ?>"  onmouseout="this.start()" onmouseover="this.stop()" scrolldelay="<?php echo esc_html($delay); ?>" truespeed scrollamount="<?php echo esc_html($scrollamount); ?>" direction="up" behavior="scroll" >
                      <?php } ?>  
                             <div id="news-container_<?php echo esc_html($randomNum); ?>" class="news-container" style="max-width: <?php echo esc_html($width); ?>;visibility: hidden">
                                    <ul>
                                    <?php

                                    foreach ($rows as $row) {
                                            ?>
                                                    <li>
                                                            <div style="padding:<?php echo esc_html($padding); ?>px">
                                                                    <div class="newsscroller_title">
                                                                    <?php 
                                                                    if ($add_link_to_title && trim($row['custom_link'])!='') {
                                                                            ?>
                                                                            <a href='<?php echo esc_url($row['custom_link']); ?>'>
                                                                                                                                                                                             <?php 
                                                                    } 
                                                                    ?>
                                                                    <?php 
                                                                    echo  esc_html(stripslashes_deep($row['title']));

                                                                    ?>
                                            <?php 
                                            if ($add_link_to_title && trim($row['custom_link'])!='') {
                                                    ?>
                        </a>
                                                    <?php 
                                            } 
                                            ?>
            </div>
                                                                    <div style="clear:both"></div>
                                                    <?php if ($show_content) { ?>
                                                                            <div class="scrollercontent">
                                                                                    <?php echo wp_kses(nl2br(stripslashes_deep($row['content'])), wp_kses_allowed_html('post')); ?>
                                                                            </div>
                                                    <?php } ?>       
                                                            </div>
                                                             <div style="clear:both"></div>
                                                    </li>
                                            <?php 
                                    }

                                    ?>
                                    </ul>
                            </div>
                       <?php if ('classic'==$news_style) { ?>  
                                    </marquee>
                       <?php } ?>
                            <?php if ('modern'==$news_style) { ?>
                                    <script type="text/javascript"><?php $intval= esc_html(uniqid('interval_')); ?>var <?php echo esc_html($intval); ?> = setInterval(function() {if(document.readyState === 'complete') {clearInterval(<?php echo esc_html($intval); ?>);jQuery("#news-container_<?php echo esc_html($randomNum); ?>").css('visibility','visible');<?php if ('v2'==$lib) : ?>jQuery('#news-container_<?php echo esc_html($randomNum); ?>').vtickerv2({ speed: <?php echo esc_html($modern_speed); ?>,pause: <?php echo esc_html($modern_scroller_delay); ?>,animating: true,mousePause: true,height:<?php echo esc_html($height); ?>,direction:'<?php echo esc_html($direction); ?>'});  <?php else : ?>   jQuery(function(){jQuery('#news-container_<?php echo esc_html($randomNum); ?>').vTicker({ speed: <?php echo esc_html($modern_speed); ?>,pause: <?php echo esc_html($modern_scroller_delay); ?>,animation: '',mousePause: true,height:<?php echo esc_html($height); ?>,direction:'<?php echo esc_html($direction); ?>'});  });<?php endif; ?>}    }, 100);</script><!-- end print_verticalScroll_func -->
                                    <?php
                            } else { 
                                    ?>

                                     <script type="text/javascript"><?php $intval= esc_html(uniqid('interval_')); ?>var <?php echo esc_html($intval); ?> = setInterval(function() { if(document.readyState === 'complete') { clearInterval(<?php echo esc_html($intval); ?>); jQuery("#news-container_<?php echo esc_html($randomNum); ?>").css('visibility','visible');  }    }, 100); </script>     
                                     
                                    <?php 
                            }
                            ?>
                            <?php
                            $output = ob_get_clean();
                            return $output; 
            }
   }

   if(!class_exists('vns_VerticalScroll')){
            class vns_VerticalScroll extends WP_Widget {


                    public function __construct() {

                            $widget_ops = array('classname' => 'vns_VerticalScroll', 'description' => 'Vertical news scroll');
                            parent::__construct('vns_VerticalScroll', 'Vertical news scroll', $widget_ops);
                    }

                    public function widget( $args, $instance ) {
                            global $wpdb;

                            if (is_array($args)) {

                                    extract($args);
                            }

                            wp_enqueue_style('news-style');
                            wp_enqueue_script('jquery');


                            $title = apply_filters('widget_title', empty($instance['title']) ? 'News Scroll' :$instance['title']);   
                            include_once ABSPATH . WPINC . '/feed.php';
                            if (isset($args['before_widget']) && !empty($args['before_widget'])) {
                                                    echo wp_kses($args['before_widget'], wp_kses_allowed_html('post')); 
                            }
                            echo  wp_kses($before_title, wp_kses_allowed_html('post')) . esc_html($title) . wp_kses($after_title, wp_kses_allowed_html('post'));   
                            $maxitem=empty($instance['maxitem']) ? 5 :intval($instance['maxitem']); 
                            $padding=empty($instance['padding']) ? 5 :intval($instance['padding']); 
                            $add_link_to_title=intval(( !isset($instance['add_link_to_title']) || null==$instance['add_link_to_title'] ) ? 0 :$instance['add_link_to_title']); 
                            $show_content=intval(( !isset($instance['show_content']) || null== $instance['show_content'] ) ? 0 :$instance['show_content']); 
                            $delay=empty($instance['delay']) ? 5 :intval($instance['delay']); 
                            $modern_scroller_delay=empty($instance['modern_scroller_delay']) ? 5000 :intval($instance['modern_scroller_delay']); 
                            $height=empty($instance['height']) ? 200 :intval($instance['height']); 
                            $scrollamt=empty($instance['scrollamount']) ? 1 :intval($instance['scrollamount']); 
                            $modern_speed=empty($instance['modern_speed']) ? 1700 :intval($instance['modern_speed']); 
                            $s_type=empty($instance['s_type']) ? 'classic' :sanitize_text_field($instance['s_type']); 
                            $direction=empty($instance['direction']) ? 'up' :sanitize_text_field($instance['direction']); 
                            $lib=sanitize_sql_orderby(empty($instance['lib_version']) ? 'v1' :$instance['lib_version']); 


                            $randomNum=rand(0, 10000);
                            $news_style='classic';


                            if ('v2'==$lib) {
                                      wp_enqueue_script('newscriptv2');
                            } else {

                                    wp_enqueue_script('newscript');
                            }


                            $rows  = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'scroll_news order by createdon desc limit %d', $maxitem), ARRAY_A);

                            ?>


                            <?php 
                            if ('classic'==$s_type) {
                                            $news_style='classic';  
                            } else if ('modern'==$s_type) {
                                    $news_style='modern';  
                            }
                            ?>
                            <?php if ('classic'==$news_style) { ?>  
                                    <marquee height='<?php echo esc_html($height); ?>' direction='<?php echo esc_html($direction); ?>'  onmouseout="this.start()" onmouseover="this.stop()" scrolldelay="<?php echo esc_html($delay); ?>" scrollamount="<?php echo esc_html($scrollamt); ?>" direction="up" behavior="scroll" >
                            <?php } ?>    
                                            <div id="news-container_<?php echo esc_html($randomNum); ?>" class="news-container" style="visibility: hidden">
                                    <?php if (!$show_content) : ?>
                                             <style>.news-info{display:inline-block;}.news-img{padding-bottom: 20px}</style>
                                    <?php endif; ?>
                                            <ul>
                                    <?php

                                    foreach ($rows as $row) {
                                            ?>
                                                            <li>
                                                                    <div style="padding:<?php echo esc_html($padding); ?>px">
                                                                            <div class="newsscroller_title">
                                                                                <?php 
                                                                                if ($add_link_to_title && trim($row['custom_link'])!='') {
                                                                                        ?>
                                                                                        <a href='<?php echo esc_url($row['custom_link']); ?>'>
                                                                                                                                                                                                         <?php 
                                                                                } 
                                                                                ?>
                                                                                <?php echo  esc_html(stripslashes_deep($row['title'])); ?>
                                                                                                        <?php 
                                                                                                        if ($add_link_to_title && trim($row['custom_link'])!='') {
                                                                                                                ?>
                                                                                 </a>
                                                                                                                <?php 
                                                                                                        } 
                                                                                                        ?>
                                                                                </div>
                                                                            <div style="clear:both"></div>
                                                                                <?php if ($show_content) { ?>
                                                                                    <div class="scrollercontent">
                                                                                            <?php echo wp_kses(nl2br(stripslashes_deep($row['content'])), wp_kses_allowed_html('post')); ?>
                                                                                    </div>
                                                                            <?php } ?>       
                                                                    </div>
                                                                     <div style="clear:both"></div>
                                                            </li>
                                            <?php 
                                    }

                                    ?>
                                            </ul>
                                    </div>
                                    <?php if ('classic'==$news_style) { ?>  
                                    </marquee>
                                    <?php } ?>
                            <?php if ('modern'==$news_style) { ?>
                                    <script type="text/javascript"><?php $intval= uniqid('interval_'); ?> var <?php echo esc_html($intval); ?> = setInterval(function() { if(document.readyState === 'complete') { clearInterval(<?php echo esc_html($intval); ?>); jQuery("#news-container_<?php echo esc_html($randomNum); ?>").css('visibility','visible'); <?php if ('v2'==$lib) : ?> jQuery('#news-container_<?php echo esc_html($randomNum); ?>').vtickerv2({  speed: <?php echo esc_html($modern_speed); ?>, pause: <?php echo esc_html($modern_scroller_delay); ?>, animating: true, mousePause: true, height:<?php echo esc_html($height); ?>, direction:'<?php echo esc_html($direction); ?>' });  <?php else : ?>   jQuery(function(){ jQuery('#news-container_<?php echo esc_html($randomNum); ?>').vTicker({  speed: <?php echo esc_html($modern_speed); ?>, pause: <?php echo esc_html($modern_scroller_delay); ?>, animation: '', mousePause: true, height:<?php echo esc_html($height); ?>, direction:'<?php echo esc_html($direction); ?>' });  }); <?php endif; ?> }  }, 100); </script>
                                    <?php
                            } else { 
                                    ?>

                                     <script type="text/javascript"><?php $intval= esc_html(uniqid('interval_')); ?> var <?php echo esc_html($intval); ?> = setInterval(function() { if(document.readyState === 'complete') { clearInterval(<?php echo esc_html($intval); ?>); jQuery("#news-container_<?php echo esc_html($randomNum); ?>").css('visibility','visible'); }  }, 100); </script>
                                    <?php 
                            }

                            if (isset($args['after_widget']) && !empty($args['after_widget'])) {

                                                            echo wp_kses($args['after_widget'], wp_kses_allowed_html('post')); 
                            }

                    }



                    public function update( $new_instance, $old_instance ) {


                            $instance = $old_instance;
                            $instance['title'] = sanitize_text_field($new_instance['title']);
                            $instance['add_link_to_title'] = intval($new_instance['add_link_to_title']);
                            $instance['maxitem'] = intval($new_instance['maxitem']);
                            $instance['padding'] = intval($new_instance['padding']);
                            $instance['show_content'] = intval($new_instance['show_content']);
                            $instance['delay'] = intval($new_instance['delay']);
                            $instance['scrollamount'] = intval($new_instance['scrollamount']);
                            $instance['height'] = intval($new_instance['height']);
                            $instance['s_type'] = sanitize_text_field($new_instance['s_type']);
                            $instance['modern_scroller_delay'] = sanitize_text_field($new_instance['modern_scroller_delay']);
                            $instance['modern_speed'] = intval($new_instance['modern_speed']);
                            $instance['direction'] = sanitize_text_field($new_instance['direction']);
                            $instance['lib_version'] = sanitize_sql_orderby($new_instance['lib_version']);
                            return $instance;


                    }
                    public function form( $instance ) {

                            //Defaults
                            $instance = wp_parse_args((array) $instance, array('s_type'=>'classic','title' => 'News','maxitem' => 5,'padding' => 5,'show_content' => 1,'delay'=>5,'scrollamount'=>1,'add_link_to_title'=>1,'height'=>200,'modern_scroller_delay'=>5000,'modern_speed'=>1700,'direction'=>'up','lib_version'=>'v1'));
                            $scroller_type=$instance['s_type'];
                            $direction=$instance['direction'];
                            $lib_version=isset($instance['lib_version']) ? $instance['lib_version']:'v1';
                            $randomNum=rand(0, 10000);
                            ?>
                            <?php

                            global $wpdb;

                            ?>
                            <p>
                                    <label for="<?php echo esc_html($this->get_field_id('s_type')); ?>"><b><?php echo __('News Scroller Type:', 'vertical-news-scroller'); ?></b></label><br/>
                                    <input 
                                    <?php 
                                    if ('modern'==$scroller_type) {
                                            ?>
                                            checked="checked" 
                                                                                                                      <?php 
                                    } 
                                    ?>
                                                     type="radio" name="<?php echo esc_html($this->get_field_name('s_type')); ?>" onchange="chnageParam(this);" id="s_type_modern" value="modern"> <?php echo __('Modern', 'vertical-news-scroller'); ?>
                                    <input 
                                    <?php 
                                    if ('classic'==$scroller_type) {
                                            ?>
                                            checked="checked" 
                                                                                                                       <?php 
                                    } 
                                    ?>
                                                     type="radio" name="<?php echo esc_html($this->get_field_name('s_type')); ?>" onchange="chnageParam(this);"  id="s_type_classic" value="classic"> <?php echo __('Classic', 'vertical-news-scroller'); ?>
                            </p>


                            <p>
                                    <label for="<?php echo esc_html($this->get_field_id('title')); ?>"><b><?php echo __('Title:', 'vertical-news-scroller'); ?></b></label>
                                    <input class="widefat" id="<?php echo esc_html($this->get_field_id('title')); ?>"
                                            name="<?php echo esc_html($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_html($instance['title']); ?>" />
                            </p>
                            <p>
                                    <input id="<?php echo esc_html($this->get_field_id('add_link_to_title')); ?>" name="<?php echo esc_html($this->get_field_name('add_link_to_title')); ?>"
                                            type="checkbox" <?php checked($instance['add_link_to_title'], 1); ?> value="1" />
                                    <label for="<?php echo esc_html($this->get_field_id('add_link_to_title')); ?>"><b><?php echo __('Add link to news title:', 'vertical-news-scroller'); ?></b></label>
                            </p>
                            <p><label for="<?php echo esc_html($this->get_field_id('maxitem')); ?>"><b><?php echo __('Max item from news:', 'vertical-news-scroller'); ?></b></label>
                                    <input class="widefat" id="<?php echo esc_html($this->get_field_id('maxitem')); ?>" name="<?php echo esc_html($this->get_field_name('maxitem')); ?>"
                                            type="text" value="<?php echo esc_html($instance['maxitem']); ?>" />
                            </p>

                            <p><label for="<?php echo esc_html($this->get_field_id('height')); ?>"><b><?php echo __('Height of scroller:', 'vertical-news-scroller'); ?></b></label>
                                    <input class="widefat" id="<?php echo esc_html($this->get_field_id('height')); ?>" name="<?php echo esc_html($this->get_field_name('height')); ?>" type="text" value="<?php echo esc_html($instance['height']); ?>" />px
                            </p>

                            <p><label for="<?php echo esc_html($this->get_field_id('padding')); ?>"><b><?php echo __('Padding:', 'vertical-news-scroller'); ?></b></label>
                                    <input class="widefat" id="<?php echo esc_html($this->get_field_id('padding')); ?>" name="<?php echo esc_html($this->get_field_name('padding')); ?>" type="text" value="<?php echo esc_html($instance['padding']); ?>" />px
                            </p>

                            <p>
                                    <input id="<?php echo esc_html($this->get_field_id('show_content')); ?>" name="<?php echo esc_html($this->get_field_name('show_content')); ?>"
                                            type="checkbox" <?php checked($instance['show_content'], 1); ?> value="1" />
                                    <label for="<?php echo esc_html($this->get_field_id('show_content')); ?>"><b><?php echo __('Show news content:', 'vertical-news-scroller'); ?></b></label>
                            </p>

                            <p id='classic_delay_<?php echo esc_html($this->get_field_id('delay')); ?>' 
                                                                                            <?php 
                                                                                            if ('modern'==$scroller_type) {
                                                                                                    ?>
                                    style="display:none" 
                                                                                                                                                            <?php 
                                                                                            }
                                                                                            ?>
                                                                              ><label for="<?php echo esc_html($this->get_field_id('delay')); ?>"><b><?php echo __('Delay:', 'vertical-news-scroller'); ?></b></label>
                                    <input class="widefat" id="<?php echo esc_html($this->get_field_id('delay')); ?>" name="<?php echo esc_html($this->get_field_name('delay')); ?>" type="text" value="<?php echo esc_html($instance['delay']); ?>" /><?php echo __('Micro Sec', 'vertical-news-scroller'); ?>
                            </p>

                            <p id='modern_delay_<?php echo esc_html($this->get_field_id('modern_scroller_delay')); ?>' 
                                                                                       <?php 
                                                                                            if ('classic'==$scroller_type) {
                                                                                                    ?>
                                    style="display:none" 
                                                                                                                                                       <?php 
                                                                                            }
                                                                                            ?>
                                                                      ><label for="<?php echo esc_html($this->get_field_id('delay')); ?>"><b><?php echo __('Delay:', 'vertical-news-scroller'); ?></b></label>
                                    <input class="widefat" id="<?php echo esc_html($this->get_field_id('modern_scroller_delay')); ?>" name="<?php echo esc_html($this->get_field_name('modern_scroller_delay')); ?>" type="text" value="<?php echo esc_html($instance['modern_scroller_delay']); ?>" />
                            </p>

                            <p id='modern_speed_<?php echo esc_html($this->get_field_id('modern_speed')); ?>' 
                                                                                       <?php 
                                                                                            if ('classic'==$scroller_type) {
                                                                                                    ?>
                                    style="display:none" 
                                                                                                                                                       <?php 
                                                                                            }
                                                                                            ?>
                                                                      ><label for="<?php echo esc_html($this->get_field_id('modern_speed')); ?>"><b><?php echo __('Speed:', 'vertical-news-scroller'); ?></b></label>
                                    <input class="widefat" id="<?php echo esc_html($this->get_field_id('modern_speed')); ?>" name="<?php echo esc_html($this->get_field_name('modern_speed')); ?>" type="text" value="<?php echo esc_html($instance['modern_speed']); ?>" />
                            </p>
                            <p id='classic_scrollamount_<?php echo esc_html($this->get_field_id('scrollamount')); ?>' 
                                                                                                       <?php 
                                                                                                            if ('modern'==$scroller_type) {
                                                                                                                    ?>
                                    style="display:none" 
                                                                                                                                                                                       <?php 
                                                                                                            }
                                                                                                            ?>
                                                                                     ><label for="<?php echo esc_html($this->get_field_id('scrollamount')); ?>"><b><?php echo __('Scroll Amount:', 'vertical-news-scroller'); ?></b></label>
                                    <input class="widefat" id="<?php echo esc_html($this->get_field_id('scrollamount')); ?>" name="<?php echo esc_html($this->get_field_name('scrollamount')); ?>" type="text" value="<?php echo esc_html($instance['scrollamount']); ?>" /><?php echo esc_html(__('(Ie 1,2,3)', 'vertical-news-scroller')); ?>
                            </p>
                             <p>
                                    <label for="<?php echo esc_html($this->get_field_id('direction')); ?>"><b><?php echo __('Direction:', 'vertical-news-scroller'); ?></b></label><br/>
                                    <input 
                                    <?php 
                                    if ('up'==$direction) {
                                            ?>
                                            checked="checked" 
                                                                                                      <?php 
                                    } 
                                    ?>
                                                     type="radio" name="<?php echo esc_html($this->get_field_name('direction')); ?>"  id="direction_up" value="up"> <?php echo __('Up', 'vertical-news-scroller'); ?>
                                    <input 
                                    <?php 
                                    if ('down'==$direction) {
                                            ?>
                                            checked="checked" 
                                                                                                            <?php 
                                    } 
                                    ?>
                                                     type="radio" name="<?php echo esc_html($this->get_field_name('direction')); ?>"  id="direction_down" value="down"> <?php echo __('Down', 'vertical-news-scroller'); ?>
                            </p>

                            <script>
                                    function chnageParam(newstype){

                                            if(newstype.value=='classic'){

                                                    jQuery("[id$=-delay]").show();      
                                                    jQuery("[id$=-scrollamount]").show();      

                                                    jQuery("[id$=modern_scroller_delay]").hide();      
                                                    jQuery("[id$=modern_speed]").hide();   
                                                    jQuery("[id$=lib_version]").hide();     



                                            }
                                            else{

                                                    jQuery("[id$=modern_scroller_delay]").show();      
                                                    jQuery("[id$=modern_speed]").show();      
                                                    jQuery("[id$=-delay]").hide();      
                                                    jQuery("[id$=-scrollamount]").hide();    
                                                    jQuery("[id$=lib_version]").show();      


                                            } 
                                    }
                            </script>
                            <p id="lib_version">
                                    <label for="<?php echo esc_html($this->get_field_id('lib_version')); ?>"><b><?php echo __('JQuery Scroller Library:', 'vertical-news-scroller'); ?></b></label><br/>
                                    <select id="<?php echo esc_html($this->get_field_id('lib_version')); ?>" name="<?php echo $this->get_field_name('lib_version'); ?>">
                                            <option 
                                            <?php 
                                            if ('v1'==$lib_version) :
                                                    ?>
                                                     selected="" 
                                                                                                                     <?php 
                                       endif;
                                            ?>
                                                             value="v1"><?php echo __('V1', 'vertical-news-scroller'); ?></option>
                                            <option 
                                            <?php 
                                            if ('v2'==$lib_version) :
                                                    ?>
                                                     selected="" 
                                                                                                                     <?php 
                                       endif;
                                            ?>
                                                             value="v2"><?php echo __('V2', 'vertical-news-scroller'); ?></option>

                                    </select>
                            </p>
                            <?php
                    } // function form
            } // widget class
   }

 if(!function_exists('vnsp_remove_extra_p_tags')){  
            function vnsp_remove_extra_p_tags( $content) {

                    if (false!==strpos($content, 'print_verticalScroll_func')) {


                            $pattern = '/<!-- print_verticalScroll_func -->(.*)<!-- end print_verticalScroll_func -->/Uis'; 
                            $content = preg_replace_callback(
                                    $pattern, function ( $matches) {


                                                    $altered = str_replace('<p>', '', $matches[1]);
                                                    $altered = str_replace('</p>', '', $altered);

                                                    $altered=str_replace('&#038;', '&', $altered);
                                                    $altered=str_replace('&#8221;', '"', $altered);


                                                    return @str_replace($matches[1], $altered, $matches[0]);
                                    }, $content
                            );



                    }

                    $content = str_replace('<p><!-- print_verticalScroll_func -->', '<!-- print_verticalScroll_func -->', $content);
                    $content = str_replace('<!-- end print_verticalScroll_func --></p>', '<!-- end print_verticalScroll_func -->', $content);


                    return $content;
            }
    }

    

function i13_vn_render_block_defaults($block_content, $block) { 

    $block_content=vnsp_remove_extra_p_tags($block_content);
    return $block_content; 

}


add_filter( 'render_block', 'i13_vn_render_block_defaults', 10, 2 );

