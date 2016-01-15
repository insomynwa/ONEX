<ul class="pagination">
<?php if( $attributes['jumlah_page'] > 1): ?>
	<?php for($i = 0; $i < $attributes['jumlah_page']; $i++): ?>
	<li><a class="page-link" id="page_<?php echo ($i+1); ?>"><?php echo ($i+1); ?></a></li>
	<?php endfor; ?>
<?php elseif( $attributes['jumlah_page'] == 1): ?>
	<li><a class="page-link active" id="page_<?php echo ($i+1); ?>" >1</a></li>
<?php endif; ?>
</ul>
<script type="text/javascript">
jQuery(document).ready( function($) {

    var first_selected_page = 1;
    $("a.page-link").css('cursor','pointer');
    $("a#page_"+first_selected_page).parent().addClass('active');
    
    var currentPage = 1;

    window.doLoadList(first_selected_page, '<?php echo $attributes["forlist"]; ?>', '<?php echo $attributes["limit"]; ?>', '<?php echo $attributes["filter"]; ?>', "div#list-area");

    $("a.page-link").click( function() {

        var page = (this.id).split('_').pop();
        if(currentPage != page){
            
            window.doLoadList(page, '<?php echo $attributes["forlist"]; ?>', '<?php echo $attributes["limit"]; ?>', '<?php echo $attributes["filter"]; ?>', "div#list-area");

            $("a#page_"+currentPage).parent().removeClass('active');
            $("a#page_"+page).parent().addClass('active');
            currentPage = page;
        }
    });

});
</script>