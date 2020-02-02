<?php require_once(HEADER); ?>
   
   	<?php include_once(ACTION_FEEDBACK); ?>
   
    <h1>Contact Us</h1>
    
    <form action="<?php echo SEND; ?>" method="post">
    	<label for="name">
    		Your Name
    		<input type="text" name="name" id="name" placeholder="Name here">
    	</label>
       	<?php displayError($errors['name']); ?>
       	
       	<br>
       	
    	<label for="email">
    		Your email
    		<input type="email" name="email" id="email" placeholder="Email">
    	</label>
       	<?php displayError($errors['email']); ?>

        <br>
    	
    	<label for="subject">
    		Subject
    		<input type="text" name="subject" id="subject" placeholder="Subject">
		</label>
       	<?php displayError($errors['subject']); ?>

        <br>
    	
    	<label for="message">
    		Message
    		<textarea name="message" id="message" cols="30" rows="10" placeholder="Your message..."></textarea>
		</label>
       	<?php displayError($errors['message']); ?>

        <br>
    	
    	<input type="submit" value="Send">
    </form>
    
<?php require_once(FOOTER); ?>