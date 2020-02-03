<?php require_once(HEADER); ?>
   
   	<?php include_once(ACTION_FEEDBACK); ?>
   
    <h1>Contact Us</h1>
    
    <form action="<?php echo SEND; ?>" method="post">
    	<label for="name">
    		Your Name
    		<input type="text" name="name" id="name" placeholder="Name here" value="<?php submitted_data('name'); ?>">
    	</label>
       	<?php display_error('name'); ?>
       	
       	<br>
       	
    	<label for="email">
    		Your email
    		<input type="email" name="email" id="email" placeholder="Email" value="<?php submitted_data('email'); ?>">
    	</label>
       	<?php display_error('email'); ?>

        <br>
    	
    	<label for="subject">
    		Subject
    		<input type="text" name="subject" id="subject" placeholder="Subject" value="<?php submitted_data('subject'); ?>">
		</label>
       	<?php display_error('subject'); ?>

        <br>
    	
    	<label for="message">
    		Message
    		<textarea name="message" id="message" cols="30" rows="10" placeholder="Your message..."><?php submitted_data('message'); ?></textarea>
		</label>
       	<?php display_error('message'); ?>

        <br>
    	
    	<input type="submit" value="Send">
    </form>
    
<?php require_once(FOOTER); ?>