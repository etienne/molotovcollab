<?php
/**
 * @package WordPress
 * @subpackage P2
 */

// Process file upload
// Adapted from http://wordpress.org/support/topic/using-media_handle_upload-from-the-front-end-and-attaching-media-to-a-post
 
if (wp_verify_nonce( $_POST['client-file-upload'], 'client-file-upload') && current_user_can( 'publish_posts', $post->ID )) {
  if('POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_FILES )) {
  	require_once(ABSPATH . 'wp-admin/includes/admin.php');
  	$attachment_id = media_handle_upload('async-upload', $_POST['postID']);
  	unset($_FILES);
  	
    // Post comment
		$comment_content = 'Attachmend ID: '. $attachment_id;
		$comment_post_ID = $post->ID;
		$user = wp_get_current_user();
		$comment_author       = $user->display_name;
		$comment_author_email = $user->user_email;
		$comment_author_url   = $user->user_url;
		$user_ID       			  = $user->ID;
		$comment_type = '';

		if ( get_option( 'require_name_email' ) && !$user->ID )
			if ( strlen( $comment_author_email ) < 6 || '' == $comment_author ) {
				die( '<p>'.__( 'Error: please fill the required fields (name, email).', 'p2' ).'</p>' );
			} elseif ( !is_email( $comment_author_email ) ) {
			    die( '<p>'.__( 'Error: please enter a valid email address.', 'p2' ).'</p>' );
			}

		if ( '' == $comment_content )
		    die( '<p>'.__( 'Error: Please type a comment.', 'p2' ).'</p>' );

		$comment_parent = 0;
		$commentdata = compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID' );

		$comment_id = wp_new_comment( $commentdata );
		add_comment_meta($comment_id, 'attachment_id', $attachment_id, true);
  }
}

// Post classes
$post_classes = array(get_the_author_meta( 'ID' ));
if ($status = get_post_meta($post->ID, 'status', true)) {
  $post_classes[] = $status;
}

