<?php
/**
 * DOAJ XML Export
 *
 * @author Claudio Tubertini
 * @license http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link http://www.open-word.com
 *
 * @wordpress
 * Plugin Name: DOAJ XML Export
 * Plugin URI: http://www.open-word.com
 * Description: Produce an XML file redirected to a simple url
 * Author: Claudio Tubertini
 * Version: 0.2.1
 * Author URI: 
 */
//$string = home_url( '/' );
//$xml = new SimpleXMLElement($string);


//add_action( 'init', 'XML_export_final');
add_action('template_redirect','XML_export' );

function XML_export() {
    if ( !preg_match( '/\?doaj.xml$/', $_SERVER['REQUEST_URI'] ) ) {
     return;
   }
  global $wpdb;
  $posts = $wpdb->get_results( "SELECT ID, post_title, post_modified_gmt
    FROM $wpdb->posts
    WHERE post_status = 'publish'
    AND post_password = ''
    AND NOT post_type = 'wpcf7_contact_form'
    ORDER BY post_type DESC, post_modified DESC
    LIMIT 50" );

  //header('Content-Disposition: attachment; filename="downloaded.xml"');
  header( "HTTP/1.1 200 OK" );
  header( 'X-Robots-Tag: noindex, follow', true );
  header( 'Content-Type: application/xml; charset=utf-8' );
  $xml = '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . '"?>' . "\n";
  $xml .=  '<!-- generator="' . htmlentities(home_url( '/' ))  . '" -->' . "\n";
 
  $xml .= '<records>';
  // foreach ($posts as $post ) {
  //   $xml .= '<phrase>'. $post->ID. '</phrase>';
  // }
  
  foreach ( $posts as $post ) {
    //$clueb_stored_meta = get_post_meta( $post->ID );
    if ( ! empty( $post->post_title ) ) {
      $xml .=  '<record>';
      $xml .= '<language>eng</language>';
      $xml .= '<publisher>CLUEB</publisher>';
    $xml .= '<journalTitle>'.get_post_meta( $post->ID, '_citation_journal_title', true ).'</journalTitle>';
    $xml .= '<issn>'.'2283-7116'.'</issn>';
    $xml .= '<publicationDate>'.get_post_meta( $post->ID, '_citation_date', true ).'</publicationDate>';
    $xml .= '<volume>'.get_post_meta( $post->ID, '_citation_volume', true ).'</volume>';
    $xml .= '<issue>'.get_post_meta( $post->ID, '_citation_issue', true ).'</issue>';
    $xml .= '<startPage>'.get_post_meta( $post->ID, '_citation_firstpage', true ).'</startPage>';
    $xml .= '<endPage>'.get_post_meta( $post->ID, '_citation_lastpage', true ).'</endPage>';
    $xml .= '<doi>'.get_post_meta( $post->ID, '_citation_doi', true ).'</doi>';
    $xml .= '<publisherRecordId></publisherRecordId>';
    $xml .= '<documentType>article</documentType>';
    $xml .=  '<title language="eng">'.get_post_meta( $post->ID, '_citation_title', true ).'</title>';
    
    $xml .= '<authors>';
    if ( null !==  get_post_meta(  $post->ID, '_citation_authors', true )  ){
    $authors = explode(";", get_post_meta(  $post->ID, '_citation_authors', true ));
      }
    
    $final_authors = array();

    foreach ($authors as $author){
     $ret = explode(',', $author);
     if (isset($ret[1])){
     $final_authors[$ret[0]]= $ret[1];
     }
     else {
      $final_authors[$ret[0]]= '';
     }
     //$final_authors[$ret[0]]= $ret[1];
    }
  
      $xmlau = '';
      $count = 1; // counts the number of authors and keep the same number as a reference for the institution of the author
      $xmlcount = '';//keep reference of institutions
      foreach ($final_authors as $k => $v) {
        //preg_match('/([a-zA-Z]+)(\d+)?/', $author, $matches);
        $xmlau .= '<author>';
        //$au_name = function ($arg = '$matches[0]') {if ($arg): return $arg; else: return ''; endif;};

        $xmlau .=    '<name>'.$k. '</name>';
        $xmlau .=   '<email></email>';

        $xmlau .=   '<affiliationId>'. $count .'</affiliationId>';
       
       $xmlau .=  '</author>';
       
      
      $xmlcount .= '<affiliationName affiliationId="'.$count.'">'. $v .'</affiliationName>';
      $count++;
    }

    $xml .= $xmlau .'</authors>';
    $xml .= '<affiliationsList>'.$xmlcount.'</affiliationsList>';
    $xml .=  '<abstract language="eng">'
      .get_post_meta( $post->ID, '_citation_abstract', true ).
    '</abstract>';
    $xml .= '<fullTextUrl format="html">'.htmlentities(get_permalink( $post->ID )).'</fullTextUrl>';
    $xml .= '<keywords language="eng">';
    $keywords = explode(",", get_post_meta(  $post->ID, '_citation_keywords', true ));
    $xmlky = '';
    foreach ($keywords as $keyword){
      $xmlky .= '<keyword>'.$keyword.'</keyword>';
      }
    $xml .= $xmlky.'</keywords>';
    
  $xml .= '</record>';
     
      }
   } 
  

  $xml .= '</records>';
  echo ( "$xml" );
  
    
    
  exit();

  }

// function XML_export_final(){
//   add_action( 'template_redirect', 'XML_export' );
// }


?>