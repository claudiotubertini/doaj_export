<?php
/*
*Plugin Name: Citation in meta tag
*Plugin URI:
*Description: 'A class to upload metadata as HTML tags and in the meanwhile add a button to upload a citation to your Mendeley Library. Example of meta tag produced
*<meta name="citation_title" content="Global and local 
*fMRI signals driven by neurons defined optogenetically by type and wiring.">
*<meta name="citation_authors" content="Lee, Jin Hyung; Durand, Remy; Gradinaru, Viviana; Zhang, Feng; Goshen, Inbal; Kim, Dae-Shik; Fenno, Lief E; Ramakrishnan, Charu; Deisseroth, Karl">
*<meta name="citation_journal_title" content="Nature">
*<meta name="citation_publisher" content="Nature Publishing Group">
*<meta name="citation_issue" content="7299">
*<meta name="citation_volume" content="465">
*<meta name="citation_doi" content="10.1038/nature09108">
*<meta name="citation_firstpage" content="788">
*<meta name="citation_lastpage" content="792">
*<meta name="citation_date" content="2010">'
* The way in which the authors are entered is the same as in the associated plugin "Metadata Class", that is authors name and his institution are separeted by a comma
* each author is separated from others by a semicolon. Important! Use commas and semicolons only as indicated.
*Version: 1.0
*Author: Claudio Tubertini
*Author URI: http://open-word.com
*License: GPLv2
*/



class Clueb_Mend {

function __construct() {
	add_action( 'wp_head', array($this,'meta_page_header_output' ));
	//add_action('add_meta_boxes', 'clueb_add_custom_box');
	add_action( 'init', array($this, 'adding_mendeley_register_shortcode' ));
}



function meta_page_header_output () { 
	//$postid = get_the_ID();
	$final_authors = array ();
	$clueb_stored_meta = get_post_meta( get_the_ID()); // retrieve all metadata inserted in meta box
	if ( isset ( $clueb_stored_meta['_citation_authors'] ) ){
	$authors = explode(";", $clueb_stored_meta['_citation_authors'][0]);
		foreach ($authors as $author){
		 $ret = explode(',', $author);
		 if (isset($ret[1])){
		 $final_authors[$ret[0]]= $ret[1];
		 }
		 else {
		 	$final_authors[$ret[0]]= '';
		 }
		} //creates an array with authors' name => authors' institution
	}
	if ( isset ( $clueb_stored_meta['_citation_title'] ) ) echo ("<meta name='citation_title' content='". $clueb_stored_meta['_citation_title'][0]. "'/>");
	
	if ( isset ( $clueb_stored_meta['_citation_authors'] ) )
	echo("<meta name='citation_authors' content='" . implode(',', array_keys($final_authors)). "'/>");
	if ( isset ( $clueb_stored_meta['_citation_journal_title'] ) )
	echo("<meta name='citation_journal_title' content='" . $clueb_stored_meta['_citation_journal_title'][0] . "'/>");
	if ( isset ( $clueb_stored_meta['_citation_publisher'] ) )
	echo("<meta name='citation_publisher' content='" . $clueb_stored_meta['_citation_publisher'][0] . "'/>");
	if ( isset ( $clueb_stored_meta['_citation_issue'] ) )
	echo("<meta name='citation_issue' content='" . $clueb_stored_meta['_citation_issue'][0] . "'/>");
	if ( isset ( $clueb_stored_meta['_citation_volume'] ) )
	echo("<meta name='citation_volume' content='" . $clueb_stored_meta['_citation_volume'][0] . "'/>");
	if ( isset ( $clueb_stored_meta['_citation_doi'] ) )
	echo("<meta name='citation_doi' content='" . $clueb_stored_meta['_citation_doi'][0] . "'/>");
	if ( isset ( $clueb_stored_meta['_citation_firstpage'] ) )
	echo("<meta name='citation_firstpage' content='" . $clueb_stored_meta['_citation_firstpage'][0] . "'/>");
	if ( isset ( $clueb_stored_meta['_citation_lastpage'] ) )
	echo("<meta name='citation_lastpage' content='" . $clueb_stored_meta['_citation_lastpage'][0] . "'/>");
	if ( isset ( $clueb_stored_meta['_citation_date'] ) )
	echo("<meta name='citation_date' content='" . $clueb_stored_meta['_citation_date'][0] . "'/>");
	if ( isset ( $clueb_stored_meta['_citation_abstract_html_url'] ) )
	echo("<meta name='citation_abstract_html_url' content='" . $clueb_stored_meta['_citation_abstract_html_url'][0] . "'/>");
	if ( isset ( $clueb_stored_meta['_citation_abstract_pdf_url'] ) )
	echo("<meta name='citation_abstract_pdf_url' content='" . $clueb_stored_meta['_citation_abstract_pdf_url'][0] . "'/>");
}
function adding_mendeley_button( $atts ) {
	$output = '<a title="Add this article to your Mendeley library" 
	href="http://www.mendeley.com/import/?url='.get_permalink().'" target="_blank"><img src="http://www.mendeley.com/graphics/mendeley.png"/></a>';
	return $output;
}
function adding_mendeley_register_shortcode() {
    add_shortcode( 'mend', array($this,'adding_mendeley_button'));
}
//citation bibtex
function citation_page_output () { 
	$postid = get_the_ID();
	$bibfile = '<html><title>Bibtex Citation</title><body>';
	$bibfile = $bibfile .'<p>@article{';
	$bibfile = $bibfile . get_the_author_meta('last_name').get_post_meta( $postid, '_citation_date', true ).'\n';
	$bibfile = $bibfile . 'AUTHOR="'.get_post_meta( $postid, '_citation_authors', true ).'",\n';
	$bibfile = $bibfile . 'TITLE="'.get_post_meta( $postid, '_citation_title', true ).'",\n';
	$bibfile = $bibfile . 'JOURNAL="'.get_post_meta( $postid, '_citation_journal_title', true ).'",\n';
	$bibfile = $bibfile . 'VOLUME="'.get_post_meta( $postid, '_citation_volume', true ).'",\n';
	$bibfile = $bibfile . 'ISSUE="'.get_post_meta( $postid, '_citation_issue', true ) .'",\n';
	$bibfile = $bibfile . 'YEAR="'.get_post_meta( $postid, '_citation_date', true ) .'",\n';
	$bibfile = $bibfile .'</p></body></html>';
	return $bibfile;
	}

}


$my_metadata = new Clueb_Mend ();
?>