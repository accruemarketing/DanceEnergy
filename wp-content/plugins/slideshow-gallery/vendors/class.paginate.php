<?php

class GalleryPaginate extends GalleryPlugin {
	
	/**
	 * DB table name to paginate on
	 *
	 */
	var $table = '';
	
	/**
	 * Fields for SELECT query
	 * Only these fields will be fetched.
	 * Use asterix for all available fields
	 *
	 */
	var $fields = '*';
	
	/**
	 * Current page
	 *
	 */
	var $page = 1;
	
	/**
	 * Records to show per page
	 *
	 */
	var $per_page = 10;
	
	/**
	 * WHERE conditions
	 * This should be an array
	 *
	 */
	var $where = '';
	
	/**
	 * ORDER condition
	 *
	 */
	var $order = array('modified', "DESC");
	
	var $plugin_url = '';
	var $sub = '';
	var $parent = '';
	
	var $allcount = 0;
	var $allRecords = array();
	
	var $pagination = '';
	
	function GalleryPaginate($table = '', $fields = '', $sub = '', $parent = '') {
		$this -> sub = $sub;
		$this -> parentd = $parent;
	
		if (!empty($table)) {
			$this -> table = $table;
		}
		
		if (!empty($fields)) {
			$this -> fields = $fields;
		}
	}
	
