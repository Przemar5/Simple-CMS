    	<nav role="navigation">
    		<div class="navbar">
    			<a href="<?php echo HOME; ?>" class="navbar__brand">
    				<?php echo APP_NAME; ?>
    			</a>
    			
    			<ul class="navbar__list">
    				<?php foreach(NAVBAR_ITEMS as $name => $url): ?>
    				<li class="navbar__item">
    					<a href="<?php echo $url; ?>" class="navbar__link">
    						<?php echo $name; ?>
    					</a>
    				</li>
    				<?php endforeach; ?>
    			</ul>
    			<form action="<?php echo BASE_URL; ?>" class="navbar__search">
    				<input type="search" name="search" class="navbar__search-text" placeholder="Search for...">
    				<input type="submit" class="navbar__search-btn" value="Search">
    			</form>
    		</div>
    	</nav>
    	
<style>
.wrapper {
	/*background-color: red;*/
}

/* Navbar */
.navbar {
	display: flex;
    justify-content: flex-start;
    align-items: center;
	position: fixed;
	top: 0;
	width: 100%;
    height: 4.5rem;
    padding: 0 1.5rem;
    background-color: #333;
    color: #e6e6e6;
    font-size: 2rem;
}

.navbar a {
    color: inherit;
    text-decoration: none;
}

.navbar a:visited {
    color: inherit;
}

.navbar a:focus,
.navbar a:hover {
    color: #fff;
}

.navbar input {
    font-size: inherit;
    font-family: inherit;
}
	
.navbar > ul {
    display: flex;
    justify-content: space-between;
    align-self: center;
}

.navbar__item {
    margin-left: 5rem;
    display: flex;
    justify-content: center;
}

.navbar__search {
    margin-left: auto;
}



</style>