<h1>Welcome to our provisioned Wordpress install!</h1>
<p>This is a basic danceenergy install it's missing the wp-config and the .sql to import for security reasons</p>

<h3>Prerequisites</h3>
<p>you are going to need at the very least:</p>
<a href="https://www.virtualbox.org/">Virtualbox</a>
<a href="https://www.vagrantup.com/downloads.html">Vagrant</a>

<h3>Installation</h3>
<p>After you have installed the above open up your Terminal and CD into a empty directory:</p>
<pre>
	<code>
		cd /PATH/TO/DIRECTORY
	</code>
</pre>
<p>Next grab the repo from git hub like so: </p>
<pre>
	<code>
		git clone git@github.com:accruemarketing/DanceEnergy.git
	</code>
</pre>
<p>Then you are going to need to place the provided .sql file into the following directory (the directory will not exist you will have to create it):</p>
<pre>
	<code>
   		/PATH/TO/LOCAL/REPOE/provisioning/mysql/
	</code>
</pre>
<p>Place the provided <code>wp-config.php</code> file in the root of your repo.</p>
<p>Back to your command line  you now need to run the command <code>vagrant up</code> then <code>vagrant provision</code></p>
<p>after this we are done!! you should be able to go to http://localhost.localdomain:8080/ in your broswer and you should see our new local dancenery site!</p>