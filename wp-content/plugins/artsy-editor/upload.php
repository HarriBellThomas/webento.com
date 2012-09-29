<?php
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

/**
 * The upload AJAX callback
 *
 * @since 0.6
**/

//	Detect AJAX in PHP
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') die('Why are you here?');

//	Load up WordPress
require_once('../../../wp-load.php');
require_once('../../../wp-admin/includes/admin.php');

//	Checking nonce
if (!isset($_GET['_wpnonce'])) die(json_encode(array('error' => 'no-nonce')));
if (! wp_verify_nonce($_GET['_wpnonce'], 'artsy-action')) 
	die(json_encode(array('error' => 'invalid-nonce')));

//	Checking capabilities
if (!is_user_logged_in() OR ! current_user_can('upload_files'))
	die(json_encode(array('error' => 'invalid-user-creds')));

//	Get the filetype that was uploaded
$filetype = wp_check_filetype($_FILES['artsy_file']['name']);

//	If it's an image
if ($filetype['type'] == 'image/gif' || $filetype['type'] == 'image/jpeg' || $filetype['type'] == 'image/png')
{
	$id = media_handle_upload('artsy_file', $_REQUEST['post_id']);
	unset($_FILES);

	if (isset($error) && is_wp_error($error))
	{
		$data = array('error' => $error->get_error_message());
	}
	else
	{
		$post_meta = wp_get_attachment_metadata($id);
		$post_parent = get_post_ancestors($id);
		$data = array(
				'error' => '',
				'id' => $id,
				'post_parent' => $post_parent[0],
				'url' => wp_get_attachment_url($id),
				'height' => $post_meta['height'],
				'width' => $post_meta['width'],
				'title' => get_the_title($id)
		);
	}
}

else {

	$data = array(
				'error' => 'filetype',
				'filetype' => $filetype['ext']
	);
}
echo json_encode($data);

/* End of file */