<?php ?>
<div id="content">
<div id="left-div">
<span class="single-entry-titles" style="margin-top: 18px;"> </span>
<div class="post-wrapper post" style="padding:10px;">
  <div style="clear: both;"></div>
  <br />
  <br />
  <?php
    for ($i = 1; $i <= count($reagents); $i++) : 
      $reagent = $reagents[$i];
      // probably want to add getLinkHtml and getImageHtml to reagent model
      $reagent_link_tag = "<a href='reagent/" . 
         $reagent->id . "-" . $reagent->name . "' " .
         "title='" . $reagent->name . "'>";
      if ( $i % 3 == 0 ) :
  ?>
    <div class='one_third'>
  <?php else: ?>
    <div class='one_third last'>
  <?php endif; ?>

  <h3 style=' color:#F90; text-align: center;'>
    <?php echo $reagent->name; ?>
  </h3>
  <?php echo $reagent_link_tag; ?>
  <img style='width:100px;' 
       src='http://www.duelist101.com/w101_reagents/<?php echo $reagent->image; ?>'
       alt='<?php echo $reagent->name ?>' class='alignright' />
  </a>
  <p>
    <strong style='color:#ffcc00'>Class</strong>: <?php echo $reagent->class->name; ?>
  </p>
  <p>
    <strong style='color:#ffcc00'>Rank</strong>: <?php echo $reagent->rank; ?>
  </p>
  <p>
    <?php echo $reagent_link_tag; ?>    
      Click here to find out more about <?php echo $reagent->name; ?>
    </a>
  </p>
  <p>&nbsp;</p>
  <span style="display:block; clear:both; margin-bottom: 20px;"> </span>
</div>

<?php if ( $i % 3 == 0 ) : ?>
  <div style="clear:both"> </div>
<?php
    endif;
  endfor;
?>

<div style="clear: both;"></div>
</div>
<br />
<br />
	
     <div id="duelist_disqus_wrapper" class="post entry">
          <h2 class="entry-title"><a title="comments">
            Leave A Comment!
          </a></h2>
	
        <div id="disqus_thread"></div>
        <script type="text/javascript">
            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
            var disqus_shortname = 'duelist101'; // required: replace example with your forum shortname
			var disqus_url = 'http://duelist101.com/database/index.php';
			var disqus_title = 'Wizard101 Database Index';

            /* * * DON'T EDIT BELOW THIS LINE * * */
            (function() {
                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
		
	</div>
	
</div>
</div>

