<?php
    require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

    $id = isset( $_SERVER[ 'REQUEST_URI' ] ) ? basename( $_SERVER[ 'REQUEST_URI' ] ) : false;

    $settings = get_option('widget_pwg_glopart');
    $cfg = current($settings);
    $glopart_id = $cfg['glopart_id'];

    $body = array(
        'adunit_id' => $glopart_id,
        'click_id' => $id
    );
    $args = array(
        'timeout' => 15,
        'sslverify' => false, 
        'body' => $body
    );

    $response = wp_remote_post( 'https://wpmoney.ru/engine/engine.php', $args );
    $body = wp_remote_retrieve_body( $response );

    if ( $body !== false ) {
        echo $body;
    } else {
    // ставим статус
    $wp_query->set_404();
        status_header(404);
    // выводим файл 404.php
    include( get_query_template( '404' ) );
    exit;
}
?>