?>
<li id="prologue-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
			<h4>
			<span class="meta">
			  <span class="client">
  				<?php if ( !is_page() ) : ?>
  				  <?php if ($post->post_type == 'discussions'): ?>
  				    Discussion interne
				    <?php else: ?>
    				  <?php foreach(get_the_category() as $category): ?>
    				    <a href="<?php echo get_category_link($category->term_id) ?>"><?php echo $category->name; ?></a><?php break; ?>
              <?php endforeach; ?>
				    <?php endif; ?>
  				<?php endif; ?>
			  </span>
				<span class="actions">
					<?php if ( ! is_singular() ) : ?>
					<?php else : ?>
						<?php if ( comments_open() && ! post_password_required() ) :
              // echo post_reply_link( array( 'before' => '', 'after' => '',  'reply_text' => __( 'Reply', 'p2' ), 'add_below' => 'prologue' ), get_the_id() ); ?>
						<?php endif; ?>
					<?php endif;?>
					<?php if ( current_user_can( 'edit_post', get_the_id() ) ) : ?>
						<a href="<?php echo ( get_edit_post_link( get_the_id() ) ); ?>" class="edit-post-link" rel="<?php the_ID(); ?>"><?php _e( 'Edit', 'p2' ); ?></a>
					<?php endif; ?>
					
					<?php do_action( 'p2_action_links' ); ?>
				</span>
			<?php if ( !is_page() ) : ?>
				<span class="tags">
					<?php tags_with_count( '', __( '<br />Tags:' , 'p2' ) .' ', ', ', ' &nbsp;' ); ?>&nbsp;
				</span>
			<?php endif; ?>
			</span>
		</h4>


	<div class="postcontent<?php if ( current_user_can( 'edit_post', get_the_id() ) ) : ?> editarea<?php endif ?>" id="content-<?php the_ID(); ?>">

		<?php p2_title(); ?>
		<?php if ($post->post_type == 'post'): ?>
      <div class="status_badge pending">En attente d’approbation</div>
      <div class="status_badge archived">Archivé</div>
		<?php endif ?>
  		<?php the_content( __( '(More ...)' , 'p2' ) ); ?>
    
	</div>

	<?php if ( ! post_password_required() ) : ?>
		<div class="discussion" style="display: none">
			<p>
				<a href="#" class="show-comments"><?php _e( 'Toggle Comments', 'p2' ); ?></a>
				<?php p2_discussion_links(); ?>
			</p>
		</div>
	<?php endif; ?>
	<?php wp_link_pages( array( 'before' => '<p class="page-nav">' . __( 'Pages:', 'p2' ) ) ); ?>

	<div class="bottom-of-entry">&nbsp;</div>

	<?php if ( ! p2_is_ajax_request() ) : ?>
	  <?php if (is_singular()): ?>
	    <ul class="tabs">
	      <li><a class="selected" href="#comments-wrapper">Discussion</a></li>
	      <li><a href="#files-wrapper">Fichiers</a></li>
      </ul>
	  <?php endif ?>
	  <?php if (is_singular()): ?>
  	  <div id="comments-wrapper" class="tab-panel">
	  <?php endif ?>
  		<?php comments_template(); ?>
  		<?php $pc = 0; ?>
  		<?php if ( ! post_password_required() ) : ?>
  			<?php $pc++; ?>
  			<div class="respond-wrap"<?php if ( ! is_singular() ): ?> style="display: none; "<?php endif; ?>>
  				<?php
  					$p2_comment_args = array(
  						'title_reply' => __( 'Reply', 'p2' ),
  						'comment_field' => '<div class="form"><textarea id="comment" class="expand50-100" name="comment" cols="45" rows="3"></textarea></div> <label class="post-error" for="comment" id="commenttext_error"></label>',
  						'comment_notes_before' => '<p class="comment-notes">' . ( get_option( 'require_name_email' ) ? sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' ) : '' ) . '</p>',
  						'comment_notes_after' => sprintf(
  							'<span class="progress"><img src="%1$s" alt="%2$s" title="%2$s" /></span>',
  							str_replace( WP_CONTENT_DIR, content_url(), locate_template( array( "i/indicator.gif" ) ) ),
  							esc_attr( 'Loading...', 'p2' )
  						),
  						'label_submit' => __( 'Reply', 'p2' ),
  						'id_submit' => 'comment-submit',
  					);
  					comment_form( $p2_comment_args );
  				?>
    	  </div>
  		<?php endif; ?>
	  <?php if (is_singular()): ?>
	  </div>
  	  <div id="files-wrapper" class="tab-panel">
  	    <?php
        $args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $post->ID ); 
        $attachments = get_posts($args);
        if ($attachments): ?>
        <ul class="attachments commentlist">
        <?php foreach ( $attachments as $attachment ):
          $thumbnail = wp_get_attachment_thumb_url($attachment->ID); ?>
          <li>
            <div class="thumbnail">
              <a href="<?php echo wp_get_attachment_url($attachment->ID) ?>">
                <?php if ($thumbnail == false): ?>
              		<img src="<?php echo get_icon_path_from_mime_type($attachment->post_mime_type) ?>" alt="File icon" />
                <?php else: ?>
                  <img class="thumbnail" src="<?php echo $thumbnail ?>" alt="Image thumbnail" />
                <?php endif ?>
              </a>
            </div>
            <div class="details">
          		<div class="title">
          		  <a href="<?php echo wp_get_attachment_url($attachment->ID) ?>">
                  <?php echo apply_filters( 'the_title' , $attachment->post_title ); ?>
                </a>
              </div>
          		<div class="type"><?php echo get_human_type_from_mime_type($attachment->post_mime_type) ?></div>
            </div>
          </li>
    		<?php endforeach; ?>
    		</ul>
  		  <?php endif; ?>
  		  <div id="file-upload-form">
          <h3 class="title">Ajouter un fichier</h3>
    		  <form id="file-form" name="file-form" method="POST" action="" enctype="multipart/form-data" >
        		<input type="file" id="async-upload" name="async-upload" />
        		<input type="hidden" name="postID" value="<?php echo $post->ID; ?>" />
        		<?php wp_nonce_field('client-file-upload', 'client-file-upload'); ?>
        		<input type="submit" value="Ajouter" id="submit" name="submit" />
        	</form>
      	</div>
  	  </div>
	  <?php endif; ?>
	<?php endif; ?>
</li>