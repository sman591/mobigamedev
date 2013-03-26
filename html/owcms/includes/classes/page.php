<?php

class owcms_page {
	
	public $page_exists = false;
	
	static public $is_owcms_admin = false;
	
	public $output_error_html = true; /* Return error HTML when errors happen (such as $this->trigger_404() ) */
	
	public function __construct($selector = '', $ignore_admin = false, $output_error_html = true) {
		
		$this->output_error_html = $output_error_html;
		
		if ($selector===false)
			return false;
		
		$paths = $this->get_url_paths();

		if (trim(ADMIN_LOCATION, '/') == $paths[0] && $ignore_admin === false) {
			
			$this->is_owcms_admin = true;
			array_shift($paths);
			
		}
		
		if (trim($selector)=='') {
			
			$max_paths = count($paths)-1;
			if ($max_paths < 0)
				$max_paths = 0;
			
			$current = 0;
			
			$slug = '';
			$position = '0';
			
			while ($current <= $max_paths) {
				
				$slug = $paths[$current];
				
				$this_page = new owcms_page('slug:'.$slug);
					
				$parent_slug = $paths[$current-1];
				
				$parent_query = mysql_query("SELECT `id` FROM `".$this->get_pages_table()."` WHERE `slug`='".$slug."' AND `position`='".$position."'");

				if (mysql_num_rows($parent_query)!=1) {
					if ($this->output_error_html === true)
						$this->trigger_404();
					else
						return 404;
				}
				
				$parent_array = mysql_fetch_array($parent_query);
				
				$position = $parent_array['id'];
				
				$current++;
			}
			
			$selector = 'id:'.$position;
		
		}
		
		if (trim($selector)!='') {
		
			$selects = explode(':', $selector);
			
			$selects[0] = trim($selects[0]);
			$selects[1] = mysql_real_escape_string(trim($selects[1]));
			
			switch ($selects[0]) {
				
				case 'id':
					$selector_query = "`id`='$selects[1]'";
				break;
				
				case 'slug':
					$selector_query = "`slug`='$selects[1]'";
				break;
				
				default:
					die('Could not select with identifier "'.$selects[0].'"!');
				break;
				
			} /* switch ($selects[1]) */
		
			$query = mysql_query("SELECT * FROM `".$this->get_pages_table()."` WHERE $selector_query");
		
			if (mysql_num_rows($query)==1) {
			
				$this->array = mysql_fetch_array($query);
				$this->page_exists = true;
			
			}
			else {
				trigger_error("Query returned no or too many pages!", E_USER_NOTICE);
				if ($this->output_error_html === true)
					$this->trigger_404();
				else
					return 404;
			}
		
		}
         
    }
    
    
    private function get_pages_table() {

	    return ($this->is_owcms_admin ? 'owcms_pages' : 'pages');
	    
    }
    
	
	public function details($detail = '', $id = '') {
		
		if ($id != '') {
			
			$temp_page = new owcms_page('id:'.$id);
			
			$array = $temp_page->array;
			
		}
		else
			$array = $this->array;
		
		switch ($detail) {
			case 'name':
				$output .= stripslashes($array['name']);
			break;
			case 'subtitle':
				$output .= stripslashes($array['subtitle']);
			break;
			default:
				$output .= $array[$detail];
			break;
		} /* switch ($detail) */
		
		if (!$array) {
			$output = 'notfound';
		}
		
		return $output;
	
	} /* function details() */
	
