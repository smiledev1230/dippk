<style>
    #content_panel{
        padding-left:20px;
        width:96%;
    }
</style>

<div id="main">
    <div id="demos">
        <table cellspacing="0" cellpadding="0" border="0"><tr><td>
          <?php /*?><div class="nav">
                <a id="prev1" href="#">
                    <img src="img/carousel_left.png" id="left" />
                </a>
                <a id="next1" href="#">
                    <img src="img/carousel_right.png" id="right"  />
                </a>
            </div><?php */?>
          </div>
          
    <div id="sect_header">
        <div class="sect_title fleft">Welcome to <?=$section_name?></div>
        <div class="sect_name fright"></div>
        <div class="fclear"></div>
    </div>
          <div style="display:none;">
            <pre style="visibility:hidden"><code class="mix">$('#s1').cycle({
                    fx:     'scrollHorz',
                    prev:   '#prev1',
                    next:   '#next1',
                    random: 1,
                    pause: true, 
                    timeout: 4000
                });</code>
            </pre>    
          </div>
        </td></tr></table>
    </div>
    
    <? include 'inc/modules/categories-section.php'; ?>
	<div id="content_panel" class="fleft">
    	<?
		if( $req['view'] ) {
			if( $category_view[$req['view']] == 'default' ) {
				$view = 'documents';
			} elseif( $category_view[$req['view']] ) {
				$view = $category_view[$req['view']];
			} else {
				$view = $req['view'];
			}
		} else {
			$view = 'featured';
        }
		include 'inc/views/view.' . $view . '.php';
		?>
    </div>
    <div class="fclear"></div>
</div>