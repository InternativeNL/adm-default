<?php

// Check if XMLRPC is blocked //

/* When the check has never executed the Wordpress
   option called adm_block_xmlrpc does not exist */

add_action('admin_init',function(){
    
    // DISABLE ACCESS TO XMLRPC.PHP //
    $adm_option = get_option('adm_block_xmlrpc', 0); // Returns 0 is it doesnt excist or 1 when script has already run
    if ($adm_option == 0){
        
        $lines = array();
        $lines[] = "<Files xmlrpc.php>";
        $lines[] = "Order Deny,Allow";
        $lines[] = "Deny from all";
        $lines[] = "</Files>";    

        insert_with_markers(get_home_path() . ".htaccess", "ADMIUM-BLOCK-XMLRPC", $lines);

        update_option('adm_block_xmlrpc', 1);
        
    }

});