	public function is_dynamic() {
		
		/* Returns if the page is requested dynamically (through javascript/ajax) */
		
		return ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' || strpos($_SERVER['REQUEST_URI'], '/server/') !== false);
		
	}
	
	
	public function is_bare() {

		/* Returns true if $page['barePage'] == 1 */
		
		return ($this->details('barePage') == 1);
		
	}
	
	
	private function get_current_url() {
		
		/* From http://webcheatsheet.com/PHP/get_current_page_url.php */
		
		$pageURL = 'http';
		
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	
		$pageURL .= "://";
	
		if ($_SERVER["SERVER_PORT"] != "80") {
	
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	
		} else {
	
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	
		}
		
		return $pageURL;
		
	}
	
	
	public function get_url_paths() {
		
		$paths = explode('/', trim(parse_url($this->get_current_url(), PHP_URL_PATH), '/'));
		
		return $paths;
		
	}
	
	
	public function get_url_query($key = '') {
		
		if (trim($key)=='') {
			
			trigger_error('No query key to get', E_USER_WARNING);
			return false;
			
		}
		
		$queries = explode('&', parse_url($this->get_current_url(), PHP_URL_QUERY));
		
		foreach ($queries as $index => $query) {
			
			$query = explode('=', $query);
			
			if ($query[0] == $key) {
				
				return $query[1];
				
			}
			
		}
		
		/* Return false if no query matching the key given */
		return false;
		
	}
	
	
	public function active($id, $menu) {
		if ($this->details('id')===$id) {
			return 'active';
		}
	}
	
	
	public function content() {
	
		switch ($this->details('specPage')) {
		
		default:
			return $this->details('content');
		break;
		
		} /* switch() */
		
	
	} /* function content(); */
	
	
	public function trigger_404() {
		
		if (!headers_sent()) {
			header("HTTP/1.0 404 Not Found");
		}
		$error = file_get_contents(FILEPATH.'/notfound.html');
		echo $error;
		exit;
		
	}
	
	
	public function get_template_dir_path() {
		
		if ($this->is_owcms_admin) {
			return FILEPATH.'/owcms/admin/includes/templates/'.ADMIN_TEMPLATE;
		}
		else {
			return FILEPATH.'/includes/templates/'.TEMPLATE;
		}
		
	}
	
	public function initialize($return = false) {
		
		if ($return === true)
			ob_start();
		
		if ($this->details('notfound')=='notfound') {
			if ($this->output_error_html === true)
				$this->trigger_404();
			else
				return 404;
		}
		
		$site = new owcms_site;
		$user = new owcms_user;
		$option = new owcms_option;
		
		$title = $option->details('title', 'return');
		
		if ($this->details('position')!='0') {
			if ($this->details('position', $this->details('position'))=='0') {
				$title = $this->details('name', $this->details('position')) . ' - '. $title;
			}
			elseif ($this->details('position', $this->details('position', $this->details('position')))=='0') {
				$title = $this->details('name', $this->details('position', $this->details('position'))).' - '.$this->details('name', $this->details('position')) . ' - '. $title;;
			}
		}
		
		if ($this->details('slug'))
			$title = $this->details('name') . ' - '.$title;
		
		/* ***** CUSTOMIZED FOR DYNAMIC PAGES ***** */
		
		if(!$this->is_dynamic()) {
			
			require $this->get_template_dir_path().'/header.php';
		
		}
		else {
			
		}
		
		echo '<div id="guts">
		<div class="row-fluid">';
		
		/* ***** END ***** */
		
		/* ***** CUSTOMIZED FOR ADMIN BROWSER SUPPORT ***** */
		if ($this->details('slug')=='admin'||$this->details('slug', $this->details('parent'))=='admin') {
		
			echo '<!--[if lt IE 9]>
				<script type="text/javascript">
				window.location = "/siteadmin/ie.html";
				</script>
			<![endif]-->';
			
		}
		/* ***** END ***** */
		
		if ($this->details('template')) {
		
			$template_file = $this->get_template_dir_path().'/'.$this->details('template').'.php';
			
			if (file_exists($template_file)) {
				
				require $this->get_template_dir_path().'/'.$this->details('template').'.php';
				
			}
			else {
		
				trigger_error("Template ".$template_file." couldn't be found! Defaulting to normal page", E_USER_ERROR);
				
				require $this->get_template_dir_path().'/page.php';
			
			}
		
		}
		else { require $this->get_template_dir_path().'/page.php'; }
		
		
		/* ***** CUSTOMIZED FOR DYNAMIC PAGES ***** */
		
		echo '</div><!-- .row -->
		</div><!-- #guts -->';
		
		if(!$this->is_dynamic()) {

			require $this->get_template_dir_path().'/footer.php';
		
		}
		
		/* ***** END ***** */
		
		if ($return === true) {
			$page_html = ob_get_contents();
			ob_end_clean();
			return $page_html;
		}
		
	}
	
	
	public function getPages($limit = null,$sort = null) {
		global $db;
		$query = $db->prepare("SELECT * FROM `pages` ORDER BY `position` ASC");
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	
	public function getMenu($content_function, $tree = null, $parent = 0, $depth = -1, $selected_id = '') {
		if (!isset($tree))
			$tree = $this->getPages();
			
		$depth++;
		
		$tree2 = array();
		foreach($tree as $i => $item) {
			if($item['position'] == $parent) {
				
				$content_function($item, $depth, $selected_id);
				
				$tree2[$item['id']] = $item;
				$tree2[$item['id']]['submenu'] = $this->getMenu($content_function, $tree, $item['id'], $depth, $selected_id);
			}
		}
		
		return $tree2;
	}
	
	
} /* class page */

?>