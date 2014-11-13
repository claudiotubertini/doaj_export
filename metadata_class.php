<?php
/*
*Plugin Name: Bibliographic Metabox
*Plugin URI:
*Description: Useful to insert metadata to posts
*Version: 1.2
*Author: Claudio Tubertini
*Author URI: http://open-word.com
*License: GPLv2
*/
if ( is_admin()) {  //do nothing for front end requests
    add_action('load-post-new.php', 'call_Clueb_pblsh_bib');
    add_action('load-post.php', 'call_Clueb_pblsh_bib');

}
function call_Clueb_pblsh_bib() {
    new Clueb_pblsh_bib();
}
 
class Clueb_pblsh_bib {
	// const  CMETA = array ("_citation_title", "_citation_authors", "_citation_journal_title", "_citation_publisher", "_citation_issue",
	// 	"_citation_volume", "_citation_doi", "_citation_firstpage", "_citation_lastpage", "_citation_date", "_citation_abstract_html_url", "_citation_abstract_pdf_url"  );
      public $cmeta;
      public function __construct() {
        $this->cmeta = array ("_citation_keywords", "_citation_abstract", "_citation_title", "_citation_authors", "_citation_journal_title", "_citation_publisher", "_citation_issue",
      "_citation_volume", "_citation_doi", "_citation_firstpage", "_citation_lastpage", "_citation_date", "_citation_abstract_html_url", "_citation_abstract_pdf_url"  );
  
        add_action('add_meta_boxes', array ( $this, 'clueb_pblsh_add_custom_box'));
        add_action('save_post', array ( $this, 'clueb_save_meta'));
        add_action( 'admin_print_styles', array ($this,'clueb_pblsh_admin_styles' ));
    }
 
    public function clueb_pblsh_add_custom_box( $post_type ) {
        $post_types = array('post', 'page', 'article');
        if ( in_array( $post_type, $post_types )) {
            add_meta_box(
                'clueb_metabox_id',            // Unique ID
                'Bibliographic Metadata',      // Box title
                array( $this, 'render_form'), // Content callback
                $post_type,
                'side', 
                'high'
            );
        }
    }
 
