<?php require_once(HEADER); ?>
   
    <?php include_once(ACTION_FEEDBACK); ?>
   	
    <h4>
    	<?php echo $foundInfo; ?>
    </h4>
    
    <?php foreach ($pages as $page): ?>
    	<div>
    		<h2>
    			<a href="<?php echo $page['url_show']; ?>">
    				<?php echo $page['title']; ?>
    			</a>
    		</h2>
    		
    		<div>
    			<?php echo $page['body']; ?>
    		</div>
    	</div>
    <?php endforeach; ?>
   
<?php require_once(FOOTER); ?>