	function start_paging($page = '') {
		global $wpdb;
	
		$page = (empty($page)) ? 1 : $page;
	
		if (!empty($page)) {
			$this -> page = $page;
		}
		
		if (!empty($this -> fields)) {
			if (is_array($this -> fields)) {
				$this -> fields = implode(", ", $this -> fields);
			}
		}
		
		$query = "SELECT " . $this -> fields . " FROM `" . $this -> table . "`";
		$countquery = "SELECT COUNT(*) FROM `" . $this -> table . "`";
		
		//check if some conditions where passed.
		if (!empty($this -> where)) {
			//append the "WHERE" command to the query
			$query .= " WHERE";
			$countquery .= " WHERE";
			$c = 1;
			
			foreach ($this -> where as $key => $val) {
				if (!empty($val) && is_array($val)) {
					$k = 1;
				
					foreach ($val as $vkey => $vval) {
						if (preg_match("/LIKE/si", $val)) {
							$query .= " `" . $key . "` " . $vval . "";	
							$countquery .= " `" . $key . "` " . $vval . "";
						} elseif (preg_match("/SE (.*)/si", $vval, $vmatches)) {
							if (!empty($vmatches[1])) {
								$query .= " `" . $key . "` <= " . $vmatches[1] . "";
								$countquery .= " `" . $key . "` <= " . $vmatches[1] . "";;
							}
						} elseif (preg_match("/LE (.*)/si", $vval, $vmatches)) {
							if (!empty($vmatches[1])) {
								$query .= " `" . $key . "` >= " . $vmatches[1] . "";
								$countquery .= " `" . $key . "` >= " . $vmatches[1] . "";
							}
						} else {
							$query .= " `" . $key . "` = '" . $vval . "'";
							$countquery .= " `" . $key . "` = '" . $vval . "'";
						}
						
						if ($k < count($val)) {
							$query .= " AND";
							$countquery .= " AND";
						}
						
						$k++;
						$vmatches = false;
					}
				} else {
					if (preg_match("/LIKE/si", $val)) {
						$query .= " `" . $key . "` " . $val . "";	
						$countquery .= " `" . $key . "` " . $val . "";
					} elseif (preg_match("/SE (.*)/si", $val, $vmatches)) {
						if (!empty($vmatches[1])) {
							$query .= " `" . $key . "` <= " . $vmatches[1] . "";
							$countquery .= " `" . $key . "` <= " . $vmatches[1] . "";
						}
					} elseif (preg_match("/LE (.*)/si", $val, $vmatches)) {
						if (!empty($vmatches[1])) {
							$query .= " `" . $key . "` >= " . $vmatches[1] . "";
							$countquery .= " `" . $key . "` >= " . $vmatches[1] . "";
						}
					} else {
						$query .= " `" . $key . "` = '" . $val . "'";
						$countquery .= " `" . $key . "` = '" . $val . "'";
					}
					
					if ($c < count($this -> where)) {
						$query .= " AND";
						$countquery .= " AND";
					}
					
					$c++;
					$vmatches = false;
				}
			}
		}
		
		$r = 1;
		
		$this -> doberecords();
		list($osortby, $osort) = $this -> order;
		$query .= " ORDER BY `" . $osortby . "` " . $osort . " LIMIT " . $this -> begRecord . " , " . $this -> per_page . ";";
		//echo $query;
		
		$query_hash = md5($query);
		if ($oc_records = wp_cache_get($query_hash, 'slideshowgallery')) {
			$records = $oc_records;
		} else {
			$records = $wpdb -> get_results($query);
			wp_cache_set($query_hash, $records, 'slideshowgallery', 0);	
		}
		
		$records_count = count($records);
		$allRecordsCount = $this -> allcount = $wpdb -> get_var($countquery);
		$totalpagescount = ceil($this -> allcount / $this -> per_page);
		
		$pageparam = (!empty($this -> sub) && $this -> sub == "N") ? '' : 'page=' . $this -> pre . $this -> sub . '&amp;';
		$pageparam = '';
		$search = (empty($this -> searchterm)) ? '' : '&amp;' . $this -> pre . 'searchterm=' . urlencode($this -> searchterm);
		
		if (empty($this -> url_page)) {
			$this -> url_page = $this -> sub;	
		}
		
		list($ofield, $odir) = $this -> order;
		
		if (count($records) < $allRecordsCount) {	
			
			$p = 1;
			$k = 1;
			$n = $this -> page;
			$search = (empty($this -> searchterm)) ? '' : '&' . $this -> pre . 'searchterm=' . urlencode($this -> searchterm);
			$orderby = (empty($ofield)) ? '' : '&orderby=' . $ofield;
			$order = (empty($odir)) ? '' : '&order=' . strtolower($odir);
			$this -> pagination .= '<span class="displaying-num">' . sprintf(__('%s items', $this -> plugin_name), $this -> allcount) . '</span>';
			$this -> pagination .= '<span class="pagination-links">';
			$this -> pagination .= '<a href="?page=' . $this -> url_page . '&amp;' . $this -> pre . 'page=1' . $search . $orderby . $order . $this -> after . '" class="first-page' . (($this -> page == 1) ? ' disabled" onclick="return false;' : '') . '">&laquo;</a>';
			$this -> pagination .= '<a class="prev-page' . (($this -> page == 1) ? ' disabled" onclick="return false;' : '') . '" href="?page=' . $this -> url_page . '&amp;' . $this -> pre . 'page=' . ($this -> page - 1) . $search . $orderby . $order . $this -> after . '" title="' . __('Previous Page', $this -> plugin_name) . '">&#8249;</a>';
			$this -> pagination .= '<span class="paging-input">';
			$this -> pagination .= '<input class="newsletters-paged-input current-page" type="text" name="paged" id="paged-input" value="' . $this -> page . '" size="1"> ';
			$this -> pagination .= __('of', $this -> plugin_name); 
			$this -> pagination .= ' <span class="total-pages">' . $totalpagescount . '</span>';
			$this -> pagination .= '</span>';
			$this -> pagination .= '<a class="next-page' . (($this -> page == $totalpagescount) ? ' disabled" onclick="return false;' : '') . '" href="?page=' . $this -> url_page . '&amp;' . $this -> pre . 'page=' . ($this -> page + 1) . $search . $orderby . $order . $this -> after . '" title="' . __('Next Page', $this -> plugin_name) . '">&#8250;</a>';
			$this -> pagination .= '<a href="?page=' . $this -> url_page . '&amp;' . $this -> pre . 'page=' . $totalpagescount . $search . $orderby . $order . $this -> after . '" class="last-page' . (($this -> page == $totalpagescount) ? ' disabled" onclick="return false;' : '') . '">&raquo;</a>';
			$this -> pagination .= '</span>';
			
			ob_start();
			
			?>
			
			<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('.newsletters-paged-input').keypress(function(e) {
					code = (e.keyCode ? e.keyCode : e.which);
		            if (code == 13) {
		            	window.location = '?page=<?php echo $this -> url_page; ?>&<?php echo $this -> pre; ?>page=' + jQuery(this).val() + '<?php echo $search . $orderby . $order . $this -> after; ?>';
		            	e.preventDefault();
		            }
				});
			});
			</script>
			
			<?php
			
			$script = ob_get_clean();
			$this -> pagination .= $script;
		}
		
		return $records;
	}
	
	function doberecords() {
		if ($this -> page > 1) {
			$this -> begRecord = (($this -> page * $this -> per_page) - ($this -> per_page));
		} else {
			$this -> begRecord = 0;
		}
		
		$this -> endRecord = $this -> begRecord + $this -> per_page;
	}
}

?>