    public function clueb_save_meta( $post_id ) {
        // if ( array_key_exists('myplugin_field', $_POST )) {
        //     update_post_meta( $post_id, '_my_meta_value_key', $_POST['myplugin_field']);
        // }
        $is_autosave = wp_is_post_autosave( $post_id );
    	$is_revision = wp_is_post_revision( $post_id );
    	$is_valid_nonce = ( isset( $_POST[ 'clueb_nonce' ] ) && wp_verify_nonce( $_POST[ 'clueb_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 		// if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
 		// 	return ;
 		// }
	    // Exits script depending on save status
	    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
	        return;
	    }
 
	    // Checks for input and sanitizes/saves if needed
	    foreach ($this->cmeta as $value){
    
	    if( isset( $_POST[ $value ] ) ) {
	        update_post_meta( $post_id, $value, sanitize_text_field( $_POST[ $value ] ) );
	    }
		}
    }
 
    public function render_form( $post ) {
  
        if ( function_exists('wp_nonce_field') )  wp_nonce_field( basename( __FILE__ ), 'clueb_nonce' );
    	$clueb_stored_meta = get_post_meta( $post->ID );
    	?>
 		<div id="clueb_pblsh_meta">
    	<p>
        <label for="_citation_title" class="clueb_pblsh-content">Title</label>
        <input  type="text" name="_citation_title" id="_citation_title" value="<?php if ( isset ( $clueb_stored_meta['_citation_title'] ) ) echo $clueb_stored_meta['_citation_title'][0]; ?>" />
    	</p><p>
    	<label for="_citation_authors" class="clueb_pblsh-content">Authors</label>
        <input  type="text" name="_citation_authors" id="_citation_authors" value="<?php if ( isset ( $clueb_stored_meta['_citation_authors'] ) ) echo $clueb_stored_meta['_citation_authors'][0]; ?>" />
    	</p><p>
    	<label for="_citation_journal_title" class="clueb_pblsh-content">Journal</label>
        <input  type="text" name="_citation_journal_title" id="_citation_journal_title" value="<?php if ( isset ( $clueb_stored_meta['_citation_journal_title'] ) ) echo $clueb_stored_meta['_citation_journal_title'][0]; ?>" />
    	</p><p>
    	<label for="_citation_publisher" class="clueb_pblsh-content">Publisher</label>
        <input type="text" name="_citation_publisher" id="_citation_publisher" value="<?php if ( isset ( $clueb_stored_meta['_citation_publisher'] ) ) echo $clueb_stored_meta['_citation_publisher'][0]; ?>" />
    	</p><p>
    	<label for="_citation_issue" class="clueb_pblsh-content">Issue</label>
        <input type="text" name="_citation_issue" id="_citation_issue" value="<?php if ( isset ( $clueb_stored_meta['_citation_issue'] ) ) echo $clueb_stored_meta['_citation_issue'][0]; ?>" />
    	</p><p>
    	<label for="_citation_volume" class="clueb_pblsh-content">Volume</label>
        <input type="text" name="_citation_volume" id="_citation_volume" value="<?php if ( isset ( $clueb_stored_meta['_citation_volume'] ) ) echo $clueb_stored_meta['_citation_volume'][0]; ?>" />
    	</p><p>
    	<label for="_citation_doi" class="clueb_pblsh-content">DOI</label>
        <input type="text" name="_citation_doi" id="_citation_doi" value="<?php if ( isset ( $clueb_stored_meta['_citation_doi'] ) ) echo $clueb_stored_meta['_citation_doi'][0]; ?>" />
    	</p><p>
    	<label for="_citation_firstpage" class="clueb_pblsh-content">First_page</label>
        <input type="text" name="_citation_firstpage" id="_citation_firstpage" value="<?php if ( isset ( $clueb_stored_meta['_citation_firstpage'] ) ) echo $clueb_stored_meta['_citation_firstpage'][0]; ?>" />
    	</p><p>
    	<label for="_citation_lastpage" class="clueb_pblsh-content">Last_page</label>
        <input type="text" name="_citation_lastpage" id="_citation_lastpage" value="<?php if ( isset ( $clueb_stored_meta['_citation_lastpage'] ) ) echo $clueb_stored_meta['_citation_lastpage'][0]; ?>" />
    	</p><p>
    	<label for="_citation_date" class="clueb_pblsh-content">Date</label>
        <input type="text" name="_citation_date" id="_citation_date" value="<?php if ( isset ( $clueb_stored_meta['_citation_date'] ) ) echo $clueb_stored_meta['_citation_date'][0]; ?>" />
    	</p>
        <p>
        <label for="_citation_abstract" class="clueb_pblsh-content">Abstract</label>
        <input type="text" name="_citation_abstract" id="_citation_abstract" value="<?php if ( isset ( $clueb_stored_meta['_citation_abstract'] ) ) echo $clueb_stored_meta['_citation_abstract'][0]; ?>" />
        </p>
        <p>
        <label for="_citation_keywords" class="clueb_pblsh-content">Keywords</label>
        <input type="text" name="_citation_keywords" id="_citation_keywords" value="<?php if ( isset ( $clueb_stored_meta['_citation_keywords'] ) ) echo $clueb_stored_meta['_citation_keywords'][0]; ?>" />
        </p><p>
    	<label for="_citation_abstract_html_url" class="clueb_pblsh-content">HTML_Abstract_URL</label>
        <input type="text" name="_citation_abstract_html_url" id="_citation_abstract_html_url" value="<?php if ( isset ( $clueb_stored_meta['_citation_abstract_html_url'] ) ) echo $clueb_stored_meta['_citation_abstract_html_url'][0]; ?>" />
    	</p><p>
    	<label for="_citation_abstract_pdf_url" class="clueb_pblsh-content">PDF_Abstract_URL</label>
        <input type="text" name="_citation_abstract_pdf_url" id="_citation_abstract_pdf_url" value="<?php if ( isset ( $clueb_stored_meta['_citation_abstract_pdf_url'] ) ) echo $clueb_stored_meta['_citation_abstract_pdf_url'][0]; ?>" />
    	</p>
    	</div>
    	<?php
 
	}

	function clueb_pblsh_admin_styles(){
    global $typenow;
    if( $typenow == 'post' ) {
        wp_enqueue_style( 'clueb_pblsh_meta_box_styles', plugin_dir_url( __FILE__ ) . 'meta-box-styles.css' );
    }
}


}



?>