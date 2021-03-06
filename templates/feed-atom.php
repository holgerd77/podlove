<?php
/**
 * Atom Feed Template for displaying Atom Posts feed.
 *
 * @package WordPress
 */

header('Content-Type: ' . feed_content_type('atom') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>
<feed
  xmlns="http://www.w3.org/2005/Atom"
  xmlns:thr="http://purl.org/syndication/thread/1.0"
  xml:lang="<?php echo get_option('rss_language'); ?>"
  <?php do_action('atom_ns'); ?>
 >
	<title type="text"><?php bloginfo_rss('name'); wp_title_rss(); ?></title>
	<subtitle type="text"><?php echo apply_filters( 'podlove_atom_feed_subtitle', get_bloginfo_rss("description") ) ?></subtitle>

	<updated><?php echo mysql2date('Y-m-d\TH:i:s\Z', get_lastpostmodified('GMT'), false); ?></updated>

	<link rel="alternate" type="text/html" href="<?php bloginfo_rss('url') ?>" />
	<id><?php echo get_bloginfo( 'atom_url' ); ?></id>
	<?php do_action( 'atom_head' ); ?>
	<?php while (have_posts()) : the_post(); ?>
	<entry>
		<author>
			<name><?php the_author() ?></name>
			<?php $author_url = get_the_author_meta('url'); if ( !empty($author_url) ) : ?>
			<uri><?php the_author_meta('url')?></uri>
			<?php endif;
			do_action('atom_author'); ?>
		</author>
		<title type="<?php echo apply_filters( 'podlove_feed_title_type', 'text' ); ?>"><?php the_title_rss() ?></title>
		<link rel="alternate" type="text/html" href="<?php the_permalink_rss() ?>" />
		<id><?php the_guid() ; ?></id>
		<updated><?php echo get_post_modified_time('Y-m-d\TH:i:s\Z', true); ?></updated>
		<published><?php echo get_post_time('Y-m-d\TH:i:s\Z', true); ?></published>
		<?php the_category_rss('atom') ?>
		<?php if ( apply_filters( 'podlove_feed_show_summary', false ) ): ?>
			<summary type="<?php html_type_rss(); ?>"><![CDATA[<?php the_excerpt_rss(); ?>]]></summary>
		<?php endif; ?>
<?php if ( !get_option('rss_use_excerpt') ) : ?>
		<content type="<?php html_type_rss(); ?>" xml:base="<?php the_permalink_rss() ?>"><![CDATA[<?php the_content_feed('atom') ?>]]></content>
<?php endif; ?>
<?php
// don't use wordpress function. do it by hand!
atom_enclosure();
?>
<?php do_action('atom_entry'); ?>
<?php if ( apply_filters( 'podlove_feed_show_comments_data', true ) ): ?>
	<link rel="replies" type="text/html" href="<?php the_permalink_rss() ?>#comments" thr:count="<?php echo get_comments_number()?>"/>
	<link rel="replies" type="application/atom+xml" href="<?php echo get_post_comments_feed_link(0,'atom') ?>" thr:count="<?php echo get_comments_number()?>"/>
	<thr:total><?php echo get_comments_number()?></thr:total>
<?php endif; ?>
	</entry>
	<?php endwhile ; ?>
</